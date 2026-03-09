<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    // Trang My Progress — overview tất cả courses
    public function index()
    {
        $user = Auth::user();

        $enrollments = $user->enrollments()
            ->with(['course.lessons', 'course.instructor'])
            ->get();

        $enrollments->each(function ($enrollment) use ($user) {
            $lessonIds    = $enrollment->course->lessons->pluck('id');
            $totalLessons = $lessonIds->count();

            if ($totalLessons === 0) {
                $enrollment->progress_percent  = 0;
                $enrollment->completed_lessons = 0;
                $enrollment->total_lessons     = 0;
                return;
            }

            $completedCount = $user->lessonProgress()
                ->whereIn('lesson_id', $lessonIds)
                ->where('completed', true)
                ->count();

            $enrollment->progress_percent  = round($completedCount / $totalLessons * 100);
            $enrollment->completed_lessons = $completedCount;
            $enrollment->total_lessons     = $totalLessons;
        });

        return view('student.progress', compact('enrollments'));
    }

    // Xem + học 1 lesson
    public function show(Lesson $lesson)
    {
        $user   = Auth::user();
        $course = $lesson->course;

        // Kiểm tra đã enroll chưa
        $isEnrolled = $user->enrollments()
            ->where('course_id', $course->id)
            ->exists();

        abort_if(!$isEnrolled, 403, 'You are not enrolled in this course.');

        // Load lessons theo order để hiện prev/next
        $lessons = $course->lessons()->orderBy('order')->get();
        $currentIndex = $lessons->search(fn($l) => $l->id === $lesson->id);
        $prevLesson   = $currentIndex > 0 ? $lessons[$currentIndex - 1] : null;
        $nextLesson   = $currentIndex < $lessons->count() - 1 ? $lessons[$currentIndex + 1] : null;

        // Progress của lesson này
        $progress = $user->lessonProgress()
            ->where('lesson_id', $lesson->id)
            ->first();

        $isCompleted = $progress?->completed ?? false;

        // Quiz của lesson này (nếu có)
        $quiz = $lesson->quizzes()->with('questions.options')->first();

        // Quiz attempt gần nhất
        $lastAttempt = $quiz
            ? $user->quizAttempts()->where('quiz_id', $quiz->id)->latest()->first()
            : null;

        return view('student.lessons.show', compact(
            'lesson', 'course', 'lessons',
            'prevLesson', 'nextLesson',
            'isCompleted', 'quiz', 'lastAttempt'
        ));
    }

    // Mark lesson completed
    public function markComplete(Request $request, Lesson $lesson)
    {
        $user   = Auth::user();
        $course = $lesson->course;

        $isEnrolled = $user->enrollments()
            ->where('course_id', $course->id)
            ->exists();

        abort_if(!$isEnrolled, 403);

        // updateOrCreate vì unique(user_id, lesson_id)
        LessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['completed' => true, 'completed_at' => now()]
        );

        return back()->with('success', 'Lesson marked as completed!');
    }
}