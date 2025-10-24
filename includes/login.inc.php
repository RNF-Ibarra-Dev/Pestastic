<?php

if (isset($_POST["loginSubmit"])) {

    $uidEmail = $_POST["userEmail"];
    $pwd = $_POST["pwd"];
    
    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    if (emptyInputLogin($uidEmail, $pwd) !== false) {
        header("location: ../login.php?error=emptyinput");
        exit();
    }

    loginMultiUser($conn, $uidEmail, $pwd);

} else {
    header("location: ../login.php");
    exit();
}