<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$role = $_SESSION['user_role'];
$user = $_SESSION['saID'];
$branch = $_SESSION['branch'];
$author = $_SESSION['author'];


// get manager ID for verification in modifying the chemical table
if (isset($_POST["managerId"])) {
    require_once("../../includes/functions.inc.php");
    echo $_SESSION['saID'];
    exit();
}
$role = $_SESSION['user_role'];
$user = $_SESSION['saID'];
$branch = $_SESSION['branch'];

$units = ['mg', 'g', 'kg', 'L', 'mL', 'gal', 'box', 'pc', 'canister'];

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
    $threshold = $_POST['edit-restockThreshold'];
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
    $edit = editChem($conn, $id, $name, $brand, $expDate, $dateRec, $notes, $branch, $upBy, $contSize, $unit, $threshold);

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
    $branch = $_POST['target_branch'] ?? NULL;
    $empId = $_SESSION['empId'];
    $request = isset($_POST['approveCheck']) ? 0 : 1;
    $notes = $_POST['notes'];
    $name = $_POST['name'] ?? [];
    $unit = $_POST['chemUnit'] ?? [];
    $receivedDate = $_POST['receivedDate'] ?? [];
    $brand = $_POST['chemBrand'] ?? [];
    $level = $_POST['chemLevel'] ?? [];
    $expDate = $_POST['expDate'] ?? [];
    $containerSize = $_POST['containerSize'] ?? [];
    $threshold = $_POST['restockThreshold'] ?? [];
    $containerCount = $_POST['containerCount'] ?? [];
    $saPwd = $_POST['saPwd'];

    $addedBy = "[$loggedId] - $loggedUsn";


    if (empty($name) || empty($brand) || empty($unit) || empty($containerCount) || empty($containerSize)) {
        http_response_code(400);
        echo "Fields cannot be empty." . var_dump($unit);
        exit;
    }

    $three_years = strtotime("+3 years");
    $default_expiry = date("Y-m-d", $three_years);

    for ($i = 0; $i < count($expDate); $i++) {
        if (empty($expDate[$i]) || $expDate == '') {
            $expDate[$i] = $default_expiry;
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
        'unit' => $unit,
        'level' => $containerSize,
        'notes' => $notes,
        'name' => $name,
        'rDate' => $receivedDate,
        'eDate' => $expDate,
        'csize' => $containerSize,
        'ccount' => $containerCount,
        'threshold' => $threshold
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
    $stocks = $_POST['stocks'] ?? [];
    $rejected_stocks = $_POST['stock_reject'] ?? [];
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

    if (!empty($rejected_stocks)) {
        $reject = reject_stocks($conn, $rejected_stocks);
        if (!isset($reject['success'])) {
            http_response_code(400);
            echo json_encode(['error' => 'function', 'msg' => $reject['msg']]);
            exit();
        }
    }

    if (!empty($stocks)) {
        $approve = approve_stock($conn, $stocks);
        if (!isset($approve['success'])) {
            http_response_code(400);
            echo json_encode(['error' => 'function', 'msg' => $approve['msg'] . $approve['ids']]);
            exit();
        }
    }
    http_response_code(200);
    echo json_encode(['success' => 'Stock approved and added to inventory officially.']);
    exit();
}

if (isset($_GET['stock']) && $_GET['stock'] === 'true') {
    $sql = "SELECT DISTINCT * FROM chemicals WHERE request = 1 ";
    $branch = $_GET['branch'] ?? NULL;
    $data = [];
    $type = '';
    if ($branch !== '' && $branch !== NULL) {
        $branch = $_GET['branch'];
        $sql .= " AND branch = ? ";
        $data[] = $branch;
        $type .= 'i';
    }
    $sql .= "ORDER BY id DESC;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "<tr><td scope='row' colspan='5' class='text-center'>Statement preparation failed. Please try again.</td></tr>";
        exit();
    }
    if (!empty($data)) {
        mysqli_stmt_bind_param($stmt, $type, ...$data);
    }
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row["name"];
            $brand = $row["brand"];
            $contsize = (int) $row['container_size'];
            $unit = $row['quantity_unit'];
            $datereceived = $row['date_received'];
            $dr = date("F j, Y", strtotime($datereceived));
            $expiry = $row['expiryDate'];
            $e = date("F j, Y", strtotime($expiry));
            $added_by = $row['added_by'];
            $ab = $added_by === "No Record" ? "User not found." : $added_by;
            $request = (int) $row['request'];
            ?>
            <tr class="text-center">
                <td>
                    <?= htmlspecialchars($id) ?>
                </td>
                <td><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($brand) ?></td>
                <td>
                    <?= htmlspecialchars("$contsize $unit") ?>
                </td>
                <td>
                    <?= htmlspecialchars($dr) ?>
                </td>
                <td>
                    <?= htmlspecialchars($e) ?>
                </td>
                <td><?= htmlspecialchars($ab) ?></td>
                <td>
                    <div class="d-flex justify-content-center">
                        <?php
                        if ($request === 1) {
                            ?>
                            <div class="btn-group">
                                <input type="checkbox" class="btn-check chkbox-approve" value="<?= htmlspecialchars($id) ?>"
                                    name="stocks[]" id="c-<?= $id ?>" autocomplete="off">
                                <label class="btn btn-sidebar btn-outline-dark" for="c-<?= htmlspecialchars($id) ?>"><i
                                        class="bi bi-check mx-auto"></i></label>
                            </div>
                            <?php
                        } else {
                            ?>
                            <p class="text-muted">Item approved.</p>
                        <?php } ?>
                    </div>
                </td>
                <td>
                    <?php
                    if ($request === 1) {
                        ?>
                        <div class="btn-group">
                            <input type="checkbox" class="btn-check chkbox-reject" value="<?= htmlspecialchars($id) ?>"
                                name="stock_reject[]" id="r-<?= htmlspecialchars($id) ?>" autocomplete="off">
                            <label class="btn btn-sidebar btn-outline-dark" for="r-<?= htmlspecialchars($id) ?>"><i
                                    class="bi bi-x mx-auto"></i></label>
                        </div>
                        <?php
                    } else {
                        ?>
                        <p class="text-muted">-</p>
                    <?php } ?>
                </td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='9' class='text-center'>No Stock Entries.</td></tr>";
    }
}

