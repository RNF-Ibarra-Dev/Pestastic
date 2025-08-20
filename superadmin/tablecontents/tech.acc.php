<?php
require("startsession.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Technician Accounts</title>
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
        <!-- main/side content -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php
            include("navbar.php");
            ?>

            <!-- manage account content -->
            <div class="bg-light bg-opacity-25 rounded p-3 mx-3 mt-3">
                <h1 class="display-6 text-center">Technician Information</h1>
            </div>
            <div class="table-responsive-sm d-flex justify-content-center">
                <table class="table align-middle table-hover tech-table w-100 text-light">
                    <caption class="text-light ms-1">All active technician account.</caption>
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
                    <tbody id="tech_table"></tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mb-5 visually-hidden" id="loader">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div id="os_pagination"></div>
            <!-- form -->
            <form id="editform" class="row g-2 text-dark">
                <input type="hidden" name="id" id="id_input">
                <div class="modal fade text-dark modal-edit" id="editModal" tabindex="-1" aria-labelledby="tech-"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="tech-">Edit
                                    <span id="display_name"></span>'s
                                    Information
                                </h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi text-light bi-x"></i></button>

                            </div>
                            <div class="modal-body">
                                <h1
                                    class="fs-5 mb-3 text-center bg-secondary text-light fw-bold py-1 rounded w-75 mx-auto bg-opacity-75">
                                    Primary Information</h1>
                                <div class="row mb-2">
                                    <div class="col-lg-6 mb-2">
                                        <label for="fname" class="form-label fw-light">First
                                            Name</label>
                                        <input type="text" name="fname" class="form-control-plaintext" id="fname"
                                            required>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label for="lname" class="form-label fw-light">Last
                                            Name</label>
                                        <input type="text" name="lname" class="form-control-plaintext" id="lname"
                                            required>
                                    </div>
                                </div>
                                <div class="invalid-feedback mb-2">
                                    Should not contain numbers and any special characters.
                                </div>

                                <div class="row mb-2">
                                    <div class="col-lg-4 mb-2 d-flex flex-column">
                                        <label for="birthdate" class="form-label fw-light">Birthdate</label>
                                        <p class="my-auto" id="bdate_info"></p>
                                        <input type="date" name="birthdate" class="form-control-plaintext d-none"
                                            id="birthdate" required>
                                    </div>
                                    <div class="mb-2 col">
                                        <label for="address" class="form-label fw-light">Address</label>
                                        <input type="text" name="address" class="form-control-plaintext" id="address"
                                            required>
                                    </div>
                                </div>

                                <h1
                                    class="fs-5 mb-3 text-center bg-secondary text-light fw-bold py-1 rounded w-75 mx-auto bg-opacity-75">
                                    Additional Information</h1>
                                <div class="row mb-2">
                                    <div class="col-lg-2 mb-2">
                                        <label for="usn" class="form-label fw-light">Username</label>
                                        <input type="text" name="usn" class="form-control-plaintext" id="usn" required>
                                    </div>

                                    <div class="col-lg-4 mb-2">
                                        <label for="email" class="form-label fw-light">Email
                                            Address</label>
                                        <input type="email" name="email" class="form-control-plaintext" id="email"
                                            required>
                                        <div class="form-text">
                                            Please choose a valid email.
                                        </div>
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label for="contact-number" class="form-label fw-light">Contact
                                            Number</label>
                                        <input type="number" name="contactNo" class="form-control-plaintext"
                                            id="contact-number" required>
                                    </div>

                                    <div class="col-lg-3 mb-2">
                                        <label for="emp-id" class="form-label fw-light">Employee
                                            ID</label>
                                        <input type="number" name="empId" class="form-control-plaintext" id="emp-id"
                                            required>
                                    </div>
                                </div>


                                <div id="password_section" class="d-none">
                                    <h1
                                        class="fs-5 mb-3 text-center bg-secondary bg-opacity-75 text-light fw-bold py-1 rounded w-75 mx-auto">
                                        Change Password</h1>
                                    <div class="row mb-2">
                                        <div class="col-lg-6 mb-2">
                                            <label for="pass" class="form-label fw-light">Password</label>
                                            <input type="password" name="pass" class="form-control-plaintext" id="pass">
                                        </div>

                                        <div class="col-lg-6 mb-2">
                                            <label for="passrepeat" class="form-label fw-light">Repeat
                                                Password</label>
                                            <input type="password" name="pwdRepeat" class="form-control-plaintext"
                                                id="passrepeat">
                                        </div>
                                    </div>
                                    <div id="passwordHelpBlock" class="form-text">
                                        To use the same password, kindly leave the password forms blank.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-grad" id="edit_toggle">Edit</button>
                                <button type="button" class="btn btn-grad d-none" data-bs-target="#modalVerify"
                                    data-bs-toggle="modal" id="proceedbtn" disabled>Proceed Edit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>


            <!-- verification modal (input SA/Manager password) -->
            <div class="modal fade text-dark modal-edit" id="modalVerify" tabindex="0" aria-labelledby="verifyChanges"
                aria-hidden="true">
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
                            <p class="alert alert-info py-2 text-center mt-2 fw-medium w-75 mx-auto mb-0" id="editAlert"
                                style="display: none"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                data-bs-target="#editModal">Go
                                back</button>
                            <button type="submit" class="btn btn-grad" name="submit-os-edit">Save
                                changes</button>
                        </div>
                    </div>
                </div>
            </div>
            </form>

            <form id="deleteform">
                <!-- delete modal -->
                <div class="modal fade text-dark modal-edit" id="deleteModal" tabindex="0"
                    aria-labelledby="verifyChanges" aria-hidden="true">
                    <input type="hidden" name="id" id="del_input_id">
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
                                    Note: deletion of accounts are irreversible. Proceed with caution.
                                </div>
                                <p class="alert alert-info py-2 text-center mt-2 fw-medium w-75 mx-auto mb-0"
                                    id="deleteAlert" style="display: none"></p>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-grad" name="deleteOS">Save
                                    changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </main>
    </div>
    <div class="toast-container m-2 me-3 bottom-0 end-0 position-fixed">
        <div class="toast align-items-center" role="alert" id="toast" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body text-dark ps-4 text-success-emphasis" id="toastmsg">
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php
    include("footer.links.php");
    ?>

    <script>
        $(document).ready(function () {

        });

    </script>

</body>

</html>