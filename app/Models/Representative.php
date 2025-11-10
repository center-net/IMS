<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;

class Representative extends Model
{
    use Translatable, Auditable;

    protected $table = 'representatives';

    /** @var list<string> */
    public $translatedAttributes = ['name'];

    public $translationModel = RepresentativeTranslation::class;

    protected $fillable = [
        'code',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Representative $rep) {
            if (empty($rep->code)) {
                $rep->code = self::generateUniqueCode();
            }
        });
    }

    private static function generateUniqueCode(): string
    {
        do {
            $candidate = 'SAL-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::query()->where('code', $candidate)->exists());
        return $candidate;
    }

    public function card()
    {
        return $this->hasOne(RepresentativeCard::class);
    }
}

