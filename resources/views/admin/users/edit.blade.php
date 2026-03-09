@extends('layouts.app')
@section('title', 'Edit User')
@section('sidebar') @include('partials.sidebar_admin') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Edit User</h2>
    <p>Update user information.</p>
  </div>
  <a href="{{ route('admin.users.index') }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<div class="card-box" style="max-width:560px">
  <form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf @method('PUT')

    <div class="field-group">
      <label class="field-label">Full Name</label>
      <div class="field-wrap">
        <i class="bi bi-person field-icon"></i>
        <input type="text" name="name" value="{{ old('name', $user->name) }}"
          class="field-input {{ $errors->has('name') ? 'is-invalid' : '' }}" required>
      </div>
      @error('name') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Email</label>
      <div class="field-wrap">
        <i class="bi bi-envelope field-icon"></i>
        <input type="email" name="email" value="{{ old('email', $user->email) }}"
          class="field-input {{ $errors->has('email') ? 'is-invalid' : '' }}" required>
      </div>
      @error('email') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Role</label>
      <div class="field-wrap">
        <i class="bi bi-shield field-icon"></i>
        <select name="role" class="field-input" style="padding-left:40px">
          <option value="student"    {{ old('role', $user->role) === 'student'    ? 'selected' : '' }}>Student</option>
          <option value="instructor" {{ old('role', $user->role) === 'instructor' ? 'selected' : '' }}>Instructor</option>
          <option value="admin"      {{ old('role', $user->role) === 'admin'      ? 'selected' : '' }}>Admin</option>
        </select>
      </div>
      @error('role') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="d-flex gap-2 mt-3">
      <button type="submit" class="btn-primary-edu">
        <i class="bi bi-check-lg"></i> Save Changes
      </button>
      <a href="{{ route('admin.users.index') }}" class="btn-outline-edu">Cancel</a>
    </div>
  </form>
</div>
@endsection