<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class BankDetails extends Model
{
    

    protected $fillable = ['contacts_id','cbu','bank','bank_account_number','alias'];

    public function contact()
    {
        return $this->belongsTo(Contact::class,'contacts_id');
    }
}
