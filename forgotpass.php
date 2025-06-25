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
        class="w-25 d-inline-flex flex-column align-items-center justify-content-center container bg-light bg-opacity-25 rounded-4 shadow-lg">
        <form id="resetpass">
            <div class="px-2 my-auto d-flex flex-column">
                <img src="img/logo.svg" alt="logo" style="width: 6rem !important" class="mx-auto mb-3">
                <h1 class="fs-2 fw-bold text-light text-center">Reset Password</h1>
                <p class="fw-light text-light text-center">Please provide the email of the account.</p>
                <div class="form-floating form-custom mb-2">
                    <input type="text" name="email" class="form-control" id="floatingInput"
                        placeholder="Email">
                    <label for="floatingInput">Email</label>
                </div>
                <p class="alert alert-info text-center" id="alert" style="display: none">aslkjd</p>
                <button type="submit" class="btn btn-sidebar text-light border px-3 py-2 w-100">Send Email</button>

            </div>
        </form>
    </div>

</body>

<?= include('footer.php') ?>

<script>
    $(document).on('submit', '#resetpass', function(e) {
        e.preventDefault();
        $.ajax({
                method: "POST",
                url: 'includes/resetpass.inc.php',
                dataType: 'json',
                data: $(this).serialize() + "&reset=true"
            })
            .done(function(d) {
                console.log(d);
            })
            .fail(function(e) {
                console.log(e);
                $("#alert").html(e.responseText).fadeIn(750).delay(2000).fadeOut(1000);
            })
    })
</script>