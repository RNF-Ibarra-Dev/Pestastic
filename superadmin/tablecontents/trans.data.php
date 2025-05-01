<?php
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");

if (isset($_GET['voidreqs']) && $_GET['voidreqs'] === 'true') {
    $sql = "SELECT * FROM transactions WHERE void_request = 1 ORDER BY id DESC;";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customerName = $row['customer_name'];
            $treatmentDate = $row['treatment_date'];
            $request = $row['void_request'];
            ?>
            <tr class="text-center">
                <td scope="row"><?= htmlspecialchars($id) ?></td>
                <td><?= htmlspecialchars($customerName) ?></td>
                <td><?= htmlspecialchars($treatmentDate) ?></td>
                <td>
                    <div class="d-flex justify-content-center">
                        <?php
                        if ($request === "1") {
                            ?>
                            <input type="checkbox" class="btn-check" value="<?= $id ?>" name="trans[]" id="c-<?= $id ?>"
                                autocomplete="off">
                            <label class="btn btn-outline-dark" for="c-<?= $id ?>"><i class="bi bi-check-circle me-2"></i>Void
                                Transaction</label>
                            <?php
                        } else {
                            ?>
                            <p class="text-muted">Voided.</p>
                        <?php } ?>
                    </div>
                </td>
            </tr>


            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='4' class='text-center'>No Void Requests.</td></tr>";
    }
}

if (isset($_GET['table']) && $_GET['table'] == 'table') {
    $sql = "SELECT * FROM transactions;";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customerName = $row['customer_name'];
            $treatmentDate = $row['treatment_date'];
            $treatment = $row['treatment'];
            $createdAt = $row['created_at'];
            $updatedAt = $row['updated_at'];
            $status = $row['transaction_status'];
            ?>
            <tr class="text-center">
                <td scope="row"><?= $id ?></td>
                <td><?= htmlspecialchars($customerName) ?></td>
                <td><?= htmlspecialchars($treatmentDate) ?></td>
                <td><?= htmlspecialchars($treatment) ?></td>
                <td><?= htmlspecialchars($status) ?></td>
                <td>
                    <div class="d-flex justify-content-center">
                        <button id="tableDetails" disable-data-bs-toggle="modal" disabled-data-bs-target="#details-modal"
                            data-trans-id="<?= $id ?>" class="btn btn-sidebar me-2">Details</button>
                    </div>
                </td>
            </tr>


            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='6' class='text-center'>Search does not exist.</td></tr>";
    }
}



if (isset($_GET['getChem']) && $_GET['getChem'] == 'add') {
    // $activeChem = $_GET['active'];
    get_chem($conn);
} else if (isset($_GET['getTech']) && $_GET['getTech'] == 'true') {
    $active = $_GET['active'];
    get_tech($conn, $active);
} else if (isset($_GET['getProb']) && $_GET['getProb'] == 'true') {
    $checked = $_GET['checked'];
    get_prob($conn, $checked);
} else if (isset($_GET['getMoreChem']) && $_GET['getMoreChem'] == 'true') {
    $rowNum = $_GET['rowNum'];
    get_more_chem($conn, $rowNum);
} else if (isset($_GET['addMoreTech']) && $_GET['addMoreTech'] == 'true') {
    $rowNum = $_GET['techRowNum'];
    $active = isset($_GET['active']) ? $_GET['active'] : null;
    add_more_tech($conn, $rowNum, $active);

}
// else {
//     // echo 'ajax get error';
//     // return;
// }

