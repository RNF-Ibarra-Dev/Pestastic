<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

function emptyInputSignup($firstName, $lastName, $username, $email, $pwd, $pwdRepeat)
{
    $result;
    if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($pwd) || empty($pwdRepeat)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}
function emptyInputEdit($firstName, $lastName, $username, $email)
{
    $result;
    if (empty($firstName) || empty($lastName) || empty($username) || empty($email)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidUsername($username)
{
    $result;
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidFirstName($firstName)
{
    $result;
    if (!preg_match("/^[a-zA-Z]+$/", $firstName)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidLastName($lastName)
{
    $result;
    if (!preg_match("/^[a-zA-Z]+$/", $lastName)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidEmail($email)
{
    $result;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function pwdMatch($pwd, $pwdRepeat)
{
    $result;
    if ($pwd !== $pwdRepeat) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function userExists($conn, $username, $email)
{
    /* 
    ? is a placeholder to avoid sql injection by 
    sending the sql statement to the database first before the data 
    */
    $sql = "SELECT * FROM technician WHERE username = ? OR techEmail = ?;";
    // initializing prepared statements
    $stmt = mysqli_stmt_init($conn);
    // run and check in the database to know if there is an error
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../createAccount.php?error=stmtfailed");
        exit();
    }

    /* 
    provide the prepared statement first, data tpye (s - string, i - integer, d - double, b - BLOB)
    the last will be the actual data passed previously
    */
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    // execute statement
    mysqli_stmt_execute($stmt);
    // get result from $stmt statement
    $resultData = mysqli_stmt_get_result($stmt);

    // check if result exist
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}



$userTypes = [
    'sa' => [
        'id' => 'saID',
        'table' => 'superadmin',
        'usn' => 'saUsn',
        'email' => 'saEmail',
        'pwd' => 'saPwd',
        'empId' => 'saEmpId'
    ],
    'ba' => [
        'id' => 'baID',
        'table' => 'branchadmin',
        'usn' => 'baUsn',
        'email' => 'baEmail',
        'pwd' => 'baPwd',
        'empId' => 'baEmpId'
    ],
    'tech' => [
        'id' => 'technicianId',
        'table' => 'technician',
        'usn' => 'username',
        'email' => 'techEmail',
        'pwd' => 'techPwd',
        'empId' => 'techEmpId'
    ]
];

function email_token($conn, $token)
{
    $token_hash = hash('sha256', $token);

    $sql = "SELECT * FROM reset_password WHERE reset_token_hash = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "stmt failed.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, 's', $token_hash);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($res)) {
        return $row['email'];
    }
    return false;
}

function get_table($conn, $email)
{
    global $userTypes;
    $stmt = mysqli_stmt_init($conn);
    $rtable = [];
    foreach ($userTypes as $role => $details) {
        $table = $details['table'];
        $pwdcol = $details['pwd'];
        $temail = $details['email'];

        $sql = "SELECT * FROM $table WHERE $temail = ?;";

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($res) > 0) {
            $rtable['tablename'] = $table;
            $rtable['pwdcol'] = $pwdcol;
            $rtable['emailcol'] = $temail;
            return $rtable;
        }
    }
    return false;
}

function reset_password($conn, $pwd, $email, $token)
{
    $emailfromtoken = email_token($conn, $token);
    if ($emailfromtoken != $email) {
        return false;
    }

    $tdetails = get_table($conn, $email);
    if (!$tdetails) {
        echo 'Email not found from our database. ' . json_encode($tdetails);
        exit();
    }
    $table = $tdetails['tablename'];
    $pwdcol = $tdetails['pwdcol'];
    $emailcol = $tdetails['emailcol'];

    mysqli_begin_transaction($conn);
    try {
        // throw new Exception("tablename: $table, pwdcol: $pwdcol, emailcol: $emailcol");
        $sql = "UPDATE $table SET $pwdcol = ? WHERE $emailcol = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("stmt failed.");
        }

        mysqli_stmt_bind_param($stmt, 'ss', $pwd, $email);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            mysqli_commit($conn);
            // throw new Exception("new pwd: $pwd, email: $email");
            return true;
        }
        throw new Exception("Failed to set new password.");
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = "Error at line " . $e->getLine() . ". " . $e->getMessage() . " File: " . $e->getFile();
        return [
            'error' => $error,
            'line' => $e->getLine(),
            'msg' => $e->getMessage(),
            'file' => $e->getFile()
        ];
    }
}

function check_email($conn, $email)
{
    global $userTypes;
    $stmt = mysqli_stmt_init($conn);
    foreach ($userTypes as $role => $details) {
        $userEmail = $details['email'];
        $userTable = $details['table'];

        $sql = "SELECT * FROM $userTable WHERE $userEmail = ?;";
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "find email stmt error.";
            exit();
        }
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            return $row;
        }
    }
    mysqli_stmt_close($stmt);
    return false;
}

function check_expiry($conn, $email)
{
    $sql = "SELECT reset_token_expires_at FROM reset_password WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "stmt error";
        exit();
    }

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($res)) {
        return $row['reset_token_expires_at'];
    }
    return false;
}

function multiUserExists($conn, $username, $email)
{

    global $userTypes;
    $stmt = mysqli_stmt_init($conn);

    foreach ($userTypes as $role => $details) {
        $userTable = $details['table'];
        $userUsn = $details['usn'];
        $userEmail = $details['email'];

        $sql = "SELECT * FROM $userTable WHERE $userUsn = ? OR $userEmail = ?;";

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../createAccount.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, 'ss', $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row;
        }
    }
    mysqli_stmt_close($stmt);
    return false;
}

function invalid_emp_id($conn, $empId)
{

    global $userTypes;
    $stmt = mysqli_stmt_init($conn);

    foreach ($userTypes as $role => $details) {
        $userTable = $details['table'];
        $userEmpId = $details['empId'];

        $sql = "SELECT * FROM $userTable WHERE $userEmpId = ?;";

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, 'i', $empId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row;
        }
    }
    mysqli_stmt_close($stmt);
    return false;
}

function checkExistingAccs($conn, $username, $email, $id)
{
    global $userTypes;
    $stmt = mysqli_stmt_init($conn);

    foreach ($userTypes as $role => $details) {
        $userId = $details['id'];
        $userTable = $details['table'];
        $userUsn = $details['usn'];
        $userEmail = $details['email'];

        $sql = "SELECT * FROM $userTable WHERE ($userUsn = ? OR $userEmail = ?) AND $userId != ?;";

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../createAccount.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, 'ssi', $username, $email, $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row;
        }
    }
    return false;
    mysqli_stmt_close($stmt);
}

function employeeIdCheck($conn, $empId, $id = NULL)
{

    global $userTypes;

    foreach ($userTypes as $role => $details) {
        $userId = $details['id'];
        $userTable = $details['table'];
        $userEmpId = $details['empId'];
        $stmt = mysqli_stmt_init($conn);
        $sql = "SELECT * FROM $userTable WHERE $userEmpId = ?";
        if ($id === NULL) {
            $sql .= ";";
        } else {
            $sql .= " AND $userId != ?;";
        }


        if (!mysqli_stmt_prepare($stmt, $sql)) {
            return false;
            // exit();
        }
        if (empty($id)) {
            mysqli_stmt_bind_param($stmt, 's', $empId);
        } else {
            mysqli_stmt_bind_param($stmt, 'si', $empId, $id);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            return $row;
        }
    }
    return false;
    mysqli_stmt_close($stmt);
}

function createTechAccount($conn, $firstName, $lastName, $username, $email, $pwd, $contact, $address, $empId, $birthdate)
{

    $sql = "INSERT INTO technician (firstName, lastName, username, techEmail, techPwd, techContact, techAddress, techEmpId, techBirthdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../superadmin/create.tech.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssssssss", $firstName, $lastName, $username, $email, $hashedPwd, $contact, $address, $empId, $birthdate);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return true;
    } else {
        return ['error' => 'Account creation failed. Please contact administration.'];
    }
}

function createOpSupAccount($conn, $firstName, $lastName, $username, $email, $pwd, $contact, $address, $empId, $birthdate)
{

    $sql = "INSERT INTO branchadmin (baFName, baLName, baUsn, baEmail, baPwd, baContact, baAddress, baEmpId, baBirthdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../superadmin/create.os.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssssssss", $firstName, $lastName, $username, $email, $hashedPwd, $contact, $address, $empId, $birthdate);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return true;
    } else {
        return ['error' => 'Account creation failed. Please contact administration.'];
    }
}

function modify_sa($conn, $fname, $lname, $username, $email, $pwd = '', $bd, $empid, $id)
{

    $sql = "UPDATE superadmin SET saUsn = ?, saName = ?, saLName = ?, saEmail = ?, saBirthdate = ?, saEmpId = ?";
    $stmt = mysqli_stmt_init($conn);

    $types = "sssssi";
    $data = [$username, $fname, $lname, $email, $bd, $empid];
    if (!empty($pwd)) {
        $sql .= ", saPwd = ?";
        $types .= 's';
        $data[] = $pwd;
    }

    $sql .= " WHERE saID = ?;";
    $types .= "i";
    $data[] = $id;

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ["error" => "modification stmt error"];
    }

    mysqli_stmt_bind_param($stmt, $types, ...$data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_affected_rows($conn) > 0) {
        return true;
    } else {
        return ['error' => 'Update failed. Please make sure to check for new changes. ' . mysqli_stmt_error($stmt)];
    }
}

