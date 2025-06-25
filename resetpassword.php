<?php
session_start();
if (isset($_SESSION["techId"]) || isset($_SESSION["baID"]) || isset($_SESSION["saID"])) {
    include("includes/functions.inc.php");
    $activeUser = activeUser();
    echo "<h1 class='display-6 mx-auto text-center'>You are already logged in as $activeUser.<br>Redirecting you back to dashboard...</h1>";
    include("footer.php");
    if (isset($_SESSION['saUsn'])) {
        header("Refresh: 3; url=superadmin/index.php");
        exit();
    } elseif (isset($_SESSION['baUsn'])) {
        header("Refresh: 3; url=os/index.php");
        exit();
    } elseif (isset($_SESSION['techUsn'])) {
        header("Refresh: 3; url=technician/index.php");
        exit();
    }
}
