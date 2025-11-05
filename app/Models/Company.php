<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;

class Company extends Model
{
    use Translatable, Auditable;

    protected $table = 'companies';

    /** @var list<string> */
    public $translatedAttributes = ['name', 'address'];

    /**
     * Bind the translation model explicitly.
     */
    public $translationModel = CompanyTranslation::class;

    protected $fillable = [
        'phone',
        'tax_percentage',
        'logo',
        'email',
    ];
}

