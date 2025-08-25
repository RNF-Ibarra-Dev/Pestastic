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
    $amtUsed = $_POST['add-amountUsed'] ?? [];
    $note = $_POST['add-notes'];
    $status = $_POST['add-status'];
    $session = $_POST['add-session'] ?? null;
    $saPwd = $_POST['saPwd'];

    // add created by
    $addedBy = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];

    if ($package === 'none' || $package === NULL) {
        if (empty($treatment)) {
            http_response_code(400);
            echo json_encode(['type' => 'emptyinput', 'errorMessage' => "Missing Treatment Assigned."]);
            exit();
        }
        $package = NULL;
    } else {
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
        if (empty($session)) {
            http_response_code(400);
            echo json_encode(['type' => 'emptyinput', 'errorMessage' => "Session count is required."]);
            exit();
        }
        if (empty($pstart) || empty($pexp)) {
            http_response_code(400);
            echo json_encode(['type' => 'emptyinput', 'errorMessage' => "Missing Package Warranty Start."]);
            exit();
        }
    }

    if (!in_array($status, $required_os_status)) {
        http_response_code(400);
        echo "Invalid status access. Please try again.";
        exit();
    }

    if (empty($customerName) || empty($techId) || empty($treatmentDate) || empty($problems) || empty($chemUsed) || empty($status) || empty($t_type) || empty($address)) {
        http_response_code(400);
        echo "All input fields are required.";
        exit();
    }


    if ($status === 'Dispatched' || $status === 'Finalizing' || $status === 'Completed') {
        if (empty($amtUsed)) {
            http_response_code(400);
            echo "Amount Used is required for the current Status.";
            exit();
        }
        for ($i = 0; $i < count($amtUsed); $i++) {
            if (empty($amtUsed[$i]) || !is_numeric($amtUsed[$i]) || $amtUsed[$i] <= 0) {
                http_response_code(400);
                echo "Error. Invalid Amount Used.";
                exit();
            }
        }
    }

    if ($status === 'Dispatched' || $status === 'Finalizing' || $status === 'Completed') {
        if (count($amtUsed) !== count($chemUsed)) {
            http_response_code(400);
            echo "Chemical used and amount used count mismatched. Please refresh the page and try again.";
            exit();
        }
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