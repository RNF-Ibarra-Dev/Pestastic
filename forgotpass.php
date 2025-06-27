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

    <div
        class="w-25 d-flex flex-column align-items-center container bg-light bg-opacity-25 rounded-4 shadow-lg">
        <form id="resetpass" class="w-100 mt-5 h-100 d-flex flex-column">
            <img src="img/logo.svg" alt="logo" style="width: 6rem !important" class="mx-auto mb-3">
            <div class="px-2 mt-5">
                <h1 class="fs-2 fw-bold text-light text-center">Reset Password</h1>
                <p class="fw-light text-light text-center">Please provide the email of the account. The mail will
                    contain the link to reset your password.</p>
                <div class="form-floating form-custom mb-2">
                    <input type="text" name="email" class="form-control" id="email" placeholder="Email">
                    <label for="email">Email</label>
                </div>
                <button type="submit" class="btn btn-form-submit bg-light bg-opacity-75 border px-3 py-2 w-100">Send
                    Link</button>
            </div>
            <div class="d-flex mt-4 text-light justify-content-center">
                <div class=" spinner-border" role="status" id="spinner" style="display: none ;">
                </div>
            </div>
            <p class=" alert alert-info text-center mt-2 mx-auto" id="alert" style="display: none">
            </p>
        </form>
        <a href="login.php" class="btn btn-form-submit bg-dark bg-opacity-50 text-light border px-3 py-2 mb-3 mx-2">Go
            back
            and log in</a>
    </div>

</body>

<?= include('footer.php') ?>

<script>
    $(document).on('submit', '#resetpass', function (e) {
        e.preventDefault();
        const data = $(this).serialize() + "&reset=true";
        $("#resetpass input#email, #resetpass button").prop('disabled', true);
        $('#spinner').show();
        $.ajax({
            method: "POST",
            url: 'includes/resetpass.inc.php',
            dataType: 'json',
            data: data
        })
            .done(function (d) {
                console.log(d);
                $("#alert").fadeOut(500).html(d.success).fadeIn(750);
            })
            .fail(function (e) {
                console.log(e);
                $("#alert").fadeOut(500).html(e.responseText).fadeIn(750);
            })
            .always(function () {
                $("#resetpass")[0].reset();
                $("#resetpass input, #resetpass button").prop('disabled', false);
                $('#spinner').hide();
            })
    });

    $(document).ready(function () {
        const param = new URLSearchParams(window.location.search);
        if (param.get('invalidtoken') == 'true') {
            $('#alert').html('Token Invalid/Expired. Please request for another link.').fadeIn(750);
        }
    })
</script>