<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['notifications']) && $_GET['notifications'] === 'true') {
    $response = [
        'notif' => '',
        'count' => 0,
        'countbadge' => ''
    ];

    $ls = "SELECT * FROM chemicals WHERE chemLevel <= (container_size * 0.35) AND CASE
                WHEN chemLevel > 0 THEN (unop_cont + 1) <= restock_threshold
                ELSE unop_cont <= restock_threshold
            END;";
    $lsr = mysqli_query($conn, $ls);
    if ($lsr) {
        $num = mysqli_num_rows($lsr);
        if ($num > 0) {
            $lchems = $num == 1 ? 'Chemical' : 'Chemicals';
            $response['notif'] .=
                "<li class='list-group-item p-0'>
            <a href='inventory.php'
            class='nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center gap'>
            <p class='fw-light mb-0'><i class='bi bi-beaker fw-light account-settings-icon'></i><span class='text-danger'>$num</span> $lchems are
            low
            in level.</p>
            </a>
            </li>";
            $response['count']++;
        }
    }
    $pt = "SELECT * FROM transactions WHERE transaction_status = 'pending';";
    $ptr = mysqli_query($conn, $pt);
    if ($ptr) {
        $num = mysqli_num_rows($ptr);
        if ($num > 0) {
            $ttransactions = $num == 1 ? 'transaction' : 'transactions';
            $response['notif'] .= "
            <li class='list-group-item p-0'>
                <a href='transactions.php'
                    class='nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center'>
                    <p class='fw-light mb-0'><i class='bi bi-clipboard-data fw-light account-settings-icon'></i> <span class='text-danger'>$num</span> Pending
                        $ttransactions needs attention.</p>
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
                        <span class='text-danger'>$num</span> Chemical $centry needs approval.
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
                        <span class='text-danger'>$num</span> Transaction void $trequest needs attention.
                    </p>
                </a>
            </li>";
            $response['count']++;
        }
    }

    $up = "SELECT * FROM transactions WHERE transaction_status = 'Accepted' AND treatment_date >= CURDATE();";
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
                        There are <span class='text-info'>$num</span> upcoming $msg.
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
