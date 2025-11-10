<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\GeneralManagerRoleSeeder;
use Database\Seeders\CountriesSeeder;
use Database\Seeders\CitiesSeeder;
use Database\Seeders\VillagesSeeder;
use Database\Seeders\CompaniesSeeder;
use Database\Seeders\OffersSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // تأكد من وجود الصلاحيات أولاً
        $this->call(PermissionsSeeder::class);

        // أنشئ/حدّث دور المدير العام ومنحه جميع الصلاحيات
        $this->call(GeneralManagerRoleSeeder::class);

        // (اختياري) إنشاء مستخدمين تجريبيين
        $this->call(UserSeeder::class);

        // إدراج فلسطين والدول العربية (متعدد اللغات)
        $this->call(CountriesSeeder::class);

        // إدراج مدن فلسطين
        $this->call(CitiesSeeder::class);

        // إدراج القرى التابعة للمدن المطلوبة
        $this->call(VillagesSeeder::class);

        // إدراج شركة افتراضية (متعدد اللغات)
        $this->call(CompaniesSeeder::class);

        // إدراج السنوات المالية الافتراضية
        $this->call(FiscalYearsSeeder::class);

        // إدراج الأشهر المالية لكل سنة مالية موجودة
        $this->call(FiscalMonthsSeeder::class);

        // إدراج خزنة افتراضية (الخزائن)
        $this->call(TreasuriesSeeder::class);

        // إدراج عروض تجريبية (متعدد اللغات)
        $this->call(OffersSeeder::class);

        // إدراج العملات المعتمدة (متعدد اللغات)
        $this->call(CurrenciesSeeder::class);

        // إدراج موردين تجريبيين (متعدد اللغات)
        $this->call(SuppliersSeeder::class);

        // إنشاء بطاقات الموردين وربطها بالموردين
        $this->call(SupplierCardsSeeder::class);

        // إدراج مندوبين تجريبيين (متعدد اللغات)
        $this->call(RepresentativesSeeder::class);

        // إنشاء بطاقات المندوبين وربطها بالمندوبين
        $this->call(RepresentativeCardsSeeder::class);
    }
}
