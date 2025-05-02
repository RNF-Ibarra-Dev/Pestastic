<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$valid_ext = ['jpeg', 'jpg', 'png'];
$path = '../../uploads/';

if (isset($_POST['add']) && $_POST['add'] === 'true') {
    $name = $_POST['eqname'];
    $avail = $_POST['avail'];
    $desc = $_POST['desc'];

    $filename = $path . basename($_FILES['eimage']['name']);

    if(!is_uploaded_file($_FILES['eimage']['tmp_name'])){
        http_response_code(400);
        echo json_encode(['error' => 'Possible attack. Filename: ' . $_FILES['eimage']['tmp_name']]);
        exit();
    }

    if(!move_uploaded_file($_FILES['eimage']['tmp_name'], $filename)){
        http_response_code(400);
        echo json_encode(['error' => 'Possible attack. Filename: ' . $_FILES['eimage']['tmp_name']]);
        exit();
    }
}
