@extends('layouts.app')
@section('title', 'Quiz')
@section('sidebar') @include('partials.sidebar_instructor') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Quiz: {{ $lesson->title }}</h2>
    <p>{{ $course->title }}</p>
  </div>
  <a href="{{ route('instructor.courses.show', $course) }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif
@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if(!$quiz)
  <div class="card-box text-center" style="color:var(--edu-muted);padding:48px">
    <i class="bi bi-patch-question" style="font-size:40px;display:block;margin-bottom:12px"></i>
    <div style="font-weight:600;margin-bottom:8px">Chưa có quiz nào cho lesson này.</div>
    <a href="{{ route('instructor.courses.lessons.quizzes.create', [$course, $lesson]) }}"
      class="btn-primary-edu mt-2">
      <i class="bi bi-plus-lg"></i> Create Quiz
    </a>
  </div>
@else
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <div class="section-title mb-0">{{ $quiz->title }}</div>
    <div class="d-flex gap-2">
      <a href="{{ route('instructor.courses.lessons.quizzes.edit', [$course, $lesson, $quiz]) }}"
        class="btn-outline-edu">
        <i class="bi bi-pencil"></i> Edit Quiz
      </a>
      <form method="POST"
        action="{{ route('instructor.courses.lessons.quizzes.destroy', [$course, $lesson, $quiz]) }}"
        onsubmit="return confirm('Delete this quiz?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-outline-edu" style="border-color:var(--edu-red);color:var(--edu-red)">
          <i class="bi bi-trash"></i>
        </button>
      </form>
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:12px">
    @foreach($quiz->questions as $i => $question)
    <div class="card-box">
      <div style="font-weight:600;font-size:14px;margin-bottom:10px">
        {{ $i + 1 }}. {{ $question->question_text }}
      </div>
      <div style="display:flex;flex-direction:column;gap:6px">
        @foreach($question->options as $option)
        <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;font-size:13px;
                    background:{{ $option->is_correct ? 'rgba(16,185,129,.1)' : 'var(--edu-surface)' }};
                    border:1.5px solid {{ $option->is_correct ? 'var(--edu-green)' : 'var(--edu-border)' }}">
          @if($option->is_correct)
            <i class="bi bi-check-circle-fill" style="color:var(--edu-green)"></i>
          @else
            <i class="bi bi-circle" style="color:var(--edu-muted)"></i>
          @endif
          {{ $option->option_text }}
          @if($option->is_correct)
            <span style="margin-left:auto;font-size:11px;color:var(--edu-green);font-weight:600">Correct</span>
          @endif
        </div>
        @endforeach
      </div>
    </div>
    @endforeach
  </div>
@endif
@endsection