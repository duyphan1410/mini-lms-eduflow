@extends('layouts.auth')

@section('title', 'Login')

@section('left-content')
<div class="left-content">
  <div class="left-tagline">
    Learn without<br><span>limits.</span>
  </div>
  <p class="left-sub">
    Nền tảng học tập hiện đại — khóa học chất lượng, theo dõi tiến độ thực tế, quiz tương tác.
  </p>
  <div class="stats-row">
    <div class="stat-chip">
      <div class="val">86+</div>
      <div class="lbl">Courses</div>
    </div>
    <div class="stat-chip">
      <div class="val">1.2k</div>
      <div class="lbl">Students</div>
    </div>
    <div class="stat-chip">
      <div class="val">42</div>
      <div class="lbl">Instructors</div>
    </div>
  </div>
</div>
@endsection

@section('content')

    <div class="auth-title">Welcome back 👋</div>
    <p class="auth-sub">Đăng nhập để tiếp tục học tập của bạn.</p>

    {{-- Error / Status --}}
    @if($errors->any())
        <div class="alert-edu">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('status'))
        <div class="alert-edu success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
    @csrf

        <div class="field-group">
            <label class="field-label" for="email">Email</label>
            <div class="field-wrap">
            <i class="bi bi-envelope field-icon"></i>
            <input
                type="email" id="email" name="email"
                class="field-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                value="{{ old('email') }}"
                placeholder="you@example.com"
                required autofocus
            >
            </div>
            @error('email')
            <div class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
            @enderror
        </div>

        <div class="field-group">
            <label class="field-label" for="password">Password</label>
            <div class="field-wrap">
                <i class="bi bi-lock field-icon"></i>
                <input
                    type="password" id="password" name="password"
                    class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="••••••••"
                    required
                >
                <button type="button" class="toggle-pw" onclick="togglePassword('password', 'pw-icon')">
                    <i class="bi bi-eye" id="pw-icon"></i>
                </button>
            </div>
            @error('password')
                <div class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
            @enderror
        </div>

    <div class="remember-row">
        <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Remember me
        </label>
    </div>

    <button type="submit" class="btn-submit">
        <i class="bi bi-arrow-right-circle"></i> Sign In
    </button>
    </form>

    <div class="divider">or</div>

    <div class="bottom-link">
        Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
    </div>

@endsection