<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use Translatable, Auditable;

    /** @var list<string> */
    public $translatedAttributes = ['display_name'];

    /**
     * Bind the translation model explicitly.
     */
    public $translationModel = PermissionTranslation::class;
}
