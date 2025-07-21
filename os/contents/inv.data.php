<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

// user information

$role = $_SESSION['user_role'];
$user = $_SESSION['baID'];
$branch = $_SESSION['branch'];

$units = ['mg', 'g', 'kg', 'L', 'mL'];


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

$valid_location = ['main_storage', 'dispatched'];
// edit
if (isset($_POST['action']) && $_POST['action'] == 'edit') {

    $id = $_POST['edit-id'];
    $checkReq = check_request($conn, $id);
    if ($checkReq) {
        echo "Unable to edit unapproved chemical.";
        exit();
    }

    $usn = $_SESSION['baUsn'];
    $saID = $_SESSION['baID'];
    $branch = $_SESSION['branch'];
    $empId = $_SESSION['empId'];

    $upBy = "$usn | Employee no. $empId";

    $name = $_POST['edit-name'];
    $brand = $_POST['edit-chemBrand'];
    $ed = $_POST['edit-expDate'] ?? null;
    $dr = $_POST['edit-receivedDate'] ?? null;
    $unit = $_POST['edit-chemUnit'];
    $notes = $_POST['edit-notes'];
    $contSize = $_POST['edit-containerSize'];
    $location = $_POST['location'];
    $threshold = $_POST['edit-restockThreshold'];
    $pwd = $_POST['baPwd'];

    $expDate = date("Y-m-d", strtotime($ed));
    $dateRec = date("Y-m-d", strtotime($dr));

    if (empty($name) || empty($brand) || empty($unit)) {
        http_response_code(400);
        echo 'Make sure to fill up required forms.';
        exit();
    }

    // location and threshold error handling

    if (!in_array($unit, $units)) {
        http_response_code(400);
        echo "Error. Invalid Unit.";
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

    if (!validateOS($conn, $pwd)) {
        http_response_code(400);
        echo 'Incorrect Password.';
        exit();
    }

    $edit = editChem($conn, $id, $name, $brand, $expDate, $dateRec, $notes, $branch, $upBy, $contSize, $unit, $location, $threshold);
    if (isset($edit['error'])) {
        http_response_code(400);
        echo $edit['error'];
        exit();
    }
    http_response_code(200);
    echo json_encode(['success' => 'Changes Saved.']);
    exit();
}

// add
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $loggedId = $_SESSION['baID'];
    $loggedUsn = $_SESSION['baUsn'];
    $branch = $_SESSION['branch'];
    $empId = $_SESSION['empId'];
    $notes = $_POST['notes'];
    $name = $_POST['name'] ?? [];
    $receivedDate = $_POST['receivedDate'] ?? [];
    $brand = $_POST['chemBrand'] ?? [];
    $unit = $_POST['chemUnit'] ?? [];
    $expDate = $_POST['expDate'] ?? [];
    $containerSize = $_POST['containerSize'] ?? [];
    $containerCount = $_POST['containerCount'] ?? [];
    $location = $_POST['location'] ?? [];
    $threshold = $_POST['restockThreshold'] ?? [];
    $baPwd = $_POST['baPwd'];

    $addedBy = "[$loggedId] - $loggedUsn";

    if (empty($name) || empty($brand) || empty($unit) || empty($containerCount) || empty($containerSize)) {
        http_response_code(400);
        echo 'Fields cannot be empty.';
        exit;
    }

    if (empty($baPwd)) {
        http_response_code(400);
        echo 'Empty Password.';
        exit;
    }
    // error handling threshold and location

    if (!validateOS($conn, $baPwd)) {
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
        'location' => $location,
        'threshold' => $threshold
    ];

    $a = addChemv2($conn, $data, $branch, $addedBy, 1);
    if (isset($a['errorMessage'])) {
        http_response_code(400);
        echo $a['errorMessage'];
        exit;
    } else {
        http_response_code(200);
        echo json_encode(['success' => 'Chemical Entry Added!']);
        exit();
    }
}

