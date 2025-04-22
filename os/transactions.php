<?php require("startsession.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operations Supervisor | Transactions</title>
    <?php include('header.links.php'); ?>
    <style>
        #sortstatus option {
            /* background: rgb(17 71 84) !important; */
            color: black;
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

            <!-- content start -->
            <div class="bg-light bg-opacity-25 pt-2 rounded p-3 mx-3 mt-3">
                <h1 class="display-6 text-light mb-0">Manage Transactions</h1>
            </div>

            <div class="hstack gap-2 mt-2 mx-4">
                <input class="form-control form-custom me-auto p-2 text-light" type="search"
                    placeholder="Search transactions . . ." id="searchbar" name="searchTrans"
                    autocomplete="one-time-code">
                <div class="vr"></div>
                <select class="form-select select-transparent bg-transparent border border-light text-light w-25"
                    id="sortstatus" aria-label="Default select example">
                    <option value='' selected>Show All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Completed">Completed</option>
                    <option value="Voided">Voided</option>
                </select>
                <button type="button" id="addbtn" class="btn btn-sidebar text-light py-3 px-4"
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
                <div class="row g-2 text-dark">
                    <div class="modal modal-lg fade text-dark modal-edit" id="addModal" tabindex="-1"
                        aria-labelledby="create" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Add New Transaction</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <p class="fs-6 fw-light">Provide the details of the transaction below.</p>
                                    <!-- <h5 class="fw-light">Primary Information</h5> -->
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
                                            <label for="add-technicianName" class="form-label fw-light">Technicians
                                            </label>
                                            <select id="add-technicianName" name="add-technicianName[]" not-size="2"
                                                class="form-select" aria-label="Default select example">
                                            </select>
                                        </div>
                                        <div class="col-lg-2 mb-2 d-flex gap-1 p-0 justify-content-start">
                                            <button type="button" id="addMoreTech"
                                                class="btn btn-grad mt-auto py-2 px-3"><i
                                                    class="bi bi-plus-circle text-light"></i></button>
                                            <button type="button" id="deleteTech"
                                                class="btn btn-grad mt-auto py-2 px-3"><i
                                                    class="bi bi-dash-circle text-light"></i></button>
                                        </div>
                                    </div>

                                    <div id="addTechContainer" class="p-0 m-0 mb-2"></div>

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
                                        <div class="col-lg-4 mb-2 ps-0">
                                            <label for="add-amountUsed" class="form-label fw-light">Amount
                                                Used</label>
                                            <div class="d-flex flex-row">
                                                <input type="number" name="add_amountUsed[]" maxlength="4"
                                                    id="add-amountUsed" class="form-control form-add me-3"
                                                    autocomplete="off">
                                                <span id="passwordHelpInline" class="form-text align-self-center">
                                                    /ml
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-2 mb-2 d-flex gap-1 p-0 justify-content-start">
                                            <button type="button" id="addMoreChem"
                                                class="btn btn-grad mt-auto py-2 px-3"><i
                                                    class="bi bi-plus-circle text-light"></i></button>
                                            <button type="button" id="deleteChemRow"
                                                class="btn btn-grad mt-auto py-2 px-3"><i
                                                    class="bi bi-dash-circle text-light"></i></button>
                                        </div>
                                    </div>

                                    <div class="p-0 m-0 mb-2" id="add-chemContainer">
                                        <!-- template add chemical -->
                                    </div>

                                    <div class="row mb-2 mx-auto">
                                        <label for="add-status" class="form-label fw-light">Status</label>
                                        <select name="add-status" id="add-status" class="form-select">
                                            <option value="#" selected>Select Status</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Accepted">Accepted</option>
                                            <option value="Voided">Void</option>
                                            <option value="Completed">Completed </option>
                                        </select>
                                    </div>

                                    <!-- <div class="row mb-2">
                                        
                                    </div> -->
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
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control" id="addPwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-3 w-75 mx-auto my-0 visually-hidden'
                                    id="add-alert"></p>
                                <!-- <div id="passwordHelpBlock" class="form-text">
                                Note: deletion of chemicals are irreversible.
                            </div> -->
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
                <div class="row g-2 text-dark">
                    <div class="modal modal-lg fade text-dark modal-edit" id="details-modal" tabindex="-1"
                        aria-labelledby="create" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- modal header -->
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Transaction Information</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>

                                <!-- modal body -->
                                <div class="modal-body pb-1">

                                    <p class="fw-light text-muted">Transaction details presented below.</p>

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

                                    <!-- edit technician choices/select, toggle visually-hidden when edit is on -->
                                    <label for='edit-technicianName' id="edit-technicianName-label"
                                        class="form-label fw-light visually-hidden">Edit Technician/s:</label>

                                    <!-- container -- ajax -- append -->
                                    <div class="d-flex flex-column m-0 p-0 visually-hidden" id="edit-technicianName">
                                    </div>

                                    <!-- add button -->
                                    <div class="w-50 d-flex visually-hidden" id="techaddbtn">
                                        <button type="button" id="edit-addTech"
                                            class="btn btn-grad mt-auto py-2 px-3 d-flex align-items-center">
                                            <p class="fw-light m-0 me-3">Add Technician</p><i
                                                class="bi bi-plus-circle text-light"></i>
                                        </button>
                                    </div>


                                    <!-- row 2 -->
                                    <div class="row mb-2">
                                        <!-- left side -->
                                        <div class="dropdown-center col-lg-6 mb-2">

                                            <!-- list technician when in view mode. Handled in ajax -->
                                            <label for="view-technicians" id="view-technicians-label"
                                                class="form-label fw-light">Technician/s:
                                            </label>
                                            <ul class="list-group list-group-flush" id="view-technicians"></ul>

                                            <!-- <select id="edit-technicianName" name="edit-technicianName[]"
                                                class="form-select visually-hidden" aria-label="Default select example">
                                                <option value="#" selected>Select Technician</option>
                                            </select> -->
                                        </div>
                                    </div>

                                    <!-- add more technician container (when edit mode) -- this is a row itself -->
                                    <!-- <div id="edit-techContainer" class="p-0 m-0 mb-2 visually-hidden"></div> -->

                                    <hr class="my-2">

                                    <!-- row 3  -->
                                    <div class="row mb-2">
                                        <!-- treatment -->
                                        <div class="col-lg-6 mb-2">
                                            <!-- left side -- treatments in view -->
                                            <label for="view-treatment" class="form-label fw-light"
                                                id="view-treatment-label">Treatment:</label>
                                            <ul class="list-group list-group-flush" id="view-treatment"></ul>

                                            <!-- left side -- treatments in edit -->
                                            <label for="edit-treatment" class="form-label fw-light visually-hidden"
                                                id="edit-treatment-label">Treatment</label>
                                            <select name="edit-treatment" id="edit-treatment"
                                                class="form-select visually-hidden">
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

                                        <!-- right side -->
                                        <div class="col-lg-6 mb-2">
                                            <!-- remove readonly in edit mode -->
                                            <label for="view-treatmentDate" class="form-label fw-light">Treatment
                                                Date:</label>
                                            <input type="date" name="edit-treatmentDate" id="view-treatmentDate"
                                                min="2025-01-01" class="form-control form-add"
                                                style="border: none !important;" readonly>
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
                                            <ul class="list-group list-group-flush" id="view-status">
                                                <li class="list-group-item" id="list-status"></li>
                                            </ul>
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

                                    <div class="p-0 m-0 mb-2" id="edit-chemContainer">
                                        <!-- template add chemical -->
                                    </div>

                                    <div class="row mb-2 mx-auto visually-hidden" id="edit-status-col">
                                        <!-- edit -->
                                        <label for="edit-status" class="form-label mb-0 fw-light p-0 "
                                            id='label-edit-status'>Transaction Status:</label>
                                        <select name="edit-status" id="edit-status" class="form-select ">
                                            <option value="#" selected>Select Status</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Accepted">Accepted</option>
                                            <option value="Voided">Void</option>
                                            <option value="Completed">Completed </option>
                                        </select>
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
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
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

            <!-- modals end -->

            <div class="d-flex justify-content-center mb-5 visually-hidden" id="loader">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div id="pagination"></div>
            <p class='text-center alert alert-success w-50 mx-auto visually-hidden' id="tableAlert"></p>
            <!-- content end -->
        </main>
    </div>

    <!-- approve modal -->
    <form id="approvependingtransactions">
        <div class="modal fade text-dark modal-edit" id="approvemodal" tabindex="0" aria-labelledby="confirmDelete"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-modal-title text-light">
                        <h1 class="modal-title fs-5">Technician's Transaction Approval</h1>
                        <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                class="bi bi-x text-light"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <label for="addPwd" class="form-label fw-light">Approve Pending Transaction? Enter
                                <?= $_SESSION['fname'] . ' ' . $_SESSION['lname'] . '\'s password to proceed.' ?></label>
                            <div class="col-lg-6 mb-2">
                                <input type="password" name="approve-pwd" class="form-control">
                            </div>
                            <input type="hidden" id="chemidinput" name="chemid">
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


    <script>
        const transUrl = 'contents/trans.data.php';
        const submitUrl = 'contents/transconfig.php';

        <?php
        if (isset($_GET['openmodal']) && $_GET['openmodal'] === 'true') {
            ?>
            $('#viewEditForm')[0].reset();
            let id = <?= $_GET['id']; ?>;
            console.log(id);
            view_transaction(id);
            $('#details-modal').on('hidden.bs.modal', function (e) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('openmodal');
                currentUrl.searchParams.delete('id');
                window.history.pushState(null, "", currentUrl.pathname + currentUrl.search);
            });
            <?php
        }
        ?>

        $(document).on('click', '#pendingbtn', function () {
            let chemId = $(this).data('pending-id');
            console.log(chemId);
            $('#chemidinput').val(chemId);
            $('#approvemodal').modal('show');
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


        $('#approvependingtransactions').on('submit', async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            let status = $('#sortstatus option:selected').val();
            try {
                const approve = await $.ajax({
                    method: 'POST',
                    dataType: 'json',
                    url: submitUrl,
                    data: $(this).serialize() + '&approve=true'
                });

                if (approve) {
                    console.log(approve);
                    $('#approvemodal').modal('hide');
                    loadpage(1, status);
                    $("#tableAlert").removeClass('visually-hidden').html(approve.success).hide().fadeIn(400).delay(2000).fadeOut(1000);

                }

            } catch (error) {
                console.log(error.responseJSON);
                // let err = error.responseText;
                let err = error.responseJSON;

                switch (err.type) {
                    case 'wrongpwd':
                        $("#approve-alert").removeClass('visually-hidden').html(err.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    case 'function':
                        $("#approve-alert").removeClass('visually-hidden').html(err.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    default:
                        console.log(err.type);
                        break;
                }

            }
        })

        $(document).on('click', '#addbtn', async function () {
            let form = 'add';
            try {
                const load = await Promise.all([
                    get_chemical_brand(form),
                    get_technician(form),
                    get_problems(form),
                    add_more_chem(),
                    add_more_tech()
                ]);
                if (load) {
                    $('#addTransaction')[0].reset();
                    $('#addTechContainer').empty();
                    $('add-chemContainer').empty();
                    $('#addModal').modal('show');
                }
            } catch (error) {
                console.log('add get error.')
            }
        })

        // add / delete more technicians
        async function add_more_tech() {
            let num = 2;

            $('#addMoreTech', '#addModal').off('click').on('click', async function () {
                // console.log('tite' + num);
                await get_more_tech(num);
                num++;
                console.log('tech add number: ' + num);
            });
            $('#deleteTech').off('click').on('click', function () {
                if (num > 2) {
                    num--;
                    let row = `#row-${num}`;
                    console.log('row number: ' + row);
                    $(row).remove();
                } else {
                    console.log('no more rows to delete.');
                }
            })
        }

        // add / delete chem main function
        async function add_more_chem() {
            // let moreChemTemp = $('#add-chemicalData').html();
            let num = 2;

            $('#addMoreChem', '#addModal').off('click').on('click', async function () {
                await add_used_chem(num);
                num++;
                console.log(num);

            });
            $('#deleteChemRow').off('click').on('click', function () {
                // console.log(num);
                if (num > 2) {
                    num--;
                    let row = `#row-${num}`;
                    console.log(row);
                    $(row).remove();

                } else {
                    console.log('no more rows to delete.');
                }
            })
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

        async function get_chemical_brand(method, transId = null) {
            try {
                const brand = await $.ajax({
                    type: 'GET',
                    url: transUrl,
                    data: {
                        getChem: method,
                        transId: transId
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

        $(document).on('click', '#tableDetails', async function () {
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
                    let data = details.success;
                    // console.log('id ' + data.id);
                    $('#view-transId').val(data.id);
                    $('#view-customerName').val(data.customer_name);
                    // let pprob = get_problems('view');
                    // $('#view-probCheckbox').val(pprob);
                    $('#view-treatmentDate').val(data.treatment_date);
                    $('#list-status').empty();
                    $('#view-status').html(data.transaction_status);
                    $(`#edit-status option[value=${data.transaction_status}]`).attr('selected', true);
                    $(`#edit-treatment option[value='${data.treatment}']`).attr('selected', true);
                    // console.log(data.transaction_status);
                    $('#view-time').html('Created at: ' + data.created_at + '<br>Updated at: ' + data.updated_at);
                    const functions = await Promise.all([
                        await view_technician(data.id),
                        await view(data.id, 'treatment'),
                        await view(data.id, 'probCheckbox'),
                        await view(data.id, 'chemUsed'),
                        await edit('technicianName', data.id),
                        await edit('probCheckbox', data.id),
                        await get_chemical_brand('edit', data.id)
                    ]);
                    if (functions) {
                        $('#details-modal').modal('show');
                    } else {
                        alert('Details Modal Error. Refresh Page.');
                    }

                }
            } catch (error) {
                alert(JSON.stringify(error.responseJSON));
            }
        }

        function get_addrow(row) {
            $.get(transUrl, {
                addrow: 'true'
            }, function (data) {
                $(`#edit-${row}`).append(data);
                console.log(status);
            })
        }

        async function check_emptyrow(row) {
            if ($(`#edit-${row}`).html().trim().length === 0) {
                console.log("empty div");
                get_addrow(row);
            } else {
                console.log('div not empty');
            }
        }

        $(document).on('click', '#edit-deleteTech', async function () {
            let rowId = $(this).data('row-id');
            let row = $('#edit-technicianName > div').length;
            // console.log(row);
            if (row === 1) {
                alert('Transaction should have at least one technician.');
            } else {
                $(this).parent().remove();
                console.log('tech row removed');
                await check_emptyrow('technicianName');
            }
        })

        $(document).on('click', '#edit-deleteChemRow', async function () {
            let rowId = $(this).data('row-id');
            let row = $('#edit-chemBrandUsed > div').length;
            if (row === 1) {
                alert('Transaction should have at least one or two chemical used');
            } else {
                if (rowId === '') {
                    let row = $(this).closest('div.row');
                    row.remove();
                    console.log('row with no id removed.');
                } else {
                    console.log(rowId);
                    $(`.row #row-${rowId}`).remove();
                }
                await check_emptyrow('chemBrandUsed');
            }
        })

        $(document).on('click', '#edit-addTech', async function () {
            // $.get(transUrl, { editTechAdd: 'true' }, function (data) {
            //     $('#edit-technicianName').append(data);
            // })
            await edit('technicianName');
        })

        $(document).on('click', '#edit-addMoreChem', async function () {
            get_addrow('chemBrandUsed');
        })

        $(document).on('click', '#editbtn', async function () {
            if (toggled) {
                await toggle();
                toggled = false;
                $(this).html('Edit/Delete Transaction');
            } else {
                await toggle();
                $(this).html('Close Edit/Delete');
            }
        });

        let toggled = false;

        async function toggle() {
            $("#view-customerName").attr("readonly", function (i, attr) {
                if (attr) {
                    $(this).removeClass('form-control-plaintext');
                    $(this).addClass('form-control');
                } else {
                    $(this).removeClass('form-control');
                    $(this).addClass('form-control-plaintext');
                }
                return attr ? null : "readonly";
            });
            $("#view-treatmentDate").attr("readonly", function (i, attr) {
                $(this).removeAttr('style');
                if (attr) {
                    $(this).removeAttr('style');
                } else {
                    $(this).attr('style', 'border: none !important');
                }
                return attr ? null : "readonly";
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
            $('#edit-status-col').toggleClass('visually-hidden');
            $('#editbtns').toggleClass('visually-hidden');
            return toggled = true;
        }


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
                    if (transId !== null) {
                        $(`#edit-${name}`).empty();
                        $(`#edit-${name}`).append(target);
                    } else {
                        $(`#edit-${name}`).append(target);
                    }
                } else {
                    alert('no edit returned');
                }
            } catch (error) {
                alert('Error at edit function: ' + edit + '\nerror: ' + error);
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

        // submit
        $(function () {
            $('#addTransaction').on('submit', async function (e) {
                e.preventDefault();
                // console.log($(this).serialize());
                try {
                    const trans = await $.ajax({
                        type: 'POST',
                        url: submitUrl,
                        data: $(this).serialize() + "&addSubmit=true",
                        dataType: 'json'
                    });

                    if (trans.success) {
                        // alert('success');
                        console.log(trans.success);
                        console.log(trans.iterate);
                        await load_paginated_table();
                        $('#confirmAdd').modal('hide');
                        $('#addTransaction')[0].reset();
                    }

                } catch (error) {
                    console.log(JSON.stringify(error));
                    // console.log(error);
                    let errorData = error.responseJSON;
                    if (errorData && errorData.errorMessage) {
                        // alert('Error Message: ' + errorData.errorMessage + ';\n\nLine: ' + errorData.line + ';\n\nFile: ' + errorData.file + ';\n\nTrace: ' + errorData.trace);
                        $('#add-alert').removeClass('visually-hidden').html(errorData.errorMessage).hide().fadeIn(400).delay(2000).fadeOut(1000);
                    } else {
                        alert('unknown error occured.');
                    }
                }
            })
        });

        // edit section
        $(document).on('click', '#confirmUpdate', function () {
            $('#confirmation #verifyAdd').text('Verify Transaction Update');
            $('#confirmation #edit-confirm').text('Update Transaction');
            $('#confirmation #edit-confirm').attr('data-update', 'update');
            $('#editPwd').val('');
        })

        // edit section
        $(document).on('click', '#confirmDelete', function () {
            $('#confirmation #verifyAdd').text('Verify Transaction Deletion');
            $('#confirmation #edit-confirm').text('Delete Transaction');
            $('#confirmation #edit-confirm').attr('data-update', 'delete');
            $('#editPwd').val('');
        })

        // submit section | confirmation modal
        $(document).on('click', '#edit-confirm', async function () {
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
            try {
                const update = await $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: submitUrl,
                    data: $('#viewEditForm').serialize() + '&update=true'
                });

                if (update) {
                    console.log(update.success);
                    // console.log(update.data);
                    console.log('final tech: ' + update.ftech);
                    console.log('final chems: ' + update.fchems);
                    console.log('final problems: ' + update.fprob);
                    console.log(update.techs);
                    console.log(update.diffs);
                    await load_paginated_table();
                    $('#confirmation').modal('hide');
                    $('#viewEditForm')[0].reset();
                    $('#tableAlert').removeClass('visually-hidden').html(update.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                }
            } catch (error) {
                let err = jQuery.parseJSON(error.responseText);
                console.log('ERROR: ' + error.responseText);
                $('#del-errormessage').removeClass('visually-hidden').html(err.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                $('input#editPwd').addClass('border border-danger-subtle').fadeIn(400);
            }
        }

        async function delete_transaction() {

            let row = $('#view-transId').val();
            let pwd = $('#editPwd').val();
            // console.log(saId);
            // console.log(row);
            // $.post(submitUrl, { delete: 'true', id: row }, function (data, status) {
            //     console.log(data + ' ' + status);
            // })

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
                    // let res = JSON.parse(res.responseText);
                    // console.log(del.success + ' SUCCESS' + JSON.stringify(del));
                    await load_paginated_table();
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
                    // alert(errdata.error);
                    $('#editPwd').addClass('border border-danger').fadeIn(400);
                    $('#del-errormessage').removeClass('visually-hidden').html(errdata.error).hide().fadeIn(400);
                } else {
                    alert('unknown error.');
                }
                // console.log(errdata.error);
            }
        }



        // search function
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
                            url: 'contents/trans.pagination.php',
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
                                // await load_paginated_table();
                                await loadpage(1, sort);
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
                    // window.history.pushState(null, "", "?page=" + page);
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

        $(document).ready(async function () {
            await loadpage();
        })

        async function loadpage(page = 1, status = '') {
            await load_pagination_buttons(page, status);
            await load_paginated_table(page, status);
        }

        $("#sortstatus").on('change', async function () {
            let status = $("#sortstatus option:selected").val();
            $("#searchbar").val('');
            if (status != '') {
                await loadpage(1, status);
            } else {
                await loadpage();
            }
        })

        $('#pagination').on('click', '.page-link', async function (e) {
            e.preventDefault();
            let status = $("#sortstatus option:selected").val();

            let currentpage = $(this).data('page');
            console.log(currentpage);

            // $('#chemicalTable').empty();
            window.history.pushState(null, "", "?page=" + currentpage);
            // await load_paginated_table(currentpage);

            // $('#pagination').empty();
            // await loadpagination(currentpage);
            await loadpage(currentpage, status);
        })


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

    <?php include('footer.links.php'); ?>
</body>

</html>