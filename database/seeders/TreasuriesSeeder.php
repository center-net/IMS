<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Treasury;
use Illuminate\Support\Carbon;

class TreasuriesSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure a single main treasury exists
        $existingMain = Treasury::query()->where('is_main', true)->first();
        if (!$existingMain) {
            $tr = new Treasury();
            // Ensure code is set to satisfy NOT NULL constraint
            $tr->code = 'TR-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $tr->is_main = true;
            $tr->status = 'open';
            $tr->translateOrNew('ar')->name = 'الخزنة الرئيسية';
            $tr->translateOrNew('en')->name = 'Main Treasury';
            $tr->save();
        }
    }
}
