<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");

$author = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
$role = "technician";
$user = $_SESSION['techId'];
$branch = $_SESSION['branch'];

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

if (isset($_POST['addSubmit']) && $_POST['addSubmit'] === 'true') {
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
    $amtUsed = $_POST['add-amountUsed'] ?? [];
    $note = $_POST['add-notes'];
    $status = $_POST['add-status'];
    $session = $_POST['add-session'] ?? null;
    $saPwd = $_POST['saPwd'];

    // add created by
    $addedBy = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];

    // http_response_code(400);
    // echo var_dump(strtotime($pstart), strtotime($pexp));
    // exit();

    if (strtotime($pexp) < strtotime($pstart)) {
        http_response_code(400);
        echo "Invalid Package Expiry Date.";
        exit();
    } else {
        $pexp = date("Y-m-d", strtotime($pexp));
        $pstart = date("Y-m-d", strtotime($pstart));
        $treatmentDate = date("Y-m-d", strtotime($treatmentDate));
    }

    if ($package === 'none' || $package === NULL) {
        if (empty($treatment)) {
            http_response_code(400);
            echo "Missing Treatment Assigned.";
            exit();
        }
        $package = NULL;
    } else {
        if (!in_array($package, $packageIds)) {
            http_response_code(400);
            echo 'Invalid Package. Please Try Again.';
            exit();
        }
        $treatment = get_package_treatment($conn, $package);
        if (isset($treatment['error'])) {
            http_response_code(400);
            echo $treatment['error'];
            exit();
        }
        if (empty($session) || !is_numeric($session)) {
            http_response_code(400);
            echo "Invalid Session Count.";
            exit();
        }
        if (empty($pstart) || empty($pexp)) {
            http_response_code(400);
            echo "Missing Package Warranty Start.";
            exit();
        }
    }

    if ($status !== 'Pending') {
        http_response_code(400);
        echo "Invalid status access. Please try again.";
        exit();
    }

    if (empty($customerName) || empty($techId) || empty($treatmentDate) || empty($problems) || empty($chemUsed) || empty($status) || empty($t_type) || empty($address)) {
        http_response_code(400);
        echo "All input fields are required.";
        exit();
    }

    if (!validate_no_numbers($customerName)) {
        http_response_code(400);
        echo "Customer name should only contain alphabets without numbers.";
        exit();
    }

    if (empty($saPwd)) {
        http_response_code(400);
        echo 'Verification required.';
        exit();
    }

    if (!validateTech($conn, $saPwd)) {
        http_response_code(400);
        echo 'Incorrect Password';
        exit();
    }

    $transaction = newTransaction($conn, $customerName, $address, $techId, $treatmentDate, $treatmentTime, $treatment, $chemUsed, $status, $problems, $package, $t_type, $session, $note, $pstart, $pexp, $addedBy, $amtUsed, $user, $role, $branch);

    if (isset($transaction['error'])) {
        http_response_code(400);
        echo $transaction['error'];
        exit();
    } else if ($transaction) {
        http_response_code(200);
        echo json_encode(['success' => 'Transaction added.']);
        exit();
    } else {
        http_response_code(400);
        echo "An unknown error has occured. Please try again later. " . $transaction['error'];
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

    $valid_status = ['Cancelled', 'Pending', 'Accepted'];

    $status = check_status($conn, $id);
    if (!in_array($status, $valid_status)) {
        http_response_code(400);
        echo 'Invalid Status.';
        exit();
    }

    if (!validateTech($conn, $pwd)) {
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
        echo 'Input Required.';
        exit();
    }

    if (!validateTech($conn, $pwd)) {
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


if (isset($_POST['finalsingletransact']) && $_POST['finalsingletransact'] === 'true') {
    $id = $_POST['finalizeid'];
    $chemUsed = $_POST['edit_chemBrandUsed'] ?? [];
    $amtUsed = $_POST['edit-amountUsed'] ?? [];
    $notes = $_POST['note'];
    $pwd = $_POST['baPwd'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo "Invalid Transaction ID.";
        exit();
    }

    if (count($chemUsed) !== count($amtUsed)) {
        http_response_code(400);
        echo "Error. The number of chemicals does not match the number of amount.";
        exit();
    }

    if (!validateTech($conn, $pwd)) {
        http_response_code(400);
        echo "Invalid Password.";
        exit();
    }

    $finalize = finalize_trans($conn, $id, $chemUsed, $amtUsed, $_SESSION['branch'], $_SESSION['techId'], $notes, $_SESSION['user_role'], $author);
    if (isset($finalize['error'])) {
        http_response_code(400);
        echo $finalize['error'];
        exit();
    } else if ($finalize) {
        http_response_code(200);
        echo json_encode(['success' => 'Transaction Finalized.']);
        exit();
    } else {
        http_response_code(400);
        echo "Unknown error occured.";
        exit();
    }
}


if (isset($_POST['singledispatch']) && $_POST['singledispatch'] === 'true') {
    $id = $_POST['dispatchid'];
    $chemUsed = $_POST['edit_chemBrandUsed'] ?? [];
    $amtUsed = $_POST['edit-amountUsed'] ?? [];
    $notes = $_POST['note'];
    $pwd = $_POST['baPwd'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo "Invalid Transaction ID.";
        exit();
    }

    if (count($chemUsed) !== count($amtUsed)) {
        http_response_code(400);
        echo "Error. The number of chemicals does not match the number of amount.";
        exit();
    }

    if (!validateTech($conn, $pwd)) {
        http_response_code(400);
        echo "Invalid Password.";
        exit();
    }

    $finalize = dispatch_trans($conn, $id, $chemUsed, $amtUsed, $_SESSION['branch'], $_SESSION['techId'], $notes, $_SESSION['user_role']);
    if (isset($finalize['error'])) {
        http_response_code(400);
        echo $finalize['error'];
        exit();
    } else if ($finalize) {
        http_response_code(200);
        echo json_encode(['success' => 'Transaction Finalized.']);
        exit();
    } else {
        http_response_code(400);
        echo "Unknown error occured.";
        exit();
    }
}