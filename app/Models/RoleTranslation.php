<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleTranslation extends Model
{
    protected $table = 'role_translations';

    protected $fillable = [
        'locale',
        'display_name',
    ];
}

