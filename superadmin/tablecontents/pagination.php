<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 10;
$rowCount = 'SELECT * FROM chemicals';
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

    $sql = "SELECT * FROM chemicals ORDER BY request DESC, id DESC LIMIT " . $limitstart
        . ", " . $pageRows . ";";

    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);


    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row["name"];
            $brand = $row["brand"];
            $level = $row["chemLevel"];
            $expDate = $row["expiryDate"];
            $request = $row['request'];
    ?>
            <tr class="text-center">
                <td scope="row">
                    <?=
                    $request === '1' ? "<i class='bi bi-exclamation-diamond me-2' data-bs-toggle='tooltip' title='For Approval'></i><strong>" . htmlspecialchars($name) . "</strong><br>(For Approval)" : htmlspecialchars($name);
                    ?>
                </td>
                <td><?= htmlspecialchars($brand) ?></td>
                <td><?= htmlspecialchars($level) ?></td>
                <td><?= htmlspecialchars($expDate) ?></td>
                <td>
                    <div class="d-flex justify-content-center">
                        <?php
                        if ($request === "1") {
                        ?>
                            <button type="button" id="approvebtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#approveModal" data-id="<?= $id ?>" data-name="<?=$name?>"><i
                                    class="bi bi-check-circle"></i></button>
                            <button type="button" id="delbtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-id="<?= $id ?>"><i
                                    class="bi bi-x-octagon"></i></button>
                        <?php
                        } else {
                        ?>
                            <button type="button" id="editbtn" class="btn btn-sidebar" data-bs-toggle="modal"
                                data-bs-target="#editModal" data-id="<?= $id ?>" data-name="<?= htmlspecialchars($name) ?>"
                                data-brand="<?= htmlspecialchars($brand) ?>" data-level="<?= htmlspecialchars($level) ?>"
                                data-expdate="<?= htmlspecialchars($expDate) ?>"><i class="bi bi-pencil-square"></i></button>
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
?>