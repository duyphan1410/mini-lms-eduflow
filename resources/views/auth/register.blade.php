@extends('layouts.auth')

@section('title', 'Register')

@section('left-content')
<div class="left-content">
  <div class="left-tagline">
    Start your<br><span>journey.</span>
  </div>
  <p class="left-sub">Tạo tài khoản miễn phí và bắt đầu học ngay hôm nay.</p>

  <div class="feature-list">
    <div class="feature-item">
      <div class="feature-icon" style="background:rgba(99,102,241,.15)">
        <i class="bi bi-collection-play" style="color:#a5b4fc"></i>
      </div>
      <div class="feature-text">
        <strong>86+ Khóa học</strong>
        Đa dạng từ Web Dev đến Data Science
      </div>
    </div>
    <div class="feature-item">
      <div class="feature-icon" style="background:rgba(6,182,212,.15)">
        <i class="bi bi-graph-up-arrow" style="color:#67e8f9"></i>
      </div>
      <div class="feature-text">
        <strong>Theo dõi tiến độ</strong>
        Dashboard cá nhân hiển thị % hoàn thành
      </div>
    </div>
    <div class="feature-item">
      <div class="feature-icon" style="background:rgba(16,185,129,.15)">
        <i class="bi bi-patch-question" style="color:#6ee7b7"></i>
      </div>
      <div class="feature-text">
        <strong>Quiz tương tác</strong>
        Kiểm tra kiến thức sau mỗi bài học
      </div>
    </div>
    <div class="feature-item">
      <div class="feature-icon" style="background:rgba(245,158,11,.15)">
        <i class="bi bi-trophy" style="color:#fcd34d"></i>
      </div>
      <div class="feature-text">
        <strong>Chứng chỉ hoàn thành</strong>
        Nhận certificate khi hoàn thành khóa học
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')

<div class="auth-title">Create account ✨</div>
<p class="auth-sub">Đăng ký miễn phí — không cần thẻ tín dụng.</p>

@if($errors->any())
  <div class="alert-edu">
    <i class="bi bi-exclamation-circle-fill"></i>
    {{ $errors->first() }}
  </div>
@endif

<form method="POST" action="{{ route('register') }}">
  @csrf

  {{-- Name --}}
  <div class="field-group">
    <label class="field-label" for="name">Họ và tên</label>
    <div class="field-wrap">
      <i class="bi bi-person field-icon"></i>
      <input
        type="text" id="name" name="name"
        class="field-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
        value="{{ old('name') }}"
        placeholder="Nguyễn Văn A"
        required autofocus
      >
    </div>
    @error('name')
      <div class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
    @enderror
  </div>

  {{-- Email --}}
  <div class="field-group">
    <label class="field-label" for="email">Email</label>
    <div class="field-wrap">
      <i class="bi bi-envelope field-icon"></i>
      <input
        type="email" id="email" name="email"
        class="field-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
        value="{{ old('email') }}"
        placeholder="you@example.com"
        required
      >
    </div>
    @error('email')
      <div class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
    @enderror
  </div>

  {{-- Password --}}
  <div class="field-group">
    <label class="field-label" for="password">Password</label>
    <div class="field-wrap">
      <i class="bi bi-lock field-icon"></i>
      <input
        type="password" id="password" name="password"
        class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
        placeholder="Tối thiểu 8 ký tự"
        required
        oninput="checkStrength(this.value)"
      >
      <button type="button" class="toggle-pw" onclick="togglePassword('password', 'pw-icon')">
        <i class="bi bi-eye" id="pw-icon"></i>
      </button>
    </div>
    <div class="pw-strength">
      <div class="pw-strength-bar">
        <div class="pw-strength-fill" id="pw-fill"></div>
      </div>
      <div class="pw-strength-label" id="pw-label"></div>
    </div>
    @error('password')
      <div class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
    @enderror
  </div>

  {{-- Confirm Password --}}
  <div class="field-group">
    <label class="field-label" for="password_confirmation">Xác nhận Password</label>
    <div class="field-wrap">
      <i class="bi bi-lock-fill field-icon"></i>
      <input
        type="password" id="password_confirmation" name="password_confirmation"
        class="field-input"
        placeholder="Nhập lại password"
        required
      >
      <button type="button" class="toggle-pw" onclick="togglePassword('password_confirmation', 'pw2-icon')">
        <i class="bi bi-eye" id="pw2-icon"></i>
      </button>
    </div>
  </div>

  {{-- Note role --}}
  <div class="role-note">
    <i class="bi bi-info-circle-fill"></i>
    <span>
      Tài khoản đăng ký mặc định là <strong>Student</strong>.
      Liên hệ admin nếu muốn trở thành Instructor.
    </span>
  </div>

  <button type="submit" class="btn-submit">
    <i class="bi bi-person-plus"></i> Tạo tài khoản
  </button>
</form>

<div class="divider">or</div>

<div class="bottom-link">
  Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
</div>

@endsection