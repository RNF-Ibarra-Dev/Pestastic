<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['notifications']) && $_GET['notifications'] === 'true') {
    $response = [
        'notif' => '',
        'count' => 0,
        'countbadge' => ''
    ];

    $ls = "SELECT * FROM chemicals WHERE (unop_cont + (CASE WHEN chemLevel > 0 THEN 1 ELSE 0 END)) < restock_threshold AND request = 0;";
    $lsr = mysqli_query($conn, $ls);
    if ($lsr) {
        $num = mysqli_num_rows($lsr);
        if ($num > 0) {
            $lchems = $num == 1 ? 'Chemical' : 'Chemicals';
            $response['notif'] .=
                "<li class='list-group-item p-0'>
            <a href='itemstock.php'
            class='nav-link btn btn-sidebar m-0 fw-light d-flex align-items-center justify-content-center'>
                <div class='fw-medium mb-0 w-100 fs-5 d-flex align-items-center py-2 justify-content-start'>
                    <i class='bi bi-beaker-fill text-body-tertiary ms-3 fw-light account-settings-icon fs-4'></i>
                    <div class='vr mx-5'></div>
                    <p class='m-0 p-0 text-start'><span class='text-danger '>$num</span>&nbsp; $lchems are
                low
                in stock.</p></div>
            </a>
            </li>";
            $response['count'] += $num;
        }
    }
    $pt = "SELECT * FROM transactions WHERE transaction_status = 'pending' AND void_request = 0;";
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
                        <p class='text-start m-0 p-0'><span class='text-danger '>$num</span>&nbsp; pending
                        $ttransactions need confirmation.</p>
                    </div>
                </a>
            </li>";
            $response['count'] += $num;
        }
    }

    $ft = "SELECT * FROM transactions WHERE transaction_status = 'finalizing' AND void_request = 0;";
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
                        <p class='m-0 p-0 text-start'><span class='text-danger '>$num</span>&nbsp; finalizing
                            $ftransactions need completion.</p>
                    </div>
                </a>
            </li>";
            $response['count'] += $num;
        }
    }


    $rt = "SELECT * FROM transactions WHERE transaction_status = 'cancelled' AND void_request = 0;";
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
                        <p class='m-0 p-0 text-start'><span class='text-danger '>$num</span>&nbsp; cancelled
                            $rtransactions need further review and rescheduling.</p>
                    </div>
                </a>
            </li>";
            $response['count'] += $num;
        }
    }

    $up = "SELECT * FROM transactions WHERE transaction_status = 'Accepted' AND treatment_date > CURDATE() AND void_request = 0;";
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
                        <p class='m-0 p-0 text-start'><span class='text-danger '>$num</span>&nbsp;
                            $unfinished_transactions are unfinished and needs update.</p>
                    </div>
                </a>
            </li>";
            $response['count'] += $num;
        }
    }

    $unfinished_t = "SELECT * FROM transactions WHERE (transaction_status = 'Accepted' OR transaction_status = 'Dispatched') AND void_request = 0 AND treatment_date < CURDATE();";
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
                        <p class='m-0 p-0 text-start'><span class='text-danger '>$num</span>&nbsp;
                            $unfinished_transactions are unfinished and needs update.</p>
                    </div>
                </a>
            </li>";
            $response['count'] += $num;
        }
    }

    $up = "SELECT * FROM transactions WHERE transaction_status = 'Accepted' AND treatment_date > CURDATE() AND void_request = 0;";
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
                        <p class='text-start m-0 p-0'>There are <span class='text-info'>$num</span> upcoming $msg waiting to be dispatched.</p>
                    </div>
                </a>
            </li>";
            $response['count'] += $num;
        }
    }

    $outdated_dispatch_p = "SELECT * FROM transactions WHERE transaction_status = 'Dispatched' AND treatment_date < CURDATE() AND void_request = 0;";
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
            $response['count'] += $num;
        }
    }

    $response['countbadge'] .= "<span class='position-absolute translate-middle badge rounded-pill bg-danger' style='top: unset !important' id='notifNum'>" . $response['count'];
    echo json_encode($response);
    exit();
}
