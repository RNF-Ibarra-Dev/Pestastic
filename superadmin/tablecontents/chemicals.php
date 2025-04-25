<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM chemicals WHERE name LIKE '%" . $search . "%' OR brand LIKE '%" . $search . "%' OR chemLevel LIKE '%" . $search . "%' OR expiryDate LIKE '%" . $search . "%'";

    $result = mysqli_query($conn, $sql);
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
            ?>
            <tr>
                <td scope="row">
                    <?=
                        $request === '1' ? "<i class='bi bi-exclamation-diamond me-2' data-bs-toggle='tooltip' title='For Approval! Contact manager for more information.'></i><strong>" . htmlspecialchars($name) . "</strong>" : htmlspecialchars($name);
                    ?>
                </td>
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

// get manager ID for verification in modifying the chemical table
if (isset($_POST["managerId"])) {
    require_once("../../includes/functions.inc.php");
    echo $_SESSION['saID'];
}

// edit
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    // echo json_encode(['greet' => 'hello']);

    $id = $_POST['id'];
    $name = $_POST['name'];
    $brand = $_POST['chemBrand'];
    $level = $_POST['chemLevel'];
    $expDate = $_POST['expDate'];
    $pwd = $_POST['saPwd'];

    if (empty($name || $brand || $level)) {
        http_response_code(400);
        echo json_encode(['type' => 'emptyinput', 'error' => 'Make sure to fill up required forms.']);
    }



    if (validate($conn, $pwd) == true) {
        if (editChem($conn, $id, $name, $brand, $level, $expDate) == true) {
            // echo 'changes saved';
            echo json_encode(['success' => 'Changes Saved.']);
            exit();
        } else {
            http_response_code(400);
            echo json_encode(['type' => 'update', 'error' => 'No Changes Made.']);
            exit();
        }
    } else {
        http_response_code(400);
        echo json_encode(['type' => 'pwd', 'error' => 'Incorrect Password']);
        exit();
    }
}

// add
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    // $id = $_POST['id'];  
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['chemBrand']);
    $level = mysqli_real_escape_string($conn, $_POST['chemLevel']);
    $expDate = $_POST['expDate'];
    $saPwd = $_POST['saPwd'];

    if (!empty($expDate)) {
        $formatDate = date('Y-m-d', strtotime($expDate));
    } else {
        $formatDate = '2025-01-01';
    }

    if (!empty($name) && !empty($brand) && !empty($level)) {
        if (!empty($saPwd)) {
            if (validate($conn, $saPwd) == true) {
                if (addChemical($conn, $name, $brand, $level, $formatDate) == true) {
                    // http_response_code(200);
                    echo json_encode(['type' => 'dsfs', 'success' => 'Chemical Added!']);
                    exit;
                } else {
                    http_response_code(400);
                    echo json_encode(['type' => 'addFailed', 'error' => 'Function to add chemical failed.']);
                    exit;
                }
            } else {
                http_response_code(400);
                echo json_encode(['type' => 'wrongPwd', 'error' => 'Wrong Password.']);
                exit;
            }
        } else {
            http_response_code(400);
            echo json_encode(['type' => 'emptyPwd', 'error' => 'Empty Password.']);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(['type' => 'emptyInput', 'error' => 'Fields cannot be empty.']);
        exit;
    }
}

// delete
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_POST['saID'];
    $pwd = $_POST['pass'];
    $chemId = $_POST['chemID'];
    // convert ids to integer

    if (!empty($pwd) && !empty($id)) {
        if (validate($conn, $pwd) == true) {
            if (deleteChem($conn, $chemId) == true) {
                http_response_code(200);
                echo json_encode(['success' => 'Chemical Deleted.']);
                exit;
            } else {
                http_response_code(400);
                echo json_encode(['type' => 'delete', 'error' => 'Error. Deletion Failed.']);
                exit;
            }
        } else {
            http_response_code(400);
            echo json_encode(['type' => 'pwd', 'error' => 'Incorrect Password.']);
            exit;
        }
        // echo $id . ' ' . $pwd . ' ' . $chemId;
    } else {
        http_response_code(400);
        echo json_encode(['type' => 'empty', 'error' => 'Password verification cannot be empty.']);
        exit;
    }
}



if(isset($_POST['approve']) && $_POST['approve'] === 'true'){
    echo json_encode(['success' => 'Yes']);
}

?>