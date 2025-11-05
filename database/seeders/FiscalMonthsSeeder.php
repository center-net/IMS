<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FiscalYear;
use App\Models\FiscalMonth;
use Illuminate\Support\Carbon;

class FiscalMonthsSeeder extends Seeder
{
    public function run(): void
    {
        $monthsEn = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        $monthsAr = ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];

        foreach (FiscalYear::all() as $fy) {
            for ($i = 1; $i <= 12; $i++) {
                $start = Carbon::create((int)$fy->year, $i, 1)->toDateString();
                $end = Carbon::create((int)$fy->year, $i, 1)->addMonth()->subDay()->toDateString();

                $existing = FiscalMonth::query()
                    ->where('fiscal_year_id', $fy->id)
                    ->where('start_date', $start)
                    ->first();
                if ($existing) {
                    continue;
                }

                $fm = new FiscalMonth();
                $fm->fiscal_year_id = $fy->id;
                $fm->start_date = $start;
                $fm->end_date = $end;
                $fm->status = 'open';
                // Ensure code is set to satisfy NOT NULL constraint
                $monthCode = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
                $fm->code = 'FM-' . (int)$fy->year . '-' . $monthCode . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                $fm->translateOrNew('en')->name = $monthsEn[$i-1];
                $fm->translateOrNew('ar')->name = $monthsAr[$i-1];
                $fm->save();
            }
        }
    }
}
