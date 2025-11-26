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
            $username = "user{$i}";
            $email = "user{$i}@example.com";

            $user = User::updateOrCreate(
                ['username' => $username],
                [
                    'email' => $email,
                    'phone' => '05000000' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                    'last_login_at' => now(),
                    'password' => bcrypt('123123'),
                ]
            );

            $user->translateOrNew('en')->name = "User {$i}";
            $user->translateOrNew('ar')->name = "مستخدم {$i}";
            $user->save();

            if ($i === 1) {
                $gmRole = Role::where('name', 'general-manager')->first();
                if ($gmRole && !$user->hasRole($gmRole->name)) {
                    $user->assignRole($gmRole);
                }
            }
        }
    }
}
