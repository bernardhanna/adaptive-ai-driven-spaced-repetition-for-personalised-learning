import React, { useState, useEffect, useRef, useCallback } from 'react';
import { Link } from '@inertiajs/inertia-react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import axios from 'axios';
import Fuse from 'fuse.js';

const Quiz = ({ questions: initialQuestions, user, course }) => {
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [questions, setQuestions] = useState(initialQuestions);
    const [userAnswer, setUserAnswer] = useState('');
    const [error, setError] = useState('');
    const [quizCompleted, setQuizCompleted] = useState(initialQuestions.length === 0);
    const [showAnswer, setShowAnswer] = useState(false);
    const [isListening, setIsListening] = useState(false);
    const [speechAnswer, setSpeechAnswer] = useState('');
    const [correctSpeechCount, setCorrectSpeechCount] = useState(0);
    const [correctAnswersCount, setCorrectAnswersCount] = useState(0);
    const recognitionRef = useRef(null);
    const currentQuestion = questions[currentQuestionIndex];
    const [spacedRepetitionMetrics, setSpacedRepetitionMetrics] = useState({});
    const [seconds, setSeconds] = useState(0);
    const [isActive, setIsActive] = useState(true);
    const [milliseconds, setMilliseconds] = useState(0);

    useEffect(() => {
        let interval = null;
        if (isActive) {
            interval = setInterval(() => {
                setMilliseconds(prevMilliseconds => prevMilliseconds + 10);
            }, 10);
        } else {
            clearInterval(interval);
        }
        return () => clearInterval(interval);
    }, [isActive]);

    const formatTime = (milliseconds) => {
        let remainingMilliseconds = milliseconds;
        const hours = Math.floor(remainingMilliseconds / 3600000);
        remainingMilliseconds = remainingMilliseconds % 3600000;
        const minutes = Math.floor(remainingMilliseconds / 60000);
        remainingMilliseconds = remainingMilliseconds % 60000;
        const seconds = Math.floor(remainingMilliseconds / 1000);
        // const ms = remainingMilliseconds % 1000; // Calculation remains but not used in return

           return `${String(hours).padStart(2, '0')}hr:${String(minutes).padStart(2, '0')}min:${String(seconds).padStart(2, '0')}sec`;

    };



    const moveToNextQuestion = useCallback(() => {
        setSpeechAnswer('');
        setShowAnswer(false);
        setError('');
        setUserAnswer('');
        setMilliseconds(0); // Reset the timer to 0 milliseconds
        setIsActive(true); // Reactivate the timer for the new question
        if (currentQuestionIndex + 1 < questions.length) {
            setCurrentQuestionIndex(currentQuestionIndex + 1);
        } else {
            setQuizCompleted(true);
            setIsActive(false); // Stop the timer when the quiz is completed
        }
    }, [currentQuestionIndex, questions.length]);

    const handleIKnowThis = useCallback((questionId) => {
        axios.post('/mark-question-known', {
            userId: user.id,
            questionId,
            courseId: course.id,
        }).then(() => {
            const updatedQuestions = questions.filter(q => q.id !== questionId);
            setQuestions(updatedQuestions);
            if (currentQuestionIndex >= updatedQuestions.length - 1) {
                setQuizCompleted(true);
            }
        }).catch(error => console.error('Error marking question as known:', error));
    }, [currentQuestionIndex, questions, user.id, course.id]);

    const handleAnswerSubmit = useCallback((e) => {
        e.preventDefault();
        const currentQuestion = questions[currentQuestionIndex];
        let isCorrect = false;

        try {
            const correctAnswers = JSON.parse(currentQuestion.answer);
            isCorrect = Array.isArray(correctAnswers)
                ? correctAnswers.some(answer => answer.toLowerCase() === userAnswer.toLowerCase())
                : currentQuestion.answer.toLowerCase() === userAnswer.toLowerCase();
        } catch (error) {
            isCorrect = currentQuestion.answer.toLowerCase() === userAnswer.toLowerCase();
        }

        if (isCorrect) {
            setError(''); // Clear the error message immediately upon correct answer
            axios.post('/submit-answer', {
                userId: user.id,
                questionId: currentQuestion.id,
                courseId: course.id,
                isCorrect: isCorrect,
                timeTaken: milliseconds,
            })
            .then(response => {
                setCorrectAnswersCount(prev => prev + 1);
                setSpacedRepetitionMetrics(response.data.data);
                moveToNextQuestion(); // Moved inside then() to ensure order of operations
            })
            .catch(error => {
                console.error('Error submitting answer:', error);
                setError("Error submitting answer.");
            });
        } else {
            setError("Incorrect answer. Try again.");
        }
    }, [currentQuestionIndex, questions, userAnswer, user.id, course.id, moveToNextQuestion]);


    useEffect(() => {
        if ("SpeechRecognition" in window || "webkitSpeechRecognition" in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognitionRef.current = new SpeechRecognition();
            recognitionRef.current.continuous = true;
            recognitionRef.current.interimResults = true;
            recognitionRef.current.onresult = (event) => {
                const lastResult = event.results[event.results.length - 1];
                 console.log(lastResult); // Debugging line to see the result object
                if (lastResult.isFinal) {
                    const transcript = lastResult[0].transcript.trim().toLowerCase();
                    console.log(transcript);
                    setSpeechAnswer(transcript);
                    const currentQuestion = questions[currentQuestionIndex];
                    if (transcript === currentQuestion.answer.toLowerCase()) {
                        moveToNextQuestion();
                    }
                }
            };

            if (isListening) {
                recognitionRef.current.start();
            } else {
                recognitionRef.current.stop();
            }
        }

        return () => {
            if (recognitionRef.current) {
                recognitionRef.current.stop();
            }
        };
    }, [isListening, questions, currentQuestionIndex, moveToNextQuestion]);

    const resetQuiz = () => {
        setCurrentQuestionIndex(0);
        setQuizCompleted(false);
        setCorrectAnswersCount(0);
    };

    const renderQuestionInput = (questionType, questionData) => {
        switch (questionType) {
            case 'text':
            case 'fill-in-the-blank':
                return <input type="text" value={userAnswer} onChange={(e) => setUserAnswer(e.target.value)} className="w-full input-class" />;
            case 'textarea':
                return <textarea value={userAnswer} onChange={(e) => setUserAnswer(e.target.value)} className="w-full h-[500px] textarea-class"></textarea>;
            case 'true-false':
                return (
                    <div>
                        <label>
                            <input type="radio" name="true-false" value="true" checked={userAnswer === "true"} onChange={(e) => setUserAnswer(e.target.value)} className="m-2" />
                            True
                        </label>
                        <label>
                            <input type="radio" name="true-false" value="false" checked={userAnswer === "false"} onChange={(e) => setUserAnswer(e.target.value)} className="m-2" />
                            False
                        </label>
                    </div>
                );
            case 'speech':
                return (
                    <button type="button" onClick={toggleListening} className={`px-4 py-2 ml-4 font-bold text-white rounded focus:outline-none focus:shadow-outline ${isListening ? 'bg-red-500 hover:bg-red-700' : 'bg-purple-500 hover:bg-purple-700'}`}>
                        {isListening ? 'Listening... (Click to Stop)' : 'Start Listening'}
                    </button>
                );
            default:
                return <input type="text" />;
        }
    };
    const fuzzyMatch = useCallback((input, answers) => {
        const fuse = new Fuse(answers, { includeScore: true, threshold: 0.4 }); // Adjust threshold as needed
        const result = fuse.search(input);
        return result.length > 0; // True if there's at least one match
    }, []);


    const handleSpeechResult = useCallback((event) => {
        const lastResultIndex = event.results.length - 1;
        const transcript = event.results[lastResultIndex][0].transcript.trim().toLowerCase();
        setSpeechAnswer(transcript);

        // Assuming 'currentQuestion.answer' holds the correct answer(s) in a manner that can be directly compared.
        const answersToCheck = Array.isArray(currentQuestion.answer) ? currentQuestion.answer : [currentQuestion.answer];
        // Use fuzzyMatch to allow for some flexibility in matching the spoken answer.
        const isCorrectAnswer = fuzzyMatch(transcript, answersToCheck);

        if (isCorrectAnswer) {
            setCorrectAnswersCount(prevCount => prevCount + 1); // Correctly update the count.
            moveToNextQuestion(); // Move to the next question, which also resets the speech answer.
        }
     }, [currentQuestion, moveToNextQuestion, fuzzyMatch]);

    const toggleListening = () => {
        if (!isListening) {
            recognitionRef.current?.start();
        } else {
            recognitionRef.current?.stop();
        }
        setIsListening(!isListening);
    };

    useEffect(() => {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SpeechRecognition) {
            console.error('Speech recognition not supported in this browser.');
            return;
        }

        recognitionRef.current = new SpeechRecognition();
        recognitionRef.current.continuous = true;
        recognitionRef.current.interimResults = true;
        recognitionRef.current.onresult = handleSpeechResult;

        // Moved the start and stop logic to the toggleListening function

        return () => {
            recognitionRef.current?.stop();
        };
    }, [handleSpeechResult]);


    const handleSkipQuestion = () => {
        setShowAnswer(false);
        moveToNextQuestion();
    };

    const handleShowAnswer = () => {
        setShowAnswer(!showAnswer);
    };

    const handlePreviousQuestion = () => {
        setError('');
        setUserAnswer('');
        setShowAnswer(false); // Reset showAnswer state
        if (currentQuestionIndex > 0) {
            setCurrentQuestionIndex(currentQuestionIndex - 1);
        }
    };

    useEffect(() => {
        if (!questions.length) {
            setQuizCompleted(true);
        }
    }, [questions]);

    if (!questions.length) {
        return (
            <AuthenticatedLayout user={user}>
                <div className="container mx-auto">
                    <h1 className="text-xl font-bold">You know Everything - Course Complete!</h1>
                    <div className="mt-4">
                        <Link href="/courses" className="px-4 py-2 mr-4 font-bold text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">
                            Back to Courses
                        </Link>
                    </div>
                </div>
            </AuthenticatedLayout>
        );
    }

    if (quizCompleted) {
        return (
            <AuthenticatedLayout user={user}>
                <div className="container mx-auto">
                    <h1 className="text-xl font-bold">Quiz Completed!</h1>
                    <p className="mt-2 text-lg">Total Correct Answers: {correctAnswersCount}</p>
                    <div className="mt-4">
                        <Link href="/courses" className="px-4 py-2 mr-4 font-bold text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">
                            Back to Courses
                        </Link>
                        <button onClick={resetQuiz} className="px-4 py-2 font-bold text-white bg-green-500 rounded hover:bg-green-700 focus:outline-none focus:shadow-outline">
                            Try Again
                        </button>
                    </div>
                </div>
            </AuthenticatedLayout>
        );
    }

    return (
        <AuthenticatedLayout user={user}>
            <div className="container mx-auto">
                <h1 className="mb-4 text-xl font-bold">Quiz</h1>
                <form onSubmit={handleAnswerSubmit}>
                    <div className="mb-4">
                        <label className="block mb-2 text-2xl font-bold text-gray-700">
                            {currentQuestionIndex + 1}. {currentQuestion.question}
                            {currentQuestion.image_url && (
                                <img src={currentQuestion.image_url} alt="Question" style={{ maxWidth: '100%', marginTop: '10px' }} />
                            )}
                            {currentQuestion.video_url && (
                                <video width="320" height="240" controls style={{ display: 'block', marginTop: '10px' }}>
                                    <source src={currentQuestion.video_url} type="video/mp4" />
                                    Your browser does not support the video tag.
                                </video>
                            )}
                            {currentQuestion.video_id && (
                                <iframe
                                    width="560"
                                    height="315"
                                    src={`https://www.youtube.com/embed/${currentQuestion.video_id}`}
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    style={{ display: 'block', marginTop: '10px' }}
                                ></iframe>
                            )}
                        </label>
                        {renderQuestionInput(questions[currentQuestionIndex].type, questions[currentQuestionIndex])}
                    </div>
                    {error && <p className="text-xs italic text-red-500">{error}</p>}
                    {showAnswer && (
                        <p className="text-xl text-green-500">
                            Answer: {
                                (() => {
                                    try {
                                        const answersArray = JSON.parse(currentQuestion.answer);
                                        return Array.isArray(answersArray) ? answersArray.join(" OR ") : currentQuestion.answer;
                                    } catch {
                                        return currentQuestion.answer;
                                    }
                                })()
                            }
                        </p>
                    )}
                    <button type="submit" className="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">
                        Submit Answer
                    </button>
                    <button type="button" onClick={handleShowAnswer} className="px-4 py-2 ml-4 font-bold text-white bg-green-500 rounded hover:bg-green-700 focus:outline-none focus:shadow-outline">
                        Show Answer
                    </button>
                    <button type="button" onClick={handlePreviousQuestion} className="px-4 py-2 ml-4 font-bold text-white bg-yellow-500 rounded hover:bg-yellow-700 focus:outline-none focus:shadow-outline">
                        Previous Question
                    </button>
                    <button type="button" onClick={handleSkipQuestion} className="px-4 py-2 ml-4 font-bold text-white bg-gray-500 rounded hover:bg-gray-700 focus:outline-none focus:shadow-outline">
                        Skip Question
                    </button>
                    <button type="button" onClick={() => handleIKnowThis(questions[currentQuestionIndex].id, course.slug)} className="px-4 py-2 ml-4 font-bold text-white bg-orange-500 rounded hover:bg-orange-700 focus:outline-none focus:shadow-outline">
                        I Know This
                    </button>
                    <button type="button" onClick={toggleListening} className={`px-4 py-2 ml-4 font-bold text-white rounded focus:outline-none focus:shadow-outline ${isListening ? 'bg-red-500 hover:bg-red-700' : 'bg-purple-500 hover:bg-purple-700'}`}>
                        {isListening ? 'Listening... (Click to Stop)' : 'Start Listening'}
                    </button>
                        <div className="p-4 my-4 bg-white rounded-lg shadow-md">
                            <h4 className="mb-2 text-lg font-semibold">Spaced Repetition Metrics (Debugging):</h4>
                            <pre className="p-3 overflow-x-auto text-sm bg-gray-100 rounded">{JSON.stringify(spacedRepetitionMetrics, null, 2)}</pre>
                        </div>
                    <div className="p-4 my-4 bg-white rounded-lg shadow-md">
                        <div className="font-bold text-primary">Timer: <span className="text-secondary">{formatTime(milliseconds)}</span></div>
                    </div>
                </form>
                {isListening && (
                    <div className="p-2 text-center text-green-700 bg-green-100 rounded-lg">
                        Listening for your answer...
                    </div>
                )}
                {speechAnswer && <p>Speech Answer: {speechAnswer} (Correct Count: {correctSpeechCount}/5)</p>}
            </div>
        </AuthenticatedLayout>
    );
};

export default Quiz;
