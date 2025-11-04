<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'username' => "user{$i}",
                'email' => "user{$i}@example.com",
                'phone' => '05000000' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                'last_login_at' => now(),
                'password' => bcrypt('123123'),
            ]);

            // إضافة ترجمات للاسم بالإنجليزية والعربية عبر العلاقة مباشرةً
            $user->translations()->create(['locale' => 'en', 'name' => "User {$i}"]);
            $user->translations()->create(['locale' => 'ar', 'name' => "مستخدم {$i}"]);

            // إسناد دور المدير العام لأول مستخدم يتم إنشاؤه
            if ($i === 1) {
                $gmRole = Role::where('name', 'general-manager')->first();
                if ($gmRole) {
                    $user->assignRole($gmRole);
                }
            }
        }
    }
}
