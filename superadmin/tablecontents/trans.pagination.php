<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

$pageRows = 5;
$rowCount = 'SELECT * FROM transactions';
$countResult = mysqli_query($conn, $rowCount);
$totalRows = mysqli_num_rows($countResult);
$totalPages = ceil($totalRows / $pageRows);

function treatment_name($conn, $id)
{
    if (!is_numeric($id)) {
        return "Invalid ID. ID passed: " . $id;
    }

    $sql = "SELECT t_name FROM treatments WHERE id = $id;";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            return $row['t_name'];
        }
    } else {
        return "No treatment found.";
    }
}




if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $status = $_GET['status'] ?? false;
    $branch = $_GET['branch'] ?? false;

    $sql = "SELECT * FROM transactions WHERE (id LIKE ? OR treatment_date LIKE ? OR customer_name LIKE ?
                    OR treatment LIKE ?)";
    if ($status) {
        $sql .= " AND transaction_status = ?";
    }

    if ($branch) {
        $sql .= " AND branch = ?";
    }

    $sql .= " ORDER BY id DESC;";

    // if ($status != '') {
    //     $sql = "SELECT * FROM transactions 
    //             WHERE (id LIKE ? OR treatment_date LIKE ? OR customer_name LIKE ?
    //                 OR treatment LIKE ?) 
    //             AND transaction_status = ? 
    //             ORDER BY id DESC;";
    // } else {
    //     $sql = "SELECT * FROM transactions 
    //             WHERE id LIKE ? OR treatment_date LIKE ? OR customer_name LIKE ?
    //                 OR treatment LIKE ? OR transaction_status LIKE ? 
    //             ORDER BY id DESC;";
    // }


    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'ERROR STMT';
        exit();
    }

    $ssearch = "%" . $search . "%";
    // $sstatus = "%" . $status . "%";

    if ($status != '') {
        mysqli_stmt_bind_param($stmt, 'sssss', $ssearch, $ssearch, $ssearch, $ssearch, $status);
    } else {
        mysqli_stmt_bind_param($stmt, 'sssss', $ssearch, $ssearch, $ssearch, $ssearch, $ssearch);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_num_rows($result);
    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customerName = $row['customer_name'];
            $treatmentDate = $row['treatment_date'];
            $treatment = $row['treatment'];
            $t_name = treatment_name($conn, intval($treatment));
            $createdAt = $row['created_at'];
            $updatedAt = $row['updated_at'];
            $status = $row['transaction_status'];
?>
            <tr class="text-center">
                <td scope="row"><?= $id ?></td>
                <td><?= htmlspecialchars($customerName) ?></td>
                <td><?= htmlspecialchars($treatmentDate) ?></td>
                <td><?= htmlspecialchars($t_name) ?></td>
                <td>
                    <?=
                    $status === 'Pending' ? "<a id='pendingbtn' data-pending-id='$id' dasta-bs-toggle='modal' data-bs-target='#approvemodal'
                             class='btn btn-sidebar rounded-pill border-0 p-0 w-100'><span class = 'text-light badge rounded-pill w-100 text-bg-warning bg-opacity-25'>Pending</span></a>" : ($status === 'Accepted' ? "<span class='badge rounded-pill text-bg-success bg-opacity-50 w-100'>$status</span>" : ($status === 'Voided' ? "<span class='badge rounded-pill text-bg-danger bg-opacity-50 w-100'>$status</span>" : ($status === 'Completed' ? "<span class='badge rounded-pill text-bg-info bg-opacity-25 text-light w-100'>$status</span>" : $status)))
                    ?>
                </td>
                <td>
                    <div class="d-flex justify-content-center">
                        <button id="tableDetails" disable-data-bs-toggle="modal" disabled-data-bs-target="#details-modal"
                            data-trans-id="<?= $id ?>" class="btn btn-sidebar me-2">Details</button>
                    </div>
                </td>
            </tr>


    <?php
        }
    } else {
        echo "<tr><td scope='row' colspan='6' class='text-center'>Search not found.</td></tr>";
    }
}

