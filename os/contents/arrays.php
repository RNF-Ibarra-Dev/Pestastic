<?php
require_once '../../includes/dbh.inc.php';
require_once '../../includes/functions.inc.php';

// $allTreatments = [
//     'Soil Injection',
//     'Termite Powder Application',
//     'Wooden Structures Treatment',
//     'Termite Control',
//     'Crawling Insects Control',
//     'Follow-up Crawling Insects Control'
// ];

$allTreatments = [];
$treatments = "SELECT t_name FROM treatments;";
$t_result = mysqli_query($conn, $treatments);
while ($row = mysqli_fetch_assoc($t_result)) {
    $allTreatments[] = $row['t_name'];
}

$allStatus = [
    'Accepted',
    'Pending',
    'Voided',
    'Completed',
    'Finalizing',
    'Cancelled',
    'Dispatched'
];

$treatmentType = [
    'General Treatment',
    'Follow-up Treatment',
    'Quarterly Treatment',
    'Monthly Treatment'
];

$allPestProblems = [];
$pestProbSql = "SELECT * FROM pest_problems;";
$pestProbResult = mysqli_query($conn, $pestProbSql);

while ($row = mysqli_fetch_assoc($pestProbResult)) {
    $allPestProblems[] = $row['problems'];
}

$packageIds = [];
$package = "SELECT * FROM packages;";
$packRes = mysqli_query($conn, $package);

while ($row = mysqli_fetch_assoc($packRes)) {
    $packageIds[] = $row['id'];
}