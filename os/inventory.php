<?php
require("startsession.php");
// include('tablecontents/tables.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operations Supervisor | Chemicals</title>
    <!-- <link rel="stylesheet" href="../../css/style.css"> -->
    <?php include('header.links.php'); ?>

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
                    <p class="fw-light mb-0 mt-4">Number of chemicals 20% below full capacity.</p>
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
            </div>
            <div class="d-flex gap-3 mb-2 mx-3">
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <p class="fs-5 fw-bold"><i
                            class="bi bi-clock-fill me-2 bg-info bg-opacity-25 py-1 px-2 rounded shadow-sm align-middle"></i>Pending
                        Entries
                    </p>
                    <p class="fw-light mb-0 mt-4">Number of pending chemical entries.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_entries"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <p class="fs-5 fw-bold"><i
                            class="bi bi-check-circle-fill me-2 bg-success bg-opacity-50 py-1 px-2 rounded shadow-sm align-middle"></i>Available
                        Chemicals
                    </p>
                    <p class="fw-light mb-0 mt-4">Number of pending chemical entries.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_entries"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <p class="fs-5 fw-bold"><i
                            class="bi bi-truck-flatbed me-2 bg-secondary bg-opacity-50 py-1 px-2 rounded shadow-sm align-middle"></i>Chemicals
                        In Use
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
                <input class="form-control form-custom rounded-pill me-auto py-2 px-3 text-light"
                    placeholder="Search . . ." id="searchbar" name="search" autocomplete="one-time-code">
                <button type="button" id="inventorylogbtn"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded py-2 px-4 text-light"
                    title="Inventory Logs"><i class="bi bi-file-earmark-text"></i><span id="hideEnText"></button>
                <button type="button" id="loadChem"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded text-light py-2 px-4" data-bs-toggle="modal"
                    data-bs-target="#addModal" data-bs-toggle="tooltip" title="Add Stock"><i
                        class="bi bi-plus-square"></i></button>

            </div>


            <!-- inventory log modal -->
            <div class="modal modal-xl fade text-dark modal-edit" data-bs-backdrop="static"
                id="inventorylogmodal" tabindex="0">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title">
                            <h1 class="modal-title fs-5 text-light">Transactions Completion</h1>
                            <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>

                        <div class="modal-body text-dark p-3">
                            <div class="table-responsive-sm  d-flex justify-content-center">
                                <table class="table align-middle table-hover w-100" id="approvechemtable">
                                    <caption class="fw-light text-muted">List of recently finished transactions
                                        marked by technicians. Select the transaction number ID to view transaction.
                                    </caption>
                                    <thead>
                                        <tr class="text-center align-middle">
                                            <th class="text-dark" scope="col">Date & Time</th>
                                            <th class="text-dark">Activity Type</th>
                                            <th class="text-dark">Chemical</th>
                                            <th class="text-dark">Amount</th>
                                            <th class="text-dark">Performed By</th>
                                            <th class="text-dark">Transaction ID</th>
                                            <th class="text-dark">Notes</th>
                                        </tr>
                                    </thead>

                                    <tbody id="inventorylogtable" class="table-group-divider">
                                    </tbody>

                                </table>
                                <div id="inventorylogpaginationbtns"></div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                data-bs-target="#finalizeconfirm">Continue</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal modal-xl fade text-dark modal-edit" data-bs-backdrop="static"
                id="chemicallogmodal" tabindex="0">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title">
                            <h1 class="modal-title fs-5 text-light">Chemical Log</h1>
                            <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>

                        <div class="modal-body text-dark p-3">
                            <div class="table-responsive-sm  d-flex justify-content-center">
                                <table class="table align-middle table-hover w-100">
                                    <!-- <caption class="fw-light text-muted">List of recently finished transactions
                                        marked by technicians. Select the transaction number ID to view transaction.
                                    </caption> -->
                                    <thead>
                                        <tr class="text-center align-middle">
                                            <th class="text-dark" scope="col">Date & Time</th>
                                            <th class="text-dark">Activity Type</th>
                                            <th class="text-dark">Amount</th>
                                            <th class="text-dark">Performed By</th>
                                            <th class="text-dark">Transaction ID</th>
                                            <th class="text-dark">Notes</th>
                                        </tr>
                                    </thead>

                                    <tbody id="loghistorytable" class="table-group-divider">
                                    </tbody>

                                </table>
                                <div id="loghistorypagination"></div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                data-bs-target="#finalizeconfirm">Continue</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- edit chemical -->
            <form id="editChemForm">
                <div class="row g-2 text-dark">
                    <div class="modal-lg modal fade text-dark modal-edit" id="editModal" tabindex="-1"
                        aria-labelledby="edit" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Edit Chemical Details</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <!-- <input type="hidden" name="edit" value="edit-chemical"> -->
                                    <input type="hidden" name="edit-id" id="edit-id" class="form-control">
                                    <div class="row mb-2">

                                        <div class="col-lg-3 mb-2">
                                            <label for="edit-name" class="form-label fw-light">Chemical Name:</label>
                                            <input type="text" name="edit-name" id="edit-name"
                                                class="ps-2 form-control-plaintext" readonly autocomplete="off">
                                        </div>
                                        <div class="col-lg-3 mb-2">
                                            <label for="edit-chemBrand" class="form-label fw-light">Chemical
                                                Brand:</label>
                                            <input type="text" name="edit-chemBrand" id="edit-chemBrand"
                                                class="ps-2 form-control-plaintext" readonly autocomplete="off">
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="edit-chemLevel" class="form-label fw-light">Chemical Level:
                                            </label>
                                            <input type="number" name="edit-chemLevel" id="edit-chemLevel"
                                                class="ps-2 form-control-plaintext" readonly>
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="edit-contSize" class="form-label fw-light">Container
                                                Size</label>
                                            <input type="number" name="edit-containerSize" id="edit-contSize"
                                                class="form-control-plaintext" readonly autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="edit-containerCount"
                                                class="form-label fw-light">Containers</label>
                                            <input type="number" name="edit-containerCount" id="edit-containerCount"
                                                class="form-control-plaintext" readonly autocomplete="one-time-code">
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

                                    <p class="col fw-light alert alert-primary py-3 w-50 mx-auto d-none text-center"
                                        id="reqalert"></p>
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
                <div class="modal fade text-dark modal-edit" id="confirmEdit" tabindex="0"
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
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="baPwd" class="form-control" id="pwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-1 py-3 w-50 mx-auto my-0'
                                    style="display: none !important;" id="incPass"></p>
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
                                        <div class="col-lg-3 mb-2">
                                            <label for="name" class="form-label fw-light">Chemical Name</label>
                                            <input type="text" name="name[]" id="add-name" class="form-control form-add"
                                                autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-3 mb-2">
                                            <label for="chemBrand" class="form-label fw-light">Chemical Brand</label>
                                            <input type="text" name="chemBrand[]" id="add-chemBrand"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="chemLevel" class="form-label fw-light text-nowrap">Current
                                                Chemical Level</label>
                                            <input type="text" name="chemLevel[]" id="add-chemLevel"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="chemLevel" class="form-label fw-light">Container Size</label>
                                            <input type="text" name="containerSize[]" id="add-chemLevel"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="chemLevel" class="form-label fw-light">Container Count</label>
                                            <input type="text" name="containerCount[]" id="add-chemLevel"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-3 mb-2">
                                            <label for="expDate" class="form-label fw-light">Date Received</label>
                                            <input type="date" name="receivedDate[]" id="add-dateReceived"
                                                class="form-control form-add form-date-rec">
                                            <div class="text-body-secondary fw-light text-muted mt-2">
                                                Note: if not specified, date received will set the date today.
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-2">
                                            <label for="expDate" class="form-label fw-light">Expiry Date</label>
                                            <input type="date" name="expDate[]" id="add-expDate"
                                                class="form-control form-add form-date-exp">
                                            <div class="text-body-secondary fw-light text-muted mt-2">
                                                Note: specify expiry date or default date will be set.
                                            </div>
                                        </div>
                                        <div class="col-3 mb-2">
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
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="baPwd" class="form-control" id="addPwd">
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
                <input type="hidden" id="delChemId" name="chemid">
                <div class="modal fade text-dark modal-edit" id="deleteModal" tabindex="0"
                    aria-labelledby="verifyChanges" aria-hidden="true">
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
                                        delete this product? Enter Branch Admin
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="baPwd" class="form-control" id="manPass">
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
                                <button type="submit" class="btn btn-grad" id="delsub">Delete
                                    Chemical</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- tabble -->
            <div class="table-responsive-sm d-flex justify-content-center">
                <table class="table align-middle table-hover m-4 os-table w-100 text-light">
                    <caption>Chemicals with <i class="bi bi-exclamation-diamond"></i> are pending for approval by the
                        Manager.</caption>
                    <thead>
                        <tr class="text-center text-nowrap">
                            <th scope="col">Name</th>
                            <th>Brand</th>
                            <th>Current Level</th>
                            <th>Remaining Container/s</th>
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
        const urldata = 'contents/inv.data.php';
        const urlpage = 'contents/inv.pagination.php';

        $(document).ready(async function () {
            // get_data();
            // get_id();
            get_sa_id();
            await loadpage(1);
        });

        async function loadpagination(pageno, entries = false) {
            try {
                return $.ajax({
                    type: 'GET',
                    url: urlpage,
                    data: {
                        pagenav: 'true',
                        active: pageno,
                        entries: entries
                    },
                    success: async function (res) {
                        $('#pagination').empty();
                        $('#pagination').append(res);
                        window.history.pushState(null, "", "?page=" + pageno);
                    }
                });

            } catch (error) {
                alert(error);
            }
        }

        async function loadtable(page, hide_entries = false) {
            try {
                return $.ajax({
                    type: 'GET',
                    url: urlpage,
                    data: {
                        table: 'true',
                        currentpage: page,
                        hideentries: hide_entries
                    },
                    success: function (data) {
                        $('#chemicalTable').empty();
                        $('#chemicalTable').append(data);
                    },
                    error: function (err) {
                        alert('loadtable func error:' + err);
                    }
                });

            } catch (error) {
                alert(error);
            }

        }

        async function loadpage(page, entryHidden = false) {
            await loadtable(page, entryHidden);
            await loadpagination(page, entryHidden);
        }

        let entryHidden = false;
        async function hide_entries() {
            if ($('#searchbar').length > 0) {
                $('#pagination').removeClass('d-none');
                $('#searchbar').val('');
            }
            entryHidden = !entryHidden ? true : false;
            await loadpage(1, entryHidden)

            if (entryHidden) {
                $('#hideentries > i').removeClass('bi-eye-slash').addClass('bi-eye');
                $('#hideEnText').text('Show Entries');
            } else {
                $('#hideentries > i').removeClass('bi-eye').addClass('bi-eye-slash');
                $('#hideEnText').text('Hide Entries');
            }
            return entryHidden;
        }

        $(document).on('click', '#hideentries', async function () {
            await hide_entries();
        });

        $('#pagination').on('click', '.page-link', async function (e) {
            e.preventDefault();

            let currentpage = $(this).data('page');
            console.log(currentpage);

            // $('#chemicalTable').empty();
            window.history.pushState(null, "", "?page=" + currentpage);
            // await loadtable(currentpage);

            // $('#pagination').empty();
            // await loadpagination(currentpage);
            await loadpage(currentpage, entryHidden);
        })



        // search
        $(function () {
            let timeout = null;

            $('#searchbar').keyup(function () {
                clearTimeout(timeout);
                $('#chemicalTable').empty();
                // $('#chemicalTable').append($('#loader'))
                // $('#loader').removeClass('visually-hidden');
                $('#loader').css('display', 'block');

                timeout = setTimeout(async function () {
                    var search = $('#searchbar').val();
                    try {
                        const searchChem = await $.ajax({
                            url: urlpage,
                            type: 'GET',
                            dataType: 'html',
                            data: {
                                search: search,
                                entries: entryHidden
                            },
                            success: async function (searchChem, status) {
                                if (!search == '') {
                                    $('#pagination').addClass('d-none');
                                    $('#chemicalTable').empty();
                                    // $('#loader').addClass('visually-hidden');
                                    $('#loader').attr('style', 'display: none !important;');
                                    $('#chemicalTable').append(searchChem);
                                } else {
                                    $('#loader').attr('style', 'display: none !important;');
                                    await loadpage(1, entryHidden);
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
            $.post(urldata, {
                managerId: true
            }, function (data, status) {
                $('#idForDeletion').val(data);
            })
        }

        // edit chemical
        $(document).on('submit', '#editChemForm', async function (e) {
            e.preventDefault();
            // console.log($(this).serialize());
            try {
                const data = await $.ajax({
                    type: 'POST',
                    url: urldata,
                    data: $(this).serialize() + '&action=edit',
                    dataType: 'json'
                });
                if (data.success) {
                    $("#chemicalTable").empty();
                    await loadpage(1, entryHidden);
                    $('#confirmEdit').modal('hide');
                    $('#tableAlert').css('display', 'block').html(data.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                    $('#editChemForm')[0].reset();
                }
            } catch (error) {
                // console.log(error);
                let err = typeof error.responseJSON === 'undefined' ? error.responseText : error.responseJSON;
                $('#incPass').html(err).fadeIn(750).delay(2000).fadeOut(1000);
            }
        })




        flatpickr("#add-receivedDate form-date-exp", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            minDate: 'today'
        });
        flatpickr("#add-receivedDate form-date-rec", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            maxDate: 'today'
        });


        $(document).on('click', '.remove-btn', function () {
            $(this).parent().parent().remove();
        })

        $(document).on('click', '#addMoreChemBtn', async function () {
            $.get(urldata, "addrow=true")
                .done(function (data) {
                    $('#addMoreChem').append(data);
                    flatpickr("#addMoreChem input.form-date-exp", {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "F j, Y",
                        minDate: 'today'
                    });
                    flatpickr("#addMoreChem input.form-date-rec", {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "F j, Y",
                        maxDate: 'today'
                    });
                })
                .fail(function (e, s, em) {
                    console.log(e);
                });
        });

        $(document).on('click', "#loadChem", function () {
            // flatpickrdate(d);
            $("#addMoreChem").empty();
            $("#addForm")[0].reset();
        });


        $(document).on('click', '.delbtn', function () {
            $('#deleteForm')[0].reset();
            $('#delChemId').val($(this).data('id'));
            $('#deleteModal').modal('show');
        });


        $(document).on('submit', '#deleteForm', async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            $.ajax({
                type: 'POST',
                url: urldata,
                data: $(this).serialize() + '&action=delete',
                dataType: 'json'
            }).done(function (d) {
                // console.log(d);
                if (d.success) {
                    $('#chemicalTable').empty();
                    loadpage(1, entryHidden);
                    $('#deleteModal').modal('hide');
                    $('#tableAlert').html(d.success).fadeIn(400).delay(2000).fadeOut(1000);
                    $('#deleteForm')[0].reset();
                }
            }).fail(function (e) {
                // console.log(e);
                $('#manPass').addClass('border border-warning').fadeIn(400);
                $('#del-emptyInput').removeClass('visually-hidden').html(e.responseText).hide().fadeIn(400).delay(2000).fadeOut(1000);
            })
        });



        $(document).on('submit', '#addForm', async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            try {
                const add = await $.ajax({
                    type: 'POST',
                    url: urldata,
                    data: $(this).serialize() + '&action=add',
                    dataType: 'json'
                });
                if (add.success) {
                    $('#chemicalTable').empty();
                    await loadpage(1, entryHidden);
                    $('#confirmAdd').modal('hide');
                    $('#tableAlert').html(add.success).fadeIn(400).delay(2000).fadeOut(1000);
                    $('#addForm')[0].reset();
                }

            } catch (error) {
                console.log(error);
                $('#addForm input').addClass('border border-warning').fadeIn(400);
                $('#aea').html(error.responseText).removeClass('d-none').fadeIn(400).delay(2000).fadeOut(1000);
            }
        });

        async function get_chem_details(id) {
            return $.get(urldata, {
                id: id,
                chemDetails: 'true'
            })
                .done(function (d, s) {
                    console.log(d);
                    return d;
                })
                .fail(function (e) {
                    console.log(e);
                })
        }

        flatpickr("#edit-dateReceived, #add-dateReceived", {
            dateFormat: "Y-m-d",
            maxDate: 'today'
        });

        flatpickr("#edit-expDate, #add-expDate", {
            dateFormat: "Y-m-d",
            minDate: 'today'
        })

        let toggled = false;

        function toggle() {
            $('#submitEdit').toggleClass('d-none');
            $('#edit-notes, #edit-name, #edit-chemBrand, #edit-chemLevel, #edit-contSize, #edit-containerCount').attr('readonly', function (i, a) {
                return a ? false : true;
            });
            $("#edit-expDate, #edit-dateReceived").attr('disabled', function (i, a) {
                return a ? false : true;
            });

            $("#toggleEditBtn").html(function (i, a) {
                return a.includes('Close Edit') ? 'Edit' : 'Close Edit';
            });
            $('#edit-notes, #edit-name, #edit-chemBrand, #edit-chemLevel, #edit-expDate, #edit-dateReceived, #edit-contSize, #edit-containerCount').toggleClass('form-control-plaintext form-control');

            return toggled = toggled ? false : true;
        }

        // get specific chemical information when edit btn is clicked
        $(document).on('click', '.editbtn', async function () {
            $('#editChemForm')[0].reset();
            let id = $(this).data('chem');
            let deets = await get_chem_details(id);
            var details = JSON.parse(deets);
            console.log(details);

            $('#submitEdit, #toggleEditBtn').attr('disabled', function () {
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
            $('#edit-contSize').val(details.container_size);
            $('#edit-containerCount').val(details.unop_cont);
            $('#edit-dateReceived').val(details.daterec);
            $('#edit-expDate').val(details.expDate);
            $('#edit-notes').val(details.notes);
            $('#addinfo').html(function () {
                return details.addby === 'No Record' ? 'Added at: ' + details.addat : 'Added at: ' + details.addat + ' by ' + details.addby;
            });
            $('#updateinfo').html(function () {
                return details.upby === 'No Update Record' ? 'Updated at: ' + details.upat : 'Updated at: ' + details.upat + ' by ' + details.upby;
            });

            if (toggled) {
                toggle();
            }
            let reqAlertStatus = $('#reqalert').hasClass('d-none');
            if (details.req == 1) {
                $('#reqalert').removeClass('d-none').html('This chemical is pending for approval by the Manager. You can only view the details of this chemical.').fadeIn(750);
            } else {
                if (!reqAlertStatus) {
                    $('#reqalert').addClass('d-none');
                }
                $('#reqalert').html('');
            }

            $('#editModal').modal('show');

        });

        function get_overview_count(container, branch) {
            $.get(urldata, {
                count: true,
                status: container,
                branch: branch
            })
                .done(function (d) {
                    console.log(d);
                    $(`#count_${container}`).empty();
                    $(`#count_${container}`).append(d);
                })
                .fail(function (e) {
                    console.log(e);
                })
        }

        function overview_display(branch = null) {
            get_overview_count('total', branch);
            get_overview_count('low', branch);
            get_overview_count('expired', branch);
            get_overview_count('entries', branch);
        }

        $(document).ready(function () {
            overview_display();
        });


        $(document).on('click', '#inventorylogbtn', function () {
            $('#inventorylogmodal').modal('show');
            $.get('contents/inv.pagination.php', {
                inventorylog: true
            })
                .done(function (data) {
                    $('#inventorylogmodal .modal-body #inventorylogtable').empty();
                    $('#inventorylogmodal .modal-body #inventorylogtable').append(data);
                })
                .fail(function (e) {
                    console.log(e);
                    $('#inventorylogmodal .modal-body').html('<p class="text-center text-danger">Error loading inventory log.</p>');
                });
        });
    </script>
</body>

</html>