<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduFlow — @yield('title')</title>

    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
</head>
<body>

{{-- Left decorative panel — mỗi trang tự định nghĩa nội dung --}}
<div class="left-panel">
  <div class="logo">
    <div class="logo-icon"><i class="bi bi-lightning-charge-fill"></i></div>
    <div class="logo-text">Edu<span>Flow</span></div>
  </div>

  @yield('left-content')

  <div class="left-footer">© {{ date('Y') }} EduFlow. All rights reserved.</div>
</div>

{{-- Right form panel --}}
<div class="right-panel">
  <div class="auth-card">
    @yield('content')
  </div>
</div>

{{-- Bootstrap JS --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  @yield('scripts')
</body>
</html>