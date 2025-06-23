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

// pest problems

if (isset($_POST['addProb']) && $_POST['addProb'] === 'true') {
    $prob = $_POST['prob'] ?? [];
    $pwd = $_POST['pwd'];

    if (count(array_unique($prob)) != count($prob)) {
        http_response_code(400);
        echo json_encode(['error' => 'Duplicate Pest Problem Names.']);
        exit();
    }

    for ($i = 0; $i < count($prob); $i++) {
        $prob[$i] = trim($prob[$i]);
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
        echo $add['error'] . ' at line ' . $add['line'] . ' at file ' . $add['file'];
        exit();
    } elseif ($add) {
        http_response_code(200);
        echo json_encode(['success' => "Pest Problems Added."]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.' . ' ' . $add]);
        exit();
    }
}

if (isset($_POST['editprob']) && $_POST['editprob'] === 'true') {
    $id = $_POST['pid'];
    $prob = trim($_POST['prob']);
    $pwd = $_POST['pwd'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid ID.']);
        exit();
    }

    if (!preg_match("/^[a-zA-Z\s'-]*$/", $prob)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Pest Problem Name.']);
        exit();
    }

    if (empty($prob)) {
        http_response_code(400);
        echo json_encode(['error' => 'Name should not be empty.']);
        exit();
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Password.']);
        exit();
    }

    $edit = edit_pprob($conn, $id, $prob);
    if (isset($edit['error'])) {
        http_response_code(400);
        echo $edit['error'] . ' at line ' . $edit['line'] . ' at file ' . $edit['file'];
        exit();
    } elseif ($edit) {
        http_response_code(200);
        echo json_encode(['success' => "Pest Problem $prob Updated."]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.']);
        exit();
    }
}


if (isset($_POST['deleteprob']) && $_POST['deleteprob'] === 'true') {
    $prob = $_POST['prob_chk'] ?? [];
    $pwd = $_POST['pwd'];

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => "Incorrect Password."]);
        exit();
    }

    $delete = delete_pprob($conn, $prob);
    if (isset($delete['error'])) {
        http_response_code(400);
        echo $delete['error'] . ' at line ' . $delete['line'] . ' at file ' . $delete['file'];
        exit();
    } elseif ($delete) {
        http_response_code(200);
        $pprob = count($prob) == 1 ? "pest problem" : "pest problems";
        echo json_encode(['success' => "Selected $pprob Deleted."]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.' . ' ' . $delete]);
        exit();
    }
}

// branches

