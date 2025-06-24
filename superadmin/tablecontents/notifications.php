<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['notifications']) && $_GET['notifications'] === 'true') {
    $notifs = 0;
    $ls = "SELECT * FROM chemicals WHERE chemLevel <= 10 ORDER BY chemLevel;";
    $lsr = mysqli_query($conn, $ls);
    if ($lsr) {
        $num = mysqli_num_rows($lsr);
        ?>
        <li class="list-group-item p-0">
            <a href="itemstock.php"
                class="nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center gap">
                <p class="fw-light mb-0"><i class="bi bi-beaker fw-light account-settings-icon"></i><?= $num ?> Chemicals are
                    low
                    in level.</p>
            </a>
        </li>
        <?php
        $notifs++;
    }
    $pt = "SELECT * FROM transactions WHERE transaction_status = 'pending';";
    $ptr = mysqli_query($conn, $pt);
    if ($ptr) {
        $num = mysqli_num_rows($ptr);
        ?>
        <li class="list-group-item p-0">
            <a href="transactions.php"
                class="nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center justify-content-center gap">
                <p class="fw-light mb-0"><i class="bi bi-clipboard-data fw-light account-settings-icon"></i><?= $num ?> Pending
                    <?= $num == 1 ? 'transaction' : 'transactions' ?> needs attention</p>
            </a>
        </li>
        <?php
        $notifs++;
    }
}