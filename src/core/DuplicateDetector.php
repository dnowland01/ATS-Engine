<?php

namespace ATS\Core;

class DuplicateDetector
{
    public function findDuplicateIndex(array $table, string $company, string $role): ?int
    {
        foreach ($table as $index => $row) {
            if (
                strtolower($row[1]) === strtolower($company) &&
                strtolower($row[2]) === strtolower($role)
            ) {
                return $index;
            }
        }
        return null;
    }
}
