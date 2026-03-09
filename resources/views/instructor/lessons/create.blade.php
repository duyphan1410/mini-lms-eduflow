@extends('layouts.app')
@section('title', 'Add Lesson')
@section('sidebar') @include('partials.sidebar_instructor') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Add Lesson</h2>
    <p>{{ $course->title }}</p>
  </div>
  <a href="{{ route('instructor.courses.show', $course) }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<div class="card-box" style="max-width:620px">
  <form method="POST" action="{{ route('instructor.courses.lessons.store', $course) }}">
    @csrf

    <div class="field-group">
      <label class="field-label">Lesson Title</label>
      <div class="field-wrap">
        <i class="bi bi-journal-text field-icon"></i>
        <input type="text" name="title" value="{{ old('title') }}"
          class="field-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
          placeholder="e.g. Introduction to HTML" required autofocus>
      </div>
      @error('title') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Content</label>
      <textarea name="content" rows="8"
        class="field-input {{ $errors->has('content') ? 'is-invalid' : '' }}"
        style="padding-left:14px;height:auto"
        placeholder="Nội dung bài học...">{{ old('content') }}</textarea>
      @error('content') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Order <span style="color:var(--edu-muted);font-weight:400">(optional — tự động nếu để trống)</span></label>
      <div class="field-wrap">
        <i class="bi bi-sort-numeric-up field-icon"></i>
        <input type="number" name="order" value="{{ old('order') }}"
          class="field-input" min="0" placeholder="1">
      </div>
    </div>

    <div class="d-flex gap-2 mt-3">
      <button type="submit" class="btn-primary-edu">
        <i class="bi bi-plus-lg"></i> Add Lesson
      </button>
      <a href="{{ route('instructor.courses.show', $course) }}" class="btn-outline-edu">Cancel</a>
    </div>
  </form>
</div>
@endsection