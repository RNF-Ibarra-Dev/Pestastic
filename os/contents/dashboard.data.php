<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['getChart']) && $_GET['getChart'] == 'status') {
  $sql = "SELECT transaction_status, COUNT(*) as count FROM transactions WHERE branch = {$_SESSION['branch']} GROUP BY transaction_status;";
  $result = mysqli_query($conn, $sql);

  $status = [];
  $count = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $status[] = $row['transaction_status'];
    $count[] = $row['count'];
  }

  echo json_encode(['status' => $status, 'count' => $count]);
  mysqli_close($conn);
  exit();
}

if (isset($_GET['line']) && $_GET['line'] === 'yearly_completion') {
  $sql = "SELECT
            DATE_FORMAT(t.updated_at, '%M') AS month,
            COUNT(*) AS counts
          FROM
            transactions t
          WHERE
            YEAR(t.updated_at) = YEAR(NOW())
          AND	
            t.transaction_status = 'Completed'
          GROUP BY
            month
          order by
            month ASC;";
  $result = mysqli_query($conn, $sql);
  $months = [];
  $counts = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $months[] = $row['month'];
    $counts[] = $row['counts'];
  }

  echo json_encode(['months' => $months, 'counts' => $counts]);
  mysqli_close($conn);
  exit();
}

if (isset($_GET['bar']) && $_GET['bar'] === 'item_trend') {
  $sql = "SELECT 
              WEEK(t.updated_at, 1) - WEEK(DATE_FORMAT(t.updated_at, '%Y-%m-01'), 1) + 1 AS week_of_month,
              COUNT(*) AS total_count
          FROM 
              transaction_chemicals tc
          JOIN
              transactions t ON t.id = tc.trans_id
          WHERE
              MONTH(t.updated_at) = MONTH(NOW())
              AND YEAR(t.updated_at) = YEAR(NOW())
          GROUP BY
              week_of_month
          ORDER BY
              week_of_month;";
  $result = mysqli_query($conn, $sql);

  $week_count = array_fill(1, 5, 0);

  while ($row = mysqli_fetch_assoc($result)) {
    $week_no = $row['week_of_month'];
    $count = $row['total_count'];
    $week_count[$week_no] = $count;
  }

  $weeks = [];
  $counts = [];
  foreach ($week_count as $week => $count) {
    $weeks[] = "Week $week";
    $counts[] = $count;
  }


  echo json_encode(['weeks' => $weeks, 'count' => $counts]);
  mysqli_close($conn);
  exit();
}

if (isset($_GET['append']) && $_GET['append'] === 'pendingtrans') {
  $sql = "SELECT * FROM transactions WHERE transaction_status = 'Pending' AND branch = {$_SESSION['branch']} ORDER BY id DESC LIMIT 5;";
  $result = mysqli_query($conn, $sql);
  $rows = mysqli_num_rows($result);

  if ($rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $id = $row['id'];
      $customer = $row['customer_name'];
      $date = $row['treatment_date'];

      ?>
      <tr class="text-center align-middle">
        <td><?= htmlspecialchars($id) ?></td>
        <td><?= htmlspecialchars($customer) ?></td>
        <td>
          <div class="d-flex justify-content-center">
            <button id="tableDetails" data-trans-id="<?= htmlspecialchars($id) ?>"
              class="btn border border-light btn-sidebar me-2"><a href="transactions.php?openmodal=true&id=<?= $id ?>"
                class="link-underline text-light link-underline-opacity-0">View</a></button>
          </div>
        </td>
      </tr>

      <?php
    }
  } else {
    echo "<tr><td scope='row' colspan='3' class='text-center'>No pending requests.</td></tr>";
    exit();
  }
}

if (isset($_GET['append']) && $_GET['append'] === 'finalizing_table') {
  $sql = "SELECT * FROM transactions WHERE transaction_status = 'Finalizing' AND branch = {$_SESSION['branch']} ORDER BY id DESC LIMIT 5;";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $id = $row['id'];
      $customer = $row['customer_name'];
      $date = $row['treatment_date'];

      ?>
      <tr class="text-center align-middle">
        <td><?= htmlspecialchars($id) ?></td>
        <td><?= htmlspecialchars($customer) ?></td>
        <td>
          <div class="d-flex justify-content-center">
            <button id="tableDetails" data-trans-id="<?= htmlspecialchars($id) ?>"
              class="btn border border-light btn-sidebar me-2"><a href="transactions.php?openmodal=true&id=<?= $id ?>"
                class="link-underline text-light link-underline-opacity-0">View</a></button>
          </div>
        </td>
      </tr>

      <?php
    }
  } else {
    echo "<tr><td scope='row' colspan='3' class='text-center'>No finalizing requests.</td></tr>";
  }
  mysqli_close($conn);
  exit();
}

