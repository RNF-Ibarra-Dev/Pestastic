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

if (isset($_GET['append']) && ($_GET['append'] === 'trt_addbranch' || $_GET['append'] === 'trt_editbranch')) {
    $sql = "SELECT * FROM branches;";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        ?>
        <option>Select Branch</option>
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
        <option disabled>No Branches Added</option>
        <?php
    }
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
                    <button type="button" id="delete_prob" class="col btn w-100 py-2 rounded-0 btn-sidebar">Delete
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

    
}