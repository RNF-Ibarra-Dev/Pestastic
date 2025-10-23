<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');


// get/fetch
if (isset($_GET['getChem']) && $_GET['getChem'] == 'add') {
    // $activeChem = $_GET['active'];
    get_chem($conn);
} else if (isset($_GET['getTech']) && $_GET['getTech'] == 'true') {
    $active = $_GET['active'];
    get_tech($conn, $active);
} else if (isset($_GET['getProb']) && $_GET['getProb'] == 'true') {
    $checked = $_GET['checked'] ?? NULL;
    get_prob($conn, $checked);
} else if (isset($_GET['getMoreChem']) && $_GET['getMoreChem'] == 'true') {
    $status = $_GET['status'];
    get_more_chem($conn, $status);
} else if (isset($_GET['addMoreTech']) && $_GET['addMoreTech'] == 'true') {
    $rowNum = $_GET['techRowNum'];
    $active = isset($_GET['active']) ? $_GET['active'] : null;
    add_more_tech($conn, $rowNum, $active);
}

function add_more_tech($conn, $num, $active)
{

    ?>
    <div class="row mb-2">
        <div class="dropdown-center d-flex col-lg-6 mb-2" id="row-<?= $num ?>">
            <select id="add-technicianName" name="add-technicianName[]" class="form-select"
                aria-label="Default select example">
                <?= get_tech($conn, $active); ?>
            </select>
        </div>
        <div class="col-2 p-0">
            <button type="button" id="deleteTech" class="btn btn-grad py-1 px-3"><i
                    class="bi bi-dash-circle text-light text-align-middle"></i></button>
        </div>
    </div>
    <?php
}


function get_more_chem($conn, $status = '')
{
    $sql = "SELECT * FROM chemicals WHERE branch = {$_SESSION['branch']}";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo 'Error fetching chem data' . mysqli_error($conn);
        return;
    }
    $id = uniqid();
    ?>
    <div class="row mb-2 addmorechem-row">
        <div class="col-lg-6 dropdown-center d-flex flex-column">
            <select id="add-chemBrandUsed-<?= $id ?>" name="add_chemBrandUsed[]" class="form-select chem-brand-select">
                <?= get_chem($conn); ?>
                <!-- chem ajax -->
            </select>
        </div>
        <div class="col-lg-6 mb-2 ps-0 d-flex justify-content-evenly">
            <div class="d-flex flex-column">
                <input type="number" maxlength="4" id="add-amountUsed-<?= $id ?>"
                    class="form-control amt-used-input form-add me-3" autocomplete="one-time-code" step="any"
                    name='add-amountUsed[]'>
            </div>
            <span class="form-text mt-2 mb-auto">-
            </span>
            <button type="button" id="deleteChem<?= $id ?>" class="delete-chem-row btn btn-grad mb-auto py-2 px-3"><i
                    class="bi bi-dash-circle text-light"></i></button>
        </div>
    </div>
    <?php
}

function get_tech($conn, $active = null)
{
    $active = $active == null ? '' : $active;
    $sql = "SELECT * FROM technician WHERE user_branch = {$_SESSION['branch']}";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo 'Error fetching tech data' . mysqli_error($conn);
        return;
    }

    echo "<option value='#' selected>Select Technician</option>";
    echo "<hr class='dropdown-divider'>";
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['technicianId'];
        $name = $row['firstName'] . ' ' . $row['lastName'];
        $empId = $row['techEmpId'];

        ?>
        <option value="<?= $id ?>" <?= $id == $active ? 'selected' : '' ?>><?= $name . ' | Technician ' . $empId ?></option>
        <?php
    }
}

