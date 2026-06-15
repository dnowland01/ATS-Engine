<?php

require __DIR__ . '/../vendor/autoload.php';

use ATS\Core\TableManager;

$tables = new TableManager(
    __DIR__ . '/../data/main_table.csv',
    __DIR__ . '/../data/under93_table.csv'
);

$main = $tables->load(__DIR__ . '/../data/main_table.csv');
$under = $tables->load(__DIR__ . '/../data/under93_table.csv');

?>
<!DOCTYPE html>
<html>
<head>
    <title>ATS Dashboard</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        h1 { margin-bottom: 10px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 40px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
        .section { margin-bottom: 60px; }
        .upload-box { padding: 20px; border: 1px solid #ccc; background: #fafafa; }
    </style>
</head>
<body>

<h1>ATS Engine — Web Dashboard</h1>

<div class="section">
    <h2>Upload Input</h2>
    <p>
        <a href="paste_job.php">Paste Job Spec</a>
    </p>
    <div class="upload-box">
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label>Choose file:</label><br>
            <input type="file" name="file" required><br><br>

            <label>Type:</label><br>
            <select name="type">
                <option value="job">Job Description</option>
                <option value="submission">Submission</option>
                <option value="failure">Failure</option>
                <option value="interview">Interview</option>
            </select><br><br>

            <button type="submit">Process</button>
        </form>
    </div>
</div>

<div class="section">
    <h2>Main Table (Fit ≥ 93%)</h2>
    <table>
        <tr>
            <?php if (!empty($main)): ?>
                <?php foreach ($main[0] as $header): ?>
                    <th><?= htmlspecialchars($header) ?></th>
                <?php endforeach; ?>
            <?php endif; ?>
        </tr>

        <?php foreach ($main as $row): ?>
            <tr>
                <?php foreach ($row as $cell): ?>
                    <td><?= htmlspecialchars($cell) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="section">
    <h2>Under‑93 Table</h2>
    <table>
        <tr>
            <?php if (!empty($under)): ?>
                <?php foreach ($under[0] as $header): ?>
                    <th><?= htmlspecialchars($header) ?></th>
                <?php endforeach; ?>
            <?php endif; ?>
        </tr>

        <?php foreach ($under as $row): ?>
            <tr>
                <?php foreach ($row as $cell): ?>
                    <td><?= htmlspecialchars($cell) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="section">
    <h2>Daily Summary</h2>
    <pre style="background:#f4f4f4; padding:20px; border:1px solid #ccc;">
<?= htmlspecialchars(@file_get_contents(__DIR__ . '/../logs/daily_summary.log')) ?>
    </pre>
</div>

</body>
</html>