if (isset($_GET['addrow']) && $_GET['addrow'] === 'true') {
    $uid = uniqid();
    ?>
    <div class="add-row-container">
        <hr class="my-2">
        <div class="row mb-2 pe-2">
            <div class="col-lg-3 mb-2">
                <label for="name-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Item Name</label>
                <input type="text" name="name[]" id="name-<?= htmlspecialchars($uid) ?>" class="form-control form-add"
                    autocomplete="one-time-code">
            </div>
            <div class="col-lg-3 mb-2">
                <label for="chemBrand-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Item Brand</label>
                <input type="text" name="chemBrand[]" id="chemBrand-<?= htmlspecialchars($uid) ?>"
                    class="form-control form-add" autocomplete="one-time-code">
            </div>
            <div class="col-lg-2 mb-2">
                <label for="containerSize-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Item Size</label>
                <input type="text" name="containerSize[]" id="containerSize-<?= htmlspecialchars($uid) ?>"
                    class="form-control form-add" autocomplete="one-time-code">
            </div>
            <div class="col-lg-2 mb-2">
                <label for="chemUnit-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Item Unit:</label>
                <select name="chemUnit[]" id="chemUnit-<?= htmlspecialchars($uid) ?>" class="form-select"
                    autocomplete="one-time-code">
                    <option value="" selected>Choose Item Unit</option>
                    <option value="mg">mg</option>
                    <option value="g">g</option>
                    <option value="kg">kg</option>
                    <option value="mL">mL</option>
                    <option value="L">L</option>
                    <option value="gal">gal</option>
                    <option value="box">Box</option>
                    <option value="pc">Piece</option>
                    <option value="canister">Canister</option>
                </select>
            </div>
            <div class="col-lg-2 mb-2">
                <label for="containerCount-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Item
                    Count</label>
                <input type="text" name="containerCount[]" id="containerCount-<?= htmlspecialchars($uid) ?>"
                    class="form-control form-add" autocomplete="one-time-code">
            </div>

        </div>
        <div class="row mb-2">
            <div class="col-lg-2 mb-2">
                <label for="restockThreshold-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Restock
                    Threshold:</label>
                <input type="number" name="restockThreshold[]" id="restockThreshold-<?= htmlspecialchars($uid) ?>"
                    class="form-control" autocomplete="one-time-code">
            </div>
            <div class="col-2 mb-2">
                <label for="recDate-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Date Received</label>
                <input type="date" name="receivedDate[]" id="recDate-<?= htmlspecialchars($uid) ?>"
                    class="form-control form-add form-date-rec">
            </div>
            <div class="col-2 mb-2">
                <label for="expDate-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Expiry Date</label>
                <input type="date" name="expDate[]" id="expDate-<?= htmlspecialchars($uid) ?>"
                    class="form-control form-add form-date-exp">
            </div>
            <div class="col-3 mb-2">
                <label for="notes-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Short Note</label>
                <textarea name="notes[]" id="notes-<?= htmlspecialchars($uid) ?>" class="form-control"
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
            $data['threshold'] = $row['restock_threshold'];
            $data['location'] = $row['chem_location'];
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
if (isset($_GET['add_select_branches']) && $_GET['add_select_branches'] === 'true') {
    $sql = "SELECT * FROM branches;";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        echo "<option value='' selected>Select item/s branch</option>";
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
            $sql = "SELECT COUNT(DISTINCT name, brand, container_size, quantity_unit) FROM chemicals";
            break;
        case "low":
            $sql = "SELECT COUNT(DISTINCT name, brand, container_size, quantity_unit) FROM chemicals WHERE (unop_cont + (CASE WHEN chemLevel > 0 THEN 1 ELSE 0 END)) < restock_threshold";
            break;
        case "expired":
            $sql = "SELECT COUNT(DISTINCT name, brand, container_size, quantity_unit) FROM chemicals WHERE expiryDate < CURDATE()";
            break;
        case "entries":
            $sql = "SELECT COUNT(*) FROM chemicals WHERE request = 1";
            break;
        case "available":
            $sql = "SELECT COUNT(DISTINCT name, brand, container_size, quantity_unit) FROM chemicals WHERE (chemLevel > 0 OR unop_cont > 0) AND request = 0 AND chem_location = 'main_storage'";
            break;
        case "dispatched":
            $sql = "SELECT COUNT(DISTINCT name, brand, container_size, quantity_unit) FROM chemicals WHERE chem_location = 'dispatched'";
            break;
        case "out-of-stock":
            $sql = "SELECT COUNT(DISTINCT name, brand, container_size, quantity_unit) FROM chemicals WHERE chemLevel = 0 AND unop_cont = 0";
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


if (isset($_GET['chemLog']) && $_GET['chemLog'] === 'true') {
    $id = $_GET['id'];

    if (!is_numeric($id) || $id === NULL) {
        http_response_code(400);
        echo 'Invalid ID.';
        exit();
    }

    // $sql = "SELECT * FROM inventory_log JOIN chemicals ON inventory_log.chem_id = chemicals.id WHERE chemicals.id = ?;";
    $sql = "SELECT * FROM chemicals LEFT JOIN inventory_log ON chemicals.id = inventory_log.chem_id WHERE chemicals.id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo 'stmt failed.';
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);
    $data = [];
    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            echo json_encode(['success' => json_encode($row)]);
        }
        // echo json_encode(['success' => json_encode($data)]);
        mysqli_stmt_close($stmt);
        exit();
    } else {
        http_response_code(400);
        echo 'Chemical not found.';
        exit();
    }
}