function addChemv2($conn, $dataArr, $branch, $addby, $request)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "INSERT INTO chemicals (name, brand, chemLevel, expiryDate, date_received, notes, branch, request, added_by, container_size, unop_cont) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "stmt failed";
            exit();
        }
        $affectedRows = 0;
        for ($i = 0; $i < count($dataArr['name']); $i++) {
            $cnote = $dataArr['notes'][$i] ?? '';
            $notes = $cnote === '' ? null : $cnote;
            mysqli_stmt_bind_param($stmt, 'ssissssisii', $dataArr['name'][$i], $dataArr['brand'][$i], $dataArr['level'][$i], $dataArr['eDate'][$i], $dataArr['rDate'][$i], $notes, $branch, $request, $addby, $dataArr['csize'][$i], $dataArr['ccount'][$i]);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error at " . $dataArr['name'] . ' ' . $dataArr['brand']);
            }
            $affectedRows++;
        }

        if ($affectedRows != count($dataArr['name'])) {
            throw new Exception("Data entry error. Incomplete insertion. Please Try Again. " . mysqli_stmt_affected_rows($stmt) . count($dataArr['name']));
        }
        mysqli_commit($conn);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'dataPassed' => $dataArr,
            'errorMessage' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'stringTrace' => $e->getTraceAsString()
        ];
    }
}
function addChemical($conn, $name, $brand, $level, $expdate, $dateadded, $notes, $receivedDate, $cb, $ub, $branch, $request = 0)
{
    if (!$request) {
        $sql = "INSERT INTO chemicals (name, brand, chemLevel, expiryDate, added_at, notes, branch) VALUES (?, ?, ?, ?, ?, ?, ?);";
    } else {
        $sql = "INSERT INTO chemicals (name, brand, chemLevel, expiryDate, added_at, notes, branch, request) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
    }
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Stmt Failed";
        exit();
    }

    if (!$request) {
        mysqli_stmt_bind_param($stmt, 'ssss', $name, $brand, $level, $expdate, $dateadded, $notes, $branch);
    } else {
        mysqli_stmt_bind_param($stmt, 'ssssi', $name, $brand, $level, $expdate, $request);
    }
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // echo "Chemical Added!";
        mysqli_stmt_close($stmt);
        return true;
    } else {
        // echo "New chemical addition failed.";
        mysqli_stmt_close($stmt);
        return false;
    }
}


function get_chemical_name($conn, $chemId)
{
    $sql = "SELECT * FROM chemicals WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'Fetch chemical name stmt failed.';
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $chemId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $brand = $row['brand'];
        $name = $row['name'];
        return "$name | $brand";
    } else {
        return false;
    }
}

function add_chemical_used($conn, $transactionId, $chemUsedId, $amtUsed = 0)
{
    $chemBrand = get_chemical_name($conn, $chemUsedId);
    if (!$chemBrand) {
        echo "Chemical Not Found";
        exit();
    }

    $sql = "INSERT INTO transaction_chemicals(trans_id, chem_id, chem_brand, amt_used) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'add chemical usage stmt failed.';
        exit();
    }
    mysqli_stmt_bind_param($stmt, "iisi", $transactionId, $chemUsedId, $chemBrand, $amtUsed);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        return true;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

function add_pest_prob($conn, $transId, $pestProblem)
{
    $sql = "INSERT INTO transaction_problems (trans_id, problem_id) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'add customer pest problems usage stmt failed.';
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $transId, $pestProblem);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        return true;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

function get_tech_name($conn, $id)
{
    $sql = "SELECT * FROM technician WHERE technicianId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'Fetch tech name stmt failed.' . mysqli_error($conn);
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $fn = $row['firstName'];
        $ln = $row['lastName'];
        // return $fn . ' ' . $ln;
        return "$fn $ln";
    } else {
        return false;
    }
}

function add_tech_trans($conn, $transId, $techId)
{
    $techInfo = get_tech_name($conn, $techId);
    if (!$techInfo) {
        // error_log('Error tech info');
        return false;
    }

    $sql = "INSERT INTO transaction_technicians (trans_id, tech_id, tech_info) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'record technician transactions stmt failed.';
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'iis', $transId, $techId, $techInfo);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        return true;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

// subtract chem
function update_chem_level($conn, $id, $ovalue, $uvalue)
{
    $updatedval = $ovalue - $uvalue;

    $sql = "UPDATE chemicals SET chemLevel = ? WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'STMT ERROR'];
    }
    mysqli_stmt_bind_param($stmt, 'ii', $updatedval, $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return true;
    } else {
        return ['error' => 'Not updated' . mysqli_stmt_error($stmt)];
    }
}

function get_chem_level($conn, $id)
{
    $sql = "SELECT chemLevel FROM chemicals WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'STMT ERROR'];
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['chemLevel'];
        }
    } else {
        return json_encode(['error' => mysqli_stmt_error($stmt)]);
    }
}


function log_transaction($conn, $transid, $chemids, $qty, $branch, $user_id, $role, $note, $status)
{
    try {
        $logtype = '';
        switch ($status) {
            case "Finalizing":
                $logtype = 'Used';
                break;
            case "Dispatched":
                $logtype = 'Out';
                break;
            case "Completed":
                $logtype = "Return";
                break;
            default:
                throw new Exception("Invalid status for logging.");
        }

        $sql = "INSERT INTO inventory_log (trans_id, chem_id, log_type, quantity, log_date, user_id, user_role, notes, branch) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("stmt failed.");
        }

        if (count($chemids) !== count($qty)) {
            throw new Exception("Chemical and amount used count do not match.");
        }

        for ($i = 0; $i < count($chemids); $i++) {
            mysqli_stmt_bind_param($stmt, 'iisdissi', $transid, $chemids[$i], $logtype, $qty[$i], $user_id, $role, $note, $branch);
            mysqli_stmt_execute($stmt);
            if (!mysqli_stmt_affected_rows($stmt) > 0) {
                throw new Exception("Failed inserting chemical ID " . $chemids[$i]);
            }
        }
        return true;
    } catch (Exception $e) {
        return [
            'error' => $e->getMessage() . ' at line ' . $e->getLine() . ' at file ' . $e->getFile()
        ];
    }
}

function newTransaction($conn, $customerName, $address, $technicianIds, $treatmentDate, $treatmentTime, $treatment, $chemUsed, $status, $pestProblem, $package, $type, $session, $note, $pstart, $pend, $addedby, $amtUsed, $user_id = 0, $user_role = '', $branch = 1)
{

    mysqli_begin_transaction($conn);
    try {
        $transSql = "INSERT INTO transactions (customer_name, customer_address, treatment_date, transaction_time, treatment, transaction_status, created_at, updated_at, package_id, treatment_type, session_no, notes, pack_start, pack_exp, created_by, branch) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, ?);";
        $transStmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($transStmt, $transSql)) {
            throw new Exception('Stmt Failed: ' . mysqli_stmt_error($transStmt));
        }
        mysqli_stmt_bind_param($transStmt, 'ssssssisissssi', $customerName, $address, $treatmentDate, $treatmentTime, $treatment, $status, $package, $type, $session, $note, $pstart, $pend, $addedby, $branch);
        mysqli_stmt_execute($transStmt);

        if (mysqli_stmt_affected_rows($transStmt) > 0) {
            mysqli_stmt_close($transStmt);
            $transId = mysqli_insert_id($conn);


            // log changes/addition
            // log_transaction_changes() . . . 
            // log chem usage
            if ($status === 'Dispatched' || $status === 'Finalizing' || $status === 'Completed') {
                $logchems = log_transaction($conn, $transId, $chemUsed, $amtUsed, $branch, $user_id, $user_role, $note, $status);
                if (isset($logchems['error'])) {
                    throw new Exception($logchems['error']);
                }
            }

            // set amt used to 0
            if ($status === "Pending" || $status === "Accepted") {
                for ($i = 0; $i < count($chemUsed); $i++) {
                    $amtUsed[$i] = 0;
                }
            }
            // ONLY records chem and amount used for the transaction
            for ($i = 0; $i < count($chemUsed); $i++) {
                $level = get_chem_level($conn, $chemUsed[$i]);
                if ($amtUsed[$i] > $level) {
                    throw new Exception('Insufficient Chemical');
                }
                $addChemFunc = add_chemical_used($conn, $transId, $chemUsed[$i], abs($amtUsed[$i]));
                if (!$addChemFunc) {
                    error_log("Insert failed at iteration $i");
                    throw new Exception('The chemical used addition failed: ' . $chemUsed[$i] . ' ' . mysqli_error($conn));
                }
            }

            // reflect only if completed or dispatched - finalizing is going to report the used. Not confirmed.
            $op = '';
            if ($status === 'Completed' || $status === 'Dispatched') {
                switch ($status) {
                    case 'Completed':
                        $op = 1;
                        break;
                    case 'Dispatched':
                        $op = -1;
                        break;
                    default:
                        $op = 0;
                        break;
                }

                for ($i = 0; $i < count($chemUsed); $i++) {
                    $amtUsed[$i] *= $op;
                    $reflect = reflect_trans_chem($conn, $amtUsed[$i], $chemUsed[$i]);
                    if (isset($reflect['error'])) {
                        throw new Exception($reflect['error']);
                    }
                }
            }

            // insert pest prob to database
            for ($i = 0; $i < count($pestProblem); $i++) {
                $addPestProb = add_pest_prob($conn, $transId, $pestProblem[$i]);
                if (!$addPestProb) {
                    throw new Exception("Adding pest problem failed: " . $pestProblem[$i] . ' ' . mysqli_error($conn));
                }
            }
            // insert technicians to database
            for ($i = 0; $i < count($technicianIds); $i++) {
                $addTech = add_tech_trans($conn, $transId, $technicianIds[$i]);
                if (!$addTech) {
                    throw new Exception('Addition of technicians failed: ' . $technicianIds[$i] . ' ' . mysqli_error($conn));
                }
            }


            mysqli_commit($conn);
            return true;
        } else {
            throw new Exception('Insertion Failed.');
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'errorMessage' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'stringTrace' => $e->getTraceAsString(),
            'error' => $e->getMessage() . ' at line ' . $e->getLine() . ' at file ' . $e->getFile()
        ];
    }
}

