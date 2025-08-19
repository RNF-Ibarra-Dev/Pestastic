<?php
session_start();
require_once "dbh.inc.php";
require_once "functions.inc.php";

if (isset($_POST['deleteTech'])) {
    $techId = $_POST['id'];
    $saId = $_SESSION['saID'];
    $confirmationPwd = htmlspecialchars($_POST['saPwd']);

    if (empty($confirmationPwd)) {
        header("location: ../superadmin/tech.acc.php?error=emptymanagerpassword");
        exit();
    }

    if (validate($conn, $confirmationPwd) !== false) {
        // editAccount($conn, $id, $fname, $lname, $usn, $email, $pwd);
        deleteTechAccount($conn, $techId);
    } else {
        header("location: ../superadmin/tech.acc.php?error=invalidmanagerpassword");
    }
}

if (isset($_POST['deleteOS']) && $_POST['deleteOS'] === 'true') {
    $baId = $_POST['id'];
    $saId = $_SESSION['saID'];
    $confirmationPwd = htmlspecialchars($_POST['saPwd']);

    if (empty($confirmationPwd)) {
        http_response_code(400);
        echo "Password should not be empty";
        exit();
    }

    if (!validate($conn, $confirmationPwd)) {
        http_response_code(403);
        echo "Invalid password";
        exit();
    }

    $delete = deleteOSAccount($conn, $baId);
    if (isset($delete['error'])) {
        http_response_code(400);
        echo $delete['error'];
    } else if ($delete) {
        http_response_code(200);
        echo json_encode(['success' => 'Account deleted successfully.']);
    } else {
        http_response_code(400);
        echo "Unknown error occurred while deleting the account.";
    }
    exit();
}