<?php

if (isset($_POST["submitCreateAcc"])) {

    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdRepeat"];
    $contactNo = $_POST["contactNo"];
    $empId = $_POST["empId"];
    $address = $_POST["address"];
    $birthdate = $_POST['birthdate'];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputSignup($firstName, $lastName, $username, $email, $pwd, $pwdRepeat) !== false) {
        header("location: ../superadmin/create.os.php?error=emptyinput");
        exit();
    }
    if (invalidFirstName($firstName) !== false) {
        header("location: ../superadmin/create.os.php?error=invalidfirstname");
        exit();
    }
    if (invalidLastName($lastName) !== false) {
        header("location: ../superadmin/create.os.php?error=invalidlastname");
        exit();
    }
    if (invalidUsername($username) !== false) {
        header("location: ../superadmin/create.os.php?error=invalidusername");
        exit();
    }
    if (invalidEmail($email) !== false) {
        header("location: ../superadmin/create.os.php?error=invalidemail");
        exit();
    }
    if (pwdMatch($pwd, $pwdRepeat) !== false) {
        header("location: ../superadmin/create.os.php?error=passwordsdontmatch");
        exit();
    }
    if (multiUserExists($conn, $username, $email) !== false) {
        header("location: ../superadmin/create.os.php?error=useralreadyexist");
        exit();
    }
    if(employeeIdCheck($conn, $empId, $id) !== false){
        header("location: ../superadmin/create.os.php?error=existingemployeeid");
        exit();
    }

    createOpSupAccount($conn, $firstName, $lastName, $username, $email, $pwd, $contactNo, $address, $empId, $birthdate);
} else {
    header("location: ../superadmin/create.os.php");
    exit();
}