<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");

if (isset($_GET['append']) && $_GET['append'] === 'treatment') {
    $sql = "SELECT * FROM treatments;";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $name = $row['t_name'];
            $brnchid = $row['branch'];
            $brnch = get_branch_details($conn, $brnchid);
?>
            <tr class="text-center">
                <td>
                    <input type="checkbox" name="trt_chk[]" value="<?= htmlspecialchars($id) ?>" class="form-check-input">
                </td>
                <td><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($brnch['name'] . ' (' . $brnch['location'] . ')') ?></td>
                <td class="p-0"><button type="button" class="btn m-0 w-100 py-2 h-100 rounded-0 btn-sidebar trt-edit"
                        data-trt="<?= htmlspecialchars($id) ?>">Edit</button></td>
            </tr>
        <?php

        }
        ?>
        <tr>
            <td colspan="4" class="p-0">
                <div class="row p-0 m-0">
                    <button type="button" class="col btn w-100 py-2 rounded-0 btn-sidebar" id="add_trt"
                        data-bs-target="#trtmnt_mdl">Add More</button>
                    <button type="button" id="delete_selected" class="col btn w-100 py-2 rounded-0 btn-sidebar">Delete
                        Selected</button>
                </div>
            </td>
        </tr>
    <?php

    } else {
    ?>
        <td colspan="3" class="text-center">No Treatments Detected.</td>
    <?php
    }
}

function branch_options($conn)
{
    $sql = "SELECT * FROM branches;";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
    ?>
        <option value="">Select Branch</option>
        <?php
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $name = $row['name'];
            $location = $row['location'];
        ?>
            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($name . ' (' . $location . ')') ?></option>
        <?php
        }
    } else {
        ?>
        <option disabled>No Branches Available.</option>
        <?php
    }
}

if (isset($_GET['append']) && ($_GET['append'] === 'trt_addbranch' || $_GET['append'] === 'trt_editbranch' || $_GET['append'] === 'add_package_branch' || $_GET['append'] === 'edit_package_branch')) {
    branch_options($conn);
}


if (isset($_GET['trt_details'])) {
    $id = $_GET['trt_details'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo "Invalid ID.";
        exit();
    }

    $deets = get_treatment_details($conn, $id);
    if (isset($deets['error'])) {
        http_response_code(400);
        echo "Error: " . $deetes['error'];
        exit();
    } else {
        http_response_code(200);
        echo json_encode($deets);
        exit();
    }
}


if (isset($_GET['append']) && $_GET['append'] === 'problems') {
    $sql = "SELECT * FROM pest_problems ORDER BY id ASC;";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $prob = $row['problems'];
        ?>
            <tr class="text-center">
                <td>
                    <input type="checkbox" name="prob_chk[]" value="<?= htmlspecialchars($id) ?>" class="form-check-input">
                </td>
                <td><?= htmlspecialchars($prob) ?></td>
                <td class="p-0"><button type="button" class="btn m-0 w-100 py-2 h-100 rounded-0 btn-sidebar prob-edit"
                        data-prob="<?= htmlspecialchars($id) ?>">Edit</button></td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="3" class="p-0">
                <div class="row p-0 m-0">
                    <button type="button" class="col btn w-100 py-2 rounded-0 btn-sidebar" id="add_prob">Add More</button>
                    <button type="button" id="delete_prob_btn" class="col btn w-100 py-2 rounded-0 btn-sidebar">Delete
                        Selected</button>
                </div>
            </td>
        </tr>
    <?php

    } else {
    ?>
        <td colspan="3" class="text-center">No Pest Problem Detected.</td>
    <?php
    }
}

