<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepresentativeTranslation extends Model
{
    protected $table = 'representative_translations';

    protected $fillable = [
        'name',
    ];
}

