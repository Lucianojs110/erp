<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CashRegister extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the Cash registers transactions.
     */
    public function cash_register_transactions()
    {
        return $this->hasMany(\App\CashRegisterTransaction::class);
    }

    public static function forDropdown($business_id)
    {
        // concatenate created_at and closed_at columns in one string, pluck it with 'id'
        // and 'name' columns, and return it as an array
        return self::where('business_id', $business_id)
            ->select(
                DB::raw("CONCAT(DATE_FORMAT(created_at, '%d/%m/%Y %H:%i'), ' - ', IFNULL(closed_at, 'Abierta')) as name"),
                'id'
            )
            ->pluck('name', 'id');
    }
}
