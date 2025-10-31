<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['getChart']) && $_GET['getChart'] == 'status') {
  $sql = "SELECT transaction_status, COUNT(*) as count FROM transactions GROUP BY transaction_status;";
  $result = mysqli_query($conn, $sql);

  $status = [];
  $count = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $status[] = $row['transaction_status'];
    $count[] = $row['count'];
  }

  echo json_encode(['status' => $status, 'count' => $count]);
}

if (isset($_GET['append']) && $_GET['append'] === 'pendingtrans') {
  $sql = "SELECT * FROM transactions t JOIN transaction_technicians tt ON t.id = tt.trans_id WHERE t.transaction_status = 'Pending' AND tt.tech_id = {$_SESSION['techId']} ORDER BY t.updated_at DESC, t.created_at DESC LIMIT 5;";
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
    echo "<tr><td scope='row' colspan='4' class='text-center'>No pending requests.</td></tr>";
    exit();
  }
}

if (isset($_GET['append']) && $_GET['append'] === 'scheduled') {
  $sql = "SELECT * FROM transactions t JOIN transaction_technicians tt ON t.id = tt.trans_id WHERE t.transaction_status = 'Accepted' AND tt.tech_id = {$_SESSION['techId']} ORDER BY id DESC LIMIT 5;";
  $result = mysqli_query($conn, $sql);
  $rows = mysqli_num_rows($result);

  if ($rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $id = $row['id'];
      $customer = $row['customer_name'];
      $date = date("F j, Y", strtotime($row['treatment_date']));
      $time = date("h:i A", strtotime($row['transaction_time']));
      ?>
      <tr class="text-center align-middle">
        <td><?= htmlspecialchars($id) ?></td>
        <td><?= htmlspecialchars($customer) ?></td>
        <td><?= htmlspecialchars("$date  |  $time") ?></td>
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
    echo "<tr><td scope='row' colspan='4' class='text-center'>No pending requests.</td></tr>";
    exit();
  }
}

