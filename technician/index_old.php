<?php
include("../header.php");

session_start();
?>

<div class="container-fluid d-flex flex-column">
    <?php
    if (isset($_SESSION['techId'])) {
        echo '<h1 class="text-center display-6 mx-auto">Welcome to Pestastic Official Inventory ' . $_SESSION['techUsn'] . '</h1>';
    } else {
        echo "<h1 class='display-6 mx-auto text-center'>Access Denied.</h1>";
        header("refresh: 3; url=../index.php?error=unauthorized_access");
    }
    ?>
    <p class="text-center">Create Account</p>


    <div class="btn-group mx-auto">
        <button type="button" class="btn btn-primary"><a href="../createAcc/branchAdmin.php"
                style="text-decoration: none" class="text-white">Branch Admin Account</a></button>
        <button type="button" class="btn btn-primary"><a href="../createAcc/technician.php"
                style="text-decoration: none" class="text-white">Technician Account</a></button>

        <?php
        if (isset($_SESSION['techId'])) {
            echo '<button type="button" class="btn btn-primary">Logged in</button>';
            echo '<button type="button" class="btn btn-primary"><a href="../includes/logout.inc.php" style="text-decoration: none"
                class="text-white">Log out</a></button>';
        } else {
            echo '<button type="button" class="btn btn-primary"><a href="login.php" style="text-decoration: none"
                class="text-white">Log the fuck in</a></button>';
        }
        ?>


    </div>
</div>

<?php
include("../footer.php");
?>