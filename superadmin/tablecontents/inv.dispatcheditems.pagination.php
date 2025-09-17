<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 5;
$rowCount = "SELECT COUNT(*) FROM chemicals
                WHERE request = 0
                AND chem_location = 'dispatched';";
$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);

function row_status($conn, $ibranch = '')
{
    $rowCount = "SELECT COUNT(*) FROM chemicals
                WHERE request = 0
                AND chem_location = 'dispatched'";
    $queries = [];

    if ($ibranch !== '' && $ibranch !== NULL) {
        $queries[] = "branch = ?";
        $branch = (int) $ibranch;
    }

    if (!empty($queries)) {
        $rowCount .= " AND " . implode(" AND ", $queries) . ";";
    }
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
    $totalRows = $row[0] ?? 0;

    $totalPages = (int) ceil($totalRows / $GLOBALS['pageRows']);

    return ['pages' => $totalPages, 'rows' => $totalRows];
}


if (isset($_GET['pagenav']) && $_GET['pagenav'] == 'true') {

    $branch = $_GET['branch'] ?? NULL;

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

    $sql = "SELECT tc.chem_brand, tc.amt_used AS amount_dispatched, c.quantity_unit AS unit, c.id AS item_id, tc.trans_id AS transaction FROM transaction_chemicals tc JOIN chemicals c ON c.id = tc.chem_id JOIN transactions t ON t.id = tc.trans_id WHERE t.transaction_status = 'Dispatched'";

    $data = [];
    $type = '';
    if ($branch !== '' && $branch !== NULL) {
        $data[] = $branch;
        $type .= 'i';
        $sql .= " AND c.branch = ?";
    }

    $sql .= " ORDER BY t.updated_at
            DESC LIMIT " . $limitstart . ", " . $pageRows . ";";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "<tr><td scope='row' colspan='7' class='text-center'>Statement preparation failed.</td></tr>";
        exit();
    }

    if (!empty($data)) {
        mysqli_stmt_bind_param($stmt, $type, ...$data);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['item_id'];  
            $item_name = $row['chem_brand'];
            $trans_id = $row['transaction'];
            $amount_dispatched = $row['amount_dispatched'];
            $unit = $row['unit'];
            // $name = $row["name"];
            // $brand = $row["brand"];
            // $level = $row['chemLevel'];
            // $unit = $row['quantity_unit'];
            // $opened = $level <= 0 ? "Empty" : "$level";
            // $contsize = $row['container_size'];
            // $datereceived = $row['date_received'];
            // $unopened = $row['unop_cont'];
            // $threshold = $row['restock_threshold'];
            // $loc = $row['chem_location'];
            // $trans_id = $row['trans_id'];
            ?>
            <tr class="text-center">
                <td>
                    <?= htmlspecialchars($id) ?>
                </td>
                <td><?= htmlspecialchars($item_name) ?></td>
                <td><?= htmlspecialchars($trans_id) ?></td>
                <td><?= htmlspecialchars("$amount_dispatched$unit") ?></td>
                <td>
                    <button type="button" class="btn btn-sidebar border border-dark rounded-4 editbtn"
                        data-chem="<?= htmlspecialchars($id) ?>"><i class="bi bi-info-circle text-dark"></i></button>
                    <a href="transactions.php?openmodal=true&id=<?= htmlspecialchars($trans_id) ?>"
                        class="btn btn-sidebar border border-dark rounded-4 disabled-returnbtn"
                        data-return="<?= htmlspecialchars($id) ?>"><i class=" bi bi-box-arrow-in-left text-dark"></i></a>
                </td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='7' class='text-center'>No dispatched items found.</td></tr>";
    }
    mysqli_close($conn);
    exit();
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];

    $sql = "SELECT * FROM chemicals
            WHERE request = 0
            AND chem_location = 'dispatched'
            AND restock_threshold >= ((CASE
                WHEN chemLevel > 0 THEN 1
                ELSE 0
                END ) + unop_cont)
            ORDER BY id";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "<tr><td scope='row' colspan='7' class='text-center'>Error. Search stmt failed.</td></tr>";
        exit();
    }

    $search = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "sssss", $search, $search, $search, $search, $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $numrows = mysqli_num_rows($result);
    if ($numrows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row["name"];
            $brand = $row["brand"];
            $level = $row['chemLevel'];
            $unit = $row['quantity_unit'];
            $opened = $level <= 0 ? "Empty" : "$level";
            $contsize = $row['container_size'];
            $datereceived = $row['date_received'];
            $unopened = $row['unop_cont'];
            $threshold = $row['restock_threshold'];
            $loc = $row['chem_location'];
            ?>
            <tr class="text-center">
                <td>
                    <?= htmlspecialchars($id) ?>
                </td>
                <td><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($brand) ?></td>
                <td>
                    <?= htmlspecialchars("$opened / $contsize $unit") ?>
                </td>
                <td>
                    <?= htmlspecialchars($unopened) ?>
                </td>
                <td>
                    <?= htmlspecialchars($threshold) ?>
                </td>
                <td>
                    <button type="button" class="btn btn-sidebar border border-dark rounded-4 editbtn"
                        data-chem="<?= htmlspecialchars($id) ?>"><i class="bi bi-info-circle text-dark"></i></button>
                    <button type="button" class="btn btn-sidebar border border-dark rounded-4 restock-btn"
                        data-chem="<?= htmlspecialchars($id) ?>"><i class=" bi bi-box text-dark"></i></button>
                </td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='7' class='text-center'>Your search does not exist.</td></tr>";
    }
    mysqli_close($conn);
    exit();
}
