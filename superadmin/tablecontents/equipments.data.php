<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['equipments']) && $_GET['equipments'] === 'true') {
    $sql = "SELECT * FROM equipments ORDER BY id DESC;";
    $results = mysqli_query($conn, $sql);

    $rows = mysqli_num_rows($results);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($results)) {
            $id = $row['id'];
            $ename = $row['equipment'];
            $desc = $row['description'];
            $avail = $row['availability'];
            $img = $row['equipment_image'];
            $delarr = json_encode([
                'id' => $id,
                'img' => $img,
                'name' => $ename
            ]);
            ?>
            <div class="col">
                <div class="card h-100 card-bg rounded-3 border-0 text-light">
                    <img src="Pestastic/<?= $img ?>" class="object-fit-cover img-round h-75"
                        id="<?= str_replace(' ', '', $ename) . $id ?>" onerror="altimg('<?= str_replace(' ', '', $ename) . $id ?>')"
                        alt="<?= htmlspecialchars($ename) ?>">
                    <div class="card-body px-2 border-top border-light ">
                        <h5 class="card-title py-2 fs-4 text-center"><?= htmlspecialchars(ucwords($ename)) ?></h5>
                        <hr>
                        <p class="card-text mb-2"><strong>Availability:</strong> <?= htmlspecialchars($avail) ?></p>
                        <p class="card-text text-light"><?= $desc == NULL ? 'No Description.' : htmlspecialchars($desc) ?></p>
                    </div>
                    <div class="card-footer bg-transparent p-0 d-flex justify-content-around border-top border-light">
                        <button type="button" data-id="<?= htmlspecialchars($id) ?>" id="editbtn"
                            class="btn btn-sidebar left-btn-rounded text-light w-50 py-3">Edit</button>
                        <button type="button" data-del="<?= htmlspecialchars($delarr, ENT_QUOTES, 'UTF-8') ?>" id="deletebtn"
                            class="btn btn-sidebar right-btn-rounded text-light w-50 py-3">Delete</button>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if (isset($_GET['editmodal']) && $_GET['editmodal'] === 'true') {
    $eid = htmlspecialchars($_GET['eid']);

    $sql = "SELECT * FROM equipments WHERE id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(400);
        echo json_encode(['error' => 'STMT ERROR']);
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'i', $eid);
    mysqli_stmt_execute($stmt);

    $results = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($results)) {
        echo json_encode(['success' => $row]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Fetch Failed.']);
    }
    mysqli_stmt_close($stmt);
    exit();
}
