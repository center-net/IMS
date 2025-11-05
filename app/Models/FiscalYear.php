<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class FiscalYear extends Model
{
    use Translatable, Auditable;

    protected $table = 'fiscal_years';

    /** @var list<string> */
    public $translatedAttributes = ['name'];

    public $translationModel = FiscalYearTranslation::class;

    protected $fillable = [
        'code',
        'year',
        'start_date',
        'end_date',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (FiscalYear $fy) {
            // Auto-generate unique code if not provided
            if (empty($fy->code)) {
                $fy->code = self::generateUniqueCode((int)($fy->year ?? Carbon::now()->year));
            }
            // Default end_date to start_date + 1 year if not set
            if (!empty($fy->start_date) && empty($fy->end_date)) {
                $fy->end_date = Carbon::parse($fy->start_date)->addYear()->subDay()->toDateString();
            }
            // Default status to open on create if unset
            if (empty($fy->status)) {
                $fy->status = 'open';
            }
        });

        static::updating(function (FiscalYear $fy) {
            // Keep code unique; if cleared, regenerate
            if (empty($fy->code)) {
                $fy->code = self::generateUniqueCode((int)($fy->year ?? Carbon::now()->year));
            }
            if (!empty($fy->start_date) && empty($fy->end_date)) {
                $fy->end_date = Carbon::parse($fy->start_date)->addYear()->subDay()->toDateString();
            }
        });
    }

    private static function generateUniqueCode(int $year): string
    {
        do {
            $candidate = 'FY-' . $year . '-' . str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::query()->where('code', $candidate)->exists());
        return $candidate;
    }
}
