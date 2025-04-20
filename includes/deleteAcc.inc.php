<?php
session_start();   
require_once "dbh.inc.php";
require_once "functions.inc.php";

if(isset($_POST['deleteTech'])){
    $techId = $_POST['id'];
    $saId = $_SESSION['saID'];
    $confirmationPwd = htmlspecialchars($_POST['saPwd']);

    if(empty($confirmationPwd)){
        header("location: ../superadmin/tech.acc.php?error=emptymanagerpassword");
        exit();
    }

    if (validate($conn, $confirmationPwd) !== false) {
        // editAccount($conn, $id, $fname, $lname, $usn, $email, $pwd);
        deleteTechAccount($conn, $techId);
    } else{
        header("location: ../superadmin/tech.acc.php?error=invalidmanagerpassword");
    }
}

if(isset($_POST['deleteOS'])){
    $baId = $_POST['id'];
    $saId = $_SESSION['saID'];
    $confirmationPwd = htmlspecialchars($_POST['saPwd']);

    if(empty($confirmationPwd)){
        header("location: ../superadmin/os.acc.php?error=emptymanagerpassword");
        exit();
    }

    if (validate($conn, $confirmationPwd) !== false) {
        deleteOSAccount($conn, $baId);
    } else{
        header("location: ../superadmin/os.acc.php?error=invalidmanagerpassword");
    }
}