<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 5;
$rowCount = "SELECT * FROM chemicals WHERE request = 1;";
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

    $sql = "SELECT *
            FROM 
                chemicals
            WHERE
                request = 1   
            ORDER BY
                updated_at
            DESC LIMIT " . $limitstart . ", " . $pageRows . ";";

    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);


    // echo "<caption class='text-light'>List of all shit.</caption>";

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row["name"];
            $brand = $row["brand"];
            $contsize = (int) $row['container_size'];
            $unit = $row['quantity_unit'];
            $datereceived = $row['date_received'];
            $dr = date("F j, Y", strtotime($datereceived));
            $expiry = $row['expiryDate'];
            $e = date("F j, Y", strtotime($expiry));
            $added_by = $row['added_by'];
            $ab = $added_by === "No Record" ? "User not found." : $added_by;
    ?>
            <tr class="text-center">
                <td>
                    <?= htmlspecialchars($id) ?>
                </td>
                <td><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($brand) ?></td>
                <td>
                    <?= htmlspecialchars("$contsize $unit") ?>
                </td>
                <td>
                    <?= htmlspecialchars($dr) ?>
                </td>
                <td>
                    <?= htmlspecialchars($e) ?>
                </td>
                <td><?= htmlspecialchars($ab) ?></td>
            </tr>

        <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='6' class='text-center'>No entry found.</td></tr>";
    }
    mysqli_close($conn);
    exit();
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];

    $sql = "SELECT
                id,
                name,
                brand,
                quantity_unit AS unit,
                container_size,
                (unop_cont + (CASE WHEN chemLevel > 0 THEN 1 ELSE 0 END)) AS total_container_stock,
                SUM(CASE
                    WHEN chem_location = 'main_storage' THEN (unop_cont + (CASE WHEN chemLevel > 0 THEN 1 ELSE 0 END))
                    ELSE 0
                END) AS containers_in_storage,
                SUM(CASE
                    WHEN chem_location IN ('stock_entry', 'dispatched', 'used_outside_site', 'awaiting_pickup') THEN (unop_cont + (CASE WHEN chemLevel > 0 THEN 1 ELSE 0 END))
                    ELSE 0
                END) AS containers_outside_storage
            FROM
                chemicals
            WHERE
                chemLevel > 0
                AND expiryDate > NOW()
                AND (id LIKE ? OR name LIKE ? OR brand LIKE ? OR quantity_unit LIKE ? OR container_size LIKE ?)
            GROUP BY
                id, name, brand, container_size, chemLevel, unop_cont, chem_location, quantity_unit, container_size
            ORDER BY
                id";

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
            $unit = $row['unit'];
            $total_containers = $row['total_container_stock'];
            $in = $row['containers_in_storage'];
            $out = $row['containers_outside_storage'];
            // $level = $row["chemLevel"];
            // $container_count = (int) $row['unop_cont'];
            $contsize = (int) $row['container_size'];
            // $total_stored = $level + ($container_count * $contsize);
            // $unit = $row['quantity_unit'];
        ?>
            <tr class="text-center">
                <td scope="row"><?= htmlspecialchars($id) ?></td>
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
        echo "<tr><td scope='row' colspan='7' class='text-center'>Your search does not exist.</td></tr>";
    }
    mysqli_close($conn);
    exit();
}
