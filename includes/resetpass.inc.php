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

    $expiry = date("Y-m-d H:i:s", time() + 60 * 5);
    // http_response_code(400);
    // echo time();
    // exit();

    if (time() > $expiry) {
        http_response_code(400);
        echo "Your token has not expired yet. Please check your email.";
        exit();
    }

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

        $mail->setFrom("noreply@example.com");
        $mail->addAddress($email);
        $mail->Subject = "Password Reset";
        $mail->Body = <<<END

        Click <a href = "localhost/resetpassword.php?token=$token">here</a> to reset your password.

        END;

        try {
            $mail->send();
        } catch (Exception $e) {
            http_response_code(400);
            echo "Failed to send to email. Error: {$mail->ErrorInfo}";
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
