<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\LessonProgress;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'content',
        'order'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
