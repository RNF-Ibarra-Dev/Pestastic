<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['queue']) && $_GET['queue'] === 'true') {
    $sort = $_GET['sort'];
    $sql = "SELECT * FROM transactions 
    WHERE transaction_status = 'Accepted' AND treatment_date >= CURDATE()
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
            $otime = $row['transaction_time'];
            $ntime = strtotime($otime);
            $time = date("h:i A", $ntime);
?>
            <div class="col">
                <div class="card bg-white bg-opacity-25 shadow-sm rounded border-0 text-light p-0">
                    <div class="card-body px-2 border-light pb-0 mx-3">
                        <h5 class="card-title">Transaction <?= htmlspecialchars($id) ?></h5>
                        <hr>
                        <p class="card-text lh-lg mb-3">
                            <strong>Customer:</strong> <?= htmlspecialchars($customername) ?><br>
                            <strong>Address:</strong> N/A <br>
                            <strong>Treatment Date:</strong> <span
                                class="ms-1 text-danger-emphasis"><?= htmlspecialchars($date) ?></span><br>
                            <strong>Time:</strong> <span
                                class='ms-1 text-danger-emphasis'><?= $otime === "00:00:00" ? "Time not set." : htmlspecialchars($time) ?></span><br>
                            <strong>Treatment:</strong> <?= htmlspecialchars($treatment) ?><br>
                        </p>
                    </div>
                    <div class="card-footer border-top-0 p-0">
                        <ul class="list-group list-group-flush d-flex justify-content-center bg-light bg-opacity-25 rounded-bottom"
                            style="--bs-list-group-bg: none !important;">
                            <li class="list-group-item d-flex p-0 m-0">
                                <button type="button" id="dispatchedtechbtn"
                                    class="btn btn-sidebar mx-auto w-100 rounded-0 text-light bg-opacity-0"
                                    data-tech="<?= htmlspecialchars($id) ?>">Assigned Technicians</button>
                            </li>
                            <li class="list-group-item d-flex p-0 m-0">
                                <button type="button" id="resched"
                                    class="btn btn-sidebar mx-auto w-100 rounded-0 text-light bg-opacity-0"
                                    data-resched="<?= htmlspecialchars($id) ?>" data-odate="<?= htmlspecialchars($date) ?>"
                                    data-otime="<?= htmlspecialchars($otime) ?>"><?= $otime === "00:00:00" ? 'Add Time' : 'Reschedule Transaction' ?></button>
                            </li>
                            <li class=" list-group-item d-flex p-0 m-0">
                                <button type="button" id="cancel"
                                    class="btn btn-sidebar mx-auto w-100 text-light rounded-top-0 bg-opacity-0"
                                    data-cancel="<?= htmlspecialchars($id) ?>">Cancel Transaction</button>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        <?php
        }
    } else {
        echo "<h4 class='fw-light mx-auto'>No upcoming transactions.</h4>";
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
            <div class="card bg-white bg-opacity-25 rounded-4 my-auto border-0 text-light px-3">
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
                        data-tech="<?= htmlspecialchars($id) ?>">Assigned Technicians</button> <br>

                </div>
            </div>
        <?php
        }
    } else {
        ?>
        <div class="card bg-white bg-opacity-25 rounded-4 border-0 text-light px-3 mx-auto my-auto">
            <div class="card-body px-2 border-light">
                <h5 class="fw-light text-center m-0">No Schedule for Today. Schedule Transaction?</h5>
            </div>
        </div>
        <?php
    }
}


if (isset($_GET['transactions']) && $_GET['transactions'] === 'true') {
    $titleonly = isset($_GET['data']) ? $_GET['data'] : NULL;
    $sql = "SELECT treatment_date, id, transaction_status FROM transactions WHERE transaction_status != 'Voided';";
    $result = mysqli_query($conn, $sql);

    $dates = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $date = $row['treatment_date'];
            $status = $row['transaction_status'];

            if ($titleonly != NULL) {
                $title = "T-" . $id . "<br>" . $status;
            } else {
                $title = "T-" . $id;
            }

            $dates[] = [
                'title' => $title,
                'start' => $date,
                'end' => $date,
                'url' => "transactions.php?openmodal=true&id=" . $id
            ];
        }
    }
    echo json_encode($dates);
    exit();
}

