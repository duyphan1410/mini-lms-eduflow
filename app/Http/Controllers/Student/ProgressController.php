<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
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

    public function submitQuiz(Request $request, Lesson $lesson)
    {
        $user = Auth::user();

        abort_if(!$user->enrollments()->where('course_id', $lesson->course_id)->exists(), 403);

        $quiz = $lesson->quizzes()->with('questions.options')->firstOrFail();

        $request->validate([
            'answers'   => 'required|array',
            'answers.*' => 'required|exists:options,id',
        ]);

        // Xóa attempt cũ — quiz_answers tự cascade delete
        QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->delete();

        // Tính score
        $score = $quiz->questions->reduce(function ($carry, $question) use ($request) {
            $selected = $request->answers[$question->id] ?? null;
            $correct  = $question->options->firstWhere('is_correct', true);
            return $carry + ($correct && $correct->id == $selected ? 1 : 0);
        }, 0);

        // attempt mới
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score'   => $score,
        ]);

        // Lưu answers — insert batch
        $answers = $quiz->questions->map(fn($q) => [
            'quiz_attempt_id' => $attempt->id,
            'question_id'     => $q->id,
            'option_id'       => $request->answers[$q->id] ?? null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ])->filter(fn($a) => $a['option_id'])->values()->toArray();

        QuizAnswer::insert($answers);

        $total = $quiz->questions->count();
        $pct   = $total > 0 ? round($score / $total * 100) : 0;

        return redirect()->route('student.lessons.show', $lesson)
            ->with('success', "Quiz submitted! Score: {$score}/{$total} ({$pct}%)");
    }
}