<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['notifications']) && $_GET['notifications'] === 'true') {
    $response = [
        'notif' => '',
        'count' => 0,
        'countbadge' => ''
    ];


    $pt = "SELECT * FROM transactions t JOIN transaction_technicians tt ON t.id = tt.trans_id WHERE tt.tech_id = {$_SESSION['techId']} AND (t.transaction_status = 'Pending' OR t.transaction_status = 'Accepted') AND t.treatment_date > CURDATE() AND void_request = 0;";
    $ptr = mysqli_query($conn, $pt);
    if ($ptr) {
        $num = mysqli_num_rows($ptr);
        if ($num > 0) {
            $ttransactions = $num == 1 ? 'transaction' : 'transactions';
            $response['notif'] .= "
            <li class='list-group-item p-0'>
                <a href='transactions.php'
                    class='nav-link btn btn-sidebar m-0 fw-light d-flex align-items-center justify-content-center'>
                    <div class='fw-medium w-100 fs-5 py-2 d-flex align-items-center justify-content-start mb-0'>
                        <i class='bi bi-clipboard-minus-fill text-body-tertiary fs-4 ms-3 fw-light account-settings-icon'></i> 
                        <div class='vr mx-5'></div>
                        <p class='text-start m-0 p-0'>You have <span class='text-danger'>$num</span>&nbsp;upcoming
                        $ttransactions.</p>
                    </div>
                </a>
            </li>";
            $response['count']++;
        }
    }

    $ft = "SELECT * FROM transactions t JOIN transaction_technicians tt ON t.id = tt.trans_id WHERE tt.tech_id = {$_SESSION['techId']} AND t.transaction_status = 'Dispatched';";
    $ftr = mysqli_query($conn, $ft);
    if ($ftr) {
        $num = mysqli_num_rows($ftr);
        if ($num > 0) {
            $ftransactions = $num == 1 ? 'transaction' : 'transactions';
            $response['notif'] .= "
            <li class='list-group-item p-0'>
                <a href='transactions.php'
                    class='nav-link btn btn-sidebar m-0 fw-light d-flex align-items-center justify-content-center'>
                    <div class='fw-medium w-100 fs-5 py-2 d-flex align-items-center justify-content-start mb-0'>
                        <i class='bi bi-clipboard-check-fill text-body-tertiary fs-4 ms-3 fw-light account-settings-icon'></i>
                        <div class='vr mx-5'></div>
                        <p class='m-0 p-0 text-start'>You have <span class='text-danger '>$num</span>&nbsp;ongoing dispatched
                            $ftransactions.</p>
                    </div>
                </a>
            </li>";
            $response['count']++;
        }
    }


    $rt = "SELECT * FROM transactions t JOIN transaction_technicians tt ON t.id = tt.trans_id WHERE tt.tech_id = {$_SESSION['techId']} AND t.transaction_status = 'cancelled' AND t.void_request = 0;";
    $rtr = mysqli_query($conn, $rt);
    if ($rtr) {
        $num = mysqli_num_rows($rtr);
        if ($num > 0) {
            $rtransactions = $num == 1 ? 'transaction' : 'transactions';
            $response['notif'] .= "
            <li class='list-group-item p-0'>
                <a href='transactions.php'
                    class='nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center'>
                    <div class='fw-medium w-100 fs-5 py-2 d-flex align-items-center justify-content-start mb-0'>
                        <i class='bi bi-calendar-minus-fill text-body-tertiary fs-4 ms-3 fw-light account-settings-icon'></i> 
                        <div class='vr mx-5'></div>
                        <p class='m-0 p-0 text-start'>You have <span class='text-danger '>$num</span>&nbsp;cancelled
                            $rtransactions that needs further review and rescheduling.</p>
                    </div>
                </a>
            </li>";
            $response['count']++;
        }
    }

    $up = "SELECT * FROM transactions t JOIN transaction_technicians tt ON t.id = tt.trans_id WHERE tt.tech_id = {$_SESSION['techId']} AND t.transaction_status = 'Accepted' AND t.treatment_date > CURDATE() AND t.void_request = 0;";
    $upr = mysqli_query($conn, $up);
    if ($upr) {
        $num = mysqli_num_rows($upr);
        if ($num > 0) {
            $msg = $num == 1 ? 'transaction' : 'transactions';
            $response['notif'] .= "
            <li class='list-group-item p-0'>
                <a href='transactions.php'
                    class='nav-link btn btn-sidebar m-0 d-flex align-items-center justify-content-center'>
                    <div class='fw-medium w-100 fs-5 py-2 d-flex align-items-center justify-content-start mb-0'>
                        <i class='bi bi-calendar2-x-fill text-body-tertiary fs-4 ms-3 fw-light account-settings-icon'></i> 
                        <div class='vr mx-5'></div>
                        <p class='m-0 p-0 text-start'>You have<span class='text-danger'>$num</span>&nbsp;$unfinished_transactions are unfinished and needs update.</p>
                    </div>
                </a>
            </li>";
            $response['count']++;
        }
    }

    $unfinished_t = "SELECT * FROM transactions t JOIN transaction_technicians tt ON t.id = tt.trans_id WHERE (t.transaction_status = 'Accepted' OR t.transaction_status = 'Dispatched') AND t.void_request = 0 AND t.treatment_date < CURDATE() AND tt.tech_id = {$_SESSION['techId']};";
    $unfinished_tr = mysqli_query($conn, $unfinished_t);
    if ($unfinished_tr) {
        $num = mysqli_num_rows($unfinished_tr);
        if ($num > 0) {
            $unfinished_transactions = $num == 1 ? 'transaction' : 'transactions';
            $response['notif'] .= "
            <li class='list-group-item p-0'>
                <a href='transactions.php'
                    class='nav-link btn btn-sidebar m-0 d-flex align-items-center justify-content-center'>
                    <div class='fw-medium w-100 fs-5 py-2 d-flex align-items-center justify-content-start mb-0'>
                        <i class='bi bi-calendar2-x-fill text-body-tertiary fs-4 ms-3 fw-light account-settings-icon'></i> 
                        <div class='vr mx-5'></div>
                        <p class='m-0 p-0 text-start'>You have <span class='text-danger '>$num</span>&nbsp;$unfinished_transactions that are unfinished and needs update.</p>
                    </div>
                </a>
            </li>";
            $response['count']++;
        }
    }

    $up = "SELECT * FROM transactions t JOIN transaction_technicians tt ON t.id = tt.trans_id WHERE t.transaction_status = 'Accepted' AND t.treatment_date > CURDATE() AND t.void_request = 0 AND tt.tech_id = {$_SESSION['techId']};";
    $upr = mysqli_query($conn, $up);
    if ($upr) {
        $num = mysqli_num_rows($upr);
        if ($num > 0) {
            $msg = $num == 1 ? 'transaction' : 'transactions';
            $response['notif'] .= "
            <li class='list-group-item p-0'>
                <a href='transactions.php'
                    class='nav-link btn btn-sidebar m-0 fw-light d-flex align-items-center justify-content-center'>
                    <div class='fw-medium w-100 fs-5 py-2 d-flex align-items-center justify-content-start mb-0'>
                        <i class='bi bi-calendar-event-fill fs-4 ms-3 text-body-tertiary fw-light account-settings-icon'></i>
                        <div class='vr mx-5'></div>
                        <p class='text-start m-0 p-0'>You have <span class='text-info'>$num</span>&nbsp;upcoming $msg waiting to be dispatched.</p>
                    </div>
                </a>
            </li>";
            $response['count']++;
        }
    }

    $outdated_dispatch_p = "SELECT * FROM transactions t JOIN transaction_technicians tt ON t.id = tt.trans_id WHERE t.transaction_status = 'Dispatched' AND t.treatment_date < CURDATE() AND t.void_request = 0 AND tt.tech_id = {$_SESSION['techId']};";
    $outdated_dispatch_pr = mysqli_query($conn, $outdated_dispatch_p);
    if ($outdated_dispatch_pr) {
        $num = mysqli_num_rows($outdated_dispatch_pr);
        if ($num > 0) {
            $msg = $num == 1 ? 'transaction' : 'transactions';
            $response['notif'] .= "
            <li class='list-group-item p-0'>
                <a href='transactions.php'
                    class='nav-link btn btn-sidebar m-0 fw-light d-flex align-items-center justify-content-center'>
                    <div class='fw-medium w-100 fs-5 d-flex align-items-center justify-content-start mb-0 py-2'>
                        <i class='bi bi-calendar-event-fill fs-4 ms-3 text-body-tertiary fw-light account-settings-icon'></i>
                        <div class='vr mx-5'></div>
                        <p class='m-0 p-0 text-start'><span class='text-danger'>$num</span> dispatched $msg were never updated.</p>
                    </div>
                </a>
            </li>";
            $response['count']++;
        }
    }

    $response['countbadge'] .= "<span class='position-absolute translate-middle badge rounded-pill bg-danger' style='top: unset !important' id='notifNum'>" . $response['count'];
    echo json_encode($response);
    exit();
}
