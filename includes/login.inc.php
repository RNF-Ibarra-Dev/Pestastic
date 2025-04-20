<?php

if (isset($_POST["loginSubmit"])) {

    $uidEmail = htmlspecialchars($_POST["userEmail"]);
    $pwd = htmlspecialchars($_POST["pwd"]);
    
    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    if (emptyInputLogin($uidEmail, $pwd) !== false) {
        header("location: ../login.php?error=emptyinput");
        exit();
    }

    // search log in credentials and role

    // loginUser($conn, $uidEmail, $pwd);
    
    loginMultiUser($conn, $uidEmail, $pwd);

} else {
    header("location: ../login.php");
    exit();
}