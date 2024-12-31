<?php

namespace App\Utils;

use App\AgentTemporalStock;
use App\Delivery;
use App\DeliveryDetail;
use App\SalesCommissionAgent;
use App\VariationLocationDetails;

/**
 * Utility class for delivery
 */
class DeliveryUtil extends Util {


    /**
     * Adjusts the stock of a product in a location
     * @param int $sales_agent_id 
     * @param int $product_id
     * @param int $variation_id
     * @param int $location_id
     * @param int $quantity
     */
    public function adjustTemporalStock($sales_agent_id, $product_id, $variation_id, $location_id, $quantity) {
        
        // Adjust the stock of a product in a location
        $agent_temporal_stock = AgentTemporalStock::where('product_id', $product_id)
            ->where('sales_commission_agent_id', $sales_agent_id)
            ->where('variation_id', $variation_id)
            ->where('location_id', $location_id)
            ->first();
        
        if ($agent_temporal_stock) {
            $agent_temporal_stock->quantity -= $quantity;
            $agent_temporal_stock->save();

            if ($agent_temporal_stock->quantity <= 0) {
                $agent_temporal_stock->delete();
            }
        }

        

    }

    /**
     * Check if a delivery exists for a transaction
     * @param int $transaction_id
     * @return bool
     */
    public function hasDelivery($transaction_id) {
        return Delivery::where('transaction_id', $transaction_id)->exists();
    }

    /**
     * Get the agent of a delivery
     * @param int $transaction_id
     * @return SalesCommissionAgent|null
     */
    public function getAgent($transaction_id) {
        $delivery = Delivery::where('transaction_id', $transaction_id)->first();

        if ($delivery) {
            return SalesCommissionAgent::where('id', $delivery->sales_commission_agent_id)->first();
        }

        return null;
    }

    /**
     * Reform the delivery details of a delivery
     * @param int|Delivery $delivery
     * @return void
     */
    public function reformDeliveryDetails($delivery) {
        if (is_numeric($delivery)) {
            $delivery = Delivery::find($delivery);
        }

        if ($delivery) {
            $delivery->deliveryDetails()->delete();
            $transaction = $delivery->transaction;
            $transaction_details = $transaction->sell_lines;

            foreach ($transaction_details as $transaction_detail) {
                $delivery_detail = new DeliveryDetail();
                $delivery_detail->delivery_id = $delivery->id;
                $delivery_detail->product_id = $transaction_detail->product_id;
                $delivery_detail->variation_id = $transaction_detail->variation_id;
                $delivery_detail->quantity = $transaction_detail->quantity;
                $delivery_detail->save();

                $this->adjustTemporalStock($delivery->sales_commission_agent_id, $transaction_detail->product_id, $transaction_detail->variation_id, $transaction->location_id, $transaction_detail->quantity);
            }
        }
    }

}