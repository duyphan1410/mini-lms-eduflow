@extends('layouts.app')
@section('title', 'My Students')
@section('sidebar') @include('partials.sidebar_instructor') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>My Students</h2>
    <p>Tất cả học viên đang học các khóa của bạn.</p>
  </div>
</div>

<div class="data-table">
  <table>
    <thead>
      <tr>
        <th>Student</th>
        <th>Course</th>
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
        <td>
          <a href="{{ route('instructor.courses.show', $enrollment->course) }}"
            style="font-size:13px;color:var(--edu-accent);font-weight:500;text-decoration:none">
            {{ $enrollment->course->title }}
          </a>
        </td>
        <td style="color:var(--edu-muted);font-size:12px">
          {{ $enrollment->created_at->format('d M Y') }}
        </td>
        <td style="min-width:160px">
          @php
            $total     = $enrollment->course->lessons->count();
            $completed = $enrollment->user->lessonProgress()
                ->whereIn('lesson_id', $enrollment->course->lessons->pluck('id'))
                ->where('completed', true)->count();
            $pct = $total > 0 ? round($completed / $total * 100) : 0;
          @endphp
          <div class="progress-bar-custom">
            <div class="progress-fill"
              style="width:{{ $pct }}%;background:{{ $pct === 100 ? 'var(--edu-green)' : 'var(--edu-accent)' }}">
            </div>
          </div>
          <div style="font-size:11px;color:var(--edu-muted);margin-top:3px">
            {{ $completed }}/{{ $total }} lessons · {{ $pct }}%
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="4" style="text-align:center;color:var(--edu-muted);padding:32px">
          Chưa có học viên nào.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-3">{{ $enrollments->links() }}</div>
@endsection