function get_chem($conn, $active = null)
{
    $active = $active == null ? '' : $active;
    $sql = "SELECT * FROM chemicals WHERE request = 0 AND chemLevel != 0 AND branch = {$_SESSION['branch']} ORDER BY id DESC;";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo 'Error fetching chem data' . mysqli_error($conn);
        return;
    }

    echo "<option value='#' selected>Select Chemical</option>";
    echo "<hr class='dropdown-divider'>";

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $brand = $row['brand'];
        $name = $row['name'];
        $level = (int) $row['chemLevel'];
        $req = (int) $row['request'];
        $unit = $row['quantity_unit'];
        ?>
        <option value="<?= htmlspecialchars($id) ?>" <?= $id == $active ? 'selected' : '' ?>>
            <?= htmlspecialchars($name) . " | " . htmlspecialchars($brand) . " | " . htmlspecialchars("$level $unit") ?>
        </option>
        <?php
    }
}

function get_prob($conn, $checked = null)
{
    $checked = $checked == null ? [] : $checked;
    $sql = "SELECT * FROM pest_problems ORDER BY id ASC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo 'Error fetching prob data' . mysqli_error($conn);
        return;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $problem = $row['problems'];
        ?>
        <input type="checkbox" value="<?= $id ?>" name="pest_problems[]" class="btn-check" id="add-<?= $id ?>"
            autocomplete="off" <?= in_array($problem, $checked) ? 'checked' : '' ?>>
        <label class="btn" for="add-<?= $id ?>"><?= $problem ?></label>
        <?php
    }
}

if (isset($_GET['row']) && $_GET['row'] == 'tech') {

    $disabled = isset($_GET['disabled']) ? $_GET['disabled'] : null;

    $sql = "SELECT * FROM transaction_technicians;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo json_encode(['type' => 'stmt', 'message' => mysqli_stmt_error($stmt)]);
        exit();
    }
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);
    $numRows = mysqli_num_rows($results);

    if ($numRows > 0) {

        ?>
        <div class="mb-3 inline-flex row" id="techRow">
            <div class="col-lg-6 d-flex">
                <select id="edit-technicianName" name="addTechnician[]" class="form-select me-2"
                    aria-label="Default select example">
                    <?php
                    get_tech($conn, false);
                    ?>
                </select>
                <button type="button" id="deleterow" class="btn btn-grad mt-auto py-2 px-3"><i
                        class="bi bi-dash-circle text-light"></i></button>
            </div>

        </div>
        <?php

        mysqli_stmt_close($stmt);
        exit();
    } else {
        ?>
        <div class="mb-2 d-inline-flex w-50" id="techRow-">
            <p class="fw-light text-muted">Technicians do not exist. Please contact the developer.</p>
        </div>
        <?php
        mysqli_stmt_close($stmt);
        exit();
    }
}

if (isset($_GET['row']) && $_GET['row'] === 'chem') {
    $idd = uniqid();
    ?>
    <div class="row d-flex" id="row-#">
        <div class="col-lg-6 mb-2">
            <label for="edit-chemBrandUsed-" class="form-label fw-light" id="edit-chemBrandUsed-label">Chemical
                Used:</label>
            <select id="edit-chemBrandUsed-" name="addChem[]" class="form-select">
                <?php get_chem($conn); ?>
            </select>
        </div>

        <div class="col-lg-4 mb-2 ps-0 " id='container-amountUsed'>
            <label for="edit-amountUsed-<?= $idd ?>" class="form-label fw-light" id="edit-amountUsed-label">Amount
                Used:</label>
            <div class="d-flex flex-row">
                <input type="number" name="amountUsed[]" step="any" maxlength="4" id="edit-amountUsed-<?= $idd ?>"
                    class="form-control form-add me-3" autocomplete="one-time-code">

                <span id="passwordHelpInline" class="form-text mt-auto mb-2">
                    /ml
                </span>
            </div>

        </div>
        <div class="col-lg-2 mb-2 d-flex align-items-end">
            <button type="button" id="deleteChemRow" class="btn btn-grad py-2 px-3"><i
                    class="bi bi-dash-circle text-light"></i></button>
        </div>

    </div>
    <?php
    exit();
}

