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
            $lchems = $num == 1 ? 'Item' : 'Items';
            $response['notif'] .=
                "<li class='list-group-item p-0'>
            <a href='itemstock.php'
            class='nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center gap'>
            <p class='fw-light mb-0'><i class='bi bi-beaker fw-light account-settings-icon'></i><span class='text-danger'>$num</span> $lchems are
            low
            in stock.</p>
            </a>
            </li>";
            $response['count']++;
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
                    class='nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center'>
                    <p class='fw-light mb-0'><i class='bi bi-clipboard-minus fw-light account-settings-icon'></i> <span class='text-danger'>$num</span> Pending
                        $ttransactions need confirmation.</p>
                </a>
            </li>";
            $response['count']++;
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
                    class='nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center'>
                    <p class='fw-light mb-0'><i class='bi bi-clipboard-check fw-light account-settings-icon'></i> <span class='text-danger'>$num</span> finalizing
                        $ftransactions need completion.</p>
                </a>
            </li>";
            $response['count']++;
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
                    <p class='fw-light mb-0'><i class='bi bi-calendar-minus fw-light account-settings-icon'></i> <span class='text-danger'>$num</span> cancelled
                        $rtransactions need further review and rescheduling.</p>
                </a>
            </li>";
            $response['count']++;
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
                    class='nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center'>
                    <p class='fw-light mb-0'><i class='bi bi-calendar2-x fw-light account-settings-icon'></i> <span class='text-danger'>$num</span>
                        $unfinished_transactions are unfinished and needs update.</p>
                </a>
            </li>";
            $response['count']++;
        }
    }

    $se = "SELECT * FROM chemicals WHERE request = 1;";
    $ser = mysqli_query($conn, $se);
    if ($ser) {
        $num = mysqli_num_rows($ser);
        if ($num > 0) {
            $centry = $num == 1 ? 'entry' : 'entries';
            $response['notif'] .= "
            <li class='list-group-item p-0'>
                <a href='itemstock.php'
                    class='nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center'>
                    <p class='fw-light mb-0'><i class='bi bi-flask-florence fw-light account-settings-icon'></i>
                        <span class='text-danger'>$num</span> Item / Chemical $centry need approval.
                    </p>
                </a>
            </li>";
            $response['count']++;
        }
    }

    $vr = "SELECT * FROM transactions WHERE void_request = 1;";
    $vrr = mysqli_query($conn, $vr);
    if ($vrr) {
        $num = mysqli_num_rows($vrr);
        if ($num > 0) {
            $trequest = $num == 1 ? 'request' : 'requests';
            $response['notif'] .= "
            <li class='list-group-item p-0'>
                <a href='transactions.php'
                    class='nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center'>
                    <p class='fw-light mb-0'><i class='bi bi-clipboard-x fw-light account-settings-icon'></i>
                        <span class='text-danger'>$num</span> Transaction void $trequest need reviewal.
                    </p>
                </a>
            </li>";
            $response['count']++;
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
                    class='nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center'>
                    <p class='fw-light mb-0'><i class='bi bi-calendar-event fw-light account-settings-icon'></i>
                        There are <span class='text-info'>$num</span> upcoming $msg waiting to be dispatched.
                    </p>
                </a>
            </li>";
            $response['count']++;
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
                    class='nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center'>
                    <p class='fw-light mb-0'><i class='bi bi-calendar-event fw-light account-settings-icon'></i>
                        <span class='text-danger'>$num</span> dispatched $msg were never updated.
                    </p>
                </a>
            </li>";
            $response['count']++;
        }
    }

    $response['countbadge'] .= "<span class='position-absolute translate-middle badge rounded-pill bg-danger' style='top: unset !important' id='notifNum'>" . $response['count'];
    echo json_encode($response);
    exit();
}
