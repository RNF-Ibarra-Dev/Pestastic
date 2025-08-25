<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician | Transactions</title>
    <?php include('header.links.php'); ?>

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

            <div class="bg-light bg-opacity-25 pt-2 rounded p-3 mx-3 mt-3 mb-2 ">
                <h1 class="display-6 text-light mb-0 fw-medium text-center">Manage Transactions</h1>
            </div>
            <div class="d-flex gap-2 my-2 mx-3 user-select-none text-center">
                <div class="bg-light bg-opacity-25 rounded-2 px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <div class="clearfix">
                        <i
                            class="float-start bi bi-alarm-fill bg-warning bg-opacity-75 pt-1 pb-2 px-4 rounded-pill shadow-sm "></i>
                        <p class="fs-5 fw-bold mx-auto w-50 mb-0">Pending</p>
                    </div>
                    <p class="fw-light mb-0 ">Pending transactions that needs to be approved by either Operations
                        Supervisor or Manager.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_pending"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded-2 px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <div class="clearfix">
                        <i
                            class="float-start bi bi-clipboard-check-fill bg-custom-success bg-opacity-25 pt-1 pb-2 px-4 rounded-pill shadow-sm "></i>
                        <p class="fs-5 fw-bold mx-auto w-50 mb-0">
                            Accepted
                        </p>
                    </div>
                    <p class=" mb-0 ">Accepted transactions that is at standby until dispatched at a
                        specific date.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_accepted"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded-2 px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <div class="clearfix">
                        <i
                            class="float-start bi bi-truck-flatbed  bg-warning bg-opacity-50 pt-1 pb-2 px-4 rounded-pill shadow-sm "></i>
                        <p class="fs-5 fw-bold mx-auto w-50 mb-0">
                            Dispatched
                        </p>
                    </div>
                    <p class=" mb-0 ">Transactions that are currently being carried out.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_dispatched"></p>
                </div>
            </div>
            <div class="d-flex gap-2 mb-2 mx-3 user-select-none text-center">
                <div class="bg-light bg-opacity-25 rounded-2 px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <div class="clearfix">
                        <i
                            class="float-start bi bi-calendar2-check-fill  bg-info bg-opacity-50 pt-1 pb-2 px-4 rounded-pill shadow-sm "></i>
                        <p class="fs-5 fw-bold mx-auto w-50 mb-0">
                            Completed
                        </p>
                    </div>
                    <p class=" mb-0 ">Reviewed and approved as finished.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_completed"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded-2 px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <div class="clearfix">
                        <i
                            class="float-start bi bi-clipboard-x-fill  bg-danger bg-opacity-50 pt-1 pb-2 px-4 rounded-pill shadow-sm "></i>
                        <p class="fs-5 fw-bold mx-auto w-50 mb-0">
                            Voided
                        </p>
                    </div>
                    <p class=" mb-0 ">Voided transactions cancelled due to a specific cause.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_voided"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded-2 px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <div class="clearfix">
                        <i
                            class="float-start bi bi-arrow-repeat  bg-primary bg-opacity-50 pt-1 pb-2 px-4 rounded-pill shadow-sm "></i>
                        <p class="fs-5 fw-bold mx-auto w-50 mb-0">
                            Finalizing
                        </p>
                    </div>
                    <p class=" mb-0 ">Transactions marked done by Technicians.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_finalizing"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded-2 px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <div class="clearfix">
                        <i
                            class="float-start bi bi-calendar2-x-fill  bg-secondary pt-1 pb-2 px-4 rounded-pill shadow-sm "></i>
                        <p class="fs-5 fw-bold mx-auto w-50 mb-0">
                            Cancelled
                        </p>
                    </div>
                    <p class=" mb-0 ">Cancelled transaction schedules.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_cancelled"></p>
                </div>

            </div>

            <div class="hstack gap-2 mt-2 mx-3">
                <select
                    class="form-select select-transparent bg-light bg-opacity-25 rounded py-2 border-0 h-100 text-light w-25 "
                    id="sortstatus" aria-label="Default select example">
                    <option value='' selected>Show All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Dispatched">Dispatched</option>
                    <option value="Finalizing">Finalizing</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="Voided">Voided</option>
                </select>
                <input class="form-control form-custom me-auto py-2 align-middle px-3 rounded-pill text-light "
                    placeholder="Search transactions . . ." id="searchbar" name="searchTrans"
                    autocomplete="one-time-code">
                <button type="button" id="recentlyCompleted" data-bs-target="#finalizetransactionmodal"
                    data-bs-toggle="modal"
                    class="btn w-50 rounded btn-sidebar bg-light bg-opacity-25 border-0 text-light py-2 px-1 "><i
                        class="bi bi-calendar2-check me-2"></i>Finalizing Transactions</button>
                <button type="button" id="requestvoidbtn" data-bs-target="#requestedvoidtransactions"
                    data-bs-toggle="modal"
                    class="btn w-50 rounded btn-sidebar bg-light bg-opacity-25 border-0 text-light py-2 px-1 "><i
                        class="bi bi-file-earmark-x me-2"></i>Requested Void Transactions</button>
                <div class="vr"></div>
                <button type="button" id="addbtn" title="Add Transaction"
                    class="btn btn-sidebar rounded border-0 bg-light bg-opacity-25 text-light py-2 px-3 "
                    disabled-data-bs-toggle="modal" disabled-data-bs-target="#addModal"><i
                        class="bi bi-file-earmark-plus"></i></button>
            </div>


            <!-- table -->
            <div class="table-responsive-sm d-flex justify-content-center">
                <table class="table align-middle table-hover m-3 mt-2 os-table w-100 text-light">
                    <caption class="text-light text-muted">List of all transactions. For easy transaction progress,
                        click the status badges.</caption>
                    <thead class="text-center">
                        <tr>
                            <th scope="row">Transaction ID</th>
                            <th>Customer Name</th>
                            <th>Treatment Date</th>
                            <th>Treatment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <!-- table -->
                    <tbody id="table"></tbody>
                </table>
            </div>


            <!-- modals -->
            <form id="logtransaction">
                <div class="row g-2 text-dark">
                    <div class="modal modal-lg fade text-dark modal-edit" id="logtransactions" tabindex="-1"
                        aria-labelledby="create" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Log New Transaction</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <p class="fs-6 fw-light text-muted">Provide the details of the transaction below.
                                    </p>
                                    <div class="row mb-2">
                                        <div class="col-lg-6 mb-2">
                                            <label for="add-customerName" class="form-label fw-light">Customer Name
                                            </label>
                                            <input type="text" name="add-customerName" id="add-customerName"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="dropdown-center col-lg-6 mb-2">
                                            <label for="addTechnician" class="form-label fw-light">Choose
                                                Technicians
                                            </label>
                                            <select id="addTechnician" disabled name="addTechnician[]" not-size="2"
                                                class="form-select" aria-label="Default select example">
                                            </select>
                                        </div>
                                    </div>

                                    <div id="addTechContainer" class="p-0 m-0 mb-2"></div>


                                    <button type="button" id="addMoreTech" class="btn btn-grad mt-auto py-1 px-3"><i
                                            class="bi bi-plus-circle text-light me-2"></i>Add More Technician</button>


                                    <hr class="my-2">

                                    <div class="row mb-2">

                                        <div class="col-lg-6 mb-2">
                                            <label for="add-treatmentDate" class="form-label fw-light">Treatment
                                                Date</label>
                                            <input type="date" name="add-treatmentDate" id="add-treatmentDate"
                                                min="2025-01-01" class="form-control form-add">
                                        </div>

                                        <div class="col-lg-6 mb-2">
                                            <label for="add-treatment" class="form-label fw-light">Treatment</label>
                                            <select name="add-treatment" id="add-treatment" class="form-select">
                                                <option value="#" selected>Select Treatment</option>
                                                <option value="Follow-up Crawling Insects Control">Follow-up Crawling
                                                    Insects Control</option>
                                                <option value="Crawling Insects Control">Crawling Insects Control
                                                </option>
                                                <option value="Termite Control">Termite Control</option>
                                                <option value="Wooden Structures Treatment">Wooden Structures Treatment
                                                </option>
                                                <option value="Termite Powder Application">Termite Powder Application
                                                </option>
                                                <option value="Soil Injection">Soil Injection</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row mb-2">

                                        <div class="col-lg-12 mb-2">
                                            <label for="add-pestproblem" class="form-label fw-light">Pest
                                                Problem</label>
                                            <div id="add-pestproblem" name="add-pestproblem"
                                                class="d-flex gap-2 align-items-center justify-content-evenly flex-wrap">
                                                <!-- pest problems ajax append here -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-2" did="row">
                                        <div class="col-lg-6 mb-2">
                                            <label for="add-chem" class="form-label fw-light">Chemical
                                                Used</label>
                                            <select id="add-chem" name="addChem[]" class="form-select">
                                                <!-- chem ajax -->
                                            </select>

                                        </div>
                                        <div class="col-lg-4 mb-2 ps-0">
                                            <label for="add-amountUsed" class="form-label fw-light">Amount
                                                Used</label>
                                            <div class="d-flex flex-row">
                                                <input type="number" name="amountUsed[]" maxlength="4"
                                                    id="add-amountUsed" class="form-control form-add me-3"
                                                    autocomplete="one-time-code">
                                                <span id="passwordHelpInline" class="form-text align-self-center">
                                                    /ml
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-0 m-0 mb-2" id="add-chemContainer">
                                        <!-- template add chemical -->
                                    </div>
                                    <button type="button" id="addMoreChem" class="btn btn-grad mt-auto py-1 px-3"><i
                                            class="bi bi-plus-circle text-light me-2"></i>Add More Chemicals</button>


                                    <p class="fw-light text-muted mt-2">Transaction will be marked as pending. Please be
                                        patient.</p>

                                    <!-- <div class="row mb-2">
                                        
                                    </div> -->
                                    <p class="text-center alert alert-info w-75 mx-auto visually-hidden"
                                        id="emptyInput"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-grad" disabled-id="submitAdd"
                                        data-bs-toggle="modal" data-bs-target="#confirmAdd">Proceed &
                                        Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- add confirmation -->
                <div class="modal fade text-dark modal-edit" id="confirmAdd" tabindex="0" aria-labelledby="confirmAdd"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyAdd">Verification</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="addPwd" class="form-label fw-light">Submit transaction? Enter your
                                        password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="techPwd" class="form-control" id="addPwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-3 w-75 mx-auto my-0 visually-hidden'
                                    id="add-alert"></p>
                                <!-- <div id="passwordHelpBlock" class="form-text">
                                Note: deletion of chemicals are irreversible.
                            </div> -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#logtransactions"
                                    data-bs-toggle="modal">Go back</button>
                                <button type="submit" class="btn btn-grad" id="submitAdd">Submit Transaction
                                    Request</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- finalizing shortcut -->
            <form id="finalizeForm">
                <input type="hidden" name="finalizeid" id="finalizeid">
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="finalizeModal" tabindex="-1"
                    aria-labelledby="create" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Finalize Transaction</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                        class="bi text-light bi-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="p-0 m-0 mb-2" id="finalize-chemBrandUsed"></div>
                                <button type="button" id="finalize-addMoreChem"
                                    class="btn btn-grad mt-auto py-2 px-3 d-flex align-items-center">
                                    <p class="fw-light m-0 me-2">Add Chemical</p><i
                                        class="bi bi-plus-circle text-light"></i>
                                </button>

                                <label for="finalizenotes" class="fw-light my-2">Note:</label>
                                <textarea name="note" class="form-control w-50" id="finalizeNotes" cols="1"
                                    placeholder="e.g. Used 200ml Termicide for kitchen and 100ml for bathroom."></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-bs-dismiss="modal" class="btn btn-grad">Close</button>
                                <button type="button" data-bs-target="#finalconfirm" data-bs-toggle="modal"
                                    class="btn btn-grad">Proceed</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="finalconfirm" tabindex="0">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Finalize Transaction Confirmation</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="finalizing-inputpwd" class="form-label fw-light">Confirm Finalizing
                                        this transaction?
                                        Enter Operation Supervisor
                                        <?= $_SESSION['username'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="baPwd" class="form-control w-75"
                                            id="finalizing-inputpwd">
                                    </div>
                                </div>
                                <p class="text-body-secondary fw-light">Note. This will only be set to finalizing status
                                    and is
                                    up for review yet.</p>
                                <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                    id="finalizingAlert">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#finalizeModal">Go back</button>
                                <button type="submit" class="btn btn-grad">Finalize</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="finalizetransactionform">
                <div class="modal modal-lg fade text-dark modal-edit" data-bs-backdrop="static"
                    id="finalizetransactionmodal" tabindex="0" aria-labelledby="confirmAdd" aria-hidden="true">
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
                                                <th class="text-dark" scope="col">Transaction ID</th>
                                                <th class="text-dark">Customer Name</th>
                                                <th class="text-dark">Treatment Date</th>
                                                <th class="text-dark">Updated By</th>
                                                <th class="text-dark">Updated At</th>
                                            </tr>
                                        </thead>

                                        <tbody id="finalizetranstable" class="table-group-divider">
                                            <tr>
                                                <td scope='row' colspan='6' class='text-center'>
                                                    <div class='spinner-grow text-secondary' role='status'><span
                                                            class='visually-hidden'>Loading...</span></div>
                                                </td>
                                            </tr>
                                        </tbody>

                                    </table>
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

                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="finalizeconfirm"
                    tabindex="0">
                    <input type="hidden" name="trans[]" id="finalizesingletransinput" disabled>
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Complete Transaction</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="confirmapprove-inputpwd" class="form-label fw-light">Approve Selected
                                        Transactions?
                                        Enter manager
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="baPwd" class="form-control"
                                            id="confirmapprove-inputpwd">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    Note: No one will be able to edit a completed transaction. This
                                    action
                                    cannot be undone.
                                </div>
                                <p class="text-center alert alert-info mt-2 mb-0 w-75 mx-auto" style="display: none;"
                                    id="finalizealert">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#finalizetransactionmodal" id='finalizebackbtn'>Go Back</button>
                                <button type="submit" class="btn btn-grad">Finalize
                                    Transaction</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="modal modal-lg fade text-dark modal-edit" data-bs-backdrop="static"
                id="requestedvoidtransactions" tabindex="0" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title">
                            <h1 class="modal-title fs-5 text-light">Requested Void Transactions</h1>
                            <button type="button" class="btn ms-auto p-0 text-light" data-bs-dismiss="modal"
                                aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>

                        <div class="modal-body text-dark p-3">
                            <div class="table-responsive-sm  d-flex justify-content-center">
                                <table class="table align-middle table-hover w-100" id="approvechemtable">
                                    <caption class="fw-light text-muted">Above are lists of requested void transactions.
                                        Select the transaction number ID to view transaction.
                                    </caption>
                                    <thead>
                                        <tr class="text-center align-middle">
                                            <th class="text-dark" scope="col">Transaction ID</th>
                                            <th class="text-dark">Customer Name</th>
                                            <th class="text-dark">Requested By</th>
                                            <th class="text-dark">Requested At</th>
                                        </tr>
                                    </thead>

                                    <tbody id="voidrequesttable" class="table-group-divider">
                                        <tr>
                                            <td scope='row' colspan='6' class='text-center'>
                                                <div class='spinner-grow text-secondary' role='status'><span
                                                        class='visually-hidden'>Loading...</span></div>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- details modal -->
            <div class="row g-2 text-dark m-0">
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="details-modal" tabindex="-1">
                    <div class="modal-xl modal-dialog-scrollable modal-dialog">
                        <div class="modal-content">

                            <!-- modal header -->
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Transaction Information</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                        class="bi text-light bi-x"></i></button>
                            </div>

                            <!-- modal body -->
                            <div class="modal-body pb-1">

                                <!-- <p class="fw-light text-muted">Transaction details presented below.</p> -->
                                <p
                                    class="fw-medium mb-4 fs-4 text-uppercase text-center bg-dark bg-gradient bg-opacity-25 text-white rounded p-2">
                                    Customer Information</p>

                                <!-- row 1 -->
                                <div class="row mb-2">

                                    <div class="col-lg-6 mb-2">
                                        <label for="view-transId" class="form-label fw-light">Transaction ID:
                                        </label>
                                        <input type="text" name="edit-transId" id="view-transId"
                                            class="form-control-plaintext form-add" readonly
                                            autocomplete="one-time-code">
                                    </div>

                                    <div class="col-lg-6 mb-2">

                                        <label for="view-customerName" class="form-label fw-light">Customer Name:
                                        </label>
                                        <!-- remove readonly in edit mode -->
                                        <input type="text" name="edit-customerName" id="view-customerName"
                                            class="form-control-plaintext form-add" readonly
                                            autocomplete="one-time-code">
                                    </div>

                                </div>

                                <div class="row mb-2">

                                    <div class="col-lg-6 mb-2" id='view-addCont'>
                                        <label for='view-address' class="form-label fw-light">Address:</label>
                                        <textarea type="text" id="view-address" class="form-control-plaintext form-add"
                                            readonly style="resize: none !important;"></textarea>
                                    </div>

                                    <div class="col-lg-6 mb-2 d-none" id='edit-addCont'>
                                        <label for='edit-address' class="form-label fw-light">Address:</label>
                                        <textarea type="text" name="edit-address" id="edit-address"
                                            class="form-control form-add"></textarea>
                                    </div>

                                </div>

                                <hr class="my-2">

                                <p
                                    class="fw-medium mt-3 mb-4 fs-4 text-uppercase text-center bg-dark bg-gradient bg-opacity-25 text-white rounded p-2">
                                    Treatment Information
                                </p>

                                <!-- row 3  -->
                                <div class="row mb-2">
                                    <!-- treatment -->
                                    <div class="col-lg-4 mb-2">
                                        <!-- left side -- treatments in view -->
                                        <label for="view-treatment" class="form-label fw-light"
                                            id="view-treatment-label">Treatment:</label>
                                        <ul class="list-group list-group-flush" id="view-treatment"></ul>

                                        <!-- left side -- treatments in edit -->
                                        <label for="edit-treatment" class="form-label fw-light visually-hidden"
                                            id="edit-treatment-label">Treatment</label>
                                        <select name="edit-treatment" id="edit-treatment"
                                            class="form-select visually-hidden">
                                            <option value="" selected>Select Treatment</option>
                                            <div id="edit-treatment-options">
                                                <!-- ajax -->
                                            </div>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="edit-treatmentType" class="form-label fw-light">Treatment
                                            Type:</label>
                                        <p id="view-treatmentType" class="ps-3"></p>
                                        <select name="edit-treatmentType" id="edit-treatmentType"
                                            class="form-select d-none">
                                            <option value="" selected>Select Treatment Type</option>
                                            <option value="General Treatment">General Treatment
                                            </option>
                                            <option value="Follow-up Treatment">Follow-up Treatment
                                            </option>
                                            <option value="Quarterly Treatment">Quarterly Treatment</option>
                                            <option value="Monthly Treatment">Monthly Treatment
                                            </option>
                                        </select>
                                    </div>

                                    <!-- right side -->
                                    <div class="col-lg-3 mb-2">
                                        <label for="view-treatmentDate" class="form-label fw-light">Treatment
                                            Date:</label>
                                        <input type="text" name="edit-treatmentDate" id="view-treatmentDate"
                                            class="form-control form-add d-none">
                                        <p id="t_viewdate" class="ps-2 mt-2 mb-0"></p>
                                    </div>

                                    <div class="col-lg-2 mb-2">
                                        <label for="view-treatmentTime" class="form-label fw-light">Treatment
                                            Time:</label>
                                        <input type="text" name="edit-treatmentTime" id="view-treatmentTime"
                                            class="form-control form-add d-none">
                                        <p id="t_viewtime" class="ps-2 mt-2 mb-0"></p>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-lg-6 mb-2">
                                        <!-- left side -- view of customer pest problems -->
                                        <label for="view-probCheckbox" id="view-probCheckbox-label"
                                            class="form-label fw-light">Customer's Pest
                                            Problems:</label>
                                        <ul class="list-group list-group-flush" id="view-probCheckbox"></ul>
                                    </div>

                                    <div class="col-lg-12 mb-2 visually-hidden" id="row-pestProblems">
                                        <!-- whole row -- edit of customer pest problems-->
                                        <label for="edit-probCheckbox" class="form-label fw-light">Customer's Pest
                                            Problems:</label>
                                        <div id="edit-probCheckbox" name="edit-probCheckbox"
                                            class="d-flex gap-2 align-items-center justify-content-evenly flex-wrap">
                                            <!-- pest problems ajax append here -->
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">

                                    <div class="col-lg-6">
                                        <label for="edit-package" class="form-label fw-light">Package:</label>
                                        <p id="view-package" class="ps-3"></p>
                                        <select name="edit-package" class="form-select d-none" id="edit-package-select">
                                            <option value="none">None</option>
                                            <div id="edit-package"></div>
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="edit-session" class="form-label text-nowrap fw-light">Session
                                            number:</label>
                                        <input type="text" class="form-control-plaintext ps-3" id="edit-session"
                                            name="edit-session" readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="view-start" class="form-label fw-light">Warranty
                                            Start:</label>
                                        <input class="form-control ps-3 d-none" placeholder="--/--/--" id="view-start">
                                        <p id="view_wstart" class="ps-3 mt-2 mb-0"></p>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="view-expiry" class="form-label text-nowrap fw-light">Warranty
                                            Expiry:</label>
                                        <input class="form-control-plaintext d-none ps-3" placeholder="--/--/--"
                                            id="view-expiry" readonly>
                                        <p id="view_wexpiry" class="ps-3 mt-2 mb-0"></p>
                                        <p class="text-secondary fw-light d-none p-2" id="w_expiry_note">Note:
                                            Expiry is autogenerated.</p>
                                    </div>
                                </div>

                                <hr class="my-2">

                                <p
                                    class="fw-medium mt-3 mb-4 fs-4 text-uppercase text-center bg-dark bg-gradient bg-opacity-25 text-white rounded p-2">
                                    Additional Information
                                </p>

                                <div class="visually-hidden" id="edit-chemBrandUsed"></div>

                                <!-- <div class="visually-hidden" id="edit-chemBrandUsed-addContainer"></div> -->

                                <div class="row mb-2">
                                    <div class="col-lg-6 mb-2" id="view-chemUsedContainer">
                                        <!-- left side -- view -->
                                        <label for="view-chemUsed" id="view-chemUsed-label"
                                            class="form-label fw-light">Chemicals
                                            Used:</label>
                                        <ul class="list-group list-group-flush" id="view-chemUsed"></ul>
                                    </div>

                                    <!-- right side -- view -->
                                    <div class="col-lg-6 mb-2" id="view-status-col">
                                        <label for="view-status" class="form-label mb-0 fw-light p-0">Transaction
                                            Status:</label>
                                        <p class="fw-light ps-3" id="view-status">
                                        </p>
                                    </div>

                                    <!-- placed under -- add button for rows toggle in edit mode as well. -->
                                    <div class="col-lg-4  my-2 d-flex visually-hidden" id='btn-amountUsed'>
                                        <button type="button" id="edit-addMoreChem"
                                            class="btn btn-grad mt-auto py-2 px-3 d-flex align-items-center">
                                            <p class="fw-light m-0 me-2">Add Chemical</p><i
                                                class="bi bi-plus-circle text-light"></i>
                                        </button>
                                    </div>

                                </div>
                                <p class="alert alert-warning py-1 mt-1 w-50 mx-auto text-center amt-used-alert"
                                    style="display: none;"></p>

                                <div class="row mb-2">
                                    <div class="col-lg-6">
                                        <!-- edit technician choices/select, toggle visually-hidden when edit is on -->
                                        <label for='edit-technicianName' id="edit-technicianName-label"
                                            class="form-label fw-light visually-hidden">Edit Technician/s:</label>

                                        <!-- container -- ajax -- append -->
                                        <div class="d-flex flex-column m-0 p-0 visually-hidden"
                                            id="edit-technicianName">
                                        </div>

                                        <!-- add button -->
                                        <div class="w-50 d-flex visually-hidden" id="techaddbtn">
                                            <button type="button" id="edit-addTech"
                                                class="btn btn-grad mt-auto py-2 px-3 d-flex align-items-center">
                                                <p class="fw-light m-0 me-3">Add Technician</p><i
                                                    class="bi bi-plus-circle text-light"></i>
                                            </button>
                                        </div>

                                        <div class="dropdown-center col-lg-6 mb-2">
                                            <!-- list technician when in view mode. Handled in ajax -->
                                            <label for="view-technicians" id="view-technicians-label"
                                                class="form-label fw-light">Technician/s:
                                            </label>
                                            <ul class="list-group list-group-flush" id="view-technicians"></ul>
                                        </div>

                                    </div>
                                    <div class="col-lg-6 mb-2" id="view-noteContainer">
                                        <label for="view-note" class="form-label fw-light">Additional
                                            Notes:</label>
                                        <p class="ps-3 fw-light" id="view-note"></p>
                                    </div>
                                    <div class="col-lg-6 mb-2 d-none" id="edit-noteContainer">
                                        <label for="edit-note" class="form-label fw-light">Additional
                                            Notes:</label>
                                        <textarea class="fw-light form-control" name="edit-note"
                                            id="edit-note"></textarea>
                                    </div>

                                </div>

                                <div class="p-0 m-0 mb-2" id="edit-chemContainer">
                                    <!-- template add chemical -->
                                </div>


                                <div class="row mb-2 d-none" id="edit-status-col">
                                    <!-- edit -->
                                    <div class="col-lg-6 d-flex flex-column">
                                        <label for="edit-status" class="form-label" id='label-edit-status'>Transaction
                                            Status:</label>
                                        <select name="edit-status" id="edit-status" class="form-select ">
                                            <option value="" selected>Select Status</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Accepted">Accepted</option>
                                            <option value="Dispatched">Dispatched</option>
                                            <option value="Finalizing">Finalizing</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                        <p class="alert alert-warning py-1 mt-2" style="display: none !important;">
                                        </p>
                                    </div>

                                </div>

                                <p id="transvoidalert" class="alert alert-danger py-2 text-center w-50 mx-auto mb-0">
                                </p>

                                <!-- toggle visually hidden when edit -->
                                <p class="mb-0 mt-4" id='metadata'><small id=view-time class="text-muted"></small>
                                </p>

                                <div class="row mt-3 mb-2 mx-auto g-2 d-flex justify-content-evenly visually-hidden"
                                    id="editbtns">
                                    <button type="button" class="btn btn-grad" style="width: 45% !important;"
                                        id="confirmUpdate" data-bs-target="#confirmation" data-bs-toggle="modal">Update
                                        Transaction</button>
                                    <button type="button" class="btn btn-grad" style="width: 45% !important;"
                                        id="confirmDelete" data-bs-toggle="modal" data-bs-target="#confirmation">Delete
                                        Transaction</button>
                                </div>
                            </div>

                            <!-- footer -->
                            <div class="modal-footer">
                                <div class="m-0 p-0 me-auto d-flex gap-2">
                                    <button type="button" class="btn mt-auto btn-grad" data-bs-toggle="modal"
                                        data-bs-target="#voidrequestmodal" id="requestvoidbtn">Request Void
                                        Transaction</button>
                                    <button type="button" class="btn btn-grad" id="modalcancelbtn"
                                        data-bs-toggle="modal" data-bs-target="#cancelscheduledmodal">Cancel
                                        Scheduled
                                        Transaction</button>
                                </div>
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close
                                    Details</button>
                                <button type="button" class="btn btn-grad" id="editbtn">Edit/Delete
                                    Transaction</button>
                            </div>
                        </div>
                    </div>
                </div>
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
        const dataurl = 'contents/trans.data.php';
        const tablecontent = 'contents/trans.pagination.php';

        const tech = <?= $_SESSION['techId'] ?>;
        console.log(tech);

        async function load_table(page = 1, status) {
            try {
                const table = await $.ajax({
                    type: 'GET',
                    url: tablecontent,
                    data: {
                        table: 'true',
                        currentpage: page,
                        tech: tech,
                        status: status
                    },
                    dataType: 'html'
                });

                if (table) {
                    $('#table').empty();
                    $('#table').append(table);
                }
            } catch (error) {
                alert('refresh');
            }
        }

        async function load_pagination_buttons(page = 1, status) {
            try {
                const pagination = await $.ajax({
                    type: 'GET',
                    url: tablecontent,
                    data: {
                        paginate: 'true',
                        active: page,
                        status: status
                    },
                    dataType: 'html'
                });

                if (pagination) {
                    $('#pagination').empty();
                    $('#pagination').append(pagination);
                    window.history.pushState(null, "", "?page=" + page);
                }
            } catch (error) {
                console.log(error);
            }
        }

        $('#pagination').on('click', '.page-link', async function (e) {
            e.preventDefault();
            let status = $("#sortstatus option:selected").val();
            let currentpage = $(this).data('page');
            await loadpage(currentpage, status);
        });

        async function loadpage(defpage = 1, status = '') {
            await load_table(defpage, status);
            await load_pagination_buttons(defpage, status);

        }

        // console.log(status);

        $("#sortstatus").on('change', async function () {
            let status = $("#sortstatus option:selected").val();
            $("#searchbar").val('');
            if (status != '') {
                await loadpage(1, status);
            } else {
                await loadpage();
            }
        })


        $(document).ready(async function () {
            await loadpage();
        });

        // modal related data fetch

        $(document).on('click', '#logtransbtn', async function () {
            try {
                const load = await Promise.all([
                    await fetch_something('addTechnician', 'tech'),
                    await get_pest_problems('add-pestproblem'),
                    await fetch_something('add-chem', 'chem', null)
                ]);
                if (!load) {
                    console.log('error loading . . .');
                }
                $('#logtransactions').modal('show');
            } catch (error) {
                alert(error);
            }

        });

        async function fetch_something(container, data, activeTech = tech) {
            try {
                $(`#${container}`).empty();
                const smt = await $.ajax({
                    type: 'GET',
                    url: dataurl,
                    data: {
                        getsmt: data,
                        active: activeTech
                    }
                });

                if (smt) {
                    $(`#${container}`).empty();
                    $(`#${container}`).append(smt);
                    console.log('success');
                    // console.log(activeTech);
                }
            } catch (error) {
                alert('err: ' + error);
            }
        }

        $(document).on('click', '#addMoreTech', async function () {
            await add_row('addTechContainer', 'tech');
        })
        $(document).on('click', '#addMoreChem', async function () {
            await add_row('add-chemContainer', 'chem', null);
        })

        async function add_row(container, data, disabled = tech) {
            try {
                const row = await $.ajax({
                    url: dataurl,
                    type: 'GET',
                    data: {
                        row: data,
                        disabled: disabled
                    },
                    dataType: 'html'
                });

                if (row) {
                    $(`#${container}`).append(row);
                } else {
                    alert('no edit returned. Contact administration.');
                }
            } catch (error) {
                alert('Error at add row function, container: ' + container + '\nerror: ' + error);
            }
        }

        async function get_pest_problems(container, active = null, disabled = null) {
            try {
                const pestproblem = await $.ajax({
                    type: 'GET',
                    dataType: 'html',
                    url: dataurl,
                    data: {
                        getProb: 'true',
                        active: active,
                        disabled: disabled
                    }
                });

                if (pestproblem) {
                    $(`#${container}`).empty();
                    $(`#${container}`).append(pestproblem);
                }
            } catch (error) {
                alert('get pest problem function error: ' + error);
            }
        }

        $(document).on('click', '#deleterow', async function () {
            let row = $(this).closest('div.row');
            row.remove();
            // $(this).parent().remove();
            console.log('tech row removed');
        })

        $(document).on('click', '#deleteChemRow', async function () {
            let row = $(this).closest('div.row');
            row.remove();
            console.log('chem row removed');
        })

        $('#logtransaction').on('shown.bs.modal', function () {
            $('#add-technicianName').prop('disabled', true);
        })

        // submit log
        const submit = "contents/trans.submit.php";
        $('#logtransaction').on('submit', async function (e) {
            // $('#add-technicianName').prop('disabled', false);
            e.preventDefault();
            const data = $(this).serializeArray();
            // console.log(JSON.stringify(data));
            console.log(data);
            try {
                const logtrans = await $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: submit,
                    data: $(this).serialize() + "&submitlog=true" + `&addTechnician[]=${tech}` + "&status=Pending"
                })

                if (logtrans) {
                    console.log(logtrans);
                    console.log(logtrans.success);
                    await loadpage();
                    $('#confirmAdd').modal('hide');

                }
            } catch (error) {
                // let err = jQuery.parseJSON(error.responseText);
                alert(error.responseText);
                console.log(error);
                alert(error.responseText.data);
            }
        })

        // details

        // $(document).on('click', '#tableDetails', async function (e) {
        //     e.preventDefault();
        //     let transId = $(this).data('trans-id');
        //     await view_details(transId);
        // })

        async function view_details(transId) {
            // console.log(transId);
            await append('transId', transId);
            try {
                const deets = await $.ajax({
                    type: 'GET',
                    url: dataurl,
                    dataType: 'json',
                    data: {
                        transId: transId,
                        fetchdetails: 'true'
                    }
                });

                if (deets.success) {
                    const data = deets.success;
                    const appenddata = await Promise.all([
                        await append('customer', data.customer_name),
                        await append('date', data.treatment_date),
                        await append('treatment', data.treatment),
                        $('#time').empty(),
                        $('#time').html('Created at: ' + data.created_at + '<br>Updated at: ' + data.updated_at),
                        await fetch_lists('technicians', data.id),
                        await fetch_lists('pestproblems', data.id),
                        await fetch_lists('chemical', data.id)
                    ])

                    if (appenddata) {
                        $('#details').modal('show');
                    } else {
                        alert('An error occured, please refresh the page and contact administration if this persists.')
                    }

                }
            } catch (error) {
                console.log(error);
            }
        }

        async function append(container, data) {
            $(`#${container}`).empty();
            $(`#${container}`).append(data);
        }

        async function fetch_lists(container, transId) {
            try {
                const lists = await $.ajax({
                    method: 'GET',
                    dataType: 'html',
                    url: dataurl,
                    data: {
                        fetch: container,
                        transId: transId
                    }
                });

                if (lists) {
                    await append(container, lists);
                }
            } catch (error) {
                return error;
            }
        }


        // search
        $(function () {
            let delay = null;

            $('#searchbar').keyup(function () {
                clearTimeout(delay);
                $('#table').empty();
                $('#loader').removeClass('visually-hidden');

                delay = setTimeout(async function () {
                    var search = $('#searchbar').val();
                    var sort = $('#sortstatus').val();
                    try {
                        const searchtransaction = await $.ajax({
                            url: tablecontent,
                            type: 'GET',
                            dataType: 'html',
                            data: {
                                search: search,
                                status: sort
                            }
                        });
                        if (searchtransaction) {
                            if (!search == '') {
                                $('#table').empty();
                                $('#loader').addClass('visually-hidden');
                                $('#table').append(searchtransaction);
                                $('#pagination').empty();
                            } else {
                                $('#loader').addClass('visually-hidden');
                                await loadpage(1, sort);
                            }
                        }
                    } catch (error) {
                        console.log(error);
                    }
                }, 250);
            });

        });

        $(document).ready(function () {
            $('#table').on('mouseenter', '.dispatched-btn', function () {
                $(this).html('Finalize Transaction');
            });
            $('#table').on('mouseleave', '.dispatched-btn', function () {
                $(this).html('Dispatched');
            });
            $('#table').on('mouseenter', '.cancel-btn', function () {
                $(this).html('Reschedule');
            });
            $('#table').on('mouseleave', '.cancel-btn', function () {
                $(this).html('Cancelled');
            });
        });

        async function get_chemical_brand(method, transId = null, status = null) {
            try {
                const brand = await $.ajax({
                    type: 'GET',
                    url: dataurl,
                    data: {
                        getChem: method,
                        transId: transId,
                        status: status
                    }
                });

                if (brand) {
                    $(`#${method}-chemBrandUsed`).empty();
                    $(`#${method}-chemBrandUsed`).append(brand);
                }
            } catch (error) {
                alert('get chem: ' + error);
            }
        }

        $("#table").on('click', '.dispatched-btn', async function () {
            let id = $(this).data('dispatched-id');
            console.log(id);
            $("#finalizeid").val(id);
            await get_chemical_brand('finalize', id, "Dispatched");
            $.get(dataurl, {
                notes: true,
                id: id
            }, function (d) {
                $("#finalizeNotes").val(d.notes);
            }, 'json')
                .fail(function (e) {
                    console.log(e);
                })
            $("#finalizeModal").modal('show');
        });

        $("#finalizeForm").on('click', "#finalize-addMoreChem", function () {
            $.get(dataurl, {
                addrow: 'true',
                status: 'Finalizing'
            }, function (data) {
                $("#finalize-chemBrandUsed").append(data);
                // console.log(data);
            }, 'html');

        });
        $("#finalizeForm").on('click', '#finalize-chemBrandUsed button', async function () {
            let row = $(this).closest('div.row');
            let length = $('#finalize-chemBrandUsed').children('.row').length;
            if (length === 1) {
                alert('One or more chemicals are required in order to proceed.');
                // console.log($('#finalize-chemBrandUsed'));
            } else {
                row.remove();
            }
        });

        $(document).on("shown.bs.modal", "#finalizetransactionmodal", async function () {
            let status = $("#sortstatus").val();
            await $.get(dataurl, "&finalizetrans=true")
                .done(function (d) {
                    if ($("#finalizetranstable").length <= 0) {
                        $("#finalizetranstable").append(d);
                    } else {
                        $("#finalizetranstable").empty();
                        $("#finalizetranstable").append(d);
                    }
                    loadpage(1, status);
                })
                .fail(function (e) {
                    console.log(e);
                })
        });

        $(document).on('click', '#requestvoidbtn', function () {
            // $('#requestedvoidtransactions').modal('show');
            $.get(dataurl, {
                voidrequest: 'true'
            }, function (data) {
                $('#voidrequesttable').empty();
                $('#voidrequesttable').append(data);
            })
                .fail(function (e) {
                    console.log(e);
                })
        });

        async function empty_form() {
            // $('#viewEditForm')[0].reset();
            $('#add-technicianName').empty();
            $('#addTechContainer').empty();
            $('#add-probCheckbox').empty();
            $('#add-chemBrandUsed').empty();
            $('#edit-technicianName').empty();
            $('#view-technicians').empty();
            $('#view-treatment').empty();
            $('#view-probCheckbox').empty();
            $('#edit-probCheckbox').empty();
            $('#edit-chemBrandUsed').empty();
            $('#view-chemUsed').empty();
            $('#view-status').empty();
            $('#edit-chemContainer').empty();
            console.log('modal cleared');
            return true;
        }

        $(document).on('click', '.finalize-peek-trans-btn', function () {
            $('#finalizetransactionmodal').modal('hide');
        });

        $(document).on('click', '#tableDetails, .finalize-peek-trans-btn, .check-void-req-btn', async function () {
            const clearform = await empty_form();
            if (clearform) {
                // $('#viewEditForm')[0].reset();
                $(`#edit-chemBrandUsed`).empty();
                let transId = $(this).data('trans-id');
                await view_transaction(transId);
            } else {
                alert('Modal Error. Refresh Page.');
            }
        });

        function treatment_name(id) {
            $.get(dataurl, `treatmentname=true&id=${id}`)
                .done(function (d) {
                    return d;
                })
                .fail(function (error, status, errmsg) {
                    console.log(error);
                    console.log(status + errmsg);
                });
        }

        async function view_technician(transId) {
            try {
                const list = await $.ajax({
                    type: 'GET',
                    dataType: 'html',
                    url: dataurl,
                    data: {
                        getTechList: 'true',
                        transId: transId
                    },
                    cache: true
                });

                if (list) {
                    $('#view-technicians').empty();
                    $('#view-technicians').append(list);
                }
            } catch (error) {
                alert(error);
            }
        }

        async function view(transId, rowName) {
            try {
                const list = await $.ajax({
                    type: 'GET',
                    dataType: 'html',
                    url: dataurl,
                    data: {
                        view: rowName,
                        transId: transId
                    }
                });
                if (list) {
                    $(`#view-${rowName}`).empty();
                    $(`#view-${rowName}`).append(list);
                    // console.log(rowName + 'loaded');
                    // console.log(list);
                }
            } catch (error) {
                alert(error);
            }
        }

        function get_package_name(id) {
            $.get(dataurl, `packagename=true&id=${id}`)
                .done(function (data) {
                    $('#view-package').empty();
                    $('#view-package').html(data);
                })
                .fail(function (error, status, errmsg) {
                    console.log(error);
                    console.log(status + errmsg);
                });
        }

        async function view_transaction(transId) {
            try {
                const details = await $.ajax({
                    type: 'GET',
                    url: dataurl,
                    dataType: 'json',
                    data: {
                        details: 'true',
                        transId: transId
                    }
                });

                if (details.success) {
                    // console.log(details);
                    let d = details.success;
                    $('#view-transId').val(d.id);
                    $('#view-customerName').val(d.customer_name ?? `Name not set.`);
                    $('#view-expiry').val(d.pack_exp ?? '--/--/--');
                    $('#list-status').empty();
                    $('#view-status').html(d.transaction_status);
                    $('#view-address').html(d.customer_address ?? 'Address not set.');
                    $('#edit-address').html(d.customer_address ?? '');
                    $('#edit-session').val(d.session_no ?? '-');
                    $('#view-treatmentType').html(d.treatment_type ?? 'Treatment type not set');

                    if (d.void_request === 1) {
                        $("#transvoidalert").show().text("This transaction is requested for void by " + d.updated_by);
                    } else {
                        $("#transvoidalert").hide();
                    }

                    if (d.transaction_status === 'Completed' || d.transaction_status === 'Voided') {
                        $('#editbtn').hide().attr('disabled', true);
                        $("#viewEditForm #requestvoidbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                        $("#viewEditForm #modalcancelbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                    } else if (d.transaction_status === 'Cancelled') {
                        $('#editbtn').show().attr('disabled', false);
                        $("#viewEditForm #requestvoidbtn").show().prop('disabled', false).attr('data-bs-target', '#voidrequestmodal');
                        $("#viewEditForm #modalcancelbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                    } else if (d.transaction_status === 'Finalizing') {
                        $('#editbtn').show().attr('disabled', false);
                        $("#viewEditForm #requestvoidbtn").show().prop('disabled', false).attr('data-bs-target', '#voidrequestmodal');
                        $("#viewEditForm #modalcancelbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                    } else if (d.transaction_status === 'Dispatched') {
                        $('#editbtn').show().attr('disabled', false);
                        $("#viewEditForm #requestvoidbtn").show().prop('disabled', false).attr('data-bs-target', '#voidrequestmodal');
                        $("#viewEditForm #modalcancelbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                    } else {
                        $('#editbtn').show().attr('disabled', false);
                        $("#viewEditForm #requestvoidbtn").show().prop('disabled', false).attr('data-bs-target', '#voidrequestmodal');

                        var t = new Date();
                        var tdate = new Date(d.treatment_date);
                        var today = new Date(t.getFullYear(), t.getMonth(), t.getDate());

                        if (tdate > today) {
                            $("#viewEditForm #modalcancelbtn").show().prop('disabled', false).attr('data-bs-target', '#cancelscheduledmodal');

                        } else {
                            $("#viewEditForm #modalcancelbtn").hide().prop('disabled', true).attr('data-bs-target', '#cancelscheduledmodal');
                        }
                    }

                    if (d.package_id !== null) {
                        // package assigned
                        $('#edit-treatment').removeAttr('name');
                        $('#view-expiry').attr('name', function (i, a) {
                            return a ? a : 'edit-expiry'
                        });
                        $('#view-start').attr('name', function (i, a) {
                            return a ? a : 'edit-start'
                        });
                        $('#edit-session').attr('disabled', function (i, a) {
                            return a == true ? false : a;
                        });
                        $('#edit-treatment').attr('disabled', function (i, a) {
                            return a == true ? false : a;
                        });
                    } else {
                        // null | no package assigned
                        $('#edit-treatment').attr('name', function (i, a) {
                            return a ? a : 'edit-treatment'
                        });
                        $('#edit-session, #view-expiry, #view-start').removeAttr('name');
                        $('#edit-session, #view-expiry, #view-start').attr('disabled', true);
                    }

                    $('#edit-note').val(d.notes ?? '');
                    $('#view-note').html(d.notes == null || d.notes == '' ? 'No existing note.' : d.notes);

                    $('#edit-status').val(d.transaction_status);

                    $('#edit-addMoreChem').attr('data-status', d.transaction_status);

                    let tname = treatment_name(d.treatment);
                    $(`#edit-treatment option[value='${tname}']`).attr('selected', true);
                    $(`#edit-treatmentType option[value='${d.treatment_type}']`).attr('selected', true);
                    $('#view-time').html(`Created at: ${d.created_at} by ${d.created_by}<br>Updated at: ${d.updated_at} by ${d.updated_by}`);

                    if (d.package_id == null) {
                        $('#edit-session').removeAttr('name');
                        $('#edit-session').attr('disabled', true);

                        $('#edit-treatment').attr('disabled', false);
                        $('#edit-treatment').attr('name', 'edit-treatment');

                        $('#view-start, #view-expiry').removeAttr('name');
                        $('#view-start, #view-expiry').attr('disabled', true);
                    } else {
                        $('#edit-treatment').removeAttr('name');
                        $('#edit-treatment').attr('disabled', true);

                        $('#edit-session').attr('disabled', false);
                        $('#edit-session').attr('name', 'edit-session');

                        $('#view-start').attr('name', 'edit-start');
                        $('#view-expiry').attr('name', 'edit-expiry');
                        $('#view-start, #view-expiry').attr('disabled', false);

                    }

                    $("#view_wstart").text(d.package_start === "January 1, 1970" ? '-' : d.package_start);
                    $("#view_wexpiry").text(d.package_end === "January 1, 1970" ? '-' : d.package_end);
                    $("#t_viewdate").text(d.trans_date);
                    $("#t_viewtime").text(d.trans_time);

                    const functions = await Promise.all([
                        await view_technician(d.id),
                        await view(d.treatment, 'treatment'),
                        await view(d.id, 'probCheckbox'),
                        await view(d.id, 'chemUsed'),
                        // await edit('treatment-options', d.treatment), //treatment option
                        // await edit('technicianName', d.id),
                        // await edit('probCheckbox', d.id),
                        // await edit('package', d.package_id ?? 0),
                        await get_chemical_brand('edit', d.id, d.transaction_status),
                        await get_package_name(d.package_id ?? 'none')
                    ]);

                    if (functions) {
                        // console.log(d.package_id);
                        $('#details-modal').modal('show');
                    } else {
                        alert('Details Modal Error. Refresh Page.');
                    }
                }
            } catch (error) {
                alert(error.responseText);
                console.log(error);
            }
        }
    </script>
</body>

</html>