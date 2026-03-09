@extends('layouts.app')
@section('title', $lesson->title)
@section('sidebar') @include('partials.sidebar_student') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>{{ $lesson->title }}</h2>
    <p>
      <a href="{{ route('student.courses.show', $course) }}" style="color:var(--edu-accent)">
        {{ $course->title }}
      </a>
      · Lesson {{ $lesson->order }}
    </p>
  </div>
  <a href="{{ route('student.courses.show', $course) }}" class="btn-outline-edu">
    <i class="bi bi-grid me-1"></i> All Lessons
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="row g-3">
  {{-- Main content --}}
  <div class="col-md-8">
    <div class="card-box" style="min-height:300px">
      <div style="font-size:15px;line-height:1.8;color:#334155">
        {!! nl2br(e($lesson->content)) !!}
      </div>
    </div>

    {{-- Quiz section --}}
    @if($quiz)
    <div class="card-box mt-3">
      <div class="section-title" style="margin-bottom:16px">
        <i class="bi bi-patch-question me-2" style="color:var(--edu-accent)"></i>
        Quiz: {{ $quiz->title }}
      </div>

      @if($lastAttempt)
        <div class="alert alert-success">
          <i class="bi bi-trophy me-2"></i>
          Điểm gần nhất: <strong>{{ $lastAttempt->score }}/{{ $quiz->questions->count() }}</strong>
          ({{ round($lastAttempt->score / max($quiz->questions->count(), 1) * 100) }}%)
        </div>
      @endif

      <form method="POST" action="{{ route('student.lessons.show', $lesson) }}">
        @csrf
        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

        @foreach($quiz->questions as $i => $question)
        <div style="margin-bottom:20px">
          <div style="font-weight:600;font-size:13px;margin-bottom:8px">
            {{ $i + 1 }}. {{ $question->question_text }}
          </div>
          @foreach($question->options as $option)
          <label style="display:flex;align-items:center;gap:8px;padding:8px 12px;
                        border:1.5px solid var(--edu-border);border-radius:8px;
                        margin-bottom:6px;cursor:pointer;font-size:13px;
                        transition:border-color .2s"
            onmouseover="this.style.borderColor='var(--edu-accent)'"
            onmouseout="this.style.borderColor='var(--edu-border)'">
            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required>
            {{ $option->option_text }}
          </label>
          @endforeach
        </div>
        @endforeach

        <button type="submit" class="btn-primary-edu">
          <i class="bi bi-send me-1"></i> Submit Quiz
        </button>
      </form>
    </div>
    @endif

    {{-- Mark complete + navigation --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:20px;flex-wrap:wrap;gap:10px">
      {{-- Prev --}}
      @if($prevLesson)
        <a href="{{ route('student.lessons.show', $prevLesson) }}" class="btn-outline-edu">
          <i class="bi bi-arrow-left me-1"></i> Previous
        </a>
      @else
        <div></div>
      @endif

      {{-- Mark complete --}}
      @if(!$isCompleted)
      <form method="POST" action="{{ route('student.lessons.complete', $lesson) }}">
        @csrf
        <button type="submit" class="btn-primary-edu" style="background:var(--edu-green);border-color:var(--edu-green)">
          <i class="bi bi-check-lg me-1"></i> Mark as Complete
        </button>
      </form>
      @else
        <span class="badge-pill" style="background:rgba(16,185,129,.1);color:var(--edu-green);font-size:13px;padding:8px 16px">
          <i class="bi bi-check-circle me-1"></i> Completed
        </span>
      @endif

      {{-- Next --}}
      @if($nextLesson)
        <a href="{{ route('student.lessons.show', $nextLesson) }}" class="btn-primary-edu">
          Next <i class="bi bi-arrow-right ms-1"></i>
        </a>
      @else
        <a href="{{ route('student.courses.show', $course) }}" class="btn-primary-edu">
          <i class="bi bi-trophy me-1"></i> Finish Course
        </a>
      @endif
    </div>
  </div>

  {{-- Lesson list sidebar --}}
  <div class="col-md-4">
    <div class="section-title">Course Lessons</div>
    <div style="display:flex;flex-direction:column;gap:6px">
      @foreach($lessons as $l)
      <a href="{{ route('student.lessons.show', $l) }}"
        style="display:flex;align-items:center;gap:10px;padding:10px 14px;
               border-radius:10px;text-decoration:none;transition:background .15s;
               background:{{ $l->id === $lesson->id ? 'rgba(99,102,241,.1)' : 'transparent' }};
               border:1.5px solid {{ $l->id === $lesson->id ? 'var(--edu-accent)' : 'transparent' }}">
        <div style="width:24px;height:24px;border-radius:50%;flex-shrink:0;
                    display:flex;align-items:center;justify-content:center;font-size:11px;
                    background:{{ in_array($l->id, collect($lessons)->pluck('id')->toArray()) ? 'var(--edu-border)' : 'var(--edu-border)' }};
                    color:var(--edu-muted)">
          {{ $l->order }}
        </div>
        <span style="font-size:13px;font-weight:{{ $l->id === $lesson->id ? '600' : '400' }};
                     color:{{ $l->id === $lesson->id ? 'var(--edu-accent)' : 'var(--edu-text)' }}">
          {{ $l->title }}
        </span>
      </a>
      @endforeach
    </div>
  </div>
</div>
@endsection