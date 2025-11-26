<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;

class Offer extends Model
{
    use Translatable, Auditable;

    protected $table = 'offers';

    /** @var list<string> */
    public $translatedAttributes = ['name'];

    public $translationModel = OfferTranslation::class;

    protected $fillable = [
        'code',
        'price',
        'original_price',
        'start_date',
        'end_date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Offer $offer) {
            if (empty($offer->code)) {
                $offer->code = self::generateUniqueCode();
            }
        });

        static::saving(function (Offer $offer) {
            if (empty($offer->code)) {
                $offer->code = self::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $candidate = 'OF-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::query()->where('code', $candidate)->exists());
        return $candidate;
    }
}
