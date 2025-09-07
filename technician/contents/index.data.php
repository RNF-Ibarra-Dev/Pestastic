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


if (isset($_GET['dispatched_transactions']) && $_GET['dispatched_transactions'] === 'true') {
  $name = $_GET['name'];
  $brand = $_GET['brand'];
  $csize = $_GET['csize'];
  $unit = $_GET['unit'];

  $get_main_id_sql = "SELECT id FROM chemicals WHERE name = ? AND brand = ? AND container_size = ? AND quantity_unit = ?;";
  $get_main_id_stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($get_main_id_stmt, $get_main_id_sql)) {
    http_response_code(400);
    echo "Prepared statement failed at finding main chemical. Please try again later.";
    exit();
  }

  mysqli_stmt_bind_param($get_main_id_stmt, "ssis", $name, $brand, $csize, $unit);
  mysqli_stmt_execute($get_main_id_stmt);
  $result = mysqli_stmt_get_result($get_main_id_stmt);
  if ($row = mysqli_fetch_assoc($result)) {
    $main_id = $row['id'];
  } else {
    http_response_code(400);
    echo "Error. Main chemical not found.";
    exit();
  }

  $sql = "SELECT t.id FROM transactions t WHERE t.transaction_status = 'Dispatched' AND EXISTS (SELECT 1 FROM transaction_chemicals tc WHERE tc.chem_id = ? AND tc.trans_id = t.id);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    http_response_code(400);
    echo "Prepared statement failed at finding dispatched transactions. Please try again later.";
    exit();
  }

  mysqli_stmt_bind_param($stmt, "i", $main_id);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($res) > 0) {
    echo "<option value=''>Select Transaction</option>";
    while ($row = mysqli_fetch_assoc($res)) {
      $id = $row['id'];
      ?>
      <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></option>
      <?php
    }
  } else {
    echo "<option selected disabled>No available accepted transactions</option>";
  }
  mysqli_close($conn);
  exit();
}


if (isset($_GET['transaction_options']) && $_GET['transaction_options'] === 'true') {
  $sql = "SELECT id FROM transactions WHERE transaction_status = 'Accepted';";
  $res = mysqli_query($conn, $sql);

  echo "<option selected>Select Transaction</option>";
  if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
      $id = $row['id'];
      ?>
      <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></option>
      <?php
    }
  } else {
    echo "<option disabled>No available accepted transactions</option>";
  }
  mysqli_close($conn);
}


if (isset($_GET['dispatch_cur_transchem']) && $_GET['dispatch_cur_transchem'] === 'true') {
  $transid = $_GET['transid'];
  $chemid = $_GET['chemId'];
  $cont_size = $_GET['containerSize'];
  $return = isset($_GET['return']);

  if (empty($chemid)) {
    http_response_code(400);
    echo "Chemical ID missing. Please try again later.";
    exit();
  }

  if (!is_numeric(trim($transid)) || !is_numeric(trim($chemid))) {
    http_response_code(400);
    echo "Invalid Transaction ID or Chemical ID.";
    exit();
  }

  if ($return) {
    $main_chem = get_main_chemical($conn, $chemid);
    if (isset($main_chem['error'])) {
      http_response_code(400);
      echo $main_chem['error'];
      exit();
    }
  } else {
    $main_chem = $chemid;
  }

  $sql = "SELECT amt_used FROM transaction_chemicals WHERE trans_id = ? AND chem_id = ?;";
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
    http_response_code(400);
    echo "There seems to be an issue. Please try again later.";
    exit();
  }

  mysqli_stmt_bind_param($stmt, 'ii', $transid, $main_chem);
  mysqli_stmt_execute($stmt);

  $res = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    $amt_used = $row['amt_used'];
    http_response_code(200);
    $openedLevel = $amt_used % $cont_size;
    $closedContainer = (int) ($amt_used / $cont_size);
    while ($openedLevel > $cont_size) {
      $openedLevel -= $cont_size;
      $closedContainer++;
    }
    echo json_encode([
      'openedLevel' => $openedLevel,
      'closedContainer' => $closedContainer
    ]);

    exit();
  }
  mysqli_stmt_close($stmt);
  http_response_code(200);
  echo json_encode(["error" => "This item has no recorded amount set for transaction ID $transid."]);
  exit();
}




