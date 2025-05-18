<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['queue']) && $_GET['queue'] === 'true') {
    $sql = "SELECT * FROM transactions ORDER BY treatment_date DESC;";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customername = $row['customer_name'];
            $date = $row['treatment_date'];
            $treatment = $row['treatment'];
            ?>
            <div class="col">
                <div class="card bg-white bg-opacity-25 rounded-3 border-0 text-light">
                    <div class="card-body px-2 border-light ">
                        <h5 class="card-title">Transaction <?= htmlspecialchars($id) ?></h5>
                        <hr>
                        <p class="card-text lh-lg">
                            <strong>Customer:</strong> <?= htmlspecialchars($customername) ?><br>
                            <strong>Address:</strong> N/A <br>
                            <strong>Treatment Date:</strong> <span
                                class="ms-1 text-danger-emphasis"><?= htmlspecialchars($date) ?></span><br>
                            <strong>Treatment:</strong> <?= htmlspecialchars($treatment) ?><br>
                        </p>
                        <button type="button" id="dispatchedtechbtn"
                            class="btn btn-sidebar border-light rounded-pill text-light bg-transparent"
                            data-tech="<?= htmlspecialchars($id) ?>">Dispatched Technicians</button>

                    </div>

                </div>
            </div>
            <?php
        }
    }
}

if (isset($_GET['dispatched']) && $_GET['dispatched'] === 'true') {
    $id = htmlspecialchars($_GET['transid']);
    $sql = "SELECT * FROM transaction_technicians WHERE trans_id = ? ;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return 'Fetch dispatched technicians stmt error.';
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <li class="list-group-item"><?= htmlspecialchars($row['tech_info']) ?></li>
            <?php
        }
    } else{
        echo "<li class = 'list-group-item'>No assigned technicians for this transaction.</li>";
    }
}