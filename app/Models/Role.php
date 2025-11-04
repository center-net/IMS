<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use Translatable, Auditable;

    /** @var list<string> */
    public $translatedAttributes = ['display_name'];

    /**
     * Bind the translation model explicitly.
     */
    public $translationModel = RoleTranslation::class;
}