function get_package_treatment($conn, $packageid)
{
    if (!ctype_digit($packageid)) {
        return ['error' => 'Invalid id'];
    }

    $sql = "SELECT treatment FROM packages WHERE id = $packageid;";
    $res = mysqli_query($conn, $sql);
    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            return $row['treatment'];
        }
    } else {
        return ['error' => 'Data not in database.'];
    }
}

function get_existing($conn, $target_id, $table, $trans_id)
{
    $sql = "SELECT $target_id FROM $table WHERE trans_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        throw new Exception("check insertion failed on table $table.");
    }

    mysqli_stmt_bind_param($stmt, 'i', $trans_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rowRes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rowRes[] = $row[$target_id];
    }

    return $rowRes;
}

function delete_old_ids($conn, $table, $trans_id, $target_id, $ids)
{
    try {
        $sql = "DELETE FROM $table WHERE trans_id = ? AND $target_id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("deletion of old id stmt error at table $table");
        }

        for ($i = 0; $i < count($ids); $i++) {
            mysqli_stmt_bind_param($stmt, 'ii', $trans_id, $ids[$i]);
            mysqli_stmt_execute($stmt);
        }

        mysqli_stmt_close($stmt);
        return true;
    } catch (Exception $e) {
        return [
            'error' => $e->getMessage()
        ];
    }
}


function get_amt_used($conn, $chemid, $transid)
{
    if (!is_numeric($chemid) || !is_numeric($transid)) {
        return ['error' => 'Invalid ID.' . $chemid . ' ' . $transid];
    }
    $sql = "SELECT amt_used FROM transaction_chemicals WHERE chem_id = ? AND trans_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'get_amt_used() stmt failed'];
    }
    mysqli_stmt_bind_param($stmt, 'ii', $chemid, $transid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        return ['error' => "result error" . mysqli_stmt_error($stmt)];
    }
    if (mysqli_num_rows($result) > 0) {
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['amt_used'];
        }
    } else {
        return ['error' => 'No row returned from transaction.' . " chem id = $chemid | trans id = $transid"];
    }
}

function revert_v2($conn, $chemids, $transid)
{
    $updatesql = "UPDATE chemicals SET chemLevel = chemLevel + ? WHERE id = ?;";
    $updatestmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($updatestmt, $updatesql)) {
        return ['error' => 'Revert chemical stmt failed.'];
    }
    $result = 0;
    foreach ($chemids as $key => $chem) {
        $amttorevert = get_amt_used($conn, $chem, $transid);
        mysqli_stmt_bind_param($updatestmt, 'ii', $amttorevert, $chem);
        mysqli_stmt_execute($updatestmt);

        if (mysqli_stmt_affected_rows($updatestmt) > 0) {
            $result += mysqli_stmt_affected_rows($updatestmt);
        } else {
            return ['error' => 'Revert Failed.' . mysqli_stmt_error($updatestmt)];
        }
    }
    return $result;
}

function update_trans_chem_revert($conn, $id, $trans_id)
{
    $transamt = get_amt_used($conn, $id, $trans_id);
    if (isset($transamt['error'])) {
        return $transamt['error'];
    }
    // return ['error' => "oamt = $transamt"];

    $updatesql = "UPDATE chemicals SET chemLevel = chemLevel + ? WHERE id = ?;";
    $updatestmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($updatestmt, $updatesql)) {
        return ['error' => 'Revert chemical stmt failed.'];
    }

    mysqli_stmt_bind_param($updatestmt, 'ii', $transamt, $id);
    mysqli_stmt_execute($updatestmt);

    if (mysqli_stmt_affected_rows($updatestmt) > 0) {
        return 'tite';
    } else {
        return ['error' => 'Revert Failed.' . mysqli_stmt_error($updatestmt)];
    }
}

function prev_trans_amt($conn, $id, $trans_id)
{
    $sql = "SELECT amt_used FROM transaction_chemicals WHERE chem_id = ? AND trans_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'Fetching previous transaction chemical amount failed. Chemical ID: ' . $id];
    }

    mysqli_stmt_bind_param($stmt, 'ii', $id, $trans_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['amt_used'];
        }
        return null;
    } else {
        // return ['error' => '[prev_trans_amt()] - No rows returned from fetching previous amount. Chemical ID: ' . $id];
        return false;
    }
}

function reflect_trans_chem($conn, $chem, $id)
{
    $sql = "UPDATE chemicals SET chemLevel = chemLevel + ? WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'Stmt failed. Error: ' . mysqli_stmt_error($stmt)];
    }

    mysqli_stmt_bind_param($stmt, 'ii', $chem, $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return true;
    } else {
        return ['error' => 'Chemical update failed. Error: ' . mysqli_stmt_error($stmt) . 'Params passed: ' . $chem . ' ' . $id];
    }
}

