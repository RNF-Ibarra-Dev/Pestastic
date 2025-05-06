<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$valid_ext = ['jpeg', 'jpg', 'png'];
$path = '../../uploads/';
$validavail = ['Available', 'Unavailable', 'In Repair'];

if (isset($_POST['add']) && $_POST['add'] === 'true') {
    $name = $_POST['eqname'];
    $avail = $_POST['avail'];
    $desc = $_POST['desc'];
    $pwd = $_POST['saPwd'];
    $eimg = strtolower(basename($_FILES['eimage']['name']));

    $ext = strtolower(pathinfo($eimg, PATHINFO_EXTENSION));

    if (empty($name)) {
        http_response_code(400);
        echo json_encode(['error' => "Empty equipment name."]);
        exit();
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => "Invalid Password."]);
        exit();
    }

    if (!in_array($avail, $validavail)) {
        $avail = 'Unavailable';
    }

    if (!in_array($ext, $valid_ext)) {
        http_response_code(400);
        echo json_encode(['error' => "Invalid Image. Only jpeg, jpg, and png are allowed."]);
        exit();
    }

    $newimg = uniqid('e_', true) . '-' . hash('sha256', $eimg) . '_' . date('y-m-d', time());
    $filename = $path . $newimg . '_' . $eimg;

    if (!is_uploaded_file($_FILES['eimage']['tmp_name'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Possible attack. Filename: ' . $_FILES['eimage']['tmp_name']]);
        exit();
    }


    $addeqp = addequipment($conn, $name, $desc, $avail, $filename);

    $add = json_decode($addeqp, true);

    if (isset($add['error'])) {
        http_response_code(400);
        echo $add['error'];
        exit();
    }

    if (!move_uploaded_file($_FILES['eimage']['tmp_name'], $filename)) {
        http_response_code(400);
        echo json_encode(['error' => 'Possible attack. Filename: ' . $_FILES['eimage']['tmp_name']]);
        exit();
    }

    http_response_code(200);
    echo json_encode(['success' => $add['success']]);

    exit();
}


if (isset($_POST['edit']) && $_POST['edit'] === 'true') {
    $name = $_POST['eqname'];
    $avail = $_POST['avail'];
    $desc = $_POST['desc'];
    $pwd = $_POST['saPwd'];
    $eid = $_POST['eid'];

    if (empty($name)) {
        http_response_code(400);
        echo json_encode(['error' => "Empty equipment name."]);
        exit();
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => "Invalid Password."]);
        exit();
    }

    if (!in_array($avail, $validavail)) {
        $avail = 'Unavailable';
    }

    // image handling:
    $fsize = $_FILES['eimage']['size'];

    if (!$fsize === 0) {
        $oldimg = $_POST['oldimgpath'];

        $eimg = strtolower(basename($_FILES['eimage']['name']));
        $ext = strtolower(pathinfo($eimg, PATHINFO_EXTENSION));

        $newimg = uniqid('e_', true) . '-' . hash('sha256', $eimg) . '_' . date('y-m-d', time());
        $filename = $path . $newimg . '_' . $eimg;

        if (!is_uploaded_file($_FILES['eimage']['tmp_name'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Possible attack. Filename: ' . $_FILES['eimage']['tmp_name']]);
            exit();
        }

        if (!in_array($ext, $valid_ext)) {
            http_response_code(400);
            echo json_encode(['error' => "Invalid Image. Only jpeg, jpg, and png are allowed."]);
            exit();
        }

        if (!move_uploaded_file($_FILES['eimage']['tmp_name'], $filename)) {
            http_response_code(400);
            echo json_encode(['error' => 'Possible attack. Filename: ' . $_FILES['eimage']['tmp_name']]);
            exit();
        }
        $update = update_equipment($conn, $name, $desc, $avail, $id, $filename);
    }else{
        $update = update_equipment($conn, $name, $desc, $avail, $id);
    }

    if(isset($update['error'])){
        http_response_code(400);
        echo json_encode(['error' => $update['error']]);
        exit();
    } else{
        if(!$fsize === 0){
            unlink($oldimg);
        }

    }

 

}