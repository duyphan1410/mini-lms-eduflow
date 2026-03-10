@extends('layouts.app')
@section('title', 'Course Management')
@section('sidebar') @include('partials.sidebar_admin') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Course Management</h2>
    <p>Review and manage all courses.</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

{{-- Filter --}}
<form method="GET" class="d-flex gap-2 mb-4">
  <input type="text" name="search" value="{{ request('search') }}"
    class="field-input" style="max-width:260px;padding-left:14px;width:auto"
    placeholder="Search course title...">
  <select name="status" class="field-input" style="max-width:160px;padding-left:14px;width:auto" onchange="this.form.submit()">
    <option value="">All status</option>
    <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
    <option value="pending"   {{ request('status') === 'pending'     ? 'selected' : '' }}>Pending</option>
    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
  </select>
  <button type="submit" class="btn-primary-edu"><i class="bi bi-search"></i></button>
  <a href="{{ route('admin.courses.index') }}" class="btn-outline-edu px-3">
    <i class="bi bi-x-lg"></i>
  </a>
</form>

<div class="data-table">
  <table>
    <thead>
      <tr>
        <th>Course</th>
        <th>Instructor</th>
        <th>Category</th>
        <th>Students</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($courses as $course)
      <tr>
        <td>
          <div style="font-weight:600;font-size:13px">{{ $course->title }}</div>
          <div style="font-size:11px;color:var(--edu-muted)">{{ $course->created_at->format('d M Y') }}</div>
        </td>
        <td style="font-size:13px">{{ $course->instructor->name }}</td>
        <td>
          <span class="badge-pill" style="background:rgba(99,102,241,.1);color:var(--edu-accent)">
            {{ $course->category->name }}
          </span>
        </td>
        <td style="font-weight:600">{{ $course->enrollments_count }}</td>
        <td>
          @if($course->status === 'published')
            <span class="badge-pill" style="background:rgba(16,185,129,.1);color:var(--edu-green)">Published</span>
          @elseif($course->status === 'pending')
            <span class="badge-pill" style="background:rgba(255,193,7,.1);color:#d97706">Pending</span>
          @else
            <span class="badge-pill" style="background:rgba(245,158,11,.1);color:var(--edu-amber)">Draft</span>
          @endif
        </td>
        <td>
          <a href="{{ route('admin.courses.edit', $course) }}"
            class="btn-outline-edu btn-sm py-1 px-2 me-1">
            <i class="bi bi-pencil"></i>
          </a>
          @if($course->status === 'pending')
          <form method="POST" action="{{ route('admin.courses.approve', $course) }}" class="d-inline">
            @csrf @method('PATCH')
            <button type="submit" class="btn-sm py-1 px-2 me-1 btn-action-approve">
              <i class="bi bi-check-lg"></i> Approve
            </button>
          </form>
          <form method="POST" action="{{ route('admin.courses.reject', $course) }}" class="d-inline"
            onsubmit="return confirm('Reject this course?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-sm py-1 px-2 me-1 btn-action-reject">
              <i class="bi bi-x-lg"></i> Reject
            </button>
          </form>
          @endif
          <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="d-inline"
            onsubmit="return confirm('Delete this course?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-sm py-1 px-2  btn-action-delete">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" style="text-align:center;color:var(--edu-muted);padding:32px">No courses found.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-3">{{ $courses->links() }}</div>

@endsection