<?php

namespace ATS\Core;

class NotesEngine
{
    public function generate(string $eventType, string $fitReasoning = ''): string
    {
        return match ($eventType) {
            'job' => $fitReasoning,
            'submission' => 'Received Resume Sent',
            'failure' => 'Refusal Message Received',
            'interview' => 'Started Interview Stage',
            default => ''
        };
    }
}
