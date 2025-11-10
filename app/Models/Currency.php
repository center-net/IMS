<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;

class Currency extends Model
{
    use Translatable, Auditable;

    protected $table = 'currencies';

    /** @var list<string> */
    public $translatedAttributes = ['name'];

    /**
     * Bind the translation model explicitly.
     */
    public $translationModel = CurrencyTranslation::class;

    protected $fillable = [
        'code',
        'symbol',
    ];
}
