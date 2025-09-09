<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician | Inventory</title>
    <!-- <link rel="stylesheet" href="../../css/style.css"> -->
    <?php include 'header.links.php'; ?>
    <style>
        #chemicalTable td {
            padding: 1rem !important;
        }
    </style>
</head>

<body class="bg-official text-light">

    <div class="sa-bg container-fluid p-0 h-100 d-flex">
        <!-- sidebar -->
        <?php include('sidenav.php'); ?>
        <!-- main content -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php include('navbar.php'); ?>
            <!-- content -->
            <div class="bg-light bg-opacity-25 pt-2 rounded p-3 mx-3 mt-3 mb-2">
                <h1 class="display-6 text-light mb-0 fw-bold text-center">Inventory Items</h1>
            </div>
            <div class="d-flex gap-2 mb-2 mx-3 user-select-none text-center">
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-archive-fill me-2 bg-success bg-opacity-25 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold w-75 mx-auto align-middle mb-0">Total
                            Items
                        </p>
                    </div>
                    <p class="fw-light mb-2 mt-auto">Official count of approved items.</p>
                    <p class="fs-4 fw-bold mb-0" id="count_total"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-exclamation-triangle-fill me-2 bg-warning bg-opacity-25 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold w-75 mx-auto align-middle mb-0">
                            Restock Items
                        </p>
                    </div>
                    <p class="fw-light mb-2 mt-auto">Number of items below restock threshold.</p>
                    <p class="fs-4 fw-bold mb-0" id="count_low"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-calendar-x-fill me-2 bg-danger bg-opacity-25 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold w-75 mx-auto align-middle mb-0">Expired
                            Items
                        </p>
                    </div>
                    <p class="fw-light mb-2 mt-auto">Number of items past their expiration date.</p>
                    <p class="fs-4 fw-bold mb-0" id="count_expired"></p>
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
                    <p class="fw-light mb-2 mt-auto">Count of new items that are up for approval.</p>
                    <p class="fs-4 fw-bold mb-0" id="count_entries"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-check-circle-fill me-2 bg-success bg-opacity-50 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold w-75 mx-auto align-middle mb-0">Available
                            Items
                        </p>
                    </div>
                    <p class="fw-light mb-2 mt-auto">Number of available and free items inside the storage.</p>
                    <p class="fs-4 fw-bold mb-0" id="count_available"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-truck-flatbed me-2 bg-secondary bg-opacity-50 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold mb-0 w-75 mx-auto">Dispatched
                            Items
                        </p>
                    </div>
                    <p class="fw-light mb-2 mt-auto">Number of items being used at dispatched transaction places.</p>
                    <p class="fs-4 fw-bold mb-0" id="count_dispatched"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded ps-3 pe-2 py-2 flex-fill flex-wrap w-100 d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-x-octagon me-2 bg-danger bg-opacity-50 py-1 px-2 rounded shadow-sm align-middle float-start"></i>
                        <p class="fs-5 fw-bold mb-0 w-75 mx-auto">Out
                            of Stock Items
                        </p>
                    </div>
                    <p class="fw-light mb-2 mt-auto">Obsolete items. Zero stock and must be deleted from database.</p>
                    <p class="fs-4 fw-bold mb-0" id="count_out-of-stock"></p>
                </div>
            </div>

            <div class="hstack gap-2 my-2 mx-3">
                <button type="button" id="entriesTableBtn"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded w-25 py-2 px-2 text-light fw-bold d-flex align-content-center justify-content-center"><i
                        class="bi bi-clock-fill me-2"></i>Pending Entries</button>
                <button type="button" id="restockTableBtn"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded w-25 py-2 px-2 text-light fw-bold d-flex align-content-center justify-content-center"><i
                        class="bi bi-box-fill me-2"></i>Restock Items</button>
                <button type="button" id="dispatchedTableBtn"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded w-25 py-2 px-2 text-light fw-bold d-flex align-content-center justify-content-center"><i
                        class="bi bi-truck-flatbed me-2"></i>Dispatched Items</button>
                <input class="form-control form-custom rounded-pill me-auto py-2 px-3 text-light"
                    placeholder="Search . . ." id="searchbar" name="search" autocomplete="one-time-code">

            </div>

            <!-- edit/view modal (viewonly modal for technician) -->
            <div class="row g-2 text-dark">
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="editModal" tabindex="-1"
                    aria-labelledby="edit" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Item Details
                                </h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                        class="bi text-light bi-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <!-- <input type="hidden" name="edit" value="edit-chemical"> -->
                                <input type="hidden" name="edit-id" id="edit-id" class="form-control">
                                <p
                                    class="text-center fw-bold fs-5 bg-secondary text-light bg-opacity-50 rounded py-1 w-50 mx-auto">
                                    Main Item Information</p>

                                <div class="row mb-2">

                                    <div class="col-lg-3 mb-2">
                                        <label for="edit-name" class="form-label fw-medium">Item Name:</label>
                                        <input type="text" name="edit-name" id="edit-name"
                                            class="ps-2 form-control-plaintext" readonly autocomplete="off">
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label for="edit-chemBrand" class="form-label fw-medium">Item
                                            Brand:</label>
                                        <input type="text" name="edit-chemBrand" id="edit-chemBrand"
                                            class="ps-2 form-control-plaintext" readonly autocomplete="off">
                                    </div>
                                    <div class="col-lg-2 mb-2">
                                        <label for="edit-contSize" class="form-label fw-medium">Item
                                            Size:</label>
                                        <input type="number" name="edit-containerSize" id="edit-contSize"
                                            class="form-control-plaintext ps-2" readonly autocomplete="one-time-code">
                                    </div>
                                    <div class="col-lg-2 mb-2">
                                        <label for="view-chemUnit" class="form-label fw-medium">Item
                                            Unit:</label>
                                        <p id="view-chemUnit" class=" ps-2"></p>
                                        <select name="edit-chemUnit" id="edit-chemUnit" class="form-select d-none"
                                            disabled autocomplete="one-time-code">
                                            <option value="" selected>Choose Item Unit</option>
                                            <option value="mg">mg</option>
                                            <option value="g">g</option>
                                            <option value="kg">kg</option>
                                            <option value="mL">mL</option>
                                            <option value="L">L</option>
                                            <option value="gal">gal</option>
                                            <option value="box">Box</option>
                                            <option value="pc">Piece</option>
                                            <option value="canister">Canister</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 mb-2">
                                        <label for="edit-restockThreshold" class="form-label fw-medium">Restock
                                            Threshold:</label>
                                        <input type="number" name="edit-restockThreshold" id="edit-restockThreshold"
                                            class="form-control-plaintext ps-2" readonly autocomplete="one-time-code">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-3 mb-2">
                                        <label for="edit-dateReceived" class="form-label fw-medium">Date
                                            Received:</label>
                                        <input type="text" name="edit-receivedDate" id="edit-dateReceived"
                                            class="ps-2 form-control-plaintext form-add form-date" readonly>
                                    </div>
                                    <div class="col-3 mb-2">
                                        <label for="edit-expDate" class="form-label fw-medium">Expiry Date:</label>
                                        <input type="text" name="edit-expDate" id="edit-expDate"
                                            class="ps-2 form-control-plaintext form-date" autocomplete="off" readonly>
                                        <p class="fw-light text-center alert alert-warning py-1 px-3 d-none"
                                            id="expdatewarning"></p>
                                    </div>
                                    <div class="col-3 mb-2">
                                        <label for="edit-notes" class="form-label fw-medium">Short Note:</label>
                                        <textarea name="edit-notes" id="edit-notes" style="resize: none !important;"
                                            class="ps-2 form-control-plaintext" readonly></textarea>
                                    </div>
                                </div>

                                <div id="chemState">
                                    <p
                                        class="text-center fw-bold fs-5 bg-secondary text-light bg-opacity-50 rounded py-1 w-50 mx-auto">
                                        Item Count Information</p>
                                    <div class="row mb-2 user-select-none">
                                        <div class="col-3">
                                            <p class="fw-medium mb-2">Opened Item Level:</p>
                                            <p class="ps-2 mb-2" id="openedContainerLevel"></p>
                                        </div>
                                        <div class="col-3">
                                            <p class="fw-medium mb-2">Closed Item Count:</p>
                                            <p class="ps-2 mb-2" id="closedContainerCount"></p>
                                        </div>
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
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
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
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <label for="pass" class="form-label fw-light">Change item information? Enter manager
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
                                disabled-name="delete">Edit Item</button>
                        </div>
                    </div>
                </div>
            </div>

            <form id="restockForm">
                <input type="hidden" name="id" id="id_input">
                <div class="modal modal-xl fade text-dark modal-edit" data-bs-backdrop="static"
                    id="restockFunctionModal" tabindex="0">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title">
                                <h1 class="modal-title fs-5 text-light">
                                    <i class="bi bi-truck-flatbed me-2"></i>
                                    Restock Item
                                </h1>
                                <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x"></i></button>
                            </div>

                            <div class="modal-body text-dark p-3">
                                <p
                                    class="text-center fw-bold fs-5 bg-secondary text-light bg-opacity-50 rounded py-1 w-50 mx-auto">
                                    Main Item Information</p>
                                <div class="row">
                                    <div class="col-2">
                                        <p class="fw-bold">Item ID:</p>
                                        <p id="restock_id" class="ms-1"></p>
                                    </div>
                                    <div class="col-2">
                                        <p class="fw-bold">Item Name:</p>
                                        <p id="restock_name" class="ms-1"></p>
                                    </div>
                                    <div class="col-2">
                                        <p class="fw-bold">Item Brand:</p>
                                        <p id="restock_brand" class="ms-1"></p>
                                    </div>
                                    <div class="col-2">
                                        <p class="fw-bold">Expiry Date:</p>
                                        <p id="restock_expiry" class="ms-1"></p>
                                    </div>
                                    <div class="col-2">
                                        <p class="fw-bold">Item Size:</p>
                                        <p id="restock_size" class="ms-1"></p>
                                    </div>
                                    <div class="col-2">
                                        <p class="fw-bold">Restock Threshold:</p>
                                        <p id="restock_threshold" class="ms-1"></p>
                                    </div>
                                </div>
                                <p
                                    class="text-center fw-bold fs-5 bg-secondary text-light bg-opacity-50 rounded py-1 w-50 mx-auto">
                                    Remaining Stock Summary</p>
                                <div class="row">
                                    <div class="col-3">
                                        <p class="fw-bold">Current Opened/Outgoing Item Left:</p>
                                        <p id="restock_opened" class="ms-1"></p>
                                    </div>
                                    <div class="col-3">
                                        <p class="fw-bold">Current Closed Container Left:</p>
                                        <p id="restock_ccontainer" class="ms-1"></p>
                                    </div>
                                    <div class="col-3">
                                        <p class="fw-bold">Total Container Left:</p>
                                        <p id="restock_tcontainer" class="ms-1"></p>
                                    </div>

                                    <div class="col-3">
                                        <label for="restock_value" class="form-label fw-bold">Number of items to
                                            restock:</label>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-grad py-0" id="restock_less"><i
                                                    class="bi bi-dash"></i></button>
                                            <input type="number" class="form-control text-center mx-2 px-1" value="0"
                                                style="width: 3rem !important;" id="restock_value" name="restock_value"
                                                autocomplete='new-password'>
                                            <button type="button" class="btn btn-grad py-0" id="restock_add"><i
                                                    class="bi bi-plus"></i></button>
                                        </div>
                                        <p class="alert alert-warning mt-1 py-1 px-2" style="display: none;"
                                            id="restock_val_alert">
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="restock_notes" class="form-label fw-bold">Additional restock
                                            note:</label>
                                        <textarea name="note" id="restock_notes" rows="1" class="form-control"
                                            placeholder="Optional note . . ."></textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#restockConfirm">Continue</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="restockConfirm" tabindex="0">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title">
                                <h1 class="modal-title fs-5 text-light">
                                    <i class="bi bi-truck-flatbed me-2"></i>
                                    Confirmation
                                </h1>
                                <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x"></i></button>
                            </div>

                            <div class="modal-body text-dark p-3">
                                <label for="restock_confirmation" class="form-label fw-light">Confirm restock? Enter
                                    <?= $_SESSION['baUsn'] ?>'s password to confirm.</label>
                                <input type="password" name="pwd" id="restock_confirmation"
                                    class="form-control ps-2 w-50">
                                <p class="text-secondary fw-light">Note: restock note.</p>
                                <p class="alert alert-info text-center py-2 px-3" id="restock_err_alert"
                                    style="display: none;"></p>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#restockFunctionModal">Go back</button>
                                <button type="submit" class="btn btn-grad">Restock Item</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- dispatched modal -->
            <div class="modal modal-xl fade text-dark modal-edit" data-bs-backdrop="static" id="dispatchedItemsModal"
                tabindex="0">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title">
                            <h1 class="modal-title fs-5 text-light">
                                <i class="bi bi-truck-flatbed me-2"></i>
                                Dispatched Items
                            </h1>
                            <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>

                        <div class="modal-body text-dark p-3">
                            <div class="table-responsive-sm flex-column d-flex justify-content-center">
                                <table class="table align-middle table-hover w-100">
                                    <caption class="fw-light text-muted">
                                        Items on this list are still pending for approval. Only a Manager can approve an
                                        item stock.
                                    </caption>
                                    <thead>
                                        <tr class="text-center align-middle">
                                            <th class="text-dark" scope="col">Item ID</th>
                                            <th class="text-dark">Item</th>
                                            <th class="text-dark">Brand</th>
                                            <th class="text-dark">Outgoing Stock Level (Opened)</th>
                                            <th class="text-dark">Unopened Stock</th>
                                            <th class="text-dark">Restock Threshold</th>
                                            <th class="text-dark">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="dispatchedTable" class="table-group-divider text-dark">
                                    </tbody>

                                </table>
                                <div id="dispatchedTablePagination"></div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- restock modal -->
            <div class="modal modal-xl fade text-dark modal-edit" data-bs-backdrop="static" id="restockModal"
                tabindex="0">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title">
                            <h1 class="modal-title fs-5 text-light">
                                <i class="bi bi-box-fill me-2"></i>
                                Items for Restock
                            </h1>
                            <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>

                        <div class="modal-body text-dark p-3">
                            <div class="table-responsive-sm flex-column d-flex justify-content-center">
                                <table class="table align-middle table-hover w-100">
                                    <caption class="fw-light text-muted">
                                        Items on this list are still pending for approval. Only a Manager can approve an
                                        item stock.
                                    </caption>
                                    <thead>
                                        <tr class="text-center align-middle">
                                            <th class="text-dark" scope="col">Item ID</th>
                                            <th class="text-dark">Item</th>
                                            <th class="text-dark">Brand</th>
                                            <th class="text-dark">Outgoing Stock Level (Opened)</th>
                                            <th class="text-dark">Unopened Stock</th>
                                            <th class="text-dark">Restock Threshold</th>
                                            <th class="text-dark">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="restockTable" class="table-group-divider text-dark">
                                    </tbody>

                                </table>
                                <div id="restockPagination"></div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- item entry modal table -->
            <div class="modal modal-xl fade text-dark modal-edit" data-bs-backdrop="static" id="itemEntryModal"
                tabindex="0">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title">
                            <h1 class="modal-title fs-5 text-light">
                                <i class="bi bi-clock-fill me-2"></i>
                                Item Entries
                            </h1>
                            <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>

                        <div class="modal-body text-dark p-3">
                            <div class="table-responsive-sm flex-column d-flex justify-content-center">
                                <table class="table align-middle table-hover w-100" id="approvechemtable">
                                    <!-- <caption class="fw-light text-muted">
                                    </caption> -->
                                    <thead>
                                        <tr class="text-center align-middle">
                                            <th class="text-dark" scope="col">Item ID</th>
                                            <th class="text-dark">Item</th>
                                            <th class="text-dark">Brand</th>
                                            <th class="text-dark">Item Size/Volume</th>
                                            <th class="text-dark">Date Received</th>
                                            <th class="text-dark">Item Expiry</th>
                                            <th class="text-dark">Added By</th>
                                        </tr>
                                    </thead>

                                    <tbody id="itemEntryTable" class="table-group-divider text-dark">
                                    </tbody>

                                </table>
                                <div id="itemEntriesPagination"></div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- inventory log modal -->
            <div class="modal modal-xl fade text-dark modal-edit" data-bs-backdrop="static" id="inventorylogmodal"
                tabindex="0">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title">
                            <h1 class="modal-title fs-5 text-light">
                                <i class="bi bi-journal-text me-2"></i>
                                Item Logs
                            </h1>
                            <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>

                        <div class="modal-body text-dark p-3">
                            <div class="table-responsive-sm  d-flex justify-content-center">
                                <table class="table align-middle table-hover w-100" id="approvechemtable">
                                    <caption class="fw-light text-muted">Note. Associated transactions are logs that are
                                        auto deducted by the transaction.
                                    </caption>
                                    <thead>
                                        <tr class="text-center align-middle">
                                            <th class="text-dark" scope="col">Date & Time</th>
                                            <th class="text-dark">Activity Type</th>
                                            <th class="text-dark">Item</th>
                                            <th class="text-dark">Amount</th>
                                            <th class="text-dark">Performed By</th>
                                            <th class="text-dark">Associated Transaction</th>
                                            <th class="text-dark">Notes</th>
                                        </tr>
                                    </thead>

                                    <tbody id="log_tbody" class="table-group-divider">
                                    </tbody>

                                </table>
                            </div>
                            <div id="inv_log_pagination"></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <form id="returnChemicalForm">
                <input type="hidden" name="returnChemicalId" id="returnChemicalId">
                <input type="hidden" id="return_currentLocation" name="return_currentLocation">
                <div class="row g-2 m-0 p-0 text-dark">
                    <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="returnChemModal"
                        tabindex="-1" aria-labelledby="create" aria-hidden="true">
                        <div class="modal-dialog modal-xl ">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Return Dispatched Item</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <p
                                        class="text-center fw-bold fs-5 bg-secondary text-light bg-opacity-50 rounded py-1 w-50 mx-auto">
                                        Item Information</p>
                                    <div class="row mb-2">
                                        <div class="col-lg-2 mb-2">
                                            <label for="returnName" class="form-label fw-medium">Item
                                                Name:</label>
                                            <p id="returnName" class="fw-light ps-2"></p>
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="return-chemBrand" class="form-label fw-medium">Item
                                                Brand:</label>
                                            <p id="return-chemBrand" class="fw-light ps-2"></p>
                                        </div>

                                        <div class="col-lg-2 mb-2">
                                            <label for="return-contSize" class="form-label fw-medium">Item
                                                Size:</label>
                                            <p id="return-contSize" class="fw-light ps-2"></p>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="return-cstatus" class="form-label fw-medium">Item Location
                                                Status:</label>
                                            <p id="return-cstatus" class="fw-light ps-2"></p>
                                        </div>
                                    </div>

                                    <p
                                        class="text-center fw-bold fs-5 bg-secondary text-light bg-opacity-50 rounded py-1 w-50 mx-auto">
                                        Dispatched Item Summary</p>

                                    <div class="row mb-2 user-select-none">
                                        <div class="col-3">
                                            <p class="fw-medium mb-2">Opened Item Level:</p>
                                            <p class="ps-2 mb-2 fw-light" id="return_openedContainerLevel"></p>
                                        </div>
                                        <div class="col-3">
                                            <p class="fw-medium mb-2">Closed Item Count:</p>
                                            <p class="ps-2 mb-2 fw-light" id="return_closedContainerCount"></p>
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="return-containerCount" class="form-label fw-medium">Total
                                                Items:</label>
                                            <p id="return-containerCount" class="fw-light ps-2"></p>
                                        </div>
                                    </div>

                                    <p
                                        class="text-center fw-bold fs-5 bg-secondary text-light bg-opacity-50 rounded py-1 w-50 mx-auto">
                                        Return Information</p>

                                    <div class="row mb-2">
                                        <div class="col-5 mb-2">
                                            <label for="opened_container" class="form-label fw-medium">Returned quantity
                                                of opened items:</label>
                                            <input type="number" name="opened_container" id="opened_container"
                                                class="form-control w-50" autocomplete="one-time-code">
                                            <p class="text-body-secondary mt-1">Note: This should not exceed the
                                                quantity of
                                                the item size and the quantity of dispatched items.</p>
                                        </div>
                                        <div class="col-2 mb-2">
                                            <label for="return_unit" class="form-label fw-medium">Unit:</label>
                                            <select id="return_unit" class="form-select" name="return_unit"></select>
                                        </div>
                                        <div class="col-5 mb-2">
                                            <label for="container_count" class="form-label fw-medium">Number of
                                                closed items (not used)
                                                to return:</label>
                                            <div class="d-flex">
                                                <input type="number" name="container_count" id="container_count"
                                                    class="form-control w-50" autocomplete="one-time-code">
                                                <span class="fw-light align-middle ms-2 my-auto">Container/s</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-4 mb-2">
                                            <label for="return_transaction" class="form-label fw-medium">Select
                                                Dispatched Transaction:</label>
                                            <select name="return_transaction" id="return_transaction"
                                                class="form-select" autocomplete="one-time-code">
                                            </select>
                                            <p class="text-body-secondary mt-2">Note: You can only return this
                                                item's dispatched transaction.</p>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <p class="fw-medium">Current Transaction Dispatch Information:</p>
                                            <p id="return_current_transaction_info" class="fw-light ms-2">Please select
                                                a transaction.</p>
                                        </div>
                                    </div>

                                    <p class="mb-0 mt-3 text-center alert alert-warning">Note: This is a summary of
                                        multiple dispatched items. This will
                                        also override existing items set in the chosen transaction. Proceed with
                                        caution.
                                    </p>
                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-grad return-proceed-btn" data-bs-toggle="modal"
                                        data-bs-target="#returnConfirmationModal">Proceed & Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="returnConfirmationModal"
                    tabindex="0" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyAdd">Return Item</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="pass" class="form-label fw-light">Return Item? Enter Technician
                                        <?= $_SESSION['techUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="baPwd" class="form-control">
                                    </div>
                                </div>
                                <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                    id="returnAlert">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#returnChemModal"
                                    data-bs-toggle="modal">Go
                                    back</button>
                                <button type="submit" class="btn btn-grad">Return Item</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="dispatchChemicalForm">
                <input type="hidden" name="dispatchChemicalId" id="dispatchChemicalId">
                <input type="hidden" id="currentLocation" name="currentLocation">
                <div class="row g-2 m-0 p-0 text-dark">
                    <div class=" modal fade text-dark modal-edit" data-bs-backdrop="static" id="dispatchChemModal"
                        tabindex="-1" aria-labelledby="create" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Set Transaction Item Dispatch</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <p
                                        class="text-center fw-bold fs-5 bg-secondary text-light bg-opacity-50 rounded py-1 w-50 mx-auto user-select-none">
                                        Item Information</p>
                                    <div class="row mb-2">
                                        <div class="col-lg-2 mb-2">
                                            <label for="dispatchName" class="form-label fw-medium">Item
                                                Name:</label>
                                            <p id="dispatchName" class="fw-light ps-2"></p>
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="dispatch-chemBrand" class="form-label fw-medium">Item
                                                Brand:</label>
                                            <p id="dispatch-chemBrand" class="fw-light ps-2"></p>
                                        </div>

                                        <div class="col-lg-2 mb-2">
                                            <label for="dispatch-contSize" class="form-label fw-medium">Item
                                                Size:</label>
                                            <p id="dispatch-contSize" class="fw-light ps-2"></p>
                                        </div>

                                        <div class="col-lg-3 mb-2">
                                            <label for="dispatch-containerCount" class="form-label fw-medium">Item
                                                Count
                                                (Including Opened):</label>
                                            <p id="dispatch-containerCount" class="fw-light ps-2"></p>
                                        </div>
                                        <div class="col-lg-3 mb-2">
                                            <label for="dispatch-cstatus" class="form-label fw-medium">Item
                                                Location Status:</label>
                                            <p id="dispatch-cstatus" class="fw-light ps-2"></p>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-4 mb-2">
                                            <label for="dispatchValue" class="form-label fw-medium">Number of items
                                                to dispatch:</label>
                                            <input type="number" name="dispatchValue" id="dispatchValue"
                                                class="form-control w-50" autocomplete="one-time-code">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" name="includeOpened" type="checkbox"
                                                    id="includeOpened">
                                                <label class="form-check-label text-muted user-select-none"
                                                    for="includeOpened">
                                                    Include opened items.
                                                </label>
                                            </div>
                                            <input type="checkbox" class="btn-check" name="dispatchAll" id="dispatchAll"
                                                autocomplete="off">
                                            <label class="btn btn-outline-dark" for="dispatchAll">Dispatch
                                                Everything</label>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="dispatch-transaction" class="form-label fw-medium">Select
                                                Transaction Dispatch:</label>
                                            <select name="dispatch-transaction" id="dispatch-transaction"
                                                class="form-select w-50" autocomplete="one-time-code">
                                            </select>
                                            <p class="text-body-secondary mt-2">Note: You can only dispatch an item
                                                to prepared and approved/accepted transactions.</p>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <p class="fw-medium">Current Transaction Dispatch Information:</p>
                                            <p id="current_transaction_info" class="fw-light ms-2">Please select a
                                                transaction.</p>
                                        </div>
                                    </div>
                                    <p
                                        class="text-center fw-bold fs-5 bg-secondary text-light bg-opacity-50 rounded py-1 w-50 mx-auto">
                                        Item Count Information</p>

                                    <div class="row mb-2 user-select-none">
                                        <div class="col-3">
                                            <p class="fw-medium mb-2">Opened Item Level:</p>
                                            <p class="ps-2 mb-2" id="dispatch_openedContainerLevel"></p>
                                        </div>
                                        <div class="col-3">
                                            <p class="fw-medium mb-2">Closed Item Count:</p>
                                            <p class="ps-2 mb-2" id="dispatch_closedContainerCount"></p>
                                        </div>
                                    </div>

                                    <p class="mb-0 mt-3 text-center alert alert-warning">Please note that this will
                                        override current items set in the chosen transaction. Proceed with caution.
                                    </p>
                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                        data-bs-target="#dispatchConfirmationModal">Proceed & Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="dispatchConfirmationModal"
                    tabindex="0" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyAdd">Dispatch Item</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="pass" class="form-label fw-light">Dispatch Item? Technician
                                        <?= $_SESSION['techUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="baPwd" class="form-control">
                                    </div>
                                </div>
                                <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                    id="dispatchAlert">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#dispatchChemModal"
                                    data-bs-toggle="modal">Go
                                    back</button>
                                <button type="submit" class="btn btn-grad">Dispatch Item</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- individual chemical modal -->
            <div class="modal modal-xl fade text-dark modal-edit" data-bs-backdrop="static" id="chemicallogmodal"
                tabindex="0">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title">
                            <h1 class="modal-title fs-5 text-light">
                                <i class="bi bi-journal-text me-2"></i>
                                Item Log<span class="text-secondary mx-2">|</span>Adjust Item
                            </h1>
                            <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>

                        <div class="modal-body text-dark p-3">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                        data-bs-target="#loghistory" type="button" role="tab" aria-selected="true">Item
                                        Log History</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="disabled-tab" data-bs-toggle="tab"
                                        data-bs-target="#adjust" type="button" role="tab" aria-selected="false">Adjust
                                        Stock</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="loghistory" role="tabpanel" tabindex="0">
                                    <h3 class="fw-medium text-center mt-2">Item Log</h3>
                                    <p class="text-secondary text-center fw-light">Recent log history from item changes.
                                    </p>
                                    <div class="table-responsive-sm d-flex flex-column justify-content-center">
                                        <table class="table align-middle table-hover w-100" id="chemicalhistorylog">
                                            <thead>
                                                <tr class="text-center align-middle">
                                                    <th class="text-dark" scope="col">Date & Time</th>
                                                    <th class="text-dark">Activity Type</th>
                                                    <th class="text-dark">Amount</th>
                                                    <th class="text-dark">Performed By</th>
                                                    <th class="text-dark">Associated Transaction</th>
                                                    <th class="text-dark">Notes</th>
                                                </tr>
                                            </thead>

                                            <tbody id="chemicalhistorylogtable" class="table-group-divider">
                                            </tbody>

                                        </table>
                                        <div id="chem_log_pagination"></div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="adjust" role="tabpanel" tabindex="0">

                                    <form id="adjustform">
                                        <input type="hidden" name="main_qty_unit" id="main_qty_unit">
                                        <input type="hidden" name="chemid" class="log-chem-id">
                                        <h3 class="fw-medium text-center mt-2">Adjust Item</h3>
                                        <p class="text-body-secondary text-center fw-light">Adjust item levels
                                            accordingly.</p>
                                        <div class="row mb-2 px-2">
                                            <div class="col-lg-3 mb-2">
                                                <label for="adjust-name" class="form-label fw-medium">Item
                                                    Name:</label>
                                                <input type="text" id="adjust-name"
                                                    class="form-control-plaintext chem-name" readonly>
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
                                                    <?= $_SESSION['firstName'] . ' ' . $_SESSION['lastName'] ?>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 mb-2">
                                                <label for="adjust-qty" class="form-label fw-medium">Quantity:</label>
                                                <div class="d-flex">
                                                    <input type="number" class="form-control ps-2" id="adjust-qty"
                                                        name="qty">
                                                    <!-- <span class="qty-unit ms-2 my-auto"></span> -->
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="containerchk" type="checkbox"
                                                        id="wholecontainercheck">
                                                    <label class="form-check-label text-muted user-select-none"
                                                        for="wholecontainercheck">
                                                        Whole Item
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-3 mb-2">
                                                <label for="adjust_qty_unit" class="form-label fw-medium">Unit to
                                                    use:</label>
                                                <select name="qty_unit" id="adjust_qty_unit"
                                                    class="form-select"></select>
                                                <p class="text-body-secondary">Note: This is only for adjustment. The
                                                    original unit will remain unchanged.</p>
                                            </div>
                                            <div class="col-lg-3 mb-2" style="display: none;"
                                                id="adjust-containerinput">
                                                <label class="form-label fw-medium" for="adjust-container">
                                                    Item Count:
                                                </label>
                                                <div class="d-flex">
                                                    <input type="number" class="form-control ps-2 w-50"
                                                        id="adjust-container" name="containercount" disabled>
                                                    <span class="fw-light ms-2 my-auto">Item/s</span>
                                                </div>
                                                <p class="fw-light">Note. Containers with different capacity should be
                                                    added as a separate item.</p>
                                            </div>
                                            <div class="col-lg-3 mb-2">
                                                <label for="adjust-logtype" class="form-label fw-medium">Adjustment
                                                    Type:</label>
                                                <select name="logtype" class="form-select" id="adjust-logtype">
                                                    <option value="" selected>Adjustment Types</option>
                                                    <option value="in">Manual Stock Correction (In)</option>
                                                    <option value="out">Manual Stock Correction (Out)</option>
                                                    <option value="lost">Lost/Damaged Stock</option>
                                                    <option value="used">Used Stock</option>
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
                                        <button type="submit" class="btn btn-grad mx-auto">Adjust Item</button>
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

            <div class="table-responsive-sm d-flex justify-content-center">
                <table class="table align-middle table-hover mx-3 my-2 os-table w-100 text-light">
                    <caption class="text-light">Items with <i class="bi bi-exclamation-diamond"></i> are pending for
                        approval by the
                        Manager.</caption>
                    <thead>
                        <tr class="text-center text-wrap align-middle">
                            <th scope="col">Item No.</th>
                            <th class="w-25">Item Name</th>
                            <th>Stock Quantity</th>
                            <th>Date Received</th>
                            <th>Stock Status</th>
                            <th>Location Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="chemicalTable">
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
            <div class="bg-light bg-opacity-25 rounded mx-3 mt-3 py-2">
                <h1 class="text-center fw-medium">Item Used Summary</h1>
            </div>
            <div class="hstack gap-3 my-3 mx-3">
                <input class="form-control form-custom rounded-pill me-auto py-2 px-3 text-light bg-transparent"
                    placeholder="Search . . ." id="searchChemUsedSummary" name="search" autocomplete="one-time-code">
                <button type="button" id="inventorylogbtn"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded py-2 px-4 text-light"
                    title="Inventory Logs"><i class="bi bi-file-earmark-text"></i></button>
            </div>
            <div class="table-responsive-sm d-flex justify-content-center">
                <table class="table align-middle table-hover m-3 mt-2 os-table w-100 text-light">
                    <thead>
                        <tr class="text-center text-wrap align-middle">
                            <th scope="col">Item No.</th>
                            <th>Item Name & Brand</th>
                            <th>Container Size</th>
                            <th>Stored</th>
                            <th>Used Open</th>
                            <th>Used Close</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="chemStateTable">
                        <!-- ajax chem table -->
                    </tbody>

                </table>
            </div>
            <div class="d-flex justify-content-center mb-5" style="display: none !important;" id="loader2">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div id="table2pagination"></div>


            <div class="bg-light bg-opacity-25 rounded mx-3 mt-3 py-2">
                <h1 class="text-center fw-medium">Container Status Report</h1>
            </div>
            <div class="hstack gap-3 my-3 mx-3">
                <input class="form-control form-custom rounded-pill me-auto py-2 px-3 text-light bg-transparent"
                    placeholder="Search . . ." id="searchContainerStatus" name="search" autocomplete="one-time-code">
                <button type="button" id="inventorylogbtn"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded py-2 px-4 text-light"
                    title="Inventory Logs"><i class="bi bi-file-earmark-text"></i></button>
            </div>
            <div class="table-responsive-sm d-flex justify-content-center">
                <table class="table align-middle table-hover m-3 mt-2 os-table w-100 text-light">
                    <thead>
                        <tr class="text-center text-wrap align-middle">
                            <!-- <th scope="col">Chemical ID</th> -->
                            <th scope="col">Item Name & Brand</th>
                            <th>Container Size</th>
                            <th>In</th>
                            <th>Out</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="containerReportTable">
                        <!-- ajax chem table -->
                    </tbody>

                </table>
            </div>
            <div class="d-flex justify-content-center mb-5" style="display: none !important;"
                id="containerReportLoader">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div id="containerReportPagination"></div>

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

    <script>
        const pagination = 'contents/inv.pagination.php';
        const urldata = 'contents/inv.data.php';
        $(document).ready(async function () {
            await loadpage();
            await overview_display();
        })
        async function loadpagination(pageno) {
            try {
                return $.ajax({
                    type: 'GET',
                    url: pagination,
                    data: {
                        pagenav: 'true',
                        active: pageno
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

        async function loadpagination2(pageno) {
            try {
                return $.ajax({
                    type: 'GET',
                    url: 'contents/state.pagination.php',
                    data: {
                        pagenav: 'true',
                        active: pageno
                    },
                    success: async function (res) {
                        $('#table2pagination').empty();
                        $('#table2pagination').append(res);
                        // window.history.pushState(null, "", "?page=" + pageno);
                    }
                });

            } catch (error) {
                alert(error);
            }
        }
        async function loadpagination3(pageno) {
            try {
                return $.ajax({
                    type: 'GET',
                    url: 'contents/inv.containerstatus.pagination.php',
                    data: {
                        pagenav: 'true',
                        active: pageno
                    },
                    success: async function (res) {
                        $('#containerReportPagination').empty();
                        $('#containerReportPagination').append(res);
                        // window.history.pushState(null, "", "?page=" + pageno);
                    }
                });

            } catch (error) {
                alert(error);
            }
        }

        async function loadtable(page) {
            try {
                return $.ajax({
                    type: 'GET',
                    url: pagination,
                    data: {
                        table: 'true',
                        currentpage: page
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

        async function loadtable2(page) {
            $.ajax({
                method: 'GET',
                url: 'contents/state.pagination.php',
                data: {
                    table: 'true',
                    currentpage: page
                },
                dataType: 'html'
            })
                .done(async function (d) {
                    $("#chemStateTable").empty();
                    $("#chemStateTable").append(d);
                })
                .fail(async function (e) {
                    alert('There was a problem fetching the data of a table. Please try again later.');
                    console.log(e);
                })
        }

        async function loadtable3(page) {
            $.ajax({
                method: 'GET',
                url: 'contents/inv.containerstatus.pagination.php',
                data: {
                    table: 'true',
                    currentpage: page
                },
                dataType: 'html'
            })
                .done(async function (d) {
                    $("#containerReportTable").empty();
                    $("#containerReportTable").append(d);
                })
                .fail(async function (e) {
                    alert('There was a problem fetching the data of a table. Please try again later.');
                    console.log(e);
                })
        }


        $('#pagination').on('click', '.page-link', async function (e) {
            e.preventDefault();
            let currentpage = $(this).data('page');
            await loadpage(currentpage);
        })

        async function loadpage(page = 1) {
            await loadtable(page);
            await loadpagination(page);
            await loadpagination2(page);
            await loadtable2(page);
            await loadtable3(page);
            await loadpagination3(page);
            overview_display();
        }

        $('#pagination').on('click', '.page-link', async function (e) {
            e.preventDefault();

            let currentpage = $(this).data('page');
            await loadtable(currentpage);
            await loadpagination(currentpage);
        })

        $('#table2pagination').on('click', '.page-link', async function (e) {
            e.preventDefault();
            let currentpage = $(this).data('page');
            await loadtable2(currentpage);
            await loadpagination2(currentpage);
        })

        $('#containerReportPagination').on('click', '.page-link', async function (e) {
            e.preventDefault();
            let currentpage = $(this).data('page');
            await loadtable3(currentpage);
            await loadpagination3(currentpage);
        })


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
                            url: pagination,
                            type: 'GET',
                            dataType: 'html',
                            data: {
                                search: search
                            },
                            success: async function (searchChem, status) {
                                if (!search == '') {
                                    $('#chemicalTable').empty();
                                    $('#loader').attr('style', 'display: none !important;');
                                    $('#chemicalTable').append(searchChem);
                                } else {
                                    $('#loader').attr('style', 'display: none !important;');
                                    await loadpage();
                                }
                            }
                        });

                    } catch (error) {
                        alert('Search Error');
                    }
                }, 250);
            });
        });

        $(function () {
            let timeout = null;

            $('#searchChemUsedSummary').keyup(function () {
                clearTimeout(timeout);
                $('#chemStateTable').empty();
                $('#loader2').show();

                timeout = setTimeout(async function () {
                    var search = $('#searchChemUsedSummary').val();
                    await $.ajax({
                        url: "contents/state.pagination.php",
                        type: 'GET',
                        dataType: 'html',
                        data: {
                            search: search,
                        }
                    })
                        .done(async function (searchChem) {
                            $('#loader2').attr('style', 'display: none !important;');
                            if (search !== '') {
                                $('#table2pagination').hide();
                                $('#chemStateTable').empty();
                                $('#chemStateTable').append(searchChem);
                            } else {
                                await loadtable2(1);
                                await loadpagination2(1);
                                $('#table2pagination').show();
                            }
                        })
                        .fail(function (e) {
                            $("#chemStateTable").html("<tr><td scope='row' colspan='8' class='text-center'>Search Failed. Please Try again later.</td></tr>")
                        });
                }, 250);
            });
        });

        $(function () {
            let timeout = null;

            $('#searchContainerStatus').keyup(function () {
                clearTimeout(timeout);
                $('#containerReportTable').empty();
                $('#containerReportLoader').show();

                timeout = setTimeout(async function () {
                    var search = $('#searchContainerStatus').val();
                    await $.ajax({
                        url: "contents/inv.containerstatus.pagination.php",
                        type: 'GET',
                        dataType: 'html',
                        data: {
                            search: search,
                        }
                    })
                        .done(async function (searchChem) {
                            $('#containerReportLoader').attr('style', 'display: none !important;');
                            if (search !== '') {
                                $('#containerReportPagination').hide();
                                $('#containerReportTable').empty();
                                $('#containerReportTable').append(searchChem);
                            } else {
                                await loadtable3(1);
                                await loadpagination3(1);
                                $('#containerReportPagination').show();
                            }
                        })
                        .fail(function (e) {
                            $("#containerReportTable").html("<tr><td scope='row' colspan='8' class='text-center'>Search Failed. Please Try again later.</td></tr>")
                        });
                }, 250);
            });
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

        function overview_display() {
            get_overview_count('total');
            get_overview_count('low');
            get_overview_count('expired');
            get_overview_count('entries');
            get_overview_count('available');
            get_overview_count('dispatched');
            get_overview_count('out-of-stock');
        }



        async function load_other_table(url, tbody_id, page = 1) {
            try {
                const data = await $.get(url, {
                    table: true,
                    currentpage: page
                });

                $(`#${tbody_id}`).empty();
                $(`#${tbody_id}`).append(data);
                return data;

            } catch (e) {
                console.log('Error in load_other_table:', e);
                $(`#${tbody_id}`).html("<tr><td colspan='25'>An error occured loading this table. Please try again later.</td></tr>");
                throw e; // Re-throw to let calling function handle it
            }
        }

        async function load_other_pagination(url, container_id, active_page_no = 1) {
            try {
                const data = await $.get(url, {
                    pagenav: true,
                    active: active_page_no
                });

                $(`#${container_id}`).empty();
                $(`#${container_id}`).append(data);
                return data;

            } catch (e) {
                console.log('Error in load_other_pagination:', e);
                alert(`An error occured loading pagination to container with ID ${container_id}. Please try again later.`);
                throw e;
            }
        }

        async function modal_table_pagination(url, tbody_id, pagination_id, page = 1) {
            try {
                const table = await load_other_table(url, tbody_id, page);
                const pagination = await load_other_pagination(url, pagination_id, page);

                if (pagination && table) {
                    return true;
                }
                return false;
            } catch (e) {
                console.log('Error in modal_table_pagination:', e);
                return false;
            }
        }

        // edit on sAdmin
        $(document).on('click', '#inventorylogbtn', async function () {
            let table = await modal_table_pagination('contents/inv.log.pagination.php', 'log_tbody', 'inv_log_pagination');
            if (table) {
                $('#inventorylogmodal').modal('show');
            } else {
                // console.log(table);
                alert("An error occured when loading log contents. Please try again later.");
            }
            $('#inv_log_pagination').on('click', '.page-link', async function (e) {
                e.preventDefault();
                let currentpage = $(this).data('page');
                await modal_table_pagination('contents/inv.log.pagination.php', 'log_tbody', 'inv_log_pagination', currentpage);
            });

        });

        $(document).on('click', '#entriesTableBtn', async function () {
            let table = await modal_table_pagination('contents/inv.itementries.pagination.php', 'itemEntryTable', 'itemEntriesPagination');
            if (table) {
                $("#itemEntryModal").modal('show');
            } else {
                console.log(table);
                alert("An error occured loading content. Please try again later.");
            }
            $('#itemEntriesPagination').on('click', '.page-link', async function (e) {
                e.preventDefault();
                let currentpage = $(this).data('page');
                await modal_table_pagination('contents/inv.itementries.pagination.php', 'itemEntryTable', 'itemEntriesPagination', currentpage);
            });
        });

        $(document).on('click', '#restockTableBtn', async function () {
            let table = await modal_table_pagination('contents/inv.itemrestock.pagination.php', 'restockTable', 'restockPagination');
            if (table) {
                $("#restockModal").modal('show');
            } else {
                console.log(table);
                alert("An error occured loading content. Please try again later.");
            }
            $('#restockPagination').on('click', '.page-link', async function (e) {
                e.preventDefault();
                let currentpage = $(this).data('page');
                await modal_table_pagination('contents/inv.itemrestock.pagination.php', 'restockTable', 'restockPagination', currentpage);
            });

        });

        $("#restockTable").on('click', '.editbtn', function () {
            $("#restockModal").modal('hide');
        });

        $("#dispatchedTable").on('click', '.editbtn, .returnbtn', function () {
            $("#dispatchedItemsModal").modal('hide');
        })

        async function get_transaction_return(name, brand, csize, unit) {
            $.get(urldata, {
                dispatched_transactions: true,
                name: name,
                brand: brand,
                csize: csize,
                unit: unit
            },
                async function (d) {
                    $("#return_transaction").empty();
                    $("#return_transaction").append(d);
                    console.log(d);
                },
                'html'
            )
                .fail(function (e) {
                    alert("Error in loading dispatched transaction options. Please refresh the page and try again.");
                    console.log(e);
                });
        }

        async function get_transaction_option() {
            $.get(urldata, {
                transaction_options: true
            },
                async function (d) {
                    $("#dispatch-transaction").empty();
                    $("#dispatch-transaction").append(d);
                },
                'html'
            )
                .fail(function (e) {
                    alert("Error in loading transaction options. Please refresh the page and try again.");
                });
        }

        $(document).on('click', '.returnbtn', async function () {
            let id = $(this).data('return');
            $("#returnChemicalForm")[0].reset();
            let deets = await get_chem_details(id);
            var details = JSON.parse(deets);
            let clocation = 'Unknown';
            if (details.location === 'main_storage') {
                clocation = "Main Storage";
            } else if (details.location === 'dispatched') {
                clocation = "Dispatched";
            }

            await qty_unit_options(details.unit, "return_unit");

            $("#returnChemicalId").val(id);
            $("#return_currentLocation").val(details.location);
            $("#return-openedLevel").text(details.level + details.unit);
            $('#returnName').text(details.name);
            $("#return-chemBrand").text(details.brand);
            $('#return-contSize').text(details.container_size + '' + details.unit);
            let opened_container_count = details.level > 0 ? 1 : 0;
            let total_container_count = details.unop_cont + opened_container_count;
            $('#return-containerCount').text(total_container_count + ' Container/s');
            $("#return-cstatus").text(clocation);

            $("#return_openedContainerLevel").text(details.level + details.unit);
            $("#return_closedContainerCount").text(details.unop_cont + " Container/s")

            let transreturn = await get_transaction_return(details.name, details.brand, details.container_size, details.unit);

            $("#returnChemModal").modal('show');
            $("#returnChemModal").on('shown.bs.modal', function () {
                $("p#return_current_transaction_info").text("Please select a valid transaction ID.");
            });
            $("#returnChemicalForm").on('change', "select#return_transaction", function () {
                let transid = $(this).val();

                $.get(urldata, {
                    dispatch_cur_transchem: true,
                    chemId: id,
                    transid: transid,
                    return: true,
                    containerSize: details.container_size
                }, function (d) {
                    if (d.error) {
                        $("p#return_current_transaction_info").text(d.error);
                    } else {
                        if (d.openedLevel === 0) {
                            $("p#return_current_transaction_info").text(d.closedContainer + " Container/s (" + details.container_size + details.unit + ")");
                        } else {
                            $("p#return_current_transaction_info").text(d.openedLevel + details.unit + " (" + d.closedContainer + " Container/s)");
                        }
                    }
                }, 'json')
                    .fail(function (e) {
                        $("p#return_current_transaction_info").text("Please select a valid transaction ID.");
                        console.log(e);
                    });
            });
        });

        $(document).on('click', '.dispatchbtn', async function () {
            let id = $(this).data('dispatch');
            $("#dispatchChemicalForm")[0].reset();
            $("#dispatch-location option").prop('disabled', false);
            // console.log(id);
            await get_transaction_option();
            let deets = await get_chem_details(id);
            var details = JSON.parse(deets);
            // console.log(details);

            let clocation = 'Unknown';
            if (details.location === 'main_storage') {
                clocation = "Main Storage";
            } else if (details.location === 'dispatched') {
                clocation = "Dispatched";
            }

            $("#dispatch_openedContainerLevel").text(details.level + details.unit);
            $('#dispatch_closedContainerCount').text(details.unop_cont + ' Container/s');

            $('#dispatchChemicalId').val(details.id);
            $("#dispatchName").text(details.name)
            $('#dispatch-chemBrand').text(details.brand);
            $('#dispatch-contSize').text(details.container_size + '' + details.unit);
            let opened_container_count = details.level > 0 ? 1 : 0;
            let total_container_count = details.unop_cont + opened_container_count;
            $('#dispatch-containerCount').text(total_container_count + ' Container/s');
            $("#dispatch-cstatus").text(clocation);

            $("#dispatchValue, #includeOpened").prop('disabled', false);
            $("#dispatchAll").on('change', function () {
                let checked = $(this).is(":checked");
                $("#dispatchValue, #includeOpened").prop('disabled', checked);
            })
            $("#dispatchChemModal").modal('show');
            $("#dispatchChemModal").on('shown.bs.modal', function () {
                $("p#current_transaction_info").text("Please select a valid transaction ID.");
            });
            $("#dispatchChemicalForm").on('change', "select#dispatch-transaction", function () {
                let transid = $(this).val();

                $.get(urldata, { dispatch_cur_transchem: true, chemId: id, transid: transid, containerSize: details.container_size }, function (d) {
                    if (d.error) {
                        $("p#current_transaction_info").text(d.error);
                    } else {
                        $("p#current_transaction_info").text(d.openedLevel + details.unit + " (" + d.closedContainer + " Container/s)");
                    }
                    console.log(d);
                }, 'json')
                    .fail(function (e) {
                        $("p#current_transaction_info").text("Please select a transaction.");
                        console.log(e);
                    });
            });
        });

        $(document).on('click', '#dispatchedTableBtn', async function () {
            let table = await modal_table_pagination('contents/inv.dispatcheditems.pagination.php', 'dispatchedTable', 'dispatchedTablePagination');
            if (table) {
                $("#dispatchedItemsModal").modal('show');
            } else {
                console.log(table);
                alert("An error occured loading content. Please try again later.");
            }
            $('#dispatchedTablePagination').on('click', '.page-link', async function (e) {
                e.preventDefault();
                let currentpage = $(this).data('page');
                await modal_table_pagination('contents/inv.dispatcheditems.pagination.php', 'dispatchedTable', 'dispatchedTablePagination', currentpage);
            });
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

        $("#restockTable").on('click', '.restock-btn', async function () {
            let id = $(this).data('restock');
            $("#restockModal").modal('hide');
            // console.log(id);
            let deets = await get_chem_details(id);
            var details = JSON.parse(deets);
            // console.log(details);

            let opened_level = details.level == 0 ? "Empty" : `${details.level}/${details.container_size}${details.unit}`;
            let tcontainer = details.unop_cont + (details.level > 0 ? 1 : 0);
            // console.log(opened_level);
            $("#id_input").val(details.id);
            $("#restock_id").text(details.id);
            $("#restock_name").text(details.name);
            $("#restock_brand").text(details.brand);
            $("#restock_expiry").text(details.expDate);
            $("#restock_size").text(details.container_size + details.unit);
            $("#restock_opened").text(opened_level);
            $("#restock_ccontainer").text(details.unop_cont);
            $("#restock_threshold").text(details.threshold);
            $("#restock_tcontainer").text(tcontainer);

            $("#restockFunctionModal").modal("show");

        });

      
        $(document).on('click', '.editbtn', async function () {
            let id = $(this).data('chem');
            let deets = await get_chem_details(id);
            var details = JSON.parse(deets);
            // console.log(details);

            $('#submitEdit, #toggleEditBtn').attr('disabled', function () {
                return details.req == 1 ? true : false;
            });

            var today = new Date();
            var exp = new Date(details.expDate);
            if (exp <= today) {
                $("#expdatewarning").html('Caution. Item Expired.').toggleClass('d-none');
            } else {
                $('#expdatewarning').html('').addClass('d-none');
            }

            let clocation = 'Not defined';
            if (details.location == 'main_storage') {
                clocation = "Main Storage";
            } else if (details.location == 'dispatched') {
                clocation = "Dispatched";
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
            $('#edit-restockThreshold').val(details.threshold);
            $('#view-chemUnit').text(details.unit);
            $('#addinfo').html(function () {
                return details.addby === 'No Record' ? 'Added at: ' + details.addat : 'Added at: ' + details.addat + ' by ' + details.addby;
            });
            $('#updateinfo').html(function () {
                return details.upby === 'No Update Record' ? 'Updated at: ' + details.upat : 'Updated at: ' + details.upat + ' by ' + details.upby;
            });

            $("#openedContainerLevel").text(details.level + details.unit);
            $("#closedContainerCount").text(details.unop_cont + " Container/s");

            let reqAlertStatus = $('#reqalert').hasClass('d-none');
            let chemstate = $("#chemState").hasClass('d-none');
            if (details.req == 1) {
                $('#reqalert').removeClass('d-none').html('This item is pending for approval by the Manager. You can only view the details of this item.').fadeIn(750);
                $('#chemState').addClass('d-none');
            } else {
                if (!reqAlertStatus) {
                    $('#reqalert').addClass('d-none');
                }
                if (chemstate) {
                    $('#chemState').removeClass('d-none');
                }
                $('#reqalert').html('');
            }

            $('#editModal').modal('show');

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

        async function load_chem_log_history(id, currentpage) {
            return $.get('contents/inv.itemloghistory.pagination.php', {
                table: true,
                chemid: id,
                currentpage: currentpage
            })
                .done(function (data) {
                    $('#chemicallogmodal .modal-body #chemicalhistorylogtable').empty();
                    $('#chemicallogmodal .modal-body #chemicalhistorylogtable').append(data);
                })
                .fail(function (e) {
                    console.error(e);
                    $('#chemicallogmodal .modal-body .tab-pane.fade').html('<p class="text-center text-danger">Error loading item log.</p>');
                });
        }

        $(document).on('submit', '#returnChemicalForm', async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            $.ajax({
                method: 'POST',
                url: urldata,
                data: $(this).serialize() + "&return_chemical=true",
                dataType: 'json'
            })
                .done(async function (d) {
                    if (d.success) {
                        show_toast(d.success);
                        $("#returnConfirmationModal").modal('hide');
                        loadpage(1, entryHidden);
                    } else {
                        alert('An unknown error has occured. Please try again later.');
                    }
                })
                .fail(function (e) {
                    $("#returnAlert").html(e.responseText).fadeIn(750).delay(2000).fadeOut(1000);
                    console.log(e);
                })
        });

        $(document).on('submit', '#dispatchChemicalForm', async function (e) {
            e.preventDefault();
            // console.log($(this).serialize());

            $.ajax({
                method: 'POST',
                url: urldata,
                data: $(this).serialize() + "&dispatch=true",
                dataType: 'json'
            })
                .done(async function (d) {
                    if (d.success) {
                        show_toast(d.success);
                        $("#dispatchConfirmationModal").modal('hide');
                        loadpage(1, entryHidden);
                    } else {
                        alert('An unknown error has occured. Please try again later.');
                    }
                })
                .fail(function (e) {
                    $("#dispatchAlert").html(e.responseText).fadeIn(750).delay(2000).fadeOut(1000);
                })
        });


        async function load_item_history_pagination(id, page) {
            return $.get("contents/inv.itemloghistory.pagination.php", {
                pagenav: true,
                active: page,
                id: id
            }, function (d) {
                $("#chem_log_pagination").empty();
                $("#chem_log_pagination").append(d);
            }, 'html')
                .fail(function (e) {
                    console.log(e);
                    $('#chem_log_pagination').html('<p class="text-center text-danger">Error loading pagination.</p>');
                })
        }

        async function item_history(id, page = 1) {
            try {
                await load_chem_log_history(id, page);
                await load_item_history_pagination(id, page);
            } catch (e) {
                console.log(e);
                alert("An error has occured loading item history. Please try again later.");
            }
        }

        async function setup_pagination_event(id) {
            $("#chem_log_pagination").off('click', '.page-link');
            $('#chem_log_pagination').on('click', '.page-link', async function (e) {
                e.preventDefault();
                let currentpage = $(this).data('page');
                await item_history(id, currentpage);
            });
        }

        async function qty_unit_options(current_unit, select_id) {
            $.get(urldata, {
                qty_unit_options: true,
                current_unit: current_unit
            },
                async function (d) {
                    $(`select#${select_id}`).empty();
                    $(`select#${select_id}`).append(d);
                },
                'html'
            )
                .fail(function (e) {
                    alert("Error in loading quantity unit options. Please refresh the page and try again.");
                    console.log(e);
                });
        }

        async function get_chem_log(id) {
            return $.ajax({
                method: 'GET',
                url: urldata,
                data: {
                    chemLog: true,
                    id: id
                },
                dataType: 'json'
            }).done(async function (data) {
                // console.log(data);

                if (data.success) {
                    let d = JSON.parse(data.success);
                    $(".log-chem-id").val(d.id);
                    $(".chem-name").val(d.name);
                    // $(".qty-unit").text(' - ' + d.quantity_unit);
                    $("#main_qty_unit").val(d.quantity_unit);

                    await qty_unit_options(d.quantity_unit, "adjust_qty_unit");

                    $("#adjust-curlevel").text(d.chemLevel + '/' + d.container_size + d.quantity_unit + ' (' + d.unop_cont + ' container/s left.)');
                    $("#adjust-dispatched").text(d.chem_location === 'dispatched' ? "Item dispatched." : "Item available.");

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
                            $("#adjust_qty_unit").prop('disabled', true);
                        } else {
                            $("#adjust-containerinput").hide();
                            $("#adjust-containerinput input").prop('disabled', true);
                            $("#adjust-qty").prop('disabled', false);
                            $("#adjust_qty_unit").prop('disabled', false);
                        }
                    })


                } else {
                    alert('Unknown Error. Please try again later.');
                }
            })
                .fail(function (e) {
                    console.log(e);
                })
        }

        $(document).on('click', '.log-chem-btn', async function () {
            let id = $(this).data('chem');
            try {
                await item_history(id);
                await setup_pagination_event(id);
                await get_chem_log(id);
                $('#chemicallogmodal').modal('show');

            } catch (error) {
                console.error(error);
            }
        });

    </script>

    <?php include('footer.links.php'); ?>
</body>

</html>