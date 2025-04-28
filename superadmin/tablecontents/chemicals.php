<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM chemicals WHERE name LIKE '%" . $search . "%' OR brand LIKE '%" . $search . "%' OR chemLevel LIKE '%" . $search . "%' OR expiryDate LIKE '%" . $search . "%' ORDER BY request DESC, id DESC;";

    $result = mysqli_query($conn, $sql);
    $numrows = mysqli_num_rows($result);
    // echo $numrows;   
    if ($numrows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row["name"];
            $brand = $row["brand"];
            $level = $row["chemLevel"];
            $expDate = $row["expiryDate"];
            $request = $row['request'];
            ?>
            <tr>
                <td scope="row">
                    <?=
                        $request === '1' ? "<i class='bi bi-exclamation-diamond me-2' data-bs-toggle='tooltip' title='For Approval! Contact manager for more information.'></i><strong>" . htmlspecialchars($name) . "</strong>" : htmlspecialchars($name);
                    ?>
                </td>
                <td><?= htmlspecialchars($brand) ?></td>
                <td><?= htmlspecialchars($level) ?></td>
                <td><?= htmlspecialchars($expDate) ?></td>
                <td>
                    <div class="d-flex justify-content-center">
                        <?php
                        if ($request === "1") {
                            ?>
                            <button type="button" id="approvebtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#approveModal" data-id="<?= $id ?>" data-name="<?= $name ?>"><i
                                    class="bi bi-check-circle"></i></button>
                            <button type="button" id="delbtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-id="<?= $id ?>"><i class="bi bi-x-octagon"></i></button>
                            <?php
                        } else {
                            ?>
                            <button type="button" id="editbtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#editModal" data-id="<?= $id ?>" data-name="<?= htmlspecialchars($name) ?>"
                                data-brand="<?= htmlspecialchars($brand) ?>" data-level="<?= htmlspecialchars($level) ?>"
                                data-expdate="<?= htmlspecialchars($expDate) ?>"><i class="bi bi-pencil-square"></i></button>
                            <button type="button" id="delbtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-id="<?= $id ?>"><i class="bi bi-trash"></i></button>
                        <?php } ?>
                    </div>
                </td>
            </tr>

            <?php
        }
    } else {
        // echo json_encode(['']);
        echo "<tr><td scope='row' colspan='5' class='text-center'>Your search does not exist.</td></tr>";
    }
}

// get manager ID for verification in modifying the chemical table
if (isset($_POST["managerId"])) {
    require_once("../../includes/functions.inc.php");
    echo $_SESSION['saID'];
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



    if (validate($conn, $pwd) == true) {
        if (editChem($conn, $id, $name, $brand, $level, $expDate) == true) {
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
            if (validate($conn, $saPwd) == true) {
                if (addChemical($conn, $name, $brand, $level, $formatDate) == true) {
                    // http_response_code(200);
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
        if (validate($conn, $pwd) == true) {
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



if (isset($_POST['approve']) && $_POST['approve'] === 'true') {
    $id[] = $_POST['id'];
    $pwd = $_POST['saPwd'];

    if (empty($id) || empty($pwd)) {
        http_response_code(400);
        if (empty($id)) {
            echo json_encode(['error' => 'emptyfield', 'msg' => 'Empty Id.']);
            exit();
        } else {
            echo json_encode(['error' => 'emptyfield', 'msg' => 'Empty Password.']);
            exit();
        }
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'wrongpwd', 'msg' => 'Incorrect Password.']);
        exit();
    }

    $approve = approve_stock($conn, $id);
    if (!$approve) {
        echo json_encode(['error' => 'function', 'msg' => $approve['msg']]);
        exit();
    }

    echo json_encode(['success' => 'Stock approved and added to inventory officially.']);
    exit();

}


if (isset($_POST['approvemultiple']) && $_POST['approvemultiple'] === 'true') {
    // get ids to delete
    $stocks = $_POST['stocks'];
    $pwd = $_POST['saPwd'];

    if (empty($stocks) || empty($pwd)) {
        http_response_code(400);
        if (empty($stocks)) {
            echo json_encode(['error' => 'emptyfield', 'msg' => 'Select at least one stock to approve.']);
        } else {
            echo json_encode(['error' => 'emptyfield', 'msg' => 'Empty password.']);
        }
        exit();
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'pwd', 'msg' => 'Incorrect Password.']);
        exit();
    }

    $approve = approve_stock($conn, $stocks);
    if (!isset($approve['success'])) {
        http_response_code(400);
        echo json_encode(['error' => 'function', 'msg' => $approve['msg'] . $approve['ids']]);
    } else {
        echo json_encode(['success' => 'Stock approved and added to inventory officially.']);
    }
    exit();
}

if (isset($_GET['stock']) && $_GET['stock'] === 'true') {
    $sql = "SELECT * FROM chemicals WHERE request = 1 ORDER BY id DESC;";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row["name"];
            $brand = $row["brand"];
            $level = $row["chemLevel"];
            $expDate = $row["expiryDate"];
            $request = $row['request'];
            ?>
            <tr class="text-center">
                <td scope="row">
                    <?=
                        $request === '1' ? "<i class='bi bi-exclamation-diamond me-2' data-bs-toggle='tooltip' title='For Approval'></i><strong>" . htmlspecialchars($name) . "</strong>" : htmlspecialchars($name);
                    ?>
                </td>
                <td><?= htmlspecialchars($brand) ?></td>
                <td><?= htmlspecialchars($level) ?></td>
                <td><?= htmlspecialchars($expDate) ?></td>
                <td>
                    <div class="d-flex justify-content-center">
                        <?php
                        if ($request === "1") {
                            ?>
                            <input type="checkbox" class="btn-check" value="<?= $id ?>" name="stocks[]" id="c-<?= $id ?>"
                                autocomplete="off">
                            <label class="btn btn-outline-dark" for="c-<?= $id ?>"><i
                                    class="bi bi-check-circle me-2"></i>Approve</label>
                            <?php
                        } else {
                            ?>
                            <p class="text-muted">Approved.</p>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='5' class='text-center'>No Stock Requests.</td></tr>";
    }
}



?>