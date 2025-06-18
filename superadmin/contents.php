<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Contents</title>
    <?php include('header.links.php'); ?>

</head>

<body class="bg-official text-light">

    <div class="sa-bg container-fluid p-0 h-100 d-flex">
        <!-- sidebar -->
        <?php include('sidenav.php'); ?>
        <!-- main content -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php include('navbar.php'); ?>
            <!-- content -->
            <div class="container-fluid h-100 justify-content-center d-flex flex-column">
                <h2 class="fw-medium text-center">Manage Contents</h2>
                <div class="container bg-light bg-opacity-25 rounded border border-light">
                    <p class="fw-medium fs-4 m-0">Transaction Contents</p>
                    <hr class="border border-light my-2">
                </div>
            </div>

        </main>

    </div>
    <?php include('footer.links.php'); ?>
</body>

</html>