if (isset($_GET['append']) && $_GET['append'] == 'pendingchem') {
  $sql = "SELECT * FROM chemicals WHERE request = 1 AND branch = {$_SESSION['branch']} ORDER BY id DESC LIMIT 5;";
  $results = mysqli_query($conn, $sql);
  $rows = mysqli_num_rows($results);

  if ($rows > 0) {
    while ($row = mysqli_fetch_assoc($results)) {
      $name = $row['name'];
      $brand = $row['brand'];
      $dr = $row['date_received'];
      $date_received = date("F j, Y", strtotime($dr));
      ?>
      <tr class="text-center align-middle">
        <td><?= htmlspecialchars($name) ?></td>
        <td><?= htmlspecialchars($brand) ?></td>
        <td><?= htmlspecialchars($date_received) ?></td>
      </tr>
      <?php
    }
  } else {
    echo "<tr><td scope='row' colspan='3' class='text-center'>No pending requests.</td></tr>";
    exit();
  }
}
if (isset($_GET['append']) && $_GET['append'] == 'lowchemicals') {
  $sql = "SELECT * FROM chemicals WHERE unop_cont < restock_threshold AND branch = {$_SESSION['branch']} ORDER BY chemLevel ASC LIMIT 5;";
  $results = mysqli_query($conn, $sql);
  $rows = mysqli_num_rows($results);

  if ($rows > 0) {
    while ($row = mysqli_fetch_assoc($results)) {
      $name = $row['name'];
      $brand = $row['brand'];
      $unopened = $row['unop_cont'];
      $level = $row['chemLevel'];
      $containers = $unopened + ($level > 0 ? 1 : 0);
      ?>
      <tr class="text-center align-middle">
        <td><?= htmlspecialchars("$name $brand") ?></td>
        <td class='<?= $containers == 0 ? 'text-danger' : 'text-warning' ?>'><?= htmlspecialchars($containers) ?></td>
      </tr>
      <?php
    }
  } else {
    echo "<tr><td scope='row' colspan='3' class='text-center'>No pending requests.</td></tr>";
    exit();
  }
}


if (isset($_GET['count']) && $_GET['count'] === 'avail_item_count') {
  $sql = "SELECT COUNT(*) FROM chemicals WHERE request = 0 AND chem_location = 'main_storage' AND unop_cont > 0 AND chemLevel > 0 AND branch = {$_SESSION['branch']};";
  $res = mysqli_query($conn, $sql);

  echo mysqli_fetch_row($res)[0];
  exit();
}

if (isset($_GET['count']) && $_GET['count'] === 'dispatched_tech') {
  $sql = "SELECT COUNT(*) FROM transaction_technicians tt JOIN transactions t ON tt.trans_id = t.id WHERE t.transaction_status = 'Finalizing' AND t.branch = {$_SESSION['branch']};";
  $res = mysqli_query($conn, $sql);

  echo mysqli_fetch_row($res)[0];
  exit();
}

if (isset($_GET['count']) && $_GET['count'] === 'weekly_completed') {
  $sql = "SELECT COUNT(*) FROM transactions WHERE (updated_at BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()) AND transaction_status = 'Completed' AND branch = {$_SESSION['branch']};";
  $res = mysqli_query($conn, $sql);

  echo mysqli_fetch_row($res)[0];
  exit();
}

if (isset($_GET['count']) && $_GET['count'] === 'urgent_restocks') {
  $sql = "SELECT COUNT(*) FROM chemicals WHERE (unop_cont + (CASE WHEN chemLevel > 0 THEN 1 ELSE 0 END)) < restock_threshold  AND branch = {$_SESSION['branch']};";
  $res = mysqli_query($conn, $sql);

  echo mysqli_fetch_row($res)[0];
  exit();
}

if (isset($_GET['count']) && $_GET['count'] === 'today_pending') {
  $sql = "SELECT COUNT(*) FROM transactions WHERE (transaction_status = 'Pending' OR transaction_status = 'Finalizing') AND branch = {$_SESSION['branch']};";
  $res = mysqli_query($conn, $sql);

  echo mysqli_fetch_row($res)[0];
  exit();
}

