<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');


// get/fetch
if (isset($_GET['getsmt']) && $_GET['getsmt'] === 'tech') {
    $tech = $_GET['active'];
    get_tech($conn, $tech);
} else if (isset($_GET['getProb']) && $_GET['getProb'] === 'true') {
    $active = isset($_GET['active']) ? $_GET['active'] : null;
    $disabled = isset($_GET['disabled']) ? $_GET['disabled'] : null;
    get_prob($conn, $active, $disabled);
} else if (isset($_GET['getsmt']) && $_GET['getsmt'] === 'chem') {
    $active = isset($_GET['active']) ? $_GET['active'] : null;
    $disabled = isset($_GET['disabled']) ? $_GET['disabled'] : null;
    get_chem($conn, $active, $disabled);
}

function get_tech($conn, $active = null, $disabled = null)
{
    $active = $active == null ? '' : $active;
    $sql = 'SELECT * FROM technician';
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
        <option value="<?= $id ?>" <?= $id == $active ? 'selected' : '' ?><?= $id == $disabled ? 'disabled' : '' ?>>
            <?= $name . ' | Technician ' . $empId ?>
        </option>
    <?php
    }
}
function get_chem($conn, $active = null, $disabled = null)
{
    $active = $active == null ? '' : $active;
    $disabled = $disabled == null ? '' : $disabled;
    $sql = 'SELECT * FROM chemicals';
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
        $level = $row['chemLevel'];
    ?>
        <option value="<?= $id ?>" <?= $level <= 0 ? 'disabled' : '' ?><?= $id == $active ? 'selected' : '' ?> <?= $id == $disabled ? 'disabled' : '' ?>>
            <?= $name . " | " . $brand . " | " . $level . "ml " . $id ?>
        </option>
    <?php
    }
}

function get_prob($conn, $checked = null, $disabled = null)
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
            autocomplete="off" <?= in_array($problem, $checked) ? 'checked' : '' ?> <?= $id == $disabled ? 'disabled' : '' ?>>
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
                    get_tech($conn, false, $disabled);
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
            <label for="edit-amountUsed-" class="form-label fw-light" id="edit-amountUsed-label">Amount Used:</label>
            <div class="d-flex flex-row">
                <input type="number" name="amountUsed[]" maxlength="4" id="edit-amountUsed-"
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
        echo json_encode(['error'=> 'NO RETURNED ROW']);
        mysqli_stmt_close($stmt);
    }
}

if (isset($_GET['fetch']) && $_GET['fetch'] === 'technicians'){
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

