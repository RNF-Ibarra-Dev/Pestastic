<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_GET['equipments']) && $_GET['equipments'] === 'true') {
    $sql = "SELECT * FROM equipments;";
    $results = mysqli_query($conn, $sql);

    $rows = mysqli_num_rows($results);

    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($results)) {
            $ename = $row['equipment'];
            $avail = $row['availability'];
            $img = $row['equipment_image'];
?>
            <div class="col">
                <div class="card h-100 text-bg-dark border-light">
                    <img src="../img/<?= $img ?>" class="card-img-top h-75" alt="...">
                    <div class="card-body border-light">
                        <h5 class="card-title"><?= htmlspecialchars($ename) ?></h5>
                        <p class="card-text">Availability: <?= htmlspecialchars($avail) ?></p>
                    </div>
                    <div class="card-footer border-light bg-transparent">Footer</div>
                </div>
            </div>
<?php
        }
    }
}
