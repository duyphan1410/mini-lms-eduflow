<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Category;
use App\Models\User;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $instructorId = User::where('email', 'instructor@eduflow.com')->value('id');

        $programming = Category::where('slug', 'programming')->first();
        $web         = Category::where('slug', 'web-development')->first();
        $database    = Category::where('slug', 'database')->first();
        $uiux        = Category::where('slug', 'uiux-design')->first();
        $devops      = Category::where('slug', 'devops')->first();

        $courses = [
            [
                'title'         => 'PHP for Beginners',
                'description'   => 'Learn PHP from scratch — variables, loops, functions, and OOP basics.',
                'category_id'   => $programming->id,
                'status'        => 'published',
            ],
            [
                'title'         => 'Laravel Basics',
                'description'   => 'Introduction to Laravel framework — routing, controllers, Blade, and Eloquent.',
                'category_id'   => $web->id,
                'status'        => 'published',
            ],
            [
                'title'         => 'SQL Fundamentals',
                'description'   => 'Learn relational databases and SQL — SELECT, JOIN, indexes, and transactions.',
                'category_id'   => $database->id,
                'status'        => 'published',
            ],
            [
                'title'         => 'UI/UX Design Principles',
                'description'   => 'Master the fundamentals of user interface and user experience design.',
                'category_id'   => $uiux->id,
                'status'        => 'draft', // demo pending flow
            ],
            [
                'title'         => 'DevOps with Docker',
                'description'   => 'Learn containerization with Docker and basic CI/CD pipelines.',
                'category_id'   => $devops->id,
                'status'        => 'pending', // demo pending flow
            ],
        ];

        foreach ($courses as $course) {
            Course::create([
                'instructor_id' => $instructorId,
                ...$course,
            ]);
        }
    }
}