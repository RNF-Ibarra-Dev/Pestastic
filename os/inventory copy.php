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
            <div class="d-flex gap-2 mb-2 mx-3 user-select-none text-center">
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-archive-fill me-2 bg-success bg-opacity-25 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold w-75 mx-auto align-middle mb-0">Total
                            Chemicals
                        </p>
                    </div>
                    <p class="fw-light mb-2">Total chemical count.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_total"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-exclamation-triangle-fill me-2 bg-warning bg-opacity-25 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold w-75 mx-auto align-middle mb-0">
                            Low Level Chemicals
                        </p>
                    </div>
                    <p class="fw-light mb-2">Number of chemicals 20% below full capacity.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_low"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-calendar-x-fill me-2 bg-danger bg-opacity-25 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold w-75 mx-auto align-middle mb-0">Expired
                            Chemicals
                        </p>
                    </div>
                    <p class="fw-light mb-2">Number of chemicals past their expiration date.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_expired"></p>
                </div>
            </div>
            <div class="d-flex gap-2 mb-2 mx-3 user-select-none text-center">
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-clock-fill me-2 bg-info bg-opacity-25 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold w-75 mx-auto align-middle mb-0">Pending
                            Entries
                        </p>
                    </div>
                    <p class="fw-light mb-2">Number of pending chemical entries.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_entries"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-check-circle-fill me-2 bg-success bg-opacity-50 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold w-75 mx-auto align-middle mb-0">Available
                            Chemicals
                        </p>
                    </div>
                    <p class="fw-light mb-2">Number of available chemicals inside the storage.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_available"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-truck-flatbed me-2 bg-secondary bg-opacity-50 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold mb-0 mx-auto">Dispatched
                            Chemicals
                        </p>
                    </div>
                    <p class="fw-light mb-2">Number of chemicals being used at transactions.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_dispatched"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-x-octagon me-2 bg-danger bg-opacity-50 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold mb-0 mx-auto">Out
                            of Stock Chemicals
                        </p>
                    </div>
                    <p class="fw-light mb-2">Number of chemicals that have zero available stock in the inventory.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_out-of-stock"></p>
                </div>
            </div>

            <div class="hstack gap-3 my-3 mx-3">
                <button type="button" id="hideentries"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded py-2 w-25 px-2 text-light"
                    title="Hide Entries"><i class="bi bi-eye-slash me-2"></i>Hide
                        Entries</span></button>
                <input class="form-control form-custom rounded-pill me-auto py-2 px-3 text-light"
                    placeholder="Search . . ." id="searchbar" name="search" autocomplete="one-time-code">
                <button type="button" id="inventorylogbtn"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded py-2 px-4 text-light"
                    title="Inventory Logs"><i class="bi bi-file-earmark-text"></i></button>
                <button type="button" id="loadChem"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded text-light py-2 px-4" data-bs-toggle="modal"
                    data-bs-target="#addModal" data-bs-toggle="tooltip" title="Add Stock"><i
                        class="bi bi-plus-square"></i></button>

            </div>
            <!-- inventory log modal -->
            <div class="modal modal-xl fade text-dark modal-edit" data-bs-backdrop="static" id="inventorylogmodal"
                tabindex="0">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title">
                            <h1 class="modal-title fs-5 text-light">Chemical Logs</h1>
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

            <!-- individual chemical modal -->
            <div class="modal modal-xl fade text-dark modal-edit" data-bs-backdrop="static" id="chemicallogmodal"
                tabindex="0">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title">
                            <h1 class="modal-title fs-5 text-light">Chemical Log</h1>
                            <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>

                        <div class="modal-body text-dark p-3">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                        data-bs-target="#loghistory" type="button" role="tab"
                                        aria-selected="true">Chemical Log History</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="disabled-tab" data-bs-toggle="tab"
                                        data-bs-target="#adjust" type="button" role="tab" aria-selected="false">Adjust
                                        Stock</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="loghistory" role="tabpanel" tabindex="0">

                                    <div class="table-responsive-sm  d-flex justify-content-center">
                                        <table class="table align-middle table-hover w-100" id="chemicalhistorylog">
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

                                            <tbody id="chemicalhistorylogtable" class="table-group-divider">
                                            </tbody>

                                        </table>
                                        <div id="inventorylogpaginationbtns"></div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="adjust" role="tabpanel" tabindex="0">

                                    <form id="adjustform">
                                        <input type="hidden" name="chemid" class="log-chem-id">
                                        <h3 class="fw-medium text-center mt-2">Adjust Chemical</h3>
                                        <p class="text-body-secondary text-center fw-light">Adjust chemical levels
                                            accordingly.</p>
                                        <div class="row mb-2 px-2">
                                            <div class="col-lg-3 mb-2">
                                                <label for="adjust-name" class="form-label fw-medium">Chemical
                                                    Name:</label>
                                                <input type="text" id="adjust-name"
                                                    class="ps-2 form-control-plaintext chem-name" readonly>
                                            </div>
                                            <div class="col-lg-3 mb-2">
                                                <label for="adjust-curlevel" class="form-label fw-medium">Currently
                                                    Available:</label>
                                                <p id="adjust-curlevel" class="mt-1 mb-0">
                                                </p>
                                            </div>
                                            <div class="col-lg-3 mb-2">
                                                <label for="adjust-dispatched" class="form-label fw-medium">Currently
                                                    Dispatched/In Use:</label>
                                                <p id="adjust-dispatched" class="mt-1 mb-0">
                                                </p>
                                            </div>
                                            <div class="col-lg-3 mb-2">
                                                <label for="adjust-user" class="form-label fw-medium">User:</label>
                                                <p id="adjust-user" class="mt-1 mb-0 text-capitalize">
                                                    <?= $_SESSION['fname'] . ' ' . $_SESSION['lname'] ?>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 mb-2">
                                                <label for="adjust-qty" class="form-label fw-medium">Quantity</label>
                                                <div class="d-flex">
                                                    <input type="number" class="form-control ps-2 w-50" id="adjust-qty"
                                                        name="qty">
                                                    <span class="qty-unit ms-2 my-auto"></span>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="containerchk" type="checkbox"
                                                        id="wholecontainercheck">
                                                    <label class="form-check-label text-muted"
                                                        for="wholecontainercheck">
                                                        Whole Container
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 mb-2" style="display: none;"
                                                id="adjust-containerinput">
                                                <label class="form-label fw-medium" for="adjust-container">
                                                    Container Count
                                                </label>
                                                <div class="d-flex">
                                                    <input type="number" class="form-control ps-2 w-50"
                                                        id="adjust-container" name="containercount" disabled>
                                                    <span class="fw-light ms-2 my-auto">Container/s</span>
                                                </div>
                                                <p class="fw-light">Note. Containers with different capacity should be
                                                    added as a separate chemical.</p>
                                            </div>
                                            <div class="col-lg-3 mb-2">
                                                <label for="adjust-logtype" class="form-label fw-medium">Adjustment
                                                    Type:</label>
                                                <select name="logtype" class="form-select" id="adjust-logtype">
                                                    <option value="" selected>Adjustment Types</option>
                                                    <option value="in">Manual Stock Correction (In)</option>
                                                    <option value="out">Manual Stock Correction (Out)</option>
                                                    <option value="lost">Lost/Damaged Stock</option>
                                                    <option value="scrapped">Scrapped</option>
                                                    <option value="other">Other (Please Specify)</option>
                                                </select>
                                                <div class="mt-2 d-none" id="ltothers">
                                                    <input type="text" id="otherlogtype" name="other_logtype"
                                                        class="form-control mt-1" disabled>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="operator"
                                                            id="adjust-increase" value="add" checked>
                                                        <label class="form-check-label"
                                                            for="adjust-increase">Increase</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="operator"
                                                            id="adjust-decrease" value="subtract">
                                                        <label class="form-check-label"
                                                            for="adjust-decrease">Decrease</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 mb-2">
                                                <label for="adjust-notes" class="form-label fw-medium">Notes:</label>
                                                <textarea name="notes" id="adjust-notes" rows="1"
                                                    class="ps-2 form-control"
                                                    placeholder="state reason of adjustment . . ."
                                                    autocomplete="off"></textarea>
                                            </div>

                                        </div>
                                        <p class="alert alert-info py-2 text-center w-75 mx-auto" id="adjustalert"
                                            style="display: none;"></p>
                                        <button type="submit" class="btn btn-grad mx-auto">Adjust Chemical</button>
                                    </form>

                                </div>
                            </div>


                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>

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
                                            <label for="edit-contSize" class="form-label fw-light">Container
                                                Size:</label>
                                            <input type="number" name="edit-containerSize" id="edit-contSize"
                                                class="form-control-plaintext" readonly autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="view-chemUnit" class="form-label fw-light">Chemical
                                                Unit:</label>
                                            <p id="view-chemUnit"></p>
                                            <select name="edit-chemUnit" id="edit-chemUnit" class="form-select d-none"
                                                disabled autocomplete="one-time-code">
                                                <option value="" selected>Choose Chemical Unit</option>
                                                <option value="mg">mg</option>
                                                <option value="g">g</option>
                                                <option value="kg">kg</option>
                                                <option value="L">L</option>
                                                <option value="mL">mL</option>
                                            </select>
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
                                            <label for="chemLevel" class="form-label fw-light">Container Size</label>
                                            <input type="text" name="containerSize[]" id="add-chemLevel"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="add-chemUnit" class="form-label fw-light">Chemical
                                                Unit:</label>
                                            <select name="chemUnit[]" id="add-chemUnit" class="form-select"
                                                 autocomplete="one-time-code">
                                                <option value="" selected>Chemical Unit</option>
                                                <option value="mg">mg</option>
                                                <option value="g">g</option>
                                                <option value="kg">kg</option>
                                                <option value="L">L</option>
                                                <option value="mL">mL</option>
                                            </select>
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
        <div class="toast-container m-2 me-3 bottom-0 end-0 position-fixed">
            <div class="toast align-items-center" role="alert" id="toast" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body text-dark ps-4 text-success-emphasis" id="toastmsg">
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.links.php'); ?>

    <script>
        const urldata = 'contents/inv.data.php';
        const urlpage = 'contents/inv.pagination.php';

        function show_toast(message) {
            $('#toastmsg').html(message);
            var toastid = $('#toast');
            var toast = new bootstrap.Toast(toastid);
            toast.show();
        }

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
            overview_display();
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
            $('#view-chemUnit, #edit-chemUnit').toggleClass('d-none');
            $('#edit-notes, #edit-name, #edit-chemBrand, #edit-chemLevel, #edit-contSize, #edit-containerCount').attr('readonly', function (i, a) {
                return a ? false : true;
            });
            $("#edit-expDate, #edit-dateReceived, #edit-chemUnit").attr('disabled', function (i, a) {
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
            $('#edit-chemUnit').val(details.unit);
            $('#view-chemUnit').text(details.unit);
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
                    // console.log(d);
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
            get_overview_count('available', branch);
            get_overview_count('dispatched', branch);
            get_overview_count('out-of-stock', branch);
        }

    

        $(document).on('click', '#inventorylogbtn', function () {
            $('#inventorylogmodal').modal('show');
            $.get('contents/inv.log.pagination.php', {
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

        function load_chem_log_history(id) {
            $.get('contents/inv.log.pagination.php', {
                chemloghistory: true,
                chemid: id
            })
                .done(function (data) {
                    $('#chemicallogmodal .modal-body #chemicalhistorylogtable').empty();
                    $('#chemicallogmodal .modal-body #chemicalhistorylogtable').append(data);
                })
                .fail(function (e) {
                    console.log(e);
                    $('#chemicallogmodal .modal-body').html('<p class="text-center text-danger">Error loading inventory log.</p>');
                });
        }


        $("#chemicalTable").on('click', '.log-chem-btn', async function () {
            let id = $(this).data('chem');
            await load_chem_log_history(id);
        });

        async function get_chem_log(id) {
            console.log(id);
            $.ajax({
                method: 'GET',
                url: urldata,
                data: {
                    chemLog: true,
                    id: id
                },
                dataType: 'json'
            }).done(function (data) {
                console.log(data);

                if (data.success) {
                    let d = JSON.parse(data.success);
                    $(".log-chem-id").val(d.id);
                    $(".chem-name").val(d.name);
                    $(".qty-unit").text(' - ' + d.quantity_unit);

                    $("#adjust-curlevel").text(d.chemLevel + '/' + d.container_size + d.quantity_unit + ' (' + d.unop_cont + ' container/s left.)');
                    $("#adjust-dispatched").text(d.log_type === 'Out' ? d.quantity + d.quantity_unit : "Chemical currently not dispatched.");

                    if (!$("#wholecontainercheck").prop('checked')) {
                        $("#adjust-containerinput").hide();
                        $("#adjust-containerinput input").prop('disabled', true);
                        $("#adjust-qty").prop('disabled', false);
                    }
                    $('#wholecontainercheck').on('change', function () {
                        if ($(this).prop('checked')) {
                            $("#adjust-containerinput").show();
                            $("#adjust-containerinput input").prop('disabled', false);
                            $("#adjust-qty").prop('disabled', true);
                        } else {
                            $("#adjust-containerinput").hide();
                            $("#adjust-containerinput input").prop('disabled', true);
                            $("#adjust-qty").prop('disabled', false);
                        }
                    })

                    $('#chemicallogmodal').modal('show');

                } else {
                    alert('Unknown Error. Please try again later.');
                }
            })
                .fail(function (e) {
                    console.log(e);
                })
        }

        $("#chemicalTable").on('click', '.log-chem-btn', function () {
            let id = $(this).data('chem');
            get_chem_log(id);
        });

        $('#adjust-logtype').on('change', function () {
            if ($(this).val() === 'other') {
                $("#ltothers").toggleClass('d-none');
                $("#ltothers input").prop('disabled', false);
            } else {
                if (!$("#ltothers").hasClass('d-none')) {
                    $("#ltothers").addClass('d-none');
                    $("#ltothers input").prop('disabled', true);
                }
            }
        });


        $(document).on('submit', '#adjustform', async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            $.ajax({
                method: "POST",
                url: urldata,
                dataType: 'json',
                data: $(this).serialize() + "&adjust=true"
            })
                .done(function (d) {
                    $("#adjustform")[0].reset();
                    // $("#adjustalert").text(d.success).fadeIn(300).delay(2000).fadeOut(1000);
                    $("#chemicallogmodal").modal('hide');
                    show_toast(d.success);
                    loadpage(1, entryHidden);
                })
                .fail(function (e) {
                    $("#adjustalert").text(e.responseText).fadeIn(300).delay(2000).fadeOut(1000);
                    console.log(e);
                })
        })
    </script>
</body>

</html>