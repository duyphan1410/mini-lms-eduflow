@extends('layouts.app')
@section('title', 'My Courses')
@section('sidebar') @include('partials.sidebar_student') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>My Courses</h2>
    <p>Các khóa học bạn đã đăng ký.</p>
  </div>
  <a href="{{ route('student.courses.index') }}" class="btn-primary-edu">
    <i class="bi bi-search me-1"></i> Browse More
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if($enrollments->isEmpty())
  <div class="card-box text-center" style="color:var(--edu-muted);padding:48px">
    <i class="bi bi-journal-bookmark" style="font-size:40px;display:block;margin-bottom:12px"></i>
    <div style="font-weight:600;margin-bottom:6px">Chưa có khóa học nào</div>
    <a href="{{ route('student.courses.index') }}" class="btn-primary-edu mt-2">
      Khám phá ngay
    </a>
  </div>
@else
<div class="row g-3">
  @foreach($enrollments as $enrollment)
  <div class="col-md-4">
    <div class="course-card">
      <div class="course-thumb thumb-{{ ($loop->index % 6) + 1 }}"><i class="bi bi-file-code"></i></div>
      <div class="course-body">
        <span class="course-category" style="background:rgba(99,102,241,.1);color:var(--edu-accent)">
          {{ $enrollment->course->category->name ?? 'Course' }}
        </span>
        <div class="course-title">{{ $enrollment->course->title }}</div>
        <div class="course-meta">
          <span><i class="bi bi-person me-1"></i>{{ $enrollment->course->instructor->name }}</span>
        </div>

        {{-- Progress bar --}}
        <div class="mt-2">
          <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--edu-muted);margin-bottom:4px">
            <span>Progress</span>
            <span>{{ $enrollment->progress_percent }}%</span>
          </div>
          <div class="progress-bar-custom">
            <div class="progress-fill"
              style="width:{{ $enrollment->progress_percent }}%;
                     background:{{ $enrollment->progress_percent === 100 ? 'var(--edu-green)' : 'var(--edu-accent)' }}">
            </div>
          </div>
        </div>

        <a href="{{ route('student.courses.show', $enrollment->course) }}"
          class="btn-primary-edu w-100 justify-content-center mt-3">
          <i class="bi bi-play-fill me-1"></i>
          {{ $enrollment->progress_percent === 100 ? 'Review' : 'Continue' }}
        </a>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endif
@endsection