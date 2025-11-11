<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;

class Item extends Model
{
    use Translatable, Auditable;

    protected $table = 'items';

    /** @var list<string> */
    public $translatedAttributes = ['name'];

    public $translationModel = ItemTranslation::class;

    protected $fillable = [
        'code',
        'category_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Item $item) {
            if (empty($item->code)) {
                $item->code = self::generateUniqueCode();
            }
        });
    }

    private static function generateUniqueCode(): string
    {
        do {
            $candidate = 'ITM-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::query()->where('code', $candidate)->exists());
        return $candidate;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}

