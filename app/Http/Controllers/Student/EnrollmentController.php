<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    // Danh sách course đã enroll
    public function index()
    {
        $enrollments = Auth::user()->enrollments()
            ->with(['course.instructor', 'course.category'])
            ->latest()
            ->get();

        // Tính progress % cho mỗi enrollment
        $enrollments->each(function ($enrollment) {
            $totalLessons = $enrollment->course->lessons()->count();

            if ($totalLessons === 0) {
                $enrollment->progress_percent = 0;
                return;
            }

            $completedLessons = Auth::user()->lessonProgress()
                ->whereIn('lesson_id', $enrollment->course->lessons()->pluck('id'))
                ->where('completed', true)
                ->count();

            $enrollment->progress_percent = round($completedLessons / $totalLessons * 100);
        });

        return view('student.my-courses', compact('enrollments'));
    }

    // Browse tất cả courses published
    public function browse(Request $request)
    {
        $enrolledIds = Auth::user()->enrollments()->pluck('course_id');
        $categories  = Category::all();

        $courses = Course::where('status', 'published')
            ->with(['instructor', 'category'])
            ->withCount('enrollments')
            ->when($request->search, fn($q) =>
                $q->where('title', 'like', "%{$request->search}%")
            )
            ->when($request->category, fn($q) =>
                $q->where('category_id', $request->category)
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('student.courses.index', compact('courses', 'enrolledIds', 'categories'));
    }

    // Chi tiết 1 course
    public function show(Course $course)
    {
        abort_if($course->status !== 'published', 404);

        $course->load(['instructor', 'category', 'lessons' => fn($q) => $q->orderBy('order')]);
        $course->loadCount('enrollments');

        $isEnrolled = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->exists();

        // Nếu đã enroll, lấy progress
        $completedLessonIds = [];
        if ($isEnrolled) {
            $completedLessonIds = Auth::user()->lessonProgress()
                ->whereIn('lesson_id', $course->lessons->pluck('id'))
                ->where('completed', true)
                ->pluck('lesson_id')
                ->toArray();
        }

        return view('student.courses.show', compact('course', 'isEnrolled', 'completedLessonIds'));
    }

    // Enroll vào course
    public function store(Course $course)
    {
        abort_if($course->status !== 'published', 404);

        // Đã enroll rồi thì không enroll lại
        $already = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->exists();

        if ($already) {
            return redirect()->route('student.courses.show', $course)
                ->with('error', 'You are already enrolled in this course.');
        }

        Enrollment::create([
            'user_id'   => Auth::id(),
            'course_id' => $course->id,
        ]);

        return redirect()->route('student.courses.show', $course)
            ->with('success', "Enrolled in '{$course->title}'! Start learning now.");
    }
}