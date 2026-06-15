<?php

namespace ATS\Core;

class AgencyDetector
{
    public function detect(string $text): string
    {
        $lower = strtolower($text);

        return match (true) {
            str_contains($lower, 'hays') => 'Hays',
            str_contains($lower, 'reed') => 'Reed',
            str_contains($lower, 'forward role') => 'Forward Role',
            default => 'Direct'
        };
    }
}
