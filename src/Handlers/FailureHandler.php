<?php

namespace ATS\Handlers;

use ATS\Core\{
    TableManager, DuplicateDetector, StatusEngine,
    NotesEngine, SourceDetector, AgencyDetector, DailySummary
};

class FailureHandler
{
    public function __construct(
        private TableManager $tables,
        private DuplicateDetector $dups,
        private StatusEngine $status,
        private NotesEngine $notes,
        private SourceDetector $source,
        private AgencyDetector $agency,
        private DailySummary $summary
    ) {}

    public function process(string $text): void
    {
        $company = $this->extractCompany($text);
        $role = $this->extractRole($text);

        $table = $this->tables->load('data/main_table.csv');
        $dupIndex = $this->dups->findDuplicateIndex($table, $company, $role);

        if ($dupIndex !== null) {
            $table[$dupIndex][10] = $this->status->determine('failure');
            $table[$dupIndex][11] = $this->notes->generate('failure');
            $this->tables->save('data/main_table.csv', $table);
            $this->summary->log("Failure updated for {$company} - {$role}");
            return;
        }

        // If not found → add new row
        $id = $this->tables->nextId($table);

        $row = [
            $id,
            $company,
            $role,
            "General",
            "N/A",
            "Full-time",
            "Unknown",
            "Remote",
            "UK",
            date('d-M'),
            $this->status->determine('failure'),
            $this->notes->generate('failure'),
            0,
            $this->source->detect($text),
            $this->agency->detect($text)
        ];

        $this->tables->insert($row, true);
        $this->summary->log("Failure added for {$company} - {$role}");
    }

    private function extractCompany(string $text): string
    {
        return "Unknown Company";
    }

    private function extractRole(string $text): string
    {
        return "Unknown Role";
    }
}
