@extends('layouts.app')
@section('title', $course->title)
@section('sidebar') @include('partials.sidebar_student') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>{{ $course->title }}</h2>
    <p>{{ $course->instructor->name }} · {{ $course->category->name ?? '' }}</p>
  </div>
  <a href="{{ route('student.courses.index') }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="row g-3">
  {{-- Lessons --}}
  <div class="col-md-8">
    <div class="section-title">Lessons ({{ $course->lessons->count() }})</div>

    @if($course->lessons->isEmpty())
    <div class="card-box text-center" style="color:var(--edu-muted)">Chưa có lesson nào.</div>
    @else
    <div style="display:flex;flex-direction:column;gap:8px">
      @foreach($course->lessons as $lesson)
      <div class="card-box" style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px">
        <div style="display:flex;align-items:center;gap:12px">
          {{-- Completed indicator --}}
          @if($isEnrolled && in_array($lesson->id, $completedLessonIds))
            <div style="width:28px;height:28px;border-radius:50%;background:rgba(16,185,129,.15);
                        display:flex;align-items:center;justify-content:center;flex-shrink:0">
              <i class="bi bi-check-lg" style="color:var(--edu-green);font-size:14px"></i>
            </div>
          @else
            <div style="width:28px;height:28px;border-radius:50%;background:var(--edu-border);
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;
                        font-size:11px;font-weight:700;color:var(--edu-muted)">
              {{ $lesson->order }}
            </div>
          @endif
          <div>
            <div style="font-weight:600;font-size:13px">{{ $lesson->title }}</div>
          </div>
        </div>
        @if($isEnrolled)
          <a href="{{ route('student.lessons.show', $lesson) }}" class="btn-outline-edu btn-sm py-1 px-3">
            <i class="bi bi-play-fill me-1"></i> Learn
          </a>
        @else
          <span style="font-size:12px;color:var(--edu-muted)"><i class="bi bi-lock"></i> Enroll to access</span>
        @endif
      </div>
      @endforeach
    </div>
    @endif
  </div>

  {{-- Course sidebar --}}
  <div class="col-md-4">
    <div class="card-box">
      @if($course->thumbnail)
        <img src="{{ asset('storage/' . $course->thumbnail) }}"
          style="width:100%;border-radius:8px;margin-bottom:14px;object-fit:cover;height:140px">
      @else
        <div class="course-thumb thumb-1 mb-3" style="border-radius:8px;height:120px"><i class="bi bi-file-code"></i></div>
      @endif

      <div style="font-size:13px;color:#475569;margin-bottom:16px;line-height:1.6">
        {{ $course->description ?? 'Không có mô tả.' }}
      </div>

      <div style="display:flex;flex-direction:column;gap:8px;font-size:13px;margin-bottom:16px">
        <div style="display:flex;gap:8px">
          <i class="bi bi-person" style="color:var(--edu-muted)"></i>
          {{ $course->instructor->name }}
        </div>
        <div style="display:flex;gap:8px">
          <i class="bi bi-journal-text" style="color:var(--edu-muted)"></i>
          {{ $course->lessons->count() }} lessons
        </div>
        <div style="display:flex;gap:8px">
          <i class="bi bi-people" style="color:var(--edu-muted)"></i>
          {{ $course->enrollments_count }} students enrolled
        </div>
      </div>

      @if(!$isEnrolled)
      <form method="POST" action="{{ route('student.enroll', $course) }}">
        @csrf
        <button type="submit" class="btn-primary-edu w-100 justify-content-center">
          <i class="bi bi-plus-circle me-1"></i> Enroll Now — Free
        </button>
      </form>
      @else
      <div class="btn-primary-edu w-100 justify-content-center" style="opacity:.8;cursor:default">
        <i class="bi bi-check-circle me-1"></i> Enrolled
      </div>
      @endif
    </div>
  </div>
</div>
@endsection