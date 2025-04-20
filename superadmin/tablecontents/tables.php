<?php
// session_start();
require_once("../includes/dbh.inc.php");
require_once('../includes/functions.inc.php');
$pageRows = 8;
$rowCount = 'SELECT * FROM chemicals';
$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);


// include 'tablcontents/pagination.php';
?>