if (isset($_GET['qty_unit_options']) && $_GET['qty_unit_options'] === 'true') {
    $cur_unit = $_GET['current_unit'];

    if (empty($cur_unit) || !is_string($cur_unit)) {
        http_response_code(400);
        echo "Invalid unit.";
        exit();
    }

    if (!in_array($cur_unit, $units)) {
        http_response_code(400);
        echo "Invalid unit.";
        exit();
    }

    if ($cur_unit === 'mg' || $cur_unit === 'g' || $cur_unit === 'kg') {
        $unit_option = ['mg', 'g', 'kg'];
    } elseif ($cur_unit === 'box' || $cur_unit === 'pc' || $cur_unit === 'canister') {
        $unit_option = ['box', 'pc', 'canister'];
    } elseif ($cur_unit === 'mL' || $cur_unit === 'L') {
        $unit_option = ['mL', 'L', 'gal'];
    } else {
        $unit_option = [$cur_unit];
    }

    echo "<option value='' selected>Choose Unit</option>";
    foreach ($unit_option as $option) {
        echo "<option value='$option'>$option</option>";
    }
    exit();
}

// for select dropdown
if (isset($_GET['dispatched_transactions']) && $_GET['dispatched_transactions'] === 'true') {
    $name = $_GET['name'];
    $brand = $_GET['brand'];
    $csize = $_GET['csize'];
    $unit = $_GET['unit'];

    $get_main_id_sql = "SELECT id FROM chemicals WHERE name = ? AND brand = ? AND container_size = ? AND quantity_unit = ?;";
    $get_main_id_stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($get_main_id_stmt, $get_main_id_sql)) {
        http_response_code(400);
        echo "Prepared statement failed at finding main chemical. Please try again later.";
        exit();
    }

    mysqli_stmt_bind_param($get_main_id_stmt, "ssis", $name, $brand, $csize, $unit);
    mysqli_stmt_execute($get_main_id_stmt);
    $result = mysqli_stmt_get_result($get_main_id_stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $main_id = $row['id'];
    } else {
        http_response_code(400);
        echo "Error. Main chemical not found.";
        exit();
    }

    $sql = "SELECT t.id FROM transactions t WHERE t.transaction_status = 'Dispatched' AND EXISTS (SELECT 1 FROM transaction_chemicals tc WHERE tc.chem_id = ? AND tc.trans_id = t.id);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "Prepared statement failed at finding dispatched transactions. Please try again later.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $main_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) > 0) {
        echo "<option value=''>Select Transaction</option>";
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            ?>
            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></option>
            <?php
        }
    } else {
        echo "<option selected disabled>No available dispatched transactions</option>";
    }
    mysqli_close($conn);
    exit();
}


