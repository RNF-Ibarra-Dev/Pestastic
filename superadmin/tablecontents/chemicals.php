<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');



// get manager ID for verification in modifying the chemical table
if (isset($_POST["managerId"])) {
    require_once("../../includes/functions.inc.php");
    echo $_SESSION['saID'];
    exit();
}

$units = ['mg', 'g', 'kg', 'L', 'mL'];

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
    // $usn = $_SESSION['saUsn'];
    $branch = $_SESSION['branch'];
    $empId = $_SESSION['empId'];

    $upBy = "$usn | Employee no. $empId";

    $name = $_POST['edit-name'];
    $brand = $_POST['edit-chemBrand'];
    $unit = $_POST['edit-chemUnit'];
    $ed = $_POST['edit-expDate'] ?? null;
    $dr = $_POST['edit-receivedDate'] ?? null;
    $notes = $_POST['edit-notes'];
    $containerCount = $_POST['edit-containerCount'];
    $contSize = $_POST['edit-containerSize'];
    $pwd = $_POST['saPwd'];

    $expDate = date("Y-m-d", strtotime($ed));
    $dateRec = date("Y-m-d", strtotime($dr));

    if (empty($name) || empty($brand) || empty($unit)) {
        http_response_code(400);
        echo 'Make sure to fill up required forms.';
        exit();
    }

    if (empty($expDate)) {
        $usualexp = strtotime("+2years");
        $expDate = date('Y-m-d', $usualexp);
    }

    if (strtotime($expDate) < strtotime($dateRec)) {
        http_response_code(400);
        echo 'Expiry date cannot be before the received date.';
        exit();
    }

    if (strtotime($dateRec) > strtotime(date('Y-m-d'))) {
        http_response_code(400);
        echo 'Invalid received date.';
        exit();
    }

    if (strtotime($expDate) < strtotime(date('Y-m-d'))) {
        http_response_code(400);
        echo 'You are setting an expired chemical.';
        exit();
    }

    if (!validate($conn, $pwd)) {
        echo 'Incorrect Password.';
        exit();
    }

    // $edit = editChem($conn, $id, $name, $brand, $level, $expDate, $dateRec, $notes, $branch, $upBy, $contSize, $containerCount);
    $edit = editChem($conn, $id, $name, $brand, $expDate, $dateRec, $notes, $branch, $upBy, $contSize, $unit);

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
    $containerSize = $_POST['containerSize'] ?? [];
    $containerCount = $_POST['containerCount'] ?? [];
    $saPwd = $_POST['saPwd'];

    $addedBy = "[$loggedId] - $loggedUsn";

    if (empty($name) || empty($brand) || empty($level)) {
        http_response_code(400);
        echo 'Fields cannot be empty.';
        exit;
    }

    for ($i = 0; $i < count($level); $i++) {
        if ($level[$i] > $containerSize[$i]) {
            http_response_code(400);
            echo 'Chemical Level cannot be greater than Container Size.';
            exit;
        }
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
        'csize' => $containerSize,
        'ccount' => $containerCount,
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
    $id = $_SESSION['saID'];
    $pwd = $_POST['saPwd'];
    $chemId = $_POST['chemid'];
    // convert ids to integer

    if (empty($id) || !is_numeric($id) || !is_numeric($chemId) || empty($chemId)) {
        http_response_code(400);
        echo 'Invalid ID.';
        exit();
    }

    if (empty($pwd)) {
        http_response_code(400);
        echo 'Password verification cannot be empty.';
        exit();
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo 'Incorrect Password.';
        exit();
    }

    $delete = deleteChem($conn, $chemId);
    if (!$delete) {
        http_response_code(400);
        echo 'Error. Deletion Failed.';
        exit();
    }
    http_response_code(200);
    echo json_encode(['success' => 'Chemical Deleted.']);
    exit();

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
            <div class="col-lg-3 mb-2">
                <label for="name" class="form-label fw-light">Chemical Name</label>
                <input type="text" name="name[]" id="add-name" class="form-control form-add" autocomplete="one-time-code">
            </div>
            <div class="col-lg-3 mb-2">
                <label for="chemBrand" class="form-label fw-light">Chemical Brand</label>
                <input type="text" name="chemBrand[]" id="add-chemBrand" class="form-control form-add"
                    autocomplete="one-time-code">
            </div>
            <div class="col-lg-2 mb-2">
                <label for="chemLevel" class="form-label fw-light text-nowrap">Current Chemical Level</label>
                <input type="text" name="chemLevel[]" id="add-chemLevel" class="form-control form-add"
                    autocomplete="one-time-code">
            </div>
            <div class="col-lg-2 mb-2">
                <label for="chemLevel" class="form-label fw-light">Container Size</label>
                <input type="text" name="containerSize[]" id="add-chemLevel" class="form-control form-add"
                    autocomplete="one-time-code">
            </div>
            <div class="col-lg-2 mb-2">
                <label for="chemLevel" class="form-label fw-light">Container Count</label>
                <input type="text" name="containerCount[]" id="add-chemLevel" class="form-control form-add"
                    autocomplete="one-time-code">
            </div>

        </div>
        <div class="row mb-2">
            <div class="col-lg-4 mb-2">
                <label for="expDate" class="form-label fw-light">Date Received</label>
                <input type="date" name="receivedDate[]" id="add-dateReceived" class="form-control form-add form-date-rec">
            </div>
            <div class="col-lg-4 mb-2">
                <label for="expDate" class="form-label fw-light">Expiry Date</label>
                <input type="date" name="expDate[]" id="add-expDate" class="form-control form-add form-date-exp">
            </div>
            <div class="col-4 mb-2">
                <label for="notes" class="form-label fw-light">Short Note</label>
                <textarea name="notes[]" id="notes" class="form-control"
                    placeholder="Optional short note . . . "></textarea>
            </div>
        </div>
        <div class="mb-2 d-flex">
            <button type="button" class="btn btn-grad remove-btn mx-auto w-50">Remove Row<i
                    class="bi bi-dash-circle ms-3"></i></button>
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
            $data['unop_cont'] = $row['unop_cont'];
            $data['container_size'] = $row['container_size'];
            $data['unit'] = $row['quantity_unit'];
        }
    } else {
        echo "Invalid ID. Make sure the chemical exist.";
        exit();
    }

    echo json_encode($data);
    mysqli_stmt_close($stmt);
    exit();
}

