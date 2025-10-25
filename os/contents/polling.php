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

if($last_seen_id == 0){
    $last_seen_id = $latest_id;
    echo json_encode(['new' => false, 'latest_id' => $last_seen_id]);
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

mysqli_close($conn);