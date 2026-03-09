@extends('layouts.app')
@section('title', 'Create Category')
@section('sidebar') @include('partials.sidebar_admin') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Create Category</h2>
    <p>Add a new course category.</p>
  </div>
  <a href="{{ route('admin.categories.index') }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<div class="card-box" style="max-width:480px">
  <form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf

    <div class="field-group">
      <label class="field-label">Category Name</label>
      <div class="field-wrap">
        <i class="bi bi-tags field-icon"></i>
        <input type="text" name="name" value="{{ old('name') }}"
          class="field-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
          placeholder="e.g. Web Development" required autofocus>
      </div>
      @error('name') <div class="field-error">{{ $message }}</div> @enderror
      <div style="font-size:11px;color:var(--edu-muted);margin-top:5px">
        Slug sẽ tự động được tạo từ tên.
      </div>
    </div>

    <div class="d-flex gap-2 mt-3">
      <button type="submit" class="btn-primary-edu">
        <i class="bi bi-plus-lg"></i> Create Category
      </button>
      <a href="{{ route('admin.categories.index') }}" class="btn-outline-edu">Cancel</a>
    </div>
  </form>
</div>
@endsection