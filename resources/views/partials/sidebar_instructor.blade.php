<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="bi bi-lightning-charge-fill"></i></div>
    <div class="logo-text">Edu<span>Flow</span></div>
  </div>

  <div class="sidebar-role-badge badge-instructor">Instructor</div>

  <nav class="nav-section">
    <div class="nav-label">Main</div>
    <a href="{{ route('dashboard') }}"
       class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <i class="bi bi-grid-1x2"></i> Dashboard
    </a>
    <a href="{{ route('instructor.courses.index') }}"
       class="nav-item {{ request()->routeIs('instructor.courses.index') ? 'active' : '' }}">
      <i class="bi bi-collection"></i> My Courses
    </a>
    <a href="{{ route('instructor.courses.create') }}"
       class="nav-item {{ request()->routeIs('instructor.courses.create') ? 'active' : '' }}">
      <i class="bi bi-journal-plus"></i> Create Course
    </a>
    <a href="{{ route('instructor.students') }}"
       class="nav-item {{ request()->routeIs('instructor.students') ? 'active' : '' }}">
      <i class="bi bi-people"></i> Students
    </a>

    <div class="nav-label mt-2">Tools</div>
    <a href="#"
       class="nav-item {{ request()->routeIs('instructor.analytics') ? 'active' : '' }}">
      <i class="bi bi-bar-chart-line"></i> Analytics
    </a>
    <a href="#"
       class="nav-item {{ request()->routeIs('instructor.profile') ? 'active' : '' }}">
      <i class="bi bi-person-circle"></i> Profile
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="user-info">
      <div class="user-avatar" style="background: linear-gradient(135deg,#06b6d4,#10b981)">
        {{ Str::upper(Str::substr(auth()->user()->name, 0, 2)) }}
      </div>
      <div>
        <div class="user-name">{{ auth()->user()->name }}</div>
        <div class="user-role-text">Instructor</div>
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