if (isset($_GET['details']) && $_GET['details'] === 'true') {
    $id = htmlspecialchars($_GET['transId']);
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['type' => 'id', 'message' => 'Invalid ID.']);
        exit();
    }
    $response = [];

    $sql = "SELECT * FROM transactions WHERE id = ? AND branch = {$_SESSION['branch']};";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo json_encode(['type' => 'stmt', 'message' => mysqli_stmt_error($stmt)]);
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    // $array = mysqli_fetch_array($result);

    if ($row = mysqli_fetch_assoc($result)) {
        $response = $row;
        $ttime = strtotime($row['transaction_time']);
        $tdate = strtotime($row['treatment_date']);
        $pstart = strtotime($row['pack_start']);
        $pend = strtotime($row['pack_exp']);
        $trans_time = date("H:i A", $ttime);
        $trans_date = date("F j, Y", $tdate);
        $package_start = date("F j, Y", $pstart);
        $package_end = date("F j, Y", $pend);
        $response['trans_time'] = $trans_time;
        $response['trans_date'] = $trans_date;
        $response['package_start'] = $package_start;
        $response['package_end'] = $package_end;
        echo json_encode(['success' => $response]);
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['type' => 'array', 'message' => 'array error']);
        mysqli_stmt_close($stmt);
        exit();
    }
    exit();
}

if (isset($_GET['treatmentname']) && $_GET['treatmentname'] === 'true') {
    $id = $_GET['id'];
    $sql = "SELECT t_name FROM treatments WHERE id = $id";
    $res = mysqli_query($conn, $sql);
    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            return $row['t_name'];
        }
    } else {
        return "Invalid ID.";
    }
}

if (isset($_GET['packagename']) && $_GET['packagename'] === 'true') {
    $id = $_GET['id'];

    if ($id == 'none') {
        echo "No Valid Package.";
        exit();
    }

    if (!ctype_digit($id)) {
        http_response_code(400);
        return "Invalid ID.";
    }

    $sql = "SELECT * FROM packages WHERE id = $id AND branch = {$_SESSION['branch']};";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $name = $row['name'];
            $sessions = $row['session_count'];
            $warranty = $row['year_warranty'];

            echo htmlspecialchars($name) . ' ' . htmlspecialchars($sessions) . ' Sessions w/ ' . htmlspecialchars($warranty) . ' years warranty.';
            exit();
        }
    } else {
        echo "No Valid Package.";
        exit();
    }
}

if (isset($_GET['getTechList']) && $_GET['getTechList'] == 'true') {
    $transId = $_GET['transId'];

    $sql = "SELECT * FROM transaction_technicians WHERE trans_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo json_encode(['type' => 'stmt', 'message' => mysqli_stmt_error($stmt)]);
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $transId);
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);
    $numRows = mysqli_num_rows($results);

    if ($numRows > 0) {
        while ($row = mysqli_fetch_assoc($results)) {
            $technician = $row['tech_info'];

            ?>
            <li class="list-group-item"><?= $technician ?></li>
            <?php
        }
        mysqli_stmt_close($stmt);
        exit();
    } else {
        ?>
        <li class="list-group-item">Technician not set. Please contact administration.</li>
        <?php
        mysqli_stmt_close($stmt);
        exit();
    }
}

if (isset($_GET['fetchdetails']) && $_GET['fetchdetails'] == 'true') {
    $transId = $_GET['transId'];
    $sql = "SELECT * FROM transactions WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo json_encode(['error' => 'STMT PREPARE ERROR. FETCH TRANSACTION DETAIL.']);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $transId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        http_response_code(200);
        echo json_encode(['success' => $row]);
        mysqli_stmt_close($stmt);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'NO RETURNED ROW']);
        mysqli_stmt_close($stmt);
    }
}

