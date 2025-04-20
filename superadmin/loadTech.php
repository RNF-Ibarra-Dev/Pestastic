<?php
require_once("startsession.php");
require_once("../includes/dbh.inc.php");


$techCount = $_POST['technicianCount'];
$sql = "SELECT * FROM technician LIMIT $techCount;";
$result = mysqli_query($conn, $sql);
$resultCheck = mysqli_num_rows($result);
// $tech = mysqli_fetch_assoc($result);
if ($result) {
    if ($resultCheck > 0) {
        // $techCount += 2;
        while ($row = mysqli_fetch_assoc($result)) {
            $techId = $row["technicianId"];
            $techFName = $row["firstName"];
            $techLName = $row["lastName"];
            $techEmail = $row["techEmail"];
            $techUsn = $row["username"];
            $techContact = $row["techContact"];
            $techEmpId = $row["techEmpId"];
            $techAddress = $row["techAddress"];
            $techPass = $row["techPwd"];
            $techBirthdate = $row["techBirthdate"];
            ?>
            <tr>
                <td scope="row"><?= htmlspecialchars($techFName) ?></td>
                <td><?= htmlspecialchars($techLName) ?></td>
                <td><?= htmlspecialchars($techUsn) ?></td>
                <td><?= htmlspecialchars($techEmail) ?></td>
                <td><?= htmlspecialchars($techContact) ?></td>
                <td><?= htmlspecialchars($techEmpId) ?></td>
                <td><?= htmlspecialchars($techAddress) ?></td>
                <td><?= htmlspecialchars($techBirthdate) ?></td>

                <td>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-sidebar me-2" data-bs-toggle="modal"
                            data-bs-target="#editModal<?= $techId ?>"><i class="bi bi-person-gear me-1"></i>edit</button>
                        <button type="button" class="btn btn-sidebar me-2" data-bs-toggle="modal"
                            data-bs-target="#deleteModal<?= $techId ?>"><i class="bi bi-person-dash me-1"></i>delete</button>
                    </div>
                </td>
            </tr>
            <!-- edit modal -->
            <!-- form -->
            <form action="../includes/editAcc.inc.php" method="post" class="row g-2 text-dark">
                <input type="hidden" name="id" value="<?= $techId ?>">
                <div class="modal fade text-dark modal-edit" id="editModal<?= $techId ?>" tabindex="-1"
                    aria-labelledby="tech-<?= $techId ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="tech-<?= $techId ?>">Edit
                                    <?= $techFName ?>'s
                                    Information
                                </h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                        class="bi text-light bi-x"></i></button>

                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <div class="col-lg-6 mb-2">
                                        <label for="fname<?= $techId ?>" class="form-label fw-light">First
                                            Name</label>
                                        <input type="text" name="fname" class="form-control" id="fname<?= $techId ?>"
                                            value="<?= $techFName ?>" required>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label for="lname<?= $techId ?>" class="form-label fw-light">Last
                                            Name</label>
                                        <input type="text" name="lname" class="form-control" id="lname<?= $techId ?>"
                                            value="<?= $techLName ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-4 mb-2">
                                        <label for="usn<?= $techId ?>" class="form-label fw-light">Username</label>
                                        <input type="text" name="usn" class="form-control" id="usn<?= $techId ?>"
                                            value="<?= $techUsn ?>" required>
                                    </div>

                                    <div class="col-lg-8 mb-2">
                                        <label for="email<?= $techId ?>" class="form-label fw-light">Email
                                            Address</label>
                                        <input type="email" name="email" class="form-control" id="email<?= $techId ?>"
                                            value="<?= $techEmail ?>" required>
                                        <div class="form-text mb-0">
                                            Please choose a valid email.
                                        </div>
                                    </div>
                                </div>

                                <h1 class="fs-5 mb-3">Additional Information</h1>
                                <div class="row mb-2">
                                    <div class="col-lg-4 mb-2">
                                        <label for="contact-number<?= $techId ?>" class="form-label fw-light">Contact
                                            Number</label>
                                        <input type="text" name="contactNo" class="form-control" id="contact-number<?= $techId ?>"
                                            value="<?= $techContact ?>" required>
                                    </div>

                                    <div class="col-lg-4 mb-2">
                                        <label for="emp-id<?= $techId ?>" class="form-label fw-light">Employee ID</label>
                                        <input type="number" name="empId" class="form-control" id="emp-id<?= $techId ?>"
                                            value="<?= $techEmpId ?>" required>
                                    </div>

                                    <div class="col-lg-4 mb-2">
                                        <label for="emp-id<?= $techId ?>" class="form-label fw-light">Birthdate</label>
                                        <input type="date" name="birthdate" class="form-control" id="emp-id<?= $techId ?>"
                                            value="<?= $techBirthdate ?>" required>
                                    </div>
                                </div>
                                <div class="mb-2 col">
                                    <label for="address<?= $techId ?>" class="form-label fw-light">Address</label>
                                    <input type="text" name="address" class="form-control" id="address<?= $techId ?>"
                                        value="<?= $techAddress ?>" required>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-6 mb-2">
                                        <label for="pass<?= $techId ?>" class="form-label fw-light">Password</label>
                                        <input type="password" name="pass" class="form-control" id="pass<?= $techId ?>">
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="passrepeat<?= $techId ?>" class="form-label fw-light">Repeat
                                            Password</label>
                                        <input type="password" name="pwdRepeat" class="form-control" id="passrepeat<?= $techId ?>">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    To use the same password, kindly leave the password forms blank.
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-grad" data-bs-target="#modalVerify<?= $techId ?>"
                                    data-bs-toggle="modal">Proceed</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- verification modal (input SA/Manager password) -->
                <div class="modal fade text-dark modal-edit" id="modalVerify<?= $techId ?>" tabindex="0"
                    aria-labelledby="verifyChanges" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyChanges">Verify Password</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                        class="bi bi-x text-light"></i></button>
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
                                    data-bs-target="#editModal<?= $techId ?>">Go back</button>
                                <button type="submit" class="btn btn-grad" name="submit-tech-edit">Save
                                    changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form action="../includes/deleteAcc.inc.php" method="post">
                <!-- delete modal -->
                <div class="modal fade text-dark modal-edit" id="deleteModal<?= $techId ?>" tabindex="0"
                    aria-labelledby="verifyChanges" aria-hidden="true">
                    <input type="hidden" value="<?= $techId ?>" name="id">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyChanges">Account Deletion</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                        class="bi bi-x text-light"></i></button>
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
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-grad" name="deleteTech">Save
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
