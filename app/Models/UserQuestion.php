<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'question_id', 'course_id', 'interval', 'repetitions', 'easiness_factor', 'next_review_date', 'confidence_score', 'time_taken'];
}