function update_transaction($conn, $transData, $technicianIds, $chemUsed, $amtUsed, $pestProblem)
{
    mysqli_begin_transaction($conn);
    try {

        // if ($transData['status'] != 'Pending') {
        //     if (empty($pestProblem)) {
        //         throw new Exception("Transaction should be pending if Pest Problem is not indicated.");
        //     }

        //     if (in_array('#', $technicianIds) || empty($technicianIds)) {
        //         throw new Exception("Transaction should be pending if there is no assigned technicians.");
        //     }

        //     if (in_array('#', $chemUsed) || empty($chemUsed)) {
        //         throw new Exception("Transaction should be pending if there is no used chemicals.");
        //     }
        // }

        $existingChems = get_existing($conn, 'chem_id', 'transaction_chemicals', $transData['transId']);
        if ($transData['status'] === 'Dispatched' || $transData['status'] === 'Completed') {
            // get transaction chemicals previous amount used
            // throw new Exception(json_encode($amtUsed));
            for ($i = 0; $i < count($chemUsed); $i++) {

                // $amt_used = $amtUsed[$i];
                if ($amtUsed[$i] === '') {
                    throw new Exception("Amount used should not be empty.");
                }
                // compare new data to old
                if (in_array($chemUsed[$i], $existingChems)) {
                    // get previous amount and set it to var $prevtransamt
                    $prevtransamt = prev_trans_amt($conn, $chemUsed[$i], $transData['transId']);
                    if (isset($prevtransamt['error'])) {
                        throw new Exception("Error: " . $prevtransamt['error'] . $chemUsed[$i] . json_encode($existingChems));
                    }
                    // we need to get the old amount used
                    // throw new Exception($chemUsed[$i] . json_encode($existingChems) . $amtUsed[$i] . $prevtransamt);
                    // check if the value is the same
                    if ($amtUsed[$i] == $prevtransamt) {
                        // // skip whole ass loop if value is the same (same value = no changes)
                        continue;
                    }
                } else {
                    // doesn't exist = new chemical -> skip -> no need to fetch previous and calculate.
                    // straight to check and reflect amount used.

                    $chemlevel = get_chem_level($conn, $chemUsed[$i]);
                    if ($amtUsed[$i] > $chemlevel) {
                        $chemname = get_chemical_name($conn, $chemUsed[$i]);
                        throw new Exception("Insufficient Chemical: " . $chemname);
                    } else {
                        $amount = (int) $amtUsed[$i];
                        $amount = !in_array((int) $amtUsed[$i], $existingChems) ? $amount * -1 : $amount;
                        // throw new Exception($amount);

                        $reflect = reflect_trans_chem($conn, $amount, $chemUsed[$i]);
                        if (isset($reflect['error'])) {
                            throw new Exception("New chemical error: " . $reflect['error']);
                        }
                    }
                    continue;
                }

                // get level of the current chemical
                $chemlevel = get_chem_level($conn, $chemUsed[$i]);

                if ($amtUsed[$i] > $chemlevel) {
                    $chemname = get_chemical_name($conn, $chemUsed[$i]);
                    throw new Exception("Insufficient Chemical:  " . $chemname);
                }

                if ($amtUsed[$i] <= 0 || $amtUsed == null) {
                    throw new Exception("Remove Chemicals without value.");
                }
                $difference = $prevtransamt - $amtUsed[$i];
                $reflect = reflect_trans_chem($conn, $difference, $chemUsed[$i]);

                if (isset($reflect['error'])) {
                    throw new Exception("Modify error: " . $reflect['error'] . 'prev amt: ' . $prevtransamt . ' Amount Used: ' . $amtUsed[$i] . json_encode($amtUsed));
                }
            }
        }


        // get existing arrays to check
        $existingTechs = get_existing($conn, 'tech_id', 'transaction_technicians', $transData['transId']);
        $existingProblems = get_existing($conn, 'problem_id', 'transaction_problems', $transData['transId']);

        // check removed chem, problem, item basta yon (function returns different array values)
        $delTechs = array_diff($existingTechs, $technicianIds);
        $delChems = array_diff($existingChems, $chemUsed);
        $delProblems = array_diff($existingProblems, $pestProblem);

        // set array to delete old removed data -- technician, chemical used, and pest problems
        if (!empty($delTechs)) {
            $delete = delete_old_ids($conn, 'transaction_technicians', $transData['transId'], 'tech_id', $delTechs);
            if (isset($delete['error'])) {
                throw new Exception("Error: " . $delete['error']);
            }
            $fTechs = array_merge($technicianIds, array_diff($existingTechs, $delTechs));
            $fTechs = array_unique($fTechs);
        } else {
            $fTechs = array_merge($existingTechs, $technicianIds);
            $fTechs = array_values(array_unique($fTechs));
        }

        // deletes chemicals that aren't at the new transaction
        if (!empty($delChems)) {
            // delete old id record from transaction_chemicals 
            // 'old id record'
            $delete = delete_old_ids($conn, 'transaction_chemicals', $transData['transId'], 'chem_id', $delChems);
            if (isset($delete['error'])) {
                throw new Exception('Error: ' . $delete['error']);
            }

            // if it has a recorded amt used, revert.
            if ($transData['status'] === 'Completed' || $transData['status'] === 'Dispatched') {
                // reverts them especially if it is reflected. only completed and dispatched are the one reflected
                $revert = revert_v2($conn, $delChems, $transData['transId']);
                if (isset($revert['error'])) {
                    throw new Exception("Error: " . $revert['error'] . " 864: Current Del Chem ID: $delChems | Transaction ID: " . $transData['transId']);
                }
            }
            $fchems = array_merge($chemUsed, array_diff($existingChems, $delChems));
            $fchems = array_unique($fchems);
        } else {
            $fchems = array_merge($existingChems, $chemUsed);
            $fchems = array_values(array_unique($fchems));
        }


        if (!empty($delProblems)) {
            $delete = delete_old_ids($conn, 'transaction_problems', $transData['transId'], 'problem_id', $delProblems);
            if (isset($delete['error'])) {
                throw new Exception('Error: ' . $delete['error']);
            }
            $fprob = array_merge($pestProblem, array_diff($existingProblems, $delProblems));
            $fprob = array_unique($fprob);
        } else {
            $fprob = array_merge($existingProblems, $pestProblem);
            $fprob = array_values(array_unique($fprob));
        }


        $transSql = "UPDATE transactions SET treatment_date = ?, customer_name = ?, 
        treatment = ?, transaction_status = ?, customer_address = ?, transaction_time = ?, 
        treatment_type = ?, package_id = ?, pack_start = ?, pack_exp = ?, session_no = ?, notes = ?, updated_by = ?, branch = ? WHERE id = ?;";
        $transStmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($transStmt, $transSql)) {
            throw new Exception('Insertion Failed.');
        }

        mysqli_stmt_bind_param(
            $transStmt,
            'sssssssississii',
            $transData['treatmentDate'],
            $transData['customer'],
            $transData['treatment'],
            $transData['status'],
            $transData['address'],
            $transData['treatmentTime'],
            $transData['ttype'],
            $transData['package'],
            $transData['pstart'],
            $transData['pexp'],
            $transData['session'],
            $transData['note'],
            $transData['upby'],
            $transData['branch'],
            $transData['transId']
        );
        // throw new Exception($transData['treatment']);
        mysqli_stmt_execute($transStmt);

        $result = mysqli_stmt_affected_rows($transStmt);



        $techAR = 0;
        foreach ($fTechs as $tech) {
            $techInfo = get_tech_name($conn, $tech);
            if (!$techInfo) {
                throw new Exception("failed to fetch tech name" . $tech);
            }
            $techSql = "INSERT INTO transaction_technicians (trans_id, tech_id, tech_info) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE tech_info = VALUES(tech_info);";
            $techStmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($techStmt, $techSql)) {
                throw new Exception('Tech stmt failed.');
            }
            mysqli_stmt_bind_param($techStmt, 'iis', $transData['transId'], $tech, $techInfo);
            mysqli_stmt_execute($techStmt);
            $techAR += mysqli_stmt_affected_rows($techStmt);
        }

        $chemAR = 0;

        if ($transData['status'] === 'Pending' || $transData['status'] === 'Accepted') {
            $amtUsed = [];
            for ($i = 0; $i < count($chemUsed); $i++) {
                $amtUsed[$i] = 0;
            }
        }

        // throw new Exception(var_dump($chemUsed) . var_dump($amtUsed));

        if (count($chemUsed) === count($amtUsed)) {
            $chemSql = "INSERT INTO transaction_chemicals (trans_id, chem_id, chem_brand, amt_used) VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE chem_brand = VALUES(chem_brand), amt_used = VALUES(amt_used);";
            $chemStmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($chemStmt, $chemSql)) {
                throw new Exception("chem stmt failed");
            }
            for ($i = 0; $i < count($chemUsed); $i++) {
                $oamt = prev_trans_amt($conn, $chemUsed[$i], $transData['transId']);
                // throw new Exception(var_dump($oamt));
                if ($oamt) {
                    if ((int) $amtUsed[$i] === $oamt) {
                        continue;
                    }
                }
                $chemNames = get_chemical_name($conn, $chemUsed[$i]);
                if (!$chemNames) {
                    throw new Exception('failed to fetch chemical name');
                }
                mysqli_stmt_bind_param($chemStmt, 'iisi', $transData['transId'], $chemUsed[$i], $chemNames, $amtUsed[$i]);
                mysqli_stmt_execute($chemStmt);
                $chemAR += mysqli_stmt_affected_rows($chemStmt);
                if (!mysqli_stmt_affected_rows($chemStmt) > 0) {
                    throw new Exception("Update failed. $chemNames " . $amtUsed[$i] . mysqli_stmt_error($chemStmt));
                }
            }
        } else {
            throw new Exception('chemical count error.');
        }

        $pestAR = 0;
        $pestSql = "INSERT INTO transaction_problems (trans_id, problem_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE trans_id = VALUES(trans_id), problem_id = VALUES(problem_id);";
        $pestStmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($pestStmt, $pestSql)) {
            throw new Exception("pest problems stmt failed.");
        }
        foreach ($pestProblem as $prob) {
            mysqli_stmt_bind_param($pestStmt, 'ii', $transData['transId'], $prob);
            mysqli_stmt_execute($pestStmt);
            $pestAR += mysqli_stmt_affected_rows($pestStmt);
        }

        // log dispatched and completed -- for chemical movement\
        // planning for some changes
        // dispatched -> logtype = out
        // finalizing -> logtype = used
        // completed -> logtype = return
        // if completed, check for logtypes with same transaction
        if ($transData['status'] === 'Dispatched' || $transData['status'] === 'Completed' || $transData['status'] === 'Finalizing') {
            $log = log_transaction($conn, $transData['transId'], $chemUsed, $amtUsed, $transData['branch'], $transData['userid'], $transData['role'], $transData['note'], $transData['status']);
            if (isset($log['error'])) {
                throw new Exception($log['error']);
            }
        }


        // mysqli_rollback($conn);
        mysqli_commit($conn);
        return [
            'success' => 'no error',
            'dataPassed' => json_encode($transData),
            'trans' => $result,
            'tech' => $techAR,
            'chem' => $chemAR,
            'pest' => $pestAR,
            'ids' => json_encode($existingTechs) . json_encode($existingChems) . json_encode($existingProblems),
            'diffs' => json_encode($delTechs) . json_encode($delChems) . json_encode($delProblems),
            'ftech' => json_encode($fTechs),
            'fchems' => json_encode($fchems),
            'fprob' => json_encode($fprob)
        ];
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'dataPassed' => $transData,
            'errorMessage' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'stringTrace' => $e->getTraceAsString()
        ];
    }
}

function get_unit($conn, $chemId)
{
    if (!is_numeric($chemId)) {
        return ['error' => 'Invalid Chemical ID'];
    }
    $sql = "SELECT quantity_unit FROM chemicals WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'Fetch chemical unit stmt failed.'];
    }
    mysqli_stmt_bind_param($stmt, 'i', $chemId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['quantity_unit'];
    } else {
        return ['error' => 'No chemical found with that ID.'];
    }
}

function request_void($conn, $id, $author)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "UPDATE transactions SET void_request = 1, updated_by = ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('Stmt Failed: ' . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_bind_param($stmt, 'si', $author, $id);
        mysqli_stmt_execute($stmt);

        if (!mysqli_stmt_affected_rows($stmt) > 0) {
            throw new Exception('There was an error requesting void. Please try again later.');
        }
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage() . ' at line ' . $e->getLine() . ' in file ' . $e->getFile(),
            'id' => $id
        ];
    }
}

