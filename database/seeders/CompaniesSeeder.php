<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompaniesSeeder extends Seeder
{
    public function run(): void
    {
        // شركة افتراضية
        $company = Company::firstOrCreate([
            'email' => 'info@example.com',
        ], [
            'phone' => '+9700000000',
            'tax_percentage' => 0,
            'logo' => null,
        ]);

        // ترجمات الاسم والعنوان
        $company->translateOrNew('ar')->name = 'الشركة الافتراضية';
        $company->translateOrNew('ar')->address = 'رام الله - فلسطين';
        $company->translateOrNew('en')->name = 'Default Company';
        $company->translateOrNew('en')->address = 'Ramallah - Palestine';
        $company->save();

        $this->command?->info('تم إنشاء/تحديث شركة افتراضية مع ترجمات الاسم والعنوان.');
    }
}

