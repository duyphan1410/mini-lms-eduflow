@extends('layouts.app')
@section('title', 'User Management')
@section('sidebar') @include('partials.sidebar_admin') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>User Management</h2>
    <p>Manage all users in the system.</p>
  </div>
  <a href="{{ route('admin.users.create') }}" class="btn-primary-edu">
    <i class="bi bi-person-plus"></i> Add User
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif
@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

{{-- Filter / Search --}}
<form method="GET" class="d-flex gap-2 mb-4">
  <input type="text" name="search" value="{{ request('search') }}"
    class="field-input" style="max-width:260px;padding-left:14px"
    placeholder="Search name or email...">
  <select name="role" class="field-input" style="max-width:160px;padding-left:14px" onchange="this.form.submit()">
    <option value="">All roles</option>
    <option value="student"    {{ request('role') === 'student'    ? 'selected' : '' }}>Student</option>
    <option value="instructor" {{ request('role') === 'instructor' ? 'selected' : '' }}>Instructor</option>
    <option value="admin"      {{ request('role') === 'admin'      ? 'selected' : '' }}>Admin</option>
  </select>
  <button type="submit" class="btn-primary-edu px-3">
    <i class="bi bi-search"></i>
  </button>
  <a href="{{ route('admin.users.index') }}" class="btn-outline-edu px-3">
    <i class="bi bi-x-lg"></i>
  </a>
</form>

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
      @forelse($users as $user)
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
          @if($user->is_active ?? true)
            <span class="badge-pill" style="background:rgba(16,185,129,.1);color:var(--edu-green)">Active</span>
          @else
            <span class="badge-pill" style="background:rgba(239,68,68,.1);color:var(--edu-red)">Banned</span>
          @endif
        </td>
        <td style="color:var(--edu-muted);font-size:12px">{{ $user->created_at->format('d M Y') }}</td>
        <td>
          <a href="{{ route('admin.users.edit', $user) }}" class="btn-outline-edu btn-sm py-1 px-2 me-1">
            <i class="bi bi-pencil"></i>
          </a>
          @if($user->id !== auth()->id())
          <form method="POST" action="{{ route('admin.users.toggle-ban', $user) }}" class="d-inline">
            @csrf @method('PATCH')
            <button type="submit" class="btn-sm py-1 px-2"
              style="border:1.5px solid var(--edu-amber);border-radius:6px;background:transparent;color:var(--edu-amber);cursor:pointer"
              title="{{ ($user->is_active ?? true) ? 'Ban' : 'Unban' }}">
              <i class="bi bi-slash-circle"></i>
            </button>
          </form>
          <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline"
            onsubmit="return confirm('Delete this user?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-sm py-1 px-2"
              style="border:1.5px solid var(--edu-red);border-radius:6px;background:transparent;color:var(--edu-red);cursor:pointer">
              <i class="bi bi-trash"></i>
            </button>
          </form>
          @endif
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5" style="text-align:center;color:var(--edu-muted);padding:32px">
          No users found.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection