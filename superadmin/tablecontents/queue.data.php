<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['queue']) && $_GET['queue'] === 'true') {
    $sort = $_GET['sort'];
    $sql = "SELECT * FROM transactions 
    WHERE transaction_status = 'Completed'
    ORDER BY treatment_date";
    if ($sort === 'true') {
        $sql .= " ASC;";
    } else {
        $sql .= " DESC;";
    }
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customername = $row['customer_name'];
            $date = $row['treatment_date'];
            $treatment = $row['treatment'];
?>
            <div class="col">
                <div class="card bg-white bg-opacity-25 shadow-sm rounded-4 border-0 text-light px-3">
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

if (isset($_GET['ondispatch']) && $_GET['ondispatch'] === 'true') {
    $sql = "SELECT * FROM transactions 
    WHERE treatment_date >= CURDATE() AND (transaction_status = 'Pending' OR transaction_status = 'Accepted') 
    ORDER BY treatment_date ASC LIMIT 3;";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customername = $row['customer_name'];
            $date = $row['treatment_date'];
            $treatment = $row['treatment'];
            $status = $row['transaction_status'];
        ?>
            <div class="col">
                <div class="card bg-white bg-opacity-25 rounded-4 border-0 text-light px-3">
                    <div class="card-body px-2 border-light ">
                        <h5 class="card-title align-middle">Transaction
                            <?= htmlspecialchars($id) ?>
                            <?= $status === 'Pending' ? "<i class='bi bi-dot text-warning fs-4'></i>" : "<i class='bi bi-dot text-success fs-4'></i>" ?>
                        </h5>
                        <hr>
                        <p class="card-text lh-lg">
                            <strong>Customer:</strong> <?= htmlspecialchars($customername) ?><br>
                            <strong>Address:</strong> N/A <br>
                            <strong>Treatment Date:</strong> <span
                                class="ms-1 text-danger-emphasis"><?= htmlspecialchars($date) ?></span><br>
                            <strong>Follow up Date:</strong> Not set<br>
                            <strong>Treatment:</strong> <?= htmlspecialchars($treatment) ?><br>
                            <strong>Status:</strong>
                            <?php echo $status == 'Accepted' ? "<span class='text-success'>" . htmlspecialchars($status) . "<i class='bi bi-dot text-success'></i></span>" : "<span class='text-warning'>" . htmlspecialchars($status) . "<i class='bi bi-dot text-warning'></i></span>" ?><br>
                        </p>
                        <button type="button" id="dispatchedtechbtn"
                            class="btn btn-sidebar border-light rounded-pill text-light bg-transparent"
                            data-tech="<?= htmlspecialchars($id) ?>">Dispatched Technicians</button> <br>
                        <?php
                        if ($status === 'Accepted') {
                            echo "<p class='text-body-secondary mb-0 text-light mt-3 lh-1'><small>Transaction ready and accepted. Dispatch?</small></p>";
                        } else {
                            echo "<p class='text-body-secondary mb-0 text-light mt-3 lh-1'><small>Transaction pending. Accept transaction?</small></p>";
                        }
                        ?>
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
    } else {
        echo "<li class = 'list-group-item'>No assigned technicians for this transaction.</li>";
    }
}

if (isset($_GET['getdata']) && $_GET['getdata'] === 'newpending') {
    $sql = "SELECT * FROM transactions WHERE transaction_status = 'pending' ORDER BY id DESC;";
    $result = mysqli_query($conn, $sql);
}

if (isset($_GET['getactive']) && $_GET['getactive'] === 'true') {
    $sql = "SELECT * FROM transactions WHERE treatment_date = CURDATE();";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customername = $row['customer_name'];
            $date = $row['treatment_date'];
            $treatment = $row['treatment'];
            $status = $row['transaction_status'];
        ?>
            <div class="col align-content-center">
                <div class="card bg-white bg-opacity-25 rounded-4 border-0 text-light px-3">
                    <div class="card-body px-2 border-light ">
                        <h5 class="card-title align-middle">Transaction
                            <?= htmlspecialchars($id) ?>
                            <?= $status === 'Pending' ? "<i class='bi bi-dot text-warning fs-4'></i>" : "<i class='bi bi-dot text-success fs-4'></i>" ?>
                        </h5>
                        <hr>
                        <p class="card-text lh-lg">
                            <strong>Customer:</strong> <?= htmlspecialchars($customername) ?><br>
                            <strong>Address:</strong> N/A <br>
                            <strong>Follow up Date:</strong> Not set<br>
                            <strong>Treatment:</strong> <?= htmlspecialchars($treatment) ?><br>
                        </p>
                        <button type="button" id="dispatchedtechbtn"
                            class="btn btn-sidebar border-light rounded-pill text-light bg-transparent"
                            data-tech="<?= htmlspecialchars($id) ?>">Dispatched Technicians</button> <br>

                    </div>
                </div>
            </div>
        <?php
        }
    } else {
        ?>
        <div class="mx-auto my-auto">
            <div class="card bg-white bg-opacity-25 rounded-4 border-0 text-light px-3">
                <div class="card-body px-2 border-light">
                    <h5 class="fw-light text-center m-0">No Schedule for Today. Schedule Transaction?</h5>
                </div>
            </div>
        </div>
<?php
    }
}

if (isset($_GET['dates']) && $_GET['dates'] === 'true') {
    $sql = "SELECT treatment_date FROM transactions;";
    $result = mysqli_query($conn, $sql);

    $dates = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $dates[] = $row['treatment_date'];
        }
    } 
    echo json_encode($dates);
    exit();
}

if(isset($_GET['transactions']) && $_GET['transactions'] === 'true'){
    $sql = "SELECT treatment_date, id FROM transactions WHERE transaction_status != 'Voided';";
    $result = mysqli_query($conn, $sql);

    $dates = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $dates[] = [
                'title'=> $row['id'],
                'start'=> $row['treatment_date'],
                'end' => $row['treatment_date']
            ];
        }
    } 
    echo json_encode($dates);
    exit();
}
