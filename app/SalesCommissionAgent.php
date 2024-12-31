<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SalesCommissionAgent extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'business_id',
        'user_id',
        'commission_percentage',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deliveries() {
        return $this->hasMany(Delivery::class);
    }
}