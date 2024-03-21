<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizAttempt; // Make sure to import your model at the top

class UserMetricsController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->userId;

        // Fetch quiz attempts for the user
        $attempts = QuizAttempt::where('user_id', $userId)->get();

        // Calculate metrics
        $totalQuizzesTaken = $attempts->count();
        $averageScore = $attempts->average('score');
        $completionRate = $attempts->where('completed', true)->count() / max($totalQuizzesTaken, 1) * 100;
        $averageTimePerQuiz = $attempts->average('time_taken');

        // Format metrics to ensure proper types are returned
        $metrics = [
            'totalQuizzesTaken' => (int) $totalQuizzesTaken,
            'averageScore' => (float) $averageScore,
            'completionRate' => (float) $completionRate,
            'averageTimePerQuiz' => (float) $averageTimePerQuiz,
        ];

        return response()->json($metrics);
    }
}
