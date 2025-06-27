<?php
session_start();
if (isset($_SESSION["techId"]) || isset($_SESSION["baID"]) || isset($_SESSION["saID"])) {
    include("includes/functions.inc.php");
    $activeUser = activeUser();
    echo "<h1 class='display-6 mx-auto text-center'>You are already logged in as $activeUser.<br>Redirecting you back to dashboard...</h1>";
    include("footer.php");
    if (isset($_SESSION['saUsn'])) {
        header("Refresh: 3; url=superadmin/index.php");
        exit();
    } elseif (isset($_SESSION['baUsn'])) {
        header("Refresh: 3; url=os/index.php");
        exit();
    } elseif (isset($_SESSION['techUsn'])) {
        header("Refresh: 3; url=technician/index.php");
        exit();
    }
}


include("header.php");

?>

<body class="d-flex py-4 bg-body-tertiary bg-official-login">

    <div class="w-25 d-flex flex-column align-items-center container bg-light bg-opacity-25 rounded-4 shadow-lg">
        <form id="resetpass" class="w-100 d-flex flex-column mt-5">
            <img src="img/logo.svg" alt="logo" style="width: 6rem !important" class="mx-auto mb-3">
            <div class="mt-5 px-2">
                <h1 class="fs-2 fw-bold text-light text-center">New Password</h1>
                <p class="fw-light text-light text-center">Type your new password.</p>
                <div class="form-floating form-custom mb-2">
                    <input type="password" name="pwd" class="form-control" id="pwd" placeholder="Password"
                        autocomplete="one-time-code">
                    <label for="pwd">Password</label>
                </div>
                <div class="form-floating form-custom mb-2">
                    <input type="password" name="rpwd" class="form-control" id="rpwd" placeholder="Repeat Password"
                        autocomplete="one-time-code">
                    <label for="rpwd">Repeat Password</label>
                </div>
                <div class="form-check mb-2 ms-1 show-password">
                    <input type="checkbox" id="showpwd" class="form-check-input">
                    <label for="showpwd" class="form-check-label text-light align-middle ms-2">Show Password</label>
                </div>
                <button type="submit"
                    class="btn btn-form-submit bg-light bg-opacity-75 border px-3 py-2 w-100">Submit</button>
            </div>
            <p class="alert alert-info text-center mt-2 text-wrap" id="alert" style="display: none"></p>
        </form>
    </div>

</body>

<?= include('footer.php') ?>

<script>
    // console.log(params.has('token'));
    // console.log(params.get('token'));

    // check url params for token. Block inputs and submit button if tokenn is invalid or missing and redirect 
    $(document).ready(function() {
        var params = new URLSearchParams(window.location.search);
        $('#resetpass input, #resetpass button').prop('disabled', true);
        if (!params.has('token')) {
            $("#alert").html("Unrestricted Access. Redirecting . . .").fadeIn(500);
            setTimeout(() => {
                $(location).attr('href', "index.php?unrestrictedaccess");
            }, 3000);
        } else {
            const token = params.get('token');
            console.log(token);
            $.post('includes/resetpass.inc.php', {
                    token: token,
                    chktoken: true
                })
                .done(function(d) {
                    // console.log(d);
                    $('#resetpass input, #resetpass button').prop('disabled', false);
                })
                .fail(function(e) {
                    console.log(e);
                    setTimeout(() => {
                        $(location).attr('href', 'forgotpass.php?invalidtoken=true');
                    }, 3000);
                    $("#alert").html(e.responseText + " Redirecting . . .").fadeIn(500);
                })
        }
    });


    $(document).on('change', '#showpwd', function() {
        let checked = $(this).prop('checked');
        if (!checked) {
            $('.form-floating input').prop('type', 'password');
        } else {
            $('.form-floating input').prop('type', 'text');
        }
    })
    let timeout;
    // $(".form-floating input").unbind('keyup');
    $('.form-floating input').on('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            if ($('#pwd').val() != '' && $('#rpwd').val() != '') {
                if ($("#pwd").val() != $("#rpwd").val()) {
                    $(".form-floating input").addClass('border-danger');
                    $("button").prop('disabled', true);
                    $("#alert").fadeOut(1000).html('Passwords does not match.').fadeIn(500);
                } else {
                    $("button").prop('disabled', false);
                    $("#alert").html('Passwords does not match.').fadeOut(1000);
                }
            }
        }, 1000);
    })

    $(document).on('submit', '#resetpass', async function(e) {
        var params = new URLSearchParams(window.location.search);
        e.preventDefault();
        // console.log($(this).serialize());
        await $.ajax({
                method: "POST",
                url: "includes/resetpass.inc.php",
                dataType: "json",
                data: $(this).serialize() + "&newpass=true&token=" + params.get('token')
            })
            .done(function(d) {
                console.log(d);
                $('#alert').fadeOut(1000, function() {
                    $(this).html(d.success).fadeIn(500);
                });
                $("#resetpass")[0].reset();
                setTimeout(() => {
                    $(location).attr('href', 'login.php');
                }, 3000);
            })
            .fail(function(e) {
                console.log(e);
                $('#alert').fadeOut(1000, function() {
                    $(this).html(e.responseText).fadeIn(500);
                })
            })
    });
</script>