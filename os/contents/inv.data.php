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

    if(strtotime($expDate) < strtotime($dateRec)){
        http_response_code(400);
        echo 'Expiry date cannot be before the received date.';
        exit();
    }

    if(strtotime($dateRec) > strtotime(date('Y-m-d'))){
        http_response_code(400);
        echo 'Invalid received date.';
        exit();
    }

    if(strtotime($expDate) < strtotime(date('Y-m-d'))){
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

    if(empty($pwd)){
        http_response_code(400);
        echo 'Password verification cannot be empty.';
        exit();
    }

    if(!validateOS($conn, $pwd)){
        http_response_code(400);
        echo 'Incorrect Password.';
        exit();
    }

    $delete = deleteChem($conn, $chemId);
    if(!$delete){
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
