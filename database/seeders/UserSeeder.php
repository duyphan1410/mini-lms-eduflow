<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // Admin
        User::create([
            'name'      => 'Admin EduFlow',
            'email'     => 'admin@eduflow.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // Instructor
        User::create([
            'name'      => 'Lê Hoàng',
            'email'     => 'instructor@eduflow.com',
            'password'  => Hash::make('password'),
            'role'      => 'instructor',
            'is_active' => true,
        ]);

        // Students
        $students = [
            ['name' => 'Trần Ngọc',   'email' => 'student@eduflow.com'],
            ['name' => 'Nguyễn Minh', 'email' => 'student2@eduflow.com'],
            ['name' => 'Phạm Linh',   'email' => 'student3@eduflow.com'],
            ['name' => 'Đỗ Hải',      'email' => 'student4@eduflow.com'],
        ];

        foreach ($students as $s) {
            User::create([
                'name'      => $s['name'],
                'email'     => $s['email'],
                'password'  => Hash::make('password'),
                'role'      => 'student',
                'is_active' => true,
            ]);
        }
    }
}
