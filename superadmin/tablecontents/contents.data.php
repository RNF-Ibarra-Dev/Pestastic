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
                <td class="p-0"><button type="button" class="btn m-0 w-100 py-2 h-100 rounded-0 btn-sidebar">Edit</button></td>
            </tr>
            <?php

        }
        ?>
        <tr>
            <td colspan="4" class="p-0">
                <div class="row p-0 m-0">
                    <button type="button" class="col btn w-100 py-2 rounded-0 btn-sidebar" data-bs-toggle="modal"
                        data-bs-target="#trtmnt_mdl">Add More</button>
                    <button type="button" class="col btn w-100 py-2 rounded-0 btn-sidebar" data-bs-toggle="modal"
                        data-bs-target="#trtmnt_del">Delete Selected</button>
                </div>
            </td>
        </tr>
        <?php

    } else {
        ?>
        <td colspan="3" class="text-center">No Treatments Detected.</td>
        <?php
    }
}

if (isset($_GET['append']) && $_GET['append'] === 'trtmnt_branch_cont') {
    $sql = "SELECT * FROM branches;";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        ?>
        <option>Select Branch</option>
        <?php
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $name = $row['name'];
            $location = $row['location'];
            ?>
            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($name . ' (' . $location . ')') ?></option>
            <?php
        }
    } else {
        ?>
        <option>No Branches Added</option>
        <?php
    }
}