if (isset($_GET['branchoptions']) && $_GET['branchoptions'] === 'true') {
    $sql = "SELECT * FROM branches;";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        echo "<option value='' selected>Show All Branch</option>";
        while ($row = mysqli_fetch_assoc($query)) {
            $id = $row['id'];
            $name = $row['name'];
            $loc = $row['location'];
            ?>
            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars("$name ($loc)") ?></option>
            <?php
        }
    }
}

if (isset($_GET['count']) && $_GET['count'] === 'true') {
    $ibranch = $_GET['branch'];
    $branchquery = '';
    if ($ibranch !== '' && $ibranch !== NULL) {
        $branch = (int) $ibranch;
        $branchquery = "branch = ?;";
    }
    $stmt = mysqli_stmt_init($conn);
    switch ($_GET['status']) {
        case "total":
            $sql = "SELECT COUNT(*) FROM chemicals";
            break;
        case "low":
            $sql = "SELECT COUNT(*) FROM chemicals WHERE chemLevel <= unop_cont * .20";
            break;
        case "expired":
            $sql = "SELECT COUNT(*) FROM chemicals WHERE expiryDate < CURDATE()";
            break;
        case "entries":
            $sql = "SELECT COUNT(*) FROM chemicals WHERE request = 1";
            break;
        case "available":
            $sql = "SELECT COUNT(*) FROM chemicals WHERE chemLevel > 0";
            break;
        case "dispatched":
            $sql = "SELECT COUNT(*) FROM inventory_log WHERE log_type = 'Out'";
            break;
        case "out-of-stock":
            $sql = "SELECT COUNT(*) FROM chemicals WHERE chemLevel = 0 AND unop_cont = 0";
            break;
        default:
            http_response_code(400);
            echo "Error in fetching counts.";
            break;
    }

    if ($branchquery !== '') {
        if (str_contains($sql, "WHERE")) {
            $sql .= " AND $branchquery";
        } else {
            $sql .= " WHERE $branchquery";
        }
    }
    $sql .= ';';
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo 'stmt failed';
        exit;
    }
    if ($branchquery !== '') {
        mysqli_stmt_bind_param($stmt, 'i', $branch);
    }
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    echo mysqli_fetch_row($res)[0];
    exit();
}
