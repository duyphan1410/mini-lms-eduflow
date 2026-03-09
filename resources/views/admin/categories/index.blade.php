@extends('layouts.app')
@section('title', 'Categories')
@section('sidebar') @include('partials.sidebar_admin') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Categories</h2>
    <p>Manage course categories.</p>
  </div>
  <a href="{{ route('admin.categories.create') }}" class="btn-primary-edu">
    <i class="bi bi-plus-lg"></i> New Category
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif
@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="data-table">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Slug</th>
        <th>Courses</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $category)
      <tr>
        <td style="color:var(--edu-muted);font-size:12px">{{ $category->id }}</td>
        <td style="font-weight:600">{{ $category->name }}</td>
        <td><code style="font-size:11px;color:var(--edu-muted)">{{ $category->slug }}</code></td>
        <td>
          <span class="badge-pill" style="background:rgba(99,102,241,.1);color:var(--edu-accent)">
            {{ $category->courses_count }} courses
          </span>
        </td>
        <td style="color:var(--edu-muted);font-size:12px">{{ $category->created_at->format('d M Y') }}</td>
        <td>
          <a href="{{ route('admin.categories.edit', $category) }}"
            class="btn-outline-edu btn-sm py-1 px-2 me-1">
            <i class="bi bi-pencil"></i>
          </a>
          <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline"
            onsubmit="return confirm('Delete this category?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-sm py-1 px-2"
              style="border:1.5px solid var(--edu-red);border-radius:6px;background:transparent;color:var(--edu-red);cursor:pointer">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" style="text-align:center;color:var(--edu-muted);padding:32px">
          No categories yet. <a href="{{ route('admin.categories.create') }}">Create one!</a>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-3">{{ $categories->links() }}</div>
@endsection     