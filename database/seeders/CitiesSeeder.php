<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\City;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {
        // تأكد من وجود دولة فلسطين
        $palestine = Country::where('iso_code', 'PS')->first();
        if (!$palestine) {
            $this->command?->warn('Palestine country not found. Run CountriesSeeder first.');
            return;
        }

        // قائمة المدن/المحافظات مع ترجمات متعددة اللغات (ar/en)
        $cities = [
            ['ar' => 'ابو غوش',        'en' => 'Abu Ghosh'],
            ['ar' => 'اريحا',          'en' => 'Jericho'],
            ['ar' => 'الجليل',         'en' => 'Galilee'],
            ['ar' => 'الجولان',         'en' => 'Golan'],
            ['ar' => 'الخليل',         'en' => 'Hebron'],
            ['ar' => 'الداخل',         'en' => 'Al-Dakhil'],
            ['ar' => 'القدس',          'en' => 'Jerusalem'],
            ['ar' => 'المثلث',         'en' => 'Triangle'],
            ['ar' => 'المركز',         'en' => 'Center'],
            ['ar' => 'بئر السبع',       'en' => 'Beersheba'],
            ['ar' => 'بيت لحم',        'en' => 'Bethlehem'],
            ['ar' => 'جنين',           'en' => 'Jenin'],
            ['ar' => 'رام الله والبيرة','en' => 'Ramallah and Al-Bireh'],
            ['ar' => 'سلفيت',          'en' => 'Salfit'],
            ['ar' => 'ضواحي القدس',     'en' => 'Jerusalem Suburbs'],
            ['ar' => 'طوباس',          'en' => 'Tubas'],
            ['ar' => 'طولكرم',         'en' => 'Tulkarm'],
            ['ar' => 'قلقيلية',        'en' => 'Qalqilya'],
            ['ar' => 'منطقة يفنا',      'en' => 'Yavne Area'],
            ['ar' => 'نابلس',          'en' => 'Nablus'],
        ];

        $created = 0; $updated = 0; $updatedTranslations = 0;
        foreach ($cities as $names) {
            $arName = $names['ar'];
            $enName = $names['en'];

            // ابحث داخل فلسطين فقط باستخدام الاسم العربي كمفتاح أساسي لتفادي الازدواج
            $existing = City::where('country_id', $palestine->id)
                ->whereTranslation('name', $arName, 'ar')
                ->first();

            if ($existing) {
                // تأكيد الربط وتحديث السعر الافتراضي
                $existing->country_id = $palestine->id;
                $existing->delivery_price = $existing->delivery_price ?? 0;
                // إضافة/تحديث الترجمة الإنجليزية
                $existing->translateOrNew('en')->name = $enName;
                $existing->save();
                $updated++;
                $updatedTranslations++;
                continue;
            }

            // إنشاء مدينة جديدة مع الترجمات
            $city = new City();
            $city->country_id = $palestine->id;
            $city->delivery_price = 0; // سعر افتراضي
            $city->translateOrNew('ar')->name = $arName;
            $city->translateOrNew('en')->name = $enName;
            $city->save();
            $created++;
        }

        $this->command?->info("تم إدراج/تحديث مدن فلسطين متعددة اللغات. المضاف: {$created}، المحدَّث: {$updated}، ترجمات محدَّثة: {$updatedTranslations}");
    }
}
