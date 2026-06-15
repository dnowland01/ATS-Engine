<?php

namespace ATS\Core;

class DailySummary
{
    private array $entries = [];

    public function log(string $message): void
    {
        $this->entries[] = date('Y-m-d H:i:s') . " - " . $message;
    }

    public function save(string $path): void
    {
        file_put_contents($path, implode(PHP_EOL, $this->entries) . PHP_EOL, FILE_APPEND);
    }
}
