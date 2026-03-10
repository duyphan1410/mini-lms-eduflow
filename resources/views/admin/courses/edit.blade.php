@extends('layouts.app')
@section('title', 'Edit Course')
@section('sidebar') @include('partials.sidebar_admin') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Edit Course</h2>
    <p>Update course information.</p>
  </div>
  <a href="{{ route('admin.courses.index') }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<div class="card-box" style="max-width:560px">
  <form method="POST" action="{{ route('admin.courses.update', $course) }}">
    @csrf @method('PUT')

    <div class="field-group">
      <label class="field-label">Title</label>
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
        class="field-input {{ $errors->has('description') ? 'is-invalid' : '' }}"
        style="padding-left:14px;height:auto">{{ old('description', $course->description) }}</textarea>
      @error('description') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Category</label>
      <div class="field-wrap">
        <i class="bi bi-tags field-icon"></i>
        <select name="category_id" class="field-input" style="padding-left:40px;width:100%">
          @foreach($categories as $category)
            <option value="{{ $category->id }}"
              {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>
      @error('category_id') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Status</label>
      <div class="field-wrap">
        <i class="bi bi-toggle-on field-icon"></i>
        <select name="status" class="field-input" style="padding-left:40px;width:100%">
          <option value="draft"     {{ old('status', $course->status) === 'draft'     ? 'selected' : '' }}>Draft</option>
          <option value="pending"   {{ old('status', $course->status) === 'pending'      ? 'selected' : '' }}>Pending</option>
          <option value="published" {{ old('status', $course->status) === 'published' ? 'selected' : '' }}>Published</option>
        </select>
      </div>
    </div>

    <div class="d-flex gap-2 mt-3">
      <button type="submit" class="btn-primary-edu">
        <i class="bi bi-check-lg"></i> Save Changes
      </button>
      <a href="{{ route('admin.courses.index') }}" class="btn-outline-edu">Cancel</a>
    </div>
  </form>
</div>
@endsection