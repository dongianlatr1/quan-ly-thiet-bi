<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['name' => 'Quản trị viên', 'username' => 'admin', 'email' => 'admin@vinhuni.edu.vn', 'role' => 'admin'],
            ['name' => 'Trương Anh Ngọc', 'username' => 'anh.ngok', 'email' => 'ngocxinhgai@gmail.com', 'role' => 'giang_vien'],
        ];

        foreach ($accounts as $acc) {
            User::updateOrCreate(
                ['username' => $acc['username']],
                $acc + ['password' => Hash::make('123456'), 'is_active' => true]
            );
        }
    }
}