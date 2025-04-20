<?php
require("startsession.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Pestastic Inventory</title>
    <?php
    include("header.links.php");
    ?>
</head>


<body class="bg-official text-light">

    <div class="sa-bg container-fluid p-0 h-100 d-flex">
        <!-- sidebar -->

        <?php
        include("sidenav.php");
        ?>

        <!-- main content or right side -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php
            include("navbar.php");
            ?>
            <!-- information container -->
            <div class="container-fluid p-3">
                <div class="bg-light bg-opacity-25 rounded p-3 shadow">
                    <?php
                    if (isset($_SESSION['saID'])) {
                        echo '<h1 class="text-center display-6 mx-auto">Welcome to Pestastic Official Inventory ' . $_SESSION['saUsn'] . '</h1>';

                    } else {
                        echo "<h1 class='display-6 mx-auto text-center bg-light rounded bg-opacity-25'>Access Denied. No user is signed.</h1>";
                    }

                    ?>

                </div>
            </div>
            <div class="container text-center">
                <div class="row g-2 m-2">
                    <div class="bg-light bg-opacity-25 rounded p-3 shadow container col-sm">
                        <h1 class="text-center text-wrap display-6">Items Stock</h1>
                    </div>
                    <div class="bg-light bg-opacity-25 rounded p-3 shadow container col-sm">
                        <h1 class="text-center text-wrap display-6">Available Equipments</h1>
                    </div>
                    <div class="bg-light bg-opacity-25 rounded p-3 shadow container col-sm">
                        <h1 class="text-center text-wrap display-6">Function</h1>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="bg-light bg-opacity-25 rounded p-3 shadow container col-sm">
                        <h1 class="text-center text-wrap display-6">Available Equipments</h1>
                    </div>
                </div>
            </div>
    </div>
    </main>


    <?php
    include("footer.links.php");
    ?>
</body>

</html>