<?php
session_start();
require_once '../../includes/dbh.inc.php';
require_once '../../includes/functions.inc.php';
// var_dump($_POST);
require_once 'arrays.php';

$author = $_SESSION['fname'] . ' ' . $_SESSION['lname'];


if (isset($_POST['addSubmit']) && $_POST['addSubmit'] === 'true') {
    // echo 'success';
    $customerName = $_POST['add-customerName'];
    $techId = $_POST['add-technicianName'] ?? [];
    $treatmentDate = $_POST['add-treatmentDate'];
    $address = $_POST['add-customerAddress'];
    $treatmentTime = $_POST['add-treatmentTime'];
    $treatment = $_POST['add-treatment'] ?? null;
    $t_type = $_POST['add-treatmentType'];
    $package = $_POST['add-package'] ?? null;
    $pstart = $_POST['add-packageStart'] ?? null;
    $pexp = $_POST['add-packageExpiry'] ?? null;
    $problems = $_POST['pest_problems'] ?? []; //array
    $chemUsed = $_POST['add_chemBrandUsed'] ?? []; //arrya
    $note = $_POST['add-notes'];
    $status = $_POST['add-status'];
    $session = $_POST['add-session'] ?? null;
    $saPwd = $_POST['saPwd'];

    // add created by
    $addedBy = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

    if ($package != 'none') {
        if (!in_array($package, $packageIds)) {
            http_response_code(400);
            echo json_encode(['type' => 'invalid_array', 'errorMessage' => 'Invalid Package. Please Try Again.']);
            exit();
        }
        $treatment = get_package_treatment($conn, $package);
        if (isset($treatment['error'])) {
            http_response_code(400);
            echo json_encode(['type' => 'invalid_id', 'errorMessage' => $treatment['error']]);
            exit();
        }
    }

    if (empty($customerName) || empty($techId) || empty($treatmentDate) || empty($problems) || empty($chemUsed) || empty($status) || empty($t_type) || empty($address)) {
        http_response_code(400);
        echo json_encode(['type' => 'emptyinput', 'errorMessage' => "All input fields are required."]);
        exit();
    }

    if ($package !== 'none') {
        if (empty($session)) {
            http_response_code(400);
            echo json_encode(['type' => 'emptyinput', 'errorMessage' => "Session count is required." . $package]);
            exit();
        }
        if (empty($pstart) || empty($pexp)) {
            http_response_code(400);
            echo json_encode(['type' => 'emptyinput', 'errorMessage' => "Missing Package Warranty Start."]);
            exit();
        }
    } else {
        if (empty($treatment)) {
            http_response_code(400);
            echo json_encode(['type' => 'emptyinput', 'errorMessage' => "Missing Treatment Assigned."]);
            exit();
        }
        $package = null;
    }

    if (!in_array($status, $allStatus)) {
        http_response_code(400);
        echo json_encode(['type' => 'invalid_array', 'errorMessage' => 'Invalid Status. Please Try Again.']);
        exit();
    }

    if (empty($saPwd)) {
        // header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['type' => 'verificationerror', 'errorMessage' => 'Verification required.']);
        exit();
    }

    if (!validateOS($conn, $saPwd)) {
        // header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['type' => 'wrongpassword', 'errorMessage' => 'Manager password is incorrect.']);
        exit();
    }

    $transaction = newTransaction($conn, $customerName, $address, $techId, $treatmentDate, $treatmentTime, $treatment, $chemUsed, $status, $problems, $package, $t_type, $session, $note, $pstart, $pexp, $addedBy);

    if (!isset($transaction['success'])) {
        http_response_code(400);
        echo json_encode(['type' => 'function', 'errorMessage' => $transaction['errorMessage'], 'line' => $transaction['line'], 'file' => $transaction['file'], 'trace' => $transaction['stringTrace']]);
        exit();
    } else {
        http_response_code(200);
        echo json_encode(['success' => 'Transaction added.']);
        exit();
    }
}

