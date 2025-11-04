<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;

class Country extends Model
{
    use Translatable, Auditable;

    protected $table = 'countries';

    /** @var list<string> */
    public $translatedAttributes = ['name'];

    /**
     * Bind the translation model explicitly.
     */
    public $translationModel = CountryTranslation::class;

    protected $fillable = [
        'iso_code',
        'national_number',
    ];
}
