<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Delivery extends Model
{

    use SoftDeletes;
    protected $fillable = [
        'sales_commission_agent_id',
        'business_location_id',
        'contact_id',
        'delivered_at',
        'transaction_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'delivered_at',
    ];

    public function agent()
    {
        return $this->belongsTo(SalesCommissionAgent::class);
    }

    public function businessLocation() {
        return $this->belongsTo(BusinessLocation::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function deliveryDetails()
    {
        return $this->hasMany(DeliveryDetail::class);
    }
}