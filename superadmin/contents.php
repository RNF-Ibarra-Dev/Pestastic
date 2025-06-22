<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Contents</title>
    <?php include('header.links.php'); ?>

</head>

<body class="bg-official text-light">

    <div class="sa-bg container-fluid p-0 h-100 d-flex">
        <!-- sidebar -->
        <?php include('settings.sidenav.php'); ?>
        <!-- main content -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php include('navbar.php'); ?>
            <!-- content -->
            <div class="container-fluid h-75 d-flex flex-column mt-3 px-5">
                <h2 class="fw-bold text-center">Manage Contents</h2>
                <div class="container bg-light bg-opacity-25 rounded border border-light py-4 px-3  ">
                    <p class="fw-medium fs-4 m-0 text-center">Treatments</p>
                    <hr class="mt-1 mb-2 opacity-50">
                    <div class="table-responsive justify-content-center">
                        <form id="trt_del_form">
                            <table class="table align-middle table-bordered table-hover p-2 w-100">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col">
                                            <input type="checkbox" id="checkallbtn" class="form-check-input">
                                        </th>
                                        <th scope="col">Treatment</th>
                                        <th scope="col">Branch</th>
                                        <th scope="col"><i class="bi bi-pencil-square"></i></th>
                                    </tr>
                                </thead>
                                <tbody id="treatment">
                                </tbody>
                            </table>
                        </form>
                        <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                            id="trtmnt_table_alert">
                        </p>
                    </div>

                    <p class="fw-medium fs-4 m-0 text-center">Problems</p>
                    <hr class="mt-1 mb-2 opacity-50">
                    <div class="table-responsive justify-content-center">
                        <form id="problemform">
                            <table class="table align-middle table-bordered table-hover p-2 w-100">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col">
                                            <input type="checkbox" id="checkallprob" class="form-check-input">
                                        </th>
                                        <th scope="col">Pest Problem</th>
                                        <th scope="col"><i class="bi bi-pencil-square"></i></th>
                                    </tr>
                                </thead>
                                <tbody id="problems"></tbody>
                            </table>
                        </form>
                        <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                            id="prob_table_alert">
                        </p>
                    </div>

                    <div class="table-responsive justify-content-center">
                        <p class="fw-medium fs-4 m-0 text-center">Branches</p>
                        <hr class="mt-1 mb-2 opacity-50">
                        <form id="branchesform">
                            <table class="table align-middle table-bordered table-hover p-2 w-100">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col">
                                            <input type="checkbox" id="checkallbranch" class="form-check-input">
                                        </th>
                                        <th scope="col">Branch</th>
                                        <th scope="col">Location</th>
                                        <th scope="col"><i class="bi bi-pencil-square"></i></th>
                                    </tr>
                                </thead>
                                <tbody id="branches"></tbody>
                            </table>
                        </form>
                        <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                            id="branch_table_alert"></p>
                    </div>

                    <div class="table-responsive justify-content-center">
                        <p class="fw-medium fs-4 m-0 text-center">Packages</p>
                        <hr class="mt-1 mb-2 opacity-50">
                        <form id="packagesform">
                            <table class="table align-middle table-bordered table-hover p-2 w-100">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col">
                                            <input type="checkbox" id="checkallbranch" class="form-check-input">
                                        </th>
                                        <th scope="col">Package</th>
                                        <th scope="col">Session Count</th>
                                        <th scope="col">Warranty Count</th>
                                        <th scope="col">Treatment</th>
                                        <th scope="col">Branch</th>
                                        <th scope="col"><i class="bi bi-pencil-square"></i></th>
                                    </tr>
                                <tbody id="packages"></tbody>
                                </thead>
                            </table>
                        </form>
                        <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                            id="package_table_alert"></p>
                    </div>

                </div>
            </div>
            <div class="container h-75"></div>
        </main>

        <!-- modals -->
        <form id="treatment_form">
            <div class="modal modal-lg fade text-dark modal-edit" data-bs-backdrop="static" id="trtmnt_mdl"
                tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Add Treatment</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="trtmnt_input" class="form-label fw-light fs-5">Treatment Name:</label>
                                    <input type="text" id="trtmnt_input" name="treatment" class="form-control"
                                        autocomplete="one-time-code">
                                </div>
                                <div class="col">
                                    <label for="trt_addbranch" class="form-label fw-light fs-5">Branch:</label>
                                    <select name="trtmnt_branch" id="trt_addbranch" class="form-select">
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                            <button type="button" data-bs-toggle="modal" class="btn btn-grad"
                                data-bs-target="#cnfrm_trtmnt">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="cnfrm_trtmnt" tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Confirm Adding New Treatment</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <label for="approve-inputpwd" class="form-label fw-light">Enter manager
                                    <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                <div class="col-6 mb-2">
                                    <input type="password" name="trtmnt_pwd" class="form-control">
                                </div>
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                id="trtmnt_alert">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                data-bs-target="#trtmnt_mdl">Go Back</button>
                            <button type="submit" class="btn btn-grad">Add Treatment</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="edit_treatment_form">
            <input type="hidden" name="id" id="trt_edit_id" required>
            <div class="modal modal-lg fade text-dark modal-edit" data-bs-backdrop="static" id="trt_edit_modal"
                tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Edit Treatment</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="trt_edit_input" class="form-label fw-light fs-5">Treatment Name:</label>
                                    <input type="text" id="trt_edit_input" name="treatment" class="form-control"
                                        autocomplete="one-time-code">
                                </div>
                                <div class="col">
                                    <label for="trt_editbranch" class="form-label fw-light fs-5">Branch:</label>
                                    <select name="branch" id="trt_editbranch" class="form-select">
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                            <button type="button" data-bs-toggle="modal" class="btn btn-grad"
                                data-bs-target="#trt_edit_confirm">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="trt_edit_confirm" tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Confirm Changes</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <label for="approve-inputpwd" class="form-label fw-light">Enter manager
                                    <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                <div class="col-6 mb-2">
                                    <input type="password" name="pwd" class="form-control">
                                </div>
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                id="trtmnt_edit_alert">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                data-bs-target="#trt_edit_modal">Go Back</button>
                            <button type="submit" class="btn btn-grad">Update Treatment</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="del_confirm">
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="del_trt" tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Confirm Delete Treatment</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <label for="approve-inputpwd" class="form-label fw-light">Enter manager
                                    <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                <div class="col-6 mb-2">
                                    <input type="password" name="pwd" class="form-control">
                                </div>
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                id="trt_del_alert">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-grad">Delete Treatment</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- problems modals -->
        <form id="prob_add_form">
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="prob_add_modal" tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Add Pest Problem</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-8">
                                    <label for="prob_name" class="form-label fw-light fs-5">Pest Problem:</label>
                                    <input type="text" id="prob_name" name="prob[]" class="form-control"
                                        autocomplete="one-time-code">
                                </div>
                            </div>
                            <div id="prob_input_container"></div>
                            <button class="btn btn-grad py-2 mt-4" type="button" id="add_prob_row"><i
                                    class="bi bi-plus-circle me-2"></i>Add More</button>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                            <button type="button" data-bs-toggle="modal" class="btn btn-grad"
                                data-bs-target="#conf_prob_add">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="conf_prob_add" tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Confirm Adding New Pest Problem</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <label for="approve-inputpwd" class="form-label fw-light">Enter manager
                                    <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                <div class="col-6 mb-2">
                                    <input type="password" name="pwd" class="form-control">
                                </div>
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                id="prob_add_alert">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                data-bs-target="#prob_add_modal">Go Back</button>
                            <button type="submit" class="btn btn-grad">Add New Pest Problem</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="prob_edit_form">
            <input type="hidden" name="pid" id="edit_prob_id">
            <div class="modal modal-lg fade text-dark modal-edit" data-bs-backdrop="static" id="prob_edit_modal"
                tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Update Pest Problem</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="edit_prob_input" class="form-label fw-light fs-5">Pest Problem:</label>
                                    <input type="text" id="edit_prob_input" name="prob" class="form-control"
                                        autocomplete="one-time-code">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                            <button type="button" data-bs-toggle="modal" class="btn btn-grad"
                                data-bs-target="#conf_edit_prob_modal">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="conf_edit_prob_modal"
                tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Confirm Pest Problem Modification</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <label for="approve-inputpwd" class="form-label fw-light">Enter manager
                                    <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                <div class="col-6 mb-2">
                                    <input type="password" name="pwd" class="form-control">
                                </div>
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                id="prob_edit_alert">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                data-bs-target="#prob_edit_modal">Go Back</button>
                            <button type="submit" class="btn btn-grad">Update Pest Problem</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="del_edit_confirm">
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="conf_del_prob_modal"
                tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Confirm Pest Problem Deletion</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <label for="approve-inputpwd" class="form-label fw-light">Enter manager
                                    <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                <div class="col-6 mb-2">
                                    <input type="password" name="pwd" class="form-control">
                                </div>
                                <p class="text-body-secondary text-muted">Note: Deleting record/s are irreversible.
                                    Proceed with caution.</p>
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                id="prob_del_alert">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-grad">Update Pest Problem</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- branches modals -->
        <form id="branch_add_form">
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="branch_add_modal" tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Add New Branch</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-6">
                                    <label for="branchname" class="form-label fw-light fs-5">Branch Name:</label>
                                    <input type="text" id="branchname" name="branch[]" class="form-control"
                                        autocomplete="one-time-code">
                                </div>
                                <div class="col-6">
                                    <label for="branchloc" class="form-label fw-light fs-5">Location:</label>
                                    <input type="text" id="branchloc" name="location[]" class="form-control"
                                        autocomplete="one-time-code">
                                </div>
                            </div>
                            <div id="branch_add_container"></div>
                            <button class="btn btn-grad py-2 mt-4" type="button" id="branch_add_row"><i
                                    class="bi bi-plus-circle me-2"></i>Add More</button>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                            <button type="button" data-bs-toggle="modal" class="btn btn-grad"
                                data-bs-target="#branch_add_conf_modal">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="branch_add_conf_modal"
                tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">New Branch Confirmation</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <label for="approve-inputpwd" class="form-label fw-light">Enter manager
                                    <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                <div class="col-6 mb-2">
                                    <input type="password" name="pwd" class="form-control">
                                </div>
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                id="branch_add_alert">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                data-bs-target="#branch_add_modal">Go Back</button>
                            <button type="submit" class="btn btn-grad">Add New Branch</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="branch_edit_form">
            <input type="hidden" name="id" id="branch_edit_id_input">
            <div class="modal modal-lg fade text-dark modal-edit" data-bs-backdrop="static" id="branch_edit_modal"
                tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Update Branch Information</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-6">
                                    <label for="br_edit_input" class="form-label fw-light fs-5">Pest Problem:</label>
                                    <input type="text" id="br_edit_input" name="branch" class="form-control"
                                        autocomplete="one-time-code">
                                </div>
                                <div class="col-6">
                                    <label for="br_loc_edit_input" class="form-label fw-light fs-5">Location:</label>
                                    <input type="text" id="br_loc_edit_input" name="location" class="form-control"
                                        autocomplete="one-time-code">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                            <button type="button" data-bs-toggle="modal" class="btn btn-grad"
                                data-bs-target="#branch_edit_conf_modal">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="branch_edit_conf_modal"
                tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Update Branch Confirmation</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <label for="approve-inputpwd" class="form-label fw-light">Enter manager
                                    <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                <div class="col-6 mb-2">
                                    <input type="password" name="pwd" class="form-control">
                                </div>
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                id="branch_edit_alert">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                data-bs-target="#branch_edit_modal">Go Back</button>
                            <button type="submit" class="btn btn-grad">Update Branch</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="branch_del_form">
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="branch_del_modal" tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Confirm Branch Deletion</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <label for="approve-inputpwd" class="form-label fw-light">Enter manager
                                    <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                <div class="col-6 mb-2">
                                    <input type="password" name="pwd" class="form-control">
                                </div>
                                <p class="text-body-secondary text-muted">Note: Deleting record/s are irreversible.
                                    Proceed with caution.</p>
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                id="branch_del_alert">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-grad">Delete Branch</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <?php include('footer.links.php'); ?>
    <script>
        const dataurl = "tablecontents/contents.data.php";
        const configurl = "tablecontents/contents.config.php";

        async function append(container) {
            return $.get(dataurl, {
                append: container
            })
                .done(function (d) {
                    $(`#${container}`).empty();
                    $(`#${container}`).append(d);
                    return true;
                })
                .fail(function (e) {
                    console.log(e.responseText);
                    return false;
                })
        }

        $(document).ready(async function () {
            await append('treatment');
            await append('trt_addbranch');
            await append('problems');
            await append('branches');
        });

        $(document).on("click", "#add_trt", function () {
            $('#treatment_form')[0].reset();
            $('#trtmnt_mdl').modal('show');
        })

        $(document).on('submit', '#treatment_form', async function (e) {
            e.preventDefault();
            // console.log($(this).serialize());
            $.ajax({
                url: configurl,
                dataType: 'json',
                method: "POST",
                data: $(this).serialize() + "&add-treatment=true"
            })
                .done(async function (d) {
                    console.log(d);
                    await append('treatment');
                    $('#cnfrm_trtmnt').modal('hide');
                    $('#trtmnt_table_alert').html(d.success).fadeIn(750).delay(5000).fadeOut(2000);
                })
                .fail(function (e) {
                    console.log(e);
                    console.log(e.responseJSON.error);
                    $('#trtmnt_alert').html(e.responseJSON.error).fadeIn(750).delay(5000).fadeOut(2000);
                })
                .always(function (a) {
                    console.log(a);
                })
        });

        // async function get()

        $(document).on('click', '.trt-edit', async function () {
            let id = $(this).data('trt');
            let branch = await append('trt_editbranch');

            $.get(dataurl, {
                trt_details: id
            })
                .done(async function (d) {
                    let deets = JSON.parse(d);
                    console.log(deets);
                    // alert(deets.t_name);
                    if (branch) {
                        $("#trt_edit_id").val(deets.id)
                        $("#trt_edit_input").val(deets.t_name);
                        $("#trt_editbranch").val(deets.branch);
                        $('#trt_edit_modal').modal('show');
                    }
                })
                .fail(function (e) {
                    alert(e.responseText);
                });
        });

        $(document).on('submit', '#edit_treatment_form', async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            $.ajax({
                method: 'POST',
                url: configurl,
                dataType: 'json',
                data: $(this).serialize() + "&edit=true"
            })
                .done(function (d) {
                    // alert(d);
                    append('treatment');
                    // console.log(d);
                    $('#trt_edit_confirm').modal('hide');
                    $('#trtmnt_table_alert').html(d.success).fadeIn(750).delay(5000).fadeOut(2000);
                })
                .fail(function (e) {
                    // console.log(e);
                    $('#trtmnt_edit_alert').html(e.responseJSON.error).fadeIn(750).delay(5000).fadeOut(2000);
                })
        });

        $(document).on('change', '#checkallbtn', async function () {
            var checked = $(this).prop('checked');
            $('tbody#treatment input[type="checkbox"]').prop('checked', checked);
        });

        async function delete_treatment(data) {
            console.log(data);
            return $.ajax({
                method: "POST",
                url: configurl,
                dataType: 'json',
                data: data + "&delete=true"
            })
                .done(function (d) {
                    console.log(d);
                    append('treatment');
                    $('#del_trt').modal('hide');
                    $('#trtmnt_table_alert').html(d.success).fadeIn(750).delay(5000).fadeOut(2000);
                })
                .fail(function (e) {
                    console.log(e);
                    $('#trt_del_alert').html(e.responseJSON.error).fadeIn(750).delay(5000).fadeOut(2000);
                })
        }

        $(document).on('click', '#delete_selected', async function () {
            var form = $('#trt_del_form');
            var formdata = form.serialize();
            // console.log(formdata);
            if (formdata.length == 0) {
                alert('No checked treatment.');
            } else {
                $('#del_trt').modal('show');
            }
        });

        $(document).on('submit', '#del_confirm', async function (e) {
            e.preventDefault();
            let data = $("#trt_del_form").serialize() + '&' + $('#del_confirm').serialize();
            await delete_treatment(data);
        });

        $(document).on('click', '#add_prob', async function () {
            $("#prob_add_form")[0].reset();
            $("#prob_add_modal").modal('show');
        });

        async function append_row(container) {
            return $.get(dataurl, {
                row: container
            })
                .done(function (d) {
                    $(`#${container}`).append(d);
                    return true;
                })
                .fail(function (e) {
                    return 'Error at append_row function: ' + e.responseText;
                    // console.log(e);
                });
        }

        $('#prob_add_form').on('click', '#add_prob_row', async function () {
            let appendrow = await append_row('prob_input_container');
            if (!appendrow) {
                console.log(appendrow);
            }
        });

        $('#prob_input_container').on('click', '.del-prob-row-btn', function () {
            $(this).parent().parent().remove();
        });

        $(document).on('submit', '#prob_add_form', async function (e) {
            e.preventDefault();
            await $.ajax({
                method: "POST",
                url: configurl,
                dataType: 'json',
                data: $(this).serialize() + "&addProb=true"
            })
                .done(async function (d) {
                    let table = await append('problems');
                    if (table) {
                        $('#prob_table_alert').html(d.success).fadeIn(750).delay(5000).fadeOut(2000);
                        $('#conf_prob_add').modal('hide');
                    }
                })
                .fail(function (e) {
                    console.log(e);
                    $('#prob_add_alert').html(e.responseJSON.error).fadeIn(750).delay(5000).fadeOut(2000);
                })
        })

        $(document).on('click', 'button.prob-edit', async function () {
            let prob = $(this).data('prob');
            $("#prob_edit_form")[0].reset();
            // console.log(prob);

            $.get(dataurl, {
                getprob: true,
                id: prob
            })
                .done(async function (d) {
                    let dd = JSON.parse(d);
                    // console.log(dd);
                    $("#edit_prob_input").val(dd.problems);
                    $("#edit_prob_id").val(dd.id);
                    $("#prob_edit_modal").modal('show');
                })
                .fail(function (e) {
                    console.log(e);
                    alert("Error fetching pest problem details.");
                });
        });

        $(document).on('submit', '#prob_edit_form', async function (e) {
            e.preventDefault();
            // console.log($(this).serialize());
            await $.ajax({
                method: "POST",
                dataType: 'json',
                url: configurl,
                data: $(this).serialize() + "&editprob=true"
            })
                .done(async function (d) {
                    let table = await append('problems');
                    if (table) {
                        $('#prob_table_alert').html(d.success).fadeIn(750).delay(5000).fadeOut(2000);
                        $('#conf_edit_prob_modal').modal('hide');
                    }
                })
                .fail(function (e) {
                    console.log(e);
                    let er = e.responseJSON;
                    let err = typeof er != 'undefined' ? er.error : e.responseText;
                    $('#prob_edit_alert').html(err).fadeIn(750).delay(5000).fadeOut(2000);
                });
        });

        async function delete_pproblem(data) {
            console.log(data);
            return $.ajax({
                method: "POST",
                url: configurl,
                dataType: 'json',
                data: data + "&deleteprob=true"
            })
                .done(async function (d) {
                    console.log(d);
                    append('problems');
                    $('#conf_del_prob_modal').modal('hide');
                    $('#prob_table_alert').html(d.success).fadeIn(750).delay(5000).fadeOut(2000);
                })
                .fail(function (e) {
                    console.log(e);
                    let er = e.responseJSON;
                    let err = typeof er != 'undefined' ? er.error : e.responseText;
                    $('#prob_del_alert').html(err).fadeIn(750).delay(5000).fadeOut(2000);
                })
        }

        $('#problemform').on('change', '#checkallprob', function () {
            let checked = $("#checkallprob").prop('checked');

            $("tbody#problems tr td input[type='checkbox']").prop('checked', checked);
        })

        $("#problemform").on('click', '#delete_prob_btn', function () {
            var form = $('#problemform');
            var formdata = form.serialize();
            // console.log(formdata);
            if (formdata.length == 0) {
                alert('No checked treatment.');
            } else {
                $('#conf_del_prob_modal').modal('show');
            }
        })

        $(document).on('submit', '#del_edit_confirm', async function (e) {
            e.preventDefault();
            // console.log($(this).serialize() + "&" + $("#problemform").serialize());
            let data = $(this).serialize() + "&" + $("#problemform").serialize();
            await delete_pproblem(data);
        });

        $(document).on('click', '#branch_addbtn', function () {
            $("#branch_add_form")[0].reset();
            $("#branch_add_modal").modal('show');
        });

        $('#branch_add_form').on('click', '#branch_add_row', async function () {
            let appendrow = await append_row('branch_add_container');
            if (!appendrow) {
                console.log(appendrow);
            }
        });

        $("#branch_add_form").on('click', '.edit-prob-row-btn', function () {
            $(this).parent().parent().remove();
        });

        $(document).on('submit', "#branch_add_form", async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            await $.ajax({
                method: 'POST',
                url: configurl,
                dataType: 'json',
                data: $(this).serialize() + "&branchadd=true"
            })
                .done(async function (d) {
                    let table = await append('branches');
                    if (table) {
                        $('#branch_add_conf_modal').modal('hide');
                        $('#branch_table_alert').html(d.success).fadeIn(750).delay(5000).fadeOut(2000);
                    } else {
                        alert("Append returned false. Please refresh browser.");
                    }
                })
                .fail(function (e) {
                    console.log(e);
                    let err = typeof e.responseJSON == 'undefined' ? e.responseText : e.responseJSON.error;
                    $('#branch_add_alert').html(err).fadeIn(750).delay(5000).fadeOut(2000);
                })
        });

        $('#branches').on('click', '.branch-edit', function () {
            $("#branch_edit_form")[0].reset();
            let branch = $(this).data('branch');

            $.get(dataurl, { editbranch: true, id: branch })
                .done(function (data) {
                    let d = JSON.parse(data);
                    console.log(d);
                    $("#branch_edit_id_input").val(d.id);
                    $("#br_edit_input").val(d.name);
                    $("#br_loc_edit_input").val(d.location);
                    $('#branch_edit_modal').modal('show');
                })
                .fail(function (e) {
                    console.log(e);
                })
        });

        $(document).on('submit', '#branch_edit_form', async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            await $.ajax({
                method: "POST",
                url: configurl,
                dataType: 'json',
                data: $(this).serialize() + "&branchedit=true"
            })
                .done(async function (d) {
                    let table = await append('branches');
                    if (table) {
                        $('#branch_edit_conf_modal').modal('hide');
                        $('#branch_table_alert').html(d.success).fadeIn(750).delay(5000).fadeOut(2000);
                    } else {
                        alert("Append returned false. Please refresh browser.");
                    }
                })
                .fail(async function (e) {
                    console.log(e);
                    let err = typeof e.responseJSON == 'undefined' ? e.responseText : e.responseJSON.error;
                    $('#branch_edit_alert').html(err).fadeIn(750).delay(5000).fadeOut(2000);
                })
        })

        $('#branchesform').on('change', '#checkallbranch', function () {
            let chk = $("#checkallbranch").prop('checked');
            $("tbody#branches tr td input[type='checkbox']").prop('checked', chk);
        });

        async function branch_delete(data) {
            return await $.ajax({
                method: "POST",
                url: configurl,
                dataType: 'json',
                data: data
            })
                .done(async function (d) {
                    let table = await append('branches');
                    if (table) {
                        $('#branch_del_modal').modal('hide');
                        $('#branch_table_alert').html(d.success).fadeIn(750).delay(5000).fadeOut(2000);
                        return true;
                    } else {
                        alert("Append returned false. Please refresh browser.");
                        return false;
                    }
                })
                .fail(async function (e) {
                    console.log(e);
                    let err = typeof e.responseJSON == 'undefined' ? e.responseText : e.responseJSON.error;
                    $('#branch_del_alert').html(err).fadeIn(750).delay(5000).fadeOut(2000);
                })
        }

        $(document).on('submit', '#branch_del_form', async function (e) {
            e.preventDefault();
            if ($("#branchesform").serialize().length() == 0) {
                alert('No selected row.');
            } else {
                console.log($(this).serialize() + "&" + $('#branchesform').serialize());
                let data = $(this).serialize() + "&" + $('#branchesform').serialize() + "&branchdelete=true";
                let del = await branch_delete(data);

                if (del) {

                }
            }

        })


    </script>
</body>

</html>