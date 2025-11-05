<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreasuryTranslation extends Model
{
    protected $table = 'treasury_translations';

    protected $fillable = [
        'treasury_id',
        'locale',
        'name',
    ];

    public $timestamps = true;
}

