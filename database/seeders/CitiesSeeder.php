<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\City;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure Palestine exists
        $palestine = Country::where('iso_code', 'PS')->first();
        if (!$palestine) {
            $this->command?->warn('Palestine country not found. Run CountriesSeeder first.');
            return;
        }

        // مدن فلسطين (الضفة والقطاع)
        $coreCities = [
            ['ar' => 'غزة',        'en' => 'Gaza',        'price' => 15],
            ['ar' => 'رفح',        'en' => 'Rafah',       'price' => 15],
            ['ar' => 'خانيونس',    'en' => 'Khan Younis', 'price' => 15],
            ['ar' => 'دير البلح',  'en' => 'Deir al-Balah','price' => 15],
            ['ar' => 'شمال غزة',   'en' => 'North Gaza',  'price' => 15],
            ['ar' => 'نابلس',      'en' => 'Nablus',      'price' => 15],
            ['ar' => 'رام الله',   'en' => 'Ramallah',    'price' => 15],
            ['ar' => 'الخليل',     'en' => 'Hebron',      'price' => 15],
            ['ar' => 'بيت لحم',    'en' => 'Bethlehem',   'price' => 15],
            ['ar' => 'جنين',       'en' => 'Jenin',       'price' => 15],
            ['ar' => 'طولكرم',     'en' => 'Tulkarm',     'price' => 15],
            ['ar' => 'قلقيلية',    'en' => 'Qalqilya',    'price' => 15],
            ['ar' => 'سلفيت',      'en' => 'Salfit',      'price' => 15],
            ['ar' => 'أريحا',      'en' => 'Jericho',     'price' => 15],
            ['ar' => 'طوباس',      'en' => 'Tubas',       'price' => 15],
        ];

        // مدن الداخل (48)
        $inside48 = [
            ['ar' => 'الناصرة',        'en' => 'Nazareth',          'price' => 15],
            ['ar' => 'أم الفحم',       'en' => 'Umm al-Fahm',       'price' => 15],
            ['ar' => 'الطيبة',         'en' => 'Tayibe',            'price' => 15],
            ['ar' => 'قلنسوة',         'en' => 'Qalansuwa',         'price' => 15],
            ['ar' => 'باقة الغربية',    'en' => 'Baqa al-Gharbiyye', 'price' => 15],
            ['ar' => 'الطيرة',         'en' => 'Tira',              'price' => 15],
            ['ar' => 'كفر قاسم',       'en' => 'Kafr Qasim',        'price' => 15],
            ['ar' => 'كفر برا',        'en' => 'Kafr Bara',         'price' => 15],
            ['ar' => 'سخنين',          'en' => 'Sakhnin',           'price' => 15],
            ['ar' => 'عرابة',          'en' => 'Arraba',            'price' => 15],
            ['ar' => 'دير حنا',        'en' => 'Deir Hanna',        'price' => 15],
            ['ar' => 'شفاعمرو',        'en' => 'Shefa-Amr',         'price' => 15],
            ['ar' => 'طمرة',           'en' => 'Tamra',             'price' => 15],
            ['ar' => 'مجد الكروم',     'en' => 'Majd al-Krum',      'price' => 15],
            ['ar' => 'عكا',            'en' => 'Acre',              'price' => 15],
            ['ar' => 'يافا',           'en' => 'Jaffa',             'price' => 15],
            ['ar' => 'اللُّد',          'en' => 'Lod',               'price' => 15],
            ['ar' => 'الرملة',         'en' => 'Ramla',             'price' => 15],
            ['ar' => 'حيفا',           'en' => 'Haifa',             'price' => 15],
            ['ar' => 'رهط',            'en' => 'Rahat',             'price' => 15],
            ['ar' => 'حورة',           'en' => 'Hura',              'price' => 15],
            ['ar' => 'اللقية',         'en' => 'Lakiya',            'price' => 15],
            ['ar' => 'شقيب السلام',    'en' => 'Shaqib al-Salam',   'price' => 15],
            ['ar' => 'عرعرة النقب',    'en' => "Ar'arat an-Naqab", 'price' => 15],
            ['ar' => 'بيت جن',         'en' => 'Beit Jann',         'price' => 15],
            ['ar' => 'الجش',           'en' => 'Jish',              'price' => 15],
        ];

        // مدن الجولان المحتل
        $golan = [
            ['ar' => 'مجدل شمس',   'en' => 'Majdal Shams', 'price' => 15],
            ['ar' => 'مسعدة',      'en' => 'Mas’ade',      'price' => 15],
            ['ar' => 'بقعاتا',     'en' => 'Buq’ata',      'price' => 15],
            ['ar' => 'عين قينيا',  'en' => 'Ein Qiniyye',  'price' => 15],
        ];

        $all = array_merge($coreCities, $inside48, $golan);

        foreach ($all as $c) {
            // تفادي التكرار عبر البحث في الترجمات العربية أو الإنجليزية
            $existing = City::whereTranslation('name', $c['ar'], 'ar')
                ->orWhereTranslation('name', $c['en'], 'en')
                ->first();

            if ($existing) {
                // تحديث السعر فقط إن رغبت
                $existing->delivery_price = $c['price'];
                $existing->country_id = $palestine->id;
                $existing->save();
                continue;
            }

            $city = new City();
            $city->country_id = $palestine->id;
            $city->delivery_price = $c['price'];
            $city->translateOrNew('ar')->name = $c['ar'];
            $city->translateOrNew('en')->name = $c['en'];
            $city->save();
        }

        $this->command?->info('تم إدراج/تحديث مدن فلسطين بما يشمل مدن الداخل والجولان.');
    }
}
