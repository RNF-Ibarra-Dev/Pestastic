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

function check_request($conn, $id)
{
    if (!is_numeric($id)) {
        echo "Invalid ID." . $id;
        exit();
    }
    $sql = "SELECT request FROM chemicals WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "check_request stmt failed.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            if ($row['request'] === 1) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        echo "ID missing at database.";
        exit();
    }

}
// edit
if (isset($_POST['action']) && $_POST['action'] == 'edit') {

    $id = $_POST['edit-id'];
    $checkReq = check_request($conn, $id);
    if ($checkReq) {
        echo "Unable to edit unapproved chemical.";
        exit();
    }

    $usn = $_SESSION['saUsn'];
    $saID = $_SESSION['saID'];
    $usn = $_SESSION['saUsn'];
    $branch = $_SESSION['branch'];
    $empId = $_SESSION['empId'];

    $upBy = "$usn | Employee no. $empId";

    $name = $_POST['edit-name'];
    $brand = $_POST['edit-chemBrand'];
    $level = $_POST['edit-chemLevel'];
    $expDate = $_POST['edit-expDate'] ?? null;
    $dateRec = $_POST['edit-receivedDate'] ?? null;
    $notes = $_POST['edit-notes'];
    $pwd = $_POST['saPwd'];

    if (empty($name || $brand || $level)) {
        http_response_code(400);
        echo 'Make sure to fill up required forms.';
    }

    if (!validate($conn, $pwd)) {
        echo 'Incorrect Password.';
        exit();
    }

    $edit = editChem($conn, $id, $name, $brand, $level, $expDate, $dateRec, $notes, $branch, $upBy);

    if (isset($edit['error'])) {
        http_response_code(400);
        echo $edit['error'];
        exit();
    }

    http_response_code(200);
    echo json_encode(['success' => "Chemical Updated!"]);
    exit();

}

// add
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $loggedId = $_SESSION['saID'];
    $loggedUsn = $_SESSION['saUsn'];
    $branch = $_SESSION['branch'];
    $empId = $_SESSION['empId'];
    $request = isset($_POST['approveCheck']) ? 0 : 1;
    $notes = $_POST['notes'];
    $name = $_POST['name'] ?? [];
    $receivedDate = $_POST['receivedDate'] ?? [];
    $brand = $_POST['chemBrand'] ?? [];
    $level = $_POST['chemLevel'] ?? [];
    $expDate = $_POST['expDate'] ?? [];
    $saPwd = $_POST['saPwd'];

    $addedBy = "[$loggedId] - $loggedUsn";

    if (empty($name) && empty($brand) && empty($level)) {
        http_response_code(400);
        echo 'Fields cannot be empty.';
        exit;
    }

    if (empty($saPwd)) {
        http_response_code(400);
        echo 'Empty Password.';
        exit;
    }

    if (!validate($conn, $saPwd)) {
        http_response_code(400);
        echo 'Wrong Password.';
        exit;
    }


    $data = [
        'brand' => $brand,
        'level' => $level,
        'notes' => $notes,
        'name' => $name,
        'rDate' => $receivedDate,
        'eDate' => $expDate,
    ];
    // echo var_dump($data);
    // exit();

    $a = addChemv2($conn, $data, $branch, $addedBy, $request);
    if (isset($a['errorMessage'])) {
        http_response_code(400);
        echo $a['errorMessage'] . ' at line ' . $a['line'] . ' data: ' . json_encode($a['dataPassed']);
        exit;
    } else {
        http_response_code(200);
        echo json_encode(['success' => 'Chemical Entry Added!']);
        exit();
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

    echo json_encode(['success' => 'Stock enties approved and added to inventory officially.']);
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

if (isset($_GET['addrow']) && $_GET['addrow'] === 'true') {
    ?>
    <div class="add-row-container">
        <hr class="my-2">
        <div class="row mb-2 pe-2">
            <div class="col-lg-4 mb-2">
                <label for="name" class="form-label fw-light">Chemical Name</label>
                <input type="text" name="name[]" id="add-name" class="form-control form-add" autocomplete="one-time-code">
            </div>
            <div class="col-lg-4 mb-2">
                <label for="chemBrand" class="form-label fw-light">Chemical Brand</label>
                <input type="text" name="chemBrand[]" id="add-chemBrand" class="form-control form-add"
                    autocomplete="one-time-code">
            </div>
            <div class="col-lg-3 mb-2">
                <label for="chemLevel" class="form-label fw-light">Chemical Level </label>
                <input type="text" name="chemLevel[]" id="add-chemLevel" class="form-control form-add"
                    autocomplete="one-time-code">
            </div>
            <button type="button" class="btn btn-grad remove-btn col-1 mt-auto mb-2"><i
                    class="bi bi-dash-circle"></i></button>
        </div>
        <div class="row mb-2">
            <div class="col-lg-4 mb-2">
                <label for="expDate" class="form-label fw-light">Date Received</label>
                <input type="date" name="receivedDate[]" id="add-dateReceived" class="form-control form-add form-date">
            </div>
            <div class="col-lg-4 mb-2">
                <label for="expDate" class="form-label fw-light">Expiry Date</label>
                <input type="date" name="expDate[]" id="add-expDate" class="form-control form-add form-date">
            </div>

            <div class="col-4 mb-2">
                <label for="notes" class="form-label fw-light">Short Note</label>
                <textarea name="notes[]" id="notes" class="form-control"
                    placeholder="Optional short note . . . "></textarea>
            </div>

        </div>
    </div>

    <?php
}

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
            $data['expDate'] = $row['expiryDate'];
            $data['addat'] = $row['added_at'];
            $data['upat'] = $row['updated_at'];
            $data['notes'] = $row['notes'];
            $data['branch'] = $row['branch'];
            $data['addby'] = $row['added_by'];
            $data['upby'] = $row['updated_by'];
            $data['daterec'] = $row['date_received'];
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

?>