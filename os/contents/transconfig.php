<?php
session_start();
require_once '../../includes/dbh.inc.php';
require_once '../../includes/functions.inc.php';
// var_dump($_POST);
require_once 'arrays.php';

$author = $_SESSION['fname'] . ' ' . $_SESSION['lname'];
$role = "branchadmin";
$user = $_SESSION['baID'];
$branch = $_SESSION['branch'];

$required_os_status = [
    'Pending',
    'Dispatched',
    'Finalizing',
    'Accepted',
    'Completed',
    'Cancelled'
];

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
    $addedBy = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

    $inspection_report = $_POST['inspection_report'];

    if (!is_numeric($inspection_report)) {
        http_response_code(400);
        echo json_encode(['type' => 'error', 'errorMessage' => 'Invalid Inspection Report ID passed.']);
        exit;
    }

    if (!validate_no_numbers($customerName)) {
        http_response_code(400);
        echo "Customer name should only contain alphabets without numbers.";
        exit();
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
        $pexp = date("Y-m-d", strtotime($pexp));
    }

    if (!in_array($status, $required_os_status)) {
        http_response_code(400);
        echo "Invalid status. Please try again.";
        exit();
    }

    if (empty($customerName) || empty($techId) || empty($treatmentDate) || empty($problems) || empty($chemUsed) || empty($status) || empty($t_type) || empty($address)) {
        http_response_code(400);
        echo "All input fields are required.";
        exit();
    }

    // if ($status === 'Dispatched' || $status === 'Finalizing' || $status === 'Completed') {
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
    // }

    // if ($status === 'Dispatched' || $status === 'Finalizing' || $status === 'Completed') {
        if (count($amtUsed) !== count($chemUsed)) {
            http_response_code(400);
            echo "Chemical used and amount used count mismatched. Please refresh the page and try again.";
            exit();
        }
    // }

    if (empty($saPwd)) {
        http_response_code(400);
        echo 'Verification required.';
        exit();
    }

    if (!validateOS($conn, $saPwd)) {
        http_response_code(400);
        echo 'Incorrect Password';
        exit();
    }

    $transaction = newTransaction($conn, $customerName, $address, $techId, $treatmentDate, $treatmentTime, $treatment, $chemUsed, $status, $problems, $package, $t_type, $session, $note, $pstart, $pexp, $addedBy, $amtUsed, $user, $role, $branch, $inspection_report);

    if (isset($transaction['error'])) {
        http_response_code(400);
        echo $transaction['errorMessage'];
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
    $amtUsed = $_POST['edit-amountUsed'] ?? []; //array
    // $status = $_POST['edit-status'];
    $note = $_POST['edit-note'] ?? null;
    $saPwd = $_POST['edit-saPwd'];
    $upby = $_SESSION['fname'] . ' ' . $_SESSION['lname'];


    // $allowedUpdateStatus = ['Pending', 'Accepted', 'Finalizing', 'Cancelled', 'Dispatched', 'Completed'];
    if (!validate_no_numbers($customerName)) {
        http_response_code(400);
        echo "Customer name should only contain alphabets without numbers.";
        exit();
    }

    // add updated by 
    if (empty($customerName) || empty($techId) || empty($treatmentDate) || empty($treatmentTime) || empty($problems) || empty($chemUsed) || empty($ttype) || empty($address)) {
        http_response_code(400);
        echo "All input fields are required.";
        exit();
    }


    // $oStatus = check_status($conn, $transId);
    // if ($status === 'Pending' || $status === 'Cancelled' || $status === 'Accepted') {
    //     $today = date("Y-m-d");
    //     if ($treatmentDate < $today) {
    //         http_response_code(400);
    //         echo "Invalid treatment date.";
    //         exit();
    //     }
    //     if ($status === 'Cancelled' && $oStatus === 'Pending') {
    //         http_response_code(400);
    //         echo "Transaction should be approved first before cancelling treatment date.";
    //         exit();
    //     }
    // }

    // if ($status === 'Dispatched' || $status === 'Finalizing' || $status === 'Completed') {
    //     if ($oStatus === 'Finalizing' && $status === 'Dispatched') {
    //         http_response_code(400);
    //         echo "Error. You cannot go back once finalizing phase is set.";
    //         exit();
    //     }

    //     if ($oStatus === 'Dispatched' && ($status === 'Pending' || $status === 'Accepted')) {
    //         http_response_code(400);
    //         echo "Error. You cannot go back once the technicians are dispatched. Please cancel the transaction first.";
    //         exit();
    //     }

    //     if (empty($amtUsed)) {
    //         http_response_code(400);
    //         echo "Amount Used is required for the current Status.";
    //         exit();
    //     }
    //     for ($i = 0; $i < count($amtUsed); $i++) {
    //         if (empty($amtUsed[$i]) || !is_numeric($amtUsed[$i]) || $amtUsed[$i] <= 0) {
    //             http_response_code(400);
    //             echo "Error. Invalid Amount Used.";
    //             exit();
    //         }
    //     }
    // }

    // if (!in_array($status, $allowedUpdateStatus)) {
    //     http_response_code(400);
    //     echo "Invalid status set. Please try again.";
    //     exit();
    // }

    // no transId
    // if (!$oStatus) {
    //     http_response_code(400);
    //     echo 'Unknown transaction ID.';
    //     exit();
    // } elseif (isset($oStatus['error'])) {
    //     // stmt error
    //     http_response_code(400);
    //     echo $oStatus['error'];
    //     exit();
    // }

    // if (!in_array($oStatus, $allowedUpdateStatus)) {
    //     // if status is not pending or accepted
    //     http_response_code(400);
    //     echo 'Invalid Status. Completed and voided transactions cannot be edited.';
    //     exit();
    // }

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
        if (empty($session) || !is_numeric($session)) {
            http_response_code(400);
            echo "Invalid Session Count.";
            exit();
        }
    } else {
        $package = null;
    }

    $status = check_status($conn, $transId);

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
        'upby' => $upby,
        'branch' => $_SESSION['branch'],
        'userid' => $_SESSION['baID'],
        'role' => 'branchadmin'
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
    } else if ($approve) {
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
        echo 'Input Required.';
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

    $valid_status = ['Cancelled', 'Pending', 'Accepted'];

    $status = check_status($conn, $id);
    if (!in_array($status, $valid_status)) {
        http_response_code(400);
        echo 'Invalid Status.';
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

    // http_response_code(400);
    // echo var_dump($chemUsed);

    if (count($chemUsed) !== count($amtUsed)) {
        http_response_code(400);
        echo "Error. The number of chemicals does not match the number of amount.";
        exit();
    }

    if (!validateOS($conn, $pwd)) {
        http_response_code(400);
        echo "Invalid Password.";
        exit();
    }

    // $final_note = "Reported Item Used";

    $finalize = finalize_trans($conn, $id, $chemUsed, $amtUsed, $_SESSION['branch'], $_SESSION['baID'], $notes, $_SESSION['user_role'], $author);
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

if (isset($_POST['singleconfirm']) && $_POST['singleconfirm'] === 'true') {
    $id = $_POST['completeid'];
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

    if (!validateOS($conn, $pwd)) {
        http_response_code(400);
        echo "Invalid Password.";
        exit();
    }

    $finalize = complete_trans($conn, $id, $chemUsed, $amtUsed, $_SESSION['branch'], $_SESSION['baID'], $notes, $_SESSION['user_role']);
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

    if (!validateOS($conn, $pwd)) {
        http_response_code(400);
        echo "Invalid Password.";
        exit();
    }

    $finalize = dispatch_trans($conn, $id, $chemUsed, $amtUsed, $_SESSION['branch'], $_SESSION['baID'], $notes, $_SESSION['user_role']);
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

$valid_property_types = ['residential', 'commercial'];
$valid_termite_only_answers = ['yes', 'no', 'no_termite'];

if (isset($_POST['new_ir']) && $_POST['new_ir'] === 'true') {
    $property_type = $_POST['property_type'] ?? NULL;
    $customer_name = $_POST['customer_name'];
    $total_area = $_POST['total_area'];
    // $total_area_unit = $_POST['total_area_unit'];
    $total_floors = $_POST['total_floors'];
    $total_rooms = $_POST['total_rooms'];
    $property_loc = $_POST['property_location'];
    $exposed_soil = $_POST['exposed_soil_ans'];
    $infestation_loc = $_POST['infestation_location'];

    $pest_problems = $_POST['pest_problems'] ?? [];


    $existing_pc = $_POST['existing_pc_provider'] ?? NULL;
    $last_treatment = $_POST['existing_provider_last_treatment'] ?? NULL;
    $last_treatment_date = $_POST['last_treatment_date'] ?? NULL;
    $note = $_POST['note'];
    $branch = $_SESSION['branch'];
    $no_treatment_history = isset($_POST['no_treatment_history']);


    $pwd = $_POST['password'];
    if ($property_type === NULL) {
        http_response_code(400);
        echo "Property type required.";
        exit;
    }

    if (!preg_match('/^[a-zA-Z0-9 -]*$/', $customer_name)) {
        http_response_code(400);
        echo "Invalid customer name.";
        exit;
    }

    if ($customer_name == '') {
        http_response_code(400);
        echo "Customer name should not be empty.";
        exit;
    }

    if (!in_array($property_type, $valid_property_types)) {
        http_response_code(400);
        echo "Invalid property type.";
        exit();
    }

    if (!is_numeric($total_area)) {
        http_response_code(400);
        echo "Invalid total area.";
        exit;
    }

    if (!is_numeric($total_floors)) {
        http_response_code(400);
        echo "Invalid total number of floors.";
        exit;
    }

    if ($total_area <= 0.0) {
        http_response_code(400);
        echo "Total floor area must be greater than zero.";
        exit;
    }
    if ($total_floors <= 0) {
        http_response_code(400);
        echo "Total floors must be greater than zero.";
        exit;
    }

    if (!is_numeric($total_rooms)) {
        http_response_code(400);
        echo "Invalid total number of rooms.";
        exit;
    }
    if ($total_rooms <= 0) {
        http_response_code(400);
        echo "Total rooms must be greater than zero.";
        exit;
    }

    if (empty($property_loc)) {
        http_response_code(400);
        echo "Property location is required.";
        exit;
    }

    if (empty($pest_problems)) {
        http_response_code(400);
        echo "Reported pest problem is required.";
        exit;
    }

    foreach ($pest_problems as $problem) {
        if (!is_numeric($problem)) {
            http_response_code(400);
            echo "Invalid pest problem selected.";
            exit;
        }
    }

    if (empty($infestation_loc)) {
        http_response_code(400);
        echo "Infestation location is required  .";
        exit;
    }

    if (!preg_match("/^[0-9a-zA-Z -]*$/", $infestation_loc)) {
        http_response_code(400);
        echo "Invalid infestation location. Special characters are not allowed.";
        exit;
    }

    if (!in_array($exposed_soil, $valid_termite_only_answers)) {
        http_response_code(400);
        echo "Invalid termite only question answer.";
        exit;
    }

    // if no treatment history is checked -> treatment history is valid
    if (!$no_treatment_history) {
        if ($existing_pc === 'no') {
            if (empty($last_treatment)) {
                http_response_code(400);
                echo "Last treatment is required.";
                exit;
            }
            if (empty($last_treatment_date)) {
                http_response_code(400);
                echo "Last treatment date is required.";
                exit;
            }
            $existing_pc = 0;
        } else if ($existing_pc === 'yes') {
            $last_treatment = NULL;
            $last_treatment_date = NULL;
            $existing_pc = 1;
        } else {
            http_response_code(400);
            echo "An error occured when recording the existing provider answer. Please refresh the page and try again.";
            exit;
        }
    }

    if (!is_numeric($branch)) {
        http_response_code(400);
        echo "Invalid branch selected. Please try again.";
        exit;
    }

    if (!validateOS($conn, $pwd)) {
        http_response_code(400);
        echo "Invalid password.";
        exit;
    }

    $add = add_inspection_report($conn, $property_type, $total_area, "sqm", $total_floors, $total_rooms, $property_loc, $exposed_soil, $infestation_loc, $pest_problems, $existing_pc, $last_treatment, $last_treatment_date, $note, $customer_name, $branch, $author);

    if (isset($add['error'])) {
        http_response_code(400);
        echo $add['error'];
    } else if ($add) {
        http_response_code(200);
        echo json_encode(['success' => 'Inspection Report Added.']);
    } else {
        http_response_code(400);
        echo "An unknown error occured. Please try again later.";
    }
    exit;
}

if (isset($_POST['modify_ir']) && $_POST['modify_ir'] === 'true') {
    $id = $_POST['ir_id'];
    $name = $_POST['customer_name'];
    $property_type = $_POST['property_type'];
    $total_floor_area = $_POST['total_floor_area'];
    $total_floors = $_POST['total_floors'];
    $total_rooms = $_POST['total_rooms'];
    $location = $_POST['location'];
    $pest_problems = $_POST['pest_problems'] ?? [];
    $location_seen = $_POST['location_seen'];
    $existing_pc = $_POST['existing_pc'];
    $exposed_soil = $_POST['exposed_soil'];

    $no_treatment_history = isset($_POST['no_treatment_history']);
    $latest_treatment = $_POST['latest_treatment'] ?? NULL; // last treatment type
    $last_treatment_date = $_POST['last_treatment_date'] ?? NULL;

    $note = $_POST['note'];
    $password = $_POST['password'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo "Invalid report ID. Please try again later.";
        exit;
    }
    if (!preg_match("/^[0-9a-zA-Z -]*$/", $name)) {
        http_response_code(400);
        echo "Invalid customer name.";
        exit;
    }
    if (!in_array($property_type, $valid_property_types)) {
        http_response_code(400);
        echo "Invalid property type.";
        exit;
    }
    if (!is_numeric($total_floor_area)) {
        http_response_code(400);
        echo "Invalid total floor area.";
        exit;
    }
    if ($total_floor_area <= 0.0) {
        http_response_code(400);
        echo "Total floor area must be greater than zero.";
        exit;
    }
    if (!is_numeric($total_floors)) {
        http_response_code(400);
        echo "Invalid total floors.";
        exit;
    }
    if ($total_floors <= 0) {
        http_response_code(400);
        echo "Total floors must be greater than zero.";
        exit;
    }
    if (!is_numeric($total_rooms)) {
        http_response_code(400);
        echo "Invalid total rooms.";
        exit;
    }
    if ($total_rooms <= 0) {
        http_response_code(400);
        echo "Total rooms must be greater than zero.";
        exit;
    }
    if (empty($location)) {
        http_response_code(400);
        echo "Property location is required.";
        exit;
    }
    if (empty($pest_problems)) {
        http_response_code(400);
        echo "Reported pest problem is required.";
        exit;
    }
    foreach ($pest_problems as $problem) {
        if (!is_numeric($problem)) {
            http_response_code(400);
            echo "Invalid pest problem selected.";
            exit;
        }
    }
    if (!in_array($exposed_soil, $valid_termite_only_answers)) {
        http_response_code(400);
        echo "Invalid answer to exposed soil outside property.";
        exit;
    }
    if (empty($location_seen)) {
        http_response_code(400);
        echo "Infestation location is required.";
        exit;
    }
    if (!preg_match("/^[0-9a-zA-Z (),.-]*$/", $location_seen)) {
        http_response_code(400);
        echo "Invalid infestation location. Special characters are not allowed.";
        exit;
    }
    if ($existing_pc !== 'yes' && $existing_pc !== 'no') {
        http_response_code(400);
        echo "Invalid existing pest control provider answer.";
        exit;
    }
    if (!$no_treatment_history) {
        if ($existing_pc === 'no') {
            if (empty($latest_treatment)) {
                http_response_code(400);
                echo "Last treatment performed is required.";
                exit;
            }
            if (empty($last_treatment_date)) {
                http_response_code(400);
                echo "Last treatment date is required.";
                exit;
            }
            $existing_pc = 0;
        } else if ($existing_pc === 'yes') {
            $latest_treatment = NULL;
            $last_treatment_date = NULL;
            $existing_pc = 1;
        } else {
            http_response_code(400);
            echo "An error occured when recording the existing provider answer. Please refresh the page and try again.";
            exit;
        }
    }



    if (!validateOS($conn, $password)) {
        http_response_code(400);
        echo "Invalid password.";
        exit;
    }

    $update = modify_ir($conn, $id, $name, $property_type, $total_floor_area, $total_floors, $total_rooms, $location, $pest_problems, $location_seen, $existing_pc, $exposed_soil, $latest_treatment, $last_treatment_date, $note, $author);
    if (isset($update['error'])) {
        http_response_code(400);
        echo $update['error'];
        exit();
    } else if ($update) {
        http_response_code(200);
        echo json_encode(['success' => 'Inspection Report Updated.']);
        exit();
    } else {
        http_response_code(400);
        echo "An unknown error occured. Please try again later.";
        exit();
    }
}