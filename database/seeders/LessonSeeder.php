<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();

        if ($courses->isEmpty()) {
            $this->command->warn('No courses found. Run CourseSeeder first.');
            return;
        }

        foreach ($courses as $course) {
            $lessonsData = $this->getLessonsData($course->title);

            foreach ($lessonsData as $order => $lessonData) {
                $lesson = Lesson::create([
                    'course_id' => $course->id,
                    'title'     => $lessonData['title'],
                    'content'   => $lessonData['content'],
                    'order'     => $order + 1,
                ]);

                // Tạo quiz cho lesson lẻ (1, 3, 5...)
                if (($order + 1) % 2 !== 0) {
                    $this->createQuiz($lesson, $order);
                }
            }
        }

        $this->command->info('Lessons and Quizzes seeded successfully.');
    }

    private function getLessonsData(string $courseTitle): array
    {
        return [
            [
                'title'   => 'Introduction & Overview',
                'content' => "Welcome to this course!\n\nIn this lesson, we'll cover:\n- Course objectives\n- What you'll learn\n- Prerequisites\n- How to get the most out of this course\n\nLet's get started!",
            ],
            [
                'title'   => 'Core Concepts',
                'content' => "In this lesson we dive into the fundamental concepts.\n\nKey topics:\n- Basic terminology\n- Core principles\n- How everything fits together\n\nTake notes as you go — these concepts will be referenced throughout the course.",
            ],
            [
                'title'   => 'Hands-on Practice',
                'content' => "Time to apply what you've learned!\n\nIn this practical lesson:\n- We'll walk through real examples\n- You'll complete exercises\n- Common mistakes and how to avoid them\n\nDon't skip the exercises — practice is key.",
            ],
            [
                'title'   => 'Advanced Techniques',
                'content' => "Now that you have the basics down, let's level up.\n\nAdvanced topics include:\n- Best practices\n- Performance tips\n- Real-world patterns\n\nThese techniques will set you apart.",
            ],
            [
                'title'   => 'Final Project & Wrap-up',
                'content' => "Congratulations on making it to the final lesson!\n\nIn this lesson:\n- Final project walkthrough\n- Course summary\n- Next steps and resources\n- How to continue learning\n\nGreat work completing this course!",
            ],
        ];
    }

    private function createQuiz(Lesson $lesson, int $lessonIndex): void
    {
        $quizData = $this->getQuizData($lessonIndex);

        $quiz = Quiz::create([
            'lesson_id' => $lesson->id,
            'title'     => $quizData['title'],
        ]);

        foreach ($quizData['questions'] as $qData) {
            $question = Question::create([
                'quiz_id'       => $quiz->id,
                'question_text' => $qData['question'],
            ]);

            foreach ($qData['options'] as $i => $optText) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $optText,
                    'is_correct'  => $i === $qData['correct'],
                ]);
            }
        }
    }

    private function getQuizData(int $lessonIndex): array
    {
        $quizzes = [
            [
                'title'     => 'Introduction Quiz',
                'questions' => [
                    [
                        'question' => 'What is the main goal of this course?',
                        'options'  => [
                            'To confuse students',
                            'To provide structured learning',
                            'To waste your time',
                            'None of the above',
                        ],
                        'correct'  => 1,
                    ],
                    [
                        'question' => 'What should you do to get the most out of this course?',
                        'options'  => [
                            'Skip all exercises',
                            'Watch passively',
                            'Take notes and practice',
                            'Rush through everything',
                        ],
                        'correct'  => 2,
                    ],
                ],
            ],
            [
                'title'     => 'Hands-on Practice Quiz',
                'questions' => [
                    [
                        'question' => 'What is the best way to learn a new skill?',
                        'options'  => [
                            'Reading only',
                            'Watching videos only',
                            'Practice and application',
                            'Memorizing definitions',
                        ],
                        'correct'  => 2,
                    ],
                    [
                        'question' => 'Why should you avoid skipping exercises?',
                        'options'  => [
                            'They are mandatory',
                            'Practice reinforces learning',
                            'The instructor said so',
                            'They count for grades',
                        ],
                        'correct'  => 1,
                    ],
                ],
            ],
            [
                'title'     => 'Final Assessment',
                'questions' => [
                    [
                        'question' => 'What is a key benefit of completing the final project?',
                        'options'  => [
                            'Getting a certificate',
                            'Applying all course concepts in practice',
                            'Finishing the course faster',
                            'Skipping future lessons',
                        ],
                        'correct'  => 1,
                    ],
                    [
                        'question' => 'What should you do after completing this course?',
                        'options'  => [
                            'Stop learning',
                            'Delete all notes',
                            'Continue learning and apply skills',
                            'Wait for the next course',
                        ],
                        'correct'  => 2,
                    ],
                ],
            ],
        ];

        return $quizzes[$lessonIndex % count($quizzes)];
    }
}