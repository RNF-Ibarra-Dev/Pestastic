<?php
session_start();
require_once("../includes/dbh.inc.php");
require_once("../includes/functions.inc.php");

$sql = "SELECT MAX(id) as latest_id FROM transactions";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$latest_id = $row['latest_id'] ?? 0;

$td_sql = "SELECT * FROM transactions WHERE id = $latest_id;";
$td_res = mysqli_query($conn, $td_sql);
$trans_details = [];
if ($row = mysqli_fetch_assoc($td_res)) {
    $trans_details = $row;
}
$date_set = date('F j, Y', strtotime($trans_details['treatment_date']));
$time_set = date('H:i A', strtotime($trans_details['transaction_time']));

$package_details = '';
if ($trans_details['package_id'] !== NULL) {
    $package = get_package_details($conn, $trans_details['package_id']);
    $package_details = <<<END
            <strong>Package ID:</strong> {$trans_details['package_id']} <br>
            <strong>Package details:</strong> {$package['name']} ({$package['session_count']} sessions | {$package['year_warranty']} Year Warranty) <br>
            <strong>Session No.:</strong> {$trans_details['session_no']} <br>
    END;
}
$branch = get_branch_details($conn, $trans_details['branch']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            background: #efefefff;
        }

        .content {
            background-color: rgba(0, 0, 0, 0.05);
            color: rgba(75, 75, 75, 1);
            width: 75vw;
            margin: 0 auto;
            padding: 1rem 1.75rem;
            border: 1px #fff solid;
            border-radius: 1rem;
        }

        .wrapper {
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .header-logo {
            margin: 1.5rem 0;
        }

        .header-content {
            margin-bottom: 1.75rem;
        }

        .header-content h1 {
            margin-bottom: .75rem;
        }

        .header-content p {
            padding-left: 0.50rem;
        }

        .details-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, .25);
            border-radius: 1rem;
            width: 30%;
            margin: 0 auto;
            padding: 1rem .75rem;
        }

        .trans-details {
            margin: 0 auto;
            gap: 1rem;
        }

        .trans-details h2 {
            margin-bottom: .50rem;
        }
    </style>
</head>

<body>

    <div class="header-logo" style="text-align: center;">
        <img src="https://pestastic-inventory.site/img/logo.svg" alt="logo" style="width: 12rem !important">
    </div>
    <div class="wrapper">

        <div class="content">

            <div class="header-content">
                <h1>New Transaction Alert</h1>
                <p>This is to inform you that a new transaction was made. The new transaction ID is <?= $latest_id ?>
                </p>
            </div>
            <div class="details-wrapper">
                <div class="trans-details">
                    <h2>
                        Brief Transaction Details:
                    </h2>
                    <div class="detail-line">
                        <strong>Customer:</strong> <?= $trans_details['customer_name'] ?> <br>
                    </div>
                    <div class="detail-line">
                        <strong>Treatment date set:</strong> <?="$date_set $time_set"?>
                    </div>
                    <div class="detail-line">
                        <strong>Treatment type:</strong> <?=$trans_details['treatment_type']?>
                    </div>
                    <div class="detail-line">
                        <strong>Branch:</strong> <?=$branch['name'] ($branch['location'])?>
                    </div>
                    <div class="detail-line">
                        <?=$package_details?> 
                    </div>
                </div>

                <p><i>Note: <br>
                        This is an automated email. Please do not reply.</i></p>
            </div>

            <img src="https://pestastic-inventory.site/img/logo.png" alt="logo" style="width: 4rem !important"><br>
            Thank you,<br>
            <b>Pestastic Inventory</b>
        </div>
    </div>
</body>

</html>