function row_status($conn, $istatus = '', $ibranch = '')
{

    $rowCount = "SELECT COUNT(*) FROM transactions";

    $queries = [];
    $data = [];
    $types = '';

    if ($istatus != NULL && $istatus != '') {
        $status = (string) $istatus;
        $queries[] = "transaction_status = ?";
        $data[] = $status;
        $types .= "s";
    }

    if ($ibranch != '' && $ibranch != NULL) {
        $branch = (int) $ibranch;
        $queries[] = "branch = ?";
        $data[] = $branch;
        $types .= 'i';
    }

    if(!empty($queries)){
        $rowCount .= " WHERE " . implode(" AND ", $queries);
    }
    $rowCount .= ";";

    $stmt = mysqli_stmt_init($conn);
    $totalRows = 0;

    if(!mysqli_stmt_prepare($stmt, $rowCount)){
        echo "row status stmt failed.";
        exit();
    }

    if(!empty($queries)){
        mysqli_stmt_bind_param($stmt, $types, ...$data);
    }

    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_row($res);
    $totalRows = $row[0];
    

    $totalPages = ceil($totalRows / $GLOBALS['pageRows']);

    return ['pages' => $totalPages, 'rows' => $totalRows];
}

// function row_status($conn, $status_input = '', $branch_input = '')
// {
//     // --- 1. Input Processing & Type Casting (Crucial First Step) ---
//     // This is where you prepare your function arguments into unambiguous filter values.

//     // For 'status' (string filter):
//     // If $status_input is '', it means no status filter.
//     $status_filter = (string) $status_input;

//     // For 'branch' (integer filter):
//     // Default to null, so if no valid branch is provided, no filter applies.
//     $branch_filter = null;
//     // Check if branch_input is provided AND is not an empty string
//     if ($branch_input !== '' && $branch_input !== null) {
//         $branch_filter = (int) $branch_input; // Cast to integer if it's a meaningful value
//     }
//     // Now:
//     //   - $status_filter will be '' or a string (e.g., 'pending').
//     //   - $branch_filter will be null (no filter), or an integer (e.g., 0, 101).


//     // --- 2. Dynamic Query Construction ---
//     // Always start with the base query for counting rows. Use COUNT(*).
//     $sql = "SELECT COUNT(*) FROM transactions";

//     // Arrays to dynamically build the WHERE clause, parameters, and types string
//     $conditions = [];   // Stores individual SQL WHERE conditions (e.g., "transaction_status = ?")
//     $params = [];       // Stores the actual values to bind for the prepared statement
//     $typesString = "";  // Stores the type string for mysqli_stmt_bind_param (e.g., "s", "i", "si")

//     // Add Status filter condition if $status_filter has a meaningful value (is not empty)
//     if (!empty($status_filter)) {
//         $conditions[] = "transaction_status = ?";
//         $params[] = $status_filter;
//         $typesString .= "s"; // 's' for string
//     }

//     // Add Branch filter condition if $branch_filter has a meaningful value (i.e., not null)
//     // This correctly includes 0 as a filterable branch ID.
//     if ($branch_filter !== null) {
//         $conditions[] = "branch = ?";
//         $params[] = $branch_filter;
//         $typesString .= "i"; // 'i' for integer
//     }

//     // Build the final SQL query string by adding the WHERE clause if any conditions exist
//     if (!empty($conditions)) {
//         $sql .= " WHERE " . implode(" AND ", $conditions); // Combines conditions with " AND "
//     }
//     $sql .= ";"; // Add semicolon at the very end of the complete query


//     // --- 3. Prepare and Execute the Statement ---
//     $stmt = mysqli_stmt_init($conn);
//     $totalRows = 0; // Default totalRows to 0 in case of error

//     // Always use prepared statements for consistency and security.
//     if (!mysqli_stmt_prepare($stmt, $sql)) {
//         error_log("SQL Prepare Failed: " . mysqli_error($conn) . " Query: " . $sql);
//         // Implement robust error handling here (e.g., throw an exception, return an error array)
//     } else {
//         // Bind parameters if there are any
//         if (!empty($params)) {
//             // The "..." splat operator unpacks the $params array into individual arguments
//             mysqli_stmt_bind_param($stmt, $typesString, ...$params);
//         }

//         // Execute the statement
//         mysqli_stmt_execute($stmt);

//         // Get the result set
//         $result = mysqli_stmt_get_result($stmt);

//         // Fetch the count (COUNT(*) query returns a single row with one column)
//         $row = mysqli_fetch_row($result);
//         $totalRows = $row[0];

//         // Close the statement
//         mysqli_stmt_close($stmt);
//     }

