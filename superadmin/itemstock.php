<?php
require("startsession.php");
// require("/tablecontents/tables.php");
include('tablecontents/tables.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Inventory</title>
    <?php include('header.links.php'); ?>
    <style>
        table#approvechemtable tr td,
        table#approvechemtable tr th {
            background-color: unset !important;
            color: unset !important;
            padding: 1.25rem !important;
        }

        table#approvechemtable tr th {
            padding: 0.75rem 0.5rem;
        }

        table#approvechemtable tr td button {
            color: unset !important;
        }
    </style>
</head>

<body class="bg-official text-light">

    <div class="sa-bg container-fluid p-0 min-vh-100 d-flex">
        <!-- sidebar -->
        <?php include('sidenav.php'); ?>
        <!-- main content -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php include('navbar.php'); ?>
            <!-- content -->
            <div class="bg-light bg-opacity-25 pt-2 rounded p-3 mx-3 mt-3 mb-2">
                <h1 class="display-6 text-light mb-0 fw-medium text-center">Chemical Inventory</h1>
            </div>
            <div class="d-flex gap-3 mb-2 mx-3">
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <p class="fs-5 fw-bold align-middle"><i
                            class="bi bi-archive-fill me-2 bg-success bg-opacity-25 py-1 px-2 rounded shadow-sm align-middle"></i>Total
                        Chemicals
                    </p>
                    <p class="fw-light mb-0 mt-4">Total chemicals in stock.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_total"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <p class="fs-5 fw-bold"><i
                            class="bi bi-exclamation-triangle-fill me-2 bg-warning bg-opacity-25 py-1 px-2 rounded shadow-sm align-middle"></i>Low
                        Level Chemicals
                    </p>
                    <p class="fw-light mb-0 mt-4">Number of chemicals running low.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_low"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <p class="fs-5 fw-bold"><i
                            class="bi bi-calendar-x-fill me-2 bg-danger bg-opacity-25 py-1 px-2 rounded shadow-sm align-middle"></i>Expired
                        Chemicals
                    </p>
                    <p class="fw-light mb-0 mt-4">Number of chemicals past their expiration date.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_expired"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <p class="fs-5 fw-bold"><i
                            class="bi bi-clock-fill me-2 bg-info bg-opacity-25 py-1 px-2 rounded shadow-sm align-middle"></i>Pending
                        Entries
                    </p>
                    <p class="fw-light mb-0 mt-4">Number of pending chemical entries.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_entries"></p>
                </div>
            </div>


            <div class="hstack gap-3 my-3 mx-3">
                <button type="button" id="hideentries"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded py-2 w-25 px-2 text-light"
                    title="Hide Entries"><i class="bi bi-eye-slash me-2"></i><span id="hideEnText">Hide
                        Entries</span></button>
                <select
                    class="form-select select-transparent bg-light bg-opacity-25 py-2 border-0 h-100 text-light w-25"
                    id="sortbranches" aria-label="Default select example">
                </select>
                <input class="form-control form-custom rounded-pill me-auto py-2 px-3 text-light" type="search"
                    placeholder="Search . . ." id="searchbar" name="searchforafuckingchemical"
                    autocomplete="one-time-code">
                <button type="button" id="approvemulti"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded py-2 px-4 text-light" data-bs-toggle="modal"
                    data-bs-toggle="tooltip" data-bs-target="#multiapproveModal" title="Approve multiple stocks"><i
                        class="bi bi-list-check"></i></button>
                <!-- <div class="vr"></div> -->
                <button type="button" id="loadChem"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded text-light py-2 px-4" data-bs-toggle="modal"
                    data-bs-target="#addModal" data-bs-toggle="tooltip" title="Add Stock"><i
                        class="bi bi-plus-square"></i></button>

            </div>

            <!-- edit chemical -->
            <form id="editChemForm">
                <div class="row g-2 text-dark">
                    <div class="modal-lg modal fade text-dark modal-edit" id="editModal" data-bs-backdrop="static"
                        tabindex="-1" aria-labelledby="edit" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">View/Edit Chemical Details</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <!-- <input type="hidden" name="edit" value="edit-chemical"> -->
                                    <input type="hidden" name="edit-id" id="edit-id" class="form-control">
                                    <div class="row mb-2">

                                        <div class="col-lg-4 mb-2">
                                            <label for="edit-name" class="form-label fw-light">Chemical Name:</label>
                                            <input type="text" name="edit-name" id="edit-name"
                                                class="ps-2 form-control-plaintext" readonly autocomplete="off">
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="edit-chemBrand" class="form-label fw-light">Chemical
                                                Brand:</label>
                                            <input type="text" name="edit-chemBrand" id="edit-chemBrand"
                                                class="ps-2 form-control-plaintext" readonly autocomplete="off">
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="edit-chemLevel" class="form-label fw-light">Chemical Level:
                                            </label>
                                            <input type="number" name="edit-chemLevel" id="edit-chemLevel"
                                                class="ps-2 form-control-plaintext" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-4 mb-2">
                                            <label for="edit-dateReceived" class="form-label fw-light">Date
                                                Received:</label>
                                            <input type="date" name="edit-receivedDate" id="edit-dateReceived"
                                                class="ps-2 form-control-plaintext form-add form-date" disabled>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="edit-expDate" class="form-label fw-light">Expiry Date:</label>
                                            <input type="date" name="edit-expDate" id="edit-expDate"
                                                class="ps-2 form-control-plaintext form-date" autocomplete="off"
                                                disabled>
                                            <p class="fw-light text-center alert alert-warning py-1 px-3 d-none"
                                                id="expdatewarning"></p>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <label for="edit-notes" class="form-label fw-light">Short Note:</label>
                                            <textarea name="edit-notes" id="edit-notes" style="resize: none !important;"
                                                class="ps-2 form-control-plaintext" readonly></textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <span class="text-body-secondary text-muted" id="addinfo"></span>
                                        <span class="text-body-secondary text-muted" id="updateinfo"></span>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" onclick="toggle()" class="btn btn-grad"
                                        id="toggleEditBtn">Edit</button>
                                    <button type="button" class="btn btn-grad d-none" id="submitEdit"
                                        data-bs-target="#confirmEdit" data-bs-toggle="modal">Proceed</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- edit confirmation -->
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="confirmEdit" tabindex="0"
                    aria-labelledby="verifyChanges" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyChanges">Save Changes</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="pass" class="form-label fw-light">Change information? Enter manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control" id="pwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-1 w-50 mx-auto my-0 visually-hidden'
                                    id="incPass"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#editModal"
                                    data-bs-toggle="modal">Go back</button>
                                <button type="submit" class="btn btn-grad" id="saveChanges" disabled-id="edit-confirm"
                                    disabled-name="delete">Edit Chemical</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- add new chemical -->
            <form id="addForm">
                <div class="row g-2 text-dark">
                    <div class="modal-lg modal fade text-dark modal-edit" data-bs-backdrop="static" id="addModal"
                        tabindex="-1" aria-labelledby="create" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Add New Chemical</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <!-- <input type="hidden" name="id" id="id" class="form-control"> -->
                                    <div class="row mb-2">
                                        <div class="col-lg-4 mb-2">
                                            <label for="name" class="form-label fw-light">Chemical Name</label>
                                            <input type="text" name="name[]" id="add-name" class="form-control form-add"
                                                autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="chemBrand" class="form-label fw-light">Chemical Brand</label>
                                            <input type="text" name="chemBrand[]" id="add-chemBrand"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="chemLevel" class="form-label fw-light">Chemical Level </label>
                                            <input type="text" name="chemLevel[]" id="add-chemLevel"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-4 mb-2">
                                            <label for="expDate" class="form-label fw-light">Date Received</label>
                                            <input type="date" name="receivedDate[]" id="add-dateReceived"
                                                class="form-control form-add form-date">
                                            <div class="text-body-secondary fw-light text-muted mt-2">
                                                Note: if not specified, date received will set the date today.
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="expDate" class="form-label fw-light">Expiry Date</label>
                                            <input type="date" name="expDate[]" id="add-expDate"
                                                class="form-control form-add form-date">
                                            <div class="text-body-secondary fw-light text-muted mt-2">
                                                Note: specify expiry date or default date will be set.
                                            </div>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <label for="notes" class="form-label fw-light">Short Note</label>
                                            <textarea name="notes[]" id="notes" class="form-control"
                                                placeholder="Optional short note . . . "></textarea>
                                        </div>
                                    </div>
                                    <div id="addMoreChem"></div>
                                    <hr class="mt-2 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-grad" id="addMoreChemBtn"><i
                                                class="bi bi-plus-circle-dotted  me-2"></i> Add More
                                            Chemical</button>
                                        <input type="checkbox" class="btn-check" name="approveCheck" id="add-approved"
                                            autocomplete="off">
                                        <label class="btn btn-outline-dark d-inline-flex align-content-center py-2"
                                            for="add-approved"><i id="flaskApproveAll"
                                                class="bi bi-flask me-2"></i>Approve All
                                            Chemicals</label>
                                    </div>


                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                        data-bs-target="#areyousureaboutthat">Cancel</button>
                                    <button type="button" class="btn btn-grad" disabled-id="submitAdd"
                                        data-bs-toggle="modal" data-bs-target="#confirmAdd">Proceed & Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade text-dark modal-edit" id="areyousureaboutthat" data-bs-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title">
                                <h1 class="modal-title fs-5 text-light">Cancel New Chemical Stock Entry?</h1>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to stop this chemical entry?
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-grad" data-bs-target="#addModal"
                                    data-bs-toggle="modal">Go Back</button>
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel Entry</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- add confirmation -->
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="confirmAdd" tabindex="0"
                    aria-labelledby="confirmAdd" aria-hidden="true">
                    <!-- <input type="hidden" id="idForDeletion" name="id">
                <input type="hidden" id="delChemId" name="chemid"> -->
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyAdd">Add New Chemical</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="pass" class="form-label fw-light">Add Chemical? Enter manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control" id="addPwd">
                                    </div>
                                </div>
                                <p class="text-center alert alert-info w-75 mx-auto d-none" id="aea">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#addModal"
                                    data-bs-toggle="modal">Go
                                    back</button>
                                <button type="submit" class="btn btn-grad" id="submitAdd" disabled-id="edit-confirm"
                                    disabled-name="delete">Add New Chemical</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- delete modal -->
            <form id="deleteForm">
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="deleteModal" tabindex="0"
                    aria-labelledby="verifyChanges" aria-hidden="true">
                    <input type="hidden" id="idForDeletion" name="id">
                    <input type="hidden" id="delChemId" name="chemid">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyChanges">Chemical Deletion</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="pass" class="form-label fw-light">Are you sure you want to
                                        delete this product? Enter manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control" id="manPass">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    Note: deletion of chemicals are irreversible.
                                </div>
                                <p class="text-center alert alert-info w-75 mx-auto visually-hidden"
                                    id="del-emptyInput">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-grad" id="delsub" name="delete">Delete
                                    Chemical</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="multiapprove">
                <div class="modal modal-lg fade text-dark modal-edit" data-bs-backdrop="static" id="multiapproveModal"
                    tabindex="0" aria-labelledby="confirmAdd" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title">
                                <h1 class="modal-title fs-5 text-light">Stock Entries</h1>
                                <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x"></i></button>
                            </div>

                            <div class="modal-body text-dark p-3">
                                <div class="table-responsive-sm  d-flex justify-content-center">
                                    <table class="table align-middle table-hover w-100" id="approvechemtable">
                                        <caption class="fw-light text-muted">List of stocks added by the Operations
                                            Supervisor.</captio>
                                        <thead>
                                            <tr class="text-center align-middle">
                                                <th scope="col">Name</th>
                                                <th>Brand</th>
                                                <th>Level</th>
                                                <th>Expiry Date</th>
                                                <th>
                                                    <input type="checkbox" class="btn-check" id="checkall"
                                                        autocomplete="off">
                                                    <label class="btn fw-bold" for="checkall">Check All <i
                                                            id="checkicon" class="bi bi-square ms-2"></i></label>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody id="approve-chemtable" class="table-group-divider">
                                            <!-- ajax chem table -->
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#confirmmultiapprove">Continue</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="confirmmultiapprove"
                    tabindex="0">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Stock Entry Approval</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="confirmapprove-inputpwd" class="form-label fw-light">Approve all stock?
                                        Enter manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control"
                                            id="confirmapprove-inputpwd">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    Note: Approving stock requests will officially make the chemicals a part of the
                                    inventory.
                                </div>
                                <p class="text-center alert alert-info w-75 mx-auto visually-hidden"
                                    id="approvemulti-errmsg">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#multiapproveModal">Go Back</button>
                                <button type="submit" class="btn btn-grad" id="approvemultichem">Approve
                                    Stocks</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- single approval modal | acts as confirmation modal (?) -->
            <form id="confirmapprove">
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="approveModal" tabindex="0">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyChanges">Stock Approval</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="approve-inputpwd" class="form-label fw-light">Approve Stock <span
                                            id="chemname"></span>? Enter manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="hidden" name="id" id="approve-id">
                                        <input type="password" name="saPwd" class="form-control" id="approve-inputpwd">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    Note: Approving stock entries will officially make the stocks a part of the
                                    inventory.
                                </div>
                                <p class="text-center alert alert-info w-75 mx-auto visually-hidden"
                                    id="approve-errmsg">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-grad" id="approvechem">Approve
                                    Chemical</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- tabble -->
            <div class="table-responsive-sm d-flex mx-3 justify-content-center">

                <table class="table align-middle table-hover os-table mx-0 w-100 text-light">
                    <caption>Chemicals with <i class="bi bi-exclamation-diamond"></i> are stock entries made by
                        Operation Supervisors and is required to be reviewed.</caption>
                    <thead>
                        <tr class="text-center">
                            <th scope="col">Name</th>
                            <th>Brand</th>
                            <th>Current Level</th>
                            <th>Remaining Containers</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="chemicalTable">
                        <!-- ajax chem table -->
                    </tbody>

                </table>
            </div>
            <div class="d-flex justify-content-center mb-5" style="display: none !important;" id="loader">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div id="pagination"></div>

            <p class='text-center alert alert-success w-25 mx-auto' style="display: none !important;" id="tableAlert">
            </p>

        </main>

    </div>

    <?php include('footer.links.php'); ?>

    <script>
        const pageurl = 'tablecontents/pagination.php';
        const dataurl = 'tablecontents/chemicals.php';


        let d = $("input.form-date");

        function flatpickrdate(d) {
            flatpickr(d, {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "F j, Y",
            });
        }


        $(document).on('shown.bs.modal', "#addModal", function() {
            flatpickrdate(d);
            $("#addMoreChem").empty();
            $("#addForm")[0].reset();
        });

        $(document).on('click', '.remove-btn', function() {
            $(this).parent().parent().remove();
        })

        $(document).on('click', '#addMoreChemBtn', async function() {
            $.get(dataurl, "addrow=true")
                .done(function(data) {
                    $('#addMoreChem').append(data);
                    flatpickr("#addMoreChem input.form-date", {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "F j, Y",
                    });
                })
                .fail(function(e, s, em) {
                    console.log(e);
                });
        });


        $(document).on('change', '#add-approved', function() {
            // let flask = $('#flaskApproveAll');
            if ($(this).is(':checked')) {
                $("#flaskApproveAll").removeClass('bi-flask');
                $("#flaskApproveAll").addClass('bi-flask-fill');
            } else {
                $("#flaskApproveAll").removeClass('bi-flask-fill');
                $("#flaskApproveAll").addClass('bi-flask');
            }
        });

        // const addexpdatee = document.getElementBy

        $(document).on('click', '#approvemulti', async function() {
            $('#multiapprove')[0].reset();
            const reqlist = await stock_requests();
            if (reqlist) {
                $('#multiapproveModal').modal('show');
            }
        });

        $(document).on('change', '#checkall', function() {
            $('#checkicon').toggleClass('bi-square bi-check-square');
            var checked = $(this).prop('checked');
            $('tbody tr td div input[type="checkbox"]').prop('checked', checked);
        });

        async function stock_requests() {
            try {
                const stock = await $.ajax({
                    method: 'GET',
                    url: dataurl,
                    dataType: 'html',
                    data: '&stock=true'
                });

                if (stock) {
                    $('#approve-chemtable').empty();
                    $('#approve-chemtable').append(stock);
                } else {
                    alert('append err');
                }
            } catch (error) {
                console.log(error);
            }
        }

        $(document).on('submit', '#multiapprove', async function(e) {
            e.preventDefault();
            console.log($(this).serialize());
            try {
                const approve = await $.ajax({
                    method: 'POST',
                    url: dataurl,
                    dataType: 'json',
                    data: $(this).serialize() + '&approvemultiple=true'
                });

                if (approve) {
                    console.log(approve);
                    console.log(approve.success);
                    $('#tableAlert').css('display', 'block').html(approve.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                    await loadpage(1);
                    $('#confirmmultiapprove').modal('hide');
                }
            } catch (error) {
                let err = error.responseJSON;
                console.log(error.responseText);
                switch (err.error) {
                    case 'emptyfield':
                        $('input#confirmapprove-inputpwd').addClass('border border-warning-subtle').fadeIn(400);
                        $('#approvemulti-errmsg').removeClass('visually-hidden').html(err.msg).hide().fadeIn(400).delay(1500).fadeOut(1000);
                        break;
                    case 'wrongpwd':
                        $('input#confirmapprove-inputpwd').addClass('border border-danger-subtle').fadeIn(400);
                        $('#approvemulti-errmsg').removeClass('visually-hidden').html(err.msg).hide().fadeIn(400).delay(1500).fadeOut(1000);
                        break;
                    case 'function':
                        // console.log(error.responseJSON.pwd);
                        $('input#confirmapprove-inputpwd').addClass('border border-danger-subtle').fadeIn(400);
                        $('#approvemulti-errmsg').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(1500).fadeOut(1000);
                        break;
                    default:
                        alert('unknown error. Please contact administration.');
                        break;
                }
            }
        });


        $(document).on('click', '#approvebtn', async function() {
            $('#confirmapprove')[0].reset();
            let chemId = $(this).data('id');
            let name = $(this).data('name');
            $("#approve-id").val(chemId);
            $('#chemname').html(name);
        });

        $(document).on('submit', '#confirmapprove', async function(e) {
            e.preventDefault();
            console.log($(this).serialize());
            try {
                const approve = await $.ajax({
                    method: 'POST',
                    dataType: 'json',
                    url: dataurl,
                    data: $(this).serialize() + '&approve=true'
                });

                if (approve) {
                    console.log(approve.success);
                    $('#tableAlert').css('display', 'block').html(approve.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                    await loadpage(1);
                    $('#approveModal').modal('hide');
                }
            } catch (error) {
                let err = error.responseJSON;
                console.log(error.responseText);
                switch (err.error) {
                    case 'emptyfield':
                        $('input#approve-inputpwd').addClass('border border-warning-subtle').fadeIn(400);
                        $('#approve-errmsg').removeClass('visually-hidden').html(err.msg).hide().fadeIn(400).delay(1500).fadeOut(1000);
                        break;
                    case 'wrongpwd':
                        $('input#approve-inputpwd').addClass('border border-danger-subtle').fadeIn(400);
                        $('#approve-errmsg').removeClass('visually-hidden').html(err.msg).hide().fadeIn(400).delay(1500).fadeOut(1000);
                        break;
                    case 'function':
                        // console.log(error.responseJSON.pwd);
                        $('input#approve-inputpwd').addClass('border border-danger-subtle').fadeIn(400);
                        $('#approve-errmsg').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(1500).fadeOut(1000);
                        break;
                    default:
                        alert('unknown error. Please contact administration.');
                        break;
                }

            }
        });

        async function loadpage(page, entryHidden = false, branch = null) {
            await loadtable(page, entryHidden, branch);
            await loadpagination(page, entryHidden, branch);
        }


        $(document).ready(async function() {
            get_sa_id();
            await loadpage(1);

            $.get(dataurl, {
                    branchoptions: true
                })
                .done(function(d) {
                    $("#sortbranches").append(d);
                })
                .fail(function(e) {
                    console.log('error appending branches option');
                })
        });


        function get_overview_count(container, branch) {
            $.get(dataurl, {
                    count: true,
                    status: container,
                    branch: branch
                })
                .done(function(d) {
                    console.log(d);
                    $(`#count_${container}`).empty();
                    $(`#count_${container}`).append(d);
                })
                .fail(function(e) {
                    console.log(e);
                })
        }

        function overview_display(branch = null) {
            get_overview_count('total', branch);
            get_overview_count('low', branch);
            get_overview_count('expired', branch);
            get_overview_count('entries', branch);
        }

        $(document).ready(function() {
            overview_display();
        });

        async function loadpagination(pageno, entries = false, branch = null) {
            try {
                return $.ajax({
                    type: 'GET',
                    url: pageurl,
                    data: {
                        pagenav: 'true',
                        active: pageno,
                        entries: entries,
                        branch: branch
                    },
                    success: async function(res) {
                        console.log(res);

                        $('#pagination').empty();
                        $('#pagination').append(res);
                        window.history.pushState(null, "", "?page=" + pageno);
                    }
                });

            } catch (error) {
                alert(error);
            }
        }

        async function loadtable(page = 1, hide_entries = false, branch = null) {
            $.ajax({
                type: 'GET',
                url: pageurl,
                data: {
                    table: 'true',
                    currentpage: page,
                    hideentries: hide_entries,
                    branch: branch
                },
                success: function(data) {
                    $('#chemicalTable').empty();
                    $('#chemicalTable').append(data);
                },
                error: function(err) {
                    alert('loadtable func error:' + err);
                }
            });

        }

        let entryHidden = false;
        async function hide_entries() {
            let branch = $("#sortbranches").val();
            if ($('#searchbar').length > 0) {
                $('#pagination').removeClass('d-none');
                $('#searchbar').val('');
            }
            entryHidden = !entryHidden ? true : false;
            await loadpage(1, entryHidden, branch)

            if (entryHidden) {
                $('#hideentries > i').removeClass('bi-eye-slash').addClass('bi-eye');
                $('#hideEnText').text('Show Entries');
            } else {
                $('#hideentries > i').removeClass('bi-eye').addClass('bi-eye-slash');
                $('#hideEnText').text('Hide Entries');
            }
            return entryHidden;
        }

        $(document).on('change', '#sortbranches', async function() {
            let branch = $(this).val();
            // console.log(branch);
            await loadpage(1, entryHidden, branch);
            $("#searchbar").val('');
            let pagination = $("#pagination");
            if (pagination.hasClass('d-none')) {
                $('#pagination').removeClass('d-none');
            }
            overview_display(branch);

        })



        $(document).on('click', '#hideentries', async function() {
            await hide_entries();
        });

        $('#pagination').on('click', '.page-link', async function(e) {
            e.preventDefault();

            let branch = $("#sortbranches").val();
            let currentpage = $(this).data('page');
            console.log(currentpage);

            // $('#chemicalTable').empty();
            window.history.pushState(null, "", "?page=" + currentpage, branch);
            // await loadtable(currentpage);

            // $('#pagination').empty();
            // await loadpagination(currentpage);
            await loadpage(currentpage, entryHidden, branch);
        })



        // search
        $(function() {
            let timeout = null;

            $('#searchbar').keyup(function() {
                let branch = $("#sortbranches").val();
                clearTimeout(timeout);
                $('#chemicalTable').empty();
                // $('#chemicalTable').append($('#loader'))
                // $('#loader').removeClass('visually-hidden');
                $('#loader').css('display', 'block');

                timeout = setTimeout(async function() {
                    var search = $('#searchbar').val();
                    try {
                        const searchChem = await $.ajax({
                            url: 'tablecontents/pagination.php',
                            type: 'GET',
                            dataType: 'html',
                            data: {
                                search: search,
                                entries: entryHidden,
                                branch: branch
                            },
                            success: async function(searchChem, status) {
                                if (!search == '') {
                                    $('#pagination').addClass('d-none');
                                    $('#chemicalTable').empty();
                                    // $('#loader').addClass('visually-hidden');
                                    $('#loader').attr('style', 'display: none !important;');
                                    $('#chemicalTable').append(searchChem);

                                } else {
                                    $('#loader').attr('style', 'display: none !important;');
                                    await loadpage(1, entryHidden, branch);
                                    $('#pagination').removeClass('d-none');
                                }
                            }
                        });

                    } catch (error) {
                        alert('Search Error');
                    }
                }, 250);
            });
        });



        function get_sa_id() {
            $.post(dataurl, {
                managerId: true
            }, function(data, status) {
                // console.log(data + ' status ' + status);
                $('#idForDeletion').val(data);
                // var saID = data;
                // console.log($('#idForDeletion').val());
                // console.log(saID);   
                // return saID; 
            })
        }

        // edit chemical
        $(document).on('submit', '#editChemForm', async function(e) {
            e.preventDefault();
            console.log($(this).serialize());
            try {
                const data = await $.ajax({
                    type: 'POST',
                    url: dataurl,
                    data: $(this).serialize() + '&action=edit',
                    dataType: 'json'
                });
                if (data.success) {
                    $("#chemicalTable").empty();
                    await loadpage(1);
                    $('#tableAlert').css('display', 'block').html(data.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                    $('#editChemForm')[0].reset();
                    $('#confirmEdit').modal('hide');
                } else {
                    alert("Invalid Response");
                }
            } catch (error) {
                let err = error.responseText;
                $('#incPass').removeClass('visually-hidden').html(err).hide().fadeIn(400).delay(1500).fadeOut(1000);

            }
        });


        async function delete_chem(id, pwd, chemId) {
            // var saID = $('#idForDeletion').val();
            // var saPass = $('#manPass').val();
            // var chemID = $('#delChemId').val();
            // console.log(chemID);
            try {
                const del = await $.ajax({
                    type: 'POST',
                    url: dataurl,
                    data: {
                        action: 'delete',
                        saID: id,
                        pass: pwd,
                        chemID: chemId,
                        // $('#addForm').serialize();
                    },
                    dataType: 'json'
                    // ,success: function (response) {
                    //     console.log(response);
                    //     $('#chemicalTable').empty();
                    //     get_data();
                    //     $('#deleteModal').modal('hide');
                    // }
                });

                if (del && del.success) {
                    $('#chemicalTable').empty();
                    await loadpage(1);
                    $('#deleteModal').modal('hide');
                    $('#deleteForm')[0].reset();
                }
            } catch (error) {
                switch (error.responseJSON.type) {
                    case 'delete':
                        console.log(error.responseJSON.error);
                        $('input#manPass').addClass('border border-warning').fadeIn(400);
                        $('#del-emptyInput').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    case 'pwd':
                        console.log(error.responseJSON.error);
                        $('input#manPass').addClass('border border-warning').fadeIn(400);
                        $('#del-emptyInput').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    case 'empty':
                        console.log(error.responseJSON.error);
                        $('input#manPass').addClass('border border-warning').fadeIn(400);
                        $('#del-emptyInput').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    default:
                        alert('unknown error.');
                        break;
                }
            }
        }

        // delete item
        $(document).on('click', '#delbtn', async function() {
            $('#deleteForm')[0].reset();
            get_sa_id();
            var chemID = $(this).data('id');
            // $('#manPass').prop('autocomplete', 'new-password');
            // $('#manPass').disableAutoFill();
            // $('#delChemId').val(chemID);
            var saID = $('#idForDeletion').val();
            $('#delsub').off('click').on('click', async function() {
                try {
                    var saPass = $('#manPass').val();
                    console.log(chemID + saID + saPass);
                    await delete_chem(saID, saPass, chemID);
                } catch (error) {
                    alert('Submit error. Try again.');
                    // alert(error);
                }
            })

        })

        $(document).on('submit', '#addForm', async function(e) {
            e.preventDefault();
            console.log($(this).serialize());
            try {
                const add = await $.ajax({
                    type: 'POST',
                    url: dataurl,
                    data: $(this).serialize() + '&action=add',
                    dataType: 'json'
                });
                // console.log('heelk');
                if (add.success) {
                    $('#chemicalTable').empty();
                    await loadpage(1);
                    $('#confirmAdd').modal('hide');
                    // $('#tableAlert').removeClass('visually-hidden').html(add.success).hide().fadeIn(400).delay(2000).fadeOut(1000).addClass('visually-hidden');
                    $('#tableAlert').css('display', 'block').html(add.success).hide().fadeIn(400).delay(2000).fadeOut(1000);

                    $('#addForm')[0].reset();
                    // success yah
                }
                // console.log('rire');

            } catch (error) {
                console.log(error);
                $('#addForm input').addClass('border border-warning').fadeIn(400);
                $('#aea').html(error.responseText).removeClass('d-none').fadeIn(400).delay(2000).fadeOut(1000);
            }
        });

        async function get_chem_details(id) {
            return $.get(dataurl, {
                    id: id,
                    chemDetails: 'true'
                })
                .done(function(d, s) {
                    console.log(d);
                    return d;
                })
                .fail(function(e) {
                    console.log(e);
                })
        }

        let editdates = ('#editChemForm input.form-date');
        flatpickr(editdates, {
            dateFormat: "Y-m-d"
        });

        let toggled = false;

        function toggle() {
            $('#submitEdit').toggleClass('d-none');
            $('#edit-notes, #edit-name, #edit-chemBrand, #edit-chemLevel').attr('readonly', function(i, a) {
                return a ? false : true;
            });
            $("#edit-expDate, #edit-dateReceived").attr('disabled', function(i, a) {
                return a ? false : true;
            });

            $("#toggleEditBtn").html(function(i, a) {
                return a.includes('Close Edit') ? 'Edit' : 'Close Edit';
            });
            $('#edit-notes, #edit-name, #edit-chemBrand, #edit-chemLevel, #edit-expDate, #edit-dateReceived').toggleClass('form-control-plaintext form-control');

            return toggled = toggled ? false : true;
        }

        // get specific chemical information when edit btn is clicked
        $(document).on('click', '.editbtn', async function() {
            $('#editChemForm')[0].reset();
            let id = $(this).data('chem');
            let deets = await get_chem_details(id);
            var details = JSON.parse(deets);
            console.log(details);

            $('#submitEdit, #toggleEditBtn').attr('disabled', function() {
                return details.req == 1 ? true : false;
            });

            var today = new Date();
            var exp = new Date(details.expDate);
            if (exp <= today) {
                $("#expdatewarning").html('Caution. Chemical Expired.').toggleClass('d-none');
            } else {
                $('#expdatewarning').html('').addClass('d-none');
            }

            $("#edit-id").val(details.id);
            $('#edit-name').val(details.name);
            $('#edit-chemBrand').val(details.brand);
            $('#edit-chemLevel').val(details.level);
            $('#edit-dateReceived').val(details.daterec);
            $('#edit-expDate').val(details.expDate);
            $('#edit-notes').val(details.notes);
            $('#addinfo').html(function() {
                return details.addby === 'No Record' ? 'Added at: ' + details.addat : 'Added at: ' + details.addat + ' by ' + details.addby;
            });
            $('#updateinfo').html(function() {
                return details.upby === 'No Update Record' ? 'Updated at: ' + details.upat : 'Updated at: ' + details.upat + ' by ' + details.upby;
            });

            if (toggled) {
                toggle();
            }

            $('#editModal').modal('show');

        });


        // $(document).on('click', '#toggleEditBtn', function () {
        //     toggle();
        // });
    </script>
</body>

</html>