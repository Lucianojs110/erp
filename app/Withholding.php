<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withholding extends Model
{
    const PERCEPCIONES = 1;
    const RETENCIONES = 2;

    protected $fillable = [
        'name',
        'percentage',
        'business_id',
        'type',
    ];

    /************************************************ Relaciones ************************************************/

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /************************************************ Relaciones ************************************************/
}