if (isset($_GET['fetch']) && $_GET['fetch'] === 'technicians') {
    $transId = $_GET['transId'];

    $sql = "SELECT * FROM transaction_technicians WHERE trans_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo json_encode(['type' => 'stmt', 'message' => mysqli_stmt_error($stmt)]);
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $transId);
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);
    $numRows = mysqli_num_rows($results);

    if ($numRows > 0) {
        while ($row = mysqli_fetch_assoc($results)) {
            $technician = $row['tech_info'];

            ?>
            <li class="list-group-item"><?= $technician ?></li>
            <?php
        }
        mysqli_stmt_close($stmt);
        exit();
    } else {
        ?>
        <li class="list-group-item">Technician not set. Please contact administration.</li>
        <?php
        mysqli_stmt_close($stmt);
        exit();
    }
}

if (isset($_GET['fetch']) && $_GET['fetch'] === 'pestproblems') {
    $transId = $_GET['transId'];

    $sql = "SELECT pest_problems.problems FROM pest_problems INNER JOIN transaction_problems ON pest_problems.id = transaction_problems.problem_id WHERE transaction_problems.trans_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(['type' => 'stmt', 'message' => mysqli_stmt_error($stmt)]);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $transId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $numRows = mysqli_num_rows($result);

    if ($numRows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $problems = $row['problems'];

            ?>
            <li class="list-group-item"><?= $problems ?></li>
            <?php
        }
        mysqli_stmt_close($stmt);
        exit();
    } else {
        ?>
        <li class="list-group-item">Pest problems are not set. Please contact administration.</li>
        <?php
        mysqli_stmt_close($stmt);
        exit();
    }
}

function get_chem_edit($conn, $active = 0)
{
    $active = $active === 0 ? 0 : (int) $active;
    $sql = "SELECT * FROM chemicals WHERE branch = {$_SESSION['branch']} ORDER BY id DESC;";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo 'Error fetching chem data' . mysqli_error($conn);
        return;
    }

    echo "<option value='#' selected>Select Chemical</option>";
    echo "<hr class='dropdown-divider'>";

    while ($row = mysqli_fetch_assoc($result)) {
        $id = (int) $row['id'];
        $brand = $row['brand'];
        $name = $row['name'];
        $level = $row['chemLevel'];
        $req = (int) $row['request'];
        $unit = $row['quantity_unit'];
        ?>
        <option value="<?= htmlspecialchars($id) ?>" <?= $id === $active ? 'selected' : '' ?>>
            <?= htmlspecialchars($name) . " | " . htmlspecialchars($brand) . " | " . htmlspecialchars("$level $unit") . ' ' . ($req === 1 ? '(Under Review)' : '') ?>
        </option>
        <?php
    }
}

if (isset($_GET['fetch']) && $_GET['fetch'] == 'chemical') {
    $transId = $_GET['transId'];

    $sql = "SELECT chem_brand, amt_used FROM transaction_chemicals WHERE trans_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(['type' => 'stmt', 'message' => mysqli_stmt_error($stmt)]);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $transId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $numRows = mysqli_num_rows($result);

    if ($numRows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $chemUsed = $row['chem_brand'];
            $amtUsed = $row['amt_used'];

            ?>
            <li class="list-group-item mb-2"><strong>Chemical:</strong> <?= $chemUsed ?><br><strong>Amount used:
                </strong><?= $amtUsed ?> ml</li>
            <?php
        }
        mysqli_stmt_close($stmt);
        exit();
    } else {
        ?>
        <li class="list-group-item">There are no logged chemicals. Please contact administration.</li>
        <?php
        mysqli_stmt_close($stmt);
        exit();
    }
}