//     // --- 4. Calculate Total Pages and Return ---
//     $totalPages = ceil($totalRows / $GLOBALS['pageRows']);

//     return ['pages' => $totalPages, 'rows' => $totalRows];
// }

if (isset($_GET['paginate']) && $_GET['paginate'] == 'true') {
    $status = $_GET['status'];
    $branch = $_GET['branch'];
    $active = $_GET['active'];
    load_pagination($conn, (int) $active, $status, $branch);
}


function load_pagination($conn, $activepage = 1, $status = '', $branch = '')
{

    // list($countResult, $totalRows, $totalPages) = row_status($conn, $pageRows, $status);
    if ($status != '' || $branch != '') {
        $rowstatus = row_status($conn, $status, $branch);
        $totalRows = $rowstatus['rows'];
        $totalPages = $rowstatus['pages'];
    } else {
        $totalPages = $GLOBALS['totalPages'];
    }

    ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php
            // set active page ex. 1 = first page. Checks if numeric as well.
            // $activepage = isset($_GET['active']) && is_numeric($_GET['active']) ? $_GET['active'] : 1;

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
    // $current = $_GET['currentpage'] ? $_GET['currentpage'] : 1;
    $current = isset($_GET['currentpage']) && is_numeric($_GET['currentpage']) ? $_GET['currentpage'] : 1;
    $status = $_GET['status'] ?? false;
    $branch = $_GET['branch'] ?? false;

    $status = $_GET['status'];
    $branch = $_GET['branch'];

    $limitstart = ($current - 1) * $pageRows;

    $sql = "SELECT * FROM transactions ";

    if (!empty($status) || !empty($branch)) {
        $stmt = mysqli_stmt_init($conn);
        $sq = "transaction_status = ?";
        $bq = "branch = ?";
        $data = [];
        $sql .= "WHERE ";

        if ($status && $branch) {
            $sql .= "$sq AND $bq";
            $types = "si";
            $data[] = $status;
            $data[] = (int) $branch;
        } else {
            $sql .= $status ? $sq : $bq;
            $data[] = $status ? $status : (int) $branch;
            $types = $status ? "s" : "i";
        }
        $sql .= " ORDER BY id DESC LIMIT $limitstart, $pageRows;";
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$data);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
    } else {
        $sql .= " ORDER BY id DESC LIMIT $limitstart, $pageRows;";
        $result = mysqli_query($conn, $sql);
    }

    $rows = mysqli_num_rows($result);


    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $customerName = $row['customer_name'];
            $treatmentDate = $row['treatment_date'];
            $td = date("F j, Y", strtotime($treatmentDate));
            $treatment = $row['treatment'];
            $t_name = treatment_name($conn, $treatment);
            $createdAt = $row['created_at'];
            $updatedAt = $row['updated_at'];
            $status = $row['transaction_status'];
    ?>
            <tr class="text-center">
                <td scope="row"><?= $id ?></td>
                <td><?= htmlspecialchars($customerName) ?></td>
                <td><?= htmlspecialchars($td) ?></td>
                <td><?= htmlspecialchars($t_name) ?></td>
                <td>
                    <?=
                    $status === 'Pending' ? "<a id='pendingbtn' data-pending-id='$id' dasta-bs-toggle='modal' data-bs-target='#approvemodal'
                             class='btn btn-sidebar rounded-pill border-0 p-0 w-100'><span class = 'w-100 text-light badge rounded-pill text-bg-warning bg-opacity-25'>Pending</span></a>" : ($status === 'Accepted' ? "<span class='badge rounded-pill text-bg-success bg-opacity-50 w-100'>$status</span>" : ($status === 'Voided' ? "<span class='badge rounded-pill text-bg-danger bg-opacity-50 w-100'>$status</span>" : ($status === 'Completed' ? "<span class='badge rounded-pill text-bg-info bg-opacity-25 text-light w-100'>$status</span>" : $status)))
                    ?>
                </td>
                <td>
                    <div class="d-flex justify-content-center">
                        <button id="tableDetails" disable-data-bs-toggle="modal" disabled-data-bs-target="#details-modal"
                            data-trans-id="<?= $id ?>" class="btn btn-sidebar me-2">Details</button>
                    </div>
                </td>
            </tr>


<?php
        }
    } else {
        echo "<tr><td scope='row' colspan='6' class='text-center'>No data found.</td></tr>";
    }
}