function void_transaction($conn, $id)
{
    $questionmarks = array_fill(0, count($id), '?');
    try {
        $sql = "UPDATE transactions SET void_request = 0, transaction_status = 'Voided' WHERE id IN (" . implode(',', $questionmarks) . ");";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('Stmt Failed' . mysqli_stmt_error($stmt));
        }

        $ints = str_repeat('i', count($id));
        mysqli_stmt_bind_param($stmt, $ints, ...$id);
        mysqli_stmt_execute($stmt);

        if (0 < mysqli_stmt_affected_rows($stmt)) {
            return [
                'success' => 'Transaction Voided.'
            ];
        } else {
            throw new Exception('Void Failed. Contact Administration.');
        }
    } catch (Exception $e) {
        return [
            'msg' => $e->getMessage(),
            'id' => json_encode($id) . ' ' . json_encode($questionmarks)
        ];
    }
}

function approve_stock($conn, $id)
{
    $questionmarks = array_fill(0, count($id), '?');
    try {
        $sql = "UPDATE chemicals SET request = 0 WHERE id IN (" . implode(',', $questionmarks) . ");";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('Stmt Failed' . mysqli_stmt_error($stmt));
        }

        $ints = str_repeat('i', count($id));
        mysqli_stmt_bind_param($stmt, $ints, ...$id);
        mysqli_stmt_execute($stmt);

        if (0 < mysqli_stmt_affected_rows($stmt)) {
            return ['success' => 'no error'];
        } else {
            throw new Exception(mysqli_stmt_error($stmt));
        }
    } catch (Exception $e) {
        return [
            'msg' => $e->getMessage(),
            'ids' => 'question marks: ' . json_encode($questionmarks) . 'ids: ' . json_encode($id)
        ];
    }
}

function check_status($conn, $id)
{
    $sql = "SELECT transaction_status FROM transactions WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'stmt failed [check_status]' . $id];
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['transaction_status'];
        }
    } else {
        return false;
    }
}
function check_approve($conn, $id)
{
    $sql = "SELECT 1 FROM transactions WHERE (treatment_date IS NULL OR customer_name IS NULL OR transaction_time IS NULL) AND id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'stmt failed [check_approve]' . $id];
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        return false;
    } else {
        return true;
    }
}

function check_normalized_data($conn, $id)
{
    $sql = "SELECT 1
    FROM transactions AS trs
    WHERE (NOT EXISTS
    (SELECT trans_id FROM transaction_technicians AS tt WHERE tt.trans_id = trs.id)
    OR NOT EXISTS
    (SELECT trans_id FROM transaction_problems AS tp WHERE tp.trans_id = trs.id)
    OR NOT EXISTS
    (SELECT trans_id FROM transaction_chemicals AS tc WHERE tc.trans_id = trs.id))
    AND trs.id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'stmt failed [check_normalized_data()]' . $id];
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        return false;
    } else {
        return true;
    }
}

function approve_transaction($conn, $id, $author)
{

    mysqli_begin_transaction($conn);
    try {

        $checkTrans = check_approve($conn, $id);
        if (isset($checkTrans['error'])) {
            throw new Exception($checkTrans['error']);
        } elseif (!$checkTrans) {
            throw new Exception('Missing data. Make sure that all transaction fields are filled in.');
        }

        $checkNormalized = check_normalized_data($conn, $id);
        if (isset($checkNormalized['error'])) {
            throw new Exception($checkNormalized['error']);
        } elseif (!$checkNormalized) {
            throw new Exception("Missing data. Make sure that all transaction fields are filled in.");
        }

        $sql = "UPDATE transactions SET transaction_status = 'Accepted', updated_by = ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo 'STMT FAILED.';
            exit();
        }

        mysqli_stmt_bind_param($stmt, 'si', $author, $id);
        mysqli_stmt_execute($stmt);
        if (!mysqli_affected_rows($conn) > 0) {
            throw new Exception('Transaction acceptance failed. Please try again later.' . $author . $id);
        }
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage() . ' at line ' . $e->getLine() . ' in file ' . $e->getFile(),
            'id' => $id
        ];
    }
}

function emptyInputLogin($uidEmail, $pwd)
{
    $result;
    if (empty($uidEmail) || empty($pwd)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function loginUser($conn, $uidEmail, $pwd)
{

    $userExists = userExists($conn, $uidEmail, $uidEmail);

    if ($userExists === false) {
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $userExists['techPwd'];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        header("location: ../login.php?error=wrongpassword");
        exit();
    } elseif ($checkPwd === true) {
        session_start();
        $_SESSION["techId"] = $userExists['technicianId'];
        $_SESSION["techUsn"] = $userExists['username'];
        header("location: ../index.php");
        exit();
    }
}


function update_pwd_hash($conn, $table, $newhashedpwd, $pwdcol, $id, $idcol)
{
    mysqli_begin_transaction($conn);

    try {
        $sql = "UPDATE $table SET $pwdcol = ? WHERE $idcol = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("rehash stmt failed.");
        }

        mysqli_stmt_bind_param($stmt, 'ss', $newhashedpwd, $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            mysqli_commit($conn);
            // throw new Exception("new hash $newhashedpwd at table $table.");
            return true;
        }
        throw new Exception("Password rehash failed.");
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage() . " at line " . $e->getLine() . " at file " . $e->getFile(),
            'line' => $e->getLine(),
        ];
    }
}

function finalize_transactions($conn, $ids, $author)
{
    mysqli_begin_transaction($conn);
    try {


        $sql = "UPDATE transactions SET transaction_status = 'Completed', complete_request = 0, updated_by = ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Finalization stmt failed.");
        }

        for ($i = 0; $i < count($ids); $i++) {
            mysqli_stmt_bind_param($stmt, 'si', $author, $ids[$i]);
            mysqli_stmt_execute($stmt);
            if (mysqli_stmt_affected_rows($stmt) <= 0) {
                throw new Exception("Cannot update transaction ID: " . $ids[$i] . mysqli_stmt_error($stmt));
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_commit($conn);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage() . " at line " . $e->getLine() . " at file " . $e->getFile(),
        ];
    }
}

function loginMultiUser($conn, $uidEmail, $pwd)
{
    // sets the row from the user exists check
    $userExists = multiUserExists($conn, $uidEmail, $uidEmail);
    $alg = PASSWORD_DEFAULT;
    $opt = ['cost' => 13];

    if ($userExists === false) {
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    // technician
    if (isset($userExists['technicianId'])) {

        $pwdHashed = $userExists['techPwd'];
        $checkPwd = password_verify($pwd, $pwdHashed);
        if ($checkPwd) {

            if (password_needs_rehash($pwdHashed, $alg, $opt)) {
                $newhash = password_hash($pwd, $alg, $opt);
                if (!$updatehash = update_pwd_hash($conn, 'technician', $newhash, 'techPwd', $userExists['technicianId'], 'technicianId')) {
                    echo $updatehash['error'];
                    exit();
                }
            }

            session_start();
            $_SESSION["techId"] = $userExists['technicianId'];
            $_SESSION["techUsn"] = $userExists['username'];
            $_SESSION["firstName"] = $userExists['firstName'];
            $_SESSION["lastName"] = $userExists['lastName'];
            $_SESSION['empId'] = $userExists['techEmpId'];
            $_SESSION['branch'] = $userExists['user_branch'];
            $_SESSION['user_role'] = "technician";
            header("location: ../technician/index.php?tech_login=success");
            exit();
        } else {
            header("location: ../login.php?error=wrongpassword");
            exit();
        }

        // branch admin - operations supervisor
    } elseif (isset($userExists['baID'])) {

        $pwdHashed = $userExists['baPwd'];
        $checkPwd = password_verify($pwd, $pwdHashed);

        if ($checkPwd) {

            if (password_needs_rehash($pwdHashed, $alg, $opt)) {
                $newhash = password_hash($pwd, $alg, $opt);
                if (!$updatehash = update_pwd_hash($conn, 'branchadmin', $newhash, 'baPwd', $userExists['baID'], 'baID')) {
                    echo $updatehash['error'];
                    exit();
                }
            }

            session_start();
            $_SESSION["baID"] = $userExists['baID'];
            $_SESSION["fname"] = $userExists['baFName'];
            $_SESSION["lname"] = $userExists['baLName'];
            $_SESSION["baUsn"] = $userExists['baUsn'];
            $_SESSION['empId'] = $userExists['baEmpId'];
            $_SESSION['baEmail'] = $userExists['baEmail'];
            $_SESSION['branch'] = $userExists['user_branch'];
            $_SESSION['user_role'] = "branchadmin";
            header("location: ../os/index.php?os_login=success");
            exit();
        } else {
            header("location: ../login.php?error=wrongpassword");
            exit();
        }

        // super admin = manager/owner
    } elseif (isset($userExists['saID'])) {

        // $pwdHashed = $userExists['techPwd'];
        // $checkPwd = password_verify($pwd, $pwdHashed);
        $password = $userExists['saPwd'];

        if ($pwd === $password) {
            session_start();
            $_SESSION["saID"] = $userExists['saID'];
            $_SESSION["saUsn"] = $userExists['saUsn'];
            $_SESSION["fname"] = $userExists['saName'];
            $_SESSION["lname"] = $userExists['saLName'];
            $_SESSION['empId'] = $userExists['saEmpId'];
            $_SESSION['saEmail'] = $userExists['saEmail'];
            $_SESSION['branch'] = $userExists['user_branch'];
            $_SESSION['user_role'] = "superadmin";
            header("location: ../superadmin/index.php?sa_login=success");
            exit();
        } else {
            header("location: ../login.php?error=wrongpassword");
            exit();
        }
    }
}

function check_request($conn, $id)
{
    if (!is_numeric($id)) {
        echo "Invalid ID." . $id;
        exit();
    }
    $sql = "SELECT request FROM chemicals WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "check_request stmt failed.";
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            if ($row['request'] === 1) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        echo "ID missing at database.";
        exit();
    }
}

