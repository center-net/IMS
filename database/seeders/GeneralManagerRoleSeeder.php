<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class GeneralManagerRoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arName = 'المدير العام';
        $enName = 'General Manager';
        $slug = $this->makeSlug($enName ?: $arName);

        // إنشاء أو جلب الدور "المدير العام" مع اشتقاق المعرف من الاسم الظاهر
        $role = Role::firstOrCreate([
            'name' => $slug,
            'guard_name' => 'web',
        ]);

        // إضافة/تحديث الترجمات للاسم الظاهر
        $role->translateOrNew('ar')->display_name = $arName;
        $role->translateOrNew('en')->display_name = $enName;
        $role->save();

        // منح جميع الصلاحيات لهذا الدور
        $permissions = Permission::all();
        $role->syncPermissions($permissions);

        $this->command?->info('تم إنشاء/تحديث دور المدير العام ومنحه جميع الصلاحيات.');
    }

    private function makeSlug(string $input): string
    {
        $arabicMap = [
            'أ' => 'a', 'ا' => 'a', 'إ' => 'i', 'آ' => 'a', 'ب' => 'b', 'ت' => 't', 'ث' => 'th',
            'ج' => 'j', 'ح' => 'h', 'خ' => 'kh', 'د' => 'd', 'ذ' => 'dh', 'ر' => 'r', 'ز' => 'z',
            'س' => 's', 'ش' => 'sh', 'ص' => 's', 'ض' => 'd', 'ط' => 't', 'ظ' => 'z', 'ع' => 'a',
            'غ' => 'gh', 'ف' => 'f', 'ق' => 'q', 'ك' => 'k', 'ل' => 'l', 'م' => 'm', 'ن' => 'n',
            'ه' => 'h', 'و' => 'w', 'ي' => 'y', 'ى' => 'a', 'ة' => 'h', 'ء' => '', 'ؤ' => 'w', 'ئ' => 'y',
        ];
        $normalized = strtr($input, $arabicMap);
        $normalized = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $normalized) ?: $normalized;
        $normalized = strtolower($normalized);
        $normalized = preg_replace('/[^a-z0-9]+/i', '-', $normalized);
        $normalized = trim($normalized, '-');
        return $normalized ?: 'role';
    }
}
