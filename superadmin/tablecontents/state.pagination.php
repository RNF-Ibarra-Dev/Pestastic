<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 5;
$rowCount = "SELECT
                c.id,
                CONCAT(c.name, ' | ', c.brand) as item,
                t.transaction_status,
                CONCAT(c.container_size, ' ', c.quantity_unit) AS container_size,
                CONCAT(SUM(CASE WHEN t.transaction_status IS NULL OR t.transaction_status != 'Completed' THEN 0 ELSE ABS(tc.amt_used) END), ' ', c.quantity_unit) AS total_amt_used,
                CONCAT(c.unop_cont * c.container_size + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END), ' ', c.quantity_unit) AS total_stored,
                CONCAT((SUM(CASE WHEN t.transaction_status IS NULL OR t.transaction_status != 'Completed' THEN 0 ELSE ABS(tc.amt_used) END) + c.unop_cont  * c.container_size + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END)), ' ', c.quantity_unit) AS total_qty
            FROM
                transaction_chemicals tc
            JOIN
                transactions t
            ON
                t.id = tc.trans_id
            RIGHT JOIN
                chemicals c
            ON
                c.id = tc.chem_id
            WHERE
                c.request = 0
            GROUP BY
                c.name, c.brand;";
$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);

function row_status($conn, $ibranch = '')
{

    $rowCount = "SELECT
                    c.id,
                    CONCAT(c.name, ' | ', c.brand) as item,
                    t.transaction_status,
                    CONCAT(c.container_size, ' ', c.quantity_unit) AS container_size,
                    CONCAT(SUM(CASE WHEN t.transaction_status IS NULL OR t.transaction_status != 'Completed' THEN 0 ELSE ABS(tc.amt_used) END), ' ', c.quantity_unit) AS total_amt_used,
                    CONCAT(c.unop_cont * c.container_size + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END), ' ', c.quantity_unit) AS total_stored,
                    CONCAT((SUM(CASE WHEN t.transaction_status IS NULL OR t.transaction_status != 'Completed' THEN 0 ELSE ABS(tc.amt_used) END) + c.unop_cont  * c.container_size + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END)), ' ', c.quantity_unit) AS total_qty
                FROM
                    transaction_chemicals tc
                JOIN
                    transactions t
                ON
                    t.id = tc.trans_id
                RIGHT JOIN
                    chemicals c
                ON
                    c.id = tc.chem_id
                WHERE
                    c.request = 0";
    $queries = [];

    // if ($entries === 'true') {
    //     $queries[] = 'request = 0';
    // }

    if ($ibranch !== '' && $ibranch !== NULL) {
        $queries[] = "c.branch = ?";
        $branch = (int) $ibranch;
    }

    if (!empty($queries)) {
        $rowCount .= " AND " . implode(" AND ", $queries);
    }
    $rowCount .= " GROUP BY
                    c.name, c.brand
                ORDER BY
                    c.name, c.brand;";
    $stmt = mysqli_stmt_init($conn);
    $totalRows = 0;

    if (!mysqli_stmt_prepare($stmt, $rowCount)) {
        http_response_code(400);
        echo "row status stmt failed.";
        exit;
    }

    if ($ibranch !== '') {
        mysqli_stmt_bind_param($stmt, 'i', $branch);
    }

    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_row($res);
    $totalRows = mysqli_num_rows($res);

    $totalPages = ceil($totalRows / $GLOBALS['pageRows']);

    return ['pages' => $totalPages, 'rows' => $totalRows];
}

if (isset($_GET['pagenav']) && $_GET['pagenav'] == 'true') {

    $branch = $_GET['branch'];

    if ($branch !== '' && $branch !== NULL) {
        $rowstatus = row_status($conn, $branch);
        $totalRows = $rowstatus['rows'];
        $totalPages = $rowstatus['pages'];
    } else {
        $GLOBALS['totalPages'];
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
                    <a class="page-link" data-page="<?= $totalPages != 0 ? $next : 1 ?>" href=""><i
                            class="bi bi-caret-right"></i></a>
                    <?php
                } else { ?>
                    <a class="page-link" data-page="<?= $totalPages != 0 ? $totalPages : 1 ?>"><i
                            class="bi bi-caret-right"></i></a>
                    <?php
                }
                ?>
            </li>
            <li class="page-item">
                <a class="page-link" data-page="<?= $totalPages != 0 ? $totalPages : 1 ?>" href=""><i
                        class="bi bi-caret-right-fill"></i></a>
            </li>
        </ul>
    </nav>

    <?php

}

