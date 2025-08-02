<?php
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
  $sql = "SELECT * FROM transactions WHERE transaction_status = 'Pending' ORDER BY id DESC LIMIT 5;";
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