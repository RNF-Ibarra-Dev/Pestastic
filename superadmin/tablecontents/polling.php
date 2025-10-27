<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");

// $last_seen_id = isset($_GET['last_seen_id']) ? (int)$_GET['last_seen_id'] : 0;
$last_seen_id = $_SESSION['last_seen_transaction_id'] ?? 0;

$sql = "SELECT MAX(id) as latest_id FROM transactions";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$latest_id = $row['latest_id'] ?? 0;
// $latest_id = 1;

if ($last_seen_id == 0) {
    $last_seen_id = $latest_id;
    echo json_encode(['new' => false, 'latest_id' => $last_seen_id . $latest_id]);
    $_SESSION['last_seen_transaction_id'] = $latest_id;
    mysqli_close($conn);
    exit;
}

if ($latest_id > $last_seen_id) {
    echo json_encode(['new' => true, 'latest_id' => $latest_id]);
    $last_seen_id = $latest_id;
    $_SESSION['last_seen_transaction_id'] = $latest_id;
} else {
    echo json_encode(['new' => false, 'latest_id' => $last_seen_id]);
}

// $latest_id = rand(1, 100); // fake latest transaction id
// $should_trigger = (rand(0, 1) === 1); // randomly decide true/false

// if ($should_trigger) {
//     echo json_encode(['new' => true, 'latest_id' => $latest_id]);
//     $_SESSION['last_seen_transaction_id'] = $latest_id;
// } else {
//     echo json_encode(['new' => false, 'latest_id' => $last_seen_id]);
// }

exit;
$td_sql = "SELECT * FROM transactions WHERE id = $latest_id;";
$td_res = mysqli_query($conn, $td_sql);
$trans_details = [];
if ($row = mysqli_fetch_assoc($td_res)) {
    $trans_details = $row;
}
$date_set = date('F j, Y', strtotime($trans_details['treatment_date']));
$time_set = date('H:i A', strtotime($trans_details['transaction_time']));

$package_details = '';
if($trans_details['package_id'] !== NULL){
    $package = get_package_details($conn, $trans_details['package_id']);
    $package_details = <<<END
            <strong>Package ID:</strong> {$trans_details['package_id']} <br>
            <strong>Package details:</strong> {$package['name']} ({$package['session_count']} sessions | {$package['year_warranty']} Year Warranty) <br>
            <strong>Session No.:</strong> {$trans_details['session_no']} <br>
    END;
}
$branch = get_branch_details($conn, $trans_details['branch']);
$email_message = <<<END
        <div style="text-align: center;">
            <img src="https://pestastic-inventory.site/img/pestastic.logo.jpg" alt="logo" style="width: 12rem !important">
        </div>
        <br>
        <b>Good day!</b>
        <p>This is to inform you that a new transaction was made. The new transaction ID is $latest_id.</p>

        <br>
        Brief Transaction Details:<br>
            <strong>Customer:</strong> {$trans_details['customer_name']} <br>
            <strong>Treatment date set:</strong> $date_set $time_set <br>
            <strong>Treatment type:</strong> {$trans_details['treatment_type']} <br>
            <strong>Branch:</strong> {$branch['name']} ({$branch['location']}) <br>
            $package_details <br>

        <p><i>Note: <br>   
        This is an automated email. Please do not reply.</i></p>

        <img src="https://pestastic-inventory.site/img/logo.png" alt="logo" style="width: 4rem !important"><br>
        Thank you,<br>
        <b>Pestastic Inventory</b>
END;
$email_manager = email_manager_alert($conn, $email_message, "New transaction was added");
if (isset($email_manager['error'])) {
    http_response_code(400);
    echo "Failed to email managers.";
    exit;
}
mysqli_close($conn);