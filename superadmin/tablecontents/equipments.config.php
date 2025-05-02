<?php
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_FILES['eimage'])) {
    echo json_encode(['tite' => 'tsts']);
}
