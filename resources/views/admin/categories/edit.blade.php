@extends('layouts.app')
@section('title', 'Edit Category')
@section('sidebar') @include('partials.sidebar_admin') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Edit Category</h2>
    <p>Update category name.</p>
  </div>
  <a href="{{ route('admin.categories.index') }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<div class="card-box" style="max-width:480px">
  <form method="POST" action="{{ route('admin.categories.update', $category) }}">
    @csrf @method('PUT')

    <div class="field-group">
      <label class="field-label">Category Name</label>
      <div class="field-wrap">
        <i class="bi bi-tags field-icon"></i>
        <input type="text" name="name" value="{{ old('name', $category->name) }}"
          class="field-input {{ $errors->has('name') ? 'is-invalid' : '' }}" required autofocus>
      </div>
      @error('name') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Current Slug</label>
      <input type="text" value="{{ $category->slug }}" class="field-input"
        style="padding-left:14px;background:var(--edu-surface);color:var(--edu-muted)" disabled>
      <div style="font-size:11px;color:var(--edu-muted);margin-top:5px">
        Slug sẽ tự động cập nhật khi đổi tên.
      </div>
    </div>

    <div class="d-flex gap-2 mt-3">
      <button type="submit" class="btn-primary-edu">
        <i class="bi bi-check-lg"></i> Save Changes
      </button>
      <a href="{{ route('admin.categories.index') }}" class="btn-outline-edu">Cancel</a>
    </div>
  </form>
</div>
@endsection