if (isset($_POST['delete']) && $_POST['delete'] === 'true') {
    $transid = $_POST['id'];
    $pwd = $_POST['saPwd'];

    if (!validateOS($conn, $pwd)) {
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
    $address = $_POST['edit-address'];
    $techId = $_POST['edit-technicianName'] ?? [];
    $treatmentDate = $_POST['edit-treatmentDate'];
    $treatmentTime = $_POST['edit-treatmentTime'];
    $treatment = $_POST['edit-treatment'] ?? null;
    $ttype = $_POST['edit-treatmentType'];
    $package = $_POST['edit-package'] ?? null;
    $pstart = $_POST['edit-start'] ?? null;
    $pexp = $_POST['edit-expiry'] ?? null;
    $session = $_POST['edit-session'] ?? null;
    $problems = $_POST['pest_problems'] ?? []; //array
    $chemUsed = $_POST['edit_chemBrandUsed'] ?? []; //arrya
    $amtUsed = $_POST['edit-amountUsed'] ?? null; //array
    $status = $_POST['edit-status'];
    $note = $_POST['edit-note'] ?? null;
    $saPwd = $_POST['edit-saPwd'];
    $upby = $_SESSION['fname'] . ' ' . $_SESSION['lname'];


    $allowedUpdateStatus = ['Pending', 'Accepted', 'Finalizing', 'Cancelled'];

    // add updated by 

    if (empty($customerName) || empty($techId) || empty($treatmentDate) || empty($treatmentTime) || empty($problems) || empty($chemUsed) || empty($status) || empty($ttype) || empty($address)) {
        http_response_code(400);
        echo "All input fields are required.";
        exit();
    }

    if ($status === 'Finalizing') {
        for ($i = 0; $i < count($amtUsed); $i++) {
            if (empty($amtUsed[$i]) || !is_numeric($amtUsed[$i]) || $amtUsed[$i] <= 0) {
                http_response_code(400);
                echo "Error. Invalid Amount Used.";
                exit();
            }
        }
    }

    $oStatus = check_status($conn, $transId);
    // no transId
    if (!$oStatus) {
        http_response_code(400);
        echo 'Unknown transaction ID.';
        exit();
    } elseif (isset($oStatus['error'])) {
        // stmt error
        http_response_code(400);
        echo $oStatus['error'];
        exit();
    } elseif (!in_array($oStatus, $allowedUpdateStatus)) {
        // if status is not pending or accepted
        http_response_code(400);
        echo 'Invalid Status. Completed and voided transactions cannot be edited.';
        exit();
    }
    // http_response_code(400);
    // echo var_dump($upby);
    // exit();

    if ($package != 'none') {
        if (!in_array($package, $packageIds)) {
            http_response_code(400);
            echo 'Invalid Package ID. Please Try Again.';
            exit();
        }
        $treatment = get_package_treatment($conn, $package);
        if (isset($treatment['error'])) {
            http_response_code(400);
            echo $treatment['error'];
            exit();
        }
    } else {
        $package = null;
    }

    $data = [
        'transId' => $transId,
        'customer' => $customerName,
        'tech' => $techId,
        'treatmentDate' => $treatmentDate,
        'treatment' => $treatment,
        'problems' => $problems,
        'chemUsed' => $chemUsed,
        'amtUsed' => $amtUsed,
        'status' => $status,
        'address' => $address,
        'treatmentTime' => $treatmentTime,
        'ttype' => $ttype,
        'package' => $package,
        'session' => $session,
        'pstart' => $pstart,
        'pexp' => $pexp,
        'note' => $note,
        'upby' => $upby
    ];

    if (!validateOS($conn, $saPwd)) {
        http_response_code(400);
        echo 'Invalid Password.';
        exit();
    }

    $update = update_transaction($conn, $data, $techId, $chemUsed, $amtUsed, $problems);
    if (!isset($update['success'])) {
        http_response_code(400);
        echo $update['errorMessage'] . ' ' . $update['line'] . ' ' . $update['file'];
    } else {
        http_response_code(200);
        echo json_encode([
            'success' => 'Transaction Updated.',
            // 'techs' => $update['ids'],
            // 'diffs' => $update['diffs'],
            // 'ftech' => $update['ftech'],
            // 'fchems' => $update['fchems'],
            // 'fprob' => $update['fprob']
        ]);
    }
}

if (isset($_POST['approve']) && $_POST['approve'] === 'true') {
    $pwd = $_POST['approve-pwd'];
    $transId = $_POST['transid'];

    if (!validateOS($conn, $pwd)) {
        http_response_code(400);
        echo 'wrong password';
        exit();
    }

    $approve = approve_transaction($conn, $transId, $author);
    if (isset($approve['error'])) {
        http_response_code(400);
        echo $approve['error'];
        exit();
    }else if($approve) {
        http_response_code(200);
        echo json_encode(['success' => 'Transaction Accepted.']);
        exit();
    } else {
        http_response_code(400);
        echo 'Unknown Error Occurred.';
        exit();
    }
}

if (isset($_POST['submitvoidreq']) && $_POST['submitvoidreq'] === 'true') {
    $trans = $_POST['transid'];
    $pwd = $_POST['baPwd'];



    $status = check_status($conn, $trans);
    if ($status === 'Voided' || $status === 'Finalizing' || $status === 'Completed') {
        http_response_code(400);
        echo 'Invalid Status. Transaction already voided or completed.';
        exit();
    }


    if (empty($trans) || empty($pwd)) {
        http_response_code(400);
        echo 'Empty Inputs.';
        exit();
    }

    if (!validateOS($conn, $pwd)) {
        http_response_code(400);
        echo 'Wrong Password.';
        exit();
    }

    $voidreq = request_void($conn, $trans, $author);

    if (isset($voidreq['error'])) {
        http_response_code(400);
        echo $voidreq['error'];
        exit();
    } elseif ($voidreq) {
        http_response_code(200);
        echo json_encode(['success' => 'Void Request Submitted.']);
        exit();
    } else {
        http_response_code(400);
        echo 'Unknown Error Occurred.';
        exit();
    }
}


if (isset($_POST['finalize']) && $_POST['finalize'] === 'true') {
    $ids = $_POST['trans'] ?? [];
    $pwd = $_POST['baPwd'];

    if (empty($ids)) {
        http_response_code(400);
        echo "Please select a transaction.";
        exit();
    }

    if (empty($pwd)) {
        http_response_code(400);
        echo "Empty Password.";
        exit();
    }

    for ($i = 0; $i < count($ids); $i++) {
        $status = check_status($conn, $ids[$i]);
        if ($status !== "Finalizing") {
            http_response_code(400);
            echo "Invalid Status. Make sure the status is Accepted and Completed.";
            exit();
        }
    }

    if (!validateOS($conn, $pwd)) {
        http_response_code(400);
        echo "Wrong Password.";
        exit();
    }

    $finalize = finalize_transactions($conn, $ids, $author);
    if (isset($finalize['error'])) {
        http_response_code(400);
        echo $finalize['error'];
        exit();
    } else {
        http_response_code(200);
        echo json_encode(['success' => 'Transactions Finalized.']);
        exit();
    }
}

if (isset($_POST['reschedule']) && $_POST['reschedule'] === 'true') {
    $id = $_POST['reschedid'];
    $date = $_POST['reschedDate'];
    $time = $_POST['reschedTime'];
    $pwd = $_POST['baPwd'];

    if (!is_numeric($id) || empty($id)) {
        http_response_code(400);
        echo 'Invalid Transaction ID.';
        exit();
    }

    if (empty($date) || empty($time)) {
        http_response_code(400);
        echo 'Date and Time are required.';
        exit();
    }

    $status = check_status($conn, $id);
    if ($status !== 'Cancelled') {
        http_response_code(400);
        echo 'Invalid Status. Only cancelled transactions can be rescheduled.';
        exit();
    }

    if (!validateOS($conn, $pwd)) {
        http_response_code(400);
        echo 'Wrong Password.';
        exit();
    }

    $resched = reschedule_transaction($conn, $id, $date, $time, $author);
    if (isset($resched['error'])) {
        http_response_code(400);
        echo $resched['error'] . ' at line ' . $resched['line'] . ' in file ' . $resched['file'];
        exit();
    } else {
        http_response_code(200);
        echo json_encode(['success' => 'Transaction Rescheduled.']);
        exit();
    }
}


if (isset($_POST['cancel']) && $_POST['cancel'] === 'true') {
    $id = $_POST['transid'];
    $pwd = $_POST['baPwd'];

    $status = check_status($conn, $id);
    if ($status === 'Cancelled') {
        http_response_code(400);
        echo "Transaction already cancelled.";
        exit();
    }

    if (empty($id)) {
        http_response_code(400);
        echo "Invalid ID.";
        exit();
    }

    if (empty($pwd)) {
        http_response_code(400);
        echo "Password is empty.";
        exit();
    }

    if (!validateOS($conn, $pwd)) {
        http_response_code(400);
        echo "Wrong Password.";
        exit();
    }

    $cancel = cancel_transaction($conn, $id, $author);
    if (isset($cancel['error'])) {
        http_response_code(400);
        echo $cancel['error'] . ' at line ' . $cancel['line'] . ' in file ' . $cancel['file'];
        exit();
    } elseif ($cancel) {
        http_response_code(200);
        echo json_encode(['success' => 'Transaction Cancelled.']);
        exit();
    } else {
        http_response_code(400);
        echo 'Unkown Error Occured.';
        exit();
    }
}
