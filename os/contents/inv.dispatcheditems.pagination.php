<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 5;
$rowCount = "SELECT 
                tc.chem_brand, 
                tc.amt_used AS amount_dispatched, 
                c.quantity_unit AS unit, 
                c.id AS item_id, 
                tc.trans_id AS transaction 
            FROM 
                transaction_chemicals tc 
            JOIN 
                chemicals c
            ON 
                c.id = tc.chem_id 
            JOIN 
                transactions t 
            ON 
                t.id = tc.trans_id 
            WHERE 
                t.transaction_status = 'Dispatched'
            AND
                c.branch = {$_SESSION['branch']};";

$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);

if (isset($_GET['pagenav']) && $_GET['pagenav'] == 'true') {

    $GLOBALS['totalPages'];

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

    $limitstart = ($current - 1) * $pageRows;

    $sql = "SELECT 
                tc.chem_brand, 
                tc.amt_used AS amount_dispatched, 
                c.quantity_unit AS unit, 
                c.id AS item_id, 
                tc.trans_id AS transaction 
            FROM 
                transaction_chemicals tc 
            JOIN 
                chemicals c
            ON 
                c.id = tc.chem_id 
            JOIN 
                transactions t 
            ON 
                t.id = tc.trans_id 
            WHERE 
                t.transaction_status = 'Dispatched'
            AND
                c.branch = {$_SESSION['branch']}
            ORDER BY 
                t.updated_at DESC
            LIMIT " . $limitstart . ", " . $pageRows . ";";

    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);


    // echo "<caption class='text-light'>List of all shit.</caption>";

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['item_id'];
            $item_name = $row['chem_brand'];
            $trans_id = $row['transaction'];
            $amount_dispatched = $row['amount_dispatched'];
            $unit = $row['unit'];
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
        echo "<tr><td scope='row' colspan='5' class='text-center'>No entry found.</td></tr>";
    }
    mysqli_close($conn);
    exit();
}