function get_tech($conn, $active = null)
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
        <option value="<?= $id ?>" <?= $id == $active ? 'selected' : '' ?>><?= $name . ' | Technician ' . $empId ?></option>
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
function get_chem($conn, $active = null)
{
    $active = $active == null ? '' : $active;
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
        <option value="<?= $id ?>" <?= $level <= 0 ? 'disabled' : '' ?><?= $id == $active ? 'selected' : '' ?>>
            <?= $name . " | " . $brand . " | " . $level . "ml" ?>
        </option>
        <?php
    }
}
function get_more_chem($conn, $rowNum)
{
    $sql = 'SELECT * FROM chemicals';
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo 'Error fetching chem data' . mysqli_error($conn);
        return;
    }

    ?>
    <div class="row mb-2" id="row-<?= $rowNum ?>">
        <div class="col-lg-6 mb-2">
            <label for="add-chemBrandUsed" class="form-label fw-light">Chemical
                Used #<?= $rowNum ?></label>
            <select id="add-chemBrandUsed" name="add_chemBrandUsed[]" class="form-select">
                <?= get_chem($conn); ?>
                <!-- chem ajax -->
            </select>

        </div>
        <div class="col-lg-4 mb-2 ps-0">
            <label for="add-amountUsed" class="form-label fw-light">Amount
                Used</label>
            <div class="d-flex flex-row">
                <input type="number" name="add_amountUsed[]" maxlength="4" id="amountUsed-<?= $rowNum ?>"
                    class="form-control form-add me-3 " autocomplete="one-time-code">
                <span id="passwordHelpInline" class="form-text align-self-center">
                    /ml
                </span>
            </div>
        </div>
    </div>
    <?php
}

function add_more_tech($conn, $num, $active)
{

    ?>

    <div class="dropdown-center col-lg-6 mb-2" id="row-<?= $num ?>">
        <label for="add-technicianName" class="form-label fw-light">Technician #<?= $num ?>
        </label>
        <select id="add-technicianName" name="add-technicianName[]" class="form-select" aria-label="Default select example">
            <?= get_tech($conn, $active); ?>
        </select>
    </div>
    <?php
}

if (isset($_GET['details']) && $_GET['details'] === 'true') {
    $id = $_GET['transId'];
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['type' => 'id', 'message' => 'ID not numeric.']);
        exit();
    }
    $response = [];

    $sql = "SELECT * FROM transactions WHERE id = ?;";
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
        echo json_encode(['success' => $row]);
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['type' => 'array', 'message' => 'array error']);
        mysqli_stmt_close($stmt);
        exit();
    }
    exit();
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

if (isset($_GET['view']) && $_GET['view'] == 'treatment') {
    $transId = $_GET['transId'];
    $sql = "SELECT treatment FROM transactions WHERE id = ?;";
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
            $treatment = $row['treatment'];

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


if (isset($_GET['edit']) && $_GET['edit'] == 'technicianName') {
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
            $id = $row['tech_id'];
            $technician = $row['tech_info'];

            ?>
            <div class="mb-2 d-inline-flex w-50" id="techRow-<?= $id ?>">
                <select id="edit-technicianName" name="edit-technicianName[]" class="form-select me-3"
                    aria-label="Default select example">
                    <?php
                    get_tech($conn, $id);
                    ?>
                </select>
                <button type="button" id="edit-deleteTech" data-row-id="<?= $id ?>" class="btn btn-grad mt-auto py-2 px-3"><i
                        class="bi bi-dash-circle text-light"></i></button>
            </div>
            <?php
        }
        mysqli_stmt_close($stmt);
        exit();
    } else {
        ?>
        <div class="mb-2 d-inline-flex w-50" id="techRow-">
            <select id="edit-technicianName" name="edit-technicianName[]" class="form-select me-3"
                aria-label="Default select example">
                <?php
                get_tech($conn);
                ?>
            </select>
            <button type="button" id="edit-deleteTech" class="btn btn-grad mt-auto py-2 px-3"><i
                    class="bi bi-dash-circle text-light"></i></button>
        </div>
        <?php
        mysqli_stmt_close($stmt);
        exit();
    }
}

if (isset($_GET['edit']) && $_GET['edit'] == 'probCheckbox') {
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
        $problems = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $problems[] = $row['problems'];
        }
        get_prob($conn, $problems);
        mysqli_stmt_close($stmt);
        exit();
    } else {
        get_prob($conn);
        mysqli_stmt_close($stmt);
        exit();
    }
}

