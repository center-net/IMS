<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;

class City extends Model
{
    use Translatable, Auditable;

    protected $table = 'cities';

    /** @var list<string> */
    public $translatedAttributes = ['name'];

    public $translationModel = CityTranslation::class;

    protected $fillable = [
        'country_id',
        'delivery_price',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}
