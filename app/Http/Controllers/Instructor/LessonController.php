<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
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
            'title'                          => 'required|string|max:255',
            'content'                        => 'nullable|string',
            'order'                          => 'nullable|integer|min:0',
            'quiz_title'                     => 'nullable|string|max:255',
            'questions'                      => 'nullable|array',
            'questions.*.question_text'      => 'required_with:quiz_title|string',
            'questions.*.options'            => 'required_with:quiz_title|array|min:2',
            'questions.*.options.*.text'     => 'required_with:quiz_title|string',
            'questions.*.correct_option'     => 'required_with:quiz_title|integer',
        ]);

        if (empty($validated['order'])) {
            $validated['order'] = $course->lessons()->max('order') + 1;
        }

        $lesson = $course->lessons()->create([
            'title'   => $validated['title'],
            'content' => $validated['content'] ?? null,
            'order'   => $validated['order'],
        ]);

        // Tạo quiz nếu có
        if ($request->filled('quiz_title') && $request->has('questions')) {
            $this->createQuiz($lesson, $request);
        }

        return redirect()->route('instructor.courses.show', $course)
            ->with('success', 'Lesson added.');
    }

    private function createQuiz(Lesson $lesson, Request $request): void
    {
        $quiz = $lesson->quizzes()->create(['title' => $request->quiz_title]);

        foreach ($request->questions as $qData) {
            $question = $quiz->questions()->create([
                'question_text' => $qData['question_text'],
            ]);
            foreach ($qData['options'] as $i => $optData) {
                $question->options()->create([
                    'option_text' => $optData['text'],
                    'is_correct'  => $i == $qData['correct_option'],
                ]);
            }
        }
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
            'quiz_title'                     => 'nullable|required_with:questions|string|max:255',
            'questions'                      => 'nullable|array|required_with:quiz_title',
            'questions.*.question_text'      => 'required_with:quiz_title|string',
            'questions.*.options'            => 'required_with:quiz_title|array|min:2',
            'questions.*.options.*.text'     => 'required_with:quiz_title|string',
            'questions.*.correct_option'     => 'required_with:quiz_title|integer',
        ]);

        $lesson->update([
            'title'   => $validated['title'],
            'content' => $validated['content'] ?? null,
            'order'   => $validated['order'] ?? $lesson->order,
        ]);

        // Nếu có quiz_title → xóa quiz cũ, tạo lại
        if ($request->filled('quiz_title') && $request->has('questions')) {
            $existing = $lesson->quizzes()->first();
            if ($existing) {
                $existing->questions()->each(fn($q) => $q->options()->delete());
                $existing->questions()->delete();
                $existing->delete();
            }
            $this->createQuiz($lesson, $request); // method private từ store()
        }

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