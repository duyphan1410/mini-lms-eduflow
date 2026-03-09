@extends('layouts.app')

@section('title', 'Instructor Dashboard')

@section('sidebar')
  @include('partials.sidebar_instructor')
@endsection

@section('content')

{{-- Topbar --}}
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Instructor Dashboard 👨‍🏫</h2>
    <p>Manage your courses and track student performance.</p>
  </div>
  <a href="{{ route('instructor.courses.create') }}" class="btn-primary-edu">
    <i class="bi bi-plus-lg"></i> New Course
  </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="stat-card">
      <div class="icon-wrap bg-indigo-soft"><i class="bi bi-collection text-indigo" style="font-size:20px"></i></div>
      <div class="stat-value">{{ $totalCourses }}</div>
      <div class="stat-label">Total Courses</div>
      <div class="stat-change" style="color:var(--edu-muted)">
        {{ $publishedCourses }} published · {{ $draftCourses }} draft
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="icon-wrap bg-cyan-soft"><i class="bi bi-people text-cyan" style="font-size:20px"></i></div>
      <div class="stat-value">{{ $totalStudents }}</div>
      <div class="stat-label">Total Students</div>
      <div class="stat-change trend-up"><i class="bi bi-arrow-up-short"></i> +{{ $newStudentsThisWeek }} this week</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="icon-wrap bg-green-soft"><i class="bi bi-star text-green" style="font-size:20px"></i></div>
      <div class="stat-value">{{ number_format($avgRating, 1) }}</div>
      <div class="stat-label">Avg Rating</div>
      <div class="stat-change trend-up"><i class="bi bi-arrow-up-short"></i> Top instructor</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="icon-wrap bg-amber-soft"><i class="bi bi-bar-chart text-amber" style="font-size:20px"></i></div>
      <div class="stat-value">{{ $completionRate }}%</div>
      <div class="stat-label">Completion Rate</div>
    </div>
  </div>
</div>

<div class="row g-3">

  {{-- My Courses Table --}}
  <div class="col-md-7">
    <div class="section-title">My Courses</div>
    <div class="data-table">
      <table>
        <thead>
          <tr>
            <th>Course</th>
            <th>Students</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($courses as $course)
          <tr>
            <td>
              <div style="font-weight:600;font-size:13px">{{ $course->title }}</div>
              <div style="font-size:11px;color:var(--edu-muted)">
                {{ $course->lessons_count }} lessons · {{ $course->quizzes_count }} quizzes
              </div>
            </td>
            <td><span style="font-weight:600">{{ $course->enrollments_count }}</span></td>
            <td>
              @if($course->status === 'published')
                <span class="badge-pill" style="background:rgba(16,185,129,.1);color:var(--edu-green)">Published</span>
              @elseif($course->status === 'pending')
                <span class="badge-pill" style="background:rgba(255,193,7,.1);;color:#d97706">Pending</span>
              @else
                <span class="badge-pill" style="background:rgba(245,158,11,.1);color:var(--edu-amber)">Draft</span>
              @endif
            </td>
            <td>
              <a href="{{ route('instructor.courses.edit', $course->id) }}"
                 class="btn-outline-edu btn-sm py-1 px-2 me-1">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="{{ route('instructor.courses.students', $course->id) }}"
                 class="btn-outline-edu btn-sm py-1 px-2">
                <i class="bi bi-people"></i>
              </a>
              @if($course->status === 'draft')
              <form method="POST" action="{{ route('instructor.courses.submit', $course->id) }}" class="d-inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn-primary-edu btn-sm py-1 px-2 ms-1">Submit</button>
              </form>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" style="text-align:center;color:var(--edu-muted);padding:24px">
              Chưa có khóa học nào. <a href="{{ route('instructor.courses.create') }}">Tạo ngay!</a>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Recent Enrollments --}}
  <div class="col-md-5">
    <div class="section-title">Recent Enrollments</div>
    <div class="card-box">
      <div style="display:flex;flex-direction:column;gap:14px">
        @forelse($recentEnrollments as $enrollment)
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div style="display:flex;align-items:center;gap:10px">
            <div class="avatar-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
              {{ Str::upper(Str::substr($enrollment->user->name, 0, 2)) }}
            </div>
            <div>
              <div style="font-size:13px;font-weight:600">{{ $enrollment->user->name }}</div>
              <div style="font-size:11px;color:var(--edu-muted)">{{ $enrollment->course->title }}</div>
            </div>
          </div>
          <div>
            <div class="progress-bar-custom" style="width:80px">
              <div class="progress-fill" style="width:{{ $enrollment->progress_percent }}%;background:var(--edu-accent)"></div>
            </div>
            <div style="font-size:10px;color:var(--edu-muted);text-align:right;margin-top:3px">
              {{ $enrollment->progress_percent }}%
            </div>
          </div>
        </div>
        @empty
        <p style="color:var(--edu-muted);font-size:13px;text-align:center">Chưa có học viên nào</p>
        @endforelse
      </div>
    </div>
  </div>

</div>
@endsection