<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');


if (isset($_GET['chemDetails']) && $_GET['chemDetails'] === 'true') {
    $chemId = $_GET['id'];
    if (!is_numeric($chemId)) {
        echo 'Invalid ID';
        exit();
    }

    $sql = "SELECT * FROM chemicals WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'stmt failed.';
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $chemId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $data = [];
    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            $data['name'] = $row['name'];
            $data['brand'] = $row['brand'];
            $data['level'] = $row['chemLevel'];
            $expdate = $row['expiryDate'];
            $data['expDate'] = date("F j, Y", strtotime($expdate));
            $addat = $row['added_at'];
            $data['addat'] = date("F j, Y h:m A", strtotime($addat));
            $upat = $row['updated_at'];
            $data['upat'] = date("F j, Y h:m A", strtotime($upat));
            $data['notes'] = $row['notes'];
            $data['branch'] = $row['branch'];
            $data['addby'] = $row['added_by'];
            $data['upby'] = $row['updated_by'];
            $daterec = $row['date_received'];
            $data['daterec'] = date("F j, Y", strtotime($daterec));
            $data['req'] = $row['request'];
            $data['id'] = $row['id'];
        }
    } else {
        echo "Invalid ID. Make sure the chemical exist.";
        exit();
    }

    echo json_encode($data);
    mysqli_stmt_close($stmt);
    exit();
}

// edit
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    // echo json_encode(['greet' => 'hello']);

    $id = $_POST['id'];
    $name = $_POST['name'];
    $brand = $_POST['chemBrand'];
    $level = $_POST['chemLevel'];
    $expDate = $_POST['expDate'];
    $pwd = $_POST['saPwd'];

    if (empty($name || $brand || $level)) {
        http_response_code(400);
        echo json_encode(['type' => 'emptyinput', 'error' => 'Make sure to fill up required forms.']);
    }



    if (validateOS($conn, $pwd) == true) {
        if (editChem($conn, $id, $name, $brand, $level, $expDate, 1) == true) {
            // echo 'changes saved';
            echo json_encode(['success' => 'Changes Saved.']);
            exit();
        } else {
            http_response_code(400);
            echo json_encode(['type' => 'update', 'error' => 'No Changes Made.']);
            exit();
        }
    } else {
        http_response_code(400);
        echo json_encode(['type' => 'pwd', 'error' => 'Incorrect Password']);
        exit();
    }
}

// add
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    // $id = $_POST['id'];  
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['chemBrand']);
    $level = mysqli_real_escape_string($conn, $_POST['chemLevel']);
    $expDate = $_POST['expDate'];
    $saPwd = $_POST['saPwd'];

    if (!empty($expDate)) {
        $formatDate = date('Y-m-d', strtotime($expDate));
    } else {
        $formatDate = '2025-01-01';
    }

    if (!empty($name) && !empty($brand) && !empty($level)) {
        if (!empty($saPwd)) {
            if (validateOS($conn, $saPwd) == true) {
                if (addChemical($conn, $name, $brand, $level, $formatDate, 1) == true) {
                    http_response_code(200);
                    echo json_encode(['type' => 'dsfs', 'success' => 'Chemical Added!']);
                    exit;
                } else {
                    http_response_code(400);
                    echo json_encode(['type' => 'addFailed', 'error' => 'Function to add chemical failed.']);
                    exit;
                }
            } else {
                http_response_code(400);
                echo json_encode(['type' => 'wrongPwd', 'error' => 'Wrong Password.']);
                exit;
            }
        } else {
            http_response_code(400);
            echo json_encode(['type' => 'emptyPwd', 'error' => 'Empty Password.']);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(['type' => 'emptyInput', 'error' => 'Fields cannot be empty.']);
        exit;
    }
}

// delete
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_POST['saID'];
    $pwd = $_POST['pass'];
    $chemId = $_POST['chemID'];
    // convert ids to integer

    if (!empty($pwd) && !empty($id)) {
        if (validateOS($conn, $pwd) == true) {
            if (deleteChem($conn, $chemId) == true) {
                http_response_code(200);
                echo json_encode(['success' => 'Chemical Deleted.']);
                exit;
            } else {
                http_response_code(400);
                echo json_encode(['type' => 'delete', 'error' => 'Error. Deletion Failed.']);
                exit;
            }
        } else {
            http_response_code(400);
            echo json_encode(['type' => 'pwd', 'error' => 'Incorrect Password.']);
            exit;
        }
        // echo $id . ' ' . $pwd . ' ' . $chemId;
    } else {
        http_response_code(400);
        echo json_encode(['type' => 'empty', 'error' => 'Password verification cannot be empty.']);
        exit;
    }
}



?>