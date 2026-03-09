<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Question;

class Option extends Model
{
    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