if (isset($_GET['table']) && $_GET['table'] == 'true') {
    $current = isset($_GET['currentpage']) && is_numeric($_GET['currentpage']) ? $_GET['currentpage'] : 1;
    $branch = $_GET['branch'] ?? NULL;
    $limitstart = ($current - 1) * $pageRows;

    $sql = "SELECT
                c.id,
                CONCAT(c.name, ' | ', c.brand) as item,
                t.transaction_status,
                CONCAT(c.container_size, ' ', c.quantity_unit) AS container_size,
                CONCAT(SUM(CASE WHEN t.transaction_status IS NULL OR t.transaction_status != 'Completed' THEN 0 ELSE ABS(tc.amt_used) END), ' ', c.quantity_unit) AS total_amt_used,
                CONCAT(c.unop_cont * c.container_size + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END), ' ', c.quantity_unit) AS total_stored,
                CONCAT((SUM(CASE WHEN t.transaction_status IS NULL OR t.transaction_status != 'Completed' THEN 0 ELSE ABS(tc.amt_used) END) + c.unop_cont  * c.container_size + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END)), ' ', c.quantity_unit) AS total_qty
            FROM
                transaction_chemicals tc
            JOIN
                transactions t
            ON
                t.id = tc.trans_id
            RIGHT JOIN
                chemicals c
            ON
                c.id = tc.chem_id
            WHERE
                c.request = 0";
    $data = [];
    $type = '';

    if ($branch !== '' && $branch !== NULL) {
        $data[] = $branch;
        $type .= 'i';
        $sql .= " AND c.branch = ? ";
    }

    $sql .= " GROUP BY
                c.name, c.brand
            ORDER BY
                c.name ASC, c.brand ASC
            LIMIT " . $limitstart . ", " . $pageRows . ";";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "<tr><td scope='row' colspan='8' class='text-center'>Statement preparation failed.</td></tr>";
        exit();
    }
    if (!empty($data)) {
        mysqli_stmt_bind_param($stmt, $type, ...$data);
    }
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_num_rows($result);


    // echo "<caption class='text-light'>List of all shit.</caption>";

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $item = $row["item"];
            $total_amt_used = $row["total_amt_used"];
            $total_stored = $row['total_stored'];
            $total_qty = $row['total_qty'];
            $contsize = $row['container_size'];
            ?>
            <tr class="text-center">
                <td scope="row"><?= htmlspecialchars($id) ?></td>
                <td>
                    <?= htmlspecialchars($item) ?>
                </td>
                <td><?= htmlspecialchars($contsize) ?></td>
                <td>
                    <?= htmlspecialchars($total_stored) ?>
                </td>
                <td><?= htmlspecialchars($total_amt_used) ?></td>
                <td>
                    <?= htmlspecialchars($total_qty) ?>
                </td>
                <td>
                    <div class="d-flex justify-content-center">
                        <!-- add dispatch/return chem -->
                        <button type="button" class="btn btn-sidebar log-chem-btn" data-chem="<?= $id ?>"><i
                                class="bi bi-journal-text" data-bs-toggle="tooltip" title="Logs"></i></button>
                        <button type="button" id="editbtn" class="btn btn-sidebar editbtn" data-chem="<?= $id ?>"><i
                                class="bi bi-info-circle"></i></button>
                    </div>
                </td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='8' class='text-center'>No Summary Found.</td></tr>";
    }
    mysqli_close($conn);
    exit();
}


if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $branch = $_GET['branch'];

    $sql = "SELECT
                c.id,
                CONCAT(c.name, ' | ', c.brand) as item,
                t.transaction_status,
                CONCAT(c.container_size, ' ', c.quantity_unit) AS container_size,
                CONCAT(SUM(CASE WHEN t.transaction_status IS NULL OR t.transaction_status != 'Completed' THEN 0 ELSE ABS(tc.amt_used) END), ' ', c.quantity_unit) AS total_amt_used,
                CONCAT(c.unop_cont * c.container_size + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END), ' ', c.quantity_unit) AS total_stored,
                CONCAT((SUM(CASE WHEN t.transaction_status IS NULL OR t.transaction_status != 'Completed' THEN 0 ELSE ABS(tc.amt_used) END) + c.unop_cont  * c.container_size + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END)), ' ', c.quantity_unit) AS total_qty
            FROM                
                transaction_chemicals tc
            JOIN
                transactions t
            ON
                t.id = tc.trans_id
            RIGHT JOIN
                chemicals c
            ON
                c.id = tc.chem_id
            WHERE
                c.request = 0
                AND (c.name LIKE ? OR c.brand LIKE ? OR c.quantity_unit LIKE ? OR c.container_size LIKE ? OR c.id LIKE ?)";

    $data = [];
    $type = '';

    if ($branch !== '' && $branch !== NULL) {
        $data[] = $branch;
        $type .= 'i';
        $sql .= " AND c.branch = ? ";
    }
    $sql .= " GROUP BY
                c.name, c.brand
            ORDER BY
                c.name ASC, c.brand ASC;";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "<tr><td scope='row' colspan='8' class='text-center'>Error. Search stmt failed.</td></tr>";
        exit();
    }

    $search = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "sssss$type", $search, $search, $search, $search, $search, ...$data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $numrows = mysqli_num_rows($result);
    if ($numrows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $item = $row["item"];
            $total_amt_used = $row["total_amt_used"];
            $total_stored = $row['total_stored'];
            $total_qty = $row['total_qty'];
            $contsize = $row['container_size'];
            ?>
            <tr class="text-center">
                <td scope="row"><?= htmlspecialchars($id) ?></td>
                <td>
                    <?= htmlspecialchars($item) ?>
                </td>
                <td><?= htmlspecialchars($contsize) ?></td>
                <td>
                    <?= htmlspecialchars($total_stored) ?>
                </td>
                <td><?= htmlspecialchars($total_amt_used) ?></td>
                <td>
                    <?= htmlspecialchars($total_qty) ?>
                </td>
                <td>
                    <div class="d-flex justify-content-center">
                        <!-- add dispatch/return chem -->
                        <button type="button" class="btn btn-sidebar log-chem-btn" data-chem="<?= $id ?>"><i
                                class="bi bi-journal-text" data-bs-toggle="tooltip" title="Logs"></i></button>
                        <button type="button" id="editbtn" class="btn btn-sidebar editbtn" data-chem="<?= $id ?>"><i
                                class="bi bi-info-circle"></i></button>
                    </div>
                </td>
            </tr>


            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='8' class='text-center'>Your search does not exist.</td></tr>";
    }
    mysqli_close($conn);
    exit();
}
