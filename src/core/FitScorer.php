<?php

namespace ATS\Core;

use ATS\Models\FitResult;

class FitScorer
{
    public function score(string $jobDescription): FitResult
    {
        // TODO: Replace with your real Compare Rule logic.
        // For now, a placeholder deterministic scoring pattern:
        $keywords = [
            'agile' => 10,
            'scrum' => 10,
            'delivery' => 15,
            'manager' => 10,
            'coach' => 10,
            'lead' => 10,
            'program' => 10,
            'technical' => 10,
            'remote' => 5
        ];

        $score = 50;
        foreach ($keywords as $word => $value) {
            if (stripos($jobDescription, $word) !== false) {
                $score += $value;
            }
        }

        $score = min($score, 100);

        $reasoning = "Fit score generated based on keyword relevance and role alignment.";

        return new FitResult($score, $reasoning);
    }
}
