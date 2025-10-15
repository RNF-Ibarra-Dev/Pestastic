<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 8;
$rowCount = 'SELECT * FROM inspection_reports;';
$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $istatus = $_GET['status'] ?? '';
    $ibranch = $_GET['branch'] ?? '';

    $sql = "SELECT * FROM transactions WHERE (id LIKE ? OR treatment_date LIKE ? OR customer_name LIKE ?
                    OR treatment LIKE ?)";

    $data = [];
    $types = '';
    $queries = [];

    if ($istatus !== '' && $istatus !== NULL) {
        $data[] = (string) $istatus;
        $types .= "s";
        $queries[] = "transaction_status = ?";
    }

    if ($ibranch !== '' && $ibranch !== NULL) {
        $data[] = (int) $ibranch;
        $types .= "i";
        $queries[] = "branch = ?";
    }

    if (!empty($data)) {
        $sql .= " AND " . implode(" AND ", $queries);
    }

    $sql .= " EXCEPT SELECT * FROM transactions WHERE void_request = 1 ORDER BY id DESC;";


    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'ERROR STMT';
        exit();
    }

    $ssearch = "%" . $search . "%";
    // $sstatus = "%" . $status . "%";

    if (!empty($data)) {
        mysqli_stmt_bind_param($stmt, "ssss$types", $ssearch, $ssearch, $ssearch, $ssearch, ...$data);
    } else {
        mysqli_stmt_bind_param($stmt, 'ssss', $ssearch, $ssearch, $ssearch, $ssearch, );
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_num_rows($result);
    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customerName = $row['customer_name'];
            $treatmentDate = $row['treatment_date'];
            $td = date("F j, Y", strtotime($treatmentDate));
            $treatment = $row['treatment'];
            $today = date("Y-m-d");
            $t_name = treatment_name($conn, $treatment);
            $createdAt = $row['created_at'];
            $updatedAt = $row['updated_at'];
            $status = $row['transaction_status'];
            $cr = (int) $row['complete_request'];

            ?>
            <tr class="text-center">
                <td scope="row"><?= $id ?></td>
                <td><?= htmlspecialchars($customerName) ?></td>
                <td><?= $status === 'Cancelled' || ($treatmentDate < $today && ($status === 'Accepted' || $status === 'Pending')) ? "<p class='btn btn-sidebar m-0 rounded-pill bg-dark bg-opacity-25 ps-2 text-warning resched-btn' data-cancelled-id='$id'><i class='bi bi-exclamation-lg'></i>Reschedule Transaction</p>" : htmlspecialchars($td) ?>
                </td>
                <td><?= htmlspecialchars($t_name) ?></td>
                <td>
                    <?=
                        $status === 'Pending' ? "<span id='pendingbtn' data-pending-id='$id' class='pending-btn w-50 border border-light border-opacity-50 shadow-sm text-wrap py-2 text-shadow text-light badge btn btn-sidebar rounded-pill text-bg-warning bg-opacity-25'>Pending</span>" :
                        ($status === 'Accepted' ? "<span data-accepted='$id' class='accepted-btn btn btn-sidebar badge rounded-pill text-bg-success bg-opacity-50 w-50 border border-light border-opacity-50 shadow-sm text-wrap py-2 text-shadow'>$status</span>" :
                            ($status === 'Finalizing' ? "<span data-finalize-id='$id' class='badge rounded-pill text-bg-primary bg-opacity-50 w-50 border border-light border-opacity-50 shadow-sm text-wrap py-2 text-shadow btn btn-sidebar finalizing-btn'>$status</span>" :
                                ($status === 'Voided' ? "<span class='badge rounded-pill text-bg-danger bg-opacity-50 w-50 shadow-sm text-wrap py-2 text-shadow'>$status</span>" :
                                    ($status === 'Completed' ? "<span class='badge rounded-pill text-bg-info bg-opacity-25 text-light w-50 shadow-sm text-wrap py-2 text-shadow'>$status</span>" :
                                        ($status === 'Cancelled' ? "<span data-cancelled-id='$id' class='cancel-btn badge rounded-pill btn btn-sidebar text-bg-secondary bg-opacity-50 w-50 border border-light border-opacity-50 shadow-sm text-wrap py-2 text-shadow'>$status</span>" :
                                            ($status === 'Dispatched' ? "<span data-dispatched-id='$id' class='dispatched-btn btn btn-sidebar badge rounded-pill btn btn-sidebar text-bg-warning text-light bg-opacity-50 w-50 border border-light border-opacity-50 shadow-sm text-wrap py-2 text-shadow'>$status</span>" : $status))))))

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
        echo "<tr><td scope='row' colspan='6' class='text-center'>Search not found.</td></tr>";
    }
}

