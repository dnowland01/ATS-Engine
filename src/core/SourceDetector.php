<?php

namespace ATS\Core;

class SourceDetector
{
    public function detect(string $text): string
    {
        $lower = strtolower($text);

        return match (true) {
            str_contains($lower, 'linkedin') => 'LinkedIn (Direct)',
            str_contains($lower, 'indeed') => 'Indeed',
            str_contains($lower, 'reed') => 'Reed (Agency)',
            str_contains($lower, 'hays') => 'Hays (Agency)',
            default => 'Direct'
        };
    }
}
