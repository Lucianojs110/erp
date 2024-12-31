<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class AgentStockReturn extends Model
{
    protected $table = 'agent_stock_returns';

    protected $fillable = [
        'location_id', 
        'product_id', 
        'variation_id', 
        'quantity', 
        'return_number',
        'agent_id'
    ];

    public function businessLocation()
    {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

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
        return $this->belongsTo(SalesCommissionAgent::class, 'agent_id');
    }
}