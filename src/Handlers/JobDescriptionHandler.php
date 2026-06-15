<?php

namespace ATS\Handlers;

use ATS\Core\{
    FitScorer, TableManager, DuplicateDetector, StatusEngine,
    NotesEngine, SourceDetector, AgencyDetector, DailySummary
};

class JobDescriptionHandler
{
    public function __construct(
        private FitScorer $scorer,
        private TableManager $tables,
        private DuplicateDetector $dups,
        private StatusEngine $status,
        private NotesEngine $notes,
        private SourceDetector $source,
        private AgencyDetector $agency,
        private DailySummary $summary
    ) {}

    public function process(string $jobDescription): void
    {
        $fit = $this->scorer->score($jobDescription);
        $isMain = $fit->score >= 93;

        $path = $isMain ? 'data/main_table.csv' : 'data/under93_table.csv';
        $table = $this->tables->load($path);

        // Extract company + role (placeholder logic)
        $company = "Unknown Company";
        $role = "Unknown Role";

        // Duplicate detection
        $dupIndex = $this->dups->findDuplicateIndex($table, $company, $role);

        if ($dupIndex !== null) {
            $table[$dupIndex][12]++; // Increment Dups
            $table[$dupIndex][11] .= " | Duplicate of earlier role";
            $this->tables->save($path, $table);
            $this->summary->log("Duplicate detected for {$company} - {$role}");
            return;
        }

        $id = $this->tables->nextId($table);

        $row = [
            $id,
            $company,
            $role,
            "General",
            "{$fit->score}%",
            "Full-time",
            "Unknown",
            "Remote",
            "UK",
            date('d-M'),
            $this->status->determine('job'),
            $this->notes->generate('job', $fit->reasoning),
            0,
            $this->source->detect($jobDescription),
            $this->agency->detect($jobDescription)
        ];

        $this->tables->insert($row, $isMain);
        $this->summary->log("Added job entry ID {$id} with fit {$fit->score}%");
    }
}
