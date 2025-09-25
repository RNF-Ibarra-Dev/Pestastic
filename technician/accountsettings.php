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
    <style>
        input,
        textarea {
            font-size: 1.25rem !important
        }
    </style>
</head>

<body class="bg-official text-light">

    <div class="sa-bg container-fluid p-0 min-vh-100 d-flex">
        <!-- sidebar -->
        <?php include('sidenav.php'); ?>
        <!-- main content -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php include('navbar.php'); ?>
            <!-- content -->
            <div class="container-fluid p-0 d-flex">
                <form id="accountsettings" class="my-5">
                    <input type="hidden" name="id" id="id">
                    <p
                        class="display-6  text-center fw-bold text-shadow mb-3 bg-light bg-opacity-25 rounded-3 py-3 mx-auto w-50">
                        User Information</p>
                    <div class="container bg-light bg-opacity-25 w-50 mx-auto rounded-3 p-3 pb-3">
                        <div class="d-flex flex-column gap-2 mx-3">
                            <div class="container two-part-inputs flex-wrap gap-3 p-0 d-flex justify-content-between">
                                <div class="col p-0">
                                    <label for="fname" class="form-label fw-bold mb-1 text-shadow">First Name:</label>
                                    <input type="text" class="form-control-plaintext text-light ps-2" id="fname"
                                        name="fname" autocomplete="off" readonly>
                                </div>
                                <div class="col p-0">
                                    <label for="lname" class="form-label fw-bold mb-1 text-shadow">Last Name:</label>
                                    <input type="text" class="form-control-plaintext text-light ps-2" id="lname"
                                        name="lname" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="container two-part-inputs flex-wrap gap-3 p-0 d-flex justify-content-between">
                                <div class="col p-0">
                                    <label for="username" class="form-label fw-bold mb-1 text-shadow">Username:</label>
                                    <input type="text" class="form-control-plaintext text-light ps-2" id="username"
                                        name="username" autocomplete="off" readonly>
                                </div>
                                <div class="col p-0">
                                    <p class="fw-bold mb-1 text-shadow fs-5">Employee ID:</p>
                                    <p class="text-light ps-2 fs-5 mt-2 mb-0" id="empid">
                                </div>
                            </div>
                            <label for="address" class="form-label fw-bold mb-0 text-shadow">Address:</label>
                            <textarea name="address" id="address"
                                class="form-control-plaintext text-light ps-2" style="resize: none;"
                                readonly></textarea>
                            <label for="email" class="form-label fw-bold mb-0 text-shadow">Email:</label>
                            <input type="text" class="form-control-plaintext text-light ps-2" id="email" name="email"
                                autocomplete="off" readonly>
                            <label for="oldpassword" class="form-label fw-bold mb-0 d-none pwd-label text-shadow">Old
                                Password:</label>
                            <input type="password" class="form-control d-none" id="oldpassword" name="oldpassword"
                                autocomplete="new-password">
                            <label for="password"
                                class="form-label fw-bold mb-0 d-none pwd-label text-shadow">Password:</label>
                            <input type="password" class="form-control d-none" id="password" name="password"
                                autocomplete="new-password">
                            <label for="rpassword" class="form-label fw-bold mb-0 d-none pwd-label text-shadow">Repeat
                                Password:</label>
                            <input type="password" class="form-control d-none" id="rpassword" name="rpassword"
                                autocomplete="new-password">
                            <div class="form-check d-flex align-items-center ms-2 d-none" id="pwd-toggle">
                                <input type="checkbox" class="form-check-input me-2" id="showpass">
                                <label class="form-check-label text-light user-select-none fs-5" for="showpass">Show
                                    Password</label>
                            </div>
                            <p class="fw-light fs-5 ms-1 d-none notes-toggle">Leave password blank if you want to retain
                                the
                                same password.</p>
                            <label for="birthdate" class="form-label fw-bold mb-0 text-shadow">Birthdate:</label>
                            <input type="date" class="form-control ps-2 d-none" id="birthdate" name="birthdate">
                            <p class="text-light ms-2 fs-5" id="displaybd"></p>


                            <button type="button" class="btn btn-grad mt-3" id="editbtn">Edit Information</button>
                            <button type="button" class="btn btn-grad d-none" data-bs-target="#confirm_modal"
                                data-bs-toggle="modal">Submit</button>
                        </div>
                    </div>
                    <div class="modal fade" id="confirm_modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title">
                                    <h1 class="modal-title fs-5">Configuration Confirmation</h1>
                                    <button type="button" class="btn ms-auto" data-bs-dismiss="modal"
                                        aria-label="Close"><i class="bi bi-x text-light"></i></button>
                                </div>
                                <div class="modal-body">
                                    <p class="fs-5 fw-medium text-dark">Confirm Changes? Enter password to continue.</p>
                                    <input type="password" autocomplete="new-password" class="form-control w-50 ps-2"
                                        name="confirm_pwd">
                                    <div class="alert alert-info text-center m-0 mt-2" style="display: none" id="alert">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" id="submitbtn" class="btn btn-grad">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
    <?php include('footer.links.php'); ?>
    <script>
        function show_toast(message) {
            $('#toastmsg').html(message);
            var toastid = $('#toast');
            var toast = new bootstrap.Toast(toastid);
            toast.show();
        }

        const dataUrl = "contents/acc.settings.data.php";
        let birthdate = flatpickr("#birthdate", {
            dateFormat: "F j, Y"
        });

        $(document).ready(async function () {
            await load_user();
        });

        async function load_user() {
            $.get(dataUrl, {
                acc: true,
                accountId: <?= $_SESSION['techId'] ?>
            })
                .done(function (d) {
                    // console.log(d);
                    let data = JSON.parse(d);
                    // console.log(data);
                    $('#fname').val(data.fname);
                    $('#lname').val(data.lname);
                    $("#username").val(data.usn);
                    $('#email').val(data.email);
                    $('#empid').text(data.empId);
                    birthdate.setDate(data.displaydate);
                    $('#displaybd').html(data.displaydate);
                    $('#id').val(data.id);
                    $("#address").val(data.address);
                })
                .fail(function (e) {
                    alert(e);
                })
                .always(function (e) {
                    console.log(e);
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
                // await toggle_input('empid'),
                await toggle_input('address')
            ]);
            $("#displaybd, #password, #rpassword, #oldpassword, #pwd-toggle, #passwordlabel, button[data-bs-target='#confirm_modal'], .pwd-label, #birthdate, .notes-toggle").toggleClass('d-none');
            $("#editbtn").html(function (i, a) {
                return a.includes('Edit Information') ? 'Close Editor' : 'Edit Information';
            })
        }

        $(document).on('change', '#showpass', function () {
            if ($(this).is(':checked')) {
                $("#password, #rpassword, #oldpassword").attr('type', 'text');
            } else {
                $("#password, #rpassword, #oldpassword").attr('type', 'password');
            }
        });


        $(document).on("show.bs.modal", '#confirm_modal', function () {
            $("#confirm_modal input[name='confirm_pwd']").val('');
        });

        $(document).on('click', "#editbtn", async function () {
            await toggle();
        });

        $(document).on('submit', '#accountsettings', async function (e) {
            // console.log($(this).serialize());
            e.preventDefault();
            $.ajax({
                url: dataUrl,
                method: 'POST',
                dataType: 'json',
                data: $(this).serialize() + "&editacc=true"
            })
                .done(async function (d) {
                    // alert(d.success);
                    toggle();
                    $("#alert").html(d.success).fadeIn(500).delay(2000).fadeOut(1000);
                    $("#nav_name").text(d.name);
                    $("#confirm_modal").modal('hide');
                })
                .fail(async function (e) {
                    console.log(e);
                    // alert(e);
                    $("#alert").html(e.responseText).fadeIn(500).delay(2000).fadeOut(1000);

                })
        });

        $(function () {
            var windowWidth = $(window).width();
            if (windowWidth <= 425) {
                $(".two-part-inputs div").toggleClass('w-100 col col-auto');
            }

            $(window).resize(function () {
                var windowWidth = $(window).width();
                if (windowWidth <= 425) {
                    $(".two-part-inputs div").addClass('w-100 col-auto').removeClass('col');
                } else {
                    $(".two-part-inputs div").removeClass('w-100 col-auto').addClass('col');
                }
            })
        })
    </script>

</body>


</html>