<!DOCTYPE html>
<html lang="en">

<?php

session_start();

include("header.php");
// check if session is set 
if (!isset($_SESSION["techId"]) && !isset($_SESSION['baID']) && !isset($_SESSION['saID'])) {
   

    ?>

    <body class="d-flex align-items-center py-4 bg-body-tertiary bg-official-login">

        <main class="w-25 p-4 mx-auto rounded-3 bg-light bg-opacity-25 ">
            <form action="includes/login.inc.php" method="post" class="d-flex flex-column">
                <img src="img/clearlogo.png" alt width="75" height="75" class="mb-1 align-self-center rounded">
                <h1 class="h3 mb-4 fw-normal text-light text-center">Inventory Log in</h1>
                <div class="form-floating form-custom mb-2">
                    <input type="text" name="userEmail" class="form-control" id="floatingInput"
                        placeholder="Username/Email">
                    <label for="floatingInput">Username/Email</label>
                </div>
                <div class="form-floating form-custom mb-2">
                    <input type="password" name="pwd" class="form-control" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>
                <!-- <div class="form-check text-start my-3"></div> -->
                <button name="loginSubmit" class="btn btn-form-submit w-100 py-2 text-dark bg-light bg-opacity-75" type="submit"><i class="bi bi-box-arrow-in-right login-anim me-1"></i> Log in</button>
                <p class="mt-2 text-center mb-3 text-light">No account? Please contact the administration.</p>
                <?php
                if (isset($_GET["error"])) {
                    if ($_GET["error"] == "emptyinput") {
                        echo "<p class='text-center alert alert-warning'>Fill in all fields!</p>";
                    } elseif ($_GET["error"] == "wronglogin") {
                        echo "<p class='text-center alert alert-warning'>User does not exist. Please try again.</p>";
                    } elseif ($_GET["error"] == "wrongpassword") {
                        echo "<p class='text-center alert alert-warning'>Wrong password.</p>";
                    }
                }
                ?>
            </form>
        </main>


        <?php
    // when active, the page redirects users to their appropriate indexes sgjlfkg.
} elseif (isset($_SESSION["techId"]) || isset($_SESSION["baID"]) || isset($_SESSION["saID"])){
    include("includes/functions.inc.php");
    $activeUser = activeUser();
    echo "<h1 class='display-6 mx-auto text-center'>You are already logged in as $activeUser.<br>Redirecting you back to dashboard...</h1>";
    include("footer.php");
    if(isset($_SESSION['saUsn'])){
        header("Refresh: 3; url=superadmin/index.php");
        exit();
    }
    elseif(isset($_SESSION['baUsn'])){
        header("Refresh: 3; url=os/index.php");
        exit();
    }
    elseif(isset($_SESSION['techUsn'])  ){
        header("Refresh: 3; url=technician/index.php");
        exit();
    }

}
include("footer.php");


?>