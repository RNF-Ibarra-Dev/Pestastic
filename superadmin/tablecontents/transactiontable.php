<?php
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");

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
                        <button id="details-<?=$id?>" class="btn btn-sidebar me-2">Details</button>
                    </div>
                </td>
            </tr>


            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='6' class='text-center'>Data Does Not Exist.</td></tr>";
    }
}

if (isset($_GET['getChem']) && $_GET['getChem'] == 'true') {
    get_chem($conn);
} else if (isset($_GET['getTech']) && $_GET['getTech'] == 'true') {
    get_tech($conn);
} else if (isset($_GET['getProb']) && $_GET['getProb'] == 'true') {
    get_prob($conn);
} else if (isset($_GET['getMoreChem']) && $_GET['getMoreChem'] == 'true') {
    $rowNum = $_GET['rowNum'];
    get_more_chem($conn, $rowNum);
} else if (isset($_GET['addMoreTech']) && $_GET['addMoreTech'] == 'true') {
    $rowNum = $_GET['techRowNum'];
    add_more_tech($conn, $rowNum);
    // echo 'tite';
} else {
    echo 'ajax get error';
    return;
}



function get_tech($conn)
{
    $sql = 'SELECT * FROM technician';
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo 'Error fetching tech data' . mysqli_error($conn);
        return;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['technicianId'];
        $name = $row['firstName'] . ' ' . $row['lastName'];
        $empId = $row['techEmpId'];
        ?>
        <option value="<?= $id ?>"><?= $name . ' | Technician ' . $empId ?></option>
        <?php
    }
}

function get_prob($conn)
{
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
            autocomplete="off">
        <label class="btn" for="add-<?= $id ?>"><?= $problem ?></label>
        <?php
    }
}
function get_chem($conn)
{

    $sql = 'SELECT * FROM chemicals';
    $result = mysqli_query($conn, $sql);
    // $rows = mysqli_num_rows($result);

    if (!$result) {
        echo 'Error fetching chem data' . mysqli_error($conn);
        return;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $brand = $row['brand'];
        $name = $row['name'];
        $level = $row['chemLevel'];
        ?>
        <option value="<?= $id ?>" <?= $level <= 0 ? 'disabled' : '' ?>><?= $name . " | " . $brand . " | " . $level . "ml" ?>
        </option>
        <?php
    }
}
function get_more_chem($conn, $rowNum)
{
    // $rowNum = $_GET['rowNum'];

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
            <select id="add-chemBrandUsed" name="add-chemBrandUsed[]" class="form-select">
                <option value="" selected>Select Chemical</option>
                <?= get_chem($conn); ?>
                <!-- chem ajax -->
            </select>

        </div>
        <div class="col-lg-4 mb-2 ps-0">
            <label for="add-amountUsed" class="form-label fw-light">Amount
                Used</label>
            <div class="d-flex flex-row">
                <input type="number" name="amountUsed[]" maxlength="4" id="amountUsed-<?= $rowNum ?>"
                    class="form-control form-add me-3 " autocomplete="one-time-code">
                <span id="passwordHelpInline" class="form-text align-self-center">
                    /ml
                </span>
            </div>
        </div>
    </div>
    <?php
}

function add_more_tech($conn, $num)
{

    ?>

    <div class="dropdown-center col-lg-6 mb-2" id="row-<?=$num?>">
        <label for="add-technicianName" class="form-label fw-light">Technician #<?=$num?>
        </label>
        <select id="add-technicianName" name="add-technicianName[]" class="form-select" aria-label="Default select example">
            <option value="#" selected>Select Technician</option>
            <hr class="dropdown-divider">
            <?= get_tech($conn); ?>
        </select>
    </div>
<?php
}


?>