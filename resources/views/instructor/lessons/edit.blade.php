@extends('layouts.app')
@section('title', 'Edit Lesson')
@section('sidebar') @include('partials.sidebar_instructor') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Edit Lesson</h2>
    <p>{{ $course->title }}</p>
  </div>
  <a href="{{ route('instructor.courses.show', $course) }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<div class="card-box" style="max-width:620px">
  <form method="POST" action="{{ route('instructor.courses.lessons.update', [$course, $lesson]) }}">
    @csrf @method('PUT')

    <div class="field-group">
      <label class="field-label">Lesson Title</label>
      <div class="field-wrap">
        <i class="bi bi-journal-text field-icon"></i>
        <input type="text" name="title" value="{{ old('title', $lesson->title) }}"
          class="field-input {{ $errors->has('title') ? 'is-invalid' : '' }}" required>
      </div>
      @error('title') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Content</label>
      <textarea name="content" rows="8"
        class="field-input" style="padding-left:14px;height:auto">{{ old('content', $lesson->content) }}</textarea>
    </div>

    <div class="field-group">
      <label class="field-label">Order</label>
      <div class="field-wrap">
        <i class="bi bi-sort-numeric-up field-icon"></i>
        <input type="number" name="order" value="{{ old('order', $lesson->order) }}"
          class="field-input" min="0">
      </div>
    </div>

    <div class="d-flex gap-2 mt-3">
      <button type="submit" class="btn-primary-edu">
        <i class="bi bi-check-lg"></i> Save Changes
      </button>
      <a href="{{ route('instructor.courses.show', $course) }}" class="btn-outline-edu">Cancel</a>
    </div>
  </form>
</div>
@endsection