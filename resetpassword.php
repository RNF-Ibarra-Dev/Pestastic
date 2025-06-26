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
        <form id="resetpass" class="h-75 d-flex flex-column">
            <div class="px-2 mt-5 d-flex flex-column">
                <img src="img/logo.svg" alt="logo" style="width: 6rem !important" class="mx-auto mb-3">
                <h1 class="fs-2 fw-bold text-light text-center">New Password</h1>
                <p class="fw-light text-light text-center">Type your new password.</p>
                <div class="form-floating form-custom" style="margin-bottom: -1rem;">
                    <input type="password" name="pwd" class="form-control" id="pwd" placeholder="Password" autocomplete="new-password">
                    <i class="bi bi-eye-slash text-light eye-slash-pwd" id="pwdtoggle"></i>
                    <label for="pwd">Password</label>
                </div>
                <div class="form-floating form-custom ">
                    <input type="password" name="email" class="form-control" id="rpwd" placeholder="Repeat Password" autocomplete="new-password">
                    <i class="bi bi-eye-slash text-light eye-slash-pwd" id="rpwdtoggle"></i>
                    <label for="rpwd">Repeat Password</label>
                </div>
                <button type="submit"
                    class="btn btn-form-submit bg-light bg-opacity-75 border px-3 py-2 w-100">Submit</button>

            </div>
            <p class="alert alert-info text-center mt-2" id="alert" style="display: none"></p>
        </form>
    </div>

</body>

<script>
    $(document).on('submit', '#resetpass', async function(e){
        e.preventDefault();
        await $.ajax({
            method: "POST",
            url: "includes/resetpass.inc.php",
            dataType: "json",
            data: $(this).serialize() + "&newpass=true"
        })
        .done(function(d){

        })
        .fail(function(e){
            
        })
    })
</script>

<?= include('footer.php') ?>