<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Question;
use App\Models\KnownQuestion;
use App\Models\UserQuestion;
use App\Services\SpacedRepetitionService;
use App\Models\QuizAttempt;
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

        $latestAttempt = QuizAttempt::where('user_id', $userId)
            ->where('quiz_id', $course->id)
            ->latest()
            ->first();

        $questions = Question::where('course_id', $course->id)
            ->whereNotIn('id', $knownQuestionIds)
            ->get()
            ->map(function ($question) use ($userId) {
                $spacedRepetitionData = UserQuestion::where('question_id', $question->id)
                    ->where('user_id', $userId)
            ->first(['next_review_date', 'interval', 'easiness_factor', 'repetitions', 'confidence_score', 'time_taken']);
                $question->spacedRepetitionData = $spacedRepetitionData;
                return $question;
            });

        return Inertia::render('Quiz', [
            'course' => $course,
            'questions' => $questions,
            'user' => auth()->user(),
            'latestAttempt' => $latestAttempt,
        ]);
    }

    public function submitAnswer(Request $request)
    {

        Log::info($request->all());

        $validated = $request->validate([
            'userId' => 'required|integer',
            'questionId' => 'required|integer',
            'courseId' => 'required|integer',
            'isCorrect' => 'required|boolean',
            'confidenceScore' => 'required|numeric',
            'timeTaken' => 'required|integer',
        ]);

        $userQuestion = UserQuestion::firstOrNew([
            'user_id' => $validated['userId'],
            'question_id' => $validated['questionId'],
            'course_id' => $validated['courseId'],
        ]);

        // Now explicitly set additional attributes
        $userQuestion->confidence_score = $validated['confidenceScore'];
        $userQuestion->time_taken = $validated['timeTaken'];


        $metrics = $this->spacedRepetitionService->calculateNextReview(
            $validated['isCorrect'],
            $userQuestion->interval ?? 0,
            $userQuestion->repetitions ?? 0,
            $userQuestion->easiness_factor ?? 2.5,
            $userQuestion->confidence_score,
            $userQuestion->time_taken
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
    public function saveAttempt(Request $request)
    {
        // Ensure you have validation for all incoming request data for security
        $validated = $request->validate([
            'quiz_id' => 'required|integer',
            'question_id' => 'required|integer',
            'confidence_score' => 'required|numeric',
            'showed_answer' => 'required|boolean',
            'skipped' => 'required|boolean',
            'time_taken' => 'required|integer',
            'completed' => 'required|boolean',
            'toughness' => 'required|string',
            'speed' => 'required|integer',
            'facial_confidence' => 'nullable|float',
            'voice_confidence' => 'nullable|float',
        ]);

        $attempt = QuizAttempt::create([
            'user_id' => $request->user()->id,
            'quiz_id' => $validated['quiz_id'],
            'question_id' => $validated['question_id'],
            'confidence_score' => $validated['confidence_score'],
            'showed_answer' => $validated['showed_answer'],
            'skipped' => $validated['skipped'],
            'time_taken' => $validated['time_taken'],
            'toughness' => $validated['toughness'],
            'speed' => $validated['speed'],
            'facial_confidence' => $validated['facial_confidence'],
            'voice_confidence' => $validated['voice_confidence'],
            'completed' => $validated['completed'],

        ]);

        return response()->json($attempt);
    }
}