if (isset($_GET['row']) && $_GET['row'] === 'prob_input_container') {
    $id = uniqid();
    ?>
    <div class="row mb-2">
        <div class="col-8">
            <label for="prob_name-<?= $id ?>" class="form-label fw-light fs-5">Pest Problem:</label>
            <input type="text" id="prob_name-<?= $id ?>" name="prob[]" class="form-control" autocomplete="one-time-code">
        </div>
        <div class="col-auto d-flex">
            <button type="button" class="btn btn-grad mt-auto del-prob-row-btn"><i class="bi bi-dash-circle"></i></button>
        </div>
    </div>
    <?php
}

if (isset($_GET['getprob']) && $_GET['getprob'] === 'true') {
    $id = $_GET['id'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo "Invalid ID.";
        exit();
    }

    $details = get_pest_problem_details($conn, $id);
    if (isset($details['error'])) {
        http_response_code(400);
        echo $details['error'];
        exit();
    } else {
        http_response_code(200);
        echo json_encode($details);
        exit();
    }
}

if (isset($_GET['append']) && $_GET['append'] === 'branches') {
    $sql = "SELECT * FROM branches;";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $name = $row['name'];
            $loc = $row['location'];
    ?>
            <tr class="text-center">
                <td>
                    <input type="checkbox" name="branch[]" value="<?= htmlspecialchars($id) ?>" class="form-check-input">
                </td>
                <td><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($loc) ?></td>
                <td class="p-0"><button type="button" class="btn m-0 w-100 py-2 h-100 rounded-0 btn-sidebar branch-edit"
                        data-branch="<?= htmlspecialchars($id) ?>">Edit</button></td>
            </tr>
        <?php

        }
        ?>
        <tr>
            <td colspan="4" class="p-0">
                <div class="row p-0 m-0">
                    <button type="button" class="col btn w-100 py-2 rounded-0 btn-sidebar" id="branch_addbtn">Add More</button>
                    <button type="button" id="branch_delbtn" class="col btn w-100 py-2 rounded-0 btn-sidebar">Delete
                        Selected</button>
                </div>
            </td>
        </tr>
    <?php
    }
}

if (isset($_GET['row']) && $_GET['row'] === 'branch_add_container') {
    $id = uniqid();
    ?>
    <div class="row mb-2">
        <div class="col-6">
            <label for="branchname_<?= $id ?>" class="form-label fw-light fs-5">Branch Name:</label>
            <input type="text" id="branchname_<?= $id ?>" name="branch[]" class="form-control" autocomplete="one-time-code">
        </div>
        <div class="col-5">
            <label for="branchloc_<?= $id ?>" class="form-label fw-light fs-5">Location:</label>
            <input type="text" id="branchloc_<?= $id ?>" name="location[]" class="form-control"
                autocomplete="one-time-code">
        </div>
        <div class="col-1 d-flex">
            <button type="button" class="btn btn-grad mt-auto edit-prob-row-btn"><i class="bi bi-dash-circle"></i></button>
        </div>
    </div>
    <?php
}

if (isset($_GET['editbranch']) && $_GET['editbranch'] === 'true') {
    $id = $_GET['id'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo "Invalid ID.";
        exit();
    }

    $details = get_branch_details($conn, $id);
    if (is_string($details)) {
        http_response_code(400);
        echo $details;
        exit();
    } else {
        http_response_code(200);
        echo json_encode($details);
        exit();
    }
}

