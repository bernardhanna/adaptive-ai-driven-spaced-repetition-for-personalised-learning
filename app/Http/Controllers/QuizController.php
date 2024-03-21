<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Question;
use App\Models\KnownQuestion;
use App\Models\UserQuestion;
use App\Services\SpacedRepetitionService;
use Carbon\Carbon;

class QuizController extends Controller
{
    protected $spacedRepetitionService;

    public function __construct(SpacedRepetitionService $spacedRepetitionService)
    {
        $this->spacedRepetitionService = $spacedRepetitionService;
    }

    public function show(Course $course)
    {
        $userId = auth()->id();
        $knownQuestionIds = KnownQuestion::where('user_id', $userId)->pluck('question_id');

        $questions = Question::where('course_id', $course->id)
            ->whereNotIn('id', $knownQuestionIds)
            ->get()
            ->map(function ($question) use ($userId) {
                $spacedRepetitionData = UserQuestion::where('question_id', $question->id)
                    ->where('user_id', $userId)
                    ->first(['next_review_date', 'interval', 'easiness_factor', 'repetitions']);
                $question->spacedRepetitionData = $spacedRepetitionData;
                return $question;
            });

        return Inertia::render('Quiz', [
            'course' => $course,
            'questions' => $questions,
            'user' => auth()->user(),
        ]);
    }

    public function submitAnswer(Request $request)
    {
        $validated = $request->validate([
            'userId' => 'required|integer',
            'questionId' => 'required|integer',
            'courseId' => 'required|integer',
            'isCorrect' => 'required|boolean',
        ]);

        $userQuestion = UserQuestion::firstOrNew([
            'user_id' => $validated['userId'],
            'question_id' => $validated['questionId'],
            'course_id' => $validated['courseId'],
        ]);

        $metrics = $this->spacedRepetitionService->calculateNextReview(
            $validated['isCorrect'],
            $userQuestion->interval ?? 0,
            $userQuestion->repetitions ?? 0,
            $userQuestion->easiness_factor ?? 2.5
        );

        // Update or fill the user question attributes based on the metrics calculated
        $userQuestion->interval = $metrics['interval'];
        $userQuestion->repetitions = $metrics['repetitions'];
        $userQuestion->easiness_factor = $metrics['easinessFactor'];
        $userQuestion->next_review_date = $metrics['nextReviewDate'];
        $userQuestion->fill($metrics);
        $userQuestion->save();

        return response()->json([
            'message' => 'Answer submitted successfully',
            'data' => $metrics,
        ]);
    }
}
