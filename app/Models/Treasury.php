<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;
use Illuminate\Support\Carbon;

class Treasury extends Model
{
    use Translatable, Auditable;

    protected $table = 'treasuries';

    /** @var list<string> */
    public $translatedAttributes = ['name'];

    public $translationModel = TreasuryTranslation::class;

    protected $fillable = [
        'code',
        'is_main',
        'main_treasury_id',
        'manager_id',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Treasury $tr) {
            // Auto-generate unique code if not provided
            if (empty($tr->code)) {
                $tr->code = self::generateUniqueCode();
            }
            // Default status to open on create if unset
            if (empty($tr->status)) {
                $tr->status = 'open';
            }
        });
    }

    private static function generateUniqueCode(): string
    {
        do {
            $candidate = 'TR-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::query()->where('code', $candidate)->exists());
        return $candidate;
    }

    /**
     * The employee (user) assigned as this treasury's manager.
     */
    public function manager(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }
}