function editChem($conn, $id, $name, $brand, $level, $expDate, $dateRec, $notes, $branch, $upBy, $contsize, $contcount, $request = 0)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "UPDATE chemicals SET name = ?, brand = ?, chemLevel = ?, expiryDate = ?, notes = ?, branch = ?, updated_by = ?, date_received = ?, container_size = ?, unop_cont = ?";
        $request == 0 ? $sql .= " WHERE id = ?;" : $sql .= ", request = ? WHERE id = ?";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("edit chem stmt error");
        }

        if ($request != 0) {
            mysqli_stmt_bind_param($stmt, "sssssissiiii", $name, $brand, $level, $expDate, $notes, $branch, $upBy, $dateRec, $contsize, $contcount, $request, $id);
        } else {
            mysqli_stmt_bind_param($stmt, "sssssissiii", $name, $brand, $level, $expDate, $notes, $branch, $upBy, $dateRec, $contsize, $contcount, $id);
        }

        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            mysqli_commit($conn);
            return true;
        } else {
            throw new Exception("Error. No changes are made.");
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ];
    }
}

function activeUser()
{
    $activeUser;
    if (isset($_SESSION["techId"])) {
        $activeUser = $_SESSION["techUsn"];
        return $activeUser;
    } elseif (isset($_SESSION['saID'])) {
        $activeUser = $_SESSION['saUsn'];
        return $activeUser;
    } elseif (isset($_SESSION['baID'])) {
        $activeUser = $_SESSION['baUsn'];
        return $activeUser;
    } else {
        echo 'error user';
    }
}

function validate($conn, $password)
{

    $managerID = $_SESSION['saID'];
    $stmt = mysqli_stmt_init($conn);
    $sql = "SELECT * FROM superadmin WHERE saID = ?;";
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../superadmin/tech.acc.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $managerID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $saPwd = $row['saPwd'];
    // $verifiedPwd = password_verify($password, $saPwd);
    if ($saPwd === $password) {
        return true;
        // header("location: ../superadmin/tech.acc.php?error=passwordcorrect");
    } else {
        return false;
    }
}
function validateTech($conn, $password)
{

    $techID = $_SESSION['techId'];
    $stmt = mysqli_stmt_init($conn);
    $sql = "SELECT * FROM technician WHERE technicianId = ?;";
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../superadmin/tech.acc.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $techID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $techpwd = $row['techPwd'];
    $verifiedPwd = password_verify($password, $techpwd);
    if ($verifiedPwd) {
        return true;
        // header("location: ../superadmin/tech.acc.php?error=passwordcorrect");
    } else {
        return false;
    }
}
function validateOS($conn, $password)
{

    $baID = $_SESSION['baID'];
    $stmt = mysqli_stmt_init($conn);
    $sql = "SELECT * FROM branchadmin WHERE baID = ?;";
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../os/inventory.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $baID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $baPwd = $row['baPwd'];
    $verifiedPwd = password_verify($password, $baPwd);
    if ($verifiedPwd) {
        // if ($password === $baPwd) {
        return true;
        // header("location: ../superadmin/tech.acc.php?error=passwordcorrect");
    } else {
        return false;
    }
}

function deleteChem($conn, $id)
{
    $stmt = mysqli_stmt_init($conn);
    $sql = 'DELETE FROM chemicals WHERE id = ?;';
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'stmt failed';
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return true;
    } else {
        return false;
    }
    // mysqli_stmt_close($stmt);
    // exit();
}
function deleteTechAccount($conn, $id)
{
    $stmt = mysqli_stmt_init($conn);
    $sql = 'DELETE FROM technician WHERE technicianId = ?;';
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('location: ../superadmin/tech.acc.php?error=stmtfailed');
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    // $row = mysqli_fetch_assoc($result);
    if (!$result) {
        header('location: ../superadmin/tech.acc.php?error=fetchfailed');
    }
    mysqli_stmt_close($stmt);
    header('location: ../superadmin/tech.acc.php?success=accountdeleted');
    exit();
}
function deleteOSAccount($conn, $id)
{
    $stmt = mysqli_stmt_init($conn);
    $sql = 'DELETE FROM branchadmin WHERE baID = ?;';
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('location: ../superadmin/os.acc.php?error=stmtfailed');
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        header('location: ../superadmin/os.acc.php?error=fetchfailed');
    }
    mysqli_stmt_close($stmt);
    header('location: ../superadmin/os.acc.php?success =accountdeleted');
    exit();
}

function deleteTransaction($conn, $id)
{
    $sql = "DELETE FROM transactions WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        $result = ['error' => 'STMT ERROR ' . mysqli_stmt_error($stmt)];
        exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) === 0) {
        http_response_code(400);
        $result = ['error' => 'DELETE FAILED' . mysqli_stmt_error($stmt)];
    } else {
        http_response_code(200);
        $result = ['success' => 'Transaction Deleted'];
    }

    mysqli_stmt_close($stmt);
    return $result;
}


function editTechAccount($conn, $id, $firstName, $lastName, $username, $email, $pwd, $contactNo, $address, $birthdate, $empId)
{
    // $sql = "UPDATE technician SET firstName= ?, lastName= ?, username= ?, techEmail= ?, techPwd= ? WHERE technicianid = ? ;";
    $sql = "UPDATE technician SET firstName= ?, lastName= ?, username= ?, techEmail= ?, techContact = ?, techAddress = ?, techBirthdate = ?, techEmpId = ?";

    if (!empty($pwd)) {
        $sql .= ", techPwd = ? WHERE technicianId = ?;";
    } else {
        $sql .= " WHERE technicianId = ?;";
    }

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../superadmin/tech.acc.php?error=stmtfailed");
        exit();
    }

    if (!empty($pwd)) {
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "sssssssssi", $firstName, $lastName, $username, $email, $contactNo, $address, $birthdate, $empId, $hashedPwd, $id);
    } else {
        mysqli_stmt_bind_param($stmt, "ssssssssi", $firstName, $lastName, $username, $email, $contactNo, $address, $birthdate, $empId, $id);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../superadmin/tech.acc.php?error=none");
    exit();
}
function editOSAccount($conn, $id, $firstName, $lastName, $username, $email, $pwd, $contactNo, $address, $birthdate, $empId)
{
    // $sql = "UPDATE technician SET firstName= ?, lastName= ?, username= ?, techEmail= ?, techPwd= ? WHERE technicianid = ? ;";
    $sql = "UPDATE branchadmin SET baFName= ?, baLName= ?, baUsn= ?, baEmail= ?, baContact = ?, baAddress = ?, baBirthdate = ?, baEmpId = ?";

    if (!empty($pwd)) {
        $sql .= ", baPwd = ? WHERE baID = ?;";
    } else {
        $sql .= " WHERE baID = ?;";
    }

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../superadmin/os.acc.php?error=stmtfailed");
        exit();
    }

    if (!empty($pwd)) {
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "sssssssssi", $firstName, $lastName, $username, $email, $contactNo, $address, $birthdate, $empId, $hashedPwd, $id);
    } else {
        mysqli_stmt_bind_param($stmt, "ssssssssi", $firstName, $lastName, $username, $email, $contactNo, $address, $birthdate, $empId, $id);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../superadmin/os.acc.php?error=none");
    exit();
}


function addequipment($conn, $name, $description, $availability, $destination)
{

    $sql = "INSERT INTO equipments (equipment, availability, equipment_image, description) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return json_encode(['error' => 'STMT FAILED.']);
    }

    mysqli_stmt_bind_param($stmt, 'ssss', $name, $availability, $destination, $description);

    if (!mysqli_execute($stmt)) {
        return json_encode(['error' => 'exec err' . mysqli_stmt_error($stmt)]);
    }

    mysqli_stmt_close($stmt);
    return json_encode(['success' => 'Equipment Added']);
}

function update_equipment($conn, $name, $desc, $avail, $id, $path = NULL)
{
    $sql = "UPDATE equipments SET equipment = ?, availability = ?, description = ?";
    $stmt = mysqli_stmt_init($conn);

    if ($path === NULL) {
        $sql .= " WHERE id = ?;";
    } else {
        $sql .= ", equipment_image = ? WHERE id = ?;";
    }

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'STMT ERROR' . mysqli_error($conn)];
    }

    if ($path === NULL) {
        mysqli_stmt_bind_param($stmt, 'sssi', $name, $avail, $desc, $id);
        mysqli_stmt_execute($stmt);
    } else {
        mysqli_stmt_bind_param($stmt, 'ssssi', $name, $avail, $desc, $path, $id);
        mysqli_stmt_execute($stmt);
    }

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return ['success' => 'Equipment information updated.'];
    } else {
        return ['error' => mysqli_stmt_errno($stmt) . mysqli_stmt_error($stmt) . mysqli_error($conn) . mysqli_stmt_affected_rows($stmt)];
    }
}

