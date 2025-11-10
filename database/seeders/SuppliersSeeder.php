<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SuppliersSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'code' => 'SUP-001',
                'ar' => 'شركة المورد الأول',
                'en' => 'First Supplier Co.',
            ],
            [
                'code' => 'SUP-002',
                'ar' => 'مؤسسة التوريد الثانية',
                'en' => 'Second Supply Ltd.',
            ],
            [
                'code' => 'SUP-003',
                'ar' => 'المورد الثالث',
                'en' => 'Third Supplier',
            ],
        ];

        foreach ($samples as $s) {
            $supplier = Supplier::firstOrNew(['code' => $s['code']]);
            $supplier->code = $s['code'];
            $supplier->translateOrNew('ar')->name = $s['ar'];
            $supplier->translateOrNew('en')->name = $s['en'];
            $supplier->save();
        }

        $this->command?->info('تم إدراج/تحديث موردين تجريبيين مع ترجمات الاسم.');
    }
}

