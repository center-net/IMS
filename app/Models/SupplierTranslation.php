<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierTranslation extends Model
{
    protected $table = 'supplier_translations';

    protected $fillable = [
        'supplier_id',
        'locale',
        'name',
    ];

    public $timestamps = true;
}

