<?php
include("header.php");

session_start();
?>

<body class="d-flex align-items-center py-4 bg-body-tertiary bg-official-login">

    <div class="container-fluid d-flex flex-column text-light bg-light bg-opacity-25 w-50 p-3 pt-0 rounded-5">
        <img src="img/clearlogo.png" alt width="100" height="100" class="mb-4 align-self-center rounded">
        <h1 class="text-center display-6 mx-auto">Welcome to Pestastic Official Inventory</h1>
        <p class="text-center">Log in to start. For inquiries please visit our <a href="https://www.pestastic.com" class="link-opacity-75-hover link-underline-opacity-50 link-light">website.</a></p>
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

            <button type="button" class="btn btn-sidebar btn-login p-2"><a href="login.php"
                    class="text-white link-opacity-75-hover link-underline-opacity-0 link-light"><i class="bi bi-box-arrow-in-right me-2"></i>Log
                    in</a></button>

        </div>
    </div>

    <?php
    include("footer.php");
    ?>