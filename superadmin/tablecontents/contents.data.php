<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once("../../includes/functions.inc.php");

if (isset($_GET['append']) && $_GET['append'] === 'treatment') {
    $sql = "SELECT * FROM treatments;";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $name = $row['t_name'];
            $brnchid = $row['branch'];
            $brnch = get_branch_details($conn, $brnchid);
            ?>
            <tr class="text-center">
                <td>
                    <input type="checkbox" name="trtmnt_chk[]" value="<?= htmlspecialchars($id) ?>" class="form-check-input">
                </td>
                <td><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($brnch['name'] . ' (' . $brnch['location'] . ')') ?></td>
            </tr>
            <?php

        }
        ?>
        <td colspan="3" class="text-center p-0"><button type="button" class="btn w-100" data-bs-toggle="modal" data-bs-target="#trtmnt_mdl">Add More</button></td>
        <?php

    } else {

    }
}