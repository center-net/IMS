<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;

class Category extends Model
{
    use Translatable, Auditable;

    protected $table = 'categories';

    /** @var list<string> */
    public $translatedAttributes = ['name'];

    public $translationModel = CategoryTranslation::class;

    protected $fillable = [
        'code',
        'parent_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Category $cat) {
            if (empty($cat->code)) {
                $cat->code = self::generateUniqueCode();
            }
        });

        static::saving(function (Category $cat) {
            if (empty($cat->code)) {
                $cat->code = self::generateUniqueCode();
            }
        });
    }

    private static function generateUniqueCode(): string
    {
        do {
            $candidate = 'CAT-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::query()->where('code', $candidate)->exists());
        return $candidate;
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
