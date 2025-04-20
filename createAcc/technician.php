<?php
include("../header.php");
?>



<main class="form-signin w-100 m-auto rounded-3" style="background-color: gainsboro">
    <form class="d-flex flex-column" action="../includes/createaccount.inc.php" method="post">
        <img src="../img/pestastic.logo.jpg" alt width="75" height="75" class="mb-4 align-self-center rounded">
        <h1 class="h3 mb-3 fw-normal text-center">Create New Technician Account</h1>
        <div class="form-floating mb-2">
            <input type="text" name="firstName" class="form-control" id="floatingInput" placeholder="First Name...">
            <label for="floatingInput">First Name</label>
        </div>
        <div class="form-floating mb-2">
            <input type="text" name="lastName" class="form-control" id="floatingInput" placeholder="Second Name...">
            <label for="floatingInput">Last Name</label>
        </div>
        <div class="form-floating mb-2">
            <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Username...">
            <label for="floatingInput">Username</label>
        </div>
        <div class="form-floating mb-2">
            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="Email...">
            <label for="floatingInput">Email Address</label>
        </div>
        <div class="form-floating mb-2">
            <input type="password" name="pwd" class="form-control" id="floatingPassword" placeholder="******">
            <label for="floatingPassword">Password</label>
        </div>
        <div class="form-floating mb-2">
            <input type="password" name="pwdRepeat" class="form-control" id="floatingPassword" placeholder="******">
            <label for="floatingPassword">Repeat Password</label>
        </div>

        <button class="btn btn-primary w-100" type="submit" name="submitCreateAcc">Create Account</button>
    

        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<p class='text-center'>All fields must be filled.</p>";
            } elseif ($_GET["error"] == "invalidusername") {
                echo "<p class='text-center'>Username not valid or it might be taken. Choose another username.</p>";
            } elseif ($_GET["error"] == "invalidfirstname" || $_GET["error"] == "invalidlastname") {
                echo "<p class='text-center'>Numbers are not allowed for names.</p>";
            } elseif ($_GET["error"] == "invalidemail") {
                echo "<p class='text-center'>Email not valid. Choose a valid email.</p>";
            } elseif ($_GET["error"] == "passwordsdontmatch") {
                echo "<p class='text-center'>Passwords do not match.</p>";
            } elseif ($_GET["error"] == "useralreadyexist") {
                echo "<p class='text-center'>User already exists!</p>";
            } elseif ($_GET["error"] == "stmtfailed") {
                echo "<p class='text-center'>Something went wrong, try again.</p>";
            } elseif ($_GET["error"] == "none") {
                echo "<p class='text-center'>Account created.</p>";
            }
        }
        ?>

    </form>
</main>

<?php
include("../footer.php");
?>