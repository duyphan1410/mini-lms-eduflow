@extends('layouts.app')
@section('title', 'Edit Course')
@section('sidebar') @include('partials.sidebar_instructor') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Edit Course</h2>
    <p>{{ $course->title }}</p>
  </div>
  <a href="{{ route('instructor.courses.show', $course) }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<div class="card-box" style="max-width:620px">
  <form method="POST" action="{{ route('instructor.courses.update', $course) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="field-group">
      <label class="field-label">Course Title</label>
      <div class="field-wrap">
        <i class="bi bi-collection field-icon"></i>
        <input type="text" name="title" value="{{ old('title', $course->title) }}"
          class="field-input {{ $errors->has('title') ? 'is-invalid' : '' }}" required>
      </div>
      @error('title') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Description</label>
      <textarea name="description" rows="4"
        class="field-input" style="padding-left:14px;height:auto">{{ old('description', $course->description) }}</textarea>
    </div>

    <div class="field-group">
      <label class="field-label">Category</label>
      <div class="field-wrap">
        <i class="bi bi-tags field-icon"></i>
        <select name="category_id" class="field-input" style="padding-left:40px">
          @foreach($categories as $category)
            <option value="{{ $category->id }}"
              {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="field-group">
      <label class="field-label">Thumbnail</label>
      @if($course->thumbnail)
        <div class="mb-2">
          <img src="{{ asset('storage/' . $course->thumbnail) }}"
            style="height:80px;border-radius:8px;object-fit:cover">
        </div>
      @endif
      <input type="file" name="thumbnail" accept="image/*"
        class="field-input" style="padding-left:14px">
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