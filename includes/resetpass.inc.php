<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST['reset']) && $_POST['reset'] === 'true') {
    $email = $_POST['email'];

    if (empty($email)) {
        http_response_code(400);
        echo "Email input empty.";
        exit();
    }

    if (!filter_var($email, FILTER_SANITIZE_EMAIL)) {
        http_response_code(400);
        echo 'Invalid Email.';
        exit();
    }

    $emailexist = check_email($conn, $email);
    if (!$emailexist) {
        http_response_code(400);
        echo "Email not found in our database." . $emailexist;
        exit();
    }

    $token = bin2hex(random_bytes(16));

    $token_hash = hash('sha256', $token);

    $now = time();

    // check for existing reset pass expiry
    $expiry = check_expiry($conn, $email);
    $e = $expiry ? strtotime($expiry) : false;

    // if none, set new expiry
    if (!$expiry || ($now >= $e)) {
        $expiry = date("Y-m-d H:i:s", time() + 60);
    } else {
        http_response_code(400);
        echo "Your link is still valid. Please check your inbox.";
        exit();
    }

    // http_response_code(400);
    // echo date('Y-m-d H:i:s',$now) . ' ' . date('Y-m-d H:i:s',$e) . ' ' . date('Y-m-d H:i:s', strtotime($expiry));
    // exit;

    $sql = "INSERT INTO reset_password
                (email, reset_token_hash,
                reset_token_expires_at) 
            VALUES(?, ?, ?)
            ON DUPLICATE KEY UPDATE
            reset_token_hash = VALUES(reset_token_hash),
            reset_token_expires_at = VALUES(reset_token_expires_at);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "stmt failed.";
        exit();
    }


    mysqli_stmt_bind_param($stmt, 'sss', $email, $token_hash, $expiry);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {

        $mail = require __DIR__ . "/../mailer.php";

        $mail->setFrom("noreply@gmail.com", "Pestastic Inventory");
        $mail->addAddress($email);
        $mail->Subject = "Password Reset";
        $mail->Body = <<<END

        <div style="text-align: center;">
            <img src="https://pestastic-inventory.site/img/pestastic.logo.jpg" alt="logo" style="width: 12rem !important">
        </div>
        <br>

        <b>Greetings!</b>
        <p>We received a request to reset your password for your Pestastic Inventory account.</p>

<<<<<<< HEAD
        Click <a href="https://Pestastic-inventory.site/resetpassword.php?token=$token_hash">here</a> to reset your password.
=======
        <p>Please click <a href="https://Pestastic-inventory.site/resetpassword.php?token=$token_hash">here</a> to reset your password.</p>
>>>>>>> main

        <br><br>
        <p><i>If you did not request a password reset, please ignore this email.</i></p>

        <p><i>Note: <br>   
        This link will expire in 1 minute for your security.</i></p>

        <img src="https://pestastic-inventory.site/img/logo.png" alt="logo" style="width: 4rem !important"><br>
        Thank you,<br>
        <b>Pestastic Team</b>

        END;

        try {
            $mail->send();
        } catch (Exception $e) {
            http_response_code(400);
            echo "Failed to send to email. {$mail->ErrorInfo}";
            exit();
        }
        http_response_code(200);
        echo json_encode(['success' => 'Email sent, please check your inbox.']);
        exit();
    } else {
        http_response_code(400);
        echo "Unknown Error.";
        exit();
    }
}


if (isset($_POST['newpass']) && $_POST['newpass'] === 'true') {
    $token = $_POST['token'];
    $pwd = $_POST['pwd'];
    $rpwd = $_POST['rpwd'];

    if ($pwd != $rpwd) {
        http_response_code(400);
        echo "Passwords do not match.";
        exit();
    }

    if (strlen($pwd) < 8) {
        http_response_code(400);
        echo "Password should be at least 8 characters.";
        exit();
    }

    if (!preg_match("/[a-zA-Z]/i", $pwd)) {
        http_response_code(400);
        echo "Password should contain at least one each of uppercase and lowercase letter.";
        exit();
    }

    if (!preg_match("/[0-9]/", $pwd)) {
        http_response_code(400);
        echo "Password should contain numbers";
        exit();
    }

    $email = email_token($conn, $token);

    if (!$email) {
        http_response_code(400);
        echo "Email not found. Make sure to double check the email you submitted.";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid Email.";
        exit();
    }

    $hpwd = password_hash($pwd, PASSWORD_DEFAULT);

    // if(password_verify($pwd, $hpwd)){
    //     http_response_code(400);
    //     echo 'verified';
    //     exit();
    // }

    $reset = reset_password($conn, $hpwd, $email, $token);

    if (isset($reset['error'])) {
        http_response_code(400);
        echo $reset['error'];
        exit();
    } elseif ($reset) {
        http_response_code(200);
        echo json_encode(['success' => 'New password set! Redirecting you to login page.']);
        exit();
    } else {
        http_response_code(400);
        echo "unknown error. $reset";
        exit();
    }
}

if (isset($_POST['chktoken']) && $_POST['chktoken'] === 'true') {
    $token = $_POST['token'];

    $email = email_token($conn, $token);
    if ($email) {
        http_response_code(200);
        $checkexpiry = check_expiry($conn, $email);
        $now = time();
        $expiry = $checkexpiry ? strtotime($checkexpiry) : false;
        if (!$expiry || ($now >= $expiry)) {
            http_response_code(400);
            echo "Token Expired.";
            exit();
        }
        http_response_code(200);
        return true;
    }

    http_response_code(400);
    echo "Token Expired.";
}
