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
    $sql = 'SELECT * FROM chemicals ORDER BY id DESC;';
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
        $req = $row['request']
    ?>
        <option value="<?= htmlspecialchars($id) ?>" <?= $level <= 0 || $req == 1 ? 'disabled' : '' ?><?= $id == $active ? 'selected' : '' ?>>
            <?= htmlspecialchars($name) . " | " . htmlspecialchars($brand) . " | " . htmlspecialchars($level) . "ml" ?>
            <?= $req == 1 ? " | Chemical Under Review" : '' ?>
        </option>
    <?php
    }
}
function get_chem_edit($conn, $active = null)
{
    $active = $active == null ? '' : $active;
    $sql = 'SELECT * FROM chemicals ORDER BY id DESC;';
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
        $req = $row['request']
    ?>
        <option value="<?= htmlspecialchars($id) ?>" <?= $id == $active ? 'selected' : '' ?>>
            <?= htmlspecialchars($name) . " | " . htmlspecialchars($brand) . " | " . htmlspecialchars($level) . "ml" ?>
            <?= $req == 1 ? " | Chemical Under Review" : '' ?>
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
    <div class="row mb-2">
        <div class="col-lg-6 dropdown-center d-flex flex-column pe-0">
            <select id="add-chemBrandUsed" name="add_chemBrandUsed[]" class="form-select">
                <?= get_chem($conn); ?>
                <!-- chem ajax -->
            </select>
        </div>
        <div class="col-2 d-flex">
            <button type="button" id="deleteChem" class="btn btn-grad mt-auto py-2 px-3"><i
                    class="bi bi-dash-circle text-light"></i></button>
        </div>
    </div>
    <?php
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

// get details. From view transaction
if (isset($_GET['details']) && $_GET['details'] === 'true') {
    $id = htmlspecialchars($_GET['transId']);
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['type' => 'id', 'message' => 'Invalid ID.']);
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
                </strong><?= $amtUsed != 0 ? $amtUsed . ' ml' : 'Amount Pending' ?></li>
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
            <div class="mb-2 d-inline-flex" id="techRow-<?= $id ?>">
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
        <div class="mb-2 d-inline-flex" id="techRow-">
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
                        <?php get_chem_edit($conn, $id); ?>
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


function packages($conn)
{
    $sql = "SELECT * FROM packages;";
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

if (isset($_GET['edit']) && $_GET['edit'] === 'treatment-options') {
    $id = $_GET['transId'];

    if (!ctype_digit($id)) {
        http_response_code(400);
        echo "Invalid ID.";
        exit();
    }

    $sql = "SELECT * FROM treatments;";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $idval = $row['id'];
            $tname = $row['t_name'];
    ?>
            <option value="<?= htmlspecialchars($idval) ?>" <?= $id == $idval ? 'selected' : '' ?>><?= htmlspecialchars($tname) ?>
            </option>
        <?php
        }
    } else {
        ?>
        <option value="" disabled>No Treatments</option>
        <?php
    }
}



if (isset($_GET['edit']) && $_GET['edit'] === 'package') {
    $package_id = $_GET['transId'];

    if (!ctype_digit($package_id)) {
        http_response_code(400);
        echo "Invalid ID.";
        exit();
    }

    $sql = "SELECT * FROM packages;";
    $res = mysqli_query($conn, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $name = $row['name'];
            $sessions = $row['session_count'];
            $warranty = $row['year_warranty'];
        ?>
            <option value="<?= $id ?>" <?= $id === $package_id ? 'selected' : '' ?>>
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

    $sql = "SELECT * FROM packages WHERE id = $id;";
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

function get_warranty_duration($conn, $id)
{
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid ID. ID passed: $id"]);
        exit();
    }

    $sql = "SELECT year_warranty FROM packages WHERE id = $id;";
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

    if(isset($duration['error'])){
        http_response_code(400);
        return $duration['error'];
        exit();
    }

    date_add($date, date_interval_create_from_date_string("$duration year"));

    return date_format($date, "Y-m-d");
}
