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
                <button type="button" id="inventorylogbtn"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded py-2 px-4 text-light"
                    title="Inventory Logs"><i class="bi bi-file-earmark-text"></i><span id="hideEnText"></button>
                <button type="button" id="approvemulti"
                    class="btn btn-sidebar bg-light bg-opacity-25 rounded py-2 px-4 text-light" data-bs-toggle="modal"
                    data-bs-toggle="tooltip" data-bs-target="#multiapproveModal" title="Approve multiple stocks"><i
                        class="bi bi-list-check"></i></button>
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
                                    <div class="table-responsive-sm  d-flex justify-content-center">
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
                                        <div id="inventorylogpaginationbtns"></div>
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

                                    <p
                                        class="text-center fw-bold fs-5 bg-secondary text-light bg-opacity-50 rounded py-1 w-50 mx-auto">
                                        Item Count Information</p>
                                    <div class="row mb-2 d-none user-select-none" id="chemState">
                                        <div class="col-3">
                                            <p class="fw-medium mb-2">Opened Item Level:</p>
                                            <p class="ps-2 mb-2" id="openedContainerLevel"></p>
                                        </div>
                                        <div class="col-3">
                                            <p class="fw-medium mb-2">Closed Item Count:</p>
                                            <p class="ps-2 mb-2" id="closedContainerCount"></p>
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
                    <div class="modal-lg modal fade text-dark modal-edit" data-bs-backdrop="static" id="addModal"
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
                                            <label for="chemLevel" class="form-label fw-light">Item Count</label>
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
                                                Item</button>
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
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
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

                                        <div class="col-lg-2 mb-2">
                                            <label for="return-openedLevel" class="form-label fw-medium">Opened
                                                Item Level:</label>
                                            <p id="return-openedLevel" class="fw-light ps-2"></p>
                                        </div>

                                        <div class="col-lg-2 mb-2">
                                            <label for="return-containerCount" class="form-label fw-medium">Item
                                                Count
                                                (Including Opened):</label>
                                            <p id="return-containerCount" class="fw-light ps-2"></p>
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <label for="return-cstatus" class="form-label fw-medium">Item Location
                                                Status:</label>
                                            <p id="return-cstatus" class="fw-light ps-2"></p>
                                        </div>
                                    </div>

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
                                            <select id="return_unit" class="form-select"></select>
                                        </div>

                                        <div class="col-lg-4 mb-2">
                                            <label for="return_transaction" class="form-label fw-medium">Select
                                                Dispatched Transaction:</label>
                                            <select name="return_transaction" id="return_transaction"
                                                class="form-select" autocomplete="one-time-code">
                                            </select>
                                            <p class="text-body-secondary mt-2">Note: You can only return this
                                                item's dispatched transaction.</p>
                                        </div>

                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 mb-2">
                                            <label for="container_count" class="form-label fw-medium">Number of
                                                closed items (not used)
                                                to return:</label>
                                            <input type="number" name="container_count" id="container_count"
                                                class="form-control w-50" autocomplete="one-time-code">
                                        </div>
                                        <div class="col-4 mb-2">
                                            <p class="fw-medium">Current Transaction Dispatch Information:</p>
                                            <p id="return_current_transaction_info" class="fw-light ms-2">Please select
                                                a transaction.</p>
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
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
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
                                <h1 class="modal-title fs-5" id="verifyChanges">Chemical Deletion</h1>
                                <h1 class="modal-title fs-5" id="verifyChanges">Item Deletion</h1>
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
                <div class="modal modal-lg fade text-dark modal-edit" data-bs-backdrop="static" id="multiapproveModal"
                    tabindex="0" aria-labelledby="confirmAdd" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title">
                                <h1 class="modal-title fs-5 text-light">Stock Entries</h1>
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
                                    <label for="confirmapprove-inputpwd" class="form-label fw-light">Approve all
                                        entries?
                                        Enter manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control"
                                            id="confirmapprove-inputpwd">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    Note: Approving entry requests will officially make the chemicals a part of the
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
                                    <label for="approve-inputpwd" class="form-label fw-light">Approve Entry No.<span
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
            // flatpickrdate(d);
            $("#addMoreChem").empty();
            $("#addForm")[0].reset();
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

        $(document).on('click', '#approvemulti', async function () {
            $('#multiapprove')[0].reset();
            const reqlist = await stock_requests();
            if (reqlist) {
                $('#multiapproveModal').modal('show');
            }
        });

        $(document).on('change', '#checkall', function () {
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

        async function loadpage(page, entryHidden = false, branch = null) {
            await loadtable(page, entryHidden, branch);
            await loadpagination(page, entryHidden, branch);
            overview_display(branch);
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

        $(document).on('change', '#sortbranches', async function () {
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



        $(document).on('click', '#hideentries', async function () {
            await hide_entries();
        });

        $('#pagination').on('click', '.page-link', async function (e) {
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

            let reqAlertStatus = $('#reqalert').hasClass('d-none');
            if (details.req == 1) {
                $('#reqalert').removeClass('d-none').html('This chemical is pending for approval by the Manager. You can only view the details of this chemical.').fadeIn(750);
            } else {
                if (!reqAlertStatus) {
                    $('#reqalert').addClass('d-none');
                }
                $('#reqalert').html('');
            }

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