if (isset($_GET['getChem']) && ($_GET['getChem'] == 'edit' || $_GET['getChem'] === 'finalize' || $_GET['getChem'] === 'complete' || $_GET['getChem'] === 'dispatch')) {
    $transId = $_GET['transId'];
    $status = $_GET['status'];

    $sql = "SELECT * FROM transaction_chemicals WHERE trans_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo json_encode(['type' => 'stmt', 'message' => mysqli_stmt_error($stmt)]);
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $transId);
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);
    $numRows = mysqli_num_rows($results);


    if ($numRows > 0) {
        while ($row = mysqli_fetch_assoc($results)) {
            $id = $row['chem_id'];
            $amtUsed = $row['amt_used'];
            $unit = get_unit($conn, $id);

            ?>
            <div class="row" id="row-<?= $id ?>">
                <div class="col-lg-4 mb-2">
                    <label for="edit-chemBrandUsed-<?= $id ?>" class="form-label fw-light">Chemical
                        Used:</label>
                    <select id="edit-chemBrandUsed-<?= $id ?>" name="edit_chemBrandUsed[]" class="form-select chem-brand-select">
                        <?php get_chem_edit($conn, $id); ?>
                    </select>
                </div>

                <div class="col-lg-4 mb-2 ps-0 d-flex justify-content-evenly">
                    <div class="d-flex flex-column">
                        <label for="edit-amountUsed-<?= $id ?>" class="form-label fw-light"
                            id="edit-amountUsed-label">Amount:</label>
                        <input type="number" name='edit-amountUsed[]' step="any" maxlength="4" id="edit-amountUsed-<?= $id ?>"
                            class="form-control form-add me-3" autocomplete="one-time-code" value="<?= $amtUsed ?>">
                    </div>
                    <span class="form-text mt-auto mx-3 mb-2">
                        <?= $unit ?>
                    </span>
                    <button type="button" data-row-id="<?= $id ?>" class="ef-del-btn btn btn-grad mt-auto py-2 px-3"><i
                            class="bi bi-dash-circle text-light"></i></button>
                </div>

            </div>
            <?php
        }
        mysqli_stmt_close($stmt);
        exit();
    } else {
        $idd = uniqid();
        ?>
        <p class="alert alert-warning py-2 text-center fw-light w-75 mx-auto">This transaction has no chemicals set. Chemical
            might be deleted.</p>
        <div class="row" id="row-<?= $idd ?>">
            <div class="col-lg-4 mb-2">
                <label for="edit-chemBrandUsed-<?= $idd ?>" class="form-label fw-light">Chemical
                    Used:</label>
                <select id="edit-chemBrandUsed-<?= $idd ?>" name="edit_chemBrandUsed[]" class="form-select chem-brand-select">
                    <?php get_chem($conn); ?>
                </select>
            </div>

            <div class="col-lg-4 mb-2 ps-0 d-flex justify-content-evenly">
                <div class="d-flex flex-column">
                    <label for="edit-amountUsed-<?= $idd ?>" class="form-label fw-light"
                        id="edit-amountUsed-label">Amount:</label>
                    <input type="number" name='edit-amountUsed[]' step="any" maxlength="4" id="edit-amountUsed-<?= $idd ?>"
                        class="form-control form-add me-3" autocomplete="one-time-code">
                </div>
                <span class="form-text mt-auto mx-3 mb-2">
                    -
                </span>
                <button type="button" data-row-id="<? $idd ?>" class="ef-del-btn btn btn-grad mt-auto py-2 px-3"><i
                        class="bi bi-dash-circle text-light"></i></button>
            </div>
        </div>
        <?php
        mysqli_stmt_close($stmt);
        exit();
    }
}


if (isset($_GET['notes']) && $_GET['notes'] === 'true') {
    $id = $_GET['id'];

    $sql = "SELECT notes FROM transactions WHERE id = ? AND branch = {$_SESSION['branch']};";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "stmt failed";
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_row($result)) {
        http_response_code(200);
        echo json_encode(['notes' => $row]);
        exit();
    }
}


