<?php
require_once '../../includes/dbh.inc.php';
require_once '../../includes/functions.inc.php';

$allTreatments = [
    'Soil Injection',
    'Termite Powder Application',
    'Wooden Structures Treatment',
    'Termite Control',
    'Crawling Insects Control',
    'Follow-up Crawling Insects Control'
];

$allStatus = [
    'Accepted',
    'Pending',
    'Voided',
    'Completed'
];

$allPestProblems = [];
$pestProbSql = "SELECT * FROM pest_problems;";
$pestProbResult = mysqli_query($conn, $pestProbSql);

while ($row = mysqli_fetch_assoc($pestProbResult)) {
    $allPestProblems[] = $row['problems'];
}

// print_r($allPestProblems);
// echo $allPestProblems[0];
