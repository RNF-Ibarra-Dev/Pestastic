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
    return false;
    mysqli_stmt_close($stmt);
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

function addChemical($conn, $name, $brand, $level, $expdate, $request = 0)
{
    if (!$request) {
        $sql = "INSERT INTO chemicals (name, brand, chemLevel, expiryDate) VALUES (?, ?, ?, ?);";
    } else {
        $sql = "INSERT INTO chemicals (name, brand, chemLevel, expiryDate, request) VALUES (?, ?, ?, ?, ?);";
    }
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Stmt Failed";
        exit();
    }

    if (!$request) {
        mysqli_stmt_bind_param($stmt, 'ssss', $name, $brand, $level, $expdate);
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

function newTransaction($conn, $customerName, $address, $technicianIds, $treatmentDate, $treatmentTime, $treatment, $chemUsed, $status, $pestProblem, $package, $type, $session, $note, $pstart, $pend)
{

    mysqli_begin_transaction($conn);
    try {
        $transSql = "INSERT INTO transactions (customer_name, customer_address, treatment_date, transaction_time, treatment, transaction_status, created_at, updated_at, package_id, treatment_type, session_no, notes, pack_start, pack_exp) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?, ?);";
        $transStmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($transStmt, $transSql)) {
            throw new Exception('Stmt Failed: ' . mysqli_stmt_error($transStmt));
        }
        mysqli_stmt_bind_param($transStmt, 'ssssssisisss', $customerName, $address, $treatmentDate, $treatmentTime, $treatment, $status, $package, $type, $session, $note, $pstart, $pend);
        mysqli_stmt_execute($transStmt);

        if (mysqli_stmt_affected_rows($transStmt) > 0) {
            mysqli_stmt_close($transStmt);
            $transId = mysqli_insert_id($conn);

            // $iterationLogs = [];
            // error_log("Total count of chemUsed: " . count($chemUsed));

            for ($i = 0; $i < count($chemUsed); $i++) {
                $addChemFunc = add_chemical_used($conn, $transId, $chemUsed[$i]);
                if (!$addChemFunc) {
                    error_log("Insert failed at iteration $i");
                    throw new Exception('The chemical used addition failed: ' . $chemUsed[$i] . ' ' . mysqli_error($conn));
                }

                // $iterationLogs[] = "iterated $i";

                // get original value
                // $level = get_chem_level($conn, $chemUsed[$i]);

                // if ($amtUsed[$i] > $level) {
                //     throw new Exception('Insufficient Chemical');
                // }

                // $update = update_chem_level($conn, $chemUsed[$i], $level, $amtUsed[$i]);

                // if (isset($update['error'])) {
                //     throw new Exception('Chemical not updated. Chem ID: ' . $chemUsed[$i] . $update['error']);
                // }
            }

            // insert pest prob to database
            for ($i = 0; $i < count($pestProblem); $i++) {
                $addPestProb = add_pest_prob($conn, $transId, $pestProblem[$i]);
                if (!$addPestProb) {
                    throw new Exception("Adding pest problem failed: " . $pestProblem[$i] . ' ' . mysqli_error($conn));
                }
            }
            // error_log("Transaction ID: " . $transId);
            // error_log("Technician IDs: " . json_encode($technicianIds));

            // insert technicians to database
            for ($i = 0; $i < count($technicianIds); $i++) {
                $addTech = add_tech_trans($conn, $transId, $technicianIds[$i]);
                if (!$addTech) {
                    throw new Exception('Addition of technicians failed: ' . $technicianIds[$i] . ' ' . mysqli_error($conn));
                }
            }
            mysqli_commit($conn);
            return [
                'success' => true,
                // 'iterate' => $iterationLogs
            ];
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

        if ($transData['status'] != 'Pending') {
            if (empty($pestProblem)) {
                throw new Exception("Transaction should be pending if Pest Problem is not indicated.");
            }

            if (in_array('#', $technicianIds) || empty($technicianIds)) {
                throw new Exception("Transaction should be pending if there is no assigned technicians.");
            }

            if (in_array('#', $chemUsed) || empty($chemUsed)) {
                throw new Exception("Transaction should be pending if there is no used chemicals.");
            }
        }

        $existingChems = get_existing($conn, 'chem_id', 'transaction_chemicals', $transData['transId']);
        if ($transData['status'] === 'Accepted' || $transData['status'] === 'Completed') {
            // get transaction chemicals previous amount used
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
                        // skip loop if value is the same
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
                        $amount = "-" . $amtUsed[$i];
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

        // set array to delete old removed data -- technician, chemical used, and 
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
            $delete = delete_old_ids($conn, 'transaction_chemicals', $transData['transId'], 'chem_id', $delChems);
            if (isset($delete['error'])) {
                throw new Exception('Error: ' . $delete['error']);
            }
            //function to revert chemical to their original value. Unless Completed.
            // foreach ($delChems as $key => $delChemss) {
            //     // throw new Exception("chem id: $delChemss transaction id: " . $transData['transId']);
            //     $prevamttorestore = prev_trans_amt($conn, $delChemss, $transData['transId']);
            //     // throw new Exception("$prevamttorestore $delChemss " . var_export($delChems));
            //     $chemId = $delChemss;
            //     $revert = update_trans_chem_revert($conn, $chemId, $transData['transId']);
            //     if (isset($revert['error'])) {
            //         throw new Exception("Error: " . $revert['error'] . " 813: Current Del Chem ID: $delChemss | Transaction ID: " . $transData['transId']);
            //     } else {
            //         throw new Exception($revert);
            //     }
            // }

            $revert = revert_v2($conn, $delChems, $transData['transId']);
            if (isset($revert['error'])) {
                throw new Exception("Error: " . $revert['error'] . " 864: Current Del Chem ID: $delChems | Transaction ID: " . $transData['transId']);
            } else {
                 // throw new Exception($revert);
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
        treatment_type = ?, package_id = ?, pack_start = ?, pack_exp = ?, session_no = ?, notes = ? WHERE id = ?;";
        $transStmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($transStmt, $transSql)) {
            throw new Exception('Insertion Failed.');
        }

        mysqli_stmt_bind_param(
            $transStmt,
            'sssssssissisi',
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
            $transData['transId'],
        );
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
        if ($transData['status'] === 'Accepted' || $transData['status'] === 'Completed') {
            if (count($chemUsed) === count($amtUsed)) {
                $chemSql = "INSERT INTO transaction_chemicals (trans_id, chem_id, chem_brand, amt_used) VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE chem_brand = VALUES(chem_brand), amt_used = VALUES(amt_used);";
                $chemStmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($chemStmt, $chemSql)) {
                    throw new Exception("chem stmt failed");
                }
                for ($i = 0; $i < count($chemUsed); $i++) {
                    $oamt = prev_trans_amt($conn, $chemUsed[$i], $transData['transId']);
                    if ($amtUsed[$i] == $oamt) {
                        continue;
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

function approve_transaction($conn, $id)
{
    $checkTrans = check_approve($conn, $id);
    if (isset($checkTrans['error'])) {
        return $checkTrans['error'];
    } elseif (!$checkTrans) {
        return 'Missing data. Make sure that all transaction fields are filled in.';
    }

    $checkNormalized = check_normalized_data($conn, $id);
    if (isset($checkNormalized['error'])) {
        return $checkNormalized['error'];
    } elseif (!$checkNormalized) {
        return "Missing data. Make sure that all transaction fields are filled in.";
    }

    $sql = "UPDATE transactions SET transaction_status = 'Accepted' WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'STMT FAILED.';
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return true;
    } else {
        return false;
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


function loginMultiUser($conn, $uidEmail, $pwd)
{
    // sets the row from the user exists check
    $userExists = multiUserExists($conn, $uidEmail, $uidEmail);

    if ($userExists === false) {
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    // technician
    if (isset($userExists['technicianId'])) {

        $pwdHashed = $userExists['techPwd'];
        $checkPwd = password_verify($pwd, $pwdHashed);

        if ($checkPwd === false) {
            header("location: ../login.php?error=wrongpassword");
            exit();
        } elseif ($checkPwd === true) {
            session_start();
            $_SESSION["techId"] = $userExists['technicianId'];
            $_SESSION["techUsn"] = $userExists['username'];
            $_SESSION["firstName"] = $userExists['firstName'];
            $_SESSION["lastName"] = $userExists['lastName'];
            header("location: ../technician/index.php?tech_login=success");
            exit();
        }

        // branch admin - operations supervisor
    } elseif (isset($userExists['baID'])) {

        // $pwdHashed = $userExists['techPwd'];
        // $checkPwd = password_verify($pwd, $pwdHashed);
        $passwood = $userExists['baPwd'];

        if ($pwd === $passwood) {
            session_start();
            $_SESSION["baID"] = $userExists['baID'];
            $_SESSION["fname"] = $userExists['baFName'];
            $_SESSION["lname"] = $userExists['baLName'];
            $_SESSION["baUsn"] = $userExists['baUsn'];
            $_SESSION['baEmail'] = $userExists['baEmail'];
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
            $_SESSION['saEmail'] = $userExists['saEmail'];
            header("location: ../superadmin/index.php?sa_login=success");
            exit();
        } else {
            header("location: ../login.php?error=wrongpassword");
            exit();
        }
    }
}

function editChem($conn, $id, $name, $brand, $level, $expDate, $request = 0)
{
    if ($request != 0) {
        $sql = "UPDATE chemicals SET name = ?, brand = ?, chemLevel = ?, expiryDate = ?, request = ? WHERE id = ?;";
    } else {
        $sql = "UPDATE chemicals SET name = ?, brand = ?, chemLevel = ?, expiryDate = ? WHERE id = ?;";
    }

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "<span class='alert alert-danger'>SQL statement error.</span>";
        exit();
    }

    if ($request != 0) {
        mysqli_stmt_bind_param($stmt, "ssssii", $name, $brand, $level, $expDate, $request, $id);
    } else {
        mysqli_stmt_bind_param($stmt, "ssssi", $name, $brand, $level, $expDate, $id);
    }
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return true;
    } else {
        return false;
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
    // $verifiedPwd = password_verify($password, $baPwd);
    // if ($verifiedPwd) {
    if ($password === $baPwd) {
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