// function ($conn, $id){
//     $sql = 
// }

function delete_equipment($conn, $id)
{
    $sql = "DELETE FROM equipments WHERE id =?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'STMT FAILED'];
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return ['success' => 'Equipment Deleted'];
    } else {
        return ['error' => mysqli_stmt_error($stmt)];
    }
}

function get_branch_details($conn, $branch_id)
{
    if (!is_numeric($branch_id)) {
        return 'invalid id';
    }

    $sql = "SELECT * FROM branches WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return 'stmt error.';
    }

    mysqli_stmt_bind_param($stmt, 'i', $branch_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            return $row;
        }
    } else {
        return 'no row returned.';
    }
}

function add_treatment($conn, $name, $branch)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "INSERT INTO treatments (t_name, branch) VALUES (?, ?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("stmt failed.");
        }

        mysqli_stmt_bind_param($stmt, 'si', $name, $branch);
        mysqli_stmt_execute($stmt);


        if (mysqli_affected_rows($conn) > 0) {
            mysqli_commit($conn);
            return true;
        } else {
            throw new Exception("Failed to add treatment " . $name);
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}

function get_branches_array($conn)
{
    $branches = [];

    $bsql = "SELECT * FROM branches;";
    $bres = mysqli_query($conn, $bsql);
    while ($row = mysqli_fetch_assoc($bres)) {
        $branches[] = $row['id'];
    }
    return $branches;
}

function get_treatment_details($conn, $id)
{
    $sql = "SELECT * FROM treatments WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'stmt failed.'];
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        return $row;
    } else {
        return ['error' => 'Treatment not found.'];
    }
}

function edit_treatment($conn, $tname, $branch, $id)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "UPDATE treatments SET t_name = ?, branch = ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('stmt failed.');
        }

        mysqli_stmt_bind_param($stmt, 'sii', $tname, $branch, $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_affected_rows($conn) > 0) {
            mysqli_commit($conn);
            return true;
        } else {
            throw new Exception('Update Failed.');
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}

function delete_treatment($conn, $ids)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "DELETE FROM treatments WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('stmt failed.');
        }
        for ($i = 0; count($ids) > $i; $i++) {
            mysqli_stmt_bind_param($stmt, 'i', $ids[$i]);
            mysqli_stmt_execute($stmt);
            if (!mysqli_stmt_affected_rows($stmt) > 0) {
                throw new Exception("Error. Failed to delete id: $ids[$i]");
            }
        }
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}

function add_problem($conn, $prob)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "INSERT INTO pest_problems (problems) VALUES (?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('stmt failed.');
        }
        for ($i = 0; count($prob) > $i; $i++) {
            mysqli_stmt_bind_param($stmt, 's', $prob[$i]);
            mysqli_stmt_execute($stmt);
            if (!mysqli_stmt_affected_rows($stmt) > 0) {
                throw new Exception("Error. Failed to add $prob[$i]");
            }
        }

        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}
function get_pest_problem_details($conn, $id)
{
    $sql = "SELECT * FROM pest_problems WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'stmt failed.'];
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        return $row;
    } else {
        return ['error' => 'Pest Problem not found.'];
    }
}

function edit_pprob($conn, $id, $prob)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "UPDATE pest_problems SET problems = ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('stmt failed.');
        }

        mysqli_stmt_bind_param($stmt, 'si', $prob, $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_affected_rows($conn) > 0) {
            mysqli_commit($conn);
            return true;
        } else {
            throw new Exception('Update Failed.');
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}

function delete_pprob($conn, $ids)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "DELETE FROM pest_problems WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('stmt failed.');
        }
        for ($i = 0; count($ids) > $i; $i++) {
            mysqli_stmt_bind_param($stmt, 'i', $ids[$i]);
            mysqli_stmt_execute($stmt);
            if (!mysqli_stmt_affected_rows($stmt) > 0) {
                throw new Exception("Error. Failed to delete id: $ids[$i]");
            }
        }
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}

function add_branch($conn, $branch, $location)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "INSERT INTO branches (name, location) VALUES (?, ?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('stmt failed.');
        }

        if (count($branch) != count($location)) {
            throw new Exception("Values missing. Branch count: " . count($branch) . ' | Location Count: ' . count($location));
        }

        for ($i = 0; count($branch) > $i; $i++) {
            mysqli_stmt_bind_param($stmt, 'ss', $branch[$i], $location[$i]);
            mysqli_stmt_execute($stmt);
            if (!mysqli_stmt_affected_rows($stmt) > 0) {
                throw new Exception("Error. Failed to add $branch[$i]");
            }
        }

        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}

function _branch_details($conn, $id)
{
    $sql = "SELECT * FROM pest_problems WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'stmt failed.'];
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        return $row;
    } else {
        return ['error' => 'Pest Problem not found.'];
    }
}

function edit_branch($conn, $id, $branch, $location)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "UPDATE branches SET name = ?, location = ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('stmt failed.');
        }

        mysqli_stmt_bind_param($stmt, 'ssi', $branch, $location, $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_affected_rows($conn) > 0) {
            mysqli_commit($conn);
            return true;
        } else {
            throw new Exception('Update Failed.');
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}


function delete_branch($conn, $ids)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "DELETE FROM branches WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('stmt failed.');
        }
        for ($i = 0; count($ids) > $i; $i++) {
            mysqli_stmt_bind_param($stmt, 'i', $ids[$i]);
            mysqli_stmt_execute($stmt);
            if (!mysqli_stmt_affected_rows($stmt) > 0) {
                throw new Exception("Error. Failed to delete id: $ids[$i]");
            }
        }
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}

function add_package($conn, $name, $session, $warranty, $branch, $treatment)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "INSERT INTO packages (name, session_count, year_warranty, treatment, branch) VALUES (?, ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("stmt failed.");
        }

        if (
            count($name) !== count($session) ||
            count($session) !== count($warranty) ||
            count($warranty) !== count($branch) ||
            count($branch) !== count($treatment)
        ) {
            throw new Exception("Error. Values missing.");
        }

        for ($i = 0; $i < count($name); $i++) {
            mysqli_stmt_bind_param($stmt, 'siiii', $name[$i], $session[$i], $warranty[$i], $treatment[$i], $branch[$i]);
            mysqli_stmt_execute($stmt);
            if (!mysqli_stmt_affected_rows($stmt) > 0) {
                throw new Exception("Error. Failed to add $name[$i]");
            }
        }
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}

function get_package_details($conn, $id)
{
    if (!is_numeric($id)) {
        return ['error' => 'Invalid ID'];
    }

    $sql = "SELECT * FROM packages WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'stmt failed.'];
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $details = [];
    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            // $details['id'] = $row['id'];
            // $details['name'] = $row['name'];
            // $details['session'] = $row['session_count'];
            // $details['warranty'] = $row['year_warranty'];
            // $treatment = get_treatment_details($conn, $row['treatment']);
            // $details['treatment'] = $treatment['t_name'];
            // $b = get_branch_details($conn, $row['branch']);
            // $details['branch'] = $b['name'];

            return $row;
        }
    } else {
        return ['error' => 'No data found'];
    }
}

function check_branch_id($conn, $id)
{
    $sql = "SELECT * FROM branches;";
    $res = mysqli_query($conn, $sql);

    $ids = [];
    if (mysqli_num_rows($res) > 0) {
        while ($r = mysqli_fetch_assoc($res)) {
            $ids[] = $r['id'];
        }
        if (in_array($id, $ids)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function edit_package($conn, $id, $branch, $name, $warranty, $session, $treatment)
{

    mysqli_begin_transaction($conn);
    try {
        $sql = "UPDATE packages SET name = ?, session_count = ?, year_warranty = ?, treatment = ?, branch = ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("stmt failed.");
        }

        mysqli_stmt_bind_param($stmt, 'siiiii', $name, $session, $warranty, $treatment, $branch, $id);
        mysqli_stmt_execute($stmt);
        if (mysqli_affected_rows($conn) > 0) {
            mysqli_commit($conn);
            return true;
        } else {
            throw new Exception("Update failed.");
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}



function delete_package($conn, $ids)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "DELETE FROM packages WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('stmt failed.');
        }
        for ($i = 0; count($ids) > $i; $i++) {
            mysqli_stmt_bind_param($stmt, 'i', $ids[$i]);
            mysqli_stmt_execute($stmt);
            if (!mysqli_stmt_affected_rows($stmt) > 0) {
                throw new Exception("Error. Failed to delete id: $ids[$i]");
            }
        }
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}

function reschedule_transaction($conn, $id, $date, $time, $author)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "UPDATE transactions SET treatment_date = ?, transaction_time = ?, transaction_status = 'Accepted', updated_by = ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("stmt failed.");
        }

        mysqli_stmt_bind_param($stmt, 'sssi', $date, $time, $author, $id);
        mysqli_stmt_execute($stmt);

        if (!mysqli_affected_rows($conn) > 0) {
            throw new Exception("Update failed.");
        }
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}

function cancel_transaction($conn, $id, $author)
{
    mysqli_begin_transaction($conn);
    try {
        $sql = "UPDATE transactions SET transaction_status = 'Cancelled', updated_by = ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("stmt failed.");
        }

        mysqli_stmt_bind_param($stmt, 'si', $author, $id);
        mysqli_stmt_execute($stmt);

        if (!mysqli_affected_rows($conn) > 0) {
            throw new Exception("Update failed.");
        }
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}

