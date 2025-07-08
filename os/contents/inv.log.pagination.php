<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');


$pageRows = 5;
$rowCount = 'SELECT * FROM inventory_log;';
$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);

if (isset($_GET['inventorylog']) && $_GET['inventorylog'] == 'true') {
    $current = isset($_GET['currentpage']) && is_numeric($_GET['currentpage']) ? $_GET['currentpage'] : 1;

    $limitstart = ($current - 1) * $pageRows;

    $sql = "SELECT * FROM inventory_log;";

    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);


    // echo "<caption class='text-light'>List of all shit.</caption>";

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['log_id'];
            $chemid = $row['chem_id'];
            $chemname = get_chemical_name($conn, $chemid);
            $logtype = $row['log_type'];
            $qty = $row['quantity'];
            $logdate = $row['log_date'];
            $role = $row['user_role'];
            $userid = $row['user_id'];
            $user = get_user($conn, $userid, $role);
            $transid = $row['trans_id'] === NULL ? 'None' : $row['trans_id'];
            $notes = $row['notes'];

            ?>
            <tr class="text-center text-dark">
                <td scope="row"><?=htmlspecialchars($logdate)?></td>
                <td><?=htmlspecialchars($logtype)?></td>
                <td><?=htmlspecialchars($chemname)?></td>
                <td><?=htmlspecialchars($qty)?></td>
                <td><?=htmlspecialchars($user)?></td>
                <td><?=htmlspecialchars($transid)?></td>
                <td><?=htmlspecialchars($notes)?></td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='7' class='text-center text-dark'>No existing logs found.</td></tr>";
    }
}
if (isset($_GET['chemloghistory']) && $_GET['chemloghistory'] == 'true') {
    $current = isset($_GET['currentpage']) && is_numeric($_GET['currentpage']) ? $_GET['currentpage'] : 1;

    // $limitstart = ($current - 1) * $pageRows;
    $chemid = (int) $_GET['chemid'];

    $sql = "SELECT * FROM inventory_log WHERE chem_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        http_response_code(400);
        echo "Stmt Failed. Please try again later.";
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $chemid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rows = mysqli_num_rows($result);


    // echo "<caption class='text-light'>List of all shit.</caption>";

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['log_id'];
            $chemid = $row['chem_id'];
            $chemname = get_chemical_name($conn, $chemid);
            $logtype = $row['log_type'];
            $qty = $row['quantity'];
            $logdate = $row['log_date'];
            $role = $row['user_role'];
            $userid = $row['user_id'];
            $user = get_user($conn, $userid, $role);
            $transid = $row['trans_id'] === NULL ? 'None' : $row['trans_id'];
            $notes = $row['notes'];

            ?>
            <tr class="text-center text-dark">
                <td scope="row"><?=htmlspecialchars($logdate)?></td>
                <td><?=htmlspecialchars($logtype)?></td>
                <td><?=htmlspecialchars($qty)?></td>
                <td><?=htmlspecialchars($user)?></td>
                <td><?=htmlspecialchars($transid)?></td>
                <td><?=htmlspecialchars($notes)?></td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='7' class='text-center text-dark'>No recorded data.</td></tr>";
    }
}

if (isset($_GET['pagenav']) && $_GET['pagenav'] == 'true') {
    $entries = $_GET['entries'];

    // if ($entries === 'true') {
    //     $rowstatus = row_status($conn, $entries);
    //     $totalRows = $rowstatus['rows'];
    //     $totalPages = $rowstatus['pages'];
    // } else {
    //     $GLOBALS['totalPages'];
    // }
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