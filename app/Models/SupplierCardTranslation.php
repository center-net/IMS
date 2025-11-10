<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierCardTranslation extends Model
{
    protected $table = 'supplier_card_translations';

    protected $fillable = [
        'supplier_card_id',
        'locale',
        'name',
        'trade_name',
        'notes',
    ];

    public $timestamps = true;
}

