<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Category;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $programming = Category::where('slug','programming')->first();
        $web = Category::where('slug','web-development')->first();
        $database = Category::where('slug','database')->first();

        Course::create([
            'title' => 'PHP for Beginners',
            'description' => 'Learn PHP from scratch',
            'category_id' => $programming->id,
            'instructor_id' => 2
        ]);

        Course::create([
            'title' => 'Laravel Basics',
            'description' => 'Introduction to Laravel framework',
            'category_id' => $web->id,
            'instructor_id' => 2
        ]);

        Course::create([
            'title' => 'SQL Fundamentals',
            'description' => 'Learn relational databases and SQL',
            'category_id' => $database->id,
            'instructor_id' => 2
        ]);
    }
}