if (isset($_GET['dispatch_cur_transchem']) && $_GET['dispatch_cur_transchem'] === 'true') {
    $transid = $_GET['transid'];
    $chemid = $_GET['chemId'];
    $cont_size = $_GET['containerSize'];
    $return = isset($_GET['return']);

    if (empty($chemid)) {
        http_response_code(400);
        echo "Chemical ID missing. Please try again later.";
        exit();
    }

    if (!is_numeric(trim($transid)) || !is_numeric(trim($chemid))) {
        http_response_code(400);
        echo "Invalid Transaction ID or Chemical ID.";
        exit();
    }

    if ($return) {
        $main_chem = get_main_chemical($conn, $chemid);
        if (isset($main_chem['error'])) {
            http_response_code(400);
            echo $main_chem['error'];
            exit();
        }
    } else {
        $main_chem = $chemid;
    }

    $sql = "SELECT amt_used FROM transaction_chemicals WHERE trans_id = ? AND chem_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "There seems to be an issue. Please try again later.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'ii', $transid, $main_chem);
    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $amt_used = $row['amt_used'];
        http_response_code(200);
        $openedLevel = $amt_used % $cont_size;
        $closedContainer = (int) ($amt_used / $cont_size);
        while ($openedLevel > $cont_size) {
            $openedLevel -= $cont_size;
            $closedContainer++;
        }
        echo json_encode([
            'openedLevel' => $openedLevel,
            'closedContainer' => $closedContainer
        ]);

        exit();
    }
    mysqli_stmt_close($stmt);
    http_response_code(200);
    echo json_encode(["error" => "This item has no recorded amount set for transaction ID $transid."]);
    exit();
}


if (isset($_GET['transaction_options']) && $_GET['transaction_options'] === 'true') {
    $sql = "SELECT id FROM transactions WHERE transaction_status = 'Accepted';";
    $res = mysqli_query($conn, $sql);

    echo "<option selected>Select Transaction</option>";
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            ?>
            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></option>
            <?php
        }
    } else {
        echo "<option disabled>No available accepted transactions</option>";
    }
    mysqli_close($conn);
}

$logtypes = [
    'in',
    'out',
    'lost',
    'scrapped',
    'used'
];

$unit_values = [
    'mass' => [
        'mg' => 0.001,
        'g' => 1,
        'kg' => 1000
    ],
    'volume' => [
        'mL' => 0.001,
        'L' => 1,
        'gal' => 3.78541
    ],
    'others' => [
        'box' => 1,
        'pc' => 1,
        'canister' => 1
    ]
];

