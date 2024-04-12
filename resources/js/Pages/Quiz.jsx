import React, { useState, useEffect, useRef, useCallback } from 'react';
import { Link } from '@inertiajs/inertia-react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import axios from 'axios';
import Fuse from 'fuse.js';
import '@tensorflow/tfjs-core';
import '@tensorflow/tfjs-backend-webgl';
import * as faceapi from '@vladmandic/face-api/dist/face-api.esm.js';

const Quiz = ({ questions: initialQuestions, user, course }) => {
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [questions, setQuestions] = useState(initialQuestions);
    const [userAnswer, setUserAnswer] = useState('');
    const [error, setError] = useState('');
    const [quizCompleted, setQuizCompleted] = useState(initialQuestions.length === 0);
    const videoRef = useRef();
    const canvasRef = useRef();
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
    const [confidenceScore, setConfidenceScore] = useState(0);
    const [quizAttempts, setQuizAttempts] = useState([]);

    const moveToNextQuestion = useCallback(() => {
        const newAttempt = {
        confidenceScore: confidenceScore.toFixed(2),
        timeTaken: formatTime(milliseconds),
        };
        setQuizAttempts([...quizAttempts, newAttempt]);
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
    }, [currentQuestionIndex, questions.length, confidenceScore, milliseconds, quizAttempts]);


     useEffect(() => {
        async function loadModelsAndSetupVideo() {
            await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
            await faceapi.nets.faceExpressionNet.loadFromUri('/models');

            navigator.mediaDevices.getUserMedia({ video: {} })
                .then(stream => {
                    const video = document.getElementById('webcam');
                    video.srcObject = stream;
                })
                .catch(err => console.error("Error accessing the webcam:", err));
        }
        loadModelsAndSetupVideo();
     }, []);


    useEffect(() => {
        const video = document.getElementById('webcam');
        if (!video) return;

        let cooldownTimeout = null;
        let allowEmotionDetection = true;

        const onPlay = () => {
            const canvasContainer = document.getElementById('canvasContainer');
            if (!canvasContainer) {
                console.error("Canvas container not found");
                return;
            }

            const canvas = faceapi.createCanvasFromMedia(video);
            canvasContainer.appendChild(canvas);
            const displaySize = { width: video.width, height: video.height };
            faceapi.matchDimensions(canvas, displaySize);

            const interval = setInterval(async () => {
                if (!allowEmotionDetection) return;

                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceExpressions();

                if (detections.length > 0) {
                    const emotions = detections[0].expressions;
                    const happinessConfidenceScore = emotions.happy;
                    setConfidenceScore(happinessConfidenceScore); // Make sure you have defined this state variable with useState
                }

                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
                faceapi.draw.drawDetections(canvas, resizedDetections);
                faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
                faceapi.draw.drawFaceExpressions(canvas, resizedDetections);

                const isHappy = detections.some(detection => detection.expressions.happy > 0.75);
                if (isHappy) {
                    allowEmotionDetection = false;
                    clearTimeout(cooldownTimeout);
                    cooldownTimeout = setTimeout(() => {
                        allowEmotionDetection = true;
                    }, 3000);

                    // Add your logic here to handle when a user is happy
                    // For example, adjusting the spaced repetition algorithm based on the confidence score
                }
            }, 100);

            return () => {
                clearInterval(interval);
                clearTimeout(cooldownTimeout);
                if (canvasContainer.contains(canvas)) {
                    canvasContainer.removeChild(canvas);
                }
            };
        };

        video.addEventListener('play', onPlay);
        return () => video.removeEventListener('play', onPlay);
    }, [currentQuestionIndex, questions.length, setCorrectAnswersCount, setQuizCompleted, setConfidenceScore]); // Ensure all dependencies are correctly listed


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
        const normalizedUserAnswer = userAnswer.replace(/\s/g, '').toLowerCase();
        const currentQuestion = questions[currentQuestionIndex];
        let isCorrect = false;

        try {
            // Assume correct answers could be a JSON string of an array or a single string
            const correctAnswers = JSON.parse(currentQuestion.answer);
            if (Array.isArray(correctAnswers)) {
                isCorrect = correctAnswers.some(answer =>
                    answer.toLowerCase().replace(/\s/g, '') === normalizedUserAnswer
                );
            } else {
                isCorrect = correctAnswers.toLowerCase().replace(/\s/g, '') === normalizedUserAnswer;
            }
        } catch (error) {
            // Fallback for non-JSON formatted answers
            isCorrect = currentQuestion.answer.toLowerCase().replace(/\s/g, '') === normalizedUserAnswer;
        }

        if (isCorrect) {
            setError(''); // Clear the error message immediately upon correct answer
            axios.post('/submit-answer', {
                userId: user.id,
                questionId: currentQuestion.id,
                courseId: course.id,
                isCorrect: isCorrect,
                timeTaken: milliseconds,
                confidenceScore: confidenceScore.toFixed(2),
                currentInterval: currentQuestion.interval,
                currentRepetitions: currentQuestion.repetitions,
                currentEasinessFactor: currentQuestion.easinessFactor,
            })
            .then(response => {
                setCorrectAnswersCount(prev => prev + 1);
                // Assuming response.data.data now includes updated SRS metrics
                setSpacedRepetitionMetrics(response.data.data);
                moveToNextQuestion(); // Ensure this happens after updating state based on the response
            })
            .catch(error => {
                console.error('Error submitting answer:', error.response.data);
                // If you want to display this error on the UI, you could set it in your component's state
                setError(error.response.data.message || "An error occurred");
            });
        } else {
            setError("Incorrect answer. Try again.");
        }
    }, [currentQuestionIndex, questions, userAnswer, user.id, course.id, moveToNextQuestion, milliseconds, confidenceScore]);



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
                <h3 className="mt-4 text-lg font-semibold">Review of Attempts:</h3>
                {quizAttempts.map((attempt, index) => (
                    <div key={index} className="p-4 mb-4 bg-white rounded-lg shadow">
                        <h4 className="font-semibold text-md">Question {index + 1}</h4>
                        <p>Confidence Score: {attempt.confidenceScore}</p>
                        <p>Time Taken: {attempt.timeTaken}</p>
                    </div>
                ))}
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
                <div id="canvasContainer" className="relative w-full h-auto">
                    {/* The canvas will be appended here by the useEffect hook */}
                    <video className="mx-auto" id="webcam" width="720" height="560" autoPlay muted></video>
                </div>
                <div className="mb-4">
                    {currentQuestion ? (
                        <>
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
                        </>
                    ) : (
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
                    )}
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
                        <div className="font-bold text-primary">Timer: <span className="text-secondary">{formatTime(milliseconds)}</span></div>
                    </div>
                   <div className="confidence-score">
                        <div className="py-8 font-bold">Current Question Confidence Score: {confidenceScore.toFixed(2)}</div> {/* Displaying the confidence score */}
                    </div>
                    {quizAttempts.length > 0 && (
                        <div className="py-4">
                            <h4 className="py-4">Previous Question Details:</h4>
                            <p className="py-4">Previous Question Confidence Score: {quizAttempts[quizAttempts.length - 1].confidenceScore}</p>
                            <p className="py-4">Previous Question Time Taken: {quizAttempts[quizAttempts.length - 1].timeTaken}</p>
                        </div>
                    )}
                        <div className="p-4 my-4 bg-white rounded-lg shadow-md">
                            <h4 className="mb-2 text-lg font-semibold">Spaced Repetition Metrics (Debugging):</h4>
                            <pre className="p-3 overflow-x-auto text-sm bg-gray-100 rounded">{JSON.stringify(spacedRepetitionMetrics, null, 2)}</pre>
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
