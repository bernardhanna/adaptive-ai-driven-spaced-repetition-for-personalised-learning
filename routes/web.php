<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\KnownController;
use App\Http\Controllers\StuffIKnowController;
use App\Http\Controllers\UserMetricsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/courses', function () {
    return Inertia::render('Courses');
})->middleware(['auth', 'verified'])->name('courses');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/courses', [CourseController::class, 'index'])->name('courses');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/courses/{course:slug}', [QuizController::class, 'show'])->name('course.quiz');
});

Route::post('/mark-question-known', [KnownController::class, 'markQuestionKnown']);

Route::get('/quiz', [QuizController::class, 'index'])->name('quiz');

Route::get('/known-questions', [KnownController::class, 'getKnownQuestions'])->middleware(['auth', 'verified']);

Route::get('/stuff-i-know', [StuffIKnowController::class, 'index'])->name('stuff-i-know')->middleware(['auth', 'verified']);

Route::delete('/unmark-question-known/{questionId}', [KnownController::class, 'unmarkQuestionKnown'])->name('unmark-question-known')->middleware(['auth', 'verified']);


Route::post('/submit-answer', [QuizController::class, 'submitAnswer'])->middleware(['auth', 'verified'])->name('submit-answer');

Route::get('/user-metrics', [UserMetricsController::class, 'index'])->middleware('auth:api');

Route::post('/quiz/{quiz}/attempt', [QuizController::class, 'saveAttempt'])->name('quiz.attempt.save');

require __DIR__.'/auth.php';