if (isset($_GET['append']) && $_GET['append'] == 'pendingchem') {
  $sql = "SELECT * FROM chemicals WHERE request = 1 ORDER BY id DESC LIMIT 5;";
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
  $sql = "SELECT * FROM chemicals WHERE unop_cont < restock_threshold ORDER BY chemLevel ASC LIMIT 5;";
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

if (isset($_GET['append']) && $_GET['append'] === 'voidreqtable') {
  $sql = "SELECT * FROM transactions WHERE void_request = 1 ORDER BY id DESC LIMIT 5;";
  $results = mysqli_query($conn, $sql);
  $rows = mysqli_num_rows($results);

  if ($rows > 0) {
    while ($row = mysqli_fetch_assoc($results)) {
      $name = $row['customer_name'];
      $id = $row['id'];
      $date = $row['treatment_date'];
      ?>
      <tr class="text-center align-middle">
        <td><?= htmlspecialchars($id) ?></td>
        <td><?= htmlspecialchars($name) ?></td>
        <td><?= htmlspecialchars($date) ?></td>
      </tr>
      <?php
    }
  } else {
    echo "<tr><td scope='row' colspan='3' class='text-center'>No Void Requests.</td></tr>";
    exit();
  }
}

if (isset($_GET['append']) && $_GET['append'] === 'finalizing_table') {
  $sql = "SELECT * FROM transactions WHERE transaction_status = 'Finalizing' ORDER BY id DESC LIMIT 5;";
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

if (isset($_GET['count']) && $_GET['count'] === 'avail_item_count') {
  $sql = "SELECT COUNT(*) FROM chemicals WHERE request = 0 AND chem_location = 'main_storage' AND unop_cont > 0 AND chemLevel > 0 AND branch = {$_SESSION['branch']};";
  $res = mysqli_query($conn, $sql);

  echo mysqli_fetch_row($res)[0];
  exit();
}

if (isset($_GET['count']) && $_GET['count'] === 'queue_transaction') {
  $sql = "SELECT COUNT(*) FROM transactions t JOIN transaction_technicians tt ON t.id = tt.trans_id WHERE t.transaction_status = 'Accepted' AND tt.tech_id = {$_SESSION['techId']};";
  $res = mysqli_query($conn, $sql);

  echo mysqli_fetch_row($res)[0];
  exit();
}

if (isset($_GET['count']) && $_GET['count'] === 'pending_trans') {
  $sql = "SELECT COUNT(*) FROM transactions t JOIN transaction_technicians tt ON t.id = tt.trans_id WHERE t.transaction_status = 'Pending' AND tt.tech_id = {$_SESSION['techId']};";
  $res = mysqli_query($conn, $sql);

  echo mysqli_fetch_row($res)[0];
  exit();
}


if (isset($_GET['count']) && $_GET['count'] === 'completed_trans') {
  $sql = "SELECT COUNT(*) FROM transactions t JOIN transaction_technicians tt ON t.id = tt.trans_id WHERE t.transaction_status = 'Completed' AND tt.tech_id = {$_SESSION['techId']};";
  $res = mysqli_query($conn, $sql);

  echo mysqli_fetch_row($res)[0];
  exit();
}


if (isset($_GET['append']) && $_GET['append'] === 'top_technicians') {
  $sql = "SELECT
            tt.tech_info as names,
            COUNT(*) AS counts
          FROM
            transaction_technicians tt
          JOIN
            transactions t
          ON 
            t.id = tt.trans_id
          WHERE
            YEAR(t.updated_at) = YEAR(NOW())
          AND
            MONTH(t.updated_at) = MONTH(NOW())
          AND
            t.transaction_status = 'Completed'
          GROUP BY
            names
          ORDER BY
            counts DESC
          LIMIT 5;";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo "<li class='list-group-item d-flex justify-content-between bg-light bg-opacity-25 text-light'>
        <span class='fw-bold fs-5'>Technician</span>
        <span class='fw-bold fs-5 text-end'>Completed Transactions</span>
      </li>";
    while ($row = mysqli_fetch_assoc($result)) {
      $name = $row['names'];
      $count = $row['counts'];
      ?>
      <li class="list-group-item d-flex justify-content-between bg-light bg-opacity-25 text-light">
        <span class="mx-2"><?= htmlspecialchars($name) ?></span>
        <span class="mx-2 fw-medium"><?= htmlspecialchars($count) ?></span>
      </li>
      <?php
    }
  } else {
    ?>
    <li class="list-group-item text-center bg-light user-select-none bg-opacity-25 text-light text-shadow">No top
      technicians for this month</li>
    <?php
  }
  mysqli_close($conn);
  exit();
}

if (isset($_GET['append']) && $_GET['append'] === 'tech_workload') {
  $sql = "SELECT
            tt.tech_info as names,
            COUNT(*) AS counts
          FROM
            transaction_technicians tt
          JOIN
            transactions t
          ON 
            t.id = tt.trans_id
          WHERE
            YEAR(t.updated_at) = YEAR(NOW())
          AND
            MONTH(t.updated_at) = MONTH(NOW())
          AND
            WEEK(t.updated_at, 1) = WEEK(NOW(), 1)
          GROUP BY
            names
          ORDER BY
            counts DESC;";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo "<li class='list-group-item d-flex justify-content-between bg-light bg-opacity-25 text-light'>
        <span class='fw-bold fs-5'>Technician</span>
        <span class='fw-bold fs-5 text-end'>Transaction Workload</span>
      </li>";
    while ($row = mysqli_fetch_assoc($result)) {
      $name = $row['names'];
      $count = $row['counts'];
      ?>
      <li class="list-group-item d-flex justify-content-between bg-light bg-opacity-25 text-light">
        <span class="mx-2"><?= htmlspecialchars($name) ?></span>
        <span class="mx-2 fw-medium"><?= htmlspecialchars($count) ?></span>
      </li>
      <?php
    }
  } else {
    ?>
    <li class="list-group-item text-center text-light bg-light bg-opacity-25 text-shadow">No assigned technicians for this
      week.</li>
    <?php
  }
  mysqli_close($conn);
  exit();
}