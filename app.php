#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use ATS\Core\{
    FitScorer, TableManager, DuplicateDetector, StatusEngine,
    NotesEngine, SourceDetector, AgencyDetector, DailySummary
};

use ATS\Handlers\{
    JobDescriptionHandler,
    SubmissionHandler,
    FailureHandler,
    InterviewHandler
};

// ------------------------------------------------------------
// Bootstrap Core Engine
// ------------------------------------------------------------

$scorer  = new FitScorer();
$tables  = new TableManager('./data/MainTrackingTable.csv', './data/Under93Table.csv');
$dups    = new DuplicateDetector();
$status  = new StatusEngine();
$notes   = new NotesEngine();
$source  = new SourceDetector();
$agency  = new AgencyDetector();
$summary = new DailySummary();

// ------------------------------------------------------------
// CLI Argument Parsing
// ------------------------------------------------------------

$usage = "
ATS Engine — Command Line Interface

Usage:
  php app.php job <file>
  php app.php submission <file>
  php app.php failure <file>
  php app.php interview <file>

Examples:
  php app.php job job.txt
  php app.php submission submission_email.txt
  php app.php failure rejection.txt
  php app.php interview invite.txt
";

if ($argc < 3) {
    echo $usage;
    exit(1);
}

$command = strtolower($argv[1]);
$file    = $argv[2];

if (!file_exists($file)) {
    echo "Error: File not found: {$file}\n";
    exit(1);
}

$content = file_get_contents($file);

// ------------------------------------------------------------
// Command Routing
// ------------------------------------------------------------

switch ($command) {

    case 'job':
        $handler = new JobDescriptionHandler(
            $scorer, $tables, $dups, $status, $notes, $source, $agency, $summary
        );
        $handler->process($content);
        echo "Job description processed.\n";
        break;

    case 'submission':
        $handler = new SubmissionHandler(
            $tables, $dups, $status, $notes, $source, $agency, $summary
        );
        $handler->process($content);
        echo "Submission processed.\n";
        break;

    case 'failure':
        $handler = new FailureHandler(
            $tables, $dups, $status, $notes, $source, $agency, $summary
        );
        $handler->process($content);
        echo "Failure processed.\n";
        break;

    case 'interview':
        $handler = new InterviewHandler(
            $tables, $dups, $status, $notes, $source, $agency, $summary
        );
        $handler->process($content);
        echo "Interview processed.\n";
        break;

    default:
        echo "Unknown command: {$command}\n\n";
        echo $usage;
        exit(1);
}

// ------------------------------------------------------------
// Save Daily Summary
// ------------------------------------------------------------

$summary->save('logs/daily_summary.log');

echo "Daily summary updated.\n";
