import React, { useState, useEffect } from 'react';

const Quiz = () => {
  const [questionData, setQuestionData] = useState(null);
  const [answer, setAnswer] = useState('');
  const [confidenceScore, setConfidenceScore] = useState(0);

  useEffect(() => {
    // Fetch question data and set it
    // This is a placeholder for the actual fetch call
    const fetchQuestionData = async () => {
      const data = await getQuestionData(); // Assume this function is defined elsewhere
      setQuestionData(data);
      setConfidenceScore(data.previousConfidenceScore || 0); // Set previous confidence score if available
    };

    fetchQuestionData();
  }, []);

  const handleAnswerChange = (event) => {
    setAnswer(event.target.value);
  };

  const handleConfidenceScoreChange = (event) => {
    setConfidenceScore(event.target.value);
  };

  const handleAnswerSubmit = () => {
    // Submit answer and confidence score
    // This is a placeholder for the actual submit call
    submitAnswer({
      answer,
      confidenceScore,
    });
  };

  if (!questionData) {
    return <div>Loading...</div>;
  }

  return (
    <div>
      <h1>{questionData.question}</h1>
      <input
        type="text"
        value={answer}
        onChange={handleAnswerChange}
        placeholder="Your answer"
      />
      <input
        type="number"
        value={confidenceScore}
        onChange={handleConfidenceScoreChange}
        placeholder="Confidence score"
      />
      <button onClick={handleAnswerSubmit}>Submit</button>
      <div>
        Previous confidence score: {confidenceScore}
      </div>
    </div>
  );
};

export default Quiz;