if (isset($_POST['branchadd']) && $_POST['branchadd'] === 'true') {
    $branch = $_POST['branch'] ?? [];
    $location = $_POST['location'] ?? [];
    $pwd = $_POST['pwd'];

    if (count(array_unique($branch)) != count($branch)) {
        http_response_code(400);
        echo json_encode(['error' => 'Duplicate Branch Name.']);
        exit();
    }

    for ($i = 0; $i < count($branch); $i++) {
        $branch[$i] = trim($branch[$i]);
        if (!preg_match("/^[a-zA-Z\s'-]*$/", $branch[$i])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid Branch Name ' . $branch[$i]]);
            exit();
        }
    }

    if (empty($branch) || empty($location)) {
        http_response_code(400);
        echo json_encode(['error' => 'Inputs should not be empty.']);
        exit();
    }

    for ($i = 0; $i < count($location); $i++) {
        $location[$i] = trim($location[$i]);
        if (!preg_match("/^[a-zA-Z0-9.\s'-]*$/", $location[$i])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid Location Name ' . $location[$i]]);
            exit();
        }
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Password.']);
        exit();
    }

    $add = add_branch($conn, $branch, $location);
    if (isset($add['error'])) {
        http_response_code(400);
        echo $add['error'] . ' at line ' . $add['line'] . ' at file ' . $add['file'];
        exit();
    } elseif ($add) {
        http_response_code(200);
        echo json_encode(['success' => "New Branch Added."]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.' . ' ' . $add]);
        exit();
    }
}

if (isset($_POST['branchedit']) && $_POST['branchedit'] === 'true') {
    $id = $_POST['id'];
    $branch = trim($_POST['branch']);
    $location = trim($_POST['location']);
    $pwd = $_POST['pwd'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid ID.']);
        exit();
    }

    if (!preg_match("/^[a-zA-Z\s'-]*$/", $branch)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Branch Name ' . $branch]);
        exit();
    }

    if (!preg_match("/^[a-zA-Z0-9.\s'-]*$/", $location)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Location Name ' . $location]);
        exit();
    }

    if (empty($branch) || empty($location)) {
        http_response_code(400);
        echo json_encode(['error' => 'Inputs should not be empty.']);
        exit();
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Password.']);
        exit();
    }

    $edit = edit_branch($conn, $id, $branch, $location);
    if (isset($edit['error'])) {
        http_response_code(400);
        echo $edit['error'] . ' at line ' . $edit['line'] . ' at file ' . $edit['file'];
        exit();
    } elseif ($edit) {
        http_response_code(200);
        echo json_encode(['success' => "Branch Updated."]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.']);
        exit();
    }
}

if (isset($_POST['branchdelete']) && $_POST['branchdelete'] === 'true') {
    $id = $_POST['branch'] ?? [];
    $pwd = $_POST['pwd'];

    for ($i = 0; $i < count($id); $i++) {
        if (!is_numeric($id[$i])) {
            http_response_code(400);
            echo json_encode(['error' => "Invalid ID $id."]);
            exit();
        }
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => "Incorrect Password."]);
        exit();
    }

    $delete = delete_branch($conn, $id);
    if (isset($delete['error'])) {
        http_response_code(400);
        echo $delete['error'] . ' at line ' . $delete['line'] . ' at file ' . $delete['file'];
        exit();
    } elseif ($delete) {
        http_response_code(200);
        $branch = count($id) == 1 ? "branch" : "branches";
        echo json_encode(['success' => "Selected $branch Deleted."]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.' . ' ' . $delete]);
        exit();
    }
}

if (isset($_POST['addpackage']) && $_POST['addpackage'] === 'true') {
    $name = $_POST['name'] ?? [];
    $session = $_POST['session'] ?? [];
    $warranty = $_POST['warranty'] ?? [];
    $branch = $_POST['branch'] ?? [];
    $treatment = $_POST['treatment'] ?? [];
    $pwd = $_POST['pwd'];

    // echo var_dump($name);
    // exit;

    for ($i = 0; $i < count($session); $i++) {
        if (empty($name[$i]) || empty($session[$i]) || empty($warranty[$i]) || empty($branch[$i]) || empty($treatment[$i])) {
            http_response_code(400);
            echo json_encode(['error' => "Fields should not be empty."]);
            exit();
        }
        if (!is_numeric($session[$i])) {
            http_response_code(400);
            echo json_encode(['error' => "Invalid session $session[$i]."]);
            exit();
        }
        if (!is_numeric($warranty[$i])) {
            http_response_code(400);
            echo json_encode(['error' => "Invalid warranty $warranty[$i]."]);
            exit();
        }
        if (!is_numeric($treatment[$i])) {
            http_response_code(400);
            echo json_encode(['error' => "Invalid treatment ID $treatment[$i]."]);
            exit();
        }
        if (!is_numeric($branch[$i])) {
            http_response_code(400);
            echo json_encode(['error' => "Invalid branch ID $branch[$i]."]);
            exit();
        }
    }

    for ($i = 0; $i < count($name); $i++) {
        $name[$i] = trim($name[$i]);
        if (!preg_match("/^[a-zA-Z0-9\s'-]*$/", $name[$i])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid Package Name ' . $name[$i]]);
            exit();
        }
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Password.']);
        exit();
    }

    $add = add_package($conn, $name, $session, $warranty, $branch, $treatment);
    if (isset($add['error'])) {
        http_response_code(400);
        echo $add['error'] . ' at line ' . $add['line'] . ' at file ' . $add['file'];
        exit();
    } elseif ($add) {
        http_response_code(200);
        $package = count($name) == 1 ? 'Package' : 'Packages';
        echo json_encode(['success' => "New $package Added."]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.' . ' ' . $add]);
        exit();
    }
}

if (isset($_POST['packageedit']) && $_POST['packageedit'] === 'true') {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $warranty = $_POST['warranty'];
    $session = $_POST['session'];
    $branch = $_POST['branch'];
    $treatment = $_POST['treatment'];
    $id = $_POST['id'];

    $pwd = $_POST['pwd'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Package ID.']);
        exit();
    }
    if (!is_numeric($warranty)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid warranty count.']);
        exit();
    }
    if (!is_numeric($session)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid session count.']);
        exit();
    }
    if (!is_numeric($branch)) {
        http_response_code(400);
        echo json_encode(['error' => 'Branch ID not recognized.']);
        exit();
    }
    if (!is_numeric($treatment)) {
        http_response_code(400);
        echo json_encode(['error' => 'Treatment ID not recognized.']);
        exit();
    }

    if (!preg_match("/^[a-zA-Z0-9\s'-]*$/", $name)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Package Name ' . $name]);
        exit();
    }

    if (empty($branch) || empty($name) || empty($warranty) || empty($session) || empty($treatment)) {
        http_response_code(400);
        echo json_encode(['error' => 'Inputs should not be empty.']);
        exit();
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid Password.']);
        exit();
    }

    $edit = edit_package($conn, $id, $branch, $name, $warranty, $session, $treatment);
    if (isset($edit['error'])) {
        http_response_code(400);
        echo $edit['error'] . ' at line ' . $edit['line'] . ' at file ' . $edit['file'];
        exit();
    } elseif ($edit) {
        http_response_code(200);
        echo json_encode(['success' => "Branch Updated."]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.']);
        exit();
    }
}


if (isset($_POST['packagedel']) && $_POST['packagedel'] === 'true') {
    $id = $_POST['package'] ?? [];
    $pwd = $_POST['pwd'];

    for ($i = 0; $i < count($id); $i++) {
        if (!is_numeric($id[$i])) {
            http_response_code(400);
            echo json_encode(['error' => "Invalid ID $id."]);
            exit();
        }
        if (empty($id[$i])){
            http_response_code(400);
            echo json_encode(['error' => "Some data are empty. Please refresh your browser."]);
            exit();
        }
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => "Incorrect Password."]);
        exit();
    }

    $delete = delete_package($conn, $id);
    if (isset($delete['error'])) {
        http_response_code(400);
        echo $delete['error'] . ' at line ' . $delete['line'] . ' at file ' . $delete['file'];
        exit();
    } elseif ($delete) {
        http_response_code(200);
        $package = count($id) == 1 ? "package" : "packages";
        echo json_encode(['success' => "Selected $package Deleted."]);
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown Error Occured.' . ' ' . $delete]);
        exit();
    }
}
