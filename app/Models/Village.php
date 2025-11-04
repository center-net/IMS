<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;

class Village extends Model
{
    use Translatable, Auditable;

    protected $table = 'villages';

    /** @var list<string> */
    public $translatedAttributes = ['name'];

    public $translationModel = VillageTranslation::class;

    protected $fillable = [
        'city_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
