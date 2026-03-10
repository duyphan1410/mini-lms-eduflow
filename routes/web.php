<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\LessonController;
use App\Http\Controllers\Instructor\QuizController;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Student\ProgressController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login',[AuthController::class,'showLogin'])->name('login');
Route::post('/login',[AuthController::class,'login']);

Route::get('/register',[AuthController::class,'showRegister'])->name('register');
Route::post('/register',[AuthController::class,'register']);

Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');;

Route::get('/', function () {
    return view('auth.login');
});


Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Admin only ──
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);       
        Route::patch('users/{user}/toggle-ban', [UserController::class, 'toggleBan'])->name('users.toggle-ban');

        Route::resource('courses', AdminCourseController::class)->except(['create', 'store', 'show']);
        Route::patch('courses/{course}/approve', [AdminCourseController::class, 'approve'])->name('courses.approve');
        Route::delete('courses/{course}/reject', [AdminCourseController::class, 'reject'])->name('courses.reject');

        Route::resource('categories', CategoryController::class)->except(['show']); 
    });

    // ── Instructor only ──
    Route::middleware('role:instructor')->prefix('instructor')->name('instructor.')->group(function () {
        Route::resource('courses', InstructorCourseController::class);
        Route::patch('courses/{course}/submit', [InstructorCourseController::class, 'submit'])->name('courses.submit');
        Route::get('courses/{course}/students', [InstructorCourseController::class, 'students'])->name('courses.students');
        Route::resource('courses.lessons', LessonController::class)->except(['show', 'index']);  
        Route::resource('courses.lessons.quizzes', QuizController::class)->except(['show']); // quiz → tạo/sửa/xóa quiz + questions + options

        Route::get('students', [InstructorCourseController::class, 'allStudents'])->name('students');
        Route::get('analytics', fn() => view('instructor.analytics'))->name('analytics');
        Route::get('profile', fn() => view('instructor.profile'))->name('profile');
    });

    // ── Student only ──
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('my-courses', [EnrollmentController::class, 'index'])->name('my-courses');
        Route::post('enroll/{course}', [EnrollmentController::class, 'store'])->name('enroll');
        Route::get('progress', [ProgressController::class, 'index'])->name('progress');
        Route::get('profile', fn() => view('student.profile'))->name('profile');

        Route::get('courses', [EnrollmentController::class, 'browse'])->name('courses.index');
        Route::get('courses/{course}', [EnrollmentController::class, 'show'])->name('courses.show');
        Route::get('lessons/{lesson}', [ProgressController::class, 'show'])->name('lessons.show');
        Route::post('lessons/{lesson}/complete', [ProgressController::class, 'markComplete'])->name('lessons.complete');
        Route::post('lessons/{lesson}/quiz', [ProgressController::class, 'submitQuiz'])->name('lessons.quiz.submit'); 
    });
});
