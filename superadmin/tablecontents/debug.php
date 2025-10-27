<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");

$email_test = email_manager_alert($conn, '', 'test');
if (isset($email_test['error'])) {
    http_response_code(400);
    echo $email_test['error'];
    exit;
} else if ($email_test) {
    echo "Email sent.";
    exit;
} else {
    http_response_code(400);
    echo "unknown error.";
    exit;
}