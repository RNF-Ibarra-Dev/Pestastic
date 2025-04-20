<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");

$allTreatments = [
    'Soil Injection',
    'Termite Powder Application',
    'Wooden Structures Treatment',
    'Termite Control',
    'Crawling Insects Control',
    'Follow-up Crawling Insects Control'
];

$allPestProblems = [];
$pestProbSql = "SELECT * FROM pest_problems;";
$pestProbResult = mysqli_query($conn, $pestProbSql);

while ($row = mysqli_fetch_assoc($pestProbResult)) {
    $allPestProblems[] = $row['problems'];
}

if (isset($_POST['submitlog']) && $_POST['submitlog'] === 'true') {
    $customerName = $_POST['add-customerName'];
    $techId = $_POST['addTechnician'] ?? [];
    $treatmentDate = $_POST['add-treatmentDate'];
    $treatment = $_POST['add-treatment'];
    $problems = $_POST['pest_problems'] ?? []; //array
    $chemUsed = $_POST['addChem'] ?? []; //arrya
    $amtUsed = $_POST['amountUsed'] ?? []; //array
    $status = $_POST['status'];
    $techPwd = $_POST['techPwd'];

    if (empty($customerName) || empty($techId) || empty($treatmentDate) || empty($treatment) || empty($problems) || empty($chemUsed) || empty($amtUsed) || empty($status)) {
        http_response_code(400);
        echo json_encode(['type' => 'emptyinput', 'errorMessage' => "All input fields are required."]);
        exit();
    }

    if (!in_array($treatment, $allTreatments, true)) {
        http_response_code(400);
        echo json_encode(['type' => 'invalid_array', 'errorMessage' => 'Treatment not valid. Please refresh the page and try again.']);
        exit();
    }


    if (empty($techPwd)) {
        http_response_code(400);
        echo json_encode(['type' => 'verificationerror', 'errorMessage' => 'Verification required.']);
        exit();
    }

    if (!validateTech($conn, $techPwd)) {
        http_response_code(400);
        echo json_encode(['type' => 'wrongpassword', 'errorMessage' => 'Manager password is incorrect.']);
        exit();
    }

    
    $transaction = newTransaction($conn, $customerName, $techId, $treatmentDate, $treatment, $chemUsed, $amtUsed, $status, $problems);

    if (!isset($transaction['success'])) {
        http_response_code(400);
        echo json_encode(['type' => 'function', 'errorMessage' => $transaction['errorMessage'], 'line' => $transaction['line'], 'file' => $transaction['file'], 'trace' => $transaction['stringTrace']]);
        exit();
    } else {
        http_response_code(200);
        echo json_encode(['success' => 'Transaction added.', 'iterate' => $transaction['iterate']]);
        exit();
    }

}