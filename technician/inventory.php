<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician | Chemicals</title>
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

            <div class="hstack gap-2 my-3 mx-3">
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
                <button type="button" id="loadChem"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded text-light py-2 px-4" data-bs-toggle="modal"
                    data-bs-target="#addModal" data-bs-toggle="tooltip" title="Add Stock"><i
                        class="bi bi-plus-square"></i></button>
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
                                                    <?= $_SESSION['fname'] . ' ' . $_SESSION['lname'] ?>
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
                <table class="table align-middle table-hover m-3 os-table w-100 text-light">
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

        $('#pagination').on('click', '.page-link', async function (e) {
            e.preventDefault();
            let currentpage = $(this).data('page');
            await loadpage(currentpage);
        })

        async function loadpage(page) {
            await loadtable(page);
            await loadpagination(page);
        }

        async function loadpage(page = 1) {
            await Promise.all([
                await loadtable(page),
                await loadpagination(page)
            ])
        }

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

    </script>

    <?php include('footer.links.php'); ?>
</body>

</html>