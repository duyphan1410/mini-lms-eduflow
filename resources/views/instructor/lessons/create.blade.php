@extends('layouts.app')
@section('title', 'Add Lesson')
@section('sidebar') @include('partials.sidebar_instructor') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Add Lesson</h2>
    <p>{{ $course->title }}</p>
  </div>
  <a href="{{ route('instructor.courses.show', $course) }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<form method="POST" action="{{ route('instructor.courses.lessons.store', $course) }}">
  @csrf

  {{-- Lesson Info --}}
  <div class="card-box mb-3">
    <div class="section-title mb-3">Lesson Info</div>

    <div class="field-group">
      <label class="field-label">Lesson Title</label>
      <div class="field-wrap">
        <i class="bi bi-journal-text field-icon"></i>
        <input type="text" name="title" value="{{ old('title') }}"
          class="field-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
          placeholder="e.g. Introduction to HTML" required autofocus>
      </div>
      @error('title') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Content</label>
      <textarea name="content" rows="6"
        class="field-input" style="padding-left:14px;height:auto"
        placeholder="Lesson content...">{{ old('content') }}</textarea>
    </div>

    <div class="field-group mb-0">
      <label class="field-label">Order <span style="color:var(--edu-muted);font-weight:400">(automatic if left blank)</span></label>
      <div class="field-wrap">
        <i class="bi bi-sort-numeric-up field-icon"></i>
        <input type="number" name="order" value="{{ old('order') }}" class="field-input" min="0" placeholder="1">
      </div>
    </div>
  </div>

  {{-- Quiz Toggle --}}
  <div class="card-box mb-3">
    <div style="display:flex;align-items:center;justify-content:space-between">
      <div>
        <div style="font-weight:600;font-size:14px">Add Quiz to this Lesson</div>
        <div style="font-size:12px;color:var(--edu-muted)">Optional — can be added later</div>
      </div>
      <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
        <input type="checkbox" id="quiz-toggle" onchange="toggleQuiz(this)"
          style="width:18px;height:18px;accent-color:var(--edu-accent)"
          {{ old('quiz_title') ? 'checked' : '' }}>
        <span style="font-size:13px;font-weight:500">Enable Quiz</span>
      </label>
    </div>

    <div id="quiz-section" style="display:{{ old('quiz_title') ? 'block' : 'none' }};margin-top:20px;border-top:1px solid var(--edu-border);padding-top:20px">
      <div class="field-group">
        <label class="field-label">Quiz Title</label>
        <div class="field-wrap">
          <i class="bi bi-patch-question field-icon"></i>
          <input type="text" name="quiz_title" value="{{ old('quiz_title') }}"
            class="field-input" placeholder="e.g. HTML Basics Quiz" required>
        </div>
      </div>

      <div id="questions-container"></div>

      <button type="button" class="btn-outline-edu" onclick="addQuestion()">
        <i class="bi bi-plus-lg"></i> Add Question
      </button>
    </div>
  </div>

  <div class="d-flex gap-2">
    <button type="submit" class="btn-primary-edu">
      <i class="bi bi-plus-lg"></i> Add Lesson
    </button>
    <a href="{{ route('instructor.courses.show', $course) }}" class="btn-outline-edu">Cancel</a>
  </div>
</form>

<script>
    window.questionCount = 0; 
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