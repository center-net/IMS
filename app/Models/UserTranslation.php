<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTranslation extends Model
{
    protected $table = 'user_translations';

    protected $fillable = [
        'locale',
        'name',
    ];
}

