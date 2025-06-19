<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");


if (isset($_POST['add-treatment']) && $_POST['add-treatment'] === 'true') {
    $trt = $_POST['treatment'];
    $branch = $_POST['trtmnt_branch'];
    $pwd = $_POST['trtmnt_pwd'];

    if (empty($trt)) {
        http_response_code(400);
        echo json_encode(['error' => "Invalid Treatment Name."]);
        exit();
    }
    $branches = get_branches_array($conn);

    if (!in_array($branch, $branches)) {
        http_response_code(400);
        echo json_encode(['error' => "Invalid Branch ID."]);
        exit();
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => "Incorrect Password."]);
        exit();
    }

    $add = add_treatment($conn, $trt, $branch);
    if (isset($add['error'])) {
        http_response_code(400);
        echo $add['error'] . ' at line ' . $add['line'] . ' at file ' . $add['file'];
        exit();
    } elseif ($add) {
        http_response_code(200);
        echo json_encode(['success' => 'FUFUUFUF SUCCES']);
        exit();
    } else {
        http_response_code(400);
        json_encode(['error' => 'Unknown Error Occured.']);
        exit();
    }


}