if (isset($_GET['getdata']) && $_GET['getdata'] === 'ongoing') {
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
            <div class="card bg-white bg-opacity-25 rounded border-0 text-light px-3">
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
        <?php
        }
    } else {
        ?>
        <div class="card bg-white bg-opacity-25 rounded border-0 text-light px-3 my-auto">
            <h5 class="fw-light text-center m-0 p-4">No Schedule for Today. Schedule Immediate Transaction?</h5>
        </div>
        <?php
    }
}


if (isset($_GET['techStats']) && $_GET['techStats'] === 'true') {
    $sort = $_GET["sort"];

    $sql = "SELECT * FROM technician";
    if ($sort !== 'none') {
        $sql .= " WHERE technician_status = '$sort';";
    }
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tech = $row['firstName'] . ' ' . $row['lastName'];
            $status = $row['technician_status'];
            $techid = $row['techEmpId'];
        ?>
            <li class="list-group-item bg-transparent text-light d-flex justify-content-between">
                <?= htmlspecialchars($tech) . ' - [' . htmlspecialchars($techid) . ']' ?> <span
                    class="<?= $status === 'Available' ? 'text-custom-success' : ($status === 'Unavailable' ? 'text-danger' : ($status === 'On Leave' ? 'text-info' : 'text-warning')) ?>"><?= htmlspecialchars($status) ?><i
                        class="ms-2 bi <?= $status === 'Available' ? 'bi-person-check-fill text-custom-success' : ($status === 'Unavailable' ? 'bi-person-x-fill text-custom-danger' : ($status === 'On Leave' ? 'bi-person-dash-fill text-info' : 'bi-person-fill-gear text-warning')) ?>"></i></span>
            </li>
        <?php
        }
    } else {
        echo "<li class='list-group-item bg-transparent text-light'>No technicians.</li>";
    }
}

if (isset($_GET['inc']) && $_GET['inc'] === 'true') {
    $sql = "SELECT trs.*
    FROM transactions AS trs
    WHERE NOT EXISTS
    (SELECT trans_id FROM transaction_technicians AS tt WHERE tt.trans_id = trs.id)
    OR NOT EXISTS
    (SELECT trans_id FROM transaction_problems AS tp WHERE tp.trans_id = trs.id)
    OR NOT EXISTS
    (SELECT trans_id FROM transaction_chemicals AS tc WHERE tc.trans_id = trs.id);";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customername = $row['customer_name'];
            $date = $row['treatment_date'];
            $treatment = $row['treatment'];
            $otime = $row['transaction_time'];
            $ntime = strtotime($otime);
            $time = date("h:i A", $ntime);
        ?>
            <div class="col">
                <div class="card bg-white bg-opacity-25 shadow-sm rounded border-0 text-light p-0">
                    <div class="card-body px-2 border-light pb-0 mx-3">
                        <h5 class="card-title">Transaction <?= htmlspecialchars($id) ?></h5>
                        <hr>
                        <p class="card-text lh-lg mb-3">
                            <strong>Treatment Date:</strong> <span
                                class="ms-1 text-danger-emphasis"><?= htmlspecialchars($date) ?></span><br>
                            <strong>Time:</strong> <span
                                class='ms-1 text-danger-emphasis'><?= $otime === "00:00:00" ? "Time not set." : htmlspecialchars($time) ?></span><br>
                            <strong>Treatment:</strong> <?= htmlspecialchars($treatment) ?><br>
                        </p>
                    </div>
                    <div class="card-footer border-top-0 p-0">
                        <ul class="list-group list-group-flush d-flex justify-content-center bg-light bg-opacity-25 rounded-bottom"
                            style="--bs-list-group-bg: none !important;">
                            <li class="list-group-item d-flex p-0 m-0">
                                <button type="button" id="reviewBtn"
                                    class="btn btn-sidebar mx-auto w-100 rounded-0 text-light bg-opacity-0"><a class="link-underline-opacity-0 link-underline link-light"
                                        href="transactions.php?openmodal=true&id=<?= htmlspecialchars($id) ?>">Review
                                        Transaction</a></button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
<?php
        }
    } else {
        echo "<div class='bg-light bg-opacity-25 rounded shadow-sm'><h5 class='fw-light m-0 px-4 py-2'>No Unassigned Transactions.</h5></div>";
    }
    exit();
}
