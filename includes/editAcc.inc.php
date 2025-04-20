<?php
session_start();
require_once "dbh.inc.php";
require_once "functions.inc.php";

if (isset($_POST["submit-tech-edit"])) {
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
    // $saPwdRepeat = htmlspecialchars($_POST["saPwdRepeat"]);



    if (emptyInputEdit($fname, $lname, $usn, $email) !== false) {
        header("location: ../superadmin/tech.acc.php?error=incompleteinput");
        exit();
    }

    if (invalidFirstName($fname) !== false) {
        header("location: ../superadmin/tech.acc.php?error=invalidfirstname");
        exit();
    }

    if (invalidLastName($lname) !== false) {
        header("location: ../superadmin/tech.acc.php?error=invalidlastname");
        exit();
    }

    if (invalidUsername($usn) !== false) {
        header("location: ../superadmin/tech.acc.php?error=invalidusername");
        exit();
    }

    if (invalidEmail($email) !== false) {
        header("location: ../superadmin/tech.acc.php?error=invalidemail");
        exit();
    }

    if (empty($saPwd)) {
        header("location: ../superadmin/tech.acc.php?error=emptymanagerpassword");
        exit();
    }

    if (checkExistingAccs($conn, $usn, $email, $id) !== false) {
        header("location: ../superadmin/tech.acc.php?error=useralreadyexist");
        exit();
    }
    if (!empty($pwd)) {
        if (pwdMatch($pwd, $pwdRepeat) !== false) {
            header("location: ../superadmin/tech.acc.php?error=unmatchedpassword");
            exit();
        }
    }
    if (employeeIdCheck($conn, $empId, $id) !== false) {
        header("location: ../superadmin/tech.acc.php?error=existingemployeeid");
        exit();
    }

    if (validate($conn, $saPwd) !== false) {
        editTechAccount($conn, $id, $fname, $lname, $usn, $email, $pwd, $contactNo, $address, $birthdate, $empId);
    } else {
        header("location: ../superadmin/tech.acc.php?error=invalidmanagerpassword");
    }
}

if (isset($_POST["submit-os-edit"])) {
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
        header("location: ../superadmin/os.acc.php?error=incompleteinput");
        exit();
    }

    if (invalidFirstName($fname) !== false) {
        header("location: ../superadmin/os.acc.php?error=invalidfirstname");
        exit();
    }

    if (invalidLastName($lname) !== false) {
        header("location: ../superadmin/os.acc.php?error=invalidlastname");
        exit();
    }

    if (invalidUsername($usn) !== false) {
        header("location: ../superadmin/os.acc.php?error=invalidusername");
        exit();
    }

    if (invalidEmail($email) !== false) {
        header("location: ../superadmin/os.acc.php?error=invalidemail");
        exit();
    }

    if (empty($saPwd)) {
        header("location: ../superadmin/os.acc.php?error=emptymanagerpassword");
        exit();
    }

    if (checkExistingAccs($conn, $usn, $email, $id) !== false) {
        header("location: ../superadmin/os.acc.php?error=useralreadyexist");
        exit();
    }
    if (!empty($pwd)) {
        if (pwdMatch($pwd, $pwdRepeat) !== false) {
            header("location: ../superadmin/os.acc.php?error=unmatchedpassword");
            exit();
        }
    }
    if (employeeIdCheck($conn, $empId, $id) !== false) {
        header("location: ../superadmin/os.acc.php?error=existingemployeeid");
        exit();
    }

    if (validate($conn, $saPwd) !== false) {
        editOSAccount($conn, $id, $fname, $lname, $usn, $email, $pwd, $contactNo, $address, $birthdate, $empId);
    } else {
        header("location: ../superadmin/os.acc.php?error=invalidmanagerpassword");
    }
}