if (isset($_GET['addrow']) && $_GET['addrow'] == 'true') {

    $status = (string) $_GET['status'];

    $idd = uniqid();
    ?>
    <div class="row" id="row-<?= $idd ?>">
        <div class="col-lg-4 mb-2">
            <label for="edit-chemBrandUsed-<?= $idd ?>" class="form-label fw-light">Chemical
                Used:</label>
            <select id="edit-chemBrandUsed-<?= $idd ?>" name="edit_chemBrandUsed[]" class="form-select chem-brand-select">
                <?php get_chem($conn); ?>
            </select>
        </div>

        <div class="col-lg-4 mb-2 ps-0 d-flex justify-content-evenly">
            <div class="d-flex flex-column">
                <label for="edit-amountUsed-<?= $idd ?>" class="form-label fw-light">Amount:</label>
                <input type="number" step="any" name='edit-amountUsed[]' maxlength="4" id="edit-amountUsed-<?= $idd ?>"
                    class="form-control form-add me-3" autocomplete="one-time-code">
            </div>
            <!-- change this line to select -->
            <span class="form-text mt-auto ms-2 mb-2">
                -
            </span>
            <button type="button" data-row-id="<?= $idd ?>" class="ef-del-btn btn btn-grad mt-auto py-2 px-3"><i
                    class="bi bi-dash-circle text-light"></i></button>
        </div>
    </div>
    <?php
    exit();
}
if (isset($_GET['finalizetrans']) && $_GET['finalizetrans'] === 'true') {
    $sql = "SELECT * FROM transactions WHERE transaction_status = 'Finalizing' AND branch = {$_SESSION['branch']} ORDER BY updated_at DESC;";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customerName = $row['customer_name'];
            $treatmentDate = $row['treatment_date'];
            $td = date('F j, Y', strtotime($treatmentDate));
            $updatedat = $row['updated_at'];
            $ua = date("h:i A", strtotime($updatedat));
            $request = $row['void_request'];
            $upby = $row['updated_by'];
            $cby = $row['created_by'];
            ?>
            <tr class="text-center">
                <td class="text-dark" scope="row"><button type="button" class="btn btn-sidebar text-dark finalize-peek-trans-btn"
                        data-trans-id="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></button></td>
                <td class="text-dark"><?= htmlspecialchars($customerName) ?></td>
                <td class="text-dark"><?= htmlspecialchars($td) ?></td>
                <td class="text-dark">
                    <?= $upby === "No User" && $cby === "No User" ? htmlspecialchars("No Recorded User.") : ($upby === $cby ? htmlspecialchars($upby) : ($upby !== 'No User' ? htmlspecialchars($upby) : htmlspecialchars($cby))) ?>
                </td>
                <td class="text-dark"><?= htmlspecialchars($ua) ?></td>
            </tr>


            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='6' class='text-center text-dark'>No finalizing transactions.</td></tr>";
    }
}

if (isset($_GET['voidrequest']) && $_GET['voidrequest'] === 'true') {
    $sql = "SELECT * FROM transactions WHERE void_request = 1 AND branch = {$_SESSION['branch']} ORDER BY updated_at DESC;";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customerName = $row['customer_name'];
            $treatmentDate = $row['treatment_date'];
            $td = date('F j, Y', strtotime($treatmentDate));
            $request = $row['void_request'];
            $upby = $row['updated_by'];
            $cby = $row['created_by'];
            $requested_at = $row['updated_at'];
            $ra = date('F j, Y h:i A', strtotime($requested_at));
            ?>
            <tr class="text-center">
                <td class="text-dark" scope="row"><button type="button" class="btn btn-sidebar text-dark check-void-req-btn"
                        data-trans-id="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></button></td>
                <td class="text-dark"><?= htmlspecialchars($customerName) ?></td>
                <td class="text-dark"><?= htmlspecialchars($upby) ?></td>
                <td class="text-dark"><?= htmlspecialchars($ra) ?></td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='5' class='text-center'>No Void Requests.</td></tr>";
    }
}


if (isset($_GET['view']) && $_GET['view'] == 'treatment') {
    $treatmentid = $_GET['transId'];
    $sql = "SELECT t_name FROM treatments WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(['type' => 'stmt', 'message' => mysqli_stmt_error($stmt)]);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $treatmentid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $numRows = mysqli_num_rows($result);

    if ($numRows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $treatment = $row['t_name'];

            ?>
            <li class="list-group-item"><?= $treatment ?></li>
            <?php
        }
        mysqli_stmt_close($stmt);
        exit();
    } else {
        ?>
        <li class="list-group-item">Treatment not set. Please contact administration.</li>
        <?php
        mysqli_stmt_close($stmt);
        exit();
    }
}

