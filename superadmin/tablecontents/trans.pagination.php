<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 5;
$rowCount = 'SELECT * FROM transactions';
$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);

function treatment_name($conn, $id){
    if(!is_numeric($id)){
        return "Invalid ID. ID passed: " . $id;
    }

    $sql = "SELECT t_name FROM treatments WHERE id = $id;";
    $res = mysqli_query($conn, $sql);

    if(mysqli_num_rows($res) > 0){
        if($row = mysqli_fetch_assoc($res)){
            return $row['t_name'];
        }
    }else{
        return "No treatment found.";
    }
}

function row_status($conn, $status = '')
{
    if ($status != '') {
        $rowCount = "SELECT * FROM transactions WHERE transaction_status = '$status'";
    } else {
        $rowCount = 'SELECT * FROM transactions';
    }
    $countResult = mysqli_query($conn, $rowCount);
    $totalRows = mysqli_num_rows($countResult);
    $totalPages = ceil($totalRows / $GLOBALS['pageRows']);

    return ['pages' => $totalPages, 'rows' => $totalRows];
}


if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $status = $_GET['status'];

    if ($status != '') {
        $sql = "SELECT * FROM transactions 
                WHERE (id LIKE ? OR treatment_date LIKE ? OR customer_name LIKE ?
                    OR treatment LIKE ?) 
                AND transaction_status = ? 
                ORDER BY id DESC;";
    } else {
        $sql = "SELECT * FROM transactions 
                WHERE id LIKE ? OR treatment_date LIKE ? OR customer_name LIKE ?
                    OR treatment LIKE ? OR transaction_status LIKE ? 
                ORDER BY id DESC;";
    }
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'ERROR STMT';
        exit();
    }

    $ssearch = "%" . $search . "%";
    // $sstatus = "%" . $status . "%";

    if ($status != '') {
        mysqli_stmt_bind_param($stmt, 'sssss', $ssearch, $ssearch, $ssearch, $ssearch, $status);
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
            $t_name = treatment_name($conn, intval($treatment));
            $createdAt = $row['created_at'];
            $updatedAt = $row['updated_at'];
            $status = $row['transaction_status'];
            ?>
            <tr class="text-center">
                <td scope="row"><?= $id ?></td>
                <td><?= htmlspecialchars($customerName) ?></td>
                <td><?= htmlspecialchars($treatmentDate) ?></td>
                <td><?= htmlspecialchars($t_name) ?></td>
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
        echo "<tr><td scope='row' colspan='6' class='text-center'>Search not found.</td></tr>";
    }
}

if (isset($_GET['paginate']) && $_GET['paginate'] == 'true') {
    $status = $_GET['status'];
    load_pagination($conn, $status);
}


function load_pagination($conn, $status)
{

    // list($countResult, $totalRows, $totalPages) = row_status($conn, $pageRows, $status);
    if ($status != '') {
        $rowstatus = row_status($conn, $status);
        $totalRows = $rowstatus['rows'];
        $totalPages = $rowstatus['pages'];
    } else {
        $totalPages = $GLOBALS['totalPages'];
    }

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



if (isset($_GET['table']) && $_GET['table'] == 'true') {
    $current = isset($_GET['currentpage']) && is_numeric($_GET['currentpage']) ? $_GET['currentpage'] : 1;
    $status = $_GET['status'] == '' ? '' : $_GET['status'];

    $limitstart = ($current - 1) * $pageRows;

    $sql = "SELECT * FROM transactions ";

    if ($status != '') {
        $sql .= "WHERE transaction_status = '$status' ";
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
            $t_name = treatment_name($conn, $treatment);
            $createdAt = $row['created_at'];
            $updatedAt = $row['updated_at'];
            $status = $row['transaction_status'];
            ?>
            <tr class="text-center">
                <td scope="row"><?= $id ?></td>
                <td><?= htmlspecialchars($customerName) ?></td>
                <td><?= htmlspecialchars($treatmentDate) ?></td>
                <td><?= htmlspecialchars($t_name) ?></td>
                <td>
                    <?=
                        $status === 'Pending' ? "<button type='button' id='pendingbtn' data-pending-id='$id' dasta-bs-toggle='modal' data-bs-target='#approvemodal'
                             class='btn btn-sidebar me-2'>Pending</button>" : htmlspecialchars($status)
                        ?>
                </td>
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
        echo "<tr><td scope='row' colspan='5' class='text-center'>No data found.</td></tr>";
    }
}