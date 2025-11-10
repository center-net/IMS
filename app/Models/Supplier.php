<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;

class Supplier extends Model
{
    use Translatable, Auditable;

    protected $table = 'suppliers';

    /** @var list<string> */
    public $translatedAttributes = ['name'];

    /**
     * Bind the translation model explicitly.
     */
    public $translationModel = SupplierTranslation::class;

    protected $fillable = [
        'code',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Supplier $supplier) {
            // Auto-generate unique code if not provided
            if (empty($supplier->code)) {
                $supplier->code = self::generateUniqueCode();
            }
        });
    }

    private static function generateUniqueCode(): string
    {
        do {
            $candidate = 'SUP-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::query()->where('code', $candidate)->exists());
        return $candidate;
    }

    public function card()
    {
        return $this->hasOne(SupplierCard::class);
    }
}
