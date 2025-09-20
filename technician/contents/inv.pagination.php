<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 5;
$rowCount = 'SELECT * FROM chemicals WHERE request = 0 AND chem_location = "main_storage";';
$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);

if (isset($_GET['pagenav']) && $_GET['pagenav'] == 'true') {
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

    $limitstart = ($current - 1) * $pageRows;

    $sql = "SELECT * FROM chemicals WHERE request = 0 AND chem_location = 'main_storage' AND branch = {$_SESSION['branch']} ORDER BY id DESC LIMIT $limitstart, $pageRows;";

    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row["name"];
            $brand = $row["brand"];
            $level = $row["chemLevel"];
            $expDate = $row["expiryDate"];
            $request = (int) $row['request'];
            $now = date("Y-m-d");
            $exp = date_create($expDate);
            $remcom = $row['unop_cont'];
            $dr = $row['date_received'];
            $date_received = date_create($dr);
            $contsize = $row['container_size'];
            $unit = $row['quantity_unit'];
            $threshold = $row['restock_threshold'];
            $opened_container = $level > 0 ? 1 : 0;
            $cur_location = $row['chem_location'];
            if ($request === 1) {
                $location = "Stock Entry";
            } else if ($cur_location === 'main_storage') {
                $location = "Main Storage";
            } else if ($cur_location === 'dispatched') {
                $location = "Dispatched";
            } else {
                $location = "Unknown";
            }
            $stock_qty = $remcom + ($level > 0 ? 1 : 0);
            ?>
            <tr class="text-center">
                <td scope="row"><?php
                if ($cur_location === 'dispatched') {
                    echo htmlspecialchars("Dispatched Chemical ID: $id");
                } else {
                    echo htmlspecialchars($id);
                }
                ?></td>
                <td>
                    <?php
                    if ($request === 1) {
                        ?>
                        <i class="bi bi-exclamation-diamond text-warning" data-bs-toggle="tooltip" title="Pending Entry"></i><br>
                        <span class="fw-bold"><?= htmlspecialchars("Item pending for entry: $name ($brand)") ?></span>
                        <?php
                    } else {
                        echo htmlspecialchars("$name ($brand)");
                    }
                    ?>
                </td>
                <td><?= htmlspecialchars($stock_qty) ?></td>
                <td>
                    <?= htmlspecialchars(date_format($date_received, "F j, Y")) ?>
                </td>
                <td>
                    <?php
                    if ($request === 0) {
                        if ($stock_qty <= $threshold) {
                            echo "<span class='bg-warning px-2 py-1 bg-opacity-25 badge rounded-pill'>Running Out</span>";
                        } else {
                            echo "<span class='bg-custom-success px-2 py-1 badge rounded-pill'>Good</span>";
                        }
                    } else {
                        echo "<span class='bg-info px-2 py-1 bg-opacity-25 badge rounded-pill'>Pending Entry</span>";
                    }
                    ?>
                </td>
                <td>
                    <div class="d-flex justify-content-center">
                        <button type="button" id="editbtn" class="btn btn-sidebar editbtn border-0" data-chem="<?= $id ?>"><i
                                class="bi bi-info-circle"></i></button>
                    </div>
                </td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='6' class='text-center'>No items found.</td></tr>";
    }
    exit();
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // $entries = $_GET['entries'];

    $sql = "SELECT * FROM chemicals WHERE (id LIKE ? OR name LIKE ? OR brand LIKE ? OR chemLevel LIKE ? OR expiryDate LIKE ?) AND branch = ?";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "<tr><td scope='row' colspan='6' class='text-center'>Error. Search stmt failed.</td></tr>";
        exit();
    }
    $branch = (int) $_SESSION['branch'];

    $search = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "sssssi", $search, $search, $search, $search, $search, $branch);
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
            $request = (int) $row['request'];
            $now = date("Y-m-d");
            $exp = date_create($expDate);
            $remcom = $row['unop_cont'];
            $dr = $row['date_received'];
            $date_received = date_create($dr);
            $contsize = $row['container_size'];
            $unit = $row['quantity_unit'];
            $threshold = $row['restock_threshold'];
            $opened_container = $level > 0 ? 1 : 0;
            $cur_location = $row['chem_location'];
            if ($request === 1) {
                $location = "Stock Entry";
            } else if ($cur_location === 'main_storage') {
                $location = "Main Storage";
            } else if ($cur_location === 'dispatched') {
                $location = "Dispatched";
            } else {
                $location = "Unknown";
            }
            $stock_qty = $remcom + ($level > 0 ? 1 : 0);
            ?>
            <tr class="text-center">
                <td scope="row"><?php
                if ($cur_location === 'dispatched') {
                    echo htmlspecialchars("Dispatched Chemical ID: $id");
                } else {
                    echo htmlspecialchars($id);
                }
                ?></td>
                <td>
                    <?php
                    if ($request === 1) {
                        ?>
                        <i class="bi bi-exclamation-diamond text-warning" data-bs-toggle="tooltip" title="Pending Entry"></i><br>
                        <span class="fw-bold"><?= htmlspecialchars("Item pending for entry: $name ($brand)") ?></span>
                        <?php
                    } else {
                        echo htmlspecialchars("$name ($brand)");
                    }
                    ?>
                </td>
                <td><?= htmlspecialchars($stock_qty) ?></td>
                <td>
                    <?= htmlspecialchars(date_format($date_received, "F j, Y")) ?>
                </td>
                <td>
                    <?php
                    if ($request === 0) {
                        if ($stock_qty <= $threshold) {
                            echo "<span class='bg-warning px-2 py-1 bg-opacity-25 badge rounded-pill'>Running Out</span>";
                        } else {
                            echo "<span class='bg-custom-success px-2 py-1 badge rounded-pill'>Good</span>";
                        }
                    } else {
                        echo "<span class='bg-info px-2 py-1 bg-opacity-25 badge rounded-pill'>Pending Entry</span>";
                    }
                    ?>
                </td>
                <td>
                    <div class="d-flex justify-content-center">
                        <button type="button" id="editbtn" class="btn btn-sidebar editbtn border-0" data-chem="<?= $id ?>"><i
                                class="bi bi-info-circle"></i></button>
                    </div>
                </td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='6' class='text-center'>Your search does not exist. {$_SESSION['branch']}</td></tr>";
    }
}
?>