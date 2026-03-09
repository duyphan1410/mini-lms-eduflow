@extends('layouts.app')
@section('title', 'Create User')
@section('sidebar') @include('partials.sidebar_admin') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Create User</h2>
    <p>Add a new user to the system.</p>
  </div>
  <a href="{{ route('admin.users.index') }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<div class="card-box" style="max-width:560px">
  <form method="POST" action="{{ route('admin.users.store') }}">
    @csrf

    <div class="field-group">
      <label class="field-label">Full Name</label>
      <div class="field-wrap">
        <i class="bi bi-person field-icon"></i>
        <input type="text" name="name" value="{{ old('name') }}"
          class="field-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
          placeholder="Nguyễn Văn A" required>
      </div>
      @error('name') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Email</label>
      <div class="field-wrap">
        <i class="bi bi-envelope field-icon"></i>
        <input type="email" name="email" value="{{ old('email') }}"
          class="field-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
          placeholder="user@example.com" required>
      </div>
      @error('email') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Role</label>
      <div class="field-wrap">
        <i class="bi bi-shield field-icon"></i>
        <select name="role" class="field-input {{ $errors->has('role') ? 'is-invalid' : '' }}"
          style="padding-left:40px">
          <option value="student"    {{ old('role') === 'student'    ? 'selected' : '' }}>Student</option>
          <option value="instructor" {{ old('role') === 'instructor' ? 'selected' : '' }}>Instructor</option>
          <option value="admin"      {{ old('role') === 'admin'      ? 'selected' : '' }}>Admin</option>
        </select>
      </div>
      @error('role') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Password</label>
      <div class="field-wrap">
        <i class="bi bi-lock field-icon"></i>
        <input type="password" name="password"
          class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
          placeholder="Min 8 characters" required>
      </div>
      @error('password') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="field-group">
      <label class="field-label">Confirm Password</label>
      <div class="field-wrap">
        <i class="bi bi-lock-fill field-icon"></i>
        <input type="password" name="password_confirmation"
          class="field-input" placeholder="Repeat password" required>
      </div>
    </div>

    <div class="d-flex gap-2 mt-3">
      <button type="submit" class="btn-primary-edu">
        <i class="bi bi-person-plus"></i> Create User
      </button>
      <a href="{{ route('admin.users.index') }}" class="btn-outline-edu">Cancel</a>
    </div>
  </form>
</div>
@endsection