<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="bi bi-lightning-charge-fill"></i></div>
    <div class="logo-text">Edu<span>Flow</span></div>
  </div>

  <div class="sidebar-role-badge badge-admin">Admin</div>

  <nav class="nav-section">
    <div class="nav-label">Overview</div>
    <a href="{{ route('dashboard') }}"
       class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <i class="bi bi-grid-1x2"></i> Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}"
       class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
      <i class="bi bi-people"></i> User Management
    </a>
    <a href="{{ route('admin.courses.index') }}"
       class="nav-item {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
      <i class="bi bi-collection"></i> Courses
    </a>
    <a href="{{ route('admin.categories.index') }}"
       class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
      <i class="bi bi-tags"></i> Categories
    </a>

    <div class="nav-label mt-2">System</div>
    <a href="#"
       class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
      <i class="bi bi-bar-chart-steps"></i> Reports
    </a>
    <a href="#"
       class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
      <i class="bi bi-gear"></i> Settings
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="user-info">
      <div class="user-avatar" style="background: linear-gradient(135deg,#ef4444,#f59e0b)">
        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
      </div>
      <div>
        <div class="user-name">{{ auth()->user()->name }}</div>
        <div class="user-role-text">Super Admin</div>
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