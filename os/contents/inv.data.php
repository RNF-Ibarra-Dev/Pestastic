<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

// user information

$role = "branchadmin";
$user = $_SESSION['baID'];
$branch = $_SESSION['branch'];


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
    $level = $_POST['edit-chemLevel'];
    $ed = $_POST['edit-expDate'] ?? null;
    $dr = $_POST['edit-receivedDate'] ?? null;
    $notes = $_POST['edit-notes'];
    $containerCount = $_POST['edit-containerCount'];
    $contSize = $_POST['edit-containerSize'];
    $pwd = $_POST['baPwd'];

    $expDate = date("Y-m-d", strtotime($ed));
    $dateRec = date("Y-m-d", strtotime($dr));

    if (empty($name) || empty($brand) || empty($level)) {
        http_response_code(400);
        echo 'Make sure to fill up required forms.';
        exit();
    }

    if (empty($expDate)) {
        $expDate = '2025-01-01';
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

    $edit = editChem($conn, $id, $name, $brand, $level, $expDate, $dateRec, $notes, $branch, $upBy, $contSize, $containerCount, 1);
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
    $level = $_POST['chemLevel'] ?? [];
    $expDate = $_POST['expDate'] ?? [];
    $containerSize = $_POST['containerSize'] ?? [];
    $containerCount = $_POST['containerCount'] ?? [];
    $baPwd = $_POST['baPwd'];

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

    if (empty($baPwd)) {
        http_response_code(400);
        echo 'Empty Password.';
        exit;
    }

    if (!validateOS($conn, $baPwd)) {
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

    $a = addChemv2($conn, $data, $branch, $addedBy, 1);
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
    'scrapped'
];

if (isset($_POST['adjust']) && $_POST['adjust'] === 'true') {
    $chemId = $_POST['chemid'];
    $qty = isset($_POST['qty']) ? $_POST['qty'] : 0;
    $logtype = $_POST['logtype'];
    $ologtype = isset($_POST['other_logtype']) ? $_POST['other_logtype'] : NULL;
    $op = isset($_POST['operator']) ? $_POST['operator'] : NULL;
    $notes = $_POST['notes'];
    $wcontainer = isset($_POST['containerchk']);
    $ccontainer = isset($_POST['containercount']) ? $_POST['containercount'] : (int) 0;


    if (!$wcontainer) {
        if (empty($qty)) {
            http_response_code(400);
            echo "Quantity is required.";
            exit();
        }
    } else {
        // $chemcapacity = get_chem_capacity($conn, $chemId);
        $oremainingcont = get_chem_containercount($conn, $chemId);
        if (isset($chemcapacity['error'])) {
            http_response_code(400);
            echo $chemcapacity['error'];
            exit();
        }
        // $qty = (int) $chemcapacity;
    }

    if (!is_numeric($chemId) || empty($chemId)) {
        http_response_code(400);
        echo "Invalid Chemical.";
        exit();
    }

    if (!is_numeric($qty)) {
        http_response_code(400);
        echo "Quantity must be a valid number.";
        exit();
    }

    if ($wcontainer) {
        if ($ccontainer <= 0) {
            http_response_code(400);
            echo "Invalid container count.";
            exit();
        }
    }

    $valid_qty = (float) $qty;

    if (!in_array($logtype, $logtypes)) {
        // if other type is null, logtype should be restricted and only fixed datas are allowed
        if ($ologtype === NULL) {
            http_response_code(400);
            echo "Please choose a valid adjustment type.";
            exit();
        }
        // if not null pass to final variable
        $valid_logtype = ucwords($ologtype);

        // check if positive or not
        if ($op === NULL) {
            http_response_code(400);
            echo "Please specify if the quantity should be added or subtracted.";
            exit();
        } else if ($op === 'add') {
            $final_qty = $valid_qty;
        } else if ($op === 'subtract') {
            $final_qty = $valid_qty * -1;
        }
    } else {
        // trigger switch if in select - restrict choices
        switch ($logtype) {
            case 'in':
                $valid_logtype = "Manual Stock Correction (In)";
                $final_qty = $valid_qty;
                break;
            case 'out':
                $valid_logtype = "Manual Stock Correction (Out)";
                $final_qty = $valid_qty * -1;
                break;
            case 'lost':
                $valid_logtype = "Lost/Damaged Item";
                $final_qty = $valid_qty * -1;
                break;
            case 'scrapped':
                $valid_logtype = "Trashed Item";
                $final_qty = $valid_qty * -1;
                break;
            default:
                http_response_code(400);
                echo "Invalid adjustment type.";
                exit();
        }
    }

    // if negative, it will be subtracted
    if ($final_qty < 0) {
        if ($wcontainer) {
            if ($oremainingcont < $ccontainer) {
                http_response_code(400);
                echo "Cannot reduce container count below the current available containers.";
                exit();
            }
            $ccontainer = (int) $ccontainer * -1;
        }
    }

    if (empty($notes)) {
        http_response_code(400);
        echo "Please explain the reason for adjustment at the notes section.";
        exit();
    }
    // valid logtype, final qty

    $adjust = adjust_chemical($conn, $chemId, $valid_logtype, $ccontainer, $final_qty, $notes, $user, $role, $branch);
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
