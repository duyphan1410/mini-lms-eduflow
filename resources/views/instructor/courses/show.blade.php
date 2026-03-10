@extends('layouts.app')
@section('title', $course->title)
@section('sidebar') @include('partials.sidebar_instructor') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>{{ $course->title }}</h2>
    <p>{{ $course->category->name ?? '' }} · {{ $course->enrollments_count }} students</p>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('instructor.courses.edit', $course) }}" class="btn-outline-edu">
      <i class="bi bi-pencil"></i> Edit
    </a>
    @if($course->status === 'draft')
      <form method="POST" action="{{ route('instructor.courses.submit', $course) }}">
        @csrf @method('PATCH')
        <button type="submit" class="btn-primary-edu">
          <i class="bi bi-send"></i> Submit for Review
        </button>
      </form>
    @elseif($course->status === 'pending')
      <span class="btn-primary-edu" style="opacity:.7;cursor:default;background:var(--edu-amber)">
        <i class="bi bi-hourglass-split"></i> Pending Review
      </span>
    @else
      <span class="btn-primary-edu" style="opacity:.7;cursor:default">
        <i class="bi bi-check-circle"></i> Published
      </span>
    @endif
  </div>
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

<div class="row g-3">
  {{-- Lessons list --}}
  <div class="col-md-8">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="section-title mb-0">Lessons ({{ $course->lessons->count() }})</div>
      <a href="{{ route('instructor.courses.lessons.create', $course) }}" class="btn-primary-edu">
        <i class="bi bi-plus-lg"></i> Add Lesson
      </a>
    </div>

    @if($course->lessons->isEmpty())
    <div class="card-box text-center" style="color:var(--edu-muted)">
      <i class="bi bi-journal-x" style="font-size:32px;display:block;margin-bottom:8px"></i>
      Chưa có lesson nào.
      <a href="{{ route('instructor.courses.lessons.create', $course) }}">Thêm ngay!</a>
    </div>
    @else
    <div class="data-table">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($course->lessons as $lesson)
          <tr>
            <td style="color:var(--edu-muted);font-size:12px;width:40px">{{ $lesson->order }}</td>
            <td style="font-weight:500">{{ $lesson->title }}</td>
            <td>
              <a href="{{ route('instructor.courses.lessons.edit', [$course, $lesson]) }}"
                class="btn-outline-edu btn-sm py-1 px-2 me-1">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST"
                action="{{ route('instructor.courses.lessons.destroy', [$course, $lesson]) }}"
                class="d-inline" onsubmit="return confirm('Delete this lesson?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-sm py-1 px-2"
                  style="border:1.5px solid var(--edu-red);border-radius:6px;background:transparent;color:var(--edu-red);cursor:pointer">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  {{-- Course info sidebar --}}
  <div class="col-md-4">
    <div class="section-title">Course Info</div>
    <div class="card-box">
      @if($course->thumbnail)
        <img src="{{ asset('storage/' . $course->thumbnail) }}"
          style="width:100%;border-radius:8px;margin-bottom:14px;object-fit:cover;height:140px">
      @endif
      <div style="display:flex;flex-direction:column;gap:10px;font-size:13px">
        <div style="display:flex;justify-content:space-between">
          <span style="color:var(--edu-muted)">Status</span>
          @if($course->status === 'published')
            <span class="badge-pill" style="background:rgba(16,185,129,.1);color:var(--edu-green)">Published</span>
          @elseif($course->status === 'pending')
            <span class="badge-pill" style="background:rgba(255,193,7,.1);color:#d97706">Pending</span>
          @else
            <span class="badge-pill" style="background:rgba(245,158,11,.1);color:var(--edu-amber)">Draft</span>
          @endif
        </div>
        <div style="display:flex;justify-content:space-between">
          <span style="color:var(--edu-muted)">Lessons</span>
          <span style="font-weight:600">{{ $course->lessons->count() }}</span>
        </div>
        <div style="display:flex;justify-content:space-between">
          <span style="color:var(--edu-muted)">Students</span>
          <span style="font-weight:600">{{ $course->enrollments_count }}</span>
        </div>
        <div style="display:flex;justify-content:space-between">
          <span style="color:var(--edu-muted)">Category</span>
          <span style="font-weight:600">{{ $course->category->name ?? '—' }}</span>
        </div>
        <div style="display:flex;justify-content:space-between">
          <span style="color:var(--edu-muted)">Created</span>
          <span>{{ $course->created_at->format('d M Y') }}</span>
        </div>
      </div>
    </div>

    <div class="mt-3">
      <a href="{{ route('instructor.courses.students', $course) }}" class="btn-outline-edu w-100 justify-content-center">
        <i class="bi bi-people me-1"></i> View Students
      </a>
    </div>
  </div>
</div>
@endsection