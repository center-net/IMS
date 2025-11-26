<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use Database\Seeders\Concerns\TranslatableSeeder;

class CompaniesSeeder extends Seeder
{
    use TranslatableSeeder;
    public function run(): void
    {
        $this->upsertTranslatable(
            Company::class,
            ['email' => 'info@example.com'],
            [
                'phone' => '+9700000000',
                'tax_percentage' => 0,
                'logo' => null,
            ],
            [
                'ar' => ['name' => 'الشركة الافتراضية', 'address' => 'رام الله - فلسطين'],
                'en' => ['name' => 'Default Company', 'address' => 'Ramallah - Palestine'],
            ],
        );

        $this->command?->info('تم إنشاء/تحديث شركة افتراضية مع ترجمات الاسم والعنوان.');
    }
}
