<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');


// get technician's transaction ids


function fetch_transids($conn)
{
    $activetech = $_SESSION['techId'];


    $transidsql = "SELECT trans_id FROM transaction_technicians WHERE tech_id = ?";
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
    return $transids;
}

$transids = fetch_transids($conn);



if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $status = $_GET['status'];

    // $sql = "SELECT * FROM transactions WHERE (id LIKE '%$search%' OR treatment_date LIKE '%$search%' OR customer_name LIKE '%$search%'
    // OR treatment LIKE '%$search%' OR transaction_status LIKE '%$search%') AND id IN(" . implode(',', $transids) . ")";
    $sql = "SELECT * FROM transactions WHERE (id LIKE ? OR treatment_date LIKE ? OR customer_name LIKE ?
    OR treatment LIKE ? OR transaction_status LIKE ?) AND id IN(" . implode(',', $transids) . ")";

    if ($status != '') {
        $sql .= "AND transaction_status = ? ORDER BY id DESC;";
    } else {
        $sql .= "ORDER BY id DESC;";
    }

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'ERROR STMT';
        exit();
    }

    $ssearch = "%" . $search . "%";
    $sstatus = "%" . $status . "%";

    if ($status != '') {
        mysqli_stmt_bind_param($stmt, 'ssssss', $ssearch, $ssearch, $ssearch, $ssearch, $ssearch, $sstatus);
    } else {
        mysqli_stmt_bind_param($stmt, 'sssss', $ssearch, $ssearch, $ssearch, $ssearch, $ssearch);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
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
        echo "<tr><td scope='row' colspan='5' class='text-center'>Search not found.</td></tr>";
    }
}

$pageRows = 5;
if (empty($transids)) {
    $rowCount = 0;
} else {
    $rowcount = count($transids);
    $rowCount = "SELECT * FROM transactions WHERE id IN(" . implode(',', $transids) . ");";
    $countResult = mysqli_query($conn, $rowCount);
    $totalRows = mysqli_num_rows($countResult);
    $totalPages = ceil($totalRows / $pageRows);
}

if (isset($_GET['table']) && $_GET['table'] == 'true') {
    $current = isset($_GET['currentpage']) && is_numeric($_GET['currentpage']) ? $_GET['currentpage'] : 1;
    $limitstart = ($current - 1) * $pageRows;
    $status = $_GET['status'];

    // var_dump($transids);
    // exit();

    if (!$transids) {
        echo "<tr><td scope='row' colspan='5' class='text-center'>You do not have any existing transactions yet.</td></tr>";
        exit();
    }

    $sql = "SELECT * FROM transactions WHERE id " . " IN(" . implode(',', $transids) . ")";

    if ($status != '') {
        $sql .= "AND transaction_status = '$status'";
        $sql .= "ORDER BY id DESC LIMIT " . $limitstart . ", " . $pageRows . ";";
    } else {
        $sql .= "ORDER BY id DESC LIMIT " . $limitstart . ", " . $pageRows . ";";
    }

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
        echo "<tr><td scope='row' colspan='5' class='text-center'>You do not have any existing transactions yet.</td></tr>";
    }
}

if (isset($_GET['paginate']) && $_GET['paginate'] == 'true') {
    ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php
            // set active page ex. 1 = first page. Checks if numeric as well.
            $activepage = isset($_GET['active']) && is_numeric($_GET['active']) ? $_GET['active'] : 1;

            // set next and previous pagination button data
            $prev = $activepage - 1;
            $next = $activepage + 1;

            // pagination number limit
            $pagenolimit = 3;

            // page no where the loop will start. will start at 2 if pagenolimit is 3 so it shows only 2 & 3 at the pagination buttons.
            $defaultstart = 1;

            // set default page starting number
            // if active page is bigger than the limit, default page should start at the lesser two numbers of the active page
            // var_dump($activepage);
            if ($activepage > $pagenolimit) {
                $defaultstart = $activepage - ($pagenolimit - 1);
                // var_dump($defaultstart);
            } else {
                $defaultstart = 1;
                // var_dump($defaultstart);
            }

            $lastpages = $totalPages;
            // var_dump($lastpages);
        
            ?>
            <li class="page-item">
                <a class="page-link" data-page="1" href=""><i class="bi bi-caret-left-fill"></i></a>

            </li>
            <li class="page-item">
                <?php
                if ($prev > 0) {
                    ?>
                    <a class="page-link" data-page="<?= $prev ?>"><i class="bi bi-caret-left"></i></a>
                    <?php
                } else { ?>
                    <a class="page-link" data-page="1"><i class="bi bi-caret-left"></i></a>
                    <?php
                }
                ?>
            </li>
            <?php
            $limitreached = false;
            for ($currentPage = $defaultstart; $currentPage <= $totalPages; $currentPage++) { ?>

                <li class="page-item <?= $activepage == $currentPage ? 'active' : '' ?>">
                    <a class="page-link" data-page="<?php echo $currentPage; ?>"
                        href="<?= $currentPage ?>"><?= $currentPage ?></a>
                </li>

                <?php

                // will show the last button 
                if ($currentPage >= $defaultstart + $pagenolimit - 1 && !$limitreached) {
                    $limitreached = true;

                    if ($currentPage != $lastpages && $currentPage <= $lastpages) {
                        ?>
                        <li class="page-item disabled">
                            <a class="page-link">...</a>
                        </li>

                        <li class="page-item">
                            <a class="page-link" data-page="<?= $totalPages ?>"><?= $totalPages ?></a>
                        </li>
                        <?php
                    }
                    break;
                }
            }
            ?>

            <li class="page-item">
                <?php
                if ($next <= $totalPages) {
                    ?>
                    <a class="page-link" data-page="<?= $next ?>" href=""><i class="bi bi-caret-right"></i></a>
                    <?php
                } else { ?>
                    <a class="page-link" data-page="<?= $totalPages ?>"><i class="bi bi-caret-right"></i></a>
                    <?php
                }
                ?>
            </li>
            <li class="page-item">
                <a class="page-link" data-page="<?= $totalPages ?>" href=""><i class="bi bi-caret-right-fill"></i></a>
            </li>
        </ul>
    </nav>

    <?php
}
