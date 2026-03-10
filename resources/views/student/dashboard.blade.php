@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('sidebar')
  @include('partials.sidebar_student')
@endsection

@section('content')

{{-- Topbar --}}
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Good morning, {{ Str::ascii(auth()->user()->name) }} 👋</h2>
    <p>You have {{ $pendingLessons }} lessons to continue today.</p>
  </div>
  
  <form method="GET" action="{{ route('student.courses.index') }}" class="d-flex">
  <div class="search-box" style="cursor:text;padding:0">
    <i class="bi bi-search" style="padding-left:14px;color:var(--edu-muted)"></i>
    <input type="text" name="search" 
      placeholder="Search courses..."
      style="border:none;outline:none;background:transparent;font-size:13px;padding:8px 14px;width:200px;color:var(--edu-navy)">
  </div>
</form>
</div>

{{-- Continue Learning Banner --}}
@if($lastLesson)
<div class="continue-banner">
  <div>
    <div style="font-size:11px;opacity:.7;text-transform:uppercase;letter-spacing:.1em;margin-bottom:6px">▶ Continue Learning</div>
    <div class="lesson-name">{{ $lastLesson->title }}</div>
    <div class="course-name">{{ $lastLesson->course->title }} · {{ $lastCourseProgress }}% completed</div>
  </div>
  <a href="{{ route('student.lessons.show', $lastLesson->id) }}" class="btn-continue">
    <i class="bi bi-play-fill me-1"></i>Resume
  </a>
</div>
@endif

{{-- Stats Row --}}
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="stat-card">
      <div class="icon-wrap bg-indigo-soft"><i class="bi bi-collection-play text-indigo" style="font-size:20px"></i></div>
      <div class="stat-value">{{ $enrolledCount }}</div>
      <div class="stat-label">Enrolled Courses</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="icon-wrap bg-green-soft"><i class="bi bi-check2-circle text-green" style="font-size:20px"></i></div>
      <div class="stat-value">{{ $completedLessons }}</div>
      <div class="stat-label">Lessons Completed</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="icon-wrap bg-amber-soft"><i class="bi bi-trophy text-amber" style="font-size:20px"></i></div>
      <div class="stat-value">{{ $avgQuizScore }}%</div>
      <div class="stat-label">Avg Quiz Score</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="icon-wrap bg-cyan-soft"><i class="bi bi-clock-history text-cyan" style="font-size:20px"></i></div>
      <div class="stat-value">{{ $studyHours }}h</div>
      <div class="stat-label">Total Study Time</div>
    </div>
  </div>
</div>

{{-- My Courses --}}
<div class="d-flex justify-content-between align-items-center mb-3">
  <div class="section-title mb-0">My Courses</div>
  <a href="{{ route('student.my-courses') }}" style="font-size:13px;color:var(--edu-accent);text-decoration:none">
    View all <i class="bi bi-arrow-right"></i>
  </a>
</div>
<div class="row g-3 mb-4">
  @forelse($enrolledCourses as $enrollment)
  @php $course = $enrollment->course; @endphp
  <div class="col-md-4">
    <div class="course-card" onclick="window.location='{{ route('student.courses.show', $course->id) }}'">
      <div class="course-thumb thumb-{{ ($loop->index % 6) + 1 }}">
        @if($course->thumbnail_emoji)
            {{ $course->thumbnail_emoji }}
        @else
            {!! '<i class="bi bi-file-code"></i>' !!}
        @endif
      </div>
      <div class="course-body">
        <span class="course-category" style="background:rgba(99,102,241,.1);color:var(--edu-accent)">
          {{ $course->category->name ?? 'Course' }}
        </span>
        <div class="course-title">{{ $course->title }}</div>
        <div class="course-meta">
          <span><i class="bi bi-play-circle me-1"></i>{{ $course->lessons_count }} lessons</span>
          <span><i class="bi bi-person me-1"></i>{{ $course->instructor->name }}</span>
        </div>
        <div class="progress-bar-custom">
          <div class="progress-fill" style="width:{{ $enrollment->progress_percent }}%;background:var(--edu-accent)"></div>
        </div>
        <div style="font-size:11px;color:var(--edu-muted);margin-top:5px">{{ $enrollment->progress_percent }}% complete</div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="card-box text-center" style="color:var(--edu-muted)">
      <i class="bi bi-collection-play" style="font-size:32px;display:block;margin-bottom:8px"></i>
      There are no courses available yet. <a href="{{ route('student.courses.index') }}">Discover now!</a>
    </div>
  </div>
  @endforelse
</div>

{{-- Available Courses --}}
<div class="d-flex justify-content-between align-items-center mb-3">
  <div class="section-title mb-0">Available Courses</div>
  <a href="{{ route('student.courses.index') }}" class="btn-outline-edu">
    <i class="bi bi-funnel me-1"></i>Browse All
  </a>
</div>
<div class="row g-3">
  @foreach($availableCourses as $course)
  <div class="col-md-4">
    <div class="course-card">
      <div class="course-thumb thumb-{{ ($loop->index % 6) + 1 }}">
        @if($course->thumbnail_emoji)
            {{ $course->thumbnail_emoji }}
        @else
            {!! '<i class="bi bi-filetype-html"></i>' !!}
        @endif
      </div>
      <div class="course-body">
        <span class="course-category" style="background:rgba(245,158,11,.1);color:var(--edu-amber)">
          {{ $course->category->name ?? 'Course' }}
        </span>
        <div class="course-title">{{ $course->title }}</div>
        <div class="course-meta">
          <span><i class="bi bi-star-fill me-1" style="color:var(--edu-amber)"></i>{{ number_format($course->avg_rating, 1) }}</span>
          <span><i class="bi bi-people me-1"></i>{{ $course->enrollments_count }} students</span>
        </div>
        <div class="mt-3">
          <form method="POST" action="{{ route('student.enroll', $course->id) }}">
            @csrf
            <button type="submit" class="btn-primary-edu w-100 justify-content-center">
              <i class="bi bi-plus-circle me-1"></i>Enroll Now
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>

@endsection