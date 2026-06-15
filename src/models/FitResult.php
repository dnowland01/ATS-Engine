<?php

namespace ATS\Models;

class FitResult
{
    public function __construct(
        public int $score,
        public string $reasoning
    ) {}
}