if (isset($_GET['getChem']) && $_GET['getChem'] == 'edit') {
    $transId = $_GET['transId'];

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

            ?>
            <div class="row" id="row-<?= $id ?>">
                <div class="col-lg-4 mb-2">
                    <label for="edit-chemBrandUsed-<?= $id ?>" class="form-label fw-light" id="edit-chemBrandUsed-label">Chemical
                        Used:</label>
                    <select id="edit-chemBrandUsed-<?= $id ?>" name="edit_chemBrandUsed[]" class="form-select">
                        <?php get_chem($conn, $id); ?>
                    </select>
                </div>

                <div class="col-lg-4 mb-2 ps-0 d-flex justify-content-evenly" id='container-amountUsed'>
                    <div class="d-flex flex-column">
                        <label for="edit-amountUsed-<?= $id ?>" class="form-label fw-light"
                            id="edit-amountUsed-label">Amount:</label>
                        <input type="number" name="edit-amountUsed[]" maxlength="4" id="edit-amountUsed-<?= $id ?>"
                            class="form-control form-add me-3" autocomplete="one-time-code" value="<?= $amtUsed ?>">
                    </div>
                    <span id="passwordHelpInline" class="form-text mt-auto mb-2">
                        /ml
                    </span>
                    <button type="button" id="edit-deleteChemRow" data-row-id="<?= $id ?>" class="btn btn-grad mt-auto py-2 px-3"><i
                            class="bi bi-dash-circle text-light"></i></button>
                </div>

            </div>
            <?php
        }
        mysqli_stmt_close($stmt);
        exit();
    } else {
        ?>
        <div class="row" id="row-#">
            <div class="col-lg-4 mb-2">
                <label for="edit-chemBrandUsed-" class="form-label fw-light" id="edit-chemBrandUsed-label">Chemical
                    Used:</label>
                <select id="edit-chemBrandUsed-" name="edit_chemBrandUsed[]" class="form-select">
                    <?php get_chem($conn); ?>
                </select>
            </div>

            <div class="col-lg-4 mb-2 ps-0 d-flex justify-content-evenly" id='container-amountUsed'>
                <div class="d-flex flex-column">
                    <label for="edit-amountUsed-" class="form-label fw-light" id="edit-amountUsed-label">Amount:</label>
                    <input type="number" name="edit-amountUsed[]" maxlength="4" id="edit-amountUsed-"
                        class="form-control form-add me-3" autocomplete="one-time-code">
                </div>
                <span id="passwordHelpInline" class="form-text mt-auto mb-2">
                    /ml
                </span>
                <button type="button" id="edit-deleteChemRow" data-row-id="" class="btn btn-grad mt-auto py-2 px-3"><i
                        class="bi bi-dash-circle text-light"></i></button>
            </div>
        </div>
        <?php
        mysqli_stmt_close($stmt);
        exit();
    }
}

if (isset($_GET['addrow']) && $_GET['addrow'] == 'true') {
    ?>
    <div class="row" id="row-#">
        <div class="col-lg-4 mb-2">
            <label for="edit-chemBrandUsed-" class="form-label fw-light" id="edit-chemBrandUsed-label">Chemical
                Used:</label>
            <select id="edit-chemBrandUsed-" name="edit_chemBrandUsed[]" class="form-select">
                <?php get_chem($conn); ?>
            </select>
        </div>

        <div class="col-lg-4 mb-2 ps-0 d-flex justify-content-evenly" id='container-amountUsed'>
            <div class="d-flex flex-column">
                <label for="edit-amountUsed-" class="form-label fw-light" id="edit-amountUsed-label">Amount:</label>
                <input type="number" name="edit-amountUsed[]" maxlength="4" id="edit-amountUsed-"
                    class="form-control form-add me-3" autocomplete="one-time-code">
            </div>
            <span id="passwordHelpInline" class="form-text mt-auto mb-2">
                /ml
            </span>
            <button type="button" id="edit-deleteChemRow" data-row-id="" class="btn btn-grad mt-auto py-2 px-3"><i
                    class="bi bi-dash-circle text-light"></i></button>
        </div>
    </div>
    <?php
    exit();
}

?>