<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="bi bi-lightning-charge-fill"></i></div>
    <div class="logo-text">Edu<span>Flow</span></div>
  </div>

  <div class="sidebar-role-badge badge-student">Student</div>

  <nav class="nav-section">
    <div class="nav-label">Main</div>
    <a href="{{ route('dashboard') }}"
       class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <i class="bi bi-grid-1x2"></i> Dashboard
    </a>
    <a href="{{ route('student.courses.index') }}"
       class="nav-item {{ request()->routeIs('student.courses.*') ? 'active' : '' }}">
      <i class="bi bi-collection-play"></i> Browse Courses
    </a>
    <a href="{{ route('student.my-courses') }}"
       class="nav-item {{ request()->routeIs('student.my-courses') ? 'active' : '' }}">
      <i class="bi bi-bookmark-heart"></i> My Courses
    </a>
    <a href="{{ route('student.progress') }}"
       class="nav-item {{ request()->routeIs('student.progress') ? 'active' : '' }}">
      <i class="bi bi-graph-up-arrow"></i> My Progress
    </a>

    <div class="nav-label mt-2">Account</div>
    <a href="#"
       class="nav-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
      <i class="bi bi-person-circle"></i> Profile
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="user-info">
      {{-- Avatar chữ cái đầu tên --}}
      <div class="user-avatar" style="background: linear-gradient(135deg,#6366f1,#06b6d4)">
        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
      </div>
      <div>
        <div class="user-name">{{ auth()->user()->name }}</div>
        <div class="user-role-text">Student</div>
      </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="nav-item mt-2" style="background:none;border:none;padding-left:0;width:100%">
        <i class="bi bi-box-arrow-right text-red"></i>
        <span style="color:#ef4444">Logout</span>
      </button>
    </form>
  </div>
</aside>