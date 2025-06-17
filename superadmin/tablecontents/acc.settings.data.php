<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");

if (isset($_GET['acc']) && $_GET['acc'] === 'true'){
    $id = $_GET['accountId'];

    if($id != $_SESSION['saID']){
        http_response_code(400);
        echo "Invalid Session ID. Refresh and try again.";
        exit();
    }

    $sql = "SELECT * FROM superadmin where saID = ?;";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)){
        http_response_code(400);
        echo "Account information stmt error.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $accountData = [];

    if(mysqli_num_rows($result)>0){
        if($row = mysqli_fetch_assoc($result)){
            $date = $row['saBirthdate'];
            $d = date_create($date);
            $dd = date_format($d, "F j, Y");
            $accountData['displaydate'] = $dd;
            $accountData['usn'] = $row['saUsn'];
            $accountData['fname'] = $row['saName'];
            $accountData['lname'] = $row['saLName'];
            $accountData['email'] = $row['saEmail'];
            $accountData['pwd'] = $row['saPwd'];
            $accountData['birthdate'] = $row['saBirthdate'];
            $accountData['branch'] = $row['user_branch'];
            
        }
    } else{
        http_response_code(400);
        echo "No returned data. Error.";
        exit();
    }
    echo json_encode($accountData);
    mysqli_stmt_close($stmt);
    exit();
}