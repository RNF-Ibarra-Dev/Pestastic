<?php
require("startsession.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Create Account</title>
    <?php
    include("header.links.php");
    ?>
</head>

<body class="bg-official text-light ">

    <div class="sa-bg container-fluid p-0 min-vh-100 d-flex">
        <!-- sidenavb -->
        <?php
        include("sidenav.php");
        ?>
        <main class="sa-content col-sm-10 p-0 container-fluid ">
            <!-- navbar -->
            <?php
            include("navbar.php");
            ?>

            <div
                class="container p-3 w-75 d-flex justify-content-center align-items-center mt-3 rounded-3 bg-light bg-opacity-25">
                <form class="row g-2 text-light" id="createacc">

                    <div class="container my-3">

                        <h1 class="h3 fw-normal w-100 text-center col-md-6 align-self-center"> <img
                                src="../img/pestasticlogoonly.png" alt width="30" height="30" class="me-3">Create New
                            Account</h1>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="text" name="firstName" class="form-control" id="floatingInput" autocomplete="off"
                            placeholder="First Name...">
                        <label for="floatingInput" class="fw-bold">First Name</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="text" name="lastName" class="form-control" id="floatingInput" autocomplete="off"
                            placeholder="Last Name...">
                        <label for="floatingInput" class="fw-bold">Last Name</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="text" name="username" class="form-control" id="floatingInput" autocomplete="off"
                            placeholder="Username...">
                        <label for="floatingInput" class="fw-bold">Username</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="Email..."
                            autocomplete="off">
                        <label for="floatingInput" class="fw-bold">Email Address</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <select name="account" id="acc" class="form-select bg-transparent py-0 text-light fw-bold">
                            <option value="" selected class="text-dark">Account Type</option>
                            <option value="os" class="text-dark">Operations Supervisor</option>
                            <option value="tech" class="text-dark">Technician</option>
                        </select>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <select name="branch" id="branch" class="form-select bg-transparent py-0 text-light fw-bold">
                        </select>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="number" maxlength="11" name="contactNo" class="form-control" id="contact"
                            placeholder="Contact No..." autocomplete="off">
                        <label for="contact" class="fw-bold">Contact Number</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="number" name="empId" class="form-control" id="empid" placeholder="Employee ID..."
                            autocomplete="off">
                        <label for="empid" class="fw-bold">Employee ID</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="date" name="birthdate" class="form-control" id="birthdate"
                            placeholder="Employee ID..." value="1930-01-01">
                        <label for="birthdate" class="fw-bold">Birthdate</label>
                    </div>

                    <div class="form-floating col-md-12 form-custom mb-2">
                        <input type="text" name="address" class="form-control" id="addess" placeholder="Address..."
                            autocomplete="off">
                        <label for="addess" class="fw-bold">Address</label>
                    </div>

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



                    <button class="btn btn-form-submit bg-light bg-opacity-75 text-dark" type="button"
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
    <?php
    include("footer.links.php");
    ?>
    <script>
        const submitUrl = "tablecontents/createacc.php";

        $(document).on('submit', '#createacc', async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            try {
                const create = await $.ajax({
                    url: submitUrl,
                    method: 'POST',
                    dataType: 'json',
                    data: $(this).serialize() + "&createacc=true"
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
    </script>


</body>

</html>