if (isset($_GET['view']) && $_GET['view'] == 'probCheckbox') {
    $transId = $_GET['transId'];

    $sql = "SELECT pest_problems.problems FROM pest_problems INNER JOIN transaction_problems ON pest_problems.id = transaction_problems.problem_id WHERE transaction_problems.trans_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(['type' => 'stmt', 'message' => mysqli_stmt_error($stmt)]);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $transId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $numRows = mysqli_num_rows($result);

    if ($numRows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $problems = $row['problems'];

            ?>
            <li class="list-group-item"><?= $problems ?></li>
            <?php
        }
        mysqli_stmt_close($stmt);
        exit();
    } else {
        ?>
        <li class="list-group-item">Pest problems are not set. Please contact administration.</li>
        <?php
        mysqli_stmt_close($stmt);
        exit();
    }
}

if (isset($_GET['view']) && $_GET['view'] == 'chemUsed') {
    $transId = $_GET['transId'];

    $sql = "SELECT chem_brand, amt_used, chem_id FROM transaction_chemicals WHERE trans_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(['type' => 'stmt', 'message' => mysqli_stmt_error($stmt)]);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $transId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $numRows = mysqli_num_rows($result);

    if ($numRows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $chemUsed = $row['chem_brand'];
            $chemId = $row['chem_id'];
            $amtUsed = $row['amt_used'];
            $unit = $unit = get_unit($conn, $chemId);
            ;

            ?>
            <li class="list-group-item mb-2"><strong>Chemical:</strong> <?= $chemUsed ?><br><strong>Amount used:
                </strong><?= $amtUsed != 0 ? "$amtUsed $unit" : 'Amount Pending' ?></li>
            <?php
        }
        mysqli_stmt_close($stmt);
        exit();
    } else {
        ?>
        <li class="list-group-item">No chemical found. Chemical might be deleted.</li>
        <?php
        mysqli_stmt_close($stmt);
        exit();
    }
}

if (isset($_GET['getunit']) && $_GET['getunit'] === 'true') {
    $chemId = $_GET['chemid'];
    if (!is_numeric($chemId)) {
        http_response_code(400);
        echo "Invalid Chemical ID.";
        exit();
    }
    $unit = get_unit($conn, $chemId);
    if (isset($unit['error'])) {
        http_response_code(400);
        echo $unit['error'];
        exit();
    } else {
        echo $unit;
        exit();
    }

}

if (isset($_GET['count']) && $_GET['count'] === 'true') {
    $status = $_GET['status'];

    $sql = "SELECT COUNT(*) FROM transactions WHERE transaction_status = ? AND branch = {$_SESSION['branch']};";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "stmt error";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $status);
    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_row($res);

    echo $row[0];
    exit();
}

function packages($conn)
{
    $sql = "SELECT * FROM packages WHERE branch = {$_SESSION['branch']};";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row['name'];
            $sessions = $row['session_count'];
            $warranty = $row['year_warranty'];
            ?>
            <option value="<?= $id ?>">
                <?= htmlspecialchars($name) . ' | ' . htmlspecialchars($warranty) . " Years Warranty" . ($sessions != 1 ? " | (" . htmlspecialchars($sessions) . " Sessions) " : '') ?>
            </option>
            <?php
        }
    } else {
        ?>
        <option disabled>No Current Package.</option>
        <?php
    }
}

if (isset($_GET['packages']) && $_GET['packages'] === 'true') {
    ?>
    <option value="none">None</option>
    <?php
    packages($conn);
}

if (isset($_GET['treatments']) && $_GET['treatments'] === 'true') {
    $sql = "SELECT * FROM treatments;";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $n = $row['t_name'];
            ?>
            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($n) ?></option>
            <?php
        }
    }
}


