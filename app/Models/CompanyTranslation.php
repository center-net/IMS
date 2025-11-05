<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyTranslation extends Model
{
    protected $table = 'company_translations';

    protected $fillable = [
        'company_id',
        'locale',
        'name',
        'address',
    ];

    public $timestamps = true;
}

