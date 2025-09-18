<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 5;
$rowCount = "SELECT 
                c.id,
                c.name,
                c.brand,
                c.container_size,
                c.quantity_unit,
                SUM(c.unop_cont + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END)) AS container_in,
                SUM((CASE 
                        WHEN t.transaction_status = 'Dispatched' THEN 
                            (CASE 
                                WHEN tc.amt_used = 0 THEN 0
                                WHEN tc.amt_used < c.container_size THEN 1
                                WHEN tc.amt_used / c.container_size < 1 AND tc.amt_used / c.container_size > 0 THEN 1
                                ELSE 
                                    FLOOR(tc.amt_used / c.container_size) + (CASE WHEN tc.amt_used % c.container_size > 0 THEN 1 ELSE 0 END)
                            END) 
                        ELSE 0 
                    END)) AS container_out,
                
                (SUM(c.unop_cont + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END)) +
                SUM((CASE 
                        WHEN t.transaction_status = 'Dispatched' THEN 
                            (CASE 
                                WHEN tc.amt_used = 0 THEN 0
                                WHEN tc.amt_used < c.container_size THEN 1
                                WHEN tc.amt_used / c.container_size < 1 AND tc.amt_used / c.container_size > 0 THEN 1
                                ELSE 
                                    FLOOR(tc.amt_used / c.container_size) + (CASE WHEN tc.amt_used % c.container_size > 0 THEN 1 ELSE 0 END)
                            END) 
                        ELSE 0 
                    END))) AS total_containers
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
                c.request = 0
            AND
                (c.chemLevel > 0 OR c.unop_cont > 0)
            AND
                c.branch = {$_SESSION['branch']}
            GROUP BY 
                c.name, c.brand, c.container_size;";
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
                c.id,
                c.name,
                c.brand,
                c.container_size,
                c.quantity_unit,
                SUM(c.unop_cont + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END)) AS container_in,
                SUM((CASE 
                        WHEN t.transaction_status = 'Dispatched' THEN 
                            (CASE 
                                WHEN tc.amt_used = 0 THEN 0
                                WHEN tc.amt_used < c.container_size THEN 1
                                WHEN tc.amt_used / c.container_size < 1 AND tc.amt_used / c.container_size > 0 THEN 1
                                ELSE 
                                    FLOOR(tc.amt_used / c.container_size) + (CASE WHEN tc.amt_used % c.container_size > 0 THEN 1 ELSE 0 END)
                            END) 
                        ELSE 0 
                    END)) AS container_out,
                
                (SUM(c.unop_cont + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END)) +
                SUM((CASE 
                        WHEN t.transaction_status = 'Dispatched' THEN 
                            (CASE 
                                WHEN tc.amt_used = 0 THEN 0
                                WHEN tc.amt_used < c.container_size THEN 1
                                WHEN tc.amt_used / c.container_size < 1 AND tc.amt_used / c.container_size > 0 THEN 1
                                ELSE 
                                    FLOOR(tc.amt_used / c.container_size) + (CASE WHEN tc.amt_used % c.container_size > 0 THEN 1 ELSE 0 END)
                            END) 
                        ELSE 0 
                    END))) AS total_containers
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
                c.request = 0
            AND
                (c.chemLevel > 0 OR c.unop_cont > 0)
            AND
                c.branch = {$_SESSION['branch']}
            GROUP BY 
                c.name, c.brand, c.container_size
            ORDER BY 
                c.name
            DESC LIMIT " . $limitstart . ", " . $pageRows . ";";

    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row["name"];
            $brand = $row["brand"];
            $unit = $row['quantity_unit'];
            $total_containers = $row['total_containers'];
            $in = $row['container_in'];
            $out = $row['container_out'];
            // $level = $row["chemLevel"];
            // $container_count = (int) $row['unop_cont'];
            $contsize = (int) $row['container_size'];
            // $total_stored = $level + ($container_count * $contsize);
            // $unit = $row['quantity_unit'];
            ?>
            <tr class="text-center">
                <td>
                    <?= htmlspecialchars("$name ($brand)") ?>
                </td>
                <td><?= htmlspecialchars("$contsize $unit") ?></td>
                <td>
                    <?= htmlspecialchars($in) ?>
                </td>
                <td><?= htmlspecialchars($out) ?></td>
                <td>
                    <?= htmlspecialchars($total_containers) ?>
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
        echo "<tr><td scope='row' colspan='6' class='text-center'>No chemicals found.</td></tr>";
    }
    mysqli_close($conn);
    exit();
}



if (isset($_GET['search'])) {
    $search = $_GET['search'];

    $sql = "SELECT 
                c.id,
                c.name,
                c.brand,
                c.container_size,
                c.quantity_unit,
                SUM(c.unop_cont + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END)) AS container_in,
                SUM((CASE 
                        WHEN t.transaction_status = 'Dispatched' THEN 
                            (CASE 
                                WHEN tc.amt_used = 0 THEN 0
                                WHEN tc.amt_used < c.container_size THEN 1
                                WHEN tc.amt_used / c.container_size < 1 AND tc.amt_used / c.container_size > 0 THEN 1
                                ELSE 
                                    FLOOR(tc.amt_used / c.container_size) + (CASE WHEN tc.amt_used % c.container_size > 0 THEN 1 ELSE 0 END)
                            END) 
                        ELSE 0 
                    END)) AS container_out,
                
                (SUM(c.unop_cont + (CASE WHEN c.chemLevel > 0 THEN 1 ELSE 0 END)) +
                SUM((CASE 
                        WHEN t.transaction_status = 'Dispatched' THEN 
                            (CASE 
                                WHEN tc.amt_used = 0 THEN 0
                                WHEN tc.amt_used < c.container_size THEN 1
                                WHEN tc.amt_used / c.container_size < 1 AND tc.amt_used / c.container_size > 0 THEN 1
                                ELSE 
                                    FLOOR(tc.amt_used / c.container_size) + (CASE WHEN tc.amt_used % c.container_size > 0 THEN 1 ELSE 0 END)
                            END) 
                        ELSE 0 
                    END))) AS total_containers
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
                c.request = 0
            AND
                (c.chemLevel > 0 OR c.unop_cont > 0)
            AND
                c.branch = {$_SESSION['branch']}
            AND 
                (c.id LIKE ? OR name LIKE ? OR c.brand LIKE ? OR c.quantity_unit LIKE ? OR c.container_size LIKE ?)
            GROUP BY 
                c.name, c.brand, c.container_size
            ORDER BY 
                c.name;";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "<tr><td scope='row' colspan='6' class='text-center'>Error. Search stmt failed.</td></tr>";
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
            $unit = $row['quantity_unit'];
            $total_containers = $row['total_containers'];
            $in = $row['container_in'];
            $out = $row['container_out'];
            // $level = $row["chemLevel"];
            // $container_count = (int) $row['unop_cont'];
            $contsize = (int) $row['container_size'];
            // $total_stored = $level + ($container_count * $contsize);
            // $unit = $row['quantity_unit'];
            ?>
            <tr class="text-center">
                <td>
                    <?= htmlspecialchars("$name ($brand)") ?>
                </td>
                <td><?= htmlspecialchars("$contsize $unit") ?></td>
                <td>
                    <?= htmlspecialchars($in) ?>
                </td>
                <td><?= htmlspecialchars($out) ?></td>
                <td>
                    <?= htmlspecialchars($total_containers) ?>
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
        echo "<tr><td scope='row' colspan='6' class='text-center'>Your search does not exist.</td></tr>";
    }
    mysqli_close($conn);
    exit();
}
