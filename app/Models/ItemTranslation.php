<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTranslation extends Model
{
    protected $table = 'item_translations';

    protected $fillable = [
        'item_id',
        'locale',
        'name',
    ];

    public $timestamps = true;
}

