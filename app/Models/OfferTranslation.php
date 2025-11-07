<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferTranslation extends Model
{
    protected $table = 'offer_translations';

    protected $fillable = [
        'offer_id',
        'locale',
        'name',
    ];

    public $timestamps = true;
}

