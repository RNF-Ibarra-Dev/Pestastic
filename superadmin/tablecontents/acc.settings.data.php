<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");

if (isset($_GET['acc']) && $_GET['acc'] === 'true') {
    $id = $_GET['accountId'];

    if ($id != $_SESSION['saID']) {
        http_response_code(400);
        echo "Invalid Session ID. Refresh page and try again.";
        exit();
    }

    $sql = "SELECT * FROM superadmin where saID = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "Account information stmt error.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $accountData = [];

    if (mysqli_num_rows($result) > 0) {
        if ($row = mysqli_fetch_assoc($result)) {
            $date = $row['saBirthdate'];
            $d = date_create($date);
            $dd = date_format($d, "F j, Y");
            $accountData['displaydate'] = $dd;
            $accountData['usn'] = $row['saUsn'];
            $accountData['fname'] = $row['saName'];
            $accountData['lname'] = $row['saLName'];
            $accountData['email'] = $row['saEmail'];
            $accountData['pwd'] = $row['saPwd'];
            $accountData['birthdate'] = $row['saBirthdate'];
            $accountData['branch'] = $row['user_branch'];
            $accountData['empId'] = $row['saEmpId'];
            $accountData['id'] = $row['saID'];
        }
    } else {
        http_response_code(400);
        echo "No returned data. Error.";
        exit();
    }
    echo json_encode($accountData);
    mysqli_stmt_close($stmt);
    exit();
}

if (isset($_POST['editacc']) && $_POST['editacc'] === 'true') {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $opwd = $_POST['oldpassword'];
    $pwd = $_POST['password'];
    $rpwd = $_POST['rpassword'];
    $bd = $_POST['birthdate'];
    $empid = $_POST['empid'];

    if(empty($fname) || empty($lname) || empty($email) || empty($bd) ||empty($empid) || empty($username)){
        http_response_code(400);
        echo "Incomplete inputs. Please confirm and submit again.";
        exit();
    }

    if ($id != $_SESSION['saID']) {
        http_response_code(400);
        echo "Invalid session ID. Refresh page and try again. " . $id . ' ' . $_SESSION['saID'];
        exit();
    }

    if(!empty($pwd) || !empty($opwd) || !empty($rpwd)){
        if(!validate($conn, $opwd)){
            http_response_code(400);
            echo "Incorrect old password.";
            exit();
        }
    }

    $birthdate = date_create($bd);
    $bdd = date_format($birthdate, "Y-m-d");

    if (invalidFirstName($fname)) {
        http_response_code(400);
        echo "First name contains invalid characters.";
        exit();
    }

    if (invalidLastName($lname)) {
        http_response_code(400);
        echo "Last name contains invalid characters.";
        exit();
    }

    if (invalidEmail($email)) {
        http_response_code(400);
        echo "Invalid email.";
        exit();
    }

    if (invalidUsername($username)) {
        http_response_code(400);
        echo "Invalid username.";
        exit();
    }

    if (pwdMatch($pwd, $rpwd)) {
        http_response_code(400);
        echo "Passwords do not match.";
        exit();
    }

    $existing_user = multiUserExists($conn, $username, $email);

    if ($existing_user) {
        if ($existing_user['saUsn'] != $_SESSION['saUsn'] && $existing_user['saID'] != $_SESSION['saID']) {
            http_response_code(400);
            echo "User already exists";
            exit();
        }
    }

    if ($empfunc = invalid_emp_id($conn, $empid)) {
        if ($empfunc['saUsn'] != $_SESSION['saUsn'] && $empfunc['saID'] != $_SESSION['saID']) {
            http_response_code(400);
            echo "User already exists";
            exit();
        }
    }

    $edit = modify_sa($conn, $fname, $lname, $username, $email, $pwd, $bdd, $empid, $id);

    if (isset($edit['error'])) {
        http_response_code(400);
        echo $edit['error'];
        exit();
    } elseif ($edit) {
        http_response_code(200);
        echo json_encode(['success' => "Account Modified."]);
        exit();
    } else {
        http_response_code(400);
        echo "unknown error.";
        exit();
    }
}
