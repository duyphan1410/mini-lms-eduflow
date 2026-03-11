@extends('layouts.app')
@section('title', 'Edit Lesson')
@section('sidebar') @include('partials.sidebar_instructor') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Edit Lesson</h2>
    <p>{{ $course->title }}</p>
  </div>
  <a href="{{ route('instructor.courses.show', $course) }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

@php $existingQuiz = $lesson->quizzes()->with('questions.options')->first(); @endphp

<form method="POST" action="{{ route('instructor.courses.lessons.update', [$course, $lesson]) }}">
  @csrf @method('PUT')

  {{-- Lesson Info --}}
  <div class="card-box mb-3">
    <div class="section-title mb-3">Lesson Info</div>

    <div class="field-group">
      <label class="field-label">Lesson Title</label>
      <div class="field-wrap">
        <i class="bi bi-journal-text field-icon"></i>
        <input type="text" name="title" value="{{ old('title', $lesson->title) }}"
          class="field-input {{ $errors->has('title') ? 'is-invalid' : '' }}" required>
      </div>
      @error('title') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Content</label>
      <textarea name="content" rows="6"
        class="field-input" style="padding-left:14px;height:auto">{{ old('content', $lesson->content) }}</textarea>
    </div>

    <div class="field-group mb-0">
      <label class="field-label">Order</label>
      <div class="field-wrap">
        <i class="bi bi-sort-numeric-up field-icon"></i>
        <input type="number" name="order" value="{{ old('order', $lesson->order) }}"
          class="field-input" min="0">
      </div>
    </div>
  </div>

  {{-- Quiz Section --}}
  <div class="card-box mb-3">
    <div style="display:flex;align-items:center;justify-content:space-between">
      <div>
        <div style="font-weight:600;font-size:14px">Quiz</div>
        <div style="font-size:12px;color:var(--edu-muted)">
          @if($existingQuiz) Quiz already exists — edit below @else Options — add quiz for this lesson @endif
        </div>
      </div>
      <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
        <input type="checkbox" id="quiz-toggle" onchange="toggleQuiz(this)"
          style="width:18px;height:18px;accent-color:var(--edu-accent)"
          {{ $existingQuiz || old('quiz_title') ? 'checked' : '' }}>
        <span style="font-size:13px;font-weight:500">Enable Quiz</span>
      </label>
    </div>

    <div id="quiz-section"
      style="display:{{ $existingQuiz || old('quiz_title') ? 'block' : 'none' }};margin-top:20px;border-top:1px solid var(--edu-border);padding-top:20px">

      <div class="field-group">
        <label class="field-label">Quiz Title</label>
        <div class="field-wrap">
          <i class="bi bi-patch-question field-icon"></i>
          <input type="text" name="quiz_title"
            value="{{ old('quiz_title', $existingQuiz?->title) }}"
            class="field-input" placeholder="e.g. HTML Basics Quiz" required>
        </div>
      </div>

      <div id="questions-container">
        @if($existingQuiz)
          @foreach($existingQuiz->questions as $qi => $question)
          <div class="card-box mb-3" id="question-{{ $qi }}" style="background:var(--edu-surface)"> 
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
              <div style="font-weight:600;font-size:13px">Question {{ $qi + 1 }}</div>
              <button type="button" onclick="removeQuestion({{ $qi }})"
                style="border:none;background:none;color:var(--edu-red);cursor:pointer;font-size:12px">
                <i class="bi bi-trash"></i> Remove
              </button>
            </div>
            <div class="field-group">
              <div class="field-wrap">
                <i class="bi bi-question-circle field-icon"></i>
                <input type="text" name="questions[{{ $qi }}][question_text]"
                  value="{{ $question->question_text }}" class="field-input" required>
              </div>
            </div>
            <div id="options-{{ $qi }}" style="display:flex;flex-direction:column;gap:8px;margin-bottom:10px">
              @foreach($question->options as $oi => $option)
              <div style="display:flex;align-items:center;gap:8px" id="option-{{ $qi }}-{{ $oi }}">
                <input type="radio" name="questions[{{ $qi }}][correct_option]"
                  value="{{ $oi }}" {{ $option->is_correct ? 'checked' : '' }} required>
                <input type="text" name="questions[{{ $qi }}][options][{{ $oi }}][text]"
                  value="{{ $option->option_text }}"
                  class="field-input" style="flex:1;padding-left:14px" required>
                <button type="button" onclick="removeOption('{{ $qi }}','{{ $oi }}')"
                  style="border:none;background:none;color:var(--edu-red);cursor:pointer">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>
              @endforeach
            </div>
            <button type="button" onclick="addOption({{ $qi }})"
              class="btn-outline-edu btn-sm py-1 px-3" style="font-size:12px">
              <i class="bi bi-plus"></i> Add Option
            </button>
          </div>
          @endforeach
        @endif
      </div>

      <button type="button" class="btn-outline-edu" onclick="addQuestion()">
        <i class="bi bi-plus-lg"></i> Add Question
      </button>
    </div>
  </div>

  <div class="d-flex gap-2">
    <button type="submit" class="btn-primary-edu">
      <i class="bi bi-check-lg"></i> Save Changes
    </button>
    <a href="{{ route('instructor.courses.show', $course) }}" class="btn-outline-edu">Cancel</a>
  </div>
</form>
<script>
    window.questionCount = {{ $existingQuiz ? $existingQuiz->questions->count() : 0 }};
    document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('form')?.addEventListener('submit', (e) => {
    const data = new FormData(e.target);
    console.log('=== FORM SUBMIT ===');
    for (let [k, v] of data.entries()) console.log(k, v);
    
    // Check questions
    const questions = document.querySelectorAll('[name^="questions"]');
    console.log('Questions fields found:', questions.length);
  });
});
</script>
@vite(['resources/js/lesson-quiz.js'])
@endsection