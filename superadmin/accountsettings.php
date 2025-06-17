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
            <div class="container-fluid p-0 d-flex align-content-center flex-column">
                <form id="accountsettings">
                    <h3 class="fw-light text-center fw-semibold mb-2">Account Information</h3>
                    <div class="container bg-light bg-opacity-25 border border-light w-50 mx-auto rounded-3 p-3 pb-3">
                        <div class="d-flex flex-column gap-2 mx-3">
                            <div class="container gap-3 p-0 d-flex justify-content-between">
                                <div class="col p-0">
                                    <label for="fname" class="form-label fw-bold mb-1">First Name:</label>
                                    <input type="text" class="form-control-plaintext text-light ps-2" id="fname" name="fname" autocomplete="off">
                                </div>
                                <div class="col p-0">
                                    <label for="lname" class="form-label fw-bold mb-1">Last Name:</label>
                                    <input type="text" class="form-control-plaintext text-light ps-2" id="lname" name="lname" autocomplete="off">
                                </div>
                            </div>
                            <label for="username" class="form-label fw-bold mb-0">Username:</label>
                            <input type="text" class="form-control-plaintext text-light ps-2" id="username" name="username" autocomplete="off">
                            <label for="email" class="form-label fw-bold mb-0">Email:</label>
                            <input type="text" class="form-control-plaintext text-light ps-2" id="email" name="email" autocomplete="off">
                            <label for="password" class="form-label fw-bold mb-0 d-none pwd-label">Password:</label>
                            <input type="password" class="form-control d-none" id="password" name="password" autocomplete="new-password">
                            <label for="password" class="form-label fw-bold mb-0 d-none pwd-label" >Repeat Password:</label>
                            <input type="password" class="form-control d-none" id="password" name="password" autocomplete="new-password">
                            <label for="birthdate" class="form-label fw-bold mb-0">Birthdate:</label>
                            <input type="date" class="form-control ps-2 d-none" id="birthdate" name="birthdate">
                            <p class="text-light ms-2" id="displaybd"></p>

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
            $.get(dataUrl, {
                    acc: true,
                    accountId: <?= $_SESSION['saID'] ?>
                })
                .done(function(d) {
                    let data = JSON.parse(d);
                    console.log(data);
                    $('#fname').val(data.fname);
                    $('#lname').val(data.lname);
                    $("#username").val(data.usn);
                    $('#email').val(data.email);
                    birthdate.setDate(data.displaydate);
                    $('#displaybd').html(data.displaydate);
                })
                .fail(function(e) {
                    alert(e);
                })
                .always(function(e) {
                    // console.log(e);
                })
        });

        async function toggle_input(input_id) {

            if ($(`#${input_id}`).hasClass('form-control-plaintext')) {
                $(`#${input_id}`).removeClass('form-control-plaintext text-light').addClass('form-control');
            }else{
                $(`#${input_id}`).removeClass('form-control').addClass('form-control-plaintext text-light');
            }
        }

        $(document).on('click', "#editbtn", async function() {
            Promise.all([
                await toggle_input('fname'),
                await toggle_input('lname'),
                await toggle_input('username'),
                await toggle_input('email')
            ]);
            $("#displaybd, #password, #passwordlabel, .pwd-label, #birthdate, #submitbtn").toggleClass('d-none');
        });

        $(document).on('submit', '#accountsettings', async function(e){
            e.preventDefault();
        })
    </script>

</body>


</html>