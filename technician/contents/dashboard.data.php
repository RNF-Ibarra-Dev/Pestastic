<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$activetech = $_SESSION['techId'];

$transidsql = "SELECT trans_id FROM transaction_technicians WHERE tech_id = ?;";
$transidstmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($transidstmt, $transidsql)) {
    echo json_encode(['error' => 'fetch transaction id stmt failed.']);
    exit();
}
mysqli_stmt_bind_param($transidstmt, 'i', $activetech);
mysqli_stmt_execute($transidstmt);
$transresult = mysqli_stmt_get_result($transidstmt);
$transids = [];
while ($row = mysqli_fetch_assoc($transresult)) {
    $transids[] = $row['trans_id'];
}


if (isset($_GET['append']) && $_GET['append'] === 'pending') {
    $sql = "SELECT * FROM transactions WHERE transaction_status = 'Pending' AND id IN (" . implode(',', $transids) . ") ORDER BY id DESC LIMIT 5 ;";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customer = $row['customer_name'];
            $date = $row['treatment_date'];

            ?>
            <tr>
                <td><?= htmlspecialchars($id) ?></td>
                <td><?= htmlspecialchars($customer) ?></td>
                <td><?= htmlspecialchars($date) ?></td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='3' class='text-center'>No pending requests.</td></tr>";
    }
}

if (isset($_GET['append']) && $_GET['append'] === 'completed') {
    $sql = "SELECT * FROM transactions WHERE transaction_status = 'Completed' AND id IN (" . implode(',', $transids) . ") ORDER BY id DESC LIMIT 5 ;";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customer = $row['customer_name'];
            $date = $row['treatment_date'];

            ?>
            <tr>
                <td><?= htmlspecialchars($id) ?></td>
                <td><?= htmlspecialchars($customer) ?></td>
                <td><?= htmlspecialchars($date) ?></td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='3' class='text-center'>No completed transactions.</td></tr>";
    }
}

if (isset($_GET['append']) && $_GET['append'] === 'accepted') {
    $sql = "SELECT * FROM transactions WHERE transaction_status = 'Accepted' AND id IN (" . implode(',', $transids) . ") ORDER BY id DESC LIMIT 5 ;";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customer = $row['customer_name'];
            $date = $row['treatment_date'];

            ?>
            <tr>
                <td><?= htmlspecialchars($id) ?></td>
                <td><?= htmlspecialchars($customer) ?></td>
                <td><?= htmlspecialchars($date) ?></td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='3' class='text-center'>No approved transactions yet.</td></tr>";
    }
}

if (isset($_GET['append']) && $_GET['append'] === 'chemicals') {
    $sql = "SELECT * FROM chemicals WHERE chemLevel > 0 ORDER BY id DESC LIMIT 5;";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['name'];
            $brand = $row['brand'];
            $level = $row['chemLevel'];

            ?>
            <tr>
                <td><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($brand) ?></td>
                <td><?= htmlspecialchars($level) ?></td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='3' class='text-center'>No available chemicals.</td></tr>";
    }
}
