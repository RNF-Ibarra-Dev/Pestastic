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

    <div class="sa-bg container-fluid p-0 min-vh-100 d-flex">
        <!-- sidebar -->
        <?php include('settings.sidenav.php'); ?>
        <!-- main content -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php include('navbar.php'); ?>
            <!-- content -->
            <div class="container-fluid p-0 d-flex justify-content-center flex-column">
                <form id="accountsettings" class="my-5">
                    <input type="hidden" name="id" id="id">
                    <h2 class="fw-light text-center fw-semibold mb-3">User Information</h2>
                    <div class="container bg-light bg-opacity-25 border border-light w-50 mx-auto rounded-3 p-3 pb-3">
                        <div class="d-flex flex-column gap-2 mx-3">
                            <div class="container gap-3 p-0 d-flex justify-content-between">
                                <div class="col p-0">
                                    <label for="fname" class="form-label fw-bold mb-1">First Name:</label>
                                    <input type="text" class="form-control-plaintext text-light ps-2" id="fname"
                                        name="fname" autocomplete="off" readonly>
                                </div>
                                <div class="col p-0">
                                    <label for="lname" class="form-label fw-bold mb-1">Last Name:</label>
                                    <input type="text" class="form-control-plaintext text-light ps-2" id="lname"
                                        name="lname" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="container gap-3 p-0 d-flex justify-content-between">
                                <div class="col p-0">
                                    <label for="username" class="form-label fw-bold mb-1">Username:</label>
                                    <input type="text" class="form-control-plaintext text-light ps-2" id="username"
                                        name="username" autocomplete="off" readonly>
                                </div>
                                <div class="col p-0">
                                    <label for="empid" class="form-label fw-bold mb-1">Employee ID:</label>
                                    <input type="text" class="form-control-plaintext text-light ps-2" id="empid"
                                        name="empid" autocomplete="off" readonly>
                                </div>
                            </div>
                            <label for="email" class="form-label fw-bold mb-0">Email:</label>
                            <input type="text" class="form-control-plaintext text-light ps-2" id="email" name="email"
                                autocomplete="off" readonly>
                            <label for="oldpassword" class="form-label fw-bold mb-0 d-none pwd-label">Old Password:</label>
                            <input type="password" class="form-control d-none" id="oldpassword" name="oldpassword"
                                autocomplete="new-password">
                            <label for="password" class="form-label fw-bold mb-0 d-none pwd-label">Password:</label>
                            <input type="password" class="form-control d-none" id="password" name="password"
                                autocomplete="new-password">
                            <label for="rpassword" class="form-label fw-bold mb-0 d-none pwd-label">Repeat
                                Password:</label>
                            <input type="password" class="form-control d-none" id="rpassword" name="rpassword"
                                autocomplete="new-password">
                            <label for="birthdate" class="form-label fw-bold mb-0">Birthdate:</label>
                            <input type="date" class="form-control ps-2 d-none" id="birthdate" name="birthdate">
                            <p class="text-light ms-2" id="displaybd"></p>

                            <div class="alert alert-info text-center m-0 mt-2" style="display: none" id="alert"></div>

                            <button type="button" class="btn btn-grad mt-3" id="editbtn">Edit Information</button>
                            <button type="submit" class="btn btn-grad d-none" id="submitbtn">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <?php include('footer.links.php'); ?>
    <script>
        const dataUrl = "tablecontents/acc.settings.data.php";
        let birthdate = flatpickr("#birthdate", {
            dateFormat: "F j, Y"
        });

        $(document).ready(async function() {
            await load_user();
        });

        async function load_user() {
            $.get(dataUrl, {
                    acc: true,
                    accountId: <?= $_SESSION['saID'] ?>
                })
                .done(function(d) {
                    console.log(d);
                    let data = JSON.parse(d);
                    console.log(data);
                    $('#fname').val(data.fname);
                    $('#lname').val(data.lname);
                    $("#username").val(data.usn);
                    $('#email').val(data.email);
                    $('#empid').val(data.empId);
                    birthdate.setDate(data.displaydate);
                    $('#displaybd').html(data.displaydate);
                    $('#id').val(data.id);
                })
                .fail(function(e) {
                    alert(e);
                })
                .always(function(e) {
                    // console.log(e);
                })
        }

        async function toggle_input(input_id) {
            if ($(`#${input_id}`).hasClass('form-control-plaintext')) {
                $(`#${input_id}`).removeClass('form-control-plaintext text-light').addClass('form-control');
                $(`#${input_id}`).attr('readonly', false);
            } else {
                $(`#${input_id}`).removeClass('form-control').addClass('form-control-plaintext text-light');
                $(`#${input_id}`).attr('readonly', true);
            }
            await load_user();
        }

        async function toggle() {
            Promise.all([
                await toggle_input('fname'),
                await toggle_input('lname'),
                await toggle_input('username'),
                await toggle_input('email'),
                await toggle_input('empid')
            ]);
            $("#displaybd, #password, #rpassword, #oldpassword, #passwordlabel, .pwd-label, #birthdate, #submitbtn").toggleClass('d-none');
            $("#editbtn").html(function(i, a) {
                return a.includes('Edit Information') ? 'Close Editor' : 'Edit Information';
            })
        }

        $(document).on('click', "#editbtn", async function() {
            await toggle();
        });

        $(document).on('submit', '#accountsettings', async function(e) {
            console.log($(this).serialize());
            e.preventDefault();
            $.ajax({
                    url: dataUrl,
                    method: 'POST',
                    dataType: 'json',
                    data: $(this).serialize() + "&editacc=true"
                })
                .done(async function(d) {
                    // alert(d.success);
                    toggle();
                    $("#alert").html(d.success).fadeIn(500).delay(2000).fadeOut(1000);
                })
                .fail(async function(e) {
                    console.log(e);
                    // alert(e);
                    $("#alert").html(e.responseText).fadeIn(500).delay(2000).fadeOut(1000);

                })
        });
    </script>

</body>


</html>