<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'quiz_id', 'question_id', 'confidence_score', 'showed_answer', 'skipped', 'time_taken', 'completed',
    ];
}
