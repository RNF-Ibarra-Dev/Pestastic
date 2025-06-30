<?php
require("startsession.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Operation Supervisor Accounts</title>
    <?php
    include("header.links.php");
    ?>
</head>

<body class="bg-official text-light">

    <div class="sa-bg container-fluid p-0 min-vh-100 d-flex">
        <!-- sidebar -->
        <?php
        include("sidenav.php");
        ?>
        <!-- main/side content -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php
            include("navbar.php");
            ?>

            <!-- manage account content -->
            <div class="bg-light bg-opacity-25 rounded p-3 mx-3 mt-3">
                <h1 class="display-6 text-center">Active Operation Supervisors Accounts</h1>
            </div>
            <div class="table-responsive-sm d-flex justify-content-center">
                <table class="table align-middle table-hover os-table w-100 text-light">
                    <caption class="text-light">List of all active operation supervisors. To create an account, proceed
                        to 'Create Account'
                        page. </caption>
                    <thead>
                        <tr>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Contact #</th>
                            <th scope="col">Employee ID</th>
                            <th scope="col">Address</th>
                            <th scope="col">Birthdate</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once("../includes/dbh.inc.php");
                        $sql = "SELECT * FROM branchadmin;";
                        $result = mysqli_query($conn, $sql);
                        $resultCheck = mysqli_num_rows($result);
                        if ($result) {
                            if ($resultCheck > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $baId = $row["baID"];
                                    $baFName = $row["baFName"];
                                    $baLName = $row["baLName"];
                                    $baEmail = $row["baEmail"];
                                    $baUsn = $row["baUsn"];
                                    $baPwd = $row["baPwd"];
                                    $baContact = $row["baContact"];
                                    $baEmpId = $row["baEmpId"];
                                    $baAddress = $row["baAddress"];
                                    $baBirthdate = $row["baBirthdate"];
                                    ?>
                                    <tr>
                                        <td scope="row"><?= htmlspecialchars($baFName) ?></td>
                                        <td><?= htmlspecialchars($baLName) ?></td>
                                        <td><?= htmlspecialchars($baUsn) ?></td>
                                        <td><?= htmlspecialchars($baEmail) ?></td>
                                        <td><?= htmlspecialchars($baContact) ?></td>
                                        <td><?= htmlspecialchars($baEmpId) ?></td>
                                        <td><?= htmlspecialchars($baAddress) ?></td>
                                        <td><?= htmlspecialchars($baBirthdate) ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-sidebar me-2" data-bs-toggle="modal"
                                                    data-bs-target="#editModal<?= $baId ?>">
                                                    <i class="bi bi-person-gear me-1"></i>edit
                                                </button>
                                                <button type="button" class="btn btn-sidebar me-2" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal<?= $baId ?>">
                                                    <i class="bi bi-person-gear me-1"></i>delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- edit modal -->
                                    <!-- form -->
                                    <form action="../includes/editAcc.inc.php" method="post" class="row g-2 text-dark">
                                        <input type="hidden" name="id" value="<?= $baId ?>">
                                        <div class="modal fade text-dark modal-edit" id="editModal<?= $baId ?>" tabindex="-1"
                                            aria-labelledby="tech-<?= $baId ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-modal-title text-light">
                                                        <h1 class="modal-title fs-5" id="tech-<?= $baId ?>">Edit
                                                            <?= $baFName ?>'s
                                                            Information
                                                        </h1>
                                                        <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                                            aria-label="Close"><i class="bi text-light bi-x"></i></button>

                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-2">
                                                            <div class="col-lg-6 mb-2">
                                                                <label for="fname<?= $baId ?>" class="form-label fw-light">First
                                                                    Name</label>
                                                                <input type="text" name="fname" class="form-control"
                                                                    id="fname<?= $baId ?>" value="<?= $baFName ?>" required>
                                                            </div>
                                                            <div class="col-lg-6 mb-2">
                                                                <label for="lname<?= $baId ?>" class="form-label fw-light">Last
                                                                    Name</label>
                                                                <input type="text" name="lname" class="form-control"
                                                                    id="lname<?= $baId ?>" value="<?= $baLName ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="invalid-feedback mb-2">
                                                            Should not contain numbers and any special characters.
                                                        </div>

                                                        <div class="row mb-2">
                                                            <div class="col-lg-4 mb-2">
                                                                <label for="usn<?= $baId ?>"
                                                                    class="form-label fw-light">Username</label>
                                                                <input type="text" name="usn" class="form-control"
                                                                    id="usn<?= $baId ?>" value="<?= $baUsn ?>" required>
                                                            </div>

                                                            <div class="col-lg-8 mb-2">
                                                                <label for="email<?= $baId ?>" class="form-label fw-light">Email
                                                                    Address</label>
                                                                <input type="email" name="email" class="form-control"
                                                                    id="email<?= $baId ?>" value="<?= $baEmail ?>" required>
                                                                <div class="form-text">
                                                                    Please choose a valid email.
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <h1 class="fs-5 mb-3">Additional Information</h1>
                                                        <div class="row mb-2">
                                                            <div class="col-lg-4 mb-2">
                                                                <label for="contact-number<?= $baId ?>"
                                                                    class="form-label fw-light">Contact
                                                                    Number</label>
                                                                <input type="number" name="contactNo" class="form-control"
                                                                    id="contact-number<?= $baId ?>" value="<?= $baContact ?>"
                                                                    required>
                                                            </div>

                                                            <div class="col-lg-4 mb-2">
                                                                <label for="emp-id<?= $baId ?>" class="form-label fw-light">Employee
                                                                    ID</label>
                                                                <input type="number" name="empId" class="form-control"
                                                                    id="emp-id<?= $baId ?>" value="<?= $baEmpId ?>" required>
                                                            </div>

                                                            <div class="col-lg-4 mb-2">
                                                                <label for="birthdate<?= $baId ?>"
                                                                    class="form-label fw-light">Birthdate</label>
                                                                <input type="date" name="birthdate" class="form-control"
                                                                    id="birthdate<?= $baId ?>" value="<?= $baBirthdate ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-2 col">
                                                            <label for="address<?= $baId ?>"
                                                                class="form-label fw-light">Address</label>
                                                            <input type="text" name="address" class="form-control"
                                                                id="address<?= $baId ?>" value="<?= $baAddress ?>" required>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-lg-6 mb-2">
                                                                <label for="pass<?= $baId ?>"
                                                                    class="form-label fw-light">Password</label>
                                                                <input type="password" name="pass" class="form-control"
                                                                    id="pass<?= $baId ?>">
                                                            </div>

                                                            <div class="col-lg-6 mb-2">
                                                                <label for="passrepeat<?= $baId ?>"
                                                                    class="form-label fw-light">Repeat
                                                                    Password</label>
                                                                <input type="password" name="pwdRepeat" class="form-control"
                                                                    id="passrepeat<?= $baId ?>">
                                                            </div>
                                                        </div>
                                                        <div id="passwordHelpBlock" class="form-text">
                                                            To use the same password, kindly leave the password forms blank.
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-grad"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-grad"
                                                            data-bs-target="#modalVerify<?= $baId ?>"
                                                            data-bs-toggle="modal">Proceed</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- verification modal (input SA/Manager password) -->
                                        <div class="modal fade text-dark modal-edit" id="modalVerify<?= $baId ?>" tabindex="0"
                                            aria-labelledby="verifyChanges" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-modal-title text-light">
                                                        <h1 class="modal-title fs-5" id="verifyChanges">Verify Password</h1>
                                                        <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                                            aria-label="Close"><i class="bi bi-x text-light"></i></button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row mb-2">
                                                            <div class="col-lg-6 mb-2">
                                                                <label for="pass" class="form-label fw-light"> Manager
                                                                    <?= $_SESSION['saUsn'] ?>'s Password</label>
                                                                <input type="password" name="saPwd" class="form-control" id="pass">
                                                            </div>
                                                        </div>
                                                        <div id="passwordHelpBlock" class="form-text">
                                                            Please verify using the manager account password
                                                            to proceed.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                                            data-bs-target="#editModal<?= $baId ?>">Go back</button>
                                                        <button type="submit" class="btn btn-grad" name="submit-os-edit">Save
                                                            changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <form action="../includes/deleteAcc.inc.php" method="post">
                                        <!-- delete modal -->
                                        <div class="modal fade text-dark modal-edit" id="deleteModal<?= $baId ?>" tabindex="0"
                                            aria-labelledby="verifyChanges" aria-hidden="true">
                                            <input type="hidden" value="<?= $baId ?>" name="id">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-modal-title text-light">
                                                        <h1 class="modal-title fs-5" id="verifyChanges">Account Deletion</h1>
                                                        <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                                            aria-label="Close"><i class="bi bi-x text-light"></i></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-2">
                                                            <label for="pass" class="form-label fw-light"> Are you sure you want to
                                                                delete this account? Enter manager
                                                                <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                                            <div class="col-lg-6 mb-2">
                                                                <input type="password" name="saPwd" class="form-control" id="pass">
                                                            </div>
                                                        </div>
                                                        <div id="passwordHelpBlock" class="form-text">
                                                            Note: deletion of accounts are irreversible.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-grad"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-grad" name="deleteOS">Save
                                                            changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <?php
                                }
                            } else {
                                echo "<h1 class='text-center'>No Records Found</h1>";
                            }
                        } else {
                            echo "Query Failed" . mysqli_error($conn);
                        }

                        ?>
                    </tbody>
                </table>
            </div>
            <!-- get error msgs -->
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "none") {
                    echo "<p class='text-center alert alert-success w-75 mx-auto'>Account Updated.</p>";
                } elseif ($_GET["error"] == "invalidusername") {
                    echo "<p class='text-center alert alert-info w-75 mx-auto'>Username not valid, please try again.</p>";
                } elseif ($_GET["error"] == "invalidfirstname" || $_GET["error"] == "invalidlastname") {
                    echo "<p class='text-center alert alert-info w-75 mx-auto'>Numbers in names are prohibited. Try again.</p>";
                } elseif ($_GET["error"] == "invalidemail") {
                    echo "<p class='text-center alert alert-info w-75 mx-auto'>Email invalid, make sure to choose a valid email.</p>";
                } elseif ($_GET["error"] == "passwordsdontmatch") {
                    echo "<p class='text-center alert alert-info w-75 mx-auto'>Password mismatch.</p>";
                } elseif ($_GET["error"] == "useralreadyexist") {
                    echo "<p class='text-center alert alert-info w-75 mx-auto'>Username already exist, please try again.</p>";
                } elseif ($_GET["error"] == "stmtfailed") {
                    echo "<p class='text-center alert alert-info w-75 mx-auto'>Something went wrong, try again.</p>";
                } elseif ($_GET["error"] == "invalidmanagerpassword") {
                    echo "<p class='text-center alert alert-info w-75 mx-auto'>Invalid manager password, try again.</p>";
                } elseif ($_GET["error"] == "emptymanagerpassword") {
                    echo "<p class='text-center alert alert-info w-75 mx-auto'>Empty manager password is prohibited.</p>";
                } elseif ($_GET["error"] == "unmatchedpassword") {
                    echo "<p class='text-center alert alert-info w-75 mx-auto'>New password not match. Please try again.</p>";
                } elseif ($_GET["error"] == "existingemployeeid") {
                    echo "<p class='text-center alert alert-info w-75 mx-auto'>Employee ID already exist.</p>";
                }
            } elseif (isset($_GET["success"]) == 'accountdeleted') {
                echo "<p class='text-center alert alert-success w-75 mx-auto'>Account Deleted.</p>";
            }
            ?>
        </main>
    </div>
    <?php
    include("footer.links.php");
    ?>

</body>

</html>