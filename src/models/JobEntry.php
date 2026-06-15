<?php

namespace ATS\Models;

class JobEntry
{
    public function __construct(
        public int $id,
        public string $company,
        public string $role,
        public string $category,
        public string $fit,
        public string $type,
        public string $salary,
        public string $mode,
        public string $location,
        public string $date,
        public string $status,
        public string $notes,
        public int $dups,
        public string $source,
        public string $agency
    ) {}
}
