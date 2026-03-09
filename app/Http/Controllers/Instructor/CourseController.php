<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Auth::user()->courses()
            ->withCount(['enrollments', 'lessons'])
            ->latest()
            ->paginate(10);

        return view('instructor.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('instructor.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'thumbnail'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['instructor_id'] = Auth::id();
        $validated['status']        = 'draft';

        $course = Course::create($validated);

        return redirect()->route('instructor.courses.show', $course)
            ->with('success', 'Course created! Now add some lessons.');
    }

    public function show(Course $course)
    {
        $this->authorizeCourse($course);

        $course->load(['lessons' => fn($q) => $q->orderBy('order'), 'category']);
        $course->loadCount('enrollments');

        return view('instructor.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $this->authorizeCourse($course);

        $categories = Category::all();
        return view('instructor.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'thumbnail'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->update($validated);

        return redirect()->route('instructor.courses.show', $course)
            ->with('success', 'Course updated.');
    }

    public function destroy(Course $course)
    {
        $this->authorizeCourse($course);
        $course->delete();

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Course deleted.');
    }

    public function submit(Course $course)
    {
        $this->authorizeCourse($course);

        // Cần ít nhất 1 lesson mới submit được
        if ($course->lessons()->count() === 0) {
            return back()->with('error', 'Add at least one lesson before submit for review.');
        }

        $course->update(['status' => 'pending']);

        return back()->with('success', "Course '{$course->title}' is now pending.");
    }

    public function students(Course $course)
    {
        $this->authorizeCourse($course);

        $enrollments = $course->enrollments()
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('instructor.courses.students.index', compact('course', 'enrollments'));
    }

    public function allStudents()
    {
        $courseIds = Auth::user()->courses()->pluck('id');

        $enrollments = Enrollment::with(['user', 'course'])
            ->whereIn('course_id', $courseIds)
            ->latest()
            ->paginate(15);

        return view('instructor.students.index', compact('enrollments'));
    }

    // Chặn instructor truy cập course của người khác
    private function authorizeCourse(Course $course): void
    {
        if ($course->instructor_id !== Auth::id()) {
            abort(403);
        }
    }
}