if (isset($_POST['adjust']) && $_POST['adjust'] === 'true') {
    $chemId = $_POST['chemid'] ?? NULL;
    $qty = isset($_POST['qty']) ? $_POST['qty'] : (float) 0;
    $logtype = $_POST['logtype'];
    $main_unit = $_POST['main_qty_unit'] ?? NULL;
    $entered_unit = isset($_POST['qty_unit']) ? $_POST['qty_unit'] : NULL;
    $ologtype = isset($_POST['other_logtype']) ? $_POST['other_logtype'] : NULL;
    $op = isset($_POST['operator']) ? $_POST['operator'] : NULL;
    $notes = $_POST['notes'];
    $chemical_details = get_chemical($conn, $chemId);

    $wcontainer = isset($_POST['containerchk']);
    // no of container used
    $ccontainer = isset($_POST['containercount']) ? $_POST['containercount'] : (int) 0;

    if ($chemId === NULL || $main_unit === NULL) {
        http_response_code(400);
        echo "There seems to be a problem loading the chemical data. Please try again later. $chemId | $main_unit";
        exit();
    }

    if (!$wcontainer) {
        if (empty($qty) || !is_numeric($qty)) {
            http_response_code(400);
            echo "Quantity must be a valid number and should not be empty.";
            exit();
        }
    }
    // http_response_code(400);

    if ($main_unit !== $entered_unit) {
        $convert = convert_to_main_unit($entered_unit, $main_unit, $qty);
        if (isset($convert['error'])) {
            http_response_code(400);
            echo $convert['error'];
            exit();
        }
        $qty = $convert;
    } else {
        $qty = (float) $qty;
    }

    if ($qty <= 0) {
        http_response_code(400);
        echo "Quantity must be greater than 0.";
        exit();
    }

    if (!$wcontainer) {
        if ($qty > $chemical_details['container_size']) {
            http_response_code(400);
            echo "The quantity entered should not exceed the container size.";
            exit();
        }
    }

    // echo var_dump($qty);
    // exit();

    // if (array_key_exists($main_unit, array_values($unit_values))) {
    //     http_response_code(400);
    //     echo var_dump($unit_values[$main_unit]);
    //     exit();
    // }

    if ($main_unit === 'mg' || $main_unit === 'g' || $main_unit === 'kg') {
        $unit_option = ['mg', 'g', 'kg'];
    } elseif ($main_unit === 'box' || $main_unit === 'pc' || $main_unit === 'canister') {
        $unit_option = ['box', 'pc', 'canister'];
    } elseif ($main_unit === 'mL' || $main_unit === 'L') {
        $unit_option = ['mL', 'L', 'gal'];
    } else {
        $unit_option = [];
    }

    if ($entered_unit === NULL || !is_string($entered_unit)) {
        http_response_code(400);
        echo "Invalid or missing quantity unit.";
        exit();
    }

    if (!in_array($entered_unit, $unit_option)) {
        http_response_code(400);
        echo "Invalid quantity unit '$entered_unit'.";
        exit();
    }


    if (!is_numeric($chemId) || empty($chemId)) {
        http_response_code(400);
        echo "Invalid Chemical ID.";
        exit();
    }

    if ($wcontainer) {
        if ($ccontainer <= 0) {
            http_response_code(400);
            echo "Invalid container count.";
            exit();
        }
        $chemcapacity = get_chem_capacity($conn, $chemId);
        if (isset($chemcapacity['error'])) {
            http_response_code(400);
            echo $chemcapacity['error'];
            exit();
        }
        $qty = (float) $chemcapacity * $ccontainer;
    } else {
        $fccontainer = 0;
    }

    // $valid_logtype = '';
    // $final_qty = 0;

    $usage_source = NULL;
    if (!in_array($logtype, $logtypes)) {
        // if other type is null, logtype should be restricted and only fixed datas are allowed
        if ($ologtype === NULL || empty($ologtype)) {
            http_response_code(400);
            echo "Please choose a valid adjustment type.";
            exit();
        }
        // if not null pass to final variable
        $valid_logtype = ucwords($ologtype);

        // check if positive or not
        if ($op === NULL || ($op !== 'add' && $op !== 'subtract')) {
            http_response_code(400);
            echo "Please specify if the quantity should be added or subtracted for the 'Other' type.";
            exit();
        }

        if ($op === 'add') {
            $final_qty = $qty;
            if ($wcontainer) {
                $fccontainer = $ccontainer;
            }
            $usage_source = "MANUAL_ADDITION";
        } else {
            $final_qty = $qty * -1;
            if ($wcontainer) {
                $fccontainer = $ccontainer * -1;
            }
            $usage_source = "MANUAL_SUBTRACTION";
        }
    } else {
        // trigger switch if in select - restrict choices
        switch ($logtype) {
            case 'in':
                $valid_logtype = "Manual Stock Correction (In)";
                $final_qty = $qty;
                if ($wcontainer) {
                    $fccontainer = $ccontainer;
                    $usage_source = "WHOLE_SEALED_CONTAINER_ADDED";
                } else {
                    $usage_source = "PARTIAL_ADDITION";
                }
                break;
            case 'out':
                $valid_logtype = "Manual Stock Correction (Out)";
                $final_qty = $qty * -1;
                if ($wcontainer) {
                    $fccontainer = $ccontainer * -1;
                    $usage_source = "WHOLE_SEALED_CONTAINER_OUT";
                } else {
                    $usage_source = "FROM_OPENED_CONTAINER";
                }
                break;
            case 'lost':
                $valid_logtype = "Lost/Damaged Item";
                $final_qty = $qty * -1;
                if ($wcontainer) {
                    $fccontainer = $ccontainer * -1;
                    $usage_source = "WHOLE_SEALED_CONTAINER_LOST";
                } else {
                    $usage_source = "PARTIAL_LOST_FROM_OPENED";
                }
                break;
            case 'used':
                $valid_logtype = "Used";
                $final_qty = $qty * -1;
                if ($wcontainer) {
                    $fccontainer = $ccontainer * -1;
                    $usage_source = "WHOLE_SEALED_CONTAINER_USED";
                } else {
                    $usage_source = "FROM_OPENED_CONTAINER";
                }
                break;
            case 'scrapped':
                $valid_logtype = "Trashed Item";
                $final_qty = $qty * -1;
                if ($wcontainer) {
                    $fccontainer = $ccontainer * -1;
                    $usage_source = "WHOLE_SEALED_CONTAINER_LOST_DAMAGED";
                } else {
                    $usage_source = "PARTIAL_SCRAPPED_FROM OPENED";
                }
                break;
            default:
                http_response_code(400);
                echo "Invalid adjustment type.";
                exit();
        }
    }

    // if checked
    if ($wcontainer && $final_qty < 0) {
        // get from database
        $oremainingcont = get_chem_containercount($conn, $chemId);
        if (isset($chemcapacity['error'])) {
            http_response_code(400);
            echo $chemcapacity['error'];
            exit();
        }
        if ($oremainingcont < abs($ccontainer)) {
            http_response_code(400);
            echo "Cannot reduce container count below the current available containers.";
            exit();
        }
    }

    if (empty($notes)) {
        http_response_code(400);
        echo "Please explain the reason for adjustment at the notes section.";
        exit();
    }

    $adjust = adjust_chemical($conn, $chemId, $valid_logtype, $fccontainer, $final_qty, $notes, $user, $role, $branch, $usage_source);
    if (isset($adjust['error'])) {
        http_response_code(400);
        echo $adjust['error'];
        exit();
    } elseif ($adjust) {
        http_response_code(200);
        echo json_encode(['success' => 'Changes Applied.']);
        exit();
    } else {
        http_response_code(400);
        echo "An unknown error occured. Please try again later.";
        exit();
    }
}


