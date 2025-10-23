<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");

$last_seen_id = $_SESSION['last_seen_transaction_id'] ?? 0;
$tech_id = $_GET['tech_id'] ?? NULL;

if (!is_numeric($tech_id)) {
    http_response_code(400);
    echo "invalid technician ID.";
    exit;
}

$sql = "SELECT MAX(trans_id) as latest_id FROM transaction_technicians";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$latest_id = (int)($row['latest_id'] ?? 0);

if ($last_seen_id === 0) {
    $_SESSION['last_seen_transaction_id'] = $latest_id;
    echo json_encode(['new' => false, 'latest_id' => $latest_id]);
    mysqli_close($conn);
    exit;
}

$assigned_techs = [];
$last_seen_id = (int)$last_seen_id;
if ($latest_id > $last_seen_id) {

    $tech_sql = "SELECT tech_id FROM transaction_technicians WHERE trans_id = $latest_id;";
    $res = mysqli_query($conn, $tech_sql);
    while ($row = mysqli_fetch_assoc($res)) {
        $assigned_techs[] = $row['tech_id'];
    }

    if (in_array($tech_id, $assigned_techs)) {
        echo json_encode(['new' => true, 'latest_id' => $latest_id]);
    } else {
        echo json_encode(['new' => false, 'latest_id' => $last_seen_id]);
    }
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

mysqli_close($conn);
exit;