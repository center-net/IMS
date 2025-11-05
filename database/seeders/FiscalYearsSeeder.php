<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FiscalYear;
use Illuminate\Support\Carbon;

class FiscalYearsSeeder extends Seeder
{

    public function run(): void
    {
        // Seed current and next fiscal years as examples
        $currentYear = Carbon::now()->year;
        $years = [$currentYear, $currentYear + 1];

        foreach ($years as $year) {
            $start = Carbon::create($year, 1, 1)->toDateString();
            $end = Carbon::create($year, 1, 1)->addYear()->toDateString();
            $fy = FiscalYear::firstOrNew(['year' => $year]);
            $fy->start_date = $start;
            $fy->end_date = $end;
            // Ensure code is set to satisfy NOT NULL constraint
            if (empty($fy->code)) {
                $fy->code = 'FY-' . $year . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            }
            $fy->translateOrNew('ar')->name = 'السنة المالية ' . $year;
            $fy->translateOrNew('en')->name = 'Fiscal Year ' . $year;
            $fy->save();
        }
    }
}
