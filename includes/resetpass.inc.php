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
    // echo "now: $now exp $e exp $expiry";
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

        $mail->setFrom("noreply@gmail.com");
        $mail->addAddress($email);
        $mail->Subject = "Password Reset";
        $mail->Body = <<<END

        Click <a href = "localhost/Pestastic/resetpassword.php?token=$token">here</a> to reset your password.

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


if(isset($_POST['newpass']) && $_POST['newpass'] === 'true'){
    $token = $_POST['token'];
    $pwd = $_POST['pwd'];
    $rpwd = $_POST['rpwd'];

    if($pwd != $rpwd){
        http_response_code(400);
        echo "Passwords do not match.";
        exit();
    }

    $email = email_token($conn, $token);

    if(!$email){
        http_response_code(400);
        echo "Email not found. Make sure to double check the email you submitted.";
        exit();
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        http_response_code(400);
        echo "Invalid Email.";
        exit();
    }

    

    $reset = reset_password($conn, $pwd, $email, $token);

    if(isset($reset['error'])){
        http_response_code(400);
        echo $reset['error'];
        exit();
    }
}

if(isset($_POST['chktoken']) && $_POST['chktoken'] === 'true'){
    $token = $_POST['token'];

    $email = email_token($conn, $token);
    if($email){
        http_response_code(200);
        echo $email;
        exit();
    }
    http_response_code(400);
    return false;
}