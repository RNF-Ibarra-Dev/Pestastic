<?php
// include 'tables.php';
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 1;
$rowCount = 'SELECT * FROM chemicals';
$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);


if (isset($_GET['pagenav']) && $_GET['pagenav'] == 'true') {
    ?>


    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php
            $activepage = isset($_GET['active']) && is_numeric($_GET['active']) ? $_GET['active'] : 1;
            $prev = $activepage - 1;
            $next = $activepage + 1;
            $pagenolimit = 2;
            $defpageno = 1;
            if($activepage > $pagenolimit){
                $defpageno = $activepage - $pagenolimit;
                var_dump($defpageno);
            }else{
                $defpageno = 1;
            }
            ?>
            <li class="page-item">
                <a class="page-link" data-page="1" href="">First</a>

            </li>
            <li class="page-item">
                <?php
                if ($prev > 0) {
                    ?>
                    <a class="page-link" data-page="<?= $prev ?>">Previous</a>
                    <?php
                } else { ?>
                    <a class="page-link" data-page="1">Previous</a>
                    <?php
                }
                ?>
            </li>
            <?php for ($currentPage=$defpageno; $currentPage <= $totalPages; $currentPage++) { ?>

                <li class="page-item">
                    <a class="page-link" data-page="<?php echo $currentPage; ?>"
                        href="<?= $currentPage ?>"><?= $currentPage ?></a>
                </li>
                <?php
                if ($currentPage >= 3) { ?>
                    <li class="page-item disabled">
                        <a class="page-link">...</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" data-page="<?= $totalPages ?>"><?= $totalPages ?></a>
                    </li>

                    <?php break;
                }
                ?>
            <?php } ?>

            <li class="page-item">
                <?php
                if ($next <= $totalPages) {
                    ?>
                    <a class="page-link" data-page="<?= $next ?>" href="">Next</a>
                    <?php
                } else { ?>
                    <a class="page-link" data-page="<?= $totalPages ?>">Next</a>
                    <?php
                }
                ?>
            </li>
            <li class="page-item">
                <a class="page-link" data-page="<?= $totalPages ?>" href="">Last</a>
            </li>
        </ul>
    </nav>
    <?php

}

if (isset($_GET['table']) && $_GET['table'] == 'true') {
    $current = isset($_GET['currentpage']) && is_numeric($_GET['currentpage']) ? $_GET['currentpage'] : 1;
    // $active = 

    // if ($current == 'last') {
    //     $current = null;
    //     $current = $totalPages;
    //     echo json_encode(["lastpage" => "$current"]);
    // }

    $limitstart = ($current - 1) * $pageRows;

    $sql = "SELECT * FROM chemicals LIMIT " . $limitstart
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
            ?>
            <tr>
                <td scope="row"><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($brand) ?></td>
                <td><?= htmlspecialchars($level) ?></td>
                <td><?= htmlspecialchars($expDate) ?></td>
                <td>
                    <div class="d-flex justify-content-center">
                        <button type="button" id="editbtn" class="btn btn-sidebar me-2" data-bs-toggle="modal"
                            data-bs-target="#editModal" data-id="<?= $id ?>" data-name="<?= htmlspecialchars($name) ?>"
                            data-brand="<?= htmlspecialchars($brand) ?>" data-level="<?= htmlspecialchars($level) ?>"
                            data-expdate="<?= htmlspecialchars($expDate) ?>"><i class="bi bi-person-gear me-1"></i>Edit</button>
                        <button type="button" id="delbtn" class="btn btn-sidebar me-2" data-bs-toggle="modal"
                            data-bs-target="#deleteModal" data-id="<?= $id ?>"><i class="bi bi-person-gear me-1"></i>Delete</button>
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