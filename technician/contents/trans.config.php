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

    $inspection_report = $_POST['inspection_report'];

    if (!is_numeric($inspection_report)) {
        http_response_code(400);
        echo json_encode(['type' => 'error', 'errorMessage' => 'Invalid Inspection Report ID passed.']);
        exit;
    }
    if (!validate_no_numbers($customerName)) {
        http_response_code(400);
        echo json_encode(['type' => 'error', 'errorMessage' => "Customer name should only contain alphabets without numbers."]);
        exit();
    }

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

    $transaction = newTransaction($conn, $customerName, $address, $techId, $treatmentDate, $treatmentTime, $treatment, $chemUsed, $status, $problems, $package, $t_type, $session, $note, $pstart, $pexp, $addedBy, $amtUsed, $user, $role, $branch, $inspection_report);

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
    $branch = $_POST['branch'];
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

    if (!validateTech($conn, $pwd)) {
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



    if (!validateTech($conn, $password)) {
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