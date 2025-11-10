<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepresentativeCardTranslation extends Model
{
    protected $table = 'representative_card_translations';

    protected $fillable = [
        'name',
        'notes',
    ];
}

