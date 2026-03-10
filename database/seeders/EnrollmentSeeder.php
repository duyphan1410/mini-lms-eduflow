<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students        = User::where('role', 'student')->get();
        $publishedCourses = Course::where('status', 'published')->with('lessons')->get();

        if ($publishedCourses->isEmpty()) {
            $this->command->warn('No published courses found.');
            return;
        }

        foreach ($students as $index => $student) {
            // Mỗi student enroll vào 1-3 courses
            $enrollCount  = min($index + 1, $publishedCourses->count());
            $coursesToEnroll = $publishedCourses->take($enrollCount);

            foreach ($coursesToEnroll as $courseIndex => $course) {
                Enrollment::create([
                    'user_id'   => $student->id,
                    'course_id' => $course->id,
                ]);

                // Thêm progress cho course đầu tiên của mỗi student
                if ($courseIndex === 0 && $course->lessons->isNotEmpty()) {
                    $lessonsToComplete = $course->lessons->take(
                        max(1, (int) ($course->lessons->count() * ($index + 1) / $students->count()))
                    );

                    foreach ($lessonsToComplete as $lesson) {
                        LessonProgress::create([
                            'user_id'      => $student->id,
                            'lesson_id'    => $lesson->id,
                            'completed'    => true,
                            'completed_at' => now()->subDays(rand(1, 10)),
                        ]);
                    }
                }
            }
        }

        $this->command->info('Enrollments and progress seeded successfully.');
    }
}