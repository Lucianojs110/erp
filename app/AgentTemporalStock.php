<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class AgentTemporalStock extends Model
{
    protected $fillable = [
        'sales_commission_agent_id', 'product_id', 'variation_id', 'location_id',
        'quantity',];



    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

    public function salesCommissionAgent()
    {
        return $this->belongsTo(SalesCommissionAgent::class);
    }

    public function businessLocation()
    {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }
}