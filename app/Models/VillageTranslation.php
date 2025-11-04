<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillageTranslation extends Model
{
    protected $table = 'village_translations';
    protected $fillable = [
        'village_id',
        'locale',
        'name',
    ];
    public $timestamps = true;
}

