<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with(['instructor', 'category'])
            ->withCount('enrollments')
            ->when($request->search, fn($q) =>
                $q->where('title', 'like', "%{$request->search}%")
            )
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->load(['instructor', 'category', 'lessons', 'enrollments.user']);

        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $categories = Category::all();

        return view('admin.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required|in:draft,published',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted.');
    }

    public function approve(Course $course)
    {
        $course->update(['status' => 'published']);

        return back()->with('success', "Course '{$course->title}' approved.");
    }

    public function reject(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', "Course '{$course->title}' rejected and removed.");
    }
}