function get_warranty_duration($conn, $id)
{
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid ID. ID passed: $id"]);
        exit();
    }

    $sql = "SELECT year_warranty FROM packages WHERE id = $id AND branch = {$_SESSION['branch']};";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            return $row['year_warranty'];
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'No data to retuurn.']);
        exit();
    }
}


if (isset($_POST['pack_exp']) && $_POST['pack_exp'] === 'true') {
    $date = $_POST['date'];
    $id = $_POST['pid'];

    $duration = get_warranty_duration($conn, $id);

    if (isset($duration['error'])) {
        http_response_code(400);
        return $duration['error'];
    }

    $fdate = date("F j, Y", strtotime($date . "+ $duration years"));
    echo $fdate;
    exit();
}


if (isset($_GET['get_ir']) && $_GET['get_ir'] === 'true') {
    $sql = "SELECT * FROM inspection_reports WHERE branch = {$_SESSION['branch']};";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        echo "<option value='' selected>Select report</option>";
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $customer = $row['customer'];
            $branch = $row['branch'];
            $location = $row['property_location'];
            ?>
            <option value="<?= htmlspecialchars($id) ?>" data-c-name="<?= htmlspecialchars($customer) ?>"
                data-branch="<?= htmlspecialchars($branch) ?>" data-loc="<?= htmlspecialchars($location) ?>">Report No.
                <?= htmlspecialchars("$id - $customer") ?>
            </option>
            <?php
        }
    } else {
        ?>
        <option selected disabled>No inspection reports found.</option>
        <?php
    }
}

if (isset($_GET['ir_details']) && $_GET['ir_details'] === 'true') {
    $ir_id = $_GET['id'];
    if (!is_numeric($ir_id)) {
        http_response_code(400);
        echo "ID passed not valid.";
        exit;
    }

    $sql = "SELECT * FROM inspection_reports WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "Statement error when fetching report details. Please try again later.";
        exit;
    }
    mysqli_stmt_bind_param($stmt, 'i', $ir_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $row['up_at'] = date("F j, Y", strtotime($row['updated_at']));
        $row['add_at'] = date("F j, Y", strtotime($row['added_at']));
        $row['branch'] = get_branch_details($conn, $row['branch']);
        $row['ltd'] = date("F j, Y", strtotime($row['last_treatment_date']));
        echo json_encode($row);
        mysqli_stmt_close($stmt);
        exit;
    }

    http_response_code(400);
    echo "Data not found.";
    mysqli_stmt_close($stmt);
    exit;

}

if (isset($_GET['ir_pest_problems']) && $_GET['ir_pest_problems'] === 'true') {
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        http_response_code(400);
        echo "Invalid ID passed.";
        exit;
    }
    $sql = "SELECT p.problems FROM pest_problems p JOIN inspection_problems ip ON ip.pest_problem = p.id WHERE ip.inspection_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "Fetching database statement error.";
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            // $pp = get_pest_problem_details($conn, $row['pest_problem']);
            // $pprob = $pp['problems'];
            $pprob = $row['problems'];
            ?>
            <li class="list-group-item"><?= htmlspecialchars($pprob) ?></li>
            <?php
        }
    } else {
        ?>
        <li class="list-group-item">No reported pest problem.</li>
        <?php
    }
    mysqli_stmt_close($stmt);
    exit;
}

if (isset($_GET['ir_problems_array']) && $_GET['ir_problems_array'] === 'true') {
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        http_response_code(400);
        echo "Invalid ID passed.";
        exit;
    }
    $sql = "SELECT p.problems FROM pest_problems p JOIN inspection_problems ip ON ip.pest_problem = p.id WHERE ip.inspection_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "Fetching database statement error.";
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $problems = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $problems[] = $row['problems'];
    }

    // echo var_dump($problems);
    get_prob($conn, $problems);
    mysqli_stmt_close($stmt);
    exit;
}