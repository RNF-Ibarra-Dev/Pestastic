<?php
session_start();
require_once '../../includes/dbh.inc.php';
require_once '../../includes/functions.inc.php';
// var_dump($_POST);
require_once 'arrays.php';

if (isset($_POST['addSubmit']) && $_POST['addSubmit'] === 'true') {
    // echo 'success';
    $customerName = $_POST['add-customerName'];
    $techId = $_POST['add-technicianName'] ?? [];
    $treatmentDate = $_POST['add-treatmentDate'];
    $treatment = $_POST['add-treatment'];
    $problems = $_POST['pest_problems'] ?? []; //array
    $chemUsed = $_POST['add_chemBrandUsed'] ?? []; //arrya
    $amtUsed = $_POST['add_amountUsed'] ?? []; //array
    $status = $_POST['add-status'];
    $saPwd = $_POST['saPwd'];

    // if(!in_array($problems, $allPestProblems)){
    //     http_response_code(400);
    //     echo json_encode(['type' => 'invalid_array', 'errorMessage' => 'Not in pest problems array']);
    //     exit();
    // }

    if (!in_array($status, $allStatus)) {
        http_response_code(400);
        echo json_encode(['type' => 'invalid_array', 'errorMessage' => 'Status not valid. Please refresh the page and try again.']);
        exit();
    }

    if (!in_array($treatment, $allTreatments, true)) {
        http_response_code(400);
        echo json_encode(['type' => 'invalid_array', 'errorMessage' => 'Treatment not valid. Please refresh the page and try again.']);
        exit();
    }

    if (empty($customerName) || empty($techId) || empty($treatmentDate) || empty($treatment) || empty($problems) || empty($chemUsed) || empty($amtUsed) || empty($status)) {
        // header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['type' => 'emptyinput', 'errorMessage' => "All input fields are required."]);
        exit();
    }

    if (empty($saPwd)) {
        // header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['type' => 'verificationerror', 'errorMessage' => 'Verification required.']);
        exit();
    }

    if (!validate($conn, $saPwd)) {
        // header('Content-Type: application/json');
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

if (isset($_POST['delete']) && $_POST['delete'] === 'true') {
    $transid = $_POST['id'];
    $pwd = $_POST['saPwd'];

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'Incorrect admin password. Please try again.']);
        exit();
    }

    $delete = deleteTransaction($conn, $transid);

    if (isset($delete['success'])) {
        // var_dump($delete['success']);
        http_response_code(200);
        echo json_encode(['success' => $delete['success']]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => $delete['error']]);
        exit();
    }
}


if (isset($_POST['update']) && $_POST['update'] === 'true') {
    $transId = $_POST['edit-transId'];
    $customerName = $_POST['edit-customerName'];
    $techId = $_POST['edit-technicianName'] ?? [];
    $treatmentDate = $_POST['edit-treatmentDate'];
    $treatment = $_POST['edit-treatment'];
    $problems = $_POST['pest_problems'] ?? []; //array
    $chemUsed = $_POST['edit_chemBrandUsed'] ?? []; //arrya
    $amtUsed = $_POST['edit-amountUsed'] ?? []; //array
    $status = $_POST['edit-status'];
    $saPwd = $_POST['edit-saPwd'];

    $data = [
        'transId' => $transId,
        'customer' => $customerName,
        'tech' => $techId,
        'treatmentDate' => $treatmentDate,
        'treatment' => $treatment,
        'problems' => $problems,
        'chemUsed' => $chemUsed,
        'amtUsed' => $amtUsed,
        'status' => $status
    ];

    if (!validate($conn, $saPwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Password.']);
        exit();
    }

    $update = update_transaction($conn, $data, $techId, $chemUsed, $amtUsed, $problems);
    if (!isset($update['success'])) {
        http_response_code(400);
        echo json_encode(['error' => $update['errorMessage'] . $update['line'] . $update['file'] . $update['stringTrace']]);
    } else {
        echo json_encode([
            'success' => 'function success ' . $update['success'],
            'techs' => $update['ids'],
            'diffs' => $update['diffs'],
            'ftech' => $update['ftech'],
            'fchems' => $update['fchems'],
            'fprob' => $update['fprob']
        ]);
    }
}

if (isset($_POST['approve']) && $_POST['approve'] === 'true') {
    $pwd = $_POST['approve-pwd'];
    $transId = $_POST['transid'];

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['type' => 'wrongpwd', 'error' => 'Wrong Password']);
        exit();
    }

    $approve = approve_transaction($conn, $transId);
    if ($approve) {
        http_response_code(200);
        echo json_encode(['success' => 'Transaction Accepted!']);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['type' => 'function', 'error' => $approve]);
        exit();
    }

}

if (isset($_POST['submitvoidreq']) && $_POST['submitvoidreq'] === 'true') {
    $trans = $_POST['trans'];
    $pwd = $_POST['saPwd'];

    if (empty($trans) || empty($pwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'Empty Inputs.']);
        exit();
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'Wrong Password.']);
        exit();
    }

    $voidreq = void_transaction($conn, $trans);

    if (isset($voidreq['msg'])) {
        http_response_code(400);
        echo json_encode(['error' => $voidreq['msg'] . $voidreq['id']]);
        exit();
    }

    http_response_code(200);
    echo json_encode(['success' => $voidreq['success']]);
    exit();

}