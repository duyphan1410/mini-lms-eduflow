# EduFlow — Mini LMS

A lightweight Learning Management System built with Laravel 10, featuring three roles: Admin, Instructor, and Student.

## Tech Stack

- **Backend:** Laravel 10, PHP 8.1+
- **Database:** MySQL
- **Frontend:** Blade, Bootstrap 5.3, Bootstrap Icons
- **Auth:** Laravel Session Auth (custom)

## Features

### Admin
- Manage users (create, edit, ban/unban)
- Manage categories
- Approve / reject courses submitted by instructors
- Dashboard with stats: users, courses, enrollments

### Instructor
- Create and manage courses & lessons
- Build quizzes with multiple-choice questions
- Submit courses for admin approval
- View enrolled students

### Student
- Browse and enroll in published courses
- Track lesson progress
- Take quizzes and see scores

## Installation

```bash
# 1. Clone & install dependencies
git clone https://github.com/your-username/eduflow.git
cd eduflow
composer install
npm install

# 2. Environment setup
cp .env.example .env
php artisan key:generate

# 3. Configure database in .env
DB_DATABASE=eduflow
DB_USERNAME=root
DB_PASSWORD=

# 4. Run migrations & seed
php artisan migrate:fresh --seed

# 5. Build assets & serve
npm run dev
php artisan serve
```

## Demo Accounts

| Role       | Email                      | Password |
|------------|----------------------------|----------|
| Admin      | admin@eduflow.com          | password |
| Instructor | instructor@eduflow.com     | password |
| Student    | student@eduflow.com        | password |

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Admin/          # UserController, CourseController, CategoryController
│   ├── Instructor/     # CourseController, LessonController, QuizController
│   └── Student/        # EnrollmentController, ProgressController
├── Models/             # User, Course, Lesson, Quiz, Question, Option, Enrollment, ...
resources/views/
├── admin/
├── instructor/
├── student/
├── partials/           # Sidebars
└── layouts/            # app.blade.php, auth.blade.php
database/
├── migrations/
└── seeders/
```

## Course Status Flow

```
Instructor creates → draft
Instructor submits → pending
Admin approves     → published
Admin rejects      → draft (back to editing)
```

## Requirements

- PHP >= 8.1
- Composer
- Node.js >= 16
- MySQL >= 8.0