<?php

namespace ATS\Core;

class StatusEngine
{
    public function determine(string $eventType): string
    {
        return match ($eventType) {
            'job' => 'To Apply',
            'submission' => 'Applied',
            'failure' => 'Refused',
            'interview' => 'Interviewing',
            default => 'Unknown'
        };
    }
}
