<?php

require __DIR__ . '/../vendor/autoload.php';

use ATS\Core\{
    FitScorer, TableManager, DuplicateDetector, StatusEngine,
    NotesEngine, SourceDetector, AgencyDetector, DailySummary
};

use ATS\Handlers\JobDescriptionHandler;

$tables = new TableManager(
    __DIR__ . '/../data/main_table.csv',
    __DIR__ . '/../data/under93_table.csv'
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $text = trim($_POST['job_spec'] ?? '');

    if ($text === '') {
        die("No job spec provided.");
    }

    // Bootstrap engine
    $scorer  = new FitScorer();
    $dups    = new DuplicateDetector();
    $status  = new StatusEngine();
    $notes   = new NotesEngine();
    $source  = new SourceDetector();
    $agency  = new AgencyDetector();
    $summary = new DailySummary();

    $handler = new JobDescriptionHandler(
        $scorer, $tables, $dups, $status, $notes, $source, $agency, $summary
    );

    $handler->process($text);

    $summary->save(__DIR__ . '/../logs/daily_summary.log');

    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Paste Job Spec</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        textarea { width: 100%; height: 400px; }
        button { padding: 10px 20px; }
    </style>
</head>
<body>

<h1>Paste Job Specification</h1>

<p>Paste the full job description text below. The system will:</p>
<ul>
    <li>Score the job spec</li>
    <li>Detect duplicates</li>
    <li>Insert into Main or Under‑93</li>
    <li>Generate notes + status</li>
    <li>Detect source + agency</li>
    <li>Log the summary</li>
</ul>

<form method="POST">
    <textarea name="job_spec" placeholder="Paste job spec text here..."></textarea><br><br>
    <button type="submit">Process Job Spec</button>
</form>

<p><a href="index.php">Back to Dashboard</a></p>

</body>
</html>
