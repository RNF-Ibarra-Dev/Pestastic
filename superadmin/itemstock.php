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
    <title>Manager | Inventory</title>
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
                <!-- <button type="button" id="hideentries"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded py-2 w-25 px-2 text-light"
                    title="Hide Entries"><i class="bi bi-eye-slash me-2"></i><span id="hideEnText">Hide
                        Entries</span></button> -->
                <button type="button" id="approvemulti"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded w-25 py-2 px-2 text-light fw-bold d-flex align-content-center justify-content-center"><i
                        class="bi bi-clock-fill me-2" data-bs-toggle="modal" data-bs-toggle="tooltip"
                        data-bs-target="#multiapproveModal" title="Approve multiple stocks"></i>Pending Entries</button>
                <button type="button" id="restockTableBtn"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded w-25 py-2 px-2 text-light fw-bold d-flex align-content-center justify-content-center"><i
                        class="bi bi-box-fill me-2"></i>Restock Items</button>
                <button type="button" id="dispatchedTableBtn"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded w-25 py-2 px-2 text-light fw-bold d-flex align-content-center justify-content-center"><i
                        class="bi bi-truck-flatbed me-2"></i>Dispatched Items</button>
                <select
                    class="form-select select-transparent bg-light bg-opacity-25 py-2 border-0 h-100 text-light fw-bold w-25"
                    id="sortbranches" aria-label="Default select example">
                </select>
                <input class="form-control form-custom rounded-pill me-auto py-2 px-3 text-light" type="search"
                    placeholder="Search . . ." id="searchbar" name="searchforafuckingchemical"
                    autocomplete="one-time-code">
                <button type="button" id="inventorylogbtn"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded py-2 px-4 text-light"
                    title="Inventory Logs"><i class="bi bi-file-earmark-text"></i><span id="hideEnText"></button>
                <!-- <button type="button" id="approvemulti"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded py-2 px-4 text-light" data-bs-toggle="modal"
                    data-bs-toggle="tooltip" data-bs-target="#multiapproveModal" title="Approve multiple stocks"><i
                        class="bi bi-list-check"></i></button> -->
                <button type="button" id="loadChem"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded text-light py-2 px-4" data-bs-toggle="modal"
                    data-bs-target="#addModal" data-bs-toggle="tooltip" title="Add Stock"><i
                        class="bi bi-plus-square"></i></button>

            </div>

            <!-- restock -->
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
                                    <?= $_SESSION['saUsn'] ?>'s password to confirm.</label>
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
                            <div class="table-responsive-sm flex-column d-flex justify-content-center">
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
                                <div id="inv_log_pagination"></div>
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
                                    <div class="table-responsive-sm flex-column d-flex justify-content-center">
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

            <!-- edit chemical -->
            <form id="editChemForm">
                <div class="row g-2 text-dark">
                    <div class="modal fade text-dark modal-edit" id="editModal" tabindex="-1" aria-labelledby="edit"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Item Details<span class="text-secondary mx-2">|</span>Edit Item
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
                                                class="form-control-plaintext ps-2" readonly
                                                autocomplete="one-time-code">
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
                                                class="form-control-plaintext ps-2" readonly
                                                autocomplete="one-time-code">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-3 mb-2">
                                            <label for="edit-dateReceived" class="form-label fw-medium">Date
                                                Received:</label>
                                            <input type="date" name="edit-receivedDate" id="edit-dateReceived"
                                                class="ps-2 form-control-plaintext form-add form-date" disabled>
                                        </div>
                                        <div class="col-3 mb-2">
                                            <label for="edit-expDate" class="form-label fw-medium">Expiry Date:</label>
                                            <input type="date" name="edit-expDate" id="edit-expDate"
                                                class="ps-2 form-control-plaintext form-date" autocomplete="off"
                                                disabled>
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
                                        <div class="row mb-2 user-select-none" id="chemState">
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
                                    <label for="pass" class="form-label fw-light">Change item information? Enter manager
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
                                    disabled-name="delete">Edit Item</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- add new chemical -->
            <form id="addForm">
                <div class="row g-2 text-dark">
                    <div class="modal-xl modal fade text-dark modal-edit" data-bs-backdrop="static" id="addModal"
                        tabindex="-1" aria-labelledby="create" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <i class="bi bi-plus-square me-2"></i>
                                    <h1 class="modal-title fs-5">Add New Item</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-2">
                                        <div class="col-lg-3 mb-2">
                                            <label for="name" class="form-label fw-light">Item Name</label>
                                            <input type="text" name="name[]" id="add-name" class="form-control form-add"
                                                autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-3 mb-2">
                                            <label for="chemBrand" class="form-label fw-light">Item Brand</label>
                                            <input type="text" name="chemBrand[]" id="add-chemBrand"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="chemLevel" class="form-label fw-light">Item Size</label>
                                            <input type="text" name="containerSize[]" id="add-chemLevel"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="add-chemUnit" class="form-label fw-light">Item
                                                Unit:</label>
                                            <select name="chemUnit[]" id="add-chemUnit" class="form-select"
                                                autocomplete="one-time-code">
                                                <option value="" selected>Item Unit</option>
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
                                            <label for="chemLevel" class="form-label fw-light">Item Count:</label>
                                            <input type="text" name="containerCount[]" id="add-chemLevel"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-2 mb-2">
                                            <label for="restockThreshold" class="form-label fw-light">Restock
                                                Threshold:</label>
                                            <input type="number" name="restockThreshold[]" id="restockThreshold"
                                                class="form-control" autocomplete="one-time-code">
                                            <!-- <div class="text-body-secondary fw-light text-muted mt-2">
                                                Note: Set threshold where chemical container count should be restocked immediately.
                                            </div> -->
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="expDate" class="form-label fw-light">Date Received</label>
                                            <input type="date" name="receivedDate[]" id="add-dateReceived"
                                                class="form-control form-add form-date-rec">
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="expDate" class="form-label fw-light">Expiry Date</label>
                                            <input type="date" name="expDate[]" id="add-expDate"
                                                class="form-control form-add form-date">
                                            <div class="text-body-secondary fw-light text-muted mt-2">
                                                Note: specify expiry date or default date will be set.
                                            </div>
                                        </div>
                                        <div class="col-3 mb-2">
                                            <label for="notes" class="form-label fw-light">Short Note</label>
                                            <textarea name="notes[]" id="notes" class="form-control"
                                                placeholder="Optional short note . . . "></textarea>
                                        </div>
                                        <div id="addMoreChem"></div>
                                        <hr class="mt-2 mb-3">
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-grad" id="addMoreChemBtn"><i
                                                    class="bi bi-plus-circle-dotted  me-2"></i> Add More
                                                Item</button>
                                            <select name="target_branch" id="add_target_branch"
                                                class="form-select w-50"></select>
                                        </div>


                                    </div>
                                    <div class="modal-footer d-flex justify-content-between">
                                        <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                            data-bs-target="#areyousureaboutthat">Cancel</button>
                                        <button type="button" class="btn btn-grad" disabled-id="submitAdd"
                                            data-bs-toggle="modal" data-bs-target="#confirmAdd">Proceed &
                                            Confirm</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade text-dark modal-edit" id="areyousureaboutthat" data-bs-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title">
                                <h1 class="modal-title fs-5 text-light">Cancel New Item Stock Entry?</h1>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to stop this item entry?
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-grad" data-bs-target="#addModal"
                                    data-bs-toggle="modal">Go Back</button>
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel
                                    Entry</button>
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
                                <h1 class="modal-title fs-5" id="verifyAdd">Add New Item</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="pass" class="form-label fw-light">Add Item? Enter manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control" id="addPwd">
                                    </div>
                                </div>
                                <p class="text-center alert alert-info w-75 mx-auto" style="display: none !important;"
                                    id="aea">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#addModal"
                                    data-bs-toggle="modal">Go
                                    back</button>
                                <button type="submit" class="btn btn-grad" id="submitAdd" disabled-id="edit-confirm"
                                    disabled-name="delete">Add New Item</button>
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
                                    <label for="pass" class="form-label fw-light">Dispatch Item? Enter Operations
                                        Supervisor
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
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
                                    <label for="pass" class="form-label fw-light">Return Item? Enter Operations
                                        Supervisor
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
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

            <!-- delete modal -->
            <form id="deleteForm">
                <input type="hidden" id="delChemId" name="chemid">
                <div class="modal fade text-dark modal-edit" id="deleteModal" tabindex="0"
                    aria-labelledby="verifyChanges" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyChanges">Item Deletion</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="pass" class="form-label fw-light">Are you sure you want to
                                        delete this product? Enter Manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control" id="manPass">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    Note: deletion of items are irreversible.
                                </div>
                                <p class="text-center alert alert-info w-75 mx-auto visually-hidden"
                                    id="del-emptyInput">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-grad" id="delsub">Delete
                                    Item</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="multiapprove">
                <div class="modal modal-xl fade text-dark modal-edit" data-bs-backdrop="static" id="multiapproveModal"
                    tabindex="0" aria-labelledby="confirmAdd" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title">
                                <h1 class="modal-title fs-5 text-light">
                                    <i class="bi bi-clock-fill me-1"></i>
                                    Stock Entries
                                </h1>
                                <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x"></i></button>
                            </div>

                            <div class="modal-body text-dark p-3">
                                <div class="table-responsive-sm  d-flex justify-content-center">
                                    <table class="table align-middle table-hover w-100" id="approvechemtable">
                                        <caption class="fw-light text-muted">List of items added by the Operations
                                            Supervisor.</captio>
                                        <thead>
                                            <tr class="text-center align-middle">
                                                <th scope="col">Item ID</th>
                                                <th>Name</th>
                                                <th>Brand</th>
                                                <th>Item Size/Volume</th>
                                                <th>Date Received</th>
                                                <th>Item Expiry</th>
                                                <th>Added By</th>
                                                <th>
                                                    <input type="checkbox" class="btn-check" id="approveall"
                                                        autocomplete="off">
                                                    <label class="btn fw-bold" for="approveall">Approve All <i
                                                            id="approveicon" class="bi bi-square ms-2"></i></label>
                                                </th>
                                                <th>
                                                    <input type="checkbox" class="btn-check" id="checkallreject"
                                                        autocomplete="off">
                                                    <label class="btn fw-bold" for="checkallreject">Reject All <i
                                                            id="rejecticon" class="bi bi-square ms-2"></i></label>
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
                                <h1 class="modal-title fs-5">Confirmation</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="confirmapprove-inputpwd" class="form-label fw-light">Approve/Reject
                                        entries?
                                        Enter manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control"
                                            id="confirmapprove-inputpwd">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    Note: Rejected entries will get deleted, proceed with caution.
                                </div>
                                <p class="text-center alert alert-info w-75 mx-auto visually-hidden"
                                    id="approvemulti-errmsg">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#multiapproveModal">Go Back</button>
                                <button type="submit" class="btn btn-grad" id="approvemultichem">Approve
                                    Entries</button>
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
                                <h1 class="modal-title fs-5" id="verifyChanges">Entry Approval</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="approve-inputpwd" class="form-label fw-light">Approve Item <span
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
                                    Entry</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- tabble -->
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

    <?php include('footer.links.php'); ?>

    <script>
        const pageurl = 'tablecontents/pagination.php';
        const dataurl = 'tablecontents/chemicals.php';

        function show_toast(message) {
            $('#toastmsg').html(message);
            var toastid = $('#toast');
            var toast = new bootstrap.Toast(toastid);
            toast.show();
        }

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

        $(document).on('click', "#loadChem", function () {
            $("#addMoreChem").empty();
            $("#addForm")[0].reset();

            $.get(dataurl, {
                add_select_branches: true
            })
                .done(function (d) {
                    $("#add_target_branch").append(d);
                })
                .fail(function (e) {
                    console.log(e);
                    alert('There is an error at loading branches. Please try again later.');
                })
        });

        $(document).on('click', '.remove-btn', function () {
            $(this).parent().parent().remove();
        })

        $(document).on('click', '#addMoreChemBtn', async function () {
            $.get(dataurl, "addrow=true")
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


        $(document).on('change', '#add-approved', function () {
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
        function update_checks() {
            let totalRows = $('tbody#approve-chemtable tr').length;

            let approveChecked = $('tbody#approve-chemtable .chkbox-approve:checked').length;
            if (approveChecked === totalRows && totalRows > 0) {
                $('#approveall').prop('checked', true);
                $('#approveicon').removeClass('bi-square').addClass('bi-check-square');
            } else {
                $('#approveall').prop('checked', false);
                $('#approveicon').removeClass('bi-check-square').addClass('bi-square');
            }

            let rejectChecked = $('tbody#approve-chemtable .chkbox-reject:checked').length;
            if (rejectChecked === totalRows && totalRows > 0) {
                $('#checkallreject').prop('checked', true);
                $('#rejecticon').removeClass('bi-square').addClass('bi-x-square');
            } else {
                $('#checkallreject').prop('checked', false);
                $('#rejecticon').removeClass('bi-x-square').addClass('bi-square');
            }
        }

        $(document).on('click', '#approvemulti', async function () {
            $('#multiapprove')[0].reset();
            await stock_requests();
            $('#multiapproveModal').modal('show');
        });

        $(document).on('change', '#approveall', function () {
            let checked = $(this).prop('checked');

            $('#approveicon').toggleClass('bi-square bi-check-square');
            $('tbody#approve-chemtable .chkbox-approve').prop('checked', checked);

            if (checked) {
                $('tbody#approve-chemtable .chkbox-reject').prop('checked', false);
                $('#checkallreject').prop('checked', false);
                $('#rejecticon').removeClass('bi-x-square').addClass('bi-square');
            }
        });

        // Reject All
        $(document).on('change', '#checkallreject', function () {
            let checked = $(this).prop('checked');

            $('#rejecticon').toggleClass('bi-square bi-x-square');
            $('tbody#approve-chemtable .chkbox-reject').prop('checked', checked);

            if (checked) {
                $('tbody#approve-chemtable .chkbox-approve').prop('checked', false);
                $('#approveall').prop('checked', false);
                $('#approveicon').removeClass('bi-check-square').addClass('bi-square');
            }
        });

        $(document).on('change', '.chkbox-approve', function () {
            if ($(this).prop('checked')) {
                // Uncheck reject in the same row
                $(this).closest('tr').find('.chkbox-reject').prop('checked', false);
            }
            update_checks();
        });

        $(document).on('change', '.chkbox-reject', function () {
            if ($(this).prop('checked')) {
                // Uncheck approve in the same row
                $(this).closest('tr').find('.chkbox-approve').prop('checked', false);
            }
            update_checks();
        });

        async function stock_requests() {
            try {
                let branch = $("#sortbranches").val();
                const stock = await $.ajax({
                    method: 'GET',
                    url: dataurl,
                    dataType: 'html',
                    data: {
                        stock: true,
                        branch: branch
                    },
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

        $(document).on('submit', '#multiapprove', async function (e) {
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


        $(document).on('click', '#approvebtn', async function () {
            $('#confirmapprove')[0].reset();
            let chemId = $(this).data('id');
            let name = $(this).data('name');
            $("#approve-id").val(chemId);
            $('#chemname').html(name);
        });

        $(document).on('submit', '#confirmapprove', async function (e) {
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


        async function loadpagination2(pageno, branch = null) {
            try {
                return $.ajax({
                    type: 'GET',
                    url: 'tablecontents/state.pagination.php',
                    data: {
                        pagenav: 'true',
                        active: pageno,
                        branch: branch
                    },
                    success: async function (res) {
                        $('#table2pagination').empty();
                        $('#table2pagination').append(res);
                    }
                });

            } catch (error) {
                alert(error);
            }
        }
        async function loadpagination3(pageno, branch = null) {
            try {
                return $.ajax({
                    type: 'GET',
                    url: 'tablecontents/inv.containerstatus.pagination.php',
                    data: {
                        pagenav: 'true',
                        active: pageno,
                        branch: branch
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

        $(document).ready(async function () {
            get_sa_id();
            await loadpage(1);

            $.get(dataurl, {
                branchoptions: true
            })
                .done(function (d) {
                    $("#sortbranches").append(d);
                })
                .fail(function (e) {
                    console.log('error appending branches option');
                })
        });


        function get_overview_count(container, branch) {
            $.get(dataurl, {
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
            get_overview_count('available', branch);
            get_overview_count('dispatched', branch);
            get_overview_count('out-of-stock', branch);
        }

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
                    success: async function (res) {
                        // console.log(res);

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
                success: function (data) {
                    $('#chemicalTable').empty();
                    $('#chemicalTable').append(data);
                },
                error: function (err) {
                    alert('loadtable func error:' + err);
                }
            });

        }

        async function loadtable2(page, branch = null) {
            $.ajax({
                method: 'GET',
                url: 'tablecontents/state.pagination.php',
                data: {
                    table: 'true',
                    currentpage: page,
                    branch: branch
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

        async function loadtable3(page, branch = null) {
            $.ajax({
                method: 'GET',
                url: 'tablecontents/inv.containerstatus.pagination.php',
                data: {
                    table: 'true',
                    currentpage: page,
                    branch: branch
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

        async function loadpage(page, entryHidden = false, branch = null) {
            await loadtable(page, entryHidden, branch);
            await loadpagination(page, entryHidden, branch);
            await loadpagination2(page, branch);
            await loadtable2(page, branch);
            await loadtable3(page, branch);
            await loadpagination3(page, branch);
            overview_display(branch);
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

        let branch = null;

        $(document).on('change', '#sortbranches', async function () {
            branch = $(this).val();
            // console.log(branch);
            await loadpage(1, entryHidden, branch);
            $("#searchbar, #searchChemUsedSummary, #searchContainerStatus").val('');
            let pagination1 = $("#pagination");
            if (pagination1.hasClass('d-none')) {
                $('#pagination').removeClass('d-none');
            }
            $('#table2pagination,#containerReportPagination').show();
            overview_display(branch);
            // return branch;
        })

        $(document).on('click', '#hideentries', async function () {
            await hide_entries();
        });

        $('#pagination').on('click', '.page-link', async function (e) {
            e.preventDefault();

            let branch = $("#sortbranches").val();
            let currentpage = $(this).data('page');

            await loadtable(currentpage, entryHidden, branch);
            await loadpagination(currentpage, entryHidden, branch);
        })



        // search
        $(function () {
            let timeout = null;

            $('#searchbar').keyup(function () {
                let branch = $("#sortbranches").val();
                clearTimeout(timeout);
                $('#chemicalTable').empty();
                // $('#chemicalTable').append($('#loader'))
                // $('#loader').removeClass('visually-hidden');
                $('#loader').css('display', 'block');

                timeout = setTimeout(async function () {
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
                            success: async function (searchChem, status) {
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
            }, function (data, status) {
                // console.log(data + ' status ' + status);
                $('#idForDeletion').val(data);
                // var saID = data;
                // console.log($('#idForDeletion').val());
                // console.log(saID);   
                // return saID; 
            })
        }

        // edit chemical
        $(document).on('submit', '#editChemForm', async function (e) {
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
                url: dataurl,
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
                    url: dataurl,
                    data: $(this).serialize() + '&action=add',
                    dataType: 'json'
                });
                if (add.success) {
                    $('#chemicalTable').empty();
                    await loadpage(1);
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
            return $.get(dataurl, {
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
        });

        $(document).on('click', '#inventorylogbtn', function () {
            $('#inventorylogmodal').modal('show');
            $.get('tablecontents/inv.log.pagination.php', {
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

        let toggled = false;

        function toggle() {
            $('#submitEdit').toggleClass('d-none');
            $('#view-chemUnit, #edit-chemUnit').toggleClass('d-none');
            $('#edit-notes, #edit-name, #edit-chemBrand, #edit-chemLevel, #edit-contSize, #edit-containerCount, #edit-restockThreshold').attr('readonly', function (i, a) {
                return a ? false : true;
            });
            $("#edit-expDate, #edit-dateReceived, #edit-chemUnit").attr('disabled', function (i, a) {
                return a ? false : true;
            });

            $("#toggleEditBtn").html(function (i, a) {
                return a.includes('Close Edit') ? 'Edit' : 'Close Edit';
            });
            $('#edit-notes, #edit-name, #edit-chemBrand, #edit-chemLevel, #edit-expDate, #edit-dateReceived, #edit-contSize, #edit-containerCount, #edit-restockThreshold').toggleClass('form-control-plaintext form-control');

            return toggled = toggled ? false : true;
        }

        async function qty_unit_options(current_unit, select_id) {
            $.get(dataurl, {
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

        $(document).on('click', '.editbtn', async function () {
            $('#editChemForm')[0].reset();
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
                $("#expdatewarning").html('Caution. Chemical Expired.').toggleClass('d-none');
            } else {
                $('#expdatewarning').html('').addClass('d-none');
            }

            // let clocation = 'Not defined';
            // if (details.location == 'main_storage') {
            //     clocation = "Main Storage";
            // } else if (details.location == 'dispatched') {
            //     clocation = "Dispatched";
            // }

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

            let reqAlertStatus = $('#reqalert').hasClass('d-none');
            let chemstate = $("#chemState").hasClass('d-none');
            if (details.req == 1) {
                $('#reqalert').removeClass('d-none').html('This chemical is pending for approval by the Manager. You can only view the details of this chemical.').fadeIn(750);
                $('#chemState').removeClass('d-none');

            } else {
                if (!reqAlertStatus) {
                    $('#reqalert').addClass('d-none');
                }
                if (chemstate) {
                    $('#chemState').removeClass('d-none');
                }
                $('#reqalert').html('');
            }

            $("#openedContainerLevel").text(details.level + details.unit);
            $("#closedContainerCount").text(details.unop_cont + " Container/s");

            if (toggled) {
                toggle();
            }

            $('#editModal').modal('show');

        });

        async function load_chem_log_history(id, currentpage) {
            return $.get('tablecontents/inv.itemloghistory.pagination.php', {
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

        async function load_item_history_pagination(id, page) {
            return $.get("tablecontents/inv.itemloghistory.pagination.php", {
                pagenav: true,
                active: page,
                id: id
            }, function (d) {
                $("#chem_log_pagination").empty();
                $("#chem_log_pagination").append(d);
            }, 'html')
                .fail(function (e) {
                    console.log(e);
                    $('#chem_log_pagination').html('<p class="text-center text-warning">Error loading pagination.</p>');
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

        async function get_chem_log(id) {
            // console.log(id);
            $.ajax({
                method: 'GET',
                url: dataurl,
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

                    $('#chemicallogmodal').modal('show');

                } else {
                    alert('Unknown Error. Please try again later.');
                }
            })
                .fail(function (e) {
                    console.log(e);
                })
        }

        $(document).on('click', '.log-chem-btn', function () {
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
                url: dataurl,
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

        $(function () {
            let timeout = null;

            $('#searchChemUsedSummary').keyup(function () {
                clearTimeout(timeout);
                let branch = $("#sortbranches").val();
                $('#chemStateTable').empty();
                $('#loader2').show();

                timeout = setTimeout(async function () {
                    var search = $('#searchChemUsedSummary').val();
                    await $.ajax({
                        url: "tablecontents/state.pagination.php",
                        type: 'GET',
                        dataType: 'html',
                        data: {
                            search: search,
                            branch: branch
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
                let branch = $("#sortbranches").val();
                $('#containerReportTable').empty();
                $('#containerReportLoader').show();

                timeout = setTimeout(async function () {
                    var search = $('#searchContainerStatus').val();
                    await $.ajax({
                        url: "tablecontents/inv.containerstatus.pagination.php",
                        type: 'GET',
                        dataType: 'html',
                        data: {
                            search: search,
                            branch: branch
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

        $('#table2pagination').on('click', '.page-link', async function (e) {
            e.preventDefault();
            let currentpage = $(this).data('page');
            let branch = $("#sortbranches").val();
            await loadtable2(currentpage, branch);
            await loadpagination2(currentpage, branch);
        })

        $('#containerReportPagination').on('click', '.page-link', async function (e) {
            e.preventDefault();
            let currentpage = $(this).data('page');
            let branch = $("#sortbranches").val();
            await loadtable3(currentpage, branch);
            await loadpagination3(currentpage, branch);
        });



        async function get_transaction_return(name, brand, csize, unit) {
            $.get(dataurl, {
                dispatched_transactions: true,
                name: name,
                brand: brand,
                csize: csize,
                unit: unit
            },
                async function (d) {
                    $("#return_transaction").empty();
                    $("#return_transaction").append(d);
                },
                'html'
            )
                .fail(function (e) {
                    alert("Error in loading dispatched transaction options. Please refresh the page and try again.");
                    console.log(e);
                });
        }


        $(document).on('click', '.returnbtn', async function () {
            let id = $(this).data('return');
            console.log(id);
            $("#returnChemicalForm")[0].reset();
            let deets = await get_chem_details(id);
            var details = JSON.parse(deets);
            console.log(details);
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
            // if (!transreturn) {
            //     $("#returnChemicalForm button.return-proceed-btn, #returnChemicalForm button[type='submit']").prop('disabled', true);
            // } else {
            //     $("#returnChemicalForm button.return-proceed-btn, #returnChemicalForm button[type='submit']").prop('disabled', false);
            // }
            // console.log(transreturn);

            $("#returnChemModal").modal('show');
            $("#returnChemModal").on('shown.bs.modal', function () {
                $("p#return_current_transaction_info").text("Please select a valid transaction ID.");
            });
            $("#returnChemicalForm").on('change', "select#return_transaction", function () {
                let transid = $(this).val();

                $.get(dataurl, { dispatch_cur_transchem: true, chemId: id, transid: transid, return: true, containerSize: details.container_size }, function (d) {
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

        async function get_transaction_option() {
            $.get(dataurl, {
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

                $.get(dataurl, { dispatch_cur_transchem: true, chemId: id, transid: transid, containerSize: details.container_size }, function (d) {
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


        $(document).on('submit', '#returnChemicalForm', async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            $.ajax({
                method: 'POST',
                url: dataurl,
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
                url: dataurl,
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

        async function load_other_table(url, tbody_id, page = 1, branch) {
            try {
                const data = await $.get(url, {
                    table: true,
                    currentpage: page,
                    branch: branch
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

        async function load_other_pagination(url, container_id, active_page_no = 1, branch) {
            try {
                const data = await $.get(url, {
                    pagenav: true,
                    active: active_page_no,
                    branch: branch
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

        async function modal_table_pagination(url, tbody_id, pagination_id, page = 1, branch) {
            try {
                const table = await load_other_table(url, tbody_id, page, branch);
                const pagination = await load_other_pagination(url, pagination_id, page, branch);

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
            let table = await modal_table_pagination('tablecontents/inv.log.pagination.php', 'log_tbody', 'inv_log_pagination', 1, branch);
            if (table) {
                $('#inventorylogmodal').modal('show');
            } else {
                console.log(table);
                alert("An error occured when loading log contents. Please try again later.");
            }
            $('#inv_log_pagination').on('click', '.page-link', async function (e) {
                e.preventDefault();
                let currentpage = $(this).data('page');
                await modal_table_pagination('tablecontents/inv.log.pagination.php', 'log_tbody', 'inv_log_pagination', currentpage, branch);
            });

        });

        $(document).on('click', '#restockTableBtn', async function () {
            // let branch = $("#sortbranches").val();
            let table = await modal_table_pagination('tablecontents/inv.itemrestock.pagination.php', 'restockTable', 'restockPagination', 1, branch);
            if (table) {
                $("#restockModal").modal('show');
            } else {
                console.log(table);
                alert("An error occured loading content. Please try again later.");
            }
            $('#restockPagination').on('click', '.page-link', async function (e) {
                e.preventDefault();
                let currentpage = $(this).data('page');
                await modal_table_pagination('tablecontents/inv.itemrestock.pagination.php', 'restockTable', 'restockPagination', currentpage, branch);
            });

        });

        $("#restockTable").on('click', '.editbtn', function () {
            $("#restockModal").modal('hide');
        });

        $("#dispatchedTable").on('click', '.editbtn, .returnbtn', function () {
            $("#dispatchedItemsModal").modal('hide');
        })


        $(document).on('click', '#dispatchedTableBtn', async function () {
            // let branch = $("#sortbranches").val();
            let table = await modal_table_pagination('tablecontents/inv.dispatcheditems.pagination.php', 'dispatchedTable', 'dispatchedTablePagination', 1, branch);
            if (table) {
                $("#dispatchedItemsModal").modal('show');
            } else {
                console.log(table);
                alert("An error occured loading content. Please try again later.");
            }
            $('#dispatchedTablePagination').on('click', '.page-link', async function (e) {
                e.preventDefault();
                let currentpage = $(this).data('page');
                await modal_table_pagination('tablecontents/inv.dispatcheditems.pagination.php', 'dispatchedTable', 'dispatchedTablePagination', currentpage, branch);
            });
        });

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

        $("#restockFunctionModal").on('click', '#restock_less', function (e) {
            e.preventDefault();
            let restock_value = parseInt($("#restock_value").val());

            let new_value = 0;
            if (restock_value <= 0) {
                $("#restock_val_alert").text("Value should not be less than zero.").fadeIn();
            } else if (restock_value > 0) {
                new_value = restock_value - 1;
                // console.log(`${new_value} ${restock_value}`);
                $("#restock_value").val(new_value);
            } else {
                $("#restock_val_alert").text("An unknown error has occured. Please refresh the page and try again.").fadeIn();
            }

            if (!Number.isInteger(restock_value)) {
                $("#restock_val_alert").text("Input should be a number!").fadeIn();
            }

            setTimeout(() => {
                $("#restock_val_alert").fadeOut();
            }, 2500);
        });

        $("#restockFunctionModal").on('click', '#restock_add', function (e) {
            e.preventDefault();
            let restock_value = parseInt($("#restock_value").val());
            let new_value = 0;

            if (restock_value >= 500) {
                $("#restock_val_alert").text("Maximum number is 500.").fadeIn();
            } else {
                new_value = restock_value + 1;
                $("#restock_value").val(new_value);
            }

            if (!Number.isInteger(restock_value)) {
                $("#restock_val_alert").text("Input should be a number!").fadeIn();
            }

            setTimeout(() => {
                $("#restock_val_alert").fadeOut();
            }, 2500);
        });

        $("#restockFunctionModal").on('input', '#restock_value', function () {
            let value = parseInt($(this).val());
            if (value >= 500) {
                $("#restock_val_alert").text("Maximum number is 500.").fadeIn();
                $(this).val('');
            } else if (value < 0) {
                $("#restock_val_alert").text("Value should not be less than zero.").fadeIn();
                $(this).val('');
            } else {
                $("#restock_val_alert").fadeOut();
            }
        })

        $(document).on('submit', '#restockForm', function (e) {
            e.preventDefault();
            let data = $(this).serialize();
            // console.log(data);

            $.ajax({
                method: 'post',
                url: dataurl,
                data: data + "&restock=true",
                dataType: 'json'
            })
                .done(function (d) {
                    // console.log(d);
                    show_toast(d.success);
                    $("#restockConfirm").modal('hide');

                })
                .fail(function (e) {
                    console.log(e);
                    show_toast(e.responseText);
                    $("#restock_err_alert").text(e.responseText).fadeIn().delay(2500).fadeOut();
                })
        });


    </script>
</body>

</html>