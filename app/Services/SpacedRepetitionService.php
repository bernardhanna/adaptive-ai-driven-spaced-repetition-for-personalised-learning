<?php

namespace App\Services;

class SpacedRepetitionService
{
    public function calculateNextReview($isCorrect, $currentInterval, $currentRepetitions, $currentEasinessFactor, $confidenceScore, $timeTaken)
    {
        // Define thresholds and adjustments
        $confidenceThreshold = 0.7; // Example threshold for confidence
        $timeThreshold = 30; // Example threshold for time taken in seconds
        $easinessFactorAdjustmentOnCorrect = 0.1;
        $easinessFactorAdjustmentOnLowConfidence = -0.1;
        $easinessFactorAdjustmentOnLongTime = -0.1;

        if (!$isCorrect) {
            $newInterval = 1;
            $newRepetitions = 0;
            // Adjust easiness factor downwards more significantly for incorrect answers
            $newEasinessFactor = max(1.3, $currentEasinessFactor - 0.2);
        } else {
            $newRepetitions = $currentRepetitions + 1;
            // Adjust easiness factor based on correctness, confidence, and time taken
            $easinessAdjustment = $easinessFactorAdjustmentOnCorrect;
            if ($confidenceScore < $confidenceThreshold) {
                $easinessAdjustment += $easinessFactorAdjustmentOnLowConfidence;
            }
            if ($timeTaken > $timeThreshold) {
                $easinessAdjustment += $easinessFactorAdjustmentOnLongTime;
            }
            $newEasinessFactor = max(1.3, $currentEasinessFactor + $easinessAdjustment);

            if ($newRepetitions == 1) {
                $newInterval = 1;
            } elseif ($newRepetitions == 2) {
                $newInterval = 6;
            } else {
                $newInterval = ceil($currentInterval * $newEasinessFactor);
            }
        }

        $nextReviewDate = now()->addDays($newInterval);

        return [
            'interval' => $newInterval,
            'repetitions' => $newRepetitions,
            'easinessFactor' => $newEasinessFactor,
            'nextReviewDate' => $nextReviewDate->toDateString(),
        ];
    }
}
