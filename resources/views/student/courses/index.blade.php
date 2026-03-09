@extends('layouts.app')
@section('title', 'Browse Courses')
@section('sidebar') @include('partials.sidebar_student') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Browse Courses</h2>
    <p>Khám phá các khóa học phù hợp với bạn.</p>
  </div>
</div>

{{-- Search + Filter --}}
<form method="GET" class="d-flex gap-2 mb-4 flex-wrap">
  <input type="text" name="search" value="{{ request('search') }}"
    class="field-input" style="max-width:280px;padding-left:14px"
    placeholder="Search courses...">
  <select name="category" class="field-input" style="max-width:180px;padding-left:14px"
    onchange="this.form.submit()">
    <option value="">All categories</option>
    @foreach($categories as $cat)
      <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
        {{ $cat->name }}
      </option>
    @endforeach
  </select>
  <button type="submit" class="btn-primary-edu px-3"><i class="bi bi-search"></i></button>
  <a href="{{ route('student.courses.index') }}" class="btn-outline-edu px-3"><i class="bi bi-x-lg"></i></a>
</form>

<div class="row g-3">
  @forelse($courses as $course)
  <div class="col-md-4">
    <div class="course-card">
      <div class="course-thumb thumb-{{ ($loop->index % 6) + 1 }}"><i class="bi bi-file-code"></i></div>
      <div class="course-body">
        <span class="course-category" style="background:rgba(99,102,241,.1);color:var(--edu-accent)">
          {{ $course->category->name ?? 'Course' }}
        </span>
        <div class="course-title">{{ $course->title }}</div>
        <div class="course-meta">
          <span><i class="bi bi-person me-1"></i>{{ $course->instructor->name }}</span>
          <span><i class="bi bi-people me-1"></i>{{ $course->enrollments_count }}</span>
        </div>
        <div class="mt-3">
          @if($enrolledIds->contains($course->id))
            <a href="{{ route('student.courses.show', $course) }}"
              class="btn-outline-edu w-100 justify-content-center">
              <i class="bi bi-play-circle me-1"></i> Continue
            </a>
          @else
            <form method="POST" action="{{ route('student.enroll', $course) }}">
              @csrf
              <button type="submit" class="btn-primary-edu w-100 justify-content-center">
                <i class="bi bi-plus-circle me-1"></i> Enroll Now
              </button>
            </form>
          @endif
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="card-box text-center" style="color:var(--edu-muted)">
      <i class="bi bi-search" style="font-size:32px;display:block;margin-bottom:8px"></i>
      Không tìm thấy khóa học nào.
    </div>
  </div>
  @endforelse
</div>
<div class="mt-3">{{ $courses->links() }}</div>
@endsection