if (isset($_GET['append']) && $_GET['append'] === 'packages') {
    $sql = "SELECT * FROM packages;";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $name = $row['name'];
            $session = $row['session_count'];
            $year = $row['year_warranty'];
            $tid = $row['treatment'];
            $t = get_treatment_details($conn, $tid);
            $treatment = $t['t_name'];
            $bid = $row['branch'];
            $b = get_branch_details($conn, $bid);
            $branch = $b['name'];
    ?>
            <tr class="text-center">
                <td>
                    <input type="checkbox" name="package[]" value="<?= htmlspecialchars($id) ?>" class="form-check-input">
                </td>
                <td><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($session . ' ' . ($session == 1 ? "Session" : "Sessions")) ?></td>
                <td><?= htmlspecialchars($year . ' ' . ($year == 1 ? "Year" : "years")) ?></td>
                <td><?= htmlspecialchars($treatment) ?></td>
                <td><?= htmlspecialchars($branch) ?></td>
                <td class="p-0"><button type="button" class="btn m-0 w-100 py-2 h-100 rounded-0 btn-sidebar package-edit"
                        data-package="<?= htmlspecialchars($id) ?>">Edit</button></td>
            </tr>
        <?php

        }
        ?>
        <tr>
            <td colspan="7" class="p-0">
                <div class="row p-0 m-0">
                    <button type="button" class="col btn w-100 py-2 rounded-0 btn-sidebar" id="package_addbtn">Add More</button>
                    <button type="button" id="package_delbtn" class="col btn w-100 py-2 rounded-0 btn-sidebar">Delete
                        Selected</button>
                </div>
            </td>
        </tr>
    <?php
    }
}

function package_options($conn)
{
    $sql = "SELECT * FROM treatments;";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
    ?>
        <option value="">Select Treatment</option>
        <?php
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $name = $row['t_name'];
        ?>
            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($name) ?></option>
        <?php
        }
    } else {
        ?>
        <option disabled>No Treatments Available</option>
    <?php
    }
}

if (isset($_GET['append']) && ($_GET['append'] === 'add_package_trt' || $_GET['append'] === 'edit_package_trt')) {
    package_options($conn);
}

if (isset($_GET['addpackagerow']) && $_GET['addpackagerow'] === 'true') {
    $id = uniqid();
    ?>
    <div class="container m-0 p-0">
        <div class="row mb-2">
            <hr class="my-4">
            <div class="col-6">
                <label for="package_name-<?= htmlspecialchars($id) ?>" class="form-label fw-light fs-5">Package
                    Name:</label>
                <input type="text" id="package_name-<?= htmlspecialchars($id) ?>" name="name[]" class="form-control"
                    autocomplete="one-time-code">
            </div>
            <div class="col-3">
                <label for="session-<?= htmlspecialchars($id) ?>" class="form-label fw-light fs-5">Session Count:</label>
                <input type="number" id="session-<?= htmlspecialchars($id) ?>" name="session[]" class="form-control"
                    autocomplete="one-time-code">
            </div>
            <div class="col-3">
                <label for="warranty-<?= htmlspecialchars($id) ?>" class="form-label fw-light fs-5">Warranty Count:</label>
                <input type="number" id="warranty-<?= htmlspecialchars($id) ?>" name="warranty[]" class="form-control"
                    autocomplete="one-time-code">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-6">
                <label for="add_package_branch-<?= htmlspecialchars($id) ?>"
                    class="form-label fw-light fs-5 added-branch-select">Branch:</label>
                <select name="branch[]" id="add_package_branch-<?= htmlspecialchars($id) ?>" class="form-select">
                    <?= branch_options($conn) ?>
                </select>
            </div>
            <div class="col-6">
                <label for="add_package_trt-<?= htmlspecialchars($id) ?>"
                    class="form-label fw-light fs-5 added-package-select">Package
                    Treatment:</label>
                <select name="treatment[]" id="add_package_trt-<?= htmlspecialchars($id) ?>" class="form-select">
                    <?= package_options($conn) ?>
                </select>
            </div>
            <button type="button" class="mt-3 w-25 mx-auto btn btn-grad delete-package-row">Delete Row</button>
        </div>
    </div>
<?php
}

if (isset($_GET['packagedetails']) && $_GET['packagedetails'] === 'true') {
    $id = $_GET['id'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo "Invalid ID.";
        exit();
    }

    $details = get_package_details($conn, $id);

    if (isset($details['error'])) {
        http_response_code(400);
        echo $details['error'];
        exit;
    } elseif (is_array($details)) {
        http_response_code(200);
        echo json_encode($details);
        exit;
    } else {
        http_response_code(400);
        echo $details;
        exit;
    }
}
