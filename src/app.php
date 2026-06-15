<?php

require 'vendor/autoload.php';

use ATS\Core\{
    FitScorer, TableManager, DuplicateDetector, StatusEngine,
    NotesEngine, SourceDetector, AgencyDetector, DailySummary
};
use ATS\Handlers\JobDescriptionHandler;

$scorer = new FitScorer();
$tables = new TableManager('data/main_table.csv', 'data/under93_table.csv');
$dups = new DuplicateDetector();
$status = new StatusEngine();
$notes = new NotesEngine();
$source = new SourceDetector();
$agency = new AgencyDetector();
$summary = new DailySummary();

$handler = new JobDescriptionHandler(
    $scorer, $tables, $dups, $status, $notes, $source, $agency, $summary
);

$jobDescription = file_get_contents('job.txt');
$handler->process($jobDescription);

$summary->save('logs/daily_summary.log');

echo "Job processed.\n";
