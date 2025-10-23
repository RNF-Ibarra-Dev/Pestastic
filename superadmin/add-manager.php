<?php
require("startsession.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager | Add Manager Account</title>
    <?php
    include("header.links.php");
    ?>
</head>

<body class="bg-official text-light ">

    <div class="sa-bg container-fluid p-0 min-vh-100 d-flex">
        <!-- sidenavb -->
        <?php
        include("settings.sidenav.php");
        ?>
        <main class="sa-content col-sm-10 p-0 container-fluid ">
            <!-- navbar -->
            <?php
            include("navbar.php");
            ?>

            <div class="bg-light bg-opacity-25 rounded-3 d-flex justify-content-center py-2 my-3 mx-2">
                <img src="../img/logo.svg" style="max-height: 4rem;"
                    class="me-2 shadow-sm my-auto bg-dark bg-opacity-25 rounded-circle">
                <h1 class="fs-1 text-shadow fw-bold align-middle m-0 my-auto">Add New Manager</h1>
            </div>
            <div
                class="p-3 mb-3 mx-2 d-flex justify-content-center align-items-center mt-3 rounded-3 bg-light bg-opacity-25">
                <form class="row g-2 text-light" id="createacc">


                    <p class="fs-4 text-light fw-bold text-center text-shadow">Primary Information</p>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="text" name="firstName" class="form-control" id="fname" autocomplete="off"
                            placeholder="First Name...">
                        <label for="fname" class="fw-bold">First Name</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="text" name="lastName" class="form-control" id="lname" autocomplete="off"
                            placeholder="Last Name...">
                        <label for="lname" class="fw-bold">Last Name</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="date" name="birthdate" class="form-control" id="birthdate"
                            placeholder="Employee ID..." value="1930-01-01">
                        <label for="birthdate" class="fw-bold">Birthdate</label>
                    </div>

                    <p class="fs-4 text-light fw-bold text-center text-shadow">Account Information</p>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="text" name="username" class="form-control" id="usn" autocomplete="username"
                            placeholder="Username...">
                        <label for="usn" class="fw-bold">Username</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email..."
                            autocomplete="off">
                        <label for="email" class="fw-bold">Email Address</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="number" name="empId" class="form-control" id="empid" placeholder="Employee ID..."
                            autocomplete="off">
                        <label for="empid" class="fw-bold">Employee ID</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <select name="branch" id="branch" class="form-select bg-transparent py-0 text-light fw-bold">
                        </select>
                    </div>

                    <p class="fs-4 text-light fw-bold text-center text-shadow">Password</p>
                    <div class="form-floating col-md-6 form-custom mb-2">
                        <input type="password" name="pwd" class="form-control" id="pwd" placeholder="******"
                            autocomplete="new-password">
                        <label for="pwd" class="fw-bold">Password</label>
                    </div>

                    <div class="form-floating col-md-6 form-custom mb-2">
                        <input type="password" name="pwdRepeat" class="form-control" id="rpwd" placeholder="******"
                            autocomplete="new-password">
                        <label for="rpwd" class="fw-bold">Repeat Password</label>
                    </div>
                    <div class="form-check ms-2">
                        <input type="checkbox" class="form-check-input" id="showpass">
                        <label class="form-check-label text-light user-select-none" for="showpass">Show
                            Password</label>
                    </div>


                    <button class="btn btn-form-submit bg-light fw-medium mx-0 bg-opacity-75 text-dark" type="button"
                        data-bs-toggle="modal" data-bs-target="#confirm"><i class="bi bi-person-add me-2"></i>Create
                        Account</button>


                    <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="confirm" tabindex="0">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Confirm Account Creation</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                        aria-label="Close"><i class="bi bi-x text-light"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-2 d-flex flex-column justify-content-center">
                                        <label for="complete-inputpwd" class="form-label">Create this account?
                                            Enter Manager
                                            <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                        <div class="col-lg-6 mb-2">
                                            <input type="password" name="manager_pwd" class="form-control w-75"
                                                id="complete-inputpwd" autocomplete="current-password">
                                        </div>
                                    </div>
                                    <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                        id="alert">
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Go back</button>
                                    <button type="submit" class="btn btn-grad">Create Account</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <p class='text-center alert alert-success w-75 mx-auto mt-3' id="successalert"
                style="display: none !important;">
            </p>
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
        function show_toast(message) {
            $('#toastmsg').html(message);
            var toastid = $('#toast');
            var toast = new bootstrap.Toast(toastid);
            toast.show();
        }
        const submitUrl = "tablecontents/createacc.php";

        $(document).on('submit', '#createacc', async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            try {
                const create = await $.ajax({
                    url: submitUrl,
                    method: 'POST',
                    dataType: 'json',
                    data: $(this).serialize() + "&addmanager=true"
                });

                if (create) {
                    $('#createacc')[0].reset();
                    $('#successalert').html(create.success).removeAttr('display').fadeIn(400).delay(1000).fadeOut(500);
                    $("#confirm").modal('hide');
                }
            } catch (error) {
                console.log(error);
                console.log(error.responseText);
                $('#alert').html(error.responseText).removeAttr('display').fadeIn(400).delay(1000).fadeOut(500);
            }
        });

        $(function () {
            $.get("tablecontents/createacc.php", { branches: 'true' }, function (data) {
                $('#branch').html(data);
            }).fail(function (error) {
                console.error("Error fetching branches:", error);
                $("#branch").html("<option value='' selected disabled>Error loading branches</option>");
            });
        });

        const today = new Date();
        const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
        let bday = $('#birthdate');
        flatpickr(bday, {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            maxDate: maxDate
        });

        $("#createacc").on('change', '#showpass', function () {
            let passField = $("#pwd");
            let passRepeatField = $("#rpwd");
            if ($(this).is(':checked')) {
                passField.attr('type', 'text');
                passRepeatField.attr('type', 'text');
            } else {
                passField.attr('type', 'password');
                passRepeatField.attr('type', 'password');
            }
        });
    </script>


</body>

</html>