// delete
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_SESSION['baID'];
    $pwd = $_POST['baPwd'];
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

    if (!validateOS($conn, $pwd)) {
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



if (isset($_GET['addrow']) && $_GET['addrow'] === 'true') {
    $uid = uniqid();
    ?>
    <div class="add-row-container">
        <hr class="my-2">
        <div class="row mb-2 pe-2">
            <div class="col-lg-3 mb-2">
                <label for="name-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Chemical Name</label>
                <input type="text" name="name[]" id="name-<?= htmlspecialchars($uid) ?>" class="form-control form-add"
                    autocomplete="one-time-code">
            </div>
            <div class="col-lg-3 mb-2">
                <label for="chemBrand-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Chemical Brand</label>
                <input type="text" name="chemBrand[]" id="chemBrand-<?= htmlspecialchars($uid) ?>"
                    class="form-control form-add" autocomplete="one-time-code">
            </div>
            <div class="col-lg-2 mb-2">
                <label for="containerSize-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Container Size</label>
                <input type="text" name="containerSize[]" id="containerSize-<?= htmlspecialchars($uid) ?>"
                    class="form-control form-add" autocomplete="one-time-code">
            </div>
            <div class="col-lg-2 mb-2">
                <label for="chemUnit-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Chemical Unit:</label>
                <select name="chemUnit[]" id="chemUnit-<?= htmlspecialchars($uid) ?>" class="form-select"
                    autocomplete="one-time-code">
                    <option value="" selected>Choose Chemical Unit</option>
                    <option value="mg">mg</option>
                    <option value="g">g</option>
                    <option value="kg">kg</option>
                    <option value="L">L</option>
                    <option value="mL">mL</option>
                </select>
            </div>
            <div class="col-lg-2 mb-2">
                <label for="containerCount-<?= htmlspecialchars($uid) ?>" class="form-label fw-light">Container
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
            <div class="col-lg-2 mb-2">
                <label for="add-location" class="form-label fw-light">Chemical
                    Location:</label>
                <select name="location[]" id="add-location" class="form-select" autocomplete="one-time-code">
                    <option value="" selected>Add Location</option>
                    <option value="main_storage">Main Storage</option>
                    <option value="dispatched">Dispatched</option>
                </select>
            </div>
        </div>
        <div class="mb-2 d-flex">
            <button type="button" class="btn btn-grad remove-btn mx-auto w-50">Remove Row<i
                    class="bi bi-dash-circle ms-3"></i></button>
        </div>
    </div>

    <?php
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
            $sql = "SELECT COUNT(*) FROM chemicals WHERE chemLevel > 0 AND request = 0";
            break;
        case "dispatched":
            $sql = "SELECT COUNT(*) FROM inventory_log il WHERE il.log_type = 'Out' AND 
            NOT EXISTS (SELECT 1 FROM transactions t WHERE t.id = il.trans_id AND t.transaction_status = 'Completed')";
            break;
        case "out-of-stock":
            $sql = "SELECT COUNT(*) FROM chemicals WHERE chemLevel = 0 AND unop_cont = 0;";
            break;
        default:
            http_response_code(400);
            echo "Error in fetching counts.";
            break;
    }

    if ($branchquery !== '') {
        if (str_contains($sql, "WHERE")) {
            if ($_GET['status'] === 'Dispatched') {
                $sql .= " AND il.branch = ?";
            } else {
                $sql .= " AND $branchquery";
            }
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

if (isset($_GET['table']) && $_GET['table'] == 'true') {
    $current = isset($_GET['currentpage']) && is_numeric($_GET['currentpage']) ? $_GET['currentpage'] : 1;
    $entries = $_GET['hideentries'];

    $limitstart = ($current - 1) * $pageRows;

    $sql = "SELECT * FROM chemicals";

    $sql .= $entries === 'true' ? " WHERE request = 0 " : ' ';

    $sql .= "ORDER BY request DESC, id DESC LIMIT " . $limitstart
        . ", " . $pageRows . ";";

    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);


    // echo "<caption class='text-light'>List of all shit.</caption>";

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row["name"];
            $brand = $row["brand"];
            $level = $row["chemLevel"];
            $expDate = $row["expiryDate"];
            $request = $row['request'];
            $now = date("Y-m-d");
            $exp = date_create($expDate);
            $remcom = $row['unop_cont'];
            $contsize = $row['container_size'];
            ?>
            <tr class="text-center">
                <td scope="row">
                    <?=
                        $request === '1' ? "<i class='bi bi-exclamation-diamond text-warning me-2' data-bs-toggle='tooltip' title='For Approval'></i><strong>" . htmlspecialchars($name) . "</strong><br>(For Approval)" : htmlspecialchars($name);
                    ?>
                </td>
                <td><?= htmlspecialchars($brand) ?></td>
                <td>
                    <?= htmlspecialchars("$level ml  / $contsize ml") ?>
                </td>
                <td><?= htmlspecialchars($remcom) ?></td>
                <td class="<?= $expDate == $now ? 'text-warning' : ($expDate < $now ? 'text-danger' : '') ?>">
                    <?= htmlspecialchars(date_format($exp, "F j, Y")) ?>
                </td>
                <td><?= $level === 0 ? "<span class='bg-danger px-2 py-1 bg-opacity-25 rounded-pill'>Out of Stock</span>" : ($level <= $contsize * 0.2 ? "<span class='bg-warning px-2 py-1 bg-opacity-25 rounded-pill'>Low Stock</span>" : "<span class='bg-success px-2 py-1 bg-opacity-25 rounded-pill'>Good</span>") ?>
                </td>
                <td>
                    <div class="d-flex justify-content-center">
                        <!-- add dispatch/return chem -->
                        <button type="button" id="editbtn" class="btn btn-sidebar " data-chem="<?= $id ?>"><i
                                class="bi bi-info-circle"></i></button>
                        <button type="button" id="editbtn" class="btn btn-sidebar editbtn" data-chem="<?= $id ?>"><i
                                class="bi bi-info-circle"></i></button>
                        <button type="button" class="btn btn-sidebar delbtn" data-bs-toggle="modal" data-bs-target="#deleteModal"
                            data-id="<?= $id ?>"><i class="bi bi-trash"></i></button>
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
$logtypes = [
    'in',
    'out',
    'lost',
    'scrapped',
    'used'
];

if (isset($_POST['adjust']) && $_POST['adjust'] === 'true') {
    $chemId = $_POST['chemid'];
    $qty = isset($_POST['qty']) ? $_POST['qty'] : (float) 0;
    $logtype = $_POST['logtype'];
    $ologtype = isset($_POST['other_logtype']) ? $_POST['other_logtype'] : NULL;
    $op = isset($_POST['operator']) ? $_POST['operator'] : NULL;
    $notes = $_POST['notes'];

    $wcontainer = isset($_POST['containerchk']);
    // no of container used
    $ccontainer = isset($_POST['containercount']) ? $_POST['containercount'] : (int) 0;

    if (!$wcontainer) {
        if (empty($qty) || !is_numeric($qty)) {
            http_response_code(400);
            echo "Quantity must be a valid number and should not be empty.";
            exit();
        }
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
    // valid logtype, final qty

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

if (isset($_GET['trans_select']) && $_GET['trans_select'] === 'true') {
    $sql = "SELECT id from transactions;";
    $res = mysqli_query($conn, $sql);
    echo "<option value='' selected>Select Transaction No.</option>";
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            ?>
            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></option>

            <?php
        }
    }
    exit();
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


    if (!validateOS($conn, $pwd)) {
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

if (isset($_GET['transaction_options']) && $_GET['transaction_options'] === 'true') {
    $sql = "SELECT id FROM transactions WHERE transaction_status = 'Accepted';";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        echo "<option value=''>Select Transaction</option>";
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
        echo "<option disabled>No available accepted transactions</option>";
    }
    mysqli_close($conn);
}

// opened and closed quantity should be the total of what will return
if (isset($_POST['return_chemical']) && $_POST['return_chemical'] === 'true') {

    $chem_id = $_POST['returnChemicalId'];
    $trans_id = $_POST['return_transaction'];
    $opened_qty = $_POST['opened_container'];
    $closed_qty = $_POST['container_count'];
    $current_location = $_POST['return_currentLocation'];
    $pwd = $_POST['baPwd'];

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

    if (!validateOS($conn, $pwd)) {
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
