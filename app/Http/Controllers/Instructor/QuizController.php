<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index(Course $course, Lesson $lesson)
    {
        $this->authorizeCourse($course);
        $quiz = $lesson->quizzes()->with('questions.options')->first();

        return view('instructor.quizzes.index', compact('course', 'lesson', 'quiz'));
    }

    public function create(Course $course, Lesson $lesson)
    {
        $this->authorizeCourse($course);
        // Mỗi lesson chỉ có 1 quiz
        if ($lesson->quizzes()->exists()) {
            return redirect()->route('instructor.courses.lessons.quizzes.index', [$course, $lesson])
                ->with('error', 'Lesson này đã có quiz rồi.');
        }

        return view('instructor.quizzes.create', compact('course', 'lesson'));
    }

    public function store(Request $request, Course $course, Lesson $lesson)
    {
        $this->authorizeCourse($course);

        $request->validate([
            'title'                          => 'required|string|max:255',
            'questions'                      => 'required|array|min:1',
            'questions.*.question_text'      => 'required|string',
            'questions.*.options'            => 'required|array|min:2',
            'questions.*.options.*.text'     => 'required|string',
            'questions.*.correct_option'     => 'required|integer', // index của option đúng
        ]);

        $quiz = $lesson->quizzes()->create(['title' => $request->title]);

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

        return redirect()->route('instructor.courses.lessons.quizzes.index', [$course, $lesson])
            ->with('success', 'Quiz created successfully!');
    }

    public function edit(Course $course, Lesson $lesson, Quiz $quiz)
    {
        $this->authorizeCourse($course);
        $quiz->load('questions.options');

        return view('instructor.quizzes.edit', compact('course', 'lesson', 'quiz'));
    }

    public function update(Request $request, Course $course, Lesson $lesson, Quiz $quiz)
    {
        $this->authorizeCourse($course);

        $request->validate([
            'title'                          => 'required|string|max:255',
            'questions'                      => 'required|array|min:1',
            'questions.*.question_text'      => 'required|string',
            'questions.*.options'            => 'required|array|min:2',
            'questions.*.options.*.text'     => 'required|string',
            'questions.*.correct_option'     => 'required|integer',
        ]);

        $quiz->update(['title' => $request->title]);

        // Xóa hết questions + options cũ rồi tạo lại
        $quiz->questions()->each(fn($q) => $q->options()->delete());
        $quiz->questions()->delete();

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

        return redirect()->route('instructor.courses.lessons.quizzes.index', [$course, $lesson])
            ->with('success', 'Quiz updated!');
    }

    public function destroy(Course $course, Lesson $lesson, Quiz $quiz)
    {
        $this->authorizeCourse($course);

        $quiz->questions()->each(fn($q) => $q->options()->delete());
        $quiz->questions()->delete();
        $quiz->delete();

        return redirect()->route('instructor.courses.lessons.quizzes.index', [$course, $lesson])
            ->with('success', 'Quiz deleted.');
    }

    private function authorizeCourse(Course $course): void
    {
        if ($course->instructor_id !== Auth::id()) abort(403);
    }
}