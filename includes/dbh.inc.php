<?php

$servername = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "pestastic_db";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName); 

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
date_default_timezone_set('Asia/Manila');