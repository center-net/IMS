<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use Database\Seeders\Concerns\TranslatableSeeder;

class CountriesSeeder extends Seeder
{
    use TranslatableSeeder;
    public function run(): void
    {
        $countries = [
            [
                'iso' => 'PS', 'code' => '+970',
                'ar' => 'فلسطين', 'en' => 'Palestine',
            ],
            ['iso' => 'JO', 'code' => '+962', 'ar' => 'الأردن', 'en' => 'Jordan'],
            ['iso' => 'EG', 'code' => '+20',  'ar' => 'مصر', 'en' => 'Egypt'],
            ['iso' => 'SA', 'code' => '+966', 'ar' => 'السعودية', 'en' => 'Saudi Arabia'],
            ['iso' => 'AE', 'code' => '+971', 'ar' => 'الإمارات', 'en' => 'United Arab Emirates'],
            ['iso' => 'QA', 'code' => '+974', 'ar' => 'قطر', 'en' => 'Qatar'],
            ['iso' => 'BH', 'code' => '+973', 'ar' => 'البحرين', 'en' => 'Bahrain'],
            ['iso' => 'KW', 'code' => '+965', 'ar' => 'الكويت', 'en' => 'Kuwait'],
            ['iso' => 'OM', 'code' => '+968', 'ar' => 'عُمان', 'en' => 'Oman'],
            ['iso' => 'IQ', 'code' => '+964', 'ar' => 'العراق', 'en' => 'Iraq'],
            ['iso' => 'SY', 'code' => '+963', 'ar' => 'سوريا', 'en' => 'Syria'],
            ['iso' => 'LB', 'code' => '+961', 'ar' => 'لبنان', 'en' => 'Lebanon'],
            ['iso' => 'YE', 'code' => '+967', 'ar' => 'اليمن', 'en' => 'Yemen'],
            ['iso' => 'MA', 'code' => '+212','ar' => 'المغرب', 'en' => 'Morocco'],
            ['iso' => 'DZ', 'code' => '+213','ar' => 'الجزائر', 'en' => 'Algeria'],
            ['iso' => 'TN', 'code' => '+216','ar' => 'تونس', 'en' => 'Tunisia'],
            ['iso' => 'LY', 'code' => '+218','ar' => 'ليبيا', 'en' => 'Libya'],
            ['iso' => 'SD', 'code' => '+249','ar' => 'السودان', 'en' => 'Sudan'],
            ['iso' => 'MR', 'code' => '+222','ar' => 'موريتانيا', 'en' => 'Mauritania'],
            ['iso' => 'DJ', 'code' => '+253','ar' => 'جيبوتي', 'en' => 'Djibouti'],
            ['iso' => 'SO', 'code' => '+252','ar' => 'الصومال', 'en' => 'Somalia'],
            ['iso' => 'KM', 'code' => '+269','ar' => 'جزر القمر', 'en' => 'Comoros'],
        ];

        foreach ($countries as $c) {
            $this->upsertTranslatable(
                Country::class,
                ['iso_code' => $c['iso']],
                ['national_number' => $c['code']],
                [
                    'ar' => ['name' => $c['ar']],
                    'en' => ['name' => $c['en']],
                ],
            );
        }

        $this->command?->info('تم إدراج فلسطين والدول العربية مع الترجمات.');
    }
}

