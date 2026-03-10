@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('sidebar')
  @include('partials.sidebar_admin')
@endsection

@section('content')

{{-- Topbar --}}
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Admin Dashboard 🛡️</h2>
    <p>System overview and management controls.</p>
  </div>
  <div class="d-flex gap-2 mt-2 mt-md-0">
    <a href="#" class="btn-outline-edu" disabled style="opacity:.5;cursor:not-allowed">
      <i class="bi bi-download me-1"></i>Export
    </a>
    <a href="{{ route('admin.users.create') }}" class="btn-primary-edu">
      <i class="bi bi-person-plus me-1"></i>Add User
    </a>
  </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="icon-wrap bg-indigo-soft"><i class="bi bi-people text-indigo" style="font-size:20px"></i></div>
      <div class="stat-value">{{ number_format($totalUsers) }}</div>
      <div class="stat-label">Total Users</div>
      <div class="stat-change trend-up"><i class="bi bi-arrow-up-short"></i> +{{ $newUsersThisWeek }} this week</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="icon-wrap bg-cyan-soft"><i class="bi bi-collection text-cyan" style="font-size:20px"></i></div>
      <div class="stat-value">{{ $totalCourses }}</div>
      <div class="stat-label">Total Courses</div>
      <div class="stat-change" style="color:var(--edu-amber)">{{ $pendingCourses }} pending approval</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="icon-wrap bg-green-soft"><i class="bi bi-journal-check text-green" style="font-size:20px"></i></div>
      <div class="stat-value">{{ number_format($totalEnrollments) }}</div>
      <div class="stat-label">Total Enrollments</div>
      <div class="stat-change trend-up"><i class="bi bi-arrow-up-short"></i> +{{ $newEnrollmentsThisMonth }} this month</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="icon-wrap bg-amber-soft"><i class="bi bi-person-check text-amber" style="font-size:20px"></i></div>
      <div class="stat-value">{{ $totalInstructors }}</div>
      <div class="stat-label">Instructors</div>
      <div class="stat-change" style="color:var(--edu-muted)">{{ $pendingInstructors }} pending review</div>
    </div>
  </div>
</div>

<div class="row g-2 mb-4">

  {{-- User Management Table --}}
  <div class="col-12 col-md-8 col-sm-12">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="section-title mb-0">User Management</div>
      <a href="{{ route('admin.users.index') }}" style="font-size:13px;color:var(--edu-accent);text-decoration:none">
        View all <i class="bi bi-arrow-right"></i>
      </a>
    </div>
    <div class="data-table">
      <table>
        <thead>
          <tr>
            <th>User</th>
            <th>Role</th>
            <th>Status</th>
            <th>Joined</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recentUsers as $user)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:8px">
                <div class="avatar-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                  {{ Str::upper(Str::substr($user->name, 0, 2)) }}
                </div>
                <div>
                  <div style="font-weight:600;font-size:13px">{{ $user->name }}</div>
                  <div style="font-size:11px;color:var(--edu-muted)">{{ $user->email }}</div>
                </div>
              </div>
            </td>
            <td>
              <span class="badge-pill badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
            </td>
            <td>
              @if($user->is_active)
                <span class="badge-pill" style="background:rgba(16,185,129,.1);color:var(--edu-green)">Active</span>
              @else
                <span class="badge-pill" style="background:rgba(239,68,68,.1);color:var(--edu-red)">Banned</span>
              @endif
            </td>
            <td style="color:var(--edu-muted);font-size:12px">
              {{ $user->created_at->format('M Y') }}
            </td>
            <td>
              <a href="{{ route('admin.users.edit', $user->id) }}"
                 class="btn-outline-edu btn-sm py-1 px-2 me-1">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="{{ route('admin.users.toggle-ban', $user->id) }}" class="d-inline">
                @csrf @method('PATCH')
                @if($user->is_active ?? true)
                  <button type="submit" class="btn-sm py-1 px-2 btn-action-ban" style="font-size:13px"
                    title="Ban user">
                    <i class="bi bi-slash-circle"></i>
                  </button>
                @else
                  <button type="submit" class="btn-sm py-1 px-2 btn-action-approve" style="font-size:13px"
                    title="Unban user">
                    <i class="bi bi-check-circle"></i>
                  </button>
                @endif
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- Pending Courses + Role Distribution --}}
  <div class="col-12 col-md-4 col-sm-12">
    <div class="section-title">Courses Pending Approval</div>
    <div class="card-box mb-3">
      <div style="display:flex;flex-direction:column;gap:12px;">
        @forelse($pendingCoursesList as $course)
        <div style="display:flex;align-items:center;justify-content:space-between;
                    {{ !$loop->last ? 'padding-bottom:12px;border-bottom:1px solid var(--edu-border)' : '' }}">
          <div>
            <div style="font-size:13px;font-weight:600">{{ $course->title }}</div>
            <div style="font-size:11px;color:var(--edu-muted)">
              by {{ $course->instructor->name }} · {{ $course->lessons_count }} lessons
            </div>
          </div>
          <div style="display:flex;gap:4px">
            <form method="POST" action="{{ route('admin.courses.approve', $course->id) }}">
              @csrf @method('PATCH')
              <button type="submit" class="btn-primary-edu py-1 px-2" style="font-size:11px">
                <i class="bi bi-check"></i>
              </button>
            </form>
            <form method="POST" action="{{ route('admin.courses.reject', $course->id) }}">
              @csrf @method('DELETE')
              <button type="submit" class="btn-action-reject py-1 px-2" style="font-size:11px">
                <i class="bi bi-x"></i>
              </button>
            </form>
          </div>
        </div>
        @empty
          <p style="color:var(--edu-muted);font-size:13px;text-align:center; width:100%; margin: 20px 0;">Không có course nào cần duyệt</p>
        @endforelse
      </div>
    </div>

    <div class="section-title">Role Distribution</div>
    <div class="card-box">
      <div style="display:flex;flex-direction:column;gap:12px">
        <div>
          <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px">
            <span>Students</span>
            <span style="font-weight:700">{{ number_format($stats['students']) }}</span>
          </div>
          <div class="progress-bar-custom">
            <div class="progress-fill" style="width:{{ $stats['student_pct'] }}%;background:var(--edu-accent)"></div>
          </div>
        </div>
        <div>
          <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px">
            <span>Instructors</span>
            <span style="font-weight:700">{{ number_format($stats['instructors']) }}</span>
          </div>
          <div class="progress-bar-custom">
            <div class="progress-fill" style="width:{{ $stats['instructor_pct'] }}%;background:var(--edu-accent2)"></div>
          </div>
        </div>
        <div>
          <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px">
            <span>Admins</span>
            <span style="font-weight:700">{{ number_format($stats['admins']) }}</span>
          </div>
          <div class="progress-bar-custom">
            <div class="progress-fill" style="width:{{ $stats['admin_pct'] }}%;background:var(--edu-red)"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection