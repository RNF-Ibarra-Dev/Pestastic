<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 8;
$rowCount = 'SELECT * FROM chemicals';
$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);


function row_status($conn, $entries = false, $ibranch = '')
{
    $rowCount = "SELECT COUNT(*) FROM chemicals";
    $queries = [];

    if ($entries === 'true') {
        $queries[] = 'request = 0';
    }

    if ($ibranch !== '' && $ibranch !== NULL) {
        $queries[] = "branch = ?";
        $branch = (int) $ibranch;
    }

    if (!empty($queries)) {
        $rowCount .= " WHERE " . implode(" AND ", $queries);
    }
    $rowCount .= ';';
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
    $totalRows = $row[0];

    $totalPages = ceil($totalRows / $GLOBALS['pageRows']);

    return ['pages' => $totalPages, 'rows' => $totalRows];
}

if (isset($_GET['pagenav']) && $_GET['pagenav'] === 'true') {
    $entries = $_GET['entries'];
    $branch = $_GET['branch'];

    if ($entries === 'true' || ($branch !== '' && $branch !== NULL)) {
        $rowstatus = row_status($conn, $entries, $branch);
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
    $entries = $_GET['hideentries'];
    $ibranch = $_GET['branch'];

    // echo var_dump($hideentries);
    // exit();

    $limitstart = ($current - 1) * $pageRows;
    $sql = "SELECT * FROM chemicals";
    $queries = [];

    if ($entries === 'true') {
        $queries[] = 'request = 0';
    }

    if ($ibranch !== '' && $ibranch !== NULL) {
        $queries[] = "branch = ?";
        $branch = (int) $ibranch;
    }

    if (!empty($queries)) {
        $sql .= " WHERE " . implode(" AND ", $queries);
    }

    $sql .= " ORDER BY request DESC, id DESC LIMIT " . $limitstart
        . ", " . $pageRows . ";";
    $stmt = mysqli_stmt_init($conn);
    $totalRows = 0;

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo "table stmt failed.";
        exit;
    }

    if ($ibranch !== '') {
        mysqli_stmt_bind_param($stmt, 'i', $branch);
    }

    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $rows = mysqli_num_rows($res);

    // echo var_dump(json_encode(mysqli_fetch_assoc($res)));
    // exit;
    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
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
            ?>
            <tr class="text-center">
                <td scope="row">
                    <?=
                        $request === 1 ? "<i class='bi bi-exclamation-diamond text-warning me-2' data-bs-toggle='tooltip' title='For Approval'></i><strong>" . htmlspecialchars($name) . "</strong><br>(For Approval)" : htmlspecialchars($name);
                    ?>
                </td>
                <td><?= htmlspecialchars($brand) ?></td>
                <td class="<?= $level <= 10 ? 'text-danger' : ($level <= 50 ? 'text-warning' : '') ?>">
                    <?= htmlspecialchars($level) . ' ml' ?>
                </td>
                <td><?= htmlspecialchars($remcom) ?></td>
                <td class="<?= $expDate == $now ? 'text-warning' : ($expDate < $now ? 'text-danger' : '') ?>">
                    <?= htmlspecialchars(date_format($exp, "F j, Y")) ?>
                </td>
                <td><?= $level === 0 ? "<span class='bg-danger px-2 py-1 bg-opacity-25 rounded-pill'>Out of Stock</span>" : ($level <= $contsize * 0.2 ? "<span class='bg-warning px-2 py-1 bg-opacity-25 rounded-pill'>Low Stock</span>" : "<span class='bg-success px-2 py-1 bg-opacity-25 rounded-pill'>Good</span>") ?></td>
                <td>
                    <div class="d-flex justify-content-center">
                        <?php
                        if ($request === 1) {
                            ?>
                            <button type="button" id="approvebtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#approveModal" data-id="<?= $id ?>" data-name="<?= $name ?>"><i
                                    class="bi bi-check-circle"></i></button>
                            <button type="button" class="btn btn-sidebar editbtn" data-chem="<?= $id ?>"><i
                                    class="bi bi-info-circle"></i></button>
                            <button type="button" id="delbtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-id="<?= $id ?>"><i class="bi bi-x-octagon"></i></button>
                            <?php
                        } else {

                            ?>
                            <button type="button" id="editbtn" class="btn btn-sidebar editbtn" data-chem="<?= $id ?>"><i
                                    class="bi bi-info-circle"></i></button>
                            <button type="button" id="delbtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-id="<?= $id ?>"><i class="bi bi-trash"></i></button>
                        <?php } ?>
                    </div>
                </td>
            </tr>

            <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='5' class='text-center'>Your search does not exist.</td></tr>";
    }
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $ibranch = $_GET['branch'];
    $entries = $_GET['entries'];
    $sql = "SELECT * FROM chemicals WHERE (name LIKE ? OR brand LIKE ? OR chemLevel LIKE ? OR expiryDate LIKE ?) ";

    // $sql .= $entries === 'true' ? "AND request = 0 ORDER BY request DESC, id DESC;" : 'ORDER BY id DESC;';

    $queries = [];
    if ($entries === 'true') {
        $queries[] = 'request = 0';
    }
    $bt = '';
    $data = [];
    if ($ibranch !== '' && $ibranch !== NULL) {
        $queries[] = "branch = ?";
        $branch = (int) $ibranch;
        $data[] = $branch;
        $bt = "i";
    }

    if (!empty($queries)) {
        $sql .= " AND " . implode(" AND ", $queries);
    }

    $sql .= " ORDER BY request DESC, id DESC;";


    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "<tr><td scope='row' colspan='5' class='text-center'>Error. Search stmt failed.</td></tr>";
        exit();
    }

    $search = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "ssss$bt", $search, $search, $search, $search, ...$data);
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

            ?>
            <tr class="text-center">
                <td scope="row">
                    <?=
                        $request == '1' ? "<i class='bi bi-exclamation-diamond text-warning me-2' data-bs-toggle='tooltip' title='For Approval'></i><strong>" . htmlspecialchars($name) . "</strong><br>(For Approval)" : htmlspecialchars($name);
                    ?>
                </td>
                <td><?= htmlspecialchars($brand) ?></td>
                <td class="<?= $level <= 10 ? 'text-danger' : ($level <= 50 ? 'text-warning' : '') ?>">
                    <?= htmlspecialchars($level) . ' ml' ?>
                </td>
                <td class="<?= $expDate == $now ? 'text-warning' : ($expDate < $now ? 'text-danger' : '') ?>">
                    <?= htmlspecialchars(date_format($exp, "F j, Y")) ?>
                </td>
                <td>
                    <div class="d-flex justify-content-center">
                        <?php
                        if ($request === "1") {
                            ?>
                            <button type="button" id="approvebtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#approveModal" data-id="<?= $id ?>" data-name="<?= $name ?>"><i
                                    class="bi bi-check-circle"></i></button>
                            <button type="button" class="btn btn-sidebar editbtn" data-chem="<?= $id ?>"><i
                                    class="bi bi-info-circle"></i></button>
                            <button type="button" id="delbtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-id="<?= $id ?>"><i class="bi bi-x-octagon"></i></button>
                            <?php
                        } else {

                            ?>
                            <button type="button" id="editbtn" class="btn btn-sidebar editbtn" data-chem="<?= $id ?>"><i
                                    class="bi bi-info-circle"></i></button>
                            <button type="button" id="delbtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-id="<?= $id ?>"><i class="bi bi-trash"></i></button>
                        <?php } ?>
                    </div>
                </td>
            </tr>

            <?php
        }
    } else {
        // echo json_encode(['']);
        echo "<tr><td scope='row' colspan='5' class='text-center'>Your search does not exist.</td></tr>";
    }
}
?>