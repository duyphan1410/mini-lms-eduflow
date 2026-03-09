@extends('layouts.app')
@section('title', 'My Courses')
@section('sidebar') @include('partials.sidebar_instructor') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>My Courses</h2>
    <p>Manage your courses and lessons.</p>
  </div>
  <a href="{{ route('instructor.courses.create') }}" class="btn-primary-edu">
    <i class="bi bi-plus-lg"></i> New Course
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="data-table">
  <table>
    <thead>
      <tr>
        <th>Course</th>
        <th>Category</th>
        <th>Lessons</th>
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
          <div style="font-size:11px;color:var(--edu-muted)">{{ $course->created_at->format('d M Y') }}</div>
        </td>
        <td>
          <span class="badge-pill" style="background:rgba(99,102,241,.1);color:var(--edu-accent)">
            {{ $course->category->name ?? '—' }}
          </span>
        </td>
        <td>{{ $course->lessons_count }}</td>
        <td>{{ $course->enrollments_count }}</td>
        <td>
          @if($course->status === 'published')
            <span class="badge-pill" style="background:rgba(16,185,129,.1);color:var(--edu-green)">Published</span>
          @elseif($course->status === 'pending')
            <span class="badge-pill" style="background:rgba(255,193,7,.1);color:#d97706">Pending</span>
          @else
            <span class="badge-pill" style="background:rgba(245,158,11,.1);color:var(--edu-amber)">Draft</span>
          @endif
        </td>
        <td>
          <a href="{{ route('instructor.courses.show', $course) }}"
            class="btn-outline-edu btn-sm py-1 px-2 me-1">
            <i class="bi bi-eye"></i>
          </a>
          <a href="{{ route('instructor.courses.edit', $course) }}"
            class="btn-outline-edu btn-sm py-1 px-2 me-1">
            <i class="bi bi-pencil"></i>
          </a>
          @if($course->status === 'draft')
          <form method="POST" action="{{ route('instructor.courses.submit', $course) }}" class="d-inline">
            @csrf @method('PATCH')
            <button type="submit" class="btn-primary-edu btn-sm py-1 px-2" style="font-size:11px">
              <i class="bi bi-send"></i> Submit
            </button>
          </form>
          @endif
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" style="text-align:center;color:var(--edu-muted);padding:32px">
          Chưa có khóa học nào. <a href="{{ route('instructor.courses.create') }}">Tạo ngay!</a>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-3">{{ $courses->links() }}</div>
@endsection