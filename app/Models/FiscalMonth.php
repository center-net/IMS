<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;
use Illuminate\Support\Carbon;

class FiscalMonth extends Model
{
    use Translatable, Auditable;

    protected $table = 'fiscal_months';

    /** @var list<string> */
    public $translatedAttributes = ['name'];

    public $translationModel = FiscalMonthTranslation::class;

    protected $fillable = [
        'fiscal_year_id',
        'code',
        'start_date',
        'end_date',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (FiscalMonth $fm) {
            // Default end_date to start_date + 1 month - 1 day if not set
            if (!empty($fm->start_date) && empty($fm->end_date)) {
                $fm->end_date = Carbon::parse($fm->start_date)->addMonth()->subDay()->toDateString();
            }
            // Auto-generate unique code if not provided: FM-<year>-<month>-XXXX
            if (empty($fm->code)) {
                $year = (int) (FiscalYear::find($fm->fiscal_year_id)?->year ?? Carbon::parse($fm->start_date)->year ?? Carbon::now()->year);
                $month = str_pad((string) Carbon::parse($fm->start_date)->month, 2, '0', STR_PAD_LEFT);
                do {
                    $candidate = 'FM-' . $year . '-' . $month . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                } while (self::query()->where('code', $candidate)->exists());
                $fm->code = $candidate;
            }
        });

        static::updating(function (FiscalMonth $fm) {
            if (!empty($fm->start_date) && empty($fm->end_date)) {
                $fm->end_date = Carbon::parse($fm->start_date)->addMonth()->subDay()->toDateString();
            }
            if (empty($fm->code)) {
                $year = (int) (FiscalYear::find($fm->fiscal_year_id)?->year ?? Carbon::parse($fm->start_date)->year ?? Carbon::now()->year);
                $month = str_pad((string) Carbon::parse($fm->start_date)->month, 2, '0', STR_PAD_LEFT);
                do {
                    $candidate = 'FM-' . $year . '-' . $month . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                } while (self::query()->where('code', $candidate)->exists());
                $fm->code = $candidate;
            }
        });
    }
}

