<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyTranslation extends Model
{
    protected $table = 'currency_translations';

    protected $fillable = [
        'currency_id',
        'locale',
        'name',
    ];

    public $timestamps = true;
}

