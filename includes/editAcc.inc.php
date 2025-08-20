<?php
session_start();
require_once "dbh.inc.php";
require_once "functions.inc.php";

if (isset($_POST["configure_tech"]) && $_POST['configure_tech'] === 'true') {
    $saID = $_SESSION['saID'];
    $id = $_POST["id"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $usn = $_POST["usn"];
    $email = $_POST["email"];
    $pwd = $_POST["pass"];
    $pwdRepeat = $_POST["pwdRepeat"];
    $saPwd = $_POST["saPwd"];
    $empId = $_POST["empId"];
    $address = $_POST["address"];
    $birthdate = $_POST['birthdate'];
    $contactNo = $_POST["contactNo"];


    if (emptyInputEdit($fname, $lname, $usn, $email) !== false) {
        http_response_code(400);
        echo "Incomplete Input.";
        exit();
    }

    if (invalidFirstName($fname) !== false) {
        http_response_code(400);
        echo "Invalid First Name.";
        exit();
    }

    if (invalidLastName($lname) !== false) {
        http_response_code(400);
        echo "Invalid Last Name.";
        exit();
    }

    if (invalidUsername($usn) !== false) {
        http_response_code(400);
        echo "Invalid Username.";
        exit();
    }

    if (invalidEmail($email) !== false) {
        http_response_code(400);
        echo "Invalid Email.";
        exit();
    }

    if (invalidUsername($usn) !== false) {
        http_response_code(400);
        echo "Invalid Username.";
        exit();
    }

    if (invalidEmail($email) !== false) {
        http_response_code(400);
        echo "Invalid Email.";
        exit();
    }

    if (empty($saPwd)) {
        header("location: ../superadmin/tech.acc.php?error=emptymanagerpassword");
        http_response_code(400);
        echo "Empty Manager Password.";
        exit();
    }

    if (checkExistingAccs($conn, $usn, $email, $id) !== false) {
        http_response_code(400);
        echo "User Already Exists.";
        exit();
    }
    if (!empty($pwd)) {
        if (pwdMatch($pwd, $pwdRepeat) !== false) {
            http_response_code(400);
            echo "Unmatched Password.";
            exit();
        }
    }
    if (employeeIdCheck($conn, $empId, $id) !== false) {
        http_response_code(400);
        echo "Employee ID already exists.";
        exit();
    }

    if (!validate($conn, $saPwd)) {
        http_response_code(400);
        echo "Wrong password.";
        exit();
    }
    $edit = editTechAccount($conn, $id, $fname, $lname, $usn, $email, $pwd, $contactNo, $address, $birthdate, $empId);
    if (isset($edit['error'])) {
        http_response_code(400);
        echo $edit['error'];
        exit();
    } else if ($edit) {
        http_response_code(200);
        echo json_encode(['success' => "Account updated successfully."]);
        exit();
    } else {
        http_response_code(400);
        echo "Unknown error occurred." . $edit;
        exit();
    }
}

if (isset($_POST["configure_os"]) && $_POST['configure_os'] === 'true') {
    $saID = $_SESSION['saID'];
    $id = $_POST["id"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $usn = $_POST["usn"];
    $email = $_POST["email"];
    $pwd = $_POST["pass"];
    $pwdRepeat = $_POST["pwdRepeat"];
    $saPwd = $_POST["saPwd"];
    $empId = $_POST["empId"];
    $address = $_POST["address"];
    $birthdate = $_POST['birthdate'];
    $contactNo = $_POST["contactNo"];

    if (emptyInputEdit($fname, $lname, $usn, $email) !== false) {
        http_response_code(400);
        echo "Incomplete Input.";
        exit();
    }

    if (invalidFirstName($fname) !== false) {
        http_response_code(400);
        echo "Invalid First Name.";
        exit();
    }

    if (invalidLastName($lname) !== false) {
        http_response_code(400);
        echo "Invalid Last Name.";
        exit();
    }

    if (invalidUsername($usn) !== false) {
        http_response_code(400);
        echo "Invalid Username.";
        exit();
    }

    if (invalidEmail($email) !== false) {
        http_response_code(400);
        echo "Invalid Email.";
        exit();
    }

    if (empty($saPwd)) {
        http_response_code(400);
        echo "Empty Manager Password.";
        exit();
    }

    if (checkExistingAccs($conn, $usn, $email, $id) !== false) {
        http_response_code(400);
        echo "User Already Exists.";
        exit();
    }
    if (!empty($pwd)) {
        if (pwdMatch($pwd, $pwdRepeat) !== false) {
            http_response_code(400);
            echo "Unmatched Password.";
            exit();
        }
    }
    if (employeeIdCheck($conn, $empId, $id) !== false) {
        http_response_code(400);
        echo "Employee ID already exists.";
        exit();
    }

    if (!validate($conn, $saPwd)) {
        http_response_code(400);
        echo "Wrong Password.";
        exit();
    }

    $edit = editOSAccount($conn, $id, $fname, $lname, $usn, $email, $pwd, $contactNo, $address, $birthdate, $empId);
    if (isset($edit['error'])) {
        http_response_code(400);
        echo $edit['error'];
        exit();
    } else if ($edit) {
        http_response_code(200);
        echo json_encode(['success' => "Account updated successfully."]);
        exit();
    } else {
        http_response_code(400);
        echo "Unknown error occurred." . $edit;
        exit();
    }
}
