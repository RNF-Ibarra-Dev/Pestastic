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
        echo "<option selected disabled>No available accepted transactions</option>";
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