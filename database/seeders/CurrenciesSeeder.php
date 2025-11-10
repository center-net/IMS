<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Services\CurrencyRateService;

class CurrenciesSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            ['code' => 'USD', 'symbol' => '$', 'ar' => 'الدولار الأمريكي', 'en' => 'US Dollar'],
            ['code' => 'EUR', 'symbol' => '€', 'ar' => 'اليورو', 'en' => 'Euro'],
            ['code' => 'ILS', 'symbol' => '₪', 'ar' => 'الشيكل الإسرائيلي', 'en' => 'Israeli Shekel'],
            ['code' => 'JOD', 'symbol' => 'د.أ', 'ar' => 'الدينار الأردني', 'en' => 'Jordanian Dinar'],
            ['code' => 'EGP', 'symbol' => '£', 'ar' => 'الجنيه المصري', 'en' => 'Egyptian Pound'],
        ];

        $rateService = new CurrencyRateService();

        foreach ($samples as $c) {
            $currency = Currency::firstOrNew(['code' => $c['code']]);
            $currency->code = $c['code'];
            $currency->symbol = $c['symbol'];
            $currency->translateOrNew('ar')->name = $c['ar'];
            $currency->translateOrNew('en')->name = $c['en'];
            $currency->save();
        }
        $this->command?->info('تم إدراج/تحديث العملات بدون حفظ سعر الصرف في قاعدة البيانات.');
    }
}
