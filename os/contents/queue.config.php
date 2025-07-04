<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_POST['resched']) && $_POST['resched'] === 'true') {
    $id = $_POST['reschedid'];
    $newdate = $_POST['reschedDate'];
    $newtime = $_POST['reschedTime'];

    if (empty($id)) {
        http_response_code(400);
        echo 'No ID found. Contact administration.';
        exit();
    }

    if (empty($newdate)) {
        http_response_code(400);
        echo 'Please pick a date.';
        exit();
    }

    if (empty($newtime)) {
        http_response_code(400);
        echo 'Please pick a time.';
        exit();
    }

    $sql = "UPDATE transactions SET treatment_date = ?, transaction_time = ? WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo 'stmt error' . mysqli_stmt_error($stmt);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'ssi', $newdate, $newtime, $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        http_response_code(200);
        echo json_encode(['success' => 'Transaction Updated.']);
        exit();
    } else {
        http_response_code(400);
        echo 'Transaction Update Fail.';
        exit();
    }
}

if (isset($_POST['cancel']) && $_POST['cancel'] === 'true') {
    $id = $_POST['cancelIdName'];
    $pwd = $_POST['cancelPass'];

    if (empty($id)) {
        http_response_code(400);
        echo "ID input empty. Please contact administration.";
        exit();
    }

    if (empty($pwd)) {
        http_response_code(400);
        echo "Password is empty.";
        exit();
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo "Wrong Password.";
        exit();
    }

    $sql = "UPDATE transactions SET transaction_status = 'Cancelled' WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "stmt failed. Please contact administration.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        http_response_code(200);
        echo json_encode(['success' => 'Transaction Cancelled.']);
        exit();
    } else {
        http_response_code(400);
        echo "Update failed. Please contact administration.";
        exit();
    }
}