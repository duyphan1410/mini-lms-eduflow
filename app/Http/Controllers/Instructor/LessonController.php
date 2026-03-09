<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function create(Course $course)
    {
        $this->authorizeCourse($course);

        return view('instructor.lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
            'order'   => 'nullable|integer|min:0',
        ]);

        // Auto order + 1
        if (empty($validated['order'])) {
            $validated['order'] = $course->lessons()->max('order') + 1;
        }

        $course->lessons()->create($validated);

        return redirect()->route('instructor.courses.show', $course)
            ->with('success', 'Lesson added.');
    }

    public function edit(Course $course, Lesson $lesson)
    {
        $this->authorizeCourse($course);

        return view('instructor.lessons.edit', compact('course', 'lesson'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
            'order'   => 'nullable|integer|min:0',
        ]);

        $lesson->update($validated);

        return redirect()->route('instructor.courses.show', $course)
            ->with('success', 'Lesson updated.');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $this->authorizeCourse($course);
        $lesson->delete();

        return redirect()->route('instructor.courses.show', $course)
            ->with('success', 'Lesson deleted.');
    }

    private function authorizeCourse(Course $course): void
    {
        if ($course->instructor_id !== Auth::id()) {
            abort(403);
        }
    }
}