@extends('layouts.app')
@section('title', 'Enrolled Students')
@section('sidebar') @include('partials.sidebar_instructor') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Enrolled Students</h2>
    <p>{{ $course->title }}</p>
  </div>
  <a href="{{ route('instructor.courses.show', $course) }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back to Course
  </a>
</div>

<div class="data-table">
  <table>
    <thead>
      <tr>
        <th>Student</th>
        <th>Enrolled</th>
        <th>Progress</th>
      </tr>
    </thead>
    <tbody>
      @forelse($enrollments as $enrollment)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:8px">
            <div class="avatar-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
              {{ strtoupper(substr($enrollment->user->name, 0, 2)) }}
            </div>
            <div>
              <div style="font-weight:600;font-size:13px">{{ $enrollment->user->name }}</div>
              <div style="font-size:11px;color:var(--edu-muted)">{{ $enrollment->user->email }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--edu-muted);font-size:12px">
          {{ $enrollment->created_at->format('d M Y') }}
        </td>
        <td style="min-width:160px">
          @php
            $total     = $course->lessons->count();
            $completed = $enrollment->user->lessonProgress()
                ->whereIn('lesson_id', $course->lessons->pluck('id'))
                ->where('completed', true)->count();
            $pct = $total > 0 ? round($completed / $total * 100) : 0;
          @endphp
          <div class="progress-bar-custom">
            <div class="progress-fill" style="width:{{ $pct }}%;background:var(--edu-accent)"></div>
          </div>
          <div style="font-size:11px;color:var(--edu-muted);margin-top:3px">
            {{ $completed }}/{{ $total }} lessons · {{ $pct }}%
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="3" style="text-align:center;color:var(--edu-muted);padding:32px">
          No students enrolled yet.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-3">{{ $enrollments->links() }}</div>
@endsection