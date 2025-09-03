<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['queue']) && $_GET['queue'] === 'true') {
    $sort = $_GET['sort'];
    $sql = "SELECT * FROM transactions 
    WHERE transaction_status = 'Accepted' AND treatment_date >= CURDATE() AND void_request = 0
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
            $td = strtotime($row['treatment_date']);
            $date = date('F j, Y', $td);
            $tt = get_treatment_details($conn, $row['treatment']);
            $treatment = $tt['t_name'] ?? "Outdated treatment. ID not found.";
            $otime = $row['transaction_time'];
            $address = $row['customer_address'];
            $ntime = strtotime($otime);
            $time = date("h:i A", $ntime);
            ?>
            <div class="col <?=mysqli_num_rows($result) == 1 ? 'mx-auto' : ''?>">
                <div class="card bg-white bg-opacity-25 shadow-sm rounded border-0 text-light mx-auto p-0"
                    style="min-height: 26rem !important;">
                    <div class="card-body px-2 border-light pb-0 mx-3">
                        <h5 class="card-title fs-4 text-center fw-bold">Transaction <?= htmlspecialchars($id) ?></h5>
                        <hr>
                        <p class="card-text lh-lg mb-3">
                            <strong class="fw-bold fs-5">Customer:</strong> <?= htmlspecialchars($customername) ?><br>
                            <strong class="fw-bold fs-5">Address:</strong> <?= htmlspecialchars($address) ?> <br>
                            <strong class="fw-bold fs-5">Treatment Date:</strong> <span
                                class="ms-1 "><?= htmlspecialchars($date) ?></span><br>
                            <strong class="fw-bold fs-5">Time:</strong> <span
                                class='ms-1 '><?= $otime === "00:00:00" || $otime === NULL ? "Time not set." : htmlspecialchars($time) ?></span><br>
                            <strong class="fw-bold fs-5">Treatment:</strong> <?= htmlspecialchars($treatment) ?><br>
                        </p>
                    </div>
                    <div class="card-footer border-top-0 p-0">
                        <ul class="list-group list-group-flush d-flex justify-content-center bg-light bg-opacity-25 rounded-bottom"
                            style="--bs-list-group-bg: none !important;">
                            <li class="list-group-item d-flex p-0 m-0">
                                <button type="button" id="dispatchedtechbtn"
                                    class="btn btn-sidebar mx-auto w-100 rounded-0 text-light bg-opacity-0 fw-bold"
                                    data-tech="<?= htmlspecialchars($id) ?>">Assigned Technicians</button>
                            </li>
                            <li class="list-group-item d-flex p-0 m-0">
                                <button type="button" id="resched"
                                    class="btn btn-sidebar mx-auto w-100 rounded-0 text-light bg-opacity-0 fw-bold"
                                    data-resched="<?= htmlspecialchars($id) ?>" data-odate="<?= htmlspecialchars($date) ?>"
                                    data-otime="<?= htmlspecialchars($otime) ?>"><?= $otime === "00:00:00" ? 'Add Time' : 'Reschedule Transaction' ?></button>
                            </li>
                            <li class=" list-group-item d-flex p-0 m-0">
                                <button type="button" id="cancel"
                                    class="btn btn-sidebar mx-auto w-100 text-light rounded-top-0 bg-opacity-0 fw-bold"
                                    data-cancel="<?= htmlspecialchars($id) ?>">Cancel Schedule</button>
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


if (isset($_GET['dispatched_items']) && $_GET['dispatched_items'] === 'true') {
    $id = $_GET['id'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo "Invalid transaction ID is passed.";
        exit();
    }


    $sql = "SELECT * FROM transaction_chemicals WHERE trans_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "Prepared statement failed. Please try again later.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $item = $row['chem_brand'];
            $item_id = $row['chem_id'];
            $item_deets = get_chemical($conn, $item_id);
            $unit = $item_deets['quantity_unit'];
            $value = $row['amt_used'];
            ?>
            <li class="list-group-item fs-5"><?= "$item ($value$unit)" ?></li>
            <?php
        }
    } else {
        echo "<span class='text-center fw-medium my-3'>No item found within this transaction ID.</span>";
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
            <li class="list-group-item fs-5"><?= htmlspecialchars($row['tech_info']) ?></li>
            <?php
        }
    } else {
        echo "<span class = 'text-center fw-medium my-3'>No assigned technicians for this transaction.</span>";
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
    $sql = "SELECT * FROM transactions WHERE transaction_status = 'dispatched' AND void_request = 0;";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $address = $row['customer_address'];
            $customername = $row['customer_name'];
            $date = $row['treatment_date'];
            $t = $row['treatment'];
            $tt = get_treatment_details($conn, $t);
            $treatment = $tt['t_name'];
            $status = $row['transaction_status'];
            ?>
            <div class="col <?=mysqli_num_rows($result) == 1 ? 'mx-auto' : ''?>">
                <div class="card bg-white bg-opacity-25 rounded border-0 text-light" style="min-height: 26rem !important;">
                    <div class="card-body px-4 border-light ">
                        <h5 class="card-title fs-4 text-center fw-bold">Transaction
                            <?= htmlspecialchars($id) ?>
                            <?= $status === 'Pending' ? "<i class='bi bi-dot text-warning fs-4'></i>" : "<i class='bi bi-dot text-success fs-4'></i>" ?>
                        </h5>
                        <hr>
                        <p class="card-text lh-lg">
                            <strong class="fw-bold fs-5 me-1">Customer:</strong> <?= htmlspecialchars($customername) ?><br>
                            <strong class="fw-bold fs-5 me-1">Address:</strong> <?= htmlspecialchars($address) ?> <br>
                            <strong class="fw-bold fs-5 me-1">Treatment:</strong> <?= htmlspecialchars($treatment) ?><br>
                        </p>
                    </div>
                    <div class="card-footer border-top-0 p-0">
                        <ul class="list-group list-group-flush d-flex justify-content-center bg-light bg-opacity-25 rounded-bottom"
                            style="--bs-list-group-bg: none !important;">
                            <li class="list-group-item d-flex p-0 m-0">
                                <button type="button"
                                    class="btn btn-sidebar mx-auto w-100 rounded-0 text-light fw-bold fs-5 bg-opacity-0 items-btn"
                                    data-item="<?= htmlspecialchars($id) ?>">Dispatched Items</button>
                            </li>
                            <li class="list-group-item d-flex p-0 m-0">
                                <button type="button" id="dispatchedtechbtn"
                                    class="btn btn-sidebar mx-auto w-100 rounded-0 text-light fw-bold fs-5 bg-opacity-0"
                                    data-tech="<?= htmlspecialchars($id) ?>">Dispatched Technicians</button>
                            </li>
                            <li class="list-group-item d-flex p-0 m-0">
                                <button type="button" id="reviewBtn"
                                    class="btn btn-sidebar mx-auto w-100 rounded-0 text-light fw-bold fs-5 bg-opacity-0"><a
                                        class="link-underline-opacity-0 link-underline link-light"
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
        ?>
        <div class="card bg-white bg-opacity-25 mx-auto rounded border-0 text-light px-3 my-auto align-self-center">
            <h5 class="fw-light text-center m-0 p-4">No Dispatched Transactions yet.</h5>
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
    $sql = "SELECT * FROM transactions WHERE transaction_status = 'Pending' AND void_request = 0;";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customername = $row['customer_name'] ?? 'Customer name not found.';
            $date = isset($row['treatment_date']) ? date("F j, Y", strtotime($row['treatment_date'])) : "Date not found";
            $t = $row['treatment'];
            $tt = get_treatment_details($conn, $t);
            $treatment = $tt['t_name'] ?? "Outdated treatment. ID not found.";
            $otime = $row['transaction_time'];

            $ntime = strtotime($otime);
            $time = date("h:i A", $ntime);
            $by = $row['submitted_by'] ?? "No record found.";
            $cat = date("F j, Y  | h:i A", strtotime($row['created_at']));
            $treatmentType = $row['treatment_type'];
            ?>
            <div class="col <?=mysqli_num_rows($result) == 1 ? 'mx-auto' : ''?>">
                <div class="card bg-white bg-opacity-25 shadow-sm rounded border-0 text-light p-0">
                    <div class="card-body px-1 border-light pb-0 mx-3">
                        <h5 class="card-title fs-4 fw-bold text-center">Transaction <?= htmlspecialchars($id) ?></h5>
                        <hr>
                        <p class="card-text lh-lg mb-3 text-wrap">
                            <strong class="fw-bold fs-5 me-2">Customer:</strong><?= htmlspecialchars($customername) ?><br>
                            <strong class="fw-bold fs-5 me-2">Scheduled Date:</strong><?= htmlspecialchars($date) ?><br>
                            <strong class="fw-bold fs-5 me-2">Scheduled
                                Time:</strong><?= $otime === "00:00:00" ? "Time not set." : htmlspecialchars($time) ?><br>
                            <strong class="fw-bold fs-5 me-2">Treatment:</strong> <?= htmlspecialchars($treatment) ?><br>
                            <strong class="fw-bold fs-5 me-2">Treatment Type:</strong> <?= htmlspecialchars($treatmentType) ?><br>
                            <strong class="fw-bold fs-5 me-2">Submitted By:</strong> <?= htmlspecialchars($by) ?><br>
                            <strong class="fw-bold fs-5 me-2">Submitted At:</strong> <?= htmlspecialchars($cat) ?><br>
                        </p>
                    </div>
                    <div class="card-footer border-top-0 p-0">
                        <ul class="list-group list-group-flush d-flex justify-content-center bg-light bg-opacity-25 rounded-bottom"
                            style="--bs-list-group-bg: none !important;">
                            <li class="list-group-item d-flex p-0 m-0">
                                <button type="button" id="reviewBtn"
                                    class="btn btn-sidebar mx-auto w-100 rounded-0 text-light fw-bold fs-5 bg-opacity-0"><a
                                        class="link-underline-opacity-0 link-underline link-light"
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
        echo "<div class='bg-light mx-auto bg-opacity-25 rounded shadow-sm'><h5 class='fw-light m-0 px-4 py-2'>No Pending Transactions.</h5></div>";
    }
    exit();
}