function get_chem_capacity($conn, $id)
{
    $sql = "SELECT container_size FROM chemicals WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'stmt failed.'];
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['container_size'];
    } else {
        return ['error' => 'Chemical not found.'];
    }
}
function get_chem_containercount($conn, $id)
{
    $sql = "SELECT unop_cont FROM chemicals WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'stmt failed.'];
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['unop_cont'];
    } else {
        return ['error' => 'Chemical not found.'];
    }
}

function reflect_chem_log($conn, $chemid, $qty, $containercount)
{

    mysqli_begin_transaction($conn);
    try {
        $sql = "UPDATE chemicals SET chemLevel = chemLevel + ?";
        $datatypes = "di";
        $data[] = $qty;

        $current = get_chem_level($conn, $chemid);
        if ($containercount !== 0) {
            if ($qty > $current) {
                $qty = 0;
            }
            $sql .= ", unop_cont = unop_cont + ?";
            $datatypes .= "i";
            $data[] = $containercount;
        } else {
            if ($qty > $current) {
                throw new Exception("You cannot add a chemical more than its full capacity.");
            }
        }
        $data[] = $chemid;
        $sql .= " WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("Reflecting chemical log stmt failed.");
        }

        mysqli_stmt_bind_param($stmt, $datatypes, ...$data);
        mysqli_stmt_execute($stmt);

        if (!mysqli_affected_rows($conn) > 0) {
            throw new Exception("Chemical reflection failed.");
        }

        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage() . ' at line ' . $e->getLine() . ' on file ' . $e->getFile()
        ];
    }
}



function adjust_chemical($conn, $chemid, $logtype, $containerCount, $qty, $notes, $user_id, $user_role, $branch)
{
    mysqli_begin_transaction($conn);
    try {
        $qtytoreflect = $containerCount != 0 ? 0 : $qty;

        $reflect = reflect_chem_log($conn, $chemid, $qtytoreflect, $containerCount);
        if (isset($reflect['error'])) {
            throw new Exception($reflect['error']);
        }


        $sql = "INSERT INTO inventory_log (chem_id, log_type, quantity, log_date, user_id, user_role, notes, branch) 
    VALUES (?, ?, ?, NOW(), ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            return ['error' => 'stmt failed.'];
        }

        mysqli_stmt_bind_param($stmt, "ssdssss", $chemid, $logtype, $qty, $user_id, $user_role, $notes, $branch);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to insert log");
        }

        mysqli_commit($conn);
        mysqli_stmt_close($stmt);

        return ['success' => true];
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage() . ' at line ' . $e->getLine() . ' on file ' . $e->getFile()
        ];
    }
}


function get_user($conn, $userid, $role)
{
    // $datacol = [];   
    $usertypes = $GLOBALS['userTypes'];
    foreach ($usertypes as $roles => $columns) {
        if ($columns['table'] === $role) {
            $datacol = $columns;
            break;
        }
    }
    $idcol = $datacol['id'];
    $table = $datacol['table'];
    $usn = $datacol['usn'];
    $sql = "SELECT $usn FROM $table WHERE $idcol = $userid;";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $usnn = $row[$usn];
        return $usnn;
    } else {
        return false;
    }
}

function get_chemical($conn, $id)
{
    $sql = "SELECT * FROM chemicals WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return ['error' => 'stmt failed.'];
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    } else {
        return ['error' => 'Chemical not found.'];
    }
}

function get_branch($conn, $transid)
{
}

function finalize_trans($conn, $transid, $chemUsed, $amtUsed, $branch, $user_id, $note, $role)
{
    mysqli_begin_transaction($conn);
    try {
        $status = check_status($conn, $transid);
        if ($status !== "Dispatched") {
            throw new Exception("Finalizing Error. Status should be dispatched.");
        }

        $status = "Finalizing";

        $sql = "UPDATE transactions SET transaction_status = ?, notes = ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("stmt failed.");
        }
        mysqli_stmt_bind_param($stmt, 'ssi', $status, $note, $transid);
        mysqli_stmt_execute($stmt);

        if (!mysqli_stmt_affected_rows($stmt) > 0) {
            throw new Exception("Failed to update transaction. Please try again later.");
        }

        $existingChems = get_existing($conn, 'chem_id', 'transaction_chemicals', $transid);
        // throw new Exception(var_dump($chemUsed) . var_dump($existingChems));
        for ($i = 0; $i < count($chemUsed); $i++) {
            // $amt_used = $amtUsed[$i];
            if ($amtUsed[$i] === '' || $amtUsed[$i] === 0) {
                throw new Exception("Amount used should not be empty or zero.");
            }

            if (in_array((int) $chemUsed[$i], $existingChems)) {
                $prevtransamt = prev_trans_amt($conn, $chemUsed[$i], $transid);
                if (isset($prevtransamt['error'])) {
                    throw new Exception("Error: " . $prevtransamt['error'] . $chemUsed[$i] . json_encode($existingChems));
                }
                if ((int) $amtUsed[$i] === $prevtransamt) {
                    continue;
                }
            }
            // new chemicals
            // $chemlevel = get_chem_level($conn, $chemUsed[$i]);
            // if ((int) $amtUsed[$i] > $chemlevel) {
            //     $chemname = get_chemical_name($conn, $chemUsed[$i]);
            //     throw new Exception("Insufficient Chemical: " . $chemname);
            // } else {
            //     $amount = (int) $amtUsed[$i];
            //     $amount = !in_array($amtUsed[$i], $existingChems) ? $amount * -1 : $amount;
            //     $reflect = reflect_trans_chem($conn, $amount, $chemUsed[$i]);
            //     if (isset($reflect['error'])) {
            //         throw new Exception("New chemical error: " . $reflect['error']);
            //     }
            // }

            $recordchem = add_chemical_used($conn, $transid, $chemUsed[$i], $amtUsed[$i]);
            if (!$recordchem) {
                throw new Exception("Failed to record a chemical. Please try again later.");
            }
            continue;
        }

        // log transaction
        $logchems = log_transaction($conn, $transid, $chemUsed, $amtUsed, $branch, $user_id, $role, $note, $status);
        if (isset($logchems['error'])) {
            throw new Exception($logchems['error']);
        }

        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage() . ' at line ' . $e->getLine() . ' at line ' . $e->getFile()
        ];
    }
}
function complete_trans($conn, $transid, $chemUsed, $amtUsed, $branch, $user_id, $note, $role)
{
    mysqli_begin_transaction($conn);
    try {
        $status = check_status($conn, $transid);
        if ($status !== "Finalizing") {
            throw new Exception("Completion Error. Status should be set to Finalizing first.");
        }

        $status = "Completed";

        $sql = "UPDATE transactions SET transaction_status = ?, notes = ? WHERE id = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("stmt failed.");
        }
        mysqli_stmt_bind_param($stmt, 'ssi', $status, $note, $transid);
        mysqli_stmt_execute($stmt);

        if (!mysqli_stmt_affected_rows($stmt) > 0) {
            throw new Exception("Failed to update transaction. Please try again later.");
        }

        $existingChems = get_existing($conn, 'chem_id', 'transaction_chemicals', $transid);
        // throw new Exception(var_dump($chemUsed) . var_dump($existingChems));
        for ($i = 0; $i < count($chemUsed); $i++) {
            // $amt_used = $amtUsed[$i];
            if ($amtUsed[$i] === '' || (int) $amtUsed[$i] === 0) {
                throw new Exception("Amount used should not be empty or zero.");
            }

            if (in_array((int) $chemUsed[$i], $existingChems)) {
                $prevtransamt = prev_trans_amt($conn, $chemUsed[$i], $transid);
                if (isset($prevtransamt['error'])) {
                    throw new Exception("Error: " . $prevtransamt['error'] . $chemUsed[$i] . json_encode($existingChems));
                }
                if ((int) $amtUsed[$i] === $prevtransamt) {
                    continue;
                }
            }
            // new chemicals
            $chemlevel = get_chem_level($conn, $chemUsed[$i]);
            if ((int) $amtUsed[$i] > $chemlevel) {
                $chemname = get_chemical_name($conn, $chemUsed[$i]);
                throw new Exception("Insufficient Chemical: " . $chemname);
            } else {
                $amount = (int) $amtUsed[$i];
                $amount = !in_array($amtUsed[$i], $existingChems) ? $amount * -1 : $amount;
                $reflect = reflect_trans_chem($conn, $amount, $chemUsed[$i]);
                if (isset($reflect['error'])) {
                    throw new Exception("New chemical error: " . $reflect['error']);
                }
            }

            $recordchem = add_chemical_used($conn, $transid, $chemUsed[$i], $amtUsed[$i]);
            if (!$recordchem) {
                throw new Exception("Failed to record a chemical. Please try again later.");
            }
            continue;
        }

        // log transaction
        $logchems = log_transaction($conn, $transid, $chemUsed, $amtUsed, $branch, $user_id, $role, $note, $status);
        if (isset($logchems['error'])) {
            throw new Exception($logchems['error']);
        }

        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'error' => $e->getMessage() . ' at line ' . $e->getLine() . ' at line ' . $e->getFile()
        ];
    }
}
