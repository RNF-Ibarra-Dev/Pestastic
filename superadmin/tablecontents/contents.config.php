<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");


if (isset($_POST['add-treatment']) && $_POST['add-treatment'] === 'true') {
    $trt = $_POST['treatment'];
    $branch = $_POST['trtmnt_branch'];
    $pwd = $_POST['trtmnt_pwd'];

    $trt = trim($trt);

    if (!preg_match("/^[a-zA-Z\s'-]*$/", $trt)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Treatment Name.']);
        exit();
    }

    if (empty($trt)) {
        http_response_code(400);
        echo json_encode(['error' => "Empty Treatment Name."]);
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
        echo json_encode(['success' => "Treatment $trt Added."]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.']);
        exit();
    }
}

if (isset($_POST['edit']) && $_POST['edit'] === 'true') {
    $trt = trim($_POST['treatment']);
    $branch = $_POST['branch'];
    $pwd = $_POST['pwd'];
    $id = $_POST['id'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Treatment ID.' . $id]);
        exit();
    }

    if (!preg_match("/^[a-zA-Z\s'-]*$/", $trt)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Treatment Name.']);
        exit();
    }

    if (empty($trt)) {
        http_response_code(400);
        echo json_encode(['error' => "Empty Treatment Name."]);
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

    $edit = edit_treatment($conn, $trt, $branch, $id);
    if (isset($edit['error'])) {
        http_response_code(400);
        echo $edit['error'] . ' at line ' . $edit['line'] . ' at file ' . $edit['file'];
        exit();
    } elseif ($edit) {
        http_response_code(200);
        echo json_encode(['success' => "Treatment $trt Added."]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.']);
        exit();
    }
}

if (isset($_POST['delete']) && $_POST['delete'] === 'true') {
    $ids = $_POST['trt_chk'] ?? [];
    $pwd = $_POST['pwd'];

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => "Incorrect Password."]);
        exit();
    }

    $delete = delete_treatment($conn, $ids);
    if (isset($delete['error'])) {
        http_response_code(400);
        echo $delete['error'] . ' at line ' . $delete['line'] . ' at file ' . $delete['file'];
        exit();
    } elseif ($delete) {
        http_response_code(200);
        echo json_encode(['success' => "Treatment/s Deleted"]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.' . ' ' . $delete]);
        exit();
    }
}

if (isset($_POST['addProb']) && $_POST['addProb'] === 'true') {
    $prob = $_POST['prob'] ?? [];
    $pwd = $_POST['pwd'];

    for ($i = 0; $i < count($prob); $i++) {
        if (!preg_match("/^[a-zA-Z\s'-]*$/", $prob[$i])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid Treatment Name ' . $prob[$i]]);
            exit();
        }
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Password.']);
        exit();
    }

    $add = add_problem($conn, $prob);
    if (isset($add['error'])) {
        http_response_code(400);
        echo $delete['error'] . ' at line ' . $add['line'] . ' at file ' . $add['file'];
        exit();
    } elseif ($add) {
        http_response_code(200);
        echo json_encode(['success' => "Treatment/s Deleted"]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.' . ' ' . $add]);
        exit();
    }

}