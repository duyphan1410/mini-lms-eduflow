@extends('layouts.app')
@section('title', 'Create Course')
@section('sidebar') @include('partials.sidebar_instructor') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Create Course</h2>
    <p>Fill in the details to create a new course.</p>
  </div>
  <a href="{{ route('instructor.courses.index') }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<div class="card-box" style="max-width:620px">
  <form method="POST" action="{{ route('instructor.courses.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="field-group">
      <label class="field-label">Course Title</label>
      <div class="field-wrap">
        <i class="bi bi-collection field-icon"></i>
        <input type="text" name="title" value="{{ old('title') }}"
          class="field-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
          placeholder="e.g. Web Development Bootcamp" required autofocus>
      </div>
      @error('title') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Description</label>
      <textarea name="description" rows="4"
        class="field-input {{ $errors->has('description') ? 'is-invalid' : '' }}"
        style="padding-left:14px;height:auto"
        placeholder="Mô tả ngắn về khóa học...">{{ old('description') }}</textarea>
      @error('description') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Category</label>
      <div class="field-wrap">
        <i class="bi bi-tags field-icon"></i>
        <select name="category_id" class="field-input {{ $errors->has('category_id') ? 'is-invalid' : '' }}"
          style="padding-left:40px">
          <option value="">-- Select category --</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>
      @error('category_id') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Thumbnail <span style="color:var(--edu-muted);font-weight:400">(optional)</span></label>
      <input type="file" name="thumbnail" accept="image/*"
        class="field-input {{ $errors->has('thumbnail') ? 'is-invalid' : '' }}"
        style="padding-left:14px">
      @error('thumbnail') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="d-flex gap-2 mt-3">
      <button type="submit" class="btn-primary-edu">
        <i class="bi bi-plus-lg"></i> Create Course
      </button>
      <a href="{{ route('instructor.courses.index') }}" class="btn-outline-edu">Cancel</a>
    </div>
  </form>
</div>
@endsection