<?php

require __DIR__ . '/../vendor/autoload.php';

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

if (!isset($_FILES['file']) || !isset($_POST['type'])) {
    die("Invalid request.");
}

$type = $_POST['type'];
$tmp = $_FILES['file']['tmp_name'];

$content = file_get_contents($tmp);

// Bootstrap engine
$scorer  = new FitScorer();
$tables  = new TableManager(__DIR__ . '/../data/main_table.csv', __DIR__ . '/../data/under93_table.csv');
$dups    = new DuplicateDetector();
$status  = new StatusEngine();
$notes   = new NotesEngine();
$source  = new SourceDetector();
$agency  = new AgencyDetector();
$summary = new DailySummary();

// Route to correct handler
switch ($type) {
    case 'job':
        $handler = new JobDescriptionHandler($scorer, $tables, $dups, $status, $notes, $source, $agency, $summary);
        break;

    case 'submission':
        $handler = new SubmissionHandler($tables, $dups, $status, $notes, $source, $agency, $summary);
        break;

    case 'failure':
        $handler = new FailureHandler($tables, $dups, $status, $notes, $source, $agency, $summary);
        break;

    case 'interview':
        $handler = new InterviewHandler($tables, $dups, $status, $notes, $source, $agency, $summary);
        break;

    default:
        die("Unknown type.");
}

$handler->process($content);
$summary->save(__DIR__ . '/../logs/daily_summary.log');

header("Location: index.php");
exit;
