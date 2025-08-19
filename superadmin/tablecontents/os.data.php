<?php
require_once "../../includes/dbh.inc.php";
require_once "../../includes/functions.inc.php";

if (isset($_GET['branchoptions']) && $_GET['branchoptions'] === 'true') {
    $sql = "SELECT * FROM branches;";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        echo "<option value='' selected>Filter Account Branch</option>";
        while ($row = mysqli_fetch_assoc($query)) {
            $id = $row['id'];
            $name = $row['name'];
            $loc = $row['location'];
            ?>
            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars("$name ($loc)") ?></option>
            <?php
        }
    }
}

if(isset($_GET['accountinfo']) && $_GET['accountinfo'] === 'true'){
    $id = $_GET['id'];
    if(!is_numeric($id)){
        http_response_code(400);
        echo "Invalid account ID. Please try again.";
        exit();
    }

    $sql = "SELECT * FROM branchadmin WHERE baID = ?;";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)){
        http_response_code(400);
        echo "Statement preparation failed.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    echo json_encode($row);
    exit();
}