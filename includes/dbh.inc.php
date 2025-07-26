<?php

// localhost
$servername = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "pestastic_db";

// hosting
// $servername = "localhost";
// $dBUsername = "u877186800_pestastic_inv";
// $dBPassword = "Pestastic_inv1";
// $dBName = "u877186800_pestastic_db";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName); 

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
date_default_timezone_set('Asia/Manila');