if (isset($_POST['restock']) && $_POST['restock'] === 'true') {
    $id = $_POST['id'];
    $value = $_POST['restock_value'];
    $pwd = $_POST['pwd'];
    $note = $_POST['note'];

    if (!is_numeric(trim($id)) || empty($id)) {
        http_response_code(400);
        echo "Invalid ID. Please refresh the page and try again.";
        exit();
    }

    if (!is_numeric(trim($value)) || empty($value)) {
        http_response_code(400);
        echo "Invalid restock value.";
        exit();
    }

    if ($value <= 0) {
        http_response_code(400);
        echo "Restock value should not be less or equal to zero.";
        exit();
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo "Wrong password.";
        exit();
    }

    $restock = restock_item($conn, $id, $value, $author, $user, $role, $note, $branch);
    if (isset($restock['error'])) {
        http_response_code(400);
        echo $restock['error'];
        exit();
    } else if ($restock) {
        http_response_code(200);
        echo json_encode(['success' => 'Restock success']);
        exit();
    } else {
        http_response_code(400);
        echo 'An unknown error has occured. Please try again later.';
        exit();
    }
}

if (isset($_POST['return_chemical']) && $_POST['return_chemical'] === 'true') {

    $chem_id = $_POST['returnChemicalId'];
    $trans_id = $_POST['return_transaction'] ?? NULL;
    $opened_qty = $_POST['opened_container'];
    $closed_qty = $_POST['container_count'];
    $entered_unit = $_POST['return_unit'];
    $current_location = $_POST['return_currentLocation'];
    $pwd = $_POST['baPwd'];

    // exit();

    if (!$trans_id) {
        http_response_code(400);
        echo "Please select a valid transaction ID associated with the dispatched item.";
        exit();
    }

    if (!is_numeric($chem_id) || !is_numeric($trans_id)) {
        http_response_code(400);
        echo "Invalid ID passed.";
        exit();
    }

    if (!is_numeric($opened_qty) || !is_numeric($closed_qty)) {
        http_response_code(400);
        echo "Invalid input passed.";
        exit();
    }

    if ($opened_qty === '' || $closed_qty === '' || empty($pwd)) {
        http_response_code(400);
        echo "Please fill all fields.";
        exit();
    }

    if (empty($entered_unit) || $entered_unit === NULL || !is_string($entered_unit)) {
        http_response_code(400);
        echo "Invalid quantity unit.";
        exit();
    }


    // convert unit
    $original_data = get_chemical($conn, $chem_id);
    $original_unit = $original_data['quantity_unit'];
    if ($entered_unit !== $original_unit) {
        $convert = convert_to_main_unit($entered_unit, $original_unit, $opened_qty);
        if (isset($convert['error'])) {
            http_response_code(400);
            echo $convert['error'];
            exit();
        }
        $opened_qty = $convert;
    }


    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo "Invalid password.";
        exit();
    }

    $return = return_dispatched_chemical($conn, $chem_id, $trans_id, $opened_qty, $closed_qty);
    if (isset($return['error'])) {
        http_response_code(400);
        echo $return['error'];
        exit();
    } else if ($return) {
        http_response_code(200);
        echo json_encode(['success' => 'Chemicals returned!']);
        exit();
    } else {
        http_response_code(400);
        echo "An unknown error occurred. Please try again later.";
        exit();
    }
}


