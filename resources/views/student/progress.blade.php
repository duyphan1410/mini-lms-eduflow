@extends('layouts.app')
@section('title', 'My Progress')
@section('sidebar') @include('partials.sidebar_student') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>My Progress</h2>
    <p>Theo dõi tiến độ học tập của bạn.</p>
  </div>
</div>

{{-- Summary stats --}}
@php
  $totalCourses    = $enrollments->count();
  $completedCourses = $enrollments->filter(fn($e) => $e->progress_percent === 100)->count();
  $totalLessons    = $enrollments->sum('total_lessons');
  $completedLessons = $enrollments->sum('completed_lessons');
@endphp

<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px">
  <div class="stat-card">
    <div class="stat-icon" style="background:rgba(99,102,241,.1)">
      <i class="bi bi-collection" style="color:var(--edu-accent)"></i>
    </div>
    <div class="stat-value">{{ $totalCourses }}</div>
    <div class="stat-label">Enrolled Courses</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:rgba(16,185,129,.1)">
      <i class="bi bi-trophy" style="color:var(--edu-green)"></i>
    </div>
    <div class="stat-value">{{ $completedCourses }}</div>
    <div class="stat-label">Completed</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:rgba(6,182,212,.1)">
      <i class="bi bi-journal-check" style="color:#06b6d4"></i>
    </div>
    <div class="stat-value">{{ $completedLessons }}</div>
    <div class="stat-label">Lessons Done</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:rgba(245,158,11,.1)">
      <i class="bi bi-bar-chart" style="color:var(--edu-amber)"></i>
    </div>
    <div class="stat-value">
      {{ $totalLessons > 0 ? round($completedLessons / $totalLessons * 100) : 0 }}%
    </div>
    <div class="stat-label">Overall Progress</div>
  </div>
</div>

{{-- Per-course progress --}}
@if($enrollments->isEmpty())
  <div class="card-box text-center" style="color:var(--edu-muted);padding:48px">
    <i class="bi bi-bar-chart" style="font-size:40px;display:block;margin-bottom:12px"></i>
    Chưa có tiến độ nào. <a href="{{ route('student.courses.index') }}">Đăng ký khóa học ngay!</a>
  </div>
@else
  <div class="section-title">Course Progress</div>
  <div style="display:flex;flex-direction:column;gap:12px">
    @foreach($enrollments as $enrollment)
    <div class="card-box">
      <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
        <div style="flex:1;min-width:200px">
          <div style="font-weight:600;font-size:14px;margin-bottom:2px">
            {{ $enrollment->course->title }}
          </div>
          <div style="font-size:12px;color:var(--edu-muted)">
            {{ $enrollment->course->instructor->name }}
          </div>
        </div>

        <div style="flex:2;min-width:200px">
          <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--edu-muted);margin-bottom:5px">
            <span>{{ $enrollment->completed_lessons }}/{{ $enrollment->total_lessons }} lessons</span>
            <span style="font-weight:600;color:{{ $enrollment->progress_percent === 100 ? 'var(--edu-green)' : 'var(--edu-accent)' }}">
              {{ $enrollment->progress_percent }}%
            </span>
          </div>
          <div class="progress-bar-custom">
            <div class="progress-fill"
              style="width:{{ $enrollment->progress_percent }}%;
                     background:{{ $enrollment->progress_percent === 100 ? 'var(--edu-green)' : 'var(--edu-accent)' }}">
            </div>
          </div>
        </div>

        <div>
          @if($enrollment->progress_percent === 100)
            <span class="badge-pill" style="background:rgba(16,185,129,.1);color:var(--edu-green)">
              <i class="bi bi-check-circle me-1"></i>Completed
            </span>
          @else
            <a href="{{ route('student.courses.show', $enrollment->course) }}"
              class="btn-outline-edu btn-sm py-1 px-3">
              Continue
            </a>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  </div>
@endif
@endsection