<?php

namespace ATS\Core;

use ATS\Models\FitResult;

class FitScorer
{
    public function score(string $jobDescription): FitResult
    {
        // Placeholder logic — you will implement your Compare Rule here
        $score = rand(70, 100);
        $reasoning = "Auto-generated reasoning based on job description analysis.";

        return new FitResult($score, $reasoning);
    }
}