if (isset($_POST['dispatch']) && $_POST['dispatch'] === 'true') {
    $id = $_POST['dispatchChemicalId'];
    $dispatchAll = isset($_POST['dispatchAll']);
    $transaction = $_POST['dispatch-transaction'];
    $clocation = $_POST['currentLocation'];
    $pwd = $_POST['baPwd'];

    $dispatch_value = 0;
    $include_opened = NULL;
    if (!$dispatchAll) {
        $dispatch_value = $_POST['dispatchValue'];
        $include_opened = isset($_POST['includeOpened']);
    }

    if (empty($transaction) || !is_numeric($transaction)) {
        http_response_code(400);
        echo "Please select a valid transaction with this corresponding item.";
        exit();
    }

    $transaction_status = check_status($conn, $transaction);
    if ($transaction_status != "Accepted") {
        http_response_code(400);
        echo "Only approved transactions are allowed to be dispatched. $transaction_status";
        exit();
    }

    if (!is_numeric($id) || $id === NULL) {
        http_response_code(400);
        echo 'Chemical ID not found. Please try again later.';
        exit();
    }

    if (!is_numeric($dispatch_value)) {
        http_response_code(400);
        echo "dispatch value should be a number.";
        exit();
    }

    if ($clocation === "Dispatched") {
        http_response_code(400);
        echo "This chemical is already dispatched. Please select an available chemical.";
        exit();
    }

    if (!validate($conn, $pwd)) {
        http_response_code(400);
        echo "Wrong Password.";
        exit();
    }

    if ($dispatchAll) {
        $dispatch = dispatch_all_chemical($conn, $id, $transaction);
    } else {
        $dispatch = dispatch_chemical($conn, $id, $transaction, $dispatch_value, $include_opened);
    }

    if (isset($dispatch['error'])) {
        http_response_code(400);
        echo $dispatch['error'];
        exit();
    } else if ($dispatch) {
        http_response_code(200);
        echo json_encode(['success' => 'Dispatch Success!']);
        exit();
    } else {
        http_response_code(400);
        echo "An unknown error has occured. Please try again later.";
        exit();
    }
}