function row_status($conn, $ibranch = '')
{

    $rowCount = "SELECT COUNT(*) FROM inspection_reports";

    $queries = [];
    $data = [];
    $types = '';

    if ($ibranch != '' && $ibranch != NULL) {
        $branch = (int) $ibranch;
        $queries[] = "branch = ?";
        $data[] = $branch;
        $types .= 'i';
    }

    if (!empty($queries)) {
        $rowCount .= " WHERE " . implode(" AND ", $queries);
    }
    $rowCount .= ";";

    $stmt = mysqli_stmt_init($conn);
    $totalRows = 0;

    if (!mysqli_stmt_prepare($stmt, $rowCount)) {
        echo "row status stmt failed.";
        exit();
    }

    if (!empty($queries)) {
        mysqli_stmt_bind_param($stmt, $types, ...$data);
    }

    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_row($res);
    $totalRows = $row[0];


    $totalPages = ceil($totalRows / $GLOBALS['pageRows']);

    return ['pages' => $totalPages, 'rows' => $totalRows];
}

if (isset($_GET['paginate']) && $_GET['paginate'] == 'true') {
    $branch = $_GET['branch'];
    $active = $_GET['active'];
    load_pagination($conn, (int) $active, $branch);
}


function load_pagination($conn, $activepage = 1, $branch = '')
{

    // list($countResult, $totalRows, $totalPages) = row_status($conn, $pageRows, $status);
    if ($branch != '') {
        $rowstatus = row_status($conn, $branch);
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
            // $activepage = isset($_GET['active']) && is_numeric($_GET['active']) ? $_GET['active'] : 1;
        
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
    // $current = $_GET['currentpage'] ? $_GET['currentpage'] : 1;
    $current = isset($_GET['currentpage']) && is_numeric($_GET['currentpage']) ? $_GET['currentpage'] : 1;

    $branch = $_GET['branch'] ?? NULL;

    $limitstart = ($current - 1) * $pageRows;

    $sql = "SELECT * FROM inspection_reports ";
    $data = [];

    if (!empty($branch)) {
        $data[] = $branch;
        $sql .= "WHERE branch = ? ";
    }
    $sql .= "ORDER BY updated_at DESC LIMIT $limitstart, $pageRows;";

    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);

    if (!empty($data)) {
        mysqli_stmt_bind_param($stmt, $types, ...$data);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customerName = $row['customer'];
            $property_type = $row['property_type'];
            $branch_id = $row['branch'];
            $brnch = get_branch_details($conn, $branch_id);
            $branch = "{$brnch['name']} ({$brnch['location']})";
            $created = date("F j, Y", strtotime($row['added_at']));
            $updated = date("F j, Y", strtotime($row['updated_at']));
            ?>
            <tr class="text-center text-capitalize">
                <td scope="row"><?= $id ?></td>
                <td><?= htmlspecialchars($customerName) ?></td>
                <td><?= htmlspecialchars($property_type) ?></td>
                <td><?= htmlspecialchars($branch) ?></td>
                <td><?= htmlspecialchars($created) ?></td>
                <td><?= $created == $updated ? 'Not updated yet.' : $updated ?></td>
                <td>
                    <div class="d-flex justify-content-center">
                        <button data-ir-id="<?= $id ?>" class="btn btn-sidebar me-2 ir-detail-btn text-dark shadow-sm border border-light">Details</button>
                    </div>
                </td>
            </tr>


            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='6' class='text-center'>No data found.</td></tr>";
    }
}
