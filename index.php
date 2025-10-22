<?php
include("header.php");

// ini_set("session.use_only_cookies", "1");
// ini_set("session.use_strict_mode", "1");

// session_set_cookie_params([
//     'lifetime' => 1800,
//     // use website url when up
//     'domain' => 'pestastic-inventory.site',
//     'path' => '/',
//     'secure' => true,
//     'httponly' => true,
// ]);
session_start();
?>

<body class="d-flex align-items-center py-4 bg-body-tertiary bg-official-login">

    <div class="container-fluid d-flex flex-column text-light bg-light bg-opacity-25 w-50 p-3 pt-0 rounded-5">
        <img src="img/logo.svg" class="my-4 align-self-center rounded" style="width: 5rem !important">
        <h1 class="text-center display-6 fw-medium mx-auto">Pestastic Official Inventory</h1>
        <p class="text-center">Log in to start. For inquiries please visit our <a href="https://www.pestastic.com"
                class="link-opacity-75-hover link-underline-opacity-50 link-light">website.</a></p>
        <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] === 'unauthorized_access') {
                echo '<div class="alert alert-info w-75 align-self-center text-center">Access denied. For more information, please contact the administration.</div>';
            }
        }
        ?>
        <div class="btn-group mx-auto">
            <!-- <button type="button" class="btn btn-primary"><a href="createAccount.php" style="text-decoration: none"
                class="text-white">Admin Create Account</a></button> -->
            <a href="login.php" class="text-light link-opacity-75-hover link-underline-opacity-0 link-light">
                <button type="button" class="btn btn-sidebar btn-login p-2 d-flex gap-2">
                    <i class="bi bi-box-arrow-in-right text-light"></i>
                    <p class="text-light mb-0">
                        Login
                    </p>
                </button></a>

        </div>
    </div>

    <?php
    include("footer.php");
    ?>