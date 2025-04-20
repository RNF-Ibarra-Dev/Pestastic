<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 5;
$rowCount = 'SELECT * FROM transactions';
$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);

if (isset($_GET['table']) && $_GET['table'] == 'true') {
    $current = isset($_GET['currentpage']) && is_numeric($_GET['currentpage']) ? $_GET['currentpage'] : 1;
    $tech = $_GET['tech'];
    $limitstart = ($current - 1) * $pageRows;

    $transidsql = "SELECT trans_id FROM transaction_technicians WHERE tech_id = ?;";
    $transidstmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($transidstmt, $transidsql)){
        echo json_encode(['error' => 'fetch transaction id stmt failed.']);
        exit();
    }
    mysqli_stmt_bind_param($transidstmt, 'i', $tech);
    mysqli_stmt_execute($transidstmt);
    $transresult = mysqli_stmt_get_result($transidstmt);
    $transids = [];
    while($row = mysqli_fetch_assoc($transresult)){
        $transids = $row['trans_id'];
    }

    echo "<tr><td scope='row' colspan='5' class='text-center'>" . implode(',', $transids) . "</td></tr>";

    // exit();

    $sql = "SELECT * FROM transactions WHERE id " . " IN(" . implode(',', $transids). ") LIMIT " . $limitstart
        . ", " . $pageRows . ";";

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
        echo "<tr><td scope='row' colspan='5' class='text-center'>Search does not exist.</td></tr>";
    }
}