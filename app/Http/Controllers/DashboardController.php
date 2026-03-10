<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        if (!view()->exists("{$role}.dashboard")) {
            return redirect('/');
        }

        $data = $this->getDashboardData($user, $role);

        return view("{$role}.dashboard", $data);
    }

    private function getDashboardData($user, $role): array
    {
        return match($role) {
            'student'    => $this->studentData($user),
            'instructor' => $this->instructorData($user),
            'admin'      => $this->adminData(),
            default      => [],
        };
    }

    private function studentData($user): array
    {
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');
        return [
            'enrolledCourses' => $user->enrollments()
            ->with('course.instructor', 'course.lessons')
            ->latest()->take(3)->get()
            ->each(function ($enrollment) use ($user) {
                $total = $enrollment->course->lessons->count();
                if ($total === 0) {
                    $enrollment->progress_percent = 0;
                    return;
                }
                $completed = $user->lessonProgress()
                    ->whereIn('lesson_id', $enrollment->course->lessons->pluck('id'))
                    ->where('completed', true)
                    ->count();
                $enrollment->progress_percent = round($completed / $total * 100);
            }),
            'availableCourses' => Course::where('status', 'published')
                                    ->whereNotIn('id', $enrolledCourseIds)
                                    ->take(3)->get(),
            'enrolledCount'    => $enrolledCourseIds->count(),
            'completedLessons' => $user->lessonProgress()->count(),
            'avgQuizScore'     => round($user->quizAttempts()->avg('score') ?? 0),
            'lastLesson'       => null, // TODO sau khi có lesson_progress
            'lastCourseProgress' => 0,
            'pendingLessons'   => 0,
            'studyHours'       => 0,
        ];
    }

    private function instructorData($user): array
    {
        $courseIds = $user->courses()->pluck('id');
        return [
            'courses'           => $user->courses()->withCount(['enrollments', 'lessons'])->get(),
            'totalCourses'      => $courseIds->count(),
            'publishedCourses'  => $user->courses()->where('status', 'published')->count(),
            'draftCourses'      => $user->courses()->where('status', 'draft')->count(),
            'totalStudents'     => Enrollment::whereIn('course_id', $courseIds)->count(),
            'newStudentsThisWeek' => 0,
            'avgRating'         => 0, // TODO sau khi có ratings
            'completionRate'    => 0,
            'recentEnrollments' => Enrollment::with(['user', 'course.lessons'])
            ->whereIn('course_id', $courseIds)
            ->latest()->take(5)->get()
            ->each(function ($enrollment) {
                $total = $enrollment->course->lessons->count();
                if ($total === 0) { $enrollment->progress_percent = 0; return; }
                $completed = $enrollment->user->lessonProgress()
                    ->whereIn('lesson_id', $enrollment->course->lessons->pluck('id'))
                    ->where('completed', true)->count();
                $enrollment->progress_percent = round($completed / $total * 100);
            }),
        ];
    }

    private function adminData(): array
    {
        $totalUsers      = User::count();
        $studentCount    = User::where('role', 'student')->count();
        $instructorCount = User::where('role', 'instructor')->count();
        $adminCount      = User::where('role', 'admin')->count();
        return [
            'totalUsers'       => $totalUsers,
            'totalCourses'     => Course::count(),
            'totalEnrollments' => Enrollment::count(),
            'totalInstructors' => $instructorCount,
            'pendingCourses'   => Course::where('status', 'pending')->count(),
            'newUsersThisWeek' => User::where('created_at', '>=', now()->subWeek())->count(),
            'newEnrollmentsThisMonth' => Enrollment::where('created_at', '>=', now()->subMonth())->count(),
            'pendingInstructors' => 0,
            'recentUsers'      => User::latest()->take(5)->get(),
            'pendingCoursesList' => Course::where('status', 'pending')->with(['instructor'])->withCount('lessons')->take(3)->get(),
            'stats'            => [
                'students'       => $studentCount,
                'instructors'    => $instructorCount,
                'admins'         => $adminCount,
                'student_pct'    => $totalUsers > 0 ? round($studentCount / $totalUsers * 100) : 0,
                'instructor_pct' => $totalUsers > 0 ? round($instructorCount / $totalUsers * 100) : 0,
                'admin_pct'      => $totalUsers > 0 ? round($adminCount / $totalUsers * 100) : 0,
            ],
        ];
    }
}
