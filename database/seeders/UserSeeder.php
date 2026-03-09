<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // Tạo Admin
        \App\Models\User::create([
            'name' => 'Admin EduFlow',
            'email' => 'admin@eduflow.com',
            'password' => \Hash::make('password'),
            'role' => 'admin',
        ]);

        // Tạo Instructor
        \App\Models\User::create([
            'name' => 'Lê Hoàng',
            'email' => 'instructor@eduflow.com',
            'password' => \Hash::make('password'),
            'role' => 'instructor',
        ]);

        // Tạo Student
        \App\Models\User::create([
            'name' => 'Trần Ngọc',
            'email' => 'student@eduflow.com',
            'password' => \Hash::make('password'),
            'role' => 'student',
        ]);
    }
}
