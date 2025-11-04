<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // صلاحيات المستخدمين
        $userPermissions = [
            [
                'name' => 'view-users',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'مشاهدة المستخدمين',
                    'en' => 'View Users'
                ]
            ],
            [
                'name' => 'create-users',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'إضافة مستخدم',
                    'en' => 'Create User'
                ]
            ],
            [
                'name' => 'edit-users',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'تعديل مستخدم',
                    'en' => 'Edit User'
                ]
            ],
            [
                'name' => 'delete-users',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'حذف مستخدم',
                    'en' => 'Delete User'
                ]
            ],
        ];

        // صلاحيات الأدوار
        $rolePermissions = [
            [
                'name' => 'view-roles',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'مشاهدة الأدوار',
                    'en' => 'View Roles'
                ]
            ],
            [
                'name' => 'create-roles',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'إضافة دور',
                    'en' => 'Create Role'
                ]
            ],
            [
                'name' => 'edit-roles',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'تعديل دور',
                    'en' => 'Edit Role'
                ]
            ],
            [
                'name' => 'delete-roles',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'حذف دور',
                    'en' => 'Delete Role'
                ]
            ],
        ];

        // صلاحيات الصلاحيات
        $permissionPermissions = [
            [
                'name' => 'view-permissions',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'مشاهدة الصلاحيات',
                    'en' => 'View Permissions'
                ]
            ],
            [
                'name' => 'create-permissions',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'إضافة صلاحية',
                    'en' => 'Create Permission'
                ]
            ],
            [
                'name' => 'edit-permissions',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'تعديل صلاحية',
                    'en' => 'Edit Permission'
                ]
            ],
            [
                'name' => 'delete-permissions',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'حذف صلاحية',
                    'en' => 'Delete Permission'
                ]
            ],
        ];

        // صلاحيات السجلات النظامية
        $logsPermissions = [
            [
                'name' => 'view-logs',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'مشاهدة السجلات',
                    'en' => 'View Logs'
                ]
            ],
        ];

        // صلاحيات الدول
        $countryPermissions = [
            [
                'name' => 'view-countries',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'مشاهدة الدول',
                    'en' => 'View Countries'
                ]
            ],
            [
                'name' => 'create-countries',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'إضافة دولة',
                    'en' => 'Create Country'
                ]
            ],
            [
                'name' => 'edit-countries',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'تعديل دولة',
                    'en' => 'Edit Country'
                ]
            ],
            [
                'name' => 'delete-countries',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'حذف دولة',
                    'en' => 'Delete Country'
                ]
            ],
        ];

        // صلاحيات المدن
        $cityPermissions = [
            [
                'name' => 'view-cities',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'مشاهدة المدن',
                    'en' => 'View Cities'
                ]
            ],
            [
                'name' => 'create-cities',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'إضافة مدينة',
                    'en' => 'Create City'
                ]
            ],
            [
                'name' => 'edit-cities',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'تعديل مدينة',
                    'en' => 'Edit City'
                ]
            ],
            [
                'name' => 'delete-cities',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'حذف مدينة',
                    'en' => 'Delete City'
                ]
            ],
        ];

        // صلاحيات القرى
        $villagePermissions = [
            [
                'name' => 'view-villages',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'مشاهدة القرى',
                    'en' => 'View Villages'
                ]
            ],
            [
                'name' => 'create-villages',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'إضافة قرية',
                    'en' => 'Create Village'
                ]
            ],
            [
                'name' => 'edit-villages',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'تعديل قرية',
                    'en' => 'Edit Village'
                ]
            ],
            [
                'name' => 'delete-villages',
                'guard_name' => 'web',
                'display_name' => [
                    'ar' => 'حذف قرية',
                    'en' => 'Delete Village'
                ]
            ],
        ];

        // دمج جميع الصلاحيات
        $allPermissions = array_merge(
            $userPermissions,
            $rolePermissions,
            $permissionPermissions,
            $logsPermissions,
            $countryPermissions,
            $cityPermissions,
            $villagePermissions
        );

        // إنشاء الصلاحيات
        foreach ($allPermissions as $permissionData) {
            $displayNames = $permissionData['display_name'];
            unset($permissionData['display_name']);

            // تحديد المعرف (slug): استخدم name إن وُجد لضمان الاتساق (جمع)، وإلا اشتق من الاسم الظاهر
            $baseDisplay = $displayNames['en'] ?? ($displayNames['ar'] ?? null);
            $slug = $permissionData['name'] ?? $this->makeSlug($baseDisplay ?? 'permission');
            $guard = $permissionData['guard_name'] ?? 'web';

            // إنشاء الصلاحية أو الحصول عليها إذا كانت موجودة
            $permission = Permission::firstOrCreate([
                'name' => $slug,
                'guard_name' => $guard,
            ]);

            // إضافة الترجمات
            foreach ($displayNames as $locale => $displayName) {
                $permission->translateOrNew($locale)->display_name = $displayName;
            }

            $permission->save();
        }

        $this->command->info('تم إنشاء ' . count($allPermissions) . ' صلاحية بنجاح!');
    }

    private function makeSlug(string $input): string
    {
        // تحويل الأحرف العربية إلى لاتينية بشكل تقريبي
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
        return $normalized ?: 'permission';
    }
}