if (isset($_POST['dispatch']) && $_POST['dispatch'] === 'true') {
  $id = $_POST['dispatchChemicalId'];
  $dispatchAll = isset($_POST['dispatchAll']);
  $transaction = $_POST['dispatch-transaction'];
  $clocation = $_POST['currentLocation'];
  $pwd = $_POST['baPwd'];

  $dispatch_value = 0;
  $include_opened = NULL;
  if (!$dispatchAll) {
    $dispatch_value = $_POST['dispatchValue'];
    $include_opened = isset($_POST['includeOpened']);
  }

  if (empty($transaction) || !is_numeric($transaction)) {
    http_response_code(400);
    echo "Please select a valid transaction with this corresponding item.";
    exit();
  }

  $transaction_status = check_status($conn, $transaction);
  if ($transaction_status != "Accepted") {
    http_response_code(400);
    echo "Only approved transactions are allowed to be dispatched. $transaction_status";
    exit();
  }

  if (!is_numeric($id) || $id === NULL) {
    http_response_code(400);
    echo 'Chemical ID not found. Please try again later.';
    exit();
  }

  if (!is_numeric($dispatch_value)) {
    http_response_code(400);
    echo "dispatch value should be a number.";
    exit();
  }

  if ($clocation === "Dispatched") {
    http_response_code(400);
    echo "This chemical is already dispatched. Please select an available chemical.";
    exit();
  }

  if (!validateTech($conn, $pwd)) {
    http_response_code(400);
    echo "Wrong Password.";
    exit();
  }

  if ($dispatchAll) {
    $dispatch = dispatch_all_chemical($conn, $id, $transaction);
  } else {
    $dispatch = dispatch_chemical($conn, $id, $transaction, $dispatch_value, $include_opened);
  }

  if (isset($dispatch['error'])) {
    http_response_code(400);
    echo $dispatch['error'];
    exit();
  } else if ($dispatch) {
    http_response_code(200);
    echo json_encode(['success' => 'Dispatch Success!']);
    exit();
  } else {
    http_response_code(400);
    echo "An unknown error has occured. Please try again later.";
    exit();
  }
}


if (isset($_POST['return_chemical']) && $_POST['return_chemical'] === 'true') {

  $chem_id = $_POST['returnChemicalId'];
  $trans_id = $_POST['return_transaction'] ?? NULL;
  $opened_qty = $_POST['opened_container'];
  $closed_qty = $_POST['container_count'];
  $entered_unit = $_POST['return_unit'];
  $current_location = $_POST['return_currentLocation'];
  $pwd = $_POST['baPwd'];

  // exit();

  if (!$trans_id) {
    http_response_code(400);
    echo "Please select a valid transaction ID associated with the dispatched item.";
    exit();
  }

  if (!is_numeric($chem_id) || !is_numeric($trans_id)) {
    http_response_code(400);
    echo "Invalid ID passed.";
    exit();
  }

  if (!is_numeric($opened_qty) || !is_numeric($closed_qty)) {
    http_response_code(400);
    echo "Invalid input passed.";
    exit();
  }

  if ($opened_qty === '' || $closed_qty === '' || empty($pwd)) {
    http_response_code(400);
    echo "Please fill all fields.";
    exit();
  }

  if (empty($entered_unit) || $entered_unit === NULL || !is_string($entered_unit)) {
    http_response_code(400);
    echo "Invalid quantity unit.";
    exit();
  }


  // convert unit
  $original_data = get_chemical($conn, $chem_id);
  $original_unit = $original_data['quantity_unit'];
  if ($entered_unit !== $original_unit) {
    $convert = convert_to_main_unit($entered_unit, $original_unit, $opened_qty);
    if (isset($convert['error'])) {
      http_response_code(400);
      echo $convert['error'];
      exit();
    }
    $opened_qty = $convert;
  }


  if (!validateTech($conn, $pwd)) {
    http_response_code(400);
    echo "Invalid password.";
    exit();
  }

  $return = return_dispatched_chemical($conn, $chem_id, $trans_id, $opened_qty, $closed_qty);
  if (isset($return['error'])) {
    http_response_code(400);
    echo $return['error'];
    exit();
  } else if ($return) {
    http_response_code(200);
    echo json_encode(['success' => 'Chemicals returned!']);
    exit();
  } else {
    http_response_code(400);
    echo "An unknown error occurred. Please try again later.";
    exit();
  }
}
