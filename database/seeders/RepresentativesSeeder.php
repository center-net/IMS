<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Representative;

class RepresentativesSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            ['code' => 'SAL001', 'ar' => 'مندوب 1', 'en' => 'Representative 1'],
            ['code' => 'SAL002', 'ar' => 'مندوب 2', 'en' => 'Representative 2'],
            ['code' => 'SAL003', 'ar' => 'مندوب 3', 'en' => 'Representative 3'],
        ];

        foreach ($samples as $s) {
            $rep = Representative::firstOrCreate(['code' => $s['code']], ['code' => $s['code']]);
            $rep->translateOrNew('ar')->name = $s['ar'];
            $rep->translateOrNew('en')->name = $s['en'];
            $rep->save();
        }
    }
}

