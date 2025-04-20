

<?php
include("header.php");

// session_start();

// if(session_status() === PHP_SESSION_ACTIVE){
//     header("location: index.php");
// }
if (isset($_POST["loginSubmit"])) {

    require_once("includes/dbh.inc.php");
    require_once("includes/functions.inc.php");

    $usn = $_POST["usn"];
    $email = $_POST["email"];

    $userExists = multiUserExists($conn, $usn, $email);
    // $exists = userExists($conn, $usnEmail, $pass);

    if (multiUserExists($conn, $usn, $email) !== false) {
        echo"user exists";
        print_r($userExists);
        if (isset($userExists['technicianId'])){
            echo 'technician';
        }
        elseif (isset($userExists['saID'])){
            echo 'Super Admin';
            print_r($userExists['saID']);
        }
        elseif (isset($userExists['baID'])){
            echo 'Branch Admin';
            var_dump($userExists['baId']);

        }

    } else {
        echo "doesn't exist";
    }

//    if ($userExists === true) {
//     echo "user exists. Function multiUserExists working.";
//    }
//    elseif ($userExists === false) {
//     echo "does not exist. Function not working";
//    }
//    elseif ($exists) {
//     echo "userExist function working.";
//    }

}

?>

<body class="d-flex align-items-center py-4 bg-body-tertiary">

    <main class="form-signin w-100 m-auto rounded-3" style="background-color: gainsboro">
        <form action="logintest.php" method="post" class="d-flex flex-column">
            <img src="img/pestastic.logo.jpg" alt width="75" height="75" class="mb-4 align-self-center rounded">
            <h1 class="h3 mb-3 fw-normal">Log in</h1>
            <div class="form-floating mb-2">
                <input type="text" name="usn" class="form-control" id="floatingInput"
                    placeholder="Username">
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" name="email" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">email</label>
            </div>
            <!-- <div class="form-check text-start my-3"></div> -->
            <button name="loginSubmit" class="btn btn-primary w-100 py-2" type="submit">Log in</button>
            <p class="mt-2 text-center mb-3 text-body-secondary">No account? <a href="createAccount.php"
                    style="text-design: none"> Create one.</a></p>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "emptyinput") {
                    echo "<p class='text-center'>Fill in all fields!</p>";
                } elseif ($_GET["error"] == "wronglogin") {
                    echo "<p class='text-center'>User does not exist. Please try again.</p>";
                } elseif ($_GET["error"] == "wrongpassword") {
                    echo "<p class='text-center'>Wrong password.</p>";
                }
            }
            ?>
        </form>
    </main>


    <?php

    include("footer.php");

    ?>