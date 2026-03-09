<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Course;

class Category extends Model
{
    protected $fillable = ['name','slug'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
