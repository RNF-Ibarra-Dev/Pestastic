<?php require("startsession.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operation Supervisor | Transactions</title>
    <?php include('header.links.php'); ?>

</head>

<body class="bg-official text-light">

    <div class="sa-bg container-fluid p-0 d-flex">
        <!-- sidebar -->
        <?php include('sidenav.php'); ?>
        <!-- main content -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php include('navbar.php'); ?>

            <!-- content start -->
            <div class="bg-light bg-opacity-25 pt-2 rounded p-3 mx-3 mt-3 mb-2 ">
                <h1 class="display-6 text-light mb-0 fw-medium text-center">Manage Transactions</h1>
            </div>
            <div class="d-flex gap-2 mb-2 mx-3 user-select-none text-center">
                <div class="bg-light bg-opacity-25 rounded px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <p class="fs-5 fw-bold "><i
                            class="bi bi-alarm-fill me-2 bg-warning bg-opacity-25 py-1 px-2 rounded shadow-sm "></i>Pending
                    </p>
                    <p class="fw-light mb-0 ">Pending transactions that needs to be approved by either Operations
                        Supervisor or Manager.</p>
                    <p class="fs-4 fw-bold mb-0 mt-2" id="count_pending"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <p class="fs-5 fw-bold"><i
                            class="bi bi-clipboard-check-fill me-2 bg-success bg-opacity-25 py-1 px-2 rounded shadow-sm "></i>Accepted
                    </p>
                    <p class="fw-light mb-0 ">Accepted transactions that is at standby until dispatched at a
                        specific date.</p>
                    <p class="fs-4 fw-bold mb-0 mt-2" id="count_accepted"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <p class="fs-5 fw-bold"><i
                            class="bi bi-calendar2-check-fill me-2 bg-info bg-opacity-25 py-1 px-2 rounded shadow-sm "></i>Completed
                    </p>
                    <p class="fw-light mb-0 ">Completed transactions marked done by Technicians and approved by
                        Operations Supervisors.</p>
                    <p class="fs-4 fw-bold mb-0 mt-2" id="count_completed"></p>
                </div>
            </div>
            <div class="d-flex gap-2 mb-2 mx-3 user-select-none text-center">
                <div class="bg-light bg-opacity-25 rounded px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <p class="fs-5 fw-bold"><i
                            class="bi bi-clipboard-x-fill me-2 bg-danger bg-opacity-25 py-1 px-2 rounded shadow-sm "></i>Voided
                    </p>
                    <p class="fw-light mb-0 ">Voided transactions cancelled due to a specific cause.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_voided"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <p class="fs-5 fw-bold"><i
                            class="bi bi-arrow-repeat me-2 bg-primary bg-opacity-25 py-1 px-2 rounded shadow-sm "></i>Finalizing
                    </p>
                    <p class="fw-light mb-0 ">Accepted transactions completion.</p>
                    <p class="fs-4 fw-bold mb-0 mt-auto" id="count_finalizing"></p>
                </div>
                <div class="bg-light bg-opacity-25 rounded px-3 py-2 flex-fill flex-wrap w-100 d-flex flex-column  ">
                    <p class="fs-5 fw-bold"><i
                            class="bi bi-calendar2-x-fill me-2 bg-secondary bg-opacity-25 py-1 px-2 rounded shadow-sm "></i>Cancelled
                    </p>
                    <p class="fw-light mb-0 ">Cancelled transaction schedules.</p>
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

            <div class="table-responsive-sm d-flex justify-content-center">
                <table class="table align-middle table-hover m-3 mt-2 os-table w-100 text-light">
                    <caption class="text-light text-muted">List of all transactions. For faster transaction approval,
                        click
                        'Pending' under the status column.</caption>
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
                    <!-- table ajax whatsoever basta yon -->
                    <tbody id="table"></tbody>
                </table>
            </div>

            <!-- modals -->
            <form id="addTransaction">
                <div class="row g-2 text-dark m-0">
                    <div class="modal fade text-dark modal-edit" id="addModal" tabindex="-1" aria-labelledby="create"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Add New Transaction</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <p
                                        class="fw-medium mb-4 fs-4 text-uppercase text-center bg-dark bg-gradient bg-opacity-25 text-white rounded p-2">
                                        Customer Information</p>
                                    <div class="row mb-2">
                                        <div class="col-lg-6 mb-2">
                                            <label for="add-customerName" class="form-label fw-light">Customer Name
                                            </label>
                                            <input type="text" name="add-customerName" id="add-customerName"
                                                class="form-control form-add" placeholder="Enter name"
                                                autocomplete="one-time-code">
                                            <!-- <p class="text-body-secondary text-muted fw-light">Note: Include full customer name</p> -->
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label for="add-customerAddress" class="form-label fw-light">Customer Full
                                                Address
                                            </label>
                                            <textarea name="add-customerAddress" id="add-customerAddress"
                                                class="form-control form-add" rows="1"
                                                placeholder="e.g B20 L64 Garnet Street Lee Grove 4 Mandaluyong, Metro Manila"></textarea>
                                        </div>
                                    </div>


                                    <hr class="my-2">

                                    <p
                                        class="fw-medium mt-3 mb-4 fs-4 text-uppercase text-center bg-dark bg-gradient bg-opacity-25 text-white rounded p-2">
                                        Treatment Information
                                    </p>

                                    <div class="row mb-2">

                                        <div class="col-lg-3 mb-2">
                                            <label for="add-treatmentDate" class="form-label fw-light">Treatment
                                                Date</label>
                                            <input name="add-treatmentDate" placeholder="--/--/--"
                                                id="add-treatmentDate" class="form-control form-add">
                                        </div>
                                        <div class="col-lg-3 mb-2">
                                            <label for="add-treatmentTime" class="form-label fw-light">Treatment
                                                Time</label>
                                            <input name="add-treatmentTime" id="add-treatmentTime" placeholder="--:--"
                                                class="form-control form-add" autocomplete="address-line3">
                                        </div>

                                        <div class="col-lg-6 mb-2">
                                            <label for="add-treatment" class="form-label fw-light">Treatment</label>
                                            <select name="add-treatment" name="add-treatment" id="add-treatment"
                                                class="form-select">
                                                <option value="" selected>Select Treatment</option>
                                                <div id="add-treatmentContainer"></div>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            <label for="add-treatmentType" class="form-label fw-light">Treatment
                                                Type</label>
                                            <select name="add-treatmentType" id="add-treatmentType" class="form-select">
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
                                        <div class="col-lg-8">
                                            <label for="add-package" class="form-label fw-light">Package</label>
                                            <select name="add-package" id="add-package" class="form-select">
                                                <div id="packageSelectContainer"></div>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 mb-2">
                                            <label for="add-packageStart"
                                                class="form-label fw-light text-nowrap">Package Warranty
                                                Start</label>
                                            <input placeholder="--/--/--" id="add-packageStart" name="add-packageStart "
                                                class="form-control form-add" disabled>
                                        </div>
                                        <div class="col-lg-3 mb-2">
                                            <label for="add-packageExpiry" class="form-label fw-light">Package
                                                Expiry</label>
                                            <input placeholder="--/--/--" class="fw-light form-control"
                                                name="add-packageExpiry" id="add-packageExpiry" readonly disabled>
                                        </div>

                                        <div class="col-lg-3">
                                            <label for="add-session" class="form-label fw-light text-nowrap">Session
                                                Number</label>
                                            <input type="number" name="add-session" class="form-control form-add"
                                                id="add-session" placeholder="e.g. 2" autocomplete="one-time-code"
                                                disabled>
                                        </div>
                                    </div>

                                    <hr class="mb-2 mt-0">

                                    <p
                                        class="fw-medium mt-3 mb-2 fs-4 text-uppercase text-center bg-dark bg-gradient bg-opacity-25 text-white rounded p-2">
                                        Additional
                                        Information
                                    </p>

                                    <div class="row mb-2">

                                        <div class="col-lg-12 mb-2">
                                            <label for="add-probCheckbox" class="form-label fw-light">Pest
                                                Problem</label>
                                            <div id="add-probCheckbox" name="add-probCheckbox"
                                                class="d-flex gap-2 align-items-center justify-content-evenly flex-wrap">
                                                <!-- pest problems ajax append here -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-2" did="row">
                                        <div class="col-lg-6 mb-2">
                                            <label for="add-chemBrandUsed" class="form-label fw-light">Chemical
                                                Used</label>
                                            <select id="add-chemBrandUsed" name="add_chemBrandUsed[]"
                                                class="form-select">
                                                <!-- chem ajax -->
                                            </select>

                                        </div>


                                        <div class="col-lg-2 mb-2 d-flex gap-1 p-0 justify-content-start">
                                            <button type="button" id="addMoreChem"
                                                class="btn btn-grad mt-auto py-2 px-3"><i
                                                    class="bi bi-plus-circle text-light"></i></button>
                                        </div>
                                    </div>

                                    <div class="row mb-2" id="add-chemContainer">
                                        <!-- template add chemical -->
                                    </div>
                                    <div class="row mb-2">
                                        <div class="dropdown-center col-lg-6 mb-2">
                                            <label for="add-technicianName" class="form-label fw-light">Technicians
                                            </label>
                                            <select id="add-technicianName" name="add-technicianName[]" not-size="2"
                                                class="form-select" aria-label="Default select example">
                                            </select>
                                        </div>
                                        <div class="col-lg-2 mb-2 d-flex gap-1 p-0 justify-content-start">
                                            <button type="button" id="addMoreTech"
                                                class="btn btn-grad mt-auto py-1 px-3"><i
                                                    class="bi bi-plus-circle text-light"></i></button>
                                        </div>
                                    </div>

                                    <div id="addTechContainer" class="row mb-2"></div>
                                    <div class="row mb-2 ">
                                        <div class="col-lg-6">
                                            <label for="add-status" class="form-label fw-light">Status</label>
                                            <select name="add-status" id="add-status" class="form-select">
                                                <option value="" selected>Select Status</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Accepted">Accepted</option>
                                                <option value="Finalizing">Finalizing </option>
                                                <option value="Completed">Completed </option>
                                                <option value="Cancelled">Cancelled </option>
                                                <option value="Voided">Voided </option>
                                            </select>
                                            <p class="alert alert-warning py-1 mt-2" style="display: none !important;">
                                            </p>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="add-notes" class="form-label fw-light">Additional Notes</label>
                                            <textarea name="add-notes" id="add-notes" placeholder=". . ."
                                                class="form-control" rows="1"></textarea>
                                        </div>
                                    </div>

                                    <p class="text-center alert alert-info w-75 mx-auto visually-hidden"
                                        id="emptyInput"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-grad" disabled-id="submitAdd"
                                        data-bs-toggle="modal" data-bs-target="#confirmAdd">Proceed &
                                        Confirm</button>
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
                                    <label for="addPwd" class="form-label fw-light">Add transaction? Enter manager
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control" id="addPwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-3 w-75 mx-auto my-0 visually-hidden'
                                    id="add-alert"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#addModal"
                                    data-bs-toggle="modal">Go back</button>
                                <button type="submit" class="btn btn-grad" id="submitAdd">Add Transaction</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


            <!-- view/edit -->
            <form id="viewEditForm">
                <div class="row g-2 text-dark m-0">
                    <div class="modal fade text-dark modal-edit" id="details-modal" tabindex="-1">
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
                                            <textarea type="text" id="view-address"
                                                class="form-control-plaintext form-add" readonly
                                                style="resize: none !important;"></textarea>
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
                                                class="form-control form-add" style="border: none !important;" disabled>
                                        </div>

                                        <div class="col-lg-2 mb-2">
                                            <label for="view-treatmentTime" class="form-label fw-light">Treatment
                                                Time:</label>
                                            <input type="text" name="edit-treatmentTime" id="view-treatmentTime"
                                                class="form-control form-add" style="border: none !important;" disabled>
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
                                            <select name="edit-package" class="form-select d-none"
                                                id="edit-package-select">
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
                                            <input class="form-control ps-3" placeholder="--/--/--" id="view-start"
                                                disabled>
                                        </div>
                                        <div class="col-lg-2">
                                            <label for="view-expiry" class="form-label text-nowrap fw-light">Warranty
                                                Expiry:</label>
                                            <input class="form-control-plaintext ps-3" placeholder="--/--/--"
                                                id="view-expiry" disabled readonly>
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
                                            <label for="edit-status" class="form-label"
                                                id='label-edit-status'>Transaction Status:</label>
                                            <select name="edit-status" id="edit-status" class="form-select ">
                                                <option value="" selected>Select Status</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Accepted">Accepted</option>
                                                <option value="Finalizing">Finalizing </option>
                                                <option value="Completed">Completed </option>
                                                <option value="Cancelled">Cancelled </option>
                                                <option value="Voided">Voided</option>
                                            </select>
                                            <p class="alert alert-warning py-1 mt-2" style="display: none !important;">
                                            </p>
                                        </div>

                                    </div>

                                    <!-- toggle visually hidden when edit -->
                                    <p class="mb-0 mt-4" id='metadata'><small id=view-time class="text-muted"></small>
                                    </p>

                                    <div class="row mt-3 mb-2 mx-auto g-2 d-flex justify-content-evenly visually-hidden"
                                        id="editbtns">
                                        <button type="button" class="btn btn-grad" style="width: 45% !important;"
                                            id="confirmUpdate" data-bs-target="#confirmation"
                                            data-bs-toggle="modal">Update
                                            Transaction</button>
                                        <button type="button" class="btn btn-grad" style="width: 45% !important;"
                                            id="confirmDelete" data-bs-toggle="modal"
                                            data-bs-target="#confirmation">Delete
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

                <div class="modal fade text-dark modal-edit" id="confirmation" tabindex="0"
                    aria-labelledby="confirmDelete" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyAdd"></h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="addPwd" class="form-label fw-light">Enter manager
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="edit-saPwd" class="form-control" id="editPwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-3 w-75 mx-auto my-0 visually-hidden'
                                    id="del-errormessage"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#details-modal"
                                    data-bs-toggle="modal">Cancel</button>
                                <button type="button" class="btn btn-grad" id="edit-confirm"></button>
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
                                        </tr>
                                    </thead>

                                    <tbody id="voidrequesttable" class="table-group-divider">
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
                                                <th class="text-dark">
                                                    <input type="checkbox" class="btn-check" id="checkall"
                                                        autocomplete="off">
                                                    <label class="btn fw-bold" for="checkall">Check All <i
                                                            id="checkicon" class="bi bi-square ms-2"></i></label>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody id="finalizetranstable" class="table-group-divider">
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

            <!-- approve modal -->
            <form id="approvependingtransactions">
                <div class="modal fade text-dark modal-edit" id="approvemodal" tabindex="0"
                    aria-labelledby="confirmDelete" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Pending Transaction Approval</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="addPwd" class="form-label fw-light">Approve Pending Transaction? <span
                                            id="transidspan"></span> Enter Manager
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="approve-pwd" class="form-control">
                                    </div>
                                    <input type="hidden" id="transidinput" name="transid">
                                </div>
                                <p class='text-center alert alert-info p-3 w-75 mx-auto my-0 visually-hidden'
                                    id="approve-alert">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close Modal</button>
                                <button type="submit" class="btn btn-grad" id="approvebtn">Approve Transaction</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="voidrequestform">
                <input type="hidden" name="transid" id="voidreqid">
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="voidrequestmodal"
                    tabindex="0">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Void Request Confirmation</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="confirmapprove-inputpwd" class="form-label fw-light">Send request to void
                                        transaction?
                                        Enter Operation Supervisor
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="baPwd" class="form-control"
                                            id="confirmapprove-inputpwd">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    Note: This might take a while for the Manager to process. Please be patient.
                                </div>
                                <p class="text-center alert alert-info w-75 mx-auto visually-hidden" id="voidalert">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#details-modal">Go back</button>
                                <button type="submit" class="btn btn-grad">Send Request</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="cancelscheduledform">
                <input type="hidden" name="transid" id="transidinputcancel">
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="cancelscheduledmodal"
                    tabindex="0">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Schedule Cancellation</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="confirmapprove-inputpwd" class="form-label fw-light">Cancel Transaction?
                                        Enter Operation Supervisor
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="baPwd" class="form-control"
                                            id="confirmapprove-inputpwd">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    Note: Cancelled transactions must be rescheduled soon or manager can void it
                                    directly.
                                </div>
                                <p class="text-center alert alert-info w-75 mt-2 mb-0 mx-auto" style="display: none;"
                                    id="cancelAlert">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#details-modal">Go back</button>
                                <button type="submit" class="btn btn-grad">Send Request</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="reschedForm">
                <input type="hidden" name="reschedid" id="reschedId">
                <div class="modal fade text-dark modal-edit" id="reschedModal" tabindex="-1" aria-labelledby="create"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Reschedule Cancelled Transaction</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                        class="bi text-light bi-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <label for="rescheddate" class="form-label">Select New Schedule</label>
                                        <input type="date" name="reschedDate" class="form-control" id="reschedDate">
                                    </div>
                                    <div class="col">
                                        <label for="reschedTime" class="form-label">Select New Time:</label>
                                        <input type="text" name="reschedTime" class="form-control" id="reschedTime">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-bs-dismiss="modal" class="btn btn-grad">Close</button>
                                <button type="button" data-bs-target="#reschedConfirm" data-bs-toggle="modal"
                                    class="btn btn-grad">Proceed</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="reschedConfirm" tabindex="0">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Reschedule Transaction Confirmation</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="confirmapprove-inputpwd" class="form-label fw-light">Confirm reschedule
                                        of this transaction?
                                        Enter Operation Supervisor
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="baPwd" class="form-control w-50"
                                            id="confirmapprove-inputpwd">
                                    </div>
                                </div>
                                <p class="text-body-secondary">Note. After setting new time and schedule, this
                                    transaction will be marked as Accepted.</p>
                                <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                    id="reschedAlert">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#reschedModal">Go back</button>
                                <button type="submit" class="btn btn-grad">Reschedule</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- modals end -->

            <div class="d-flex justify-content-center mb-5 visually-hidden" id="loader">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div id="pagination"></div>
            <p class='text-center alert alert-success w-50 mx-auto visually-hidden' id="tableAlert"></p>

            <!-- toast -->
            <div class="toast-container m-2 me-3 bottom-0 end-0">
                <div class="toast align-items-center" role="alert" id="toast" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body text-dark ps-4 text-success-emphasis" id="toastmsg">
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <!-- content end -->
        </main>
    </div>

    <?php include('footer.links.php'); ?>

    <script>
        const transUrl = 'contents/trans.data.php';
        const submitUrl = 'contents/transconfig.php';
        // addTechContainer | add-chemContainer

        function show_toast(message) {
            $('#toastmsg').html(message);
            var toastid = $('#toast');
            var toast = new bootstrap.Toast(toastid);
            toast.show();
        }

        <?php
        if (isset($_GET['openmodal']) && $_GET['openmodal'] === 'true') {
        ?>
            $('#viewEditForm')[0].reset();
            let id = <?= $_GET['id']; ?>;
            console.log(id);
            view_transaction(id);
            $('#details-modal').on('hidden.bs.modal', function(e) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('openmodal');
                currentUrl.searchParams.delete('id');
                window.history.pushState(null, "", currentUrl.pathname + currentUrl.search);
            });
        <?php
        }
        ?>


        $(document).on('change', '#checkall', function() {
            $('#checkicon').toggleClass('bi-square bi-check-square');
            var checked = $(this).prop('checked');
            $('tbody tr td div input[type="checkbox"]').prop('checked', checked);
        });



        let apd = $('#add-packageStart');
        addPackageDate = flatpickr(apd, {
            // altInput: true,
            // altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        });

        async function compute_package_expiry(date, packId) {
            return $.post(transUrl, {
                    date: date,
                    pack_exp: 'true',
                    pid: packId
                }, function(data) {
                    // alert(data);
                    return data;
                })
                .fail(function(err) {
                    console.log(err);
                })
        }

        $(document).on("shown.bs.modal", "#finalizetransactionmodal", async function() {
            let status = $("#sortstatus").val();
            await $.get(transUrl, "&finalizetrans=true")
                .done(function(d) {
                    $("#finalizetranstable").empty();
                    $("#finalizetranstable").append(d);
                    loadpage(1, status);
                })
                .fail(function(e) {
                    console.log(e);
                })
        });

        $(document).on('submit', "#finalizetransactionform", async function(e) {

            e.preventDefault();
            console.log($(this).serialize());
            await $.ajax({
                    method: 'POST',
                    url: submitUrl,
                    dataType: 'json',
                    data: $(this).serialize() + '&finalize=true'
                })
                .done(function(data) {
                    console.log(data);
                    if (data.success) {
                        $('#finalizeconfirm').modal('hide');
                        $("#tableAlert").removeClass('visually-hidden').html(data.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        loadpage(1, $("#sortstatus").val());
                        $('#finalizetransactionmodal')[0].reset();
                    }
                })
                .fail(function(err) {
                    console.log(err);
                    $("#finalizealert").html(err.responseText).fadeIn(400).delay(2000).fadeOut(1000);
                });
        });

        $("#table").on('click', '.finalize-btn', function() {
            // console.log($(this).data('finalize-id'));
            let id = $(this).data('finalize-id');
            $("#finalizebackbtn").hide().attr('data-bs-target', '');
            $("#finalizeconfirm").modal('show');
            $("#finalizeconfirm").on('hidden.bs.modal', function() {
                $("#finalizebackbtn").show().attr('data-bs-target', '#finalizetransactionmodal');
                $("#finalizesingletransinput").prop('disabled', true);
            });
            $("#finalizesingletransinput").prop('disabled', false).val(id);
        });

        // $('#table').on('click', 'cancel-btn')



        $(document).on('change', '#add-packageStart', async function(e) {
            let package_id = $('#add-package').val();
            if (!$.isNumeric(package_id)) {
                alert('Please Select a package! Invalid package ID.');
            } else {
                let date = $(this).val();
                let exp = await compute_package_expiry(date, package_id);
                $('#add-packageExpiry').empty();
                $('#add-packageExpiry').val(exp);
                // alert(exp);
            }
        })

        let adddate = $('#add-treatmentDate');
        addDate = flatpickr(adddate, {
            // altInput: true,
            // altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            minDate: new Date().fp_incr(1),
            setDate: 'today'
            // enableTime: true
        });

        let addtime = $('#add-treatmentTime');
        addTime = flatpickr(addtime, {
            altInput: true,
            altFormat: "h:i K",
            dateFormat: "h:i",
            enableTime: true,
            noCalendar: true,
            setDate: '8:00'
        });

        // $('#viewEditForm').on('change', 'select#edit-status', function () {
        //     if ($(this).val() === 'Completed') {
        //         // console.log('tte');
        //         $('#statusNote').html('Note: Once a transaction is marked as completed, it is no longer editable.').removeClass('d-none');
        //     } else {
        //         $("#statusNote").addClass('d-none');
        //     }
        // })

        $(document).on('click', '#pendingbtn', function() {
            let transId = $(this).data('pending-id');
            console.log(transId);
            $('#transidinput').val(transId);
            $('#transidspan').val(transId);
            $('#approvependingtransactions')[0].reset();
            $('#approvemodal').modal('show');
        });


        $('#approvependingtransactions').on('submit', async function(e) {
            e.preventDefault();
            // console.log($(this).serialize());
            let status = $('#sortstatus').val();
            try {
                const approve = await $.ajax({
                    method: 'POST',
                    dataType: 'json',
                    url: submitUrl,
                    data: $(this).serialize() + '&approve=true'
                });

                if (approve) {
                    // console.log(approve);
                    $('#approvemodal').modal('hide');
                    loadpage(1, status);
                    // $("#tableAlert").removeClass('visually-hidden').html(approve.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                    show_toast(approve.success);
                }

            } catch (error) {
                console.log(error);
                let err = error.responseText;
                $("#approve-alert").removeClass('visually-hidden').html(err).hide().fadeIn(400).delay(2000).fadeOut(1000);
            }
        })

        async function treatments(form) {
            try {
                $.get(transUrl, "treatments=true", function(data) {
                    $(`#${form}-treatmentContainer`).empty();
                    $(`#${form}-treatmentContainer`).html(data);
                });
            } catch (error) {
                console.log(error);
            }
        }

        $(document).on('click', '#addbtn', async function() {
            let form = 'add';
            try {
                const load = await Promise.all([
                    get_chemical_brand(form),
                    get_technician(form),
                    get_problems(form),
                    add_more_chem(),
                    add_more_tech(),
                    add_packages(),
                    treatments(form)
                ]);
                if (load) {
                    $('#addTransaction')[0].reset();
                    $('#addTechContainer').empty();
                    $('#add-chemContainer').empty();
                    $('#addModal').modal('show');
                }

            } catch (error) {
                console.log('add get error.')
            }
        });


        async function add_packages() {
            try {
                const package = await $.ajax({
                    method: 'GET',
                    url: transUrl,
                    dataType: 'html',
                    data: "packages=true"
                });

                if (package) {
                    $('#packageSelectContainer').empty();
                    $('#packageSelectContainer').html(package);
                }
            } catch (error) {
                console.log(error);
                console.log(error.responseText);
            }
        }

        // add / delete more technicians
        async function add_more_tech() {
            let num = 2;

            $('#addMoreTech', '#addModal').off('click').on('click', async function() {
                // console.log('tite' + num);
                await get_more_tech(num);
                num++;
                console.log('tech add number: ' + num);
            });

        }

        function get_overview_count(container) {
            $.get(transUrl, {
                    count: true,
                    status: container
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

        // $(document).ready(function() {
        //     get_counts();
        // });

        function get_counts() {
            get_overview_count('pending');
            get_overview_count('accepted');
            get_overview_count('completed');
            get_overview_count('voided');
            get_overview_count('cancelled');
            get_overview_count('finalizing');
        }

        // add / delete chem main function
        async function add_more_chem() {
            // let moreChemTemp = $('#add-chemicalData').html();
            let num = 2;

            $('#addMoreChem', '#addModal').off('click').on('click', async function() {
                await add_used_chem(num);
                num++;
                console.log(num);

            });

        }

        // append tech row function
        async function get_more_tech(num) {
            try {
                const addTech = await $.ajax({
                    type: 'GET',
                    url: transUrl,
                    dataType: 'html',
                    data: {
                        addMoreTech: 'true',
                        techRowNum: num
                    }
                });

                if (addTech) {
                    $('#addTechContainer').append(addTech);
                } else {
                    console.log('add tech append failed');
                }
            } catch (error) {
                alert('Adding more technician error: ' + JSON.stringify(error));
            }
        }

        // append chem row function
        async function add_used_chem(num) {
            // let rowNum = num;
            try {
                const addMoreUsed = await $.ajax({
                    type: 'GET',
                    url: transUrl,
                    dataType: 'html',
                    data: {
                        getMoreChem: 'true',
                        rowNum: num
                    }
                });

                if (addMoreUsed) {
                    $('#add-chemContainer').append(addMoreUsed);
                    // console.log('add more chem success');
                }
            } catch (error) {
                alert(error);
            }
        }

        // fetch modal contents:
        async function get_problems(form, checked = null) {
            // if (typeof transId == 'undefined') transId = null;
            $(`#${form}-probCheckbox`).empty();
            try {
                const prob = await $.ajax({
                    type: 'GET',
                    url: transUrl,
                    dataType: 'html',
                    data: {
                        getProb: 'true',
                        checked: checked
                    }
                });

                if (prob) {
                    $(`#${form}-probCheckbox`).append(prob);
                    console.log('get problems success.');
                }
            } catch {
                alert('get problem error');
            }
        }

        async function get_technician(form, activeTech) {
            if (typeof activeTech == 'undefined') activeTech = null;
            // console.log('no active tech. Var = ' + activeTech);

            try {
                $(`#${form}-technicianName`).empty();
                const tech = await $.ajax({
                    type: 'GET',
                    url: transUrl,
                    data: {
                        getTech: 'true',
                        active: activeTech
                    }
                });

                if (tech) {
                    $(`#${form}-technicianName`).append(tech);
                    console.log('success');
                    console.log(activeTech);
                }
            } catch (error) {
                alert('tech: ' + error);
            }
        }

        async function get_chemical_brand(method, transId = null, status = null) {
            try {
                const brand = await $.ajax({
                    type: 'GET',
                    url: transUrl,
                    data: {
                        getChem: method,
                        transId: transId,
                        status: status
                    }
                });

                if (brand) {
                    $(`#${method}-chemBrandUsed`).empty();
                    $(`#${method}-chemBrandUsed`).append(brand);
                    // console.log(brand);
                }
            } catch (error) {
                alert('get chem: ' + error);
            }
        }

        let etd = $('#view-treatmentDate');
        editTransDate = flatpickr(etd, {
            dateFormat: "Y-m-d",
            // placeholder: "--/--/--"
            // minDate: new Date().fp_incr(1),
        });

        // let expdate = $('#view-expiry');
        // packageExpDate = flatpickr(expdate, {
        //     dateFormat: "Y-m-d",
        //     // minDate: new Date().fp_incr(1),
        // });

        let startdate = $('#view-start');
        packageStartDate = flatpickr(startdate, {
            dateFormat: "Y-m-d",
            // minDate: new Date().fp_incr(1),
        });

        let ett = $('#view-treatmentTime');
        editTransTime = flatpickr(ett, {
            dateFormat: "H:i",
            noCalendar: true,
            enableTime: true,
            // minDate: new Date().fp_incr(1),
        });

        let toggled = false;

        async function toggle() {
            // let package = 

            $("#view-customerName, #edit-session").attr("readonly", function(i, attr) {
                if (attr) {
                    $(this).removeClass('form-control-plaintext');
                    $(this).addClass('form-control');
                    if ($('#edit-session').val() == 'One time session.') {
                        $('#edit-session').val('');
                    }
                } else {
                    if ($('#edit-session').val().length === 0) {
                        $('#edit-session').val('One time session.');
                    }
                    $(this).removeClass('form-control');
                    $(this).addClass('form-control-plaintext');
                }

                return attr ? false : true;
            });
            $("#view-treatmentDate, #view-treatmentTime, #view-start, #view-expiry").attr("disabled", function(i, attr) {
                $(this).removeAttr('style');
                if (attr) {
                    $(this).removeAttr('style');
                } else {
                    $(this).attr('style', 'border: none !important');
                }
                return attr ? false : true;
            });
            $('#view-technicians-label').toggleClass('visually-hidden');
            $('#view-technicians').toggleClass('visually-hidden');
            $('#view-treatment-label').toggleClass('visually-hidden');
            $('#view-treatment').toggleClass('visually-hidden');
            $('#view-chemUsedContainer').toggleClass('visually-hidden');
            $('#view-probCheckbox').toggleClass('visually-hidden');
            $('#view-probCheckbox-label').toggleClass('visually-hidden');
            $('#metadata').toggleClass('visually-hidden');
            $('#view-status-col').toggleClass('visually-hidden');
            $('#view-addCont').toggleClass('d-none');
            $('#view-treatmentType').toggleClass('d-none');
            $('#view-package').toggleClass('d-none');
            $('#view-noteContainer').toggleClass('d-none');

            // removes visually-hidden when edit/detele button is clicked
            $('#edit-technicianName-label').toggleClass('visually-hidden');
            $('#edit-technicianName').toggleClass('visually-hidden');
            $('#techaddbtn').toggleClass('visually-hidden');
            $('#edit-treatment').toggleClass('visually-hidden');
            $('#edit-treatment-label').toggleClass('visually-hidden');
            $('#row-pestProblems').toggleClass('visually-hidden');
            $('#edit-chemBrandUsed').toggleClass('visually-hidden');
            $('#edit-techContainer').toggleClass('visually-hidden');
            $('#btn-amountUsed').toggleClass('visually-hidden');
            $('#edit-status-col').toggleClass('d-none');
            $('#editbtns').toggleClass('visually-hidden');
            $('#edit-addCont').toggleClass('d-none');
            $('#edit-treatmentType').toggleClass('d-none');
            $('#edit-package-select').toggleClass('d-none');
            $('#edit-noteContainer').toggleClass('d-none');

            if ($('#edit-package-select').val() != 'none') {
                $('#edit-treatment').attr('disabled', function(i, a) {
                    return a ? a : true;
                });
                $('#edit-session, #edit-start').attr('disabled', function(i, a) {
                    return a ? false : a;
                });
            }

            return toggled = true;
        }

        $(document).on('change', '#edit-status', function() {
            let sts = $(this).val();
            // if (sts == 'Pending' || 'Completed') {
            //     editTransDate.config.minDate = new Date().fp_incr(1);
            // } else {
            //     editTransDate.config.minDate = null;
            // }

            // $('#edit-addMoreChem').removeAttr('data-status');
            $('#edit-addMoreChem').data('status', sts);

            if (sts === 'Completed' || sts === 'Accepted' || sts === 'Finalizing') {
                $('#edit-chemBrandUsed input.form-control').prop('disabled', false);
                $('#edit-chemBrandUsed input.form-control').attr('name', 'edit-amountUsed[]');
            } else {
                $('#edit-chemBrandUsed input.form-control').prop('disabled', true);
                $('#edit-chemBrandUsed input.form-control').removeAttr('name');
            }
        });

        async function edit(name, transId = null) {
            try {
                const target = await $.ajax({
                    url: transUrl,
                    type: 'GET',
                    data: {
                        edit: name,
                        transId: transId
                    },
                    dataType: 'html'
                });

                if (target) {
                    // console.log(target);
                    if (transId != null) {
                        $(`#edit-${name}`).empty();
                        $(`#edit-${name}`).append(target);
                    } else {
                        $(`#edit-${name}`).append(target);
                    }
                } else {
                    alert('no edit returned' + target);
                }
            } catch (error) {
                alert('Error at edit function: ' + name + '\nerror: ' + error);
                console.log(error);
            }
        }

        async function view_technician(transId) {
            try {
                const list = await $.ajax({
                    type: 'GET',
                    dataType: 'html',
                    url: transUrl,
                    data: {
                        getTechList: 'true',
                        transId: transId
                    },
                    cache: true
                });

                if (list) {
                    $('#view-technicians').empty();
                    $('#view-technicians').append(list);
                    // console.log(list);
                    // console.log('technicians loaded');
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
                    url: transUrl,
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

        function treatment_name(id) {
            $.get(transUrl, `treatmentname=true&id=${id}`)
                .done(function(d) {
                    return d;
                })
                .fail(function(error, status, errmsg) {
                    console.log(error);
                    console.log(status + errmsg);
                });
        }

        function get_package_name(id) {
            $.get(transUrl, `packagename=true&id=${id}`)
                .done(function(data) {
                    $('#view-package').empty();
                    $('#view-package').html(data);
                })
                .fail(function(error, status, errmsg) {
                    console.log(error);
                    console.log(status + errmsg);
                });
        }

        let sval, tval, wval, weval;
        $(document).on('change', '#edit-package-select', function() {
            if ($(this).val() === 'none') {
                sval = $('#edit-session').val();
                $('#edit-session').val('');
                $('#edit-session').removeAttr('name');
                $('#edit-session').attr('disabled', true);

                tval = $('#edit-treatment').val();
                $('#edit-treatment').val('');
                $('#edit-treatment').attr('disabled', false);
                $('#edit-treatment').attr('name', 'edit-treatment');

                wval = $('#view-start').val();
                weval = $('#view-expiry').val();
                $('#view-start').val('');
                $('#view-expiry').val('');
                $('#view-start, #view-expiry').removeAttr('name');
                $('#view-start, #view-expiry').attr('disabled', true);
            } else {
                $('#edit-treatment').val(tval);
                $('#edit-treatment').removeAttr('name');
                $('#edit-treatment').attr('disabled', true);

                $('#edit-session').val(sval);
                $('#edit-session').attr('disabled', false);
                $('#edit-session').attr('name', 'edit-session');

                $('#view-start').attr('name', 'edit-start');
                $('#view-expiry').attr('name', 'edit-expiry');
                $('#view-start, #view-expiry').attr('disabled', false);
                $('#view-start').val(wval);
                $('#view-expiry').val(weval);

            }

        });

        $(document).on('click', '#requestvoidbtn', function() {
            // $('#requestedvoidtransactions').modal('show');
            $.get(transUrl, {
                    voidrequest: 'true'
                }, function(data) {
                    $('#voidrequesttable').empty();
                    $('#voidrequesttable').append(data);
                })
                .fail(function(e) {
                    console.log(e);
                })
        });

        $(document).on('click', "#modalcancelbtn", function() {
            let id = $('#view-transId').val();
            // console.log(id);
            $('#transidinputcancel').val(id);
            $('#cancelscheduledform').on('hidden.bs.modal', function() {
                $("#cancelscheduledform")[0].reset();

            });
        });

        $(document).on('click', '#voidrequestmodal', function() {
            let id = $('#view-transId').val();
            $("#voidreqid").val(id);
        })

        $(document).on('submit', "#voidrequestform", async function(e) {
            e.preventDefault();
            console.log($(this).serialize());

            $.ajax({
                    method: "POST",
                    url: submitUrl,
                    dataType: 'json',
                    data: $(this).serialize() + "&submitvoidreq=true"
                })
                .done(async function(d) {
                    show_toast(d.success);
                    $("#voidrequestform")[0].reset();
                    $("#voidrequestmodal").modal('hide');
                    await loadpage(1, $("#sortstatus").val());
                })
                .fail(function(e) {
                    $("voidalert").fadeIn(400).html(e.responseText).delay(2000).fadeOut(1000);
                    // console.log(e);
                })
        })
        $(document).on('submit', '#cancelscheduledform', async function(e) {
            e.preventDefault();
            // console.log($(this).serialize());
            await $.ajax({
                    method: "POST",
                    url: submitUrl,
                    dataType: 'json',
                    data: $(this).serialize() + "&cancel=true"
                })
                .done(async function(d) {
                    show_toast(d.success);
                    $("#cancelscheduledform")[0].reset();
                    $("#cancelscheduledmodal").modal('hide');
                    await loadpage(1, $("#sortstatus").val());
                })
                .fail(function(e) {
                    $("#cancelAlert").fadeIn(400).html(e.responseText).delay(2000).fadeOut(1000);
                    console.log(e);
                })
        })

        $("#table").on('click', '.cancel-btn', function() {
            let id = $(this).data('cancelled-id');
            // console.log(id);
            $("#reschedId").val(id);
            $("#reschedModal").modal('show');
        })

        // resched dates flatpickr
        let reschedDatee = document.getElementById('reschedDate');
        reschedDate = flatpickr(reschedDatee, {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            minDate: new Date().fp_incr(1)
        });

        let reschedTimee = document.getElementById('reschedTime');
        reschedTime = flatpickr(reschedTimee, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: false,
            altFormat: "h:i K",
            altInput: true
        });

        $(document).on("submit", "#reschedForm", async function(e) {
            e.preventDefault();
            console.log($(this).serialize());
            await $.ajax({
                    method: "POST",
                    url: submitUrl,
                    dataType: 'json',
                    data: $(this).serialize() + "&reschedule=true"
                })
                .done(async function(d) {
                    show_toast(d.success);
                    $("#reschedForm")[0].reset();
                    $("#reschedConfirm").modal('hide');
                    await loadpage(1, $("#sortstatus").val());
                })
                .fail(function(e) {
                    $("#reschedAlert").fadeIn(400).html(e.responseText).delay(2000).fadeOut(1000);
                    console.log(e);
                })
        });



        async function view_transaction(transId) {
            try {
                const details = await $.ajax({
                    type: 'GET',
                    url: transUrl,
                    dataType: 'json',
                    data: {
                        details: 'true',
                        transId: transId
                    }
                });

                if (details.success) {
                    console.log(details);
                    let d = details.success;
                    $('#view-transId').val(d.id);
                    $('#view-customerName').val(d.customer_name ?? `Name not set.`);
                    editTransDate.clear();
                    editTransDate.setDate(d.treatment_date);
                    // packageExpDate.clear();
                    // packageExpDate.setDate(d.pack_exp ?? '--/--/--');
                    $('#view-expiry').val(d.pack_exp ?? '--/--/--');
                    packageStartDate.clear();
                    packageStartDate.setDate(d.pack_start ?? '--/--/--');
                    $('#list-status').empty();
                    $('#view-status').html(d.transaction_status);
                    $('#view-address').html(d.customer_address ?? 'Address not set.');
                    $('#edit-address').html(d.customer_address ?? '');
                    editTransTime.setDate(d.transaction_time);
                    $('#edit-session').val(d.session_no ?? '-');
                    $('#view-treatmentType').html(d.treatment_type ?? 'Treatment type not set');

                    if (d.transaction_status == 'Completed' || d.transaction_status == 'Voided') {
                        $('#editbtn').hide().attr('disabled', true);
                        $("#viewEditForm #requestvoidbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                        $("#viewEditForm #modalcancelbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                    } else if (d.transaction_status === 'Cancelled') {
                        $("#viewEditForm #modalcancelbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                    } else if (d.transaction_status === 'Voided') {
                        $("#viewEditForm #requestvoidbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                    } else if (d.transaction_status === 'Finalizing') {
                        $("#viewEditForm #modalcancelbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                    } else {
                        $('#editbtn').show().attr('disabled', false);
                        $("#viewEditForm #requestvoidbtn").show().prop('disabled', false).attr('data-bs-target', '#voidrequestmodal');
                        $("#viewEditForm #modalcancelbtn").show().prop('disabled', false).attr('data-bs-target', '#cancelscheduledmodal');
                    }

                    if (d.void_request === 1) {
                        $('#editbtn').hide().attr('disabled', true);
                        $("#viewEditForm #requestvoidbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                        $("#viewEditForm #modalcancelbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                    }

                    // if (d.transaction_status !== 'Completed' || d.transaction_status !== 'Voided') {
                    //     $("#viewEditForm #requestvoidbtn").show().prop('disabled', false).attr('data-bs-target', '#voidrequestmodal');
                    //     $("#viewEditForm #modalcancelbtn").show().prop('disabled', false).attr('data-bs-target', '#cancelscheduledmodal');
                    // } else {
                    //     $("#viewEditForm #requestvoidbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                    //     $("#viewEditForm #modalcancelbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                    // }

                    if (d.transaction_status === 'Finalizing') {
                        $('#edit-chemBrandUsed input.form-control.form-add').attr('disabled', false);
                        $('#edit-chemBrandUsed input.form-control.form-add').attr('name', 'edit-amountUsed[]');
                    }

                    if (d.package_id != null) {
                        // package assigned
                        $('#edit-treatment').removeAttr('name');
                        $('#view-expiry').attr('name', function(i, a) {
                            return a ? a : 'edit-expiry'
                        });
                        $('#view-start').attr('name', function(i, a) {
                            return a ? a : 'edit-start'
                        });
                        $('#edit-session').attr('disabled', function(i, a) {
                            return a == true ? false : a;
                        });
                        $('#edit-treatment').attr('disabled', function(i, a) {
                            return a == true ? false : a;
                        });
                    } else {
                        // null | no package assigned
                        $('#edit-treatment').attr('name', function(i, a) {
                            return a ? a : 'edit-treatment'
                        });
                        $('#edit-session, #view-expiry, #view-start').removeAttr('name');
                        $('#edit-session, #view-expiry, #view-start').attr('disabled', true);
                    }

                    $('#edit-note').val(d.notes ?? '');
                    $('#view-note').html(d.notes ?? 'No existing note.');

                    // $(`#edit-status option[value=${d.transaction_status}]`).attr('selected', true);
                    $('#edit-status').val(d.transaction_status);

                    // if (d.transaction_status == 'Pending') {
                    //     editTransDate.config.minDate = new Date().fp_incr(1);
                    // } else {
                    //     editTransDate.config.minDate = null;
                    // }
                    $('#edit-addMoreChem').attr('data-status', d.transaction_status);

                    let tname = treatment_name(d.treatment);
                    $(`#edit-treatment option[value='${tname}']`).attr('selected', true);
                    $(`#edit-treatmentType option[value='${d.treatment_type}']`).attr('selected', true);
                    $('#view-time').html('Created at: ' + d.created_at + ' by ' + (d.created_by == 'No User' ? 'User not found.' : d.created_by) + '<br>Updated at: ' + d.updated_at + (d.updated_by == 'No User' ? '---' : " by " + d.updated_by));

                    const functions = await Promise.all([
                        await view_technician(d.id),
                        await view(d.treatment, 'treatment'),
                        await view(d.id, 'probCheckbox'),
                        await view(d.id, 'chemUsed'),
                        await edit('treatment-options', d.treatment), //treatment option
                        await edit('technicianName', d.id),
                        await edit('probCheckbox', d.id),
                        await edit('package', d.package_id ?? 0),
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
            }
        }

        $(document).on('focus', '#view-start', async function(e) {
            let package_id = $('#edit-package-select').val();
            if (!$.isNumeric(package_id)) {
                alert('Please Select a package! Invalid package ID.');
            } else {
                let date = $(this).val();
                let exp = await compute_package_expiry(date, package_id);
                $('#view-expiry').empty();
                $('#view-expiry').val(exp);
                // alert(exp);
            }
        })

        $(document).on('click', '#editbtn', async function() {
            let transId = $('#view-transId').val();
            if (toggled) {
                await toggle();
                toggled = false;
                $(this).html('Edit/Delete Transaction');
            } else {
                await toggle();
                $(this).html('Close Edit/Delete');
            }
            await view_transaction(transId);
        });

        $(document).on('click', '.finalize-peek-trans-btn', function() {
            $('#finalizetransactionmodal').modal('hide');
        })
        $(document).on('click', '.check-void-req-btn', function() {
            $('#requestedvoidtransactions').modal('hide');
        })

        // open details
        $(document).on('click', '#tableDetails, .finalize-peek-trans-btn, .check-void-req-btn', async function() {
            const clearform = await empty_form();
            if (clearform) {
                $('#viewEditForm')[0].reset();
                $(`#edit-chemBrandUsed`).empty();
                let transId = $(this).data('trans-id');
                if (toggled) {
                    await toggle();
                    toggled = false;
                    $('#editbtn').html('Edit/Delete Transaction');
                }
                await view_transaction(transId);
            } else {
                alert('Modal Error. Refresh Page.');
            }
        });

        function get_addrow(row, status = null) {
            $.get(transUrl, {
                addrow: 'true',
                status: status
            }, function(data) {
                $(`#edit-${row}`).append(data);
                console.log(status);
            })
        }

        // new
        $(document).on('change', '#add-status, #edit-status', function() {
            let sel = $(this);
            if (sel.val() === 'Voided') {
                sel.next().fadeIn(750).html("Note. Voiding a transaction completely will require Manager approval. Ignore to continue.");
            } else if (sel.val() === 'Accepted') {
                sel.next().fadeIn(750).html("Make sure to double check the details at least before the dispatch date.");
            } else if (sel.val() === 'Finalizing') {
                sel.next().fadeIn(750).html("Note. Transactions with this status will be subjected to completion, please make sure to double check the details.");
            } else if (sel.val() === 'Cancelled') {
                sel.next().fadeIn(750).html("Cancelled transactions are accepted and is needed to be rescheduled.");
            } else if (sel.val() === 'Completed') {
                sel.next().fadeIn(750).html("Make sure to double check details. Setting this transaction to complete will make this viewonly.");
            } else {
                sel.next().fadeOut(1000);
            }
        });

        // new
        $("#addModal, #details-modal").on('hidden.bs.modal', async function() {
            $("#add-status, #edit-status").next().hide();
        });

        async function check_emptyrow(row) {
            if ($(`#edit-${row}`).html().trim().length === 0) {
                console.log("empty div");
                get_addrow(row);
            } else {
                console.log('div not empty');
            }
        }

        $(document).on('click', '#edit-deleteTech', async function() {
            let rowId = $(this).data('row-id');
            let row = $('#edit-technicianName > div').length;
            if (row === 1) {
                alert('Transaction should have at least one technician.');
            } else {
                $(this).parent().remove();
                // console.log('tech row removed');
                await check_emptyrow('technicianName');
            }
        })

        $(document).on('click', '#deleteTech', async function() {
            let rowId = $(this).data('row-id');
            let row = $('#addTechContainer').length;
            if (row === 0) {
                alert('Transaction should have at least one technician.');
            } else {
                $(this).parent().parent().remove();
                // console.log('tech row removed');
                // await check_emptyrow('technicianName');
            }
        })

        $(document).on('change', '#edit-chemBrandUsed select.form-select', function() {
            // console.log($(this).val());
            let span = $(this).parent().next().find('span');
            $.get(transUrl, {
                    getunit: 'true',
                    chemid: $(this).val()
                })
                .done(function(d) {
                    // console.log(d);
                    span.html(d);
                })
                .fail(function(err) {
                    console.log(err);
                    span.html('-');
                });
        })

        $(document).on('click', 'button.ef-del-btn.btn.btn-grad', async function() {
            let rowId = $(this).data('row-id');
            let row = $('#edit-chemBrandUsed > div').length;
            if (row === 1) {
                alert('Transaction should have at least one or two chemical used');
            } else {
                if (rowId === '') {
                    let row = $(this).closest('div.row');
                    row.remove();
                    // console.log('row with no id removed.');
                } else {
                    // console.log(rowId);
                    $(`.row #row-${rowId}`).remove();
                }
                await check_emptyrow('chemBrandUsed');
            }
        })

        $(document).on('click', '#edit-addTech', async function() {
            // $.get(transUrl, { editTechAdd: 'true' }, function (data) {
            //     $('#edit-technicianName').append(data);
            // })
            await edit('technicianName');
        })

        $(document).on('click', '#edit-addMoreChem', async function() {
            let stats = $(this).data('status');
            get_addrow('chemBrandUsed', stats);
        })



        $(document).on('click', '#deleteChem', function() {
            $(this).parent().parent().remove();
        });

        async function empty_form() {
            $('#viewEditForm')[0].reset();
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


        // toggle name and disable when package is active
        let aps, at, a_s, ae;
        $(document).on('change', '#add-package', function() {
            let package = $(this).val();
            // console.log(package);
            if (package === 'none') {
                aps = $('#add-packageStart').val();
                a_s = $('#add-session').val();
                ae = $('#add-packageExpiry').val();
                $('#add-session').attr('disabled', true).val('');
                $('#add-treatment').attr('disabled', false).val(at);
                $('#add-packageStart').attr('disabled', true).val('');
                $('#add-packageExpiry').attr('disabled', true).val('');
            } else {
                at = $('#add-treatment').val();
                $('#add-session').attr('disabled', false).val(a_s);
                $('#add-treatment').attr('disabled', true).val('');
                $('#add-packageStart').attr('disabled', false).val(aps);
                $('#add-packageExpiry').attr('disabled', false).val(ae);
            }
        });

        $(document).on('focusout', 'form input, form select, form textarea', function() {
            if ($(this).val() == '' || $(this).val() == '#') {
                $(this).addClass('border border-danger');
            } else {
                $(this).removeClass('border border-danger');
            }
        });

        // submit
        $(function() {
            $('#addTransaction').on('submit', async function(e) {
                e.preventDefault();
                let status = $("#sortstatus").val();
                console.log($(this).serialize());
                try {
                    const trans = await $.ajax({
                        type: 'POST',
                        url: submitUrl,
                        data: $(this).serialize() + "&addSubmit=true",
                        dataType: 'json'
                    });

                    if (trans.success) {
                        console.log(trans.success);
                        console.log(trans.iterate);
                        await loadpage(1, status);
                        $('#confirmAdd').modal('hide');
                        $('#addTransaction')[0].reset();
                        $('#tableAlert').removeClass('visually-hidden').html(trans.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                    }
                    if (trans) {
                        console.log(trans);
                    }

                } catch (error) {
                    console.log(error);
                    // console.log(error);
                    let errorData = error.responseJSON;
                    if (errorData && errorData.errorMessage) {
                        // alert('Error Message: ' + errorData.errorMessage + ';\n\nLine: ' + errorData.line + ';\n\nFile: ' + errorData.file + ';\n\nTrace: ' + errorData.trace);
                        $('#add-alert').removeClass('visually-hidden').html(errorData.errorMessage).hide().fadeIn(400).delay(2000).fadeOut(1000);
                    } else {
                        // alert('unknown error occured.');
                    }
                }
            })
        });

        // edit section
        $(document).on('click', '#confirmUpdate', function() {
            $('#confirmation #verifyAdd').text('Verify Transaction Update');
            $('#confirmation #edit-confirm').text('Update Transaction');
            $('#confirmation #edit-confirm').attr('data-update', 'update');
            $('#editPwd').val('');
        })

        // edit section
        $(document).on('click', '#confirmDelete', function() {
            $('#confirmation #verifyAdd').text('Verify Transaction Deletion');
            $('#confirmation #edit-confirm').text('Delete Transaction');
            $('#confirmation #edit-confirm').attr('data-update', 'delete');
            $('#editPwd').val('');
        })

        // submit section | confirmation modal
        $(document).on('click', '#edit-confirm', async function() {
            let update = $(this).attr('data-update');
            // console.log(update);
            if (update === 'delete') {
                await delete_transaction();
            } else if (update === 'update') {
                let form = $('#viewEditForm').serialize();
                console.log(form);
                await update_transaction();
            } else {
                alert('Unknown error. Please refresh the page.');
            }
        })

        async function update_transaction() {
            let status = $("#sortstatus").val();
            try {
                const update = await $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: submitUrl,
                    data: $('#viewEditForm').serialize() + '&update=true'
                });

                if (update) {
                    await loadpage(1, status);
                    $('#confirmation').modal('hide');
                    $('#viewEditForm')[0].reset();
                    $('#tableAlert').removeClass('visually-hidden').html(update.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                }
            } catch (error) {
                console.log(error);
                alert(error.responseText);
                let err = error.responseText;
                $('#del-errormessage').removeClass('visually-hidden').html(err).hide().fadeIn(400).delay(2000).fadeOut(1000);
                $('input#editPwd').addClass('border border-danger-subtle').fadeIn(400);
            }
        }

        async function delete_transaction() {
            let status = $("#sortstatus").val();
            let row = $('#view-transId').val();
            let pwd = $('#editPwd').val();

            try {
                const del = await $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: submitUrl,
                    data: {
                        delete: 'true',
                        id: row,
                        saPwd: pwd
                    }
                });

                if (del) {
                    await loadpage(1, status);
                    $('#confirmation').modal('hide');
                    $('#viewEditForm')[0].reset();
                    $('#tableAlert').removeClass('visually-hidden').html(del.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                }
            } catch (error) {
                console.log(error.responseText);
                console.log(error);
                let errdata = jQuery.parseJSON(error.responseText);

                console.log('fail   ' + errdata.error);
                if (errdata && errdata.error) {
                    $('#editPwd').addClass('border border-danger').fadeIn(400);
                    $('#del-errormessage').removeClass('visually-hidden').html(errdata.error).hide().fadeIn(400);
                } else {
                    alert('unknown error.');
                }
            }
        }

        // search function
        $(function() {
            let delay = null;

            $('#searchbar').keyup(function() {
                clearTimeout(delay);
                $('#table').empty();
                $('#loader').removeClass('visually-hidden');

                delay = setTimeout(async function() {
                    var search = $('#searchbar').val();
                    let status = $('#sortstatus').val();
                    try {
                        const searchtransaction = await $.ajax({
                            url: 'contents/trans.pagination.php',
                            type: 'GET',
                            dataType: 'html',
                            data: {
                                search: search,
                                status: status
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
                                await loadpage(1, status);
                            }
                        }
                    } catch (error) {
                        console.log(error);
                    }
                }, 250);
            });

        });

        // load pagination buttons
        async function load_pagination_buttons(page = 1, status) {
            try {
                const pagination = await $.ajax({
                    type: 'GET',
                    url: 'contents/trans.pagination.php',
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
                }
            } catch (error) {
                console.log(error);
            }
        }

        // paginated table
        async function load_paginated_table(page = 1, status) {
            try {
                const table = await $.ajax({
                    type: 'GET',
                    url: 'contents/trans.pagination.php',
                    data: {
                        table: 'true',
                        currentpage: page,
                        status: status
                    },
                    dataType: 'html'
                });

                if (table) {
                    $('#table').empty();
                    $('#table').append(table);
                }
            } catch (error) {
                console.log(error);
            }
        }

        $(document).ready(function() {
            loadpage(1);
        })


        $("#sortstatus").on('change', async function() {
            let status = $("#sortstatus option:selected").val();
            $("#searchbar").val('');
            await loadpage(1, status);
        })

        async function loadpage(page = 1, status = '') {
            await load_pagination_buttons(page, status);
            await load_paginated_table(page, status);
            get_counts();
        }

        $('#pagination').on('click', '.page-link', async function(e) {
            e.preventDefault();
            let status = $("#sortstatus option:selected").val();

            let currentpage = $(this).data('page');
            console.log(currentpage);
            window.history.pushState(null, "", "?page=" + currentpage);
            await loadpage(currentpage, status);
        });





        // $(document).ready(function () {
        //     // Prevent right-click
        //     $(document).on("contextmenu", function (event) {
        //         event.preventDefault();
        //     });

        //     // Prevent F12 and Ctrl+Shift+I (Inspect Element)
        //     $(document).on("keydown", function (event) {
        //         if (event.key === "F12" || (event.ctrlKey && event.shiftKey && event.key === "I")) {
        //             event.preventDefault();
        //         }
        //     });
        // });
    </script>

</body>

</html>