<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 10;
$rowCount = 'SELECT * FROM chemicals WHERE request = 0';
$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);

function row_status($conn, $entries = false)
{
    $rowCount = "SELECT COUNT(*) FROM chemicals";

    if ($entries) {
        $rowCount .= "  WHERE request = 0;";
    } else {
        $rowCount .= ";";
    }

    $totalRows = 0;
    $result = mysqli_query($conn, $rowCount);
    $row = mysqli_fetch_row($result);
    $totalRows = $row[0];

    $totalPages = ceil($totalRows / $GLOBALS['pageRows']);

    return ['pages' => $totalPages, 'rows' => $totalRows];
}


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

    // $sql = "SELECT  
    //             c.id AS chem_id,
    //             c.name,
    //             c.brand,
    //             c.chemLevel,
    //             c.container_size,
    //             c.unop_cont, 
    //             c.quantity_unit,
    //             il.log_type, 
    //             il.quantity, 
    //             il.containers_affected_count AS ccount, 
    //             il.log_date
    //         FROM 
    //             chemicals c 
    //         LEFT JOIN 
    //             inventory_log il ON c.id = il.chem_id
    //         WHERE 
    //             c.request = 0 
    //             AND c.chemLevel > 0 
    //             AND c.expiryDate > NOW()
    //         ORDER BY 
    //         c.request DESC, c.id DESC LIMIT " . $limitstart
    //             . ", " . $pageRows . ";";

    $sql = "SELECT
                c.id AS chem_id,
                c.name,
                c.brand,
                c.quantity_unit AS unit,
                c.container_size,
                (c.chemLevel + (c.unop_cont * c.container_size)) AS total_stored_quantity,
                SUM(CASE
                    WHEN il.log_type IN ('Out', 'Used', 'Disposed', 'Trashed Item', 'Lost/Damaged Item')
                    AND il.containers_affected_count = 0
                    THEN ABS(il.quantity)
                    ELSE 0
                END) AS total_used_open,
                SUM(CASE
                    WHEN il.log_type IN ('Out', 'Used', 'Disposed', 'Trashed Item', 'Lost/Damaged Item')
                    AND il.containers_affected_count < 0
                    THEN ABS(il.quantity)
                    ELSE 0
                END) AS total_used_closed
            FROM
                chemicals c
            LEFT JOIN
                inventory_log il ON c.id = il.chem_id
            WHERE
                c.request = 0
                AND c.chemLevel > 0
                AND c.expiryDate > NOW()
            GROUP BY
                c.id, c.name, c.brand, c.container_size, c.chemLevel, c.unop_cont
            ORDER BY
                c.id
                DESC LIMIT " . $limitstart. ", " . $pageRows . ";";

    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);


    // echo "<caption class='text-light'>List of all shit.</caption>";

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['chem_id'];
            $name = $row["name"];
            $brand = $row["brand"];
            $unit = $row['unit'];
            $total_stored = $row['total_stored_quantity'];
            $total_used_open = $row['total_used_open'];
            $total_used_closed = $row['total_used_closed'];
            $total_qty = $total_stored + $total_used_open + $total_used_closed;
            // $level = $row["chemLevel"];
            // $container_count = (int) $row['unop_cont'];
            $contsize = (int) $row['container_size'];
            // $total_stored = $level + ($container_count * $contsize);
            // $unit = $row['quantity_unit'];
            ?>
            <tr class="text-center">
                <td scope="row">
                    <?= htmlspecialchars("$name ($brand)") ?>
                </td>
                <td><?= htmlspecialchars("$contsize $unit") ?></td>
                <td>
                    <?= htmlspecialchars("$total_stored $unit") ?>
                </td>
                <td><?= htmlspecialchars("$total_used_open $unit") ?></td>
                <td>
                    <?= htmlspecialchars("$total_used_closed $unit") ?>
                </td>
                <td>
                    <?= htmlspecialchars("$total_qty $unit") ?>
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
        // echo json_encode(['']);
        echo "<tr><td scope='row' colspan='7' class='text-center'>No chemicals found.</td></tr>";
    }
}



if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $entries = $_GET['entries'];

    $sql = "SELECT * FROM chemicals WHERE (name LIKE ? OR brand LIKE ? OR chemLevel LIKE ? OR expiryDate LIKE ?) ";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "<tr><td scope='row' colspan='7' class='text-center'>Error. Search stmt failed.</td></tr>";
        exit();
    }

    $search = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "ssss", $search, $search, $search, $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $numrows = mysqli_num_rows($result);
    // echo $numrows;   
    if ($numrows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row["name"];
            $brand = $row["brand"];
            $level = $row["chemLevel"];
            $expDate = $row["expiryDate"];
            $request = $row['request'];
            $now = date("Y-m-d");
            $exp = date_create($expDate);
            $remcom = $row['unop_cont'];
            $contsize = $row['container_size'];
            $unit = $row['quantity_unit'];
            ?>
            <tr class="text-center">
                <td scope="row">
                    <?=
                        $request === '1' ? "<i class='bi bi-exclamation-diamond text-warning me-2' data-bs-toggle='tooltip' title='For Approval'></i><strong>" . htmlspecialchars($name) . "</strong><br>(For Approval)" : htmlspecialchars($name);
                    ?>
                </td>
                <td><?= htmlspecialchars($brand) ?></td>
                <td>
                    <?= htmlspecialchars("$level / $contsize$unit") ?>
                </td>
                <td><?= htmlspecialchars($remcom) ?></td>
                <td class="<?= $expDate == $now ? 'text-warning' : ($expDate < $now ? 'text-danger' : '') ?>">
                    <?= htmlspecialchars(date_format($exp, "F j, Y")) ?>
                </td>
                <td><?= $level === 0 ? "<span class='bg-danger px-2 py-1 bg-opacity-25 rounded-pill'>Out of Stock</span>" : ($level <= $contsize * 0.2 ? "<span class='bg-warning px-2 py-1 bg-opacity-25 rounded-pill'>Low Stock</span>" : "<span class='bg-success px-2 py-1 bg-opacity-25 rounded-pill'>Good</span>") ?>
                </td>
                <td>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-sidebar log-chem-btn" data-chem="<?= $id ?>"><i
                                class="bi bi-journal-text" data-bs-toggle="tooltip" title="Logs"></i></button>
                        <button type="button" id="editbtn" class="btn btn-sidebar editbtn" data-chem="<?= $id ?>"><i
                                class="bi bi-info-circle"></i></button>
                        <button type="button" class="btn btn-sidebar delbtn" data-bs-toggle="modal" data-bs-target="#deleteModal"
                            data-id="<?= $id ?>"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='7' class='text-center'>Your search does not exist.</td></tr>";
    }
}

