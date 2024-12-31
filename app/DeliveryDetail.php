<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class DeliveryDetail extends Model
{
    protected $fillable = [
        'delivery_id',
        'product_id',
        'quantity',
        'variation_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'delivered_at',
    ];

    public function delivery() {
        return $this->belongsTo(Delivery::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function variation() {
        return $this->belongsTo(Variation::class);
    }
}