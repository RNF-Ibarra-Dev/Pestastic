<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_POST["createacc"]) && $_POST['createacc'] === 'true') {

    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdRepeat"];
    $contactNo = $_POST["contactNo"];
    $empId = $_POST["empId"];
    $address = $_POST["address"];
    $birthdate = $_POST['birthdate'];
    $account = $_POST['account'];
    $branch = $_POST['branch'];
    $mpwd = $_POST['manager_pwd'];

    if (emptyInputSignup($firstName, $lastName, $username, $email, $pwd, $pwdRepeat) !== false) {
        http_response_code(400);
        echo "Please fill in all details.";
        exit();
    }
    if (invalidFirstName($firstName) !== false) {
        http_response_code(400);
        echo 'Invalid first name.';
        exit();
    }
    if (invalidLastName($lastName) !== false) {
        http_response_code(400);
        echo 'Invalid last name.';
        exit();
    }
    if (invalidUsername($username) !== false) {
        http_response_code(400);
        echo 'Invalid Username';
        exit();
    }

    if (invalidEmail($email) !== false) {
        http_response_code(400);
        echo 'Invalid email.';
        exit();
    }

    if (strlen($contactNo) !== 11) {
        http_response_code(400);
        echo 'Contact number should contain exactly 11 digits.';
        exit();
    }

    if (pwdMatch($pwd, $pwdRepeat) !== false) {
        http_response_code(400);
        echo 'Passwords do not match.';
        exit();
    }
    if (multiUserExists($conn, $username, $email) !== false) {
        http_response_code(400);
        echo 'User already exists';
        exit();
    }

    if (strlen($empId) !== 3) {
        http_response_code(400);
        echo 'Employee ID should only contain three digits.';
        exit();
    }

    if (employeeIdCheck($conn, $empId) !== false) {
        http_response_code(400);
        echo 'Employee ID already exist.';
        exit();
    }

    if (!validate($conn, $mpwd)) {
        http_response_code(400);
        echo "Invalid password.";
        exit();
    }

    if ($account === 'tech') {
        $create = createTechAccount($conn, $firstName, $lastName, $username, $email, $pwd, $contactNo, $address, $empId, $birthdate, $branch);
    } elseif ($account === 'os') {
        $create = createOpSupAccount($conn, $firstName, $lastName, $username, $email, $pwd, $contactNo, $address, $empId, $birthdate, $branch);
    } else {
        http_response_code(400);
        echo 'Invalid account type. Please try again.';
        exit();
    }

    if (isset($create['error'])) {
        http_response_code(400);
        echo 'Error: ' . $create['error'];
        exit();
    } else {
        echo json_encode(['success' => 'Account Created!']);
    }

}

if (isset($_POST["addmanager"]) && $_POST['addmanager'] === 'true') {

    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdRepeat"];
    $empId = $_POST["empId"];
    $birthdate = $_POST['birthdate'];
    $branch = $_POST['branch'];
    $mpwd = $_POST['manager_pwd'];

    if (emptyInputSignup($firstName, $lastName, $username, $email, $pwd, $pwdRepeat) !== false) {
        http_response_code(400);
        echo "Please fill in all details.";
        exit();
    }
    if (invalidFirstName($firstName) !== false) {
        http_response_code(400);
        echo 'Invalid first name.';
        exit();
    }
    if (invalidLastName($lastName) !== false) {
        http_response_code(400);
        echo 'Invalid last name.';
        exit();
    }
    if (invalidUsername($username) !== false) {
        http_response_code(400);
        echo 'Invalid Username';
        exit();
    }

    if (invalidEmail($email) !== false) {
        http_response_code(400);
        echo 'Invalid email.';
        exit();
    }

    if (pwdMatch($pwd, $pwdRepeat) !== false) {
        http_response_code(400);
        echo 'Passwords do not match.';
        exit();
    }
    if (multiUserExists($conn, $username, $email) !== false) {
        http_response_code(400);
        echo 'User already exists';
        exit();
    }

    if (strlen($empId) !== 3) {
        http_response_code(400);
        echo 'Employee ID should only contain three digits.';
        exit();
    }

    if (employeeIdCheck($conn, $empId) !== false) {
        http_response_code(400);
        echo 'Employee ID already exist.';
        exit();
    }

    if (!validate($conn, $mpwd)) {
        http_response_code(400);
        echo "Invalid password.";
        exit();
    }

    $create = createManager($conn, $firstName, $lastName, $username, $email, $pwd, $empId, $birthdate, $branch);

    if (isset($create['error'])) {
        http_response_code(400);
        echo 'Error: ' . $create['error'];
        exit();
    } else {
        echo json_encode(['success' => 'Account Created!']);
    }

}


if (isset($_GET['branches']) && $_GET['branches'] === 'true') {
    $sql = "SELECT * FROM branches;";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<option value='' selected class='text-dark'>Select User Branch</option>";
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row['name'];
            $loc = $row['location'];
            ?>
            <option value="<?= htmlspecialchars($id) ?>" class="text-dark"><?= htmlspecialchars("$name ($loc)") ?></option>
            <?php
        }
    } else {
        echo "<option value='' selected>No branches available</option>";
    }
}