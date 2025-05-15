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

    <div class="sa-bg container-fluid p-0 h-100 d-flex">
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

                    <div class="form-floating col-md-6 form-custom mb-2">
                        <input type="text" name="firstName" class="form-control" id="floatingInput"
                            placeholder="First Name...">
                        <label for="floatingInput">First Name</label>
                    </div>

                    <div class="form-floating col-md-6 form-custom mb-2">
                        <input type="text" name="lastName" class="form-control" id="floatingInput"
                            placeholder="Last Name...">
                        <label for="floatingInput">Last Name</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="text" name="username" class="form-control" id="floatingInput"
                            placeholder="Username...">
                        <label for="floatingInput">Username</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="Email...">
                        <label for="floatingInput">Email Address</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <select name="account" id="acc" class="form-select bg-transparent py-0 text-light">
                            <option value="" selected class="text-dark">Account Type</option>
                            <option value="os" class="text-dark">Operations Supervisor</option>
                            <option value="tech" class="text-dark">Technician</option>
                        </select>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="number" maxlength="11" name="contactNo" class="form-control" id="floatingInput"
                            placeholder="Contact No...">
                        <label for="floatingInput">Contact Number</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="number" name="empId" class="form-control" id="floatingInput"
                            placeholder="Employee ID...">
                        <label for="floatingInput">Employee ID</label>
                    </div>

                    <div class="form-floating col-md-4 form-custom mb-2">
                        <input type="date" name="birthdate" class="form-control" id="floatingInput"
                            placeholder="Employee ID..." value="1930-01-01">
                        <label for="floatingInput">Birthdate</label>
                    </div>

                    <div class="form-floating col-md-12 form-custom mb-2">
                        <input type="text" name="address" class="form-control" id="floatingInput"
                            placeholder="Address...">
                        <label for="floatingInput">Address</label>
                    </div>

                    <div class="form-floating col-md-6 form-custom mb-2">
                        <input type="password" name="pwd" class="form-control" id="floatingPassword"
                            placeholder="******">
                        <label for="floatingPassword">Password</label>
                    </div>

                    <div class="form-floating col-md-6 form-custom mb-2">
                        <input type="password" name="pwdRepeat" class="form-control" id="floatingPassword"
                            placeholder="******">
                        <label for="floatingPassword">Repeat Password</label>
                    </div>

                    <button class="btn btn-form-submit bg-light bg-opacity-75 text-dark" type="submit"><i
                            class="bi bi-person-add me-2"></i>Create Account</button>



                    <?php
                    // if (isset($_GET["error"])) {
                    //     if ($_GET["error"] == "emptyinput") {
                    //         echo "<p class='text-center alert alert-info w-75 mx-auto'>All fields must be filled.</p>";
                    //     } elseif ($_GET["error"] == "invalidusername") {
                    //         echo "<p class='text-center alert alert-info w-75 mx-auto'>Username not valid or it might be taken. Choose another username.</p>";
                    //     } elseif ($_GET["error"] == "invalidfirstname" || $_GET["error"] == "invalidlastname") {
                    //         echo "<p class='text-center alert alert-info w-75 mx-auto'>Numbers are not allowed for names.</p>";
                    //     } elseif ($_GET["error"] == "invalidemail") {
                    //         echo "<p class='text-center alert alert-info w-75 mx-auto'>Email not valid. Choose a valid email.</p>";
                    //     } elseif ($_GET["error"] == "passwordsdontmatch") {
                    //         echo "<p class='text-center alert alert-info w-75 mx-auto'>Passwords do not match.</p>";
                    //     } elseif ($_GET["error"] == "useralreadyexist") {
                    //         echo "<p class='text-center alert alert-info w-75 mx-auto'>User already exists!</p>";
                    //     } elseif ($_GET["error"] == "stmtfailed") {
                    //         echo "<p class='text-center alert alert-info w-75 mx-auto'>Something went wrong, try again.</p>";
                    //     } elseif ($_GET["error"] == "none") {
                    //         echo "<p class='text-center alert alert-success w-75 mx-auto'>Account created.</p>";
                    //     } elseif ($_GET["error"] == "existingemployeeid") {
                    //         echo "<p class='text-center alert alert-info w-75 mx-auto'>Employee ID already exist.</p>";
                    //     }
                    // }
                    ?>

                </form>
            </div>
            <p class='text-center alert alert-info w-75 mx-auto mt-3' id="alert" style="display: none !important;">
            </p>
            <p class='text-center alert alert-success w-75 mx-auto mt-3' id="successalert" style="display: none !important;">
            </p>
        </main>
    </div>
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
                    console.log(create);
                    $('#successalert').html(create.success).removeAttr('display').fadeIn(400).delay(1000).fadeOut(500);
                }
            } catch (error) {
                console.log(error);
                console.log(error.responseText);
                $('#alert').html(error.responseText).removeAttr('display').fadeIn(400).delay(1000).fadeOut(500);
            }
        })

        async function check_input() {

        }
    </script>
    <?php
    include("footer.links.php");
    ?>

</body>

</html>