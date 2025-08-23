<?php

// ini_set("session.use_only_cookies", "1");
// ini_set("session.use_strict_mode", "1");

// session_set_cookie_params([
//     'lifetime' => 1800,
//     // use website url when up
//     'domain' => 'pestastic-inventory.site',
//     'path' => '/',
//     'secure' => true,
//     'httponly' => true,
// ]);

session_start();

session_regenerate_id(true);
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION["saID"])) {
    require_once('header.links.php');
    ?>
     <div class='d-flex flex-column align-items-center w-100 text-light justify-content-center h-100'>
        <div class="flex-row my-4">
            <div class="spinner-grow spinner-grow-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow spinner-grow-sm mx-4" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow spinner-grow-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <h1 class='display-6 fw-medium'>Access Denied.</h1>
        <p class='text-light fw-light'>Unauthorized user accessing authorized content. Taking you back.</p>
    </div>
    <?php
    header("refresh: 2; url=../index.php?error=unauthorized_access");
    exit();
}
?>