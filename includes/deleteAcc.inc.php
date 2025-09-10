<?php
session_start();
require_once "dbh.inc.php";
require_once "functions.inc.php";

if (isset($_POST['deleteTech']) && $_POST['deleteTech'] === 'true') {
    $techId = $_POST['id'];
    $saId = $_SESSION['saID'];
    $confirmationPwd = htmlspecialchars($_POST['saPwd']);

    if (!is_numeric($techId)) {
        http_response_code(400);
        echo "Invalid account ID was passed. Refresh page and try again.";
        // echo var_dump($techId);
        exit();
    }

    if (!is_numeric($saId)) {
        http_response_code(400);
        echo "Invalid account session ID. Please try again later.";
        exit();
    }

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

    $delete = deleteTechAccount($conn, $techId);

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

if (isset($_POST['deleteOS']) && $_POST['deleteOS'] === 'true') {
    $baId = $_POST['id'];
    $saId = $_SESSION['saID'];
    $confirmationPwd = htmlspecialchars($_POST['saPwd']);

    if (!is_numeric($baId)) {
        http_response_code(400);
        echo "Invalid account ID was passed. Refresh page and try again.";
        exit();
    }

    if (!is_numeric($saId)) {
        http_response_code(400);
        echo "Invalid account session ID. Please try again later.";
        exit();
    }

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