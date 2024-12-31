<?php

namespace App\Http\Controllers;

use App\AgentStockReturn;
use App\AgentTemporalStock;
use App\SalesCommissionAgent;
use App\Utils\DeliveryUtil;
use App\VariationLocationDetails;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AgentTemporalStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if (!auth()->user()->can('delivery.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {

            $sales_commission_agent_id = SalesCommissionAgent::where('user_id', session()->get('user.id'))->first();
            $sales_commission_agent_id = $sales_commission_agent_id ? $sales_commission_agent_id->id : null;

            $temporal_stocks = AgentTemporalStock::join('products', 'products.id', '=', 'agent_temporal_stocks.product_id')
                ->join('variations', 'variations.id', '=', 'agent_temporal_stocks.variation_id')
                ->join('business_locations', 'business_locations.id', '=', 'agent_temporal_stocks.location_id')
                ->join('sales_commission_agents', 'sales_commission_agents.id', '=', 'agent_temporal_stocks.sales_commission_agent_id')
                ->join('users', 'users.id', '=', 'sales_commission_agents.user_id')
                ->select(
                    'products.name as product_name',
                    'agent_temporal_stocks.quantity',
                    DB::raw('CONCAT(users.first_name, " ", users.last_name) as agent_name'),
                    'business_locations.name as location_name'
                    );

            if (!empty($sales_commission_agent_id)) {
                $temporal_stocks->where('agent_temporal_stocks.sales_commission_agent_id', $sales_commission_agent_id);
            }
            

            return DataTables::of($temporal_stocks)
                    ->rawColumns([4])
                    ->make(false);
        }

        return view('delivery.temporal_stock');
    }

    public function indexReturn() {
        if (!auth()->user()->can('return.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {

            $sales_commission_agent_id = SalesCommissionAgent::where('user_id', session()->get('user.id'))->first();
            $sales_commission_agent_id = $sales_commission_agent_id ? $sales_commission_agent_id->id : null;

            $temporal_stocks = AgentStockReturn::join('business_locations', 'business_locations.id', '=', 'agent_stock_returns.location_id')
                ->select(
                    'agent_stock_returns.return_number',
                    // sum all quantity of the same return number
                    DB::raw('SUM(agent_stock_returns.quantity) as returned_quantity'),
                    'business_locations.name as location_name',
                    'agent_stock_returns.id',
                )->groupBy('agent_stock_returns.return_number');

            if (!empty($sales_commission_agent_id)) {
                $temporal_stocks->where('agent_stock_returns.agent_id', $sales_commission_agent_id);
            }

            return DataTables::of($temporal_stocks)
                    ->editColumn('return_number', function($row) {
                        return 'NDV-'.$row->return_number;
                    })
                    ->addColumn('action', function($row) {
                        return '<a href="'.route('temporalstock.return.show', $row->return_number).'" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-eye-open"></i> '.__("messages.view").'</a>';
                    })
                    ->removeColumn('id')
                    ->rawColumns([3])
                    ->make(false);
        }
    }

    public function createReturn() {
        if (!auth()->user()->can('return.view')) {
            abort(403, 'Unauthorized action.');
        }

        $sales_commission_agent = SalesCommissionAgent::where('user_id', session()->get('user.id'))->first();
        $sales_commission_agent = $sales_commission_agent ? $sales_commission_agent->id : null;

        $temporal_stocks = AgentTemporalStock::where('agent_temporal_stocks.sales_commission_agent_id', $sales_commission_agent)
                ->join('products', 'products.id', '=', 'agent_temporal_stocks.product_id')
                ->join('variations', 'variations.id', '=', 'agent_temporal_stocks.variation_id')
                ->join('business_locations', 'business_locations.id', '=', 'agent_temporal_stocks.location_id')
                ->join('deliveries', 'deliveries.sales_commission_agent_id', '=', 'agent_temporal_stocks.sales_commission_agent_id')
                ->join('delivery_details', 'delivery_details.delivery_id', '=', 'deliveries.id')
                ->join('transactions', 'transactions.id', '=', 'deliveries.transaction_id')
                ->select(
                    'products.name as product_name',
                    'products.id as product_id',
                    'variations.id as variation_id',
                    'business_locations.name as location_name',
                    'business_locations.id as location_id',
                    'agent_temporal_stocks.id',
                    'agent_temporal_stocks.quantity',
                    DB::raw('SUM(CASE WHEN (delivery_details.product_id = agent_temporal_stocks.product_id AND transactions.status = "draft") THEN delivery_details.quantity ELSE 0 END) as occupied_quantity')
                )
                ->groupBy('products.id', 'agent_temporal_stocks.quantity')
                ->get();

        // filter out the products that have no stock
        $temporal_stocks = $temporal_stocks->filter(function($stock) {
            return $stock->quantity > $stock->occupied_quantity;
        });

        return view('delivery.create_return', compact('temporal_stocks', 'sales_commission_agent'));
    }

    public function storeReturn() {

        try {
            $deliveryUtil = new DeliveryUtil();

            $last_return = AgentStockReturn::latest()->first();

            $return_number = $last_return ? (int)($last_return->return_number) + 1 : 1;
            
            foreach(request('product_id') as $key => $product_id) {
                $data = [
                    'product_id' => $product_id,
                    'variation_id' => request('variation_id')[$key],
                    'quantity' => request('quantity')[$key],
                    'agent_id' => request('agent_id'),
                    'location_id' => request('location_id')[$key],
                    'return_number' => $return_number
                ];

                AgentStockReturn::create($data);

                $deliveryUtil->adjustTemporalStock(request('agent_id'), $product_id, request('variation_id')[$key], request('location_id')[$key], request('quantity')[$key], );

                VariationLocationDetails::where('variation_id', request('variation_id')[$key])
                    ->where('location_id', request('location_id')[$key])
                    ->increment('stock_quantity', request('quantity')[$key]);

            }

            return redirect()->back()->with('success', 'Return has been added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the return');
        }
    }

    public function showReturn($return_number) {
        $return = AgentStockReturn::where('return_number', $return_number)
            ->join('products', 'products.id', '=', 'agent_stock_returns.product_id')
            ->join('variations', 'variations.id', '=', 'agent_stock_returns.variation_id')
            ->join('business_locations', 'business_locations.id', '=', 'agent_stock_returns.location_id')
            ->select(
                'products.name as product_name',
                'agent_stock_returns.quantity',
                'business_locations.name as location_name',
            )->get();

        return view('delivery.print_return', compact('return', 'return_number'));
    }

    public function printReturn($return_number) {
            $return = AgentStockReturn::where('return_number', $return_number)
            ->join('products', 'products.id', '=', 'agent_stock_returns.product_id')
            ->join('variations', 'variations.id', '=', 'agent_stock_returns.variation_id')
            ->join('business_locations', 'business_locations.id', '=', 'agent_stock_returns.location_id')
            ->select(
                'products.name as product_name',
                'agent_stock_returns.quantity',
                'business_locations.name as location_name',
            )->get();

        $html_content = view('delivery.print_return', compact('return', 'return_number'))->render();

        return [
            'success' => true,
            'receipt' => [
                'html_content' => $html_content]
        ];
        
    }
}