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
            <form id="accountsettings">
                <h2 class="fw-light mt-3 text-center"><?= $_SESSION['saUsn'] ?>'s Account Information</h2>
                <div class="container bg-dark bg-opacity-50 border border-light w-75 mx-auto rounded-3 p-3">
                    <div class="d-flex flex-column gap-2 w-75 mx-auto">
                        <div class="row">
                            <label for="fname" class="form-label fw-light col">First Name:</label>
                            <input type="text" class="form-control" id="fname" name="fname">
                            <label for="lname" class="form-label fw-light col">Last Name:</label>
                            <input type="text" class="form-control" id="lname" name="lname">
                        </div>
                        <label for="username" class="form-label fw-light">Username:</label>
                        <input type="text" class="form-control" id="username" name="username">
                        <label for="email" class="form-label fw-light">Email:</label>
                        <input type="text" class="form-control" id="email" name="email">
                        <label for="birthdate" class="form-label fw-light">Birth Date:</label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate">
                    </div>
                </div>
            </form>

        </main>

    </div>
    <?php include('footer.links.php'); ?>
</body>

</html>