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
        <main class="sa-content col-sm-10 p-0 container-fluid" id="transaction_content">
            <!-- navbar -->
            <?php include('navbar.php'); ?>

            <!-- content start -->
            <div class="bg-light bg-opacity-25 pt-2 rounded p-3 mx-3 mt-3 mb-2 ">
                <h1 class="display-6 text-light mb-0 fw-medium text-center">Manage Transactions</h1>
            </div>
            <div class="d-flex gap-2 my-2 mx-3 user-select-none text-center information-summary">
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
            <div class="d-flex gap-2 mb-2 mx-3 user-select-none text-center information-summary">
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
                    <p class=" mb-0 ">Transactions marked done after dispatch.</p>
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

            <div class="hstack align-items-stretch gap-2 mt-2 mx-3">
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
                    class="btn w-50 rounded btn-sidebar bg-light bg-opacity-25 border-0 text-light py-2 px-1 d-flex justify-content-center gap-2 flex-wrap"><i
                        class="bi bi-calendar2-check"></i>Finalizing Transactions</button>
                <button id="ir_btn"
                    class="btn btn-sidebar position-relative text-light py-2 w-50 px-2 bg-light bg-opacity-25 d-flex justify-content-center gap-2 flex-wrap"
                    data-bs-target="#inspection_report_modal" data-bs-toggle="modal"><i
                        class="bi bi-file-earmark-text"></i>
                    <p class="mb-0">
                        Inspection Reports
                    </p>
                </button>
                <button type="button" id="requestvoidbtn" data-bs-target="#requestedvoidtransactions"
                    data-bs-toggle="modal"
                    class="btn w-50 rounded btn-sidebar bg-light bg-opacity-25 border-0 text-light py-2 px-1  d-flex justify-content-center gap-2 flex-wrap"><i
                        class="bi bi-file-earmark-x"></i>Requested Void Transactions</button>
                <div class="vr"></div>
                <button type="button" id="addbtn" class="btn btn-sidebar bg-light bg-opacity-25 text-light py-2 px-3"
                    data-bs-toggle="modal" data-bs-target="#add_ir" title="Add Transaction"><i
                        class="bi bi-file-earmark-plus"></i></button>
                <button type="button" title="Add Inspection Report"
                    class="btn btn-sidebar bg-light bg-opacity-25 text-light py-2 px-3" id="add_inspection"
                    data-bs-target="#inspection_select_modal" data-bs-toggle="modal">
                    <i class="bi bi-clipboard-plus"></i>
                </button>
            </div>

            <div class="table-responsive-sm d-flex justify-content-center">
                <table class="table align-middle table-hover m-3 mt-2 os-table w-100 text-light">
                    <caption class="text-light text-muted">List of all transactions.</caption>
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

            <!-- inspection report details -->
            <form id="ir_edit_form">

                <div class="modal fade text-dark" data-bs-backdrop="static" id="ir_details_modal" tabindex="-1"
                    aria-labelledby="create" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5 fw-bold">
                                    Inspection Reports <span class="fw-light text-light">|</span> Details
                                </h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                        class="bi text-light bi-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="ir_id" id="ir_details_id">
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <p class="form-label fw-bold fs-5">Inspection Report ID:</p>
                                        <p class="ps-2 m-0" id="ir_inspection_id"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ir_customer" class="form-label fw-bold fs-5">Customer Name:</label>
                                        <input type="text" name="customer_name" id="ir_customer"
                                            class="form-control-plaintext ir-input ps-2 name-input" autocomplete="off"
                                            readonly>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="ir_property_type" class="form-label fw-bold fs-5">Property
                                            Type:</label>
                                        <p class="mb-0 ps-2 text-capitalize" id="ir_property_type_display"></p>
                                        <select name="property_type" id="ir_property_type" class="form-select d-none"
                                            autocomplete="off" disabled>
                                            <option value="residential">Residential Property</option>
                                            <option value="commercial">Commercial Property</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label for="ir_floor_area" class="form-label fw-bold fs-5">Total floor
                                            area:</label>
                                        <div class="d-flex align-items-center">
                                            <input type="number" step="0.01" min="0.00" name="total_floor_area"
                                                id="ir_floor_area"
                                                class="form-control-plaintext ir-input ps-2 w-25 text-center"
                                                autocomplete="off" readonly>
                                            <p class="mb-0 ms-2" id="ir_floor_area_unit"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ir_total_floors" class="form-label fw-bold fs-5">Total number of
                                            floors:</label>
                                        <div class="d-flex align-items-center">
                                            <input type="number" name="total_floors" min="0" id="ir_total_floors"
                                                class="form-control-plaintext ir-input ps-2 w-15 text-center"
                                                autocomplete="off" readonly>
                                            <p class="mb-0 ms-2">floor/s</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ir_total_rooms" class="form-label fw-bold fs-5">Total property
                                            rooms:</label>
                                        <div class="d-flex align-items-center">
                                            <input type="number" name="total_rooms" min="0" id="ir_total_rooms"
                                                class="form-control-plaintext ps-2 w-15 text-center ir-input"
                                                autocomplete="off" readonly>
                                            <p class="mb-0 ms-2">room/s</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="ir_location" class="form-label fw-bold fs-5">Location:</label>
                                        <input type="text" name="location" id="ir_location"
                                            class="form-control-plaintext ir-input ps-2" autocomplete="off" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ir_exposed_soil" class="form-label fw-bold fs-5">Exposed soil
                                            outside property (For termite problems):</label>
                                        <p class="ps-2 mb-0 text-capitalize" id="ir_exposed_soil_display"></p>
                                        <select name="exposed_soil" id="ir_exposed_soil" class="form-select d-none">
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                            <option value="no_termite">No termite</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md">
                                        <label for="ir_pproblems_list" class="form-label fw-bold fs-5">Reported pest
                                            problems:</label>
                                        <ul class="list-group list-group-flush display-toggle w-50"
                                            id="ir_pproblems_list">
                                        </ul>
                                        <div id="ir_pest_problem_container"
                                            class="ps-2 d-none display-toggle d-flex justify-content-center gap-2 flex-wrap">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="ir_location_seen" class="form-label fw-bold fs-5">Infestation
                                            location
                                            (First seen):</label>
                                        <input type="text" name="location_seen" id="ir_location_seen"
                                            class="form-control-plaintext ir-input ps-2" autocomplete="off" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- <label for="ir_existing_pc" class="form-label fw-bold fs-5">Existing pest
                                            control
                                            provider:</label> -->
                                        <p class="fw-bold fs-5 mb-2">Existing pest control provider:</p>
                                        <!-- <input type="text" name="existing_pc" id="ir_existing_pc"
                                            class="form-control-plaintext ir-input ps-2" autocomplete="off" readonly> -->
                                        <p id="ir_existing_pc" class="ps-2"></p>
                                        <div class="btn-group mb-2 d-none" id="existing_btn_group">
                                            <input type="radio" class="btn-check" name="existing_pc" value="yes"
                                                id="ir_yes" autocomplete="off">
                                            <label for="ir_yes" class="btn btn-outline-dark fw-medium">Yes</label>

                                            <input type="radio" class="btn-check" name="existing_pc" value="no"
                                                id="ir_no" autocomplete="off">
                                            <label for="ir_no" class="btn btn-outline-dark fw-medium">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2" id="ir_existing_pc_details">
                                    <div class="col-md-6">
                                        <label for="ir_latest_treatment" class="form-label fw-bold fs-5">Latest
                                            treatment
                                            type:</label>
                                        <input type="text" name="latest_treatment" id="ir_latest_treatment"
                                            class="form-control-plaintext ir-input ps-2" autocomplete="off" readonly>
                                        <p class="ir-input ps-2 fw-light mb-0" id="ir_treatment_history_display"></p>
                                        <div class="form-check form-check-inline d-flex flex-row align-items-center gap-2 d-none mt-1"
                                            id="no_trt_history_chkbx">
                                            <input class="form-check-input ps-2" name="no_treatment_history"
                                                type="checkbox" id="ir_no_treatment_history">
                                            <label class="form-check-label fw-light fs-5"
                                                for="ir_no_treatment_history">No
                                                treatment history</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ir_last_treatment" class="form-label fw-bold fs-5">Last treatment
                                            date:</label>
                                        <p id="ir_last_treatment_display" class="ps-2 mb-0"></p>
                                        <input type="text" name="last_treatment_date" id="ir_last_treatment"
                                            class="form-control ps-2 d-none" autocomplete="off" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="ir_note" class="form-label fw-bold fs-5">Additional notes:</label>
                                        <textarea type="text" name="note" id="ir_note"
                                            class="form-control-plaintext ir-input ps-2" autocomplete="off" readonly
                                            style="resize: none;"></textarea>
                                    </div>
                                </div>
                                <button type="button" class="w-75 mx-auto my-2 btn btn-grad ir-confirm-btn d-none"
                                    data-bs-toggle="modal" data-bs-target="#ir_edit_confirm">Confirm
                                    changes</button>
                                <small class="m-0 text-muted fw-light" id="ir_metadata"></small>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" id="ir_back_btn"
                                    data-bs-target="#inspection_report_modal" data-bs-toggle="modal">Back</button>
                                <button type="button" class="btn btn-grad" id="ir_edit_toggle">Edit</button>
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="ir_edit_confirm" tabindex="0"
                    aria-labelledby="confirmAdd" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Confirm report changes</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="ir_modify_pwd" class="form-label fw-light">Modify report? Enter
                                        operations supervisor
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="password" class="form-control" id="ir_modify_pwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-3 w-75 mx-auto my-0' style="display: none;"
                                    id="ir_modify_alert"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#ir_details_modal"
                                    data-bs-toggle="modal">Go back</button>
                                <button type="submit" class="btn btn-grad">Modify report</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- inspection report table -->
            <div class="modal fade text-dark" data-bs-backdrop="static" id="inspection_report_modal" tabindex="-1"
                aria-labelledby="create" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5 fw-bold">
                                Inspection Reports
                            </h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                    class="bi text-light bi-x"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive-sm d-flex justify-content-center">
                                <table class="table align-middle table-hover m-3 mt-2 os-table w-100 text-light">
                                    <caption class="text-muted">List of all available inspection reports within the
                                        branch.</caption>
                                    <thead class="text-center  align-middle">
                                        <tr>
                                            <th scope="row">Inspection ID</th>
                                            <th>Customer Name</th>
                                            <th>Property Type</th>
                                            <th>Branch</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ir_table">
                                        <tr>
                                            <td colspan="7">
                                                <div class="d-flex justify-content-center">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="ir_pagination"></div>
                        </div>
                        <div class="modal-footer"> <button type="button" class="btn btn-grad"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <form id="new_inspection_report">
                <div class="modal fade text-dark" data-bs-backdrop="static" id="inspection_select_modal" tabindex="-1"
                    aria-labelledby="create" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5 fw-bold">
                                    Create Inspection Report
                                </h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                        class="bi text-light bi-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <div id="create_inspection_container">

                                    <p
                                        class="fs-4 bg-secondary bg-opacity-50 fw-bold rounded w-100 text-center text-light py-2">
                                        New Inspection Report</p>

                                    <div class="mb-2 w-50">
                                        <label for="ir_customer_name" class="form-label fs-5 fw-bold">Customer
                                            Name:</label>
                                        <input type="text" class="form-control name-input" name="customer_name"
                                            id="ir_customer_name" autocomplete="off">
                                    </div>

                                    <p class="fw-bold fs-5">Property type:</p>
                                    <div class="d-flex gap-2 justify-content-evenly align-content-center my-2">
                                        <input type="radio" class="btn-check" name="property_type" value="residential"
                                            id="residential_btn" autocomplete="off">
                                        <label for="residential_btn" class="btn fw-medium btn-outline-dark">Residential
                                            Property</label>
                                        <p class="text-secondary fw-light fs-5 mb-0">or</p>
                                        <input type="radio" class="btn-check" name="property_type" value="commercial"
                                            id="commercial_btn" autocomplete="off">
                                        <label for="commercial_btn" class="btn fw-medium btn-outline-dark">Commercial
                                            Property</label>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <label for="floor_area" class="form-label fs-5 fw-bold">Total floor
                                                area:</label>
                                            <input type="number" id="floor_area" class="form-control ps-2" step="0.01"
                                                min="0.00" name="total_area" autocomplete="off">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="floor_area_unit" class="form-label fs-5 fw-bold">Unit:</label>
                                            <input type="text" class="form-control-plaintext ps-2" id="floor_area_unit"
                                                value="sqm" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="total_floors" class="form-label fs-5 fw-bold">Total number of
                                                floors:</label>
                                            <input type="number" min="1" steps="1" name="total_floors"
                                                class="form-control" id="total_floors">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="total_rooms" class="form-label fs-5 fw-bold">Total property
                                                rooms:</label>
                                            <input type="number" min="1" steps="1" name="total_rooms"
                                                class="form-control" id="total_floors">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md">
                                            <label for="location" class="form-label fs-5 fw-bold">Property
                                                Location:</label>
                                            <textarea name="property_location" id="location" rows="2"
                                                class="form-control" autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <p class="fs-5 fw-bold">Reported pest problem:</p>
                                        <div class="col-md d-flex gap-2 justify-content-center flex-wrap"
                                            id="reported_pest_container"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md d-flex flex-column gap-1">
                                            <p class="fs-5 fw-bold m-0">For Termite Only:</p>
                                            <p class="fw-light ps-2">Is there an exposed soil outside the property?</p>
                                            <div class="btn-group mb-2">
                                                <input type="radio" class="btn-check" name="exposed_soil_ans"
                                                    value="yes" id="yes" autocomplete="off">
                                                <label for="yes" class="btn btn-outline-dark fw-medium">Yes</label>

                                                <input type="radio" class="btn-check" name="exposed_soil_ans" value="no"
                                                    id="no" autocomplete="off">
                                                <label for="no" class="btn btn-outline-dark fw-medium">No</label>
                                            </div>
                                            <div class="mx-auto">
                                                <input type="radio" class="btn-check" name="exposed_soil_ans"
                                                    value="no_termite" id="no_termite" autocomplete="off" checked>
                                                <label for="no_termite" class="btn btn-outline-dark fw-medium">No
                                                    Termite
                                                    Reported</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="mb-3">
                                        <label for="infestation_location" class="form-label fs-5 fw-bold">Infestation
                                            Location (First seen):</label>
                                        <input type="text" class="form-control ps-2" name="infestation_location"
                                            id="infestation_" autocomplete="off">
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md">
                                            <p class="fs-5 fw-bold mb-1">Existing Pest Control
                                                Provider:</p>
                                            <p class="ps-2 fw-light">Does this customer have an existing pest control
                                                provider?
                                            </p>
                                            <div class="btn-group mb-3 d-flex">
                                                <input type="radio" class="btn-check" name="existing_pc_provider"
                                                    value="yes" id="existing_pc__yes" autocomplete="off">
                                                <label for="existing_pc__yes"
                                                    class="btn btn-outline-dark fw-medium">Yes</label>
                                                <input type="radio" class="btn-check" name="existing_pc_provider"
                                                    value="no" id="existing_pc__no" autocomplete="off">
                                                <label for="existing_pc__no"
                                                    class="btn btn-outline-dark fw-medium">No</label>
                                            </div>
                                            <p class="mb-1 fs-5 fw-light d-none existing_label" id="existing_label">
                                                Please
                                                state the
                                                last/latest
                                                treatment type and date.</p>
                                            <div class="d-flex gap-2 mb-2 flex-wrap">
                                                <div class="w-100 mb-2">
                                                    <label for="existing_provider_last_treatment"
                                                        class=" form-label fw-bold fs-5">Latest
                                                        treatment type:</label>
                                                    <input type="text" name="existing_provider_last_treatment"
                                                        id="existing_provider_last_treatment"
                                                        class="existing-pc-form form-control ps-2" autocomplete="off"
                                                        disabled>
                                                    <div
                                                        class="form-check form-check-inline existing_label d-none d-flex flex-row align-items-center gap-2">
                                                        <input class="form-check-input" name="no_treatment_history"
                                                            type="checkbox" id="no_treatment_history" value="true">
                                                        <label class="form-check-label fw-light fs-5"
                                                            for="no_treatment_history">No
                                                            treatment history</label>
                                                    </div>
                                                </div>
                                                <div class="w-100 mb-2">
                                                    <label for="last_treatment_date"
                                                        class=" form-label fw-bold fs-5">Last
                                                        treatment
                                                        date:</label>
                                                    <input type="text" name="last_treatment_date"
                                                        class="existing-pc-form form-control" id="last_treatment_date"
                                                        disabled>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label for="note" class="form-label fs-5 fw-bold">Additional note:</label>
                                            <textarea type="text" class="form-control ps-2" name="note"
                                                id="note"></textarea>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-grad" data-bs-target="#ir_confirm"
                                    data-bs-toggle="modal">Create Inspection
                                    Report</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="ir_confirm" tabindex="0"
                    aria-labelledby="confirmAdd" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyAdd">Verification</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="ir_confirm_pwd" class="form-label fw-light">Add transaction? Enter
                                        operations supervisor
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="password" class="form-control" id="ir_confirm_pwd">
                                    </div>
                                </div>
                                <p class="alert alert-info py-2 w-75 my-3 mx-auto text-center fw-medium"
                                    id="new_ir_alert" style="display: none;">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#inspection_select_modal"
                                    data-bs-toggle="modal">Go back</button>
                                <button type="submit" class="btn btn-grad">Create Report</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="addTransaction">

                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="add_ir" tabindex="0"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Add Transaction</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <label for="select_ir_trans" class="form-label fw-bold fs-5">Select inspection
                                    report:</label>
                                <select name="inspection_report" id="select_ir_trans" class="form-select w-50"></select>
                                <p class="mb-1 text-muted ps-1">Inspection report is required in order to proceed.</p>
                                <button type="button" class="btn btn-grad w-50 mx-auto mt-3" id="add_create_new_ir"
                                    data-bs-target="#inspection_select_modal" data-bs-toggle="modal">Create new
                                    report</button>
                                <button type="button" class="btn btn-grad w-50 mx-auto d-none mt-3"
                                    id="ir_add_proceed_btn">Proceed</button>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 text-dark m-0">
                    <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="addModal" tabindex="-1"
                        aria-labelledby="create" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Add New Transaction</h1>
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
                                                class="form-control form-add name-input" placeholder="Enter name"
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
                                            <input placeholder="--/--/--" id="add-packageStart" name="add-packageStart"
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
                                    <div class="row mb-2">
                                        <div class="col-lg-6 mb-2">
                                            <label for="add-chemBrandUsed" class="form-label fw-light">Chemical / Item
                                                Used</label>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label for="add-amountUsed" class="form-label fw-light">Amount
                                                Used</label>
                                        </div>
                                    </div>

                                    <div class="row mb-2" id="first-chem">
                                        <div class="col-lg-6 mb-2">
                                            <select id="add-chemBrandUsed" name="add_chemBrandUsed[]"
                                                class="form-select chem-brand-select">
                                                <!-- chem ajax -->
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-2 ps-0 d-flex justify-content-evenly">
                                            <div class="d-flex flex-column">
                                                <input type="number" maxlength="4" id="add-amountUsed"
                                                    name="add-amountUsed[]" step="any"
                                                    class="form-control amt-used-input form-add me-3"
                                                    autocomplete="one-time-code">
                                            </div>
                                            <span class="form-text mt-auto mb-2">-</span>
                                            <button type="button"
                                                class="delete-chem-row btn btn-grad mb-auto py-2 px-3">
                                                <i class="bi bi-dash-circle text-light"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-2" id="add-chemContainer">
                                        <!-- template add chemical -->
                                    </div>
                                    <button type="button" id="addMoreChem"
                                        class="btn btn-grad mt-auto d-flex gap-2 mb-2 mx-auto py-2 px-3">
                                        <span class="text-shadow">Add Chemical / Item</span>
                                        <i class="bi bi-plus-circle text-light"></i>
                                    </button>

                                    <p class="alert alert-warning py-1 mt-1 w-50 mx-auto text-center amt-used-alert"
                                        style="display: none;"></p>
                                    <p class="text-muted fst-italic">Note: Amount used will not be reflected unless
                                        transaction is dispatched or completed.</p>

                                    <div class="row mb-2">
                                        <label for="add-technicianName" class="form-label fw-light">Technicians
                                        </label>
                                    </div>

                                    <div class="row mb-2" id="firstTech">
                                        <div class="dropdown-center col-10 col-lg-6">

                                            <select id="add-technicianName" name="add-technicianName[]" not-size="2"
                                                class="form-select" aria-label="Default select example">
                                            </select>
                                        </div>
                                        <div class="col-2 p-0 d-flex align-items-stretch">
                                            <button type="button" id="deleteTech" class="btn btn-grad mb-auto px-3"><i
                                                    class="bi bi-dash-circle text-light text-align-middle"></i></button>
                                        </div>
                                    </div>

                                    <div id="addTechContainer" class="mb-2"></div>
                                    <div class="mb-2 mt-3 d-flex gap-1 p-0 justify-content-center">
                                        <button type="button" id="addMoreTech" class="btn btn-grad mt-auto py-2 px-3">
                                            <span class="text-shadow">Add Technician</span>
                                            <i class="bi bi-plus-circle text-light"></i>
                                        </button>
                                    </div>
                                    <div class="row mb-2 ">
                                        <div class="col-lg-6">
                                            <label for="add-status" class="form-label fw-light">Status</label>
                                            <select name="add-status" id="add-status" class="form-select">
                                                <option value="" selected>Select Status</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Accepted">Accepted</option>
                                                <option value="Finalizing">Finalizing</option>
                                                <option value="Dispatched">Dispatched</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Cancelled">Cancelled</option>
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
                                    <div class="row mb-2">
                                        <div class="col-lg-6 d-flex justify-content-between">
                                            <div>
                                                <p class="fw-bold mb-0 fs-5">Selected inspection report:</p>
                                                <span class="ps-2 my-2" id="add_selected_ir"></span>
                                            </div>
                                            <button id="show_ir_details" class="btn btn-grad mt-auto me-3">View
                                                report</button>
                                        </div>
                                    </div>

                                    <p class="text-center alert alert-info w-75 mx-auto visually-hidden"
                                        id="emptyInput"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                        data-bs-target="#confirm_close">Cancel</button>
                                    <button type="button" class="btn btn-grad" disabled-id="submitAdd"
                                        data-bs-toggle="modal" data-bs-target="#confirmAdd">Proceed &
                                        Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- add confirmation -->
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="confirmAdd" tabindex="0"
                    aria-labelledby="confirmAdd" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyAdd">Verification</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="addPwd" class="form-label fw-light">Add transaction? Enter operations
                                        supervisor
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
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="confirm_close" tabindex="0"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                            </div>
                            <div class="modal-body">
                                <p class="fs-5 text-center mb-0 fw-bold">Data input will get lost. Discard transaction
                                    creation?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#addModal"
                                    data-bs-toggle="modal">Go back</button>
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Discard</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


            <!-- view/edit -->
            <form id="viewEditForm">
                <div class="row g-2 text-dark m-0">
                    <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="details-modal"
                        tabindex="-1">
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
                                                class="form-control-plaintext form-add name-input" readonly
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
                                            <input class="form-control ps-3 d-none" placeholder="--/--/--"
                                                id="view-start">
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
                                    <p class="text-muted fst-italic">Note: Amount used will not be reflected unless
                                        transaction is dispatched or completed.</p>
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


                                    <div class="row mb-2">
                                        <!-- edit -->
                                        <div class="col-lg-4 d-flex justify-content-between">
                                            <div>
                                                <p class="fw-bold fs-5 mb-0">Inspection Report ID:
                                                </p>
                                                <p id="view-label-ir" class="ps-2 my-2"></p>
                                            </div>
                                            <button type="button" id="view_inspection_report_btn"
                                                class="btn btn-grad me-2 mt-auto">View report</button>
                                        </div>
                                        <div class="col-lg-6 d-flex flex-column d-none" id="edit-status-col">
                                            <label for="edit-status" class="label fw-bold form-label"
                                                id='label-edit-status'>Transaction Status:</label>
                                            <p id="edit-status" class="mb-0 ps-2"></p>
                                            <p class="alert alert-warning py-1 mt-2" style="display: none !important;">
                                            </p>
                                        </div>

                                    </div>

                                    <p id="transvoidalert"
                                        class="alert alert-danger py-2 text-center w-50 mx-auto mb-0">
                                    </p>

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

                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="confirmation" tabindex="0"
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
                                    <label for="addPwd" class="form-label fw-light">Enter operations supervisor
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="edit-saPwd" class="form-control" id="editPwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-3 w-75 mx-auto my-0 visually-hidden'
                                    id="del-errormessage">
                                </p>
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
                                    <table class="table align-middle table-hover w-100" id="finalizetable">
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
                                                <th class="text-dark">
                                                    <input type="checkbox" class="btn-check" id="finalize-checkall"
                                                        autocomplete="off">
                                                    <label class="btn fw-bold" for="finalize-checkall">Check All <i
                                                            id="finalize-checkicon"
                                                            class="bi bi-square ms-2"></i></label>
                                                </th>
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
                                        Enter operations supervisor
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
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="approvemodal" tabindex="0"
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
                                            id="transidspan"></span> Enter operations supervisor
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
                                    <label for="confirmapprove-inputpwd" class="form-label fw-light">Send request to
                                        void
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
                                <p class="text-center alert alert-info w-75 mx-auto mb-0 mt-2" style="display: none"
                                    id="voidalert">
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
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="reschedModal" tabindex="-1"
                    aria-labelledby="create" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Reschedule Transaction</h1>
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
                                <h1 class="modal-title fs-5">Reschedule Confirmation</h1>
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
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
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

            <form id="completeForm">
                <input type="hidden" name="completeid" id="completeid">
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="completeModal" tabindex="-1"
                    aria-labelledby="create" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Complete Finalized Transaction</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                        class="bi text-light bi-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <p class="fs-3 fw-bold mb-0">Reported item quantity left:</p>
                                <div class="p-0 m-0 mb-2" id="complete-chemBrandUsed"></div>
                                <button type="button" id="complete-addMoreChem"
                                    class="btn btn-grad mt-auto py-2 px-3 d-flex align-items-center">
                                    <p class="fw-light m-0 me-2">Add Chemical / Item</p><i
                                        class="bi bi-plus-circle text-light"></i>
                                </button>

                                <label for="completenotes" class="fw-light my-2">Additional Note:</label>
                                <textarea name="note" class="form-control w-50" id="completenotes" cols="1"
                                    placeholder="e.g. Used 200ml Termicide for kitchen and 100ml for bathroom."></textarea>
                                <p class="text-secondary ms-2 mb-0 mt-2">
                                    Please check the items before proceeding.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-bs-dismiss="modal" class="btn btn-grad">Close</button>
                                <button type="button" data-bs-target="#completeconfirm" data-bs-toggle="modal"
                                    class="btn btn-grad">Proceed</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="completeconfirm"
                    tabindex="0">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Transaction Completion Confirmation</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="complete-inputpwd" class="form-label fw-light">Mark
                                        this transaction as Completed?
                                        Enter Operation Supervisor
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="baPwd" class="form-control w-75"
                                            id="complete-inputpwd">
                                    </div>
                                </div>
                                <p class="text-body-secondary fw-light">Note. You cannot revert this once marked as
                                    Complete. It is recommended to double check.</p>
                                <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                    id="completeAlert">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#completeModal">Go back</button>
                                <button type="submit" class="btn btn-grad">Complete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="dispatchForm">
                <input type="hidden" name="dispatchid" id="dispatchid">
                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="dispatchModal" tabindex="-1"
                    aria-labelledby="create" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Dispatch Transaction</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                        class="bi text-light bi-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="p-0 m-0 mb-2" id="dispatch-chemBrandUsed"></div>
                                <button type="button" id="dispatch-addMoreChem"
                                    class="btn btn-grad mt-auto py-2 px-3 d-flex align-items-center">
                                    <p class="fw-light m-0 me-2">Add Chemical</p><i
                                        class="bi bi-plus-circle text-light"></i>
                                </button>

                                <label for="dispatchnotes" class="fw-light my-2">Note:</label>
                                <textarea name="note" class="form-control w-50" id="dispatchnotes" cols="1"
                                    placeholder="e.g. Chemicals and equipment prepared for dispatch. Technician will bring 500ml Termicide and 2 sprayers."></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-bs-dismiss="modal" class="btn btn-grad">Close</button>
                                <button type="button" data-bs-target="#dispatchconfirm" data-bs-toggle="modal"
                                    class="btn btn-grad">Proceed</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="dispatchconfirm"
                    tabindex="0">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Dispatch Transaction Confirmation</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="complete-inputpwd" class="form-label fw-light">Update transaction to
                                        Dispatch?
                                        Enter Operation Supervisor
                                        <?= $_SESSION['baUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="baPwd" class="form-control w-75"
                                            id="complete-inputpwd">
                                    </div>
                                </div>
                                <p class="text-body-secondary fw-light">Note. Make sure the dispatch team is geared and
                                    ready.</p>
                                <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                    id="dispatchAlert">
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#dispatchModal">Go back</button>
                                <button type="submit" class="btn btn-grad">Dispatch</button>
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
    <!-- toast -->
    <div class="toast-container m-2 me-3 bottom-0 end-0 position-fixed">
        <div class="toast align-items-center" role="alert" id="toast" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body text-dark ps-4 text-success-emphasis" id="toastmsg">
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
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
            $('#details-modal').on('hidden.bs.modal', function (e) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('openmodal');
                currentUrl.searchParams.delete('id');
                window.history.pushState(null, "", currentUrl.pathname + currentUrl.search);
            });
            <?php
        }
        ?>

        $("#finalizetranstable").on('change', 'tr input[type="checkbox"]', function () {
            let checked_inputs = $("#finalizetranstable input[type='checkbox']:checked").length;
            let totalRows = $("#finalizetranstable input[type='checkbox']").length;
            if (checked_inputs === totalRows && totalRows > 0) {
                $("#finalize-checkall").prop('checked', true);
                $("#finalize-checkicon").removeClass('bi-square').addClass('bi-check-square');
            } else {
                $("#finalize-checkall").prop('checked', false);
                $("#finalize-checkicon").removeClass('bi-check-square').addClass('bi-square');
            }
        });


        $(document).on('change', '#finalize-checkall', function () {
            let checked = $(this).prop('checked');

            $('table#finalizetable #finalize-checkicon').toggleClass('bi-square bi-check-square');
            // $("table#finalizetable #checkall").prop('checked', checked);

            $('table#finalizetable tbody tr input[type="checkbox"]').prop('checked', checked);
        });



        let apd = $('#add-packageStart');
        addPackageDate = flatpickr(apd, {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        });

        async function compute_package_expiry(date, packId) {
            return $.post(transUrl, {
                date: date,
                pack_exp: 'true',
                pid: packId
            }, function (data) {
                // alert(data);
                return data;
            })
                .fail(function (err) {
                    console.log(err);
                })
        }

        $(document).on("shown.bs.modal", "#finalizetransactionmodal", async function () {
            let status = $("#sortstatus").val();
            await $.get(transUrl, "&finalizetrans=true")
                .done(function (d) {
                    if ($("#finalizetranstable").length <= 0) {
                        $("#finalizetranstable").append(d);
                    } else {
                        $("#finalizetranstable").empty();
                        $("#finalizetranstable").append(d);
                    }
                })
                .fail(function (e) {
                    console.log(e);
                })
        });

        $(document).on('submit', "#finalizetransactionform", async function (e) {

            e.preventDefault();
            // console.log($(this).serialize());
            await $.ajax({
                method: 'POST',
                url: submitUrl,
                dataType: 'json',
                data: $(this).serialize() + '&finalize=true'
            })
                .done(function (data) {
                    // console.log(data);
                    if (data.success) {
                        $('#finalizeconfirm').modal('hide');
                        // $("#tableAlert").removeClass('visually-hidden').html(data.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        show_toast(data.success);
                        loadpage(1, $("#sortstatus").val());
                        $('#finalizetransactionmodal')[0].reset();
                    }
                })
                .fail(function (err) {
                    console.log(err);
                    $("#finalizealert").html(err.responseText).fadeIn(400).delay(2000).fadeOut(1000);
                });
        });

        $(document).on('submit', '#finalizeForm', async function (e) {
            e.preventDefault();
            let status = $('#sortstatus').val();
            // console.log($(this).serialize());
            $.ajax({
                method: 'POST',
                dataType: 'json',
                data: $(this).serialize() + "&finalsingletransact=true",
                url: submitUrl
            })
                .done(function (d) {
                    if (d.success) {
                        loadpage(1, status);
                        show_toast(d.success);
                        $("#finalizeForm")[0].reset();
                        $("#finalconfirm").modal('hide');
                    } else {
                        alert('Unknown error occured');
                    }
                })
                .fail(function (e) {
                    console.log(e);
                    $("#finalizingAlert").html(e.responseText).fadeIn(750).delay(2000).fadeOut(1000);
                })
        });

        $(document).on('submit', '#completeForm', async function (e) {
            e.preventDefault();
            let status = $('#sortstatus').val();
            // console.log($(this).serialize());
            $.ajax({
                method: 'POST',
                dataType: 'json',
                data: $(this).serialize() + "&singleconfirm=true",
                url: submitUrl
            })
                .done(function (d) {
                    if (d.success) {
                        loadpage(1, status);
                        show_toast(d.success);
                        $("#completeForm")[0].reset();
                        $("#completeconfirm").modal('hide');
                    } else {
                        alert('Unknown error occured');
                    }
                })
                .fail(function (e) {
                    console.log(e);
                    $("#completeAlert").html(e.responseText).fadeIn(750).delay(2000).fadeOut(1000);
                })
        });

        $(document).on('submit', '#dispatchForm', async function (e) {
            e.preventDefault();
            let status = $('#sortstatus').val();
            console.log($(this).serialize());
            $.ajax({
                method: 'POST',
                dataType: 'json',
                data: $(this).serialize() + "&singledispatch=true",
                url: submitUrl
            })
                .done(function (d) {
                    if (d.success) {
                        loadpage(1, status);
                        show_toast(d.success);
                        $("#dispatchForm")[0].reset();
                        $("#dispatchconfirm").modal('hide');
                    } else {
                        alert('Unknown error occured');
                    }
                })
                .fail(function (e) {
                    console.log(e);
                    $("#dispatchAlert").html(e.responseText).fadeIn(750).delay(2000).fadeOut(1000);
                })
        });

        $("#table").on('click', '.finalize-btn', function () {
            // console.log($(this).data('finalize-id'));
            let id = $(this).data('finalize-id');
            $("#finalizebackbtn").hide().attr('data-bs-target', '');
            $("#finalizeconfirm").modal('show');
            $("#finalizeconfirm").on('hidden.bs.modal', function () {
                $("#finalizebackbtn").show().attr('data-bs-target', '#finalizetransactionmodal');
                $("#finalizesingletransinput").prop('disabled', true);
            });
            $("#finalizesingletransinput").prop('disabled', false).val(id);
        });

        $(document).on('change', '#add-packageStart', async function (e) {
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
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            minDate: new Date().fp_incr(1),
            setDate: 'today'
            // enableTime: true
        });

        let addtime = $('#add-treatmentTime');
        addTime = flatpickr(addtime, {
            altInput: true,
            altFormat: "h:i K",
            dateFormat: "H:i",
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

        $(document).on('click', '#pendingbtn', function () {
            let transId = $(this).data('pending-id');
            console.log(transId);
            $('#transidinput').val(transId);
            $('#transidspan').val(transId);
            $('#approvependingtransactions')[0].reset();
            $('#approvemodal').modal('show');
        });


        $('#approvependingtransactions').on('submit', async function (e) {
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
                $.get(transUrl, { treatments: 'true', branch: <?= $_SESSION['branch'] ?> }, function (data) {
                    $(`#${form}-treatmentContainer`).empty();
                    $(`#${form}-treatmentContainer`).html(data);
                });
            } catch (error) {
                console.log(error);
            }
        }

        // inspection report changes 
        $("#add_ir").on('change', '#select_ir_trans', function () {
            const selected = $(this).find('option:selected');
            if ($(this).val() !== '') {
                $("#ir_add_proceed_btn").toggleClass('d-none', false);
                $("#add_create_new_ir").toggleClass('d-none', true);
                $("#add_selected_ir").text("IR No.: " + $(this).val());
                $("button#show_ir_details").data("ir-id", $(this).val());
                let customer_name = selected.data('c-name');
                $("#add-customerName").val(customer_name);
                let loc = selected.data('loc');
                $("#add-customerAddress").val(loc);
            } else {
                $("#ir_add_proceed_btn").toggleClass('d-none', true);
                $("#add_create_new_ir").toggleClass('d-none', false);
                $("#add_selected_ir").text('No IR Selected');
                $("button#show_ir_details").data("ir-id", '');
            }
        });

        $(document).on('click', "#addbtn", function () {
            $('#addTransaction')[0].reset();
        })

        $(document).on('shown.bs.modal', "#add_ir", function () {
            $("#ir_add_proceed_btn").toggleClass('d-none', true);
            $("#add_create_new_ir").toggleClass('d-none', false);
            $("#add_selected_ir").text('');
        });

        $("#add_ir").on('click', '#ir_add_proceed_btn', function () {
            $("#add_ir").modal('hide');
            load_add_trans();
        });
        // add_selected_ir
        $(document).on('shown.bs.modal', '#add_ir', function () {

            $("#ir_add_proceed_btn").toggleClass('d-none', true);
            $.get(transUrl, { get_ir: 'true' }, function (d) {
                $("#select_ir_trans").empty();
                $("#select_ir_trans").append(d);
            }).fail(function (e) {
                console.log(e);
            });
        });

        $("#addModal").on('click', '#show_ir_details', async function () {
            let id = $(this).data('ir-id');
            console.log(id);
            await load_report(id);
            $("#addModal").modal('hide');
            $("#ir_details_modal").modal('show');
        });

        async function load_add_trans() {
            let form = 'add';
            try {
                const load = await Promise.all([
                    get_chemical_brand(form),
                    get_technician(form),
                    get_problems(form),
                    // add_more_chem(),
                    add_more_tech(),
                    add_packages(),
                    treatments(form)
                ]);
                if (load) {
                    $('#add-session').attr('disabled', true);
                    $('#add-treatment').attr('disabled', false);
                    $('#add-packageStart').attr('disabled', true);
                    $('#add-packageExpiry').attr('disabled', true);

                    // $('#addTransaction')[0].reset();
                    $('#addTechContainer').empty();
                    $('#add-chemContainer').empty();
                    $('#addModal').modal('show');
                }

            } catch (error) {
                console.log('add get error.')
            }
        }


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

            $('#addMoreTech', '#addModal').off('click').on('click', async function () {
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
                .done(function (d) {
                    // console.log(d);
                    $(`#count_${container}`).empty();
                    $(`#count_${container}`).append(d);
                })
                .fail(function (e) {
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
            get_overview_count('dispatched');
        }

        // add / delete chem main function
        // async function add_more_chem() {
        //     // let moreChemTemp = $('#add-chemicalData').html();
        //     let num = 2;

        //     $('#addMoreChem', '#addModal').off('click').on('click', async function() {
        //         await add_used_chem(num);
        //         num++;
        //         console.log(num);

        //     });

        // }

        $(document).on('click', '#addMoreChem', async function () {
            let sts = $(this).data('status');
            console.log(sts);
            await add_used_chem(sts);
        })

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
        async function add_used_chem(status = '') {
            // let rowNum = num;
            try {
                const addMoreUsed = await $.ajax({
                    type: 'GET',
                    url: transUrl,
                    dataType: 'html',
                    data: {
                        getMoreChem: 'true',
                        status: status
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
                    $(`#${form}-probCheckbox`).empty();
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
            altInput: true,
            altFormat: "F j, Y"
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
            altInput: true,
            altFormat: "F j, Y"
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

            $("#view-customerName, #edit-session").attr("readonly", function (i, attr) {
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
            $("#view-start, #view-start + input, #view_wstart, #view-expiry, #view_wexpiry, #w_expiry_note, #view-treatmentDate, #t_viewdate, #t_viewtime, #view-treatmentTime, #view-treatmentDate + input, #view-treatmentTime + input").toggleClass('d-none');

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
                $('#edit-treatment').attr('disabled', function (i, a) {
                    return a ? a : true;
                });
                $('#edit-session, #edit-start').attr('disabled', function (i, a) {
                    return a ? false : a;
                });
            }

            return toggled = true;
        }

        // $(document).on('change', "#add-status", function () {
        //     let sts = $(this).val();

        //     $('#addMoreChem').data('status', sts);

        //     if (sts === 'Completed' || sts === 'Finalizing' || sts === 'Dispatched') {
        //         $('input.form-control.amt-used-input').prop('disabled', false);
        //         $('input.form-control.amt-used-input').attr('name', 'add-amountUsed[]');
        //     } else {
        //         $('input.form-control.amt-used-input').val('-').prop('disabled', true);
        //         $('input.form-control.amt-used-input').removeAttr('name');
        //     }

        // })

        // $(document).on('change', '#edit-status', function () {
        //     let sts = $(this).val();
        //     $('#edit-addMoreChem').data('status', sts);

        //     if (sts === 'Completed' || sts === 'Finalizing' || sts === 'Dispatched') {
        //         $('#edit-chemBrandUsed input.form-control').prop('disabled', false);
        //         $('#edit-chemBrandUsed input.form-control').attr('name', 'edit-amountUsed[]');
        //     } else {
        //         $('#edit-chemBrandUsed input.form-control').val('-').prop('disabled', true);
        //         $('#edit-chemBrandUsed input.form-control').removeAttr('name');
        //     }
        // });

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
                .done(function (d) {
                    return d;
                })
                .fail(function (error, status, errmsg) {
                    console.log(error);
                    console.log(status + errmsg);
                });
        }

        function get_package_name(id) {
            $.get(transUrl, `packagename=true&id=${id}`)
                .done(function (data) {
                    $('#view-package').empty();
                    $('#view-package').html(data);
                })
                .fail(function (error, status, errmsg) {
                    console.log(error);
                    console.log(status + errmsg);
                });
        }

        let sval, tval, wval, weval;
        $(document).on('change', '#edit-package-select', function () {
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
                packageStartDate.clear();
                $('#view-expiry').val('');
                $('#view-start, #view-expiry').removeAttr('name');
                $('#view-start, #view-start + input, #view-expiry').attr('disabled', true);
            } else {
                $('#edit-treatment').val(tval);
                $('#edit-treatment').removeAttr('name');
                $('#edit-treatment').attr('disabled', true);

                $('#edit-session').val(sval);
                $('#edit-session').attr('disabled', false);
                $('#edit-session').attr('name', 'edit-session');

                $('#view-start').attr('name', 'edit-start');
                $('#view-expiry').attr('name', 'edit-expiry');
                $('#view-start, #view-start + input, #view-expiry').attr('disabled', false);
                $('#view-start').val(wval);
                packageStartDate.setDate(wval);
                $('#view-expiry').val(weval);

            }

        });

        $(document).on('click', '#requestvoidbtn', function () {
            // $('#requestedvoidtransactions').modal('show');
            $.get(transUrl, {
                voidrequest: 'true'
            }, function (data) {
                $('#voidrequesttable').empty();
                $('#voidrequesttable').append(data);
            })
                .fail(function (e) {
                    console.log(e);
                })
        });

        $(document).on('click', "#modalcancelbtn", function () {
            let id = $('#view-transId').val();
            // console.log(id);
            $('#transidinputcancel').val(id);
            $('#cancelscheduledform').on('hidden.bs.modal', function () {
                $("#cancelscheduledform")[0].reset();

            });
        });

        $(document).on('click', '#voidrequestmodal', function () {
            let id = $('#view-transId').val();
            $("#voidreqid").val(id);
        })

        $(document).on('submit', "#voidrequestform", async function (e) {
            e.preventDefault();
            console.log($(this).serialize());

            $.ajax({
                method: "POST",
                url: submitUrl,
                dataType: 'json',
                data: $(this).serialize() + "&submitvoidreq=true"
            })
                .done(async function (d) {
                    show_toast(d.success);
                    $("#voidrequestform")[0].reset();
                    $("#voidrequestmodal").modal('hide');
                    await loadpage(1, $("#sortstatus").val());
                })
                .fail(function (e) {
                    $("#voidalert").fadeIn(400).html(e.responseText).delay(2000).fadeOut(1000);
                    // console.log(e);
                })
        })
        $(document).on('submit', '#cancelscheduledform', async function (e) {
            e.preventDefault();
            // console.log($(this).serialize());
            await $.ajax({
                method: "POST",
                url: submitUrl,
                dataType: 'json',
                data: $(this).serialize() + "&cancel=true"
            })
                .done(async function (d) {
                    show_toast(d.success);
                    $("#cancelscheduledform")[0].reset();
                    $("#cancelscheduledmodal").modal('hide');
                    await loadpage(1, $("#sortstatus").val());
                })
                .fail(function (e) {
                    $("#cancelAlert").fadeIn(400).html(e.responseText).delay(2000).fadeOut(1000);
                    console.log(e);
                })
        })

        $("#table").on('click', '.cancel-btn, .resched-btn', function () {
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

        $(document).on("submit", "#reschedForm", async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            await $.ajax({
                method: "POST",
                url: submitUrl,
                dataType: 'json',
                data: $(this).serialize() + "&reschedule=true"
            })
                .done(async function (d) {
                    show_toast(d.success);
                    $("#reschedForm")[0].reset();
                    $("#reschedConfirm").modal('hide');
                    await loadpage(1, $("#sortstatus").val());
                })
                .fail(function (e) {
                    $("#reschedAlert").fadeIn(400).html(e.responseText).delay(2000).fadeOut(1000);
                    console.log(e);
                })
        });

        $("#table").on('click', '.dispatched-btn', async function () {
            let id = $(this).data('dispatched-id');
            // console.log(id);
            $("#finalizeid").val(id);
            await get_chemical_brand('finalize', id, "Dispatched");
            $.get(transUrl, {
                notes: true,
                id: id
            }, function (d) {
                // console.log(d);
                // dd = JSON.parse(d);
                $("#finalizeNotes").val(d.notes);
            }, 'json')
                .fail(function (e) {
                    console.log(e);
                })
            $("#finalizeModal").modal('show');
        });

        $("#table").on('click', '.accepted-btn', async function () {
            let id = $(this).data('accepted');
            // console.log(id);
            $("#dispatchid").val(id);
            await get_chemical_brand('dispatch', id, "Dispatched");
            $.get(transUrl, {
                notes: true,
                id: id
            }, function (d) {
                // console.log(d);
                // dd = JSON.parse(d);
                $("#dispatchNotes").val(d.notes);
            }, 'json')
                .fail(function (e) {
                    console.log(e);
                })
            $("#dispatchModal").modal('show');
        });

        $("#table").on('click', '.finalizing-btn', async function () {
            let id = $(this).data('finalize-id');

            $("#completeid").val(id);
            await get_chemical_brand('complete', id, "Finalizing");
            $.get(transUrl, {
                notes: true,
                id: id
            }, function (d) {
                $("#completenotes").val(d.notes);
            }, 'json')
                .fail(function (e) {
                    console.log(e);
                })
            $("#completeModal").modal('show');
        });

        $(document).on('shown.bs.modal', '#finalizetransactionmodal', function () {
            $("#finalize-checkall").prop('checked', false);
            $("#finalize-checkicon").removeClass('bi-check-square').addClass('bi-square');
        })

        $("#viewEditForm").on('click', '#view_inspection_report_btn', function () {
            let id = $(this).data('ir-id');
            // console.log(id);
            load_report(id);
            $("#details-modal").modal('hide');
            $("#ir_details_modal").modal('show');
        });

        $(document).on('click', '#view_inspection_report_btn', function () {
            $("#ir_details_modal #ir_back_btn").attr('data-bs-target', '#details-modal');
        })

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
                    $("#view-label-ir").text("IR No. " + d.inspection_report);
                    if (d.inspection_report == 0) {
                        $("#view_inspection_report_btn").prop("disabled", true);
                        $("#view_inspection_report_btn").removeAttr("data-ir-id");
                        $("#view-label-ir").text("No IR reported.");
                    } else {
                        $("#view_inspection_report_btn").prop("disabled", false);
                        $("#view_inspection_report_btn").attr("data-ir-id", d.inspection_report);
                    }
                    $('#view-customerName').val(d.customer_name ?? `Name not set.`);
                    editTransDate.clear();
                    editTransDate.setDate(d.treatment_date);
                    // packageExpDate.clear();
                    // packageExpDate.setDate(d.pack_exp ?? '--/--/--');
                    $('#view-expiry').val(d.package_end ?? '--/--/--');
                    $("#view_wexpiry").text(d.package_end ?? '-');
                    packageStartDate.clear();
                    packageStartDate.setDate(d.pack_start ?? '--/--/--');
                    $('#list-status').empty();
                    $('#view-status').html(d.transaction_status);
                    $('#view-address').html(d.customer_address ?? 'Address not set.');
                    $('#edit-address').html(d.customer_address ?? '');
                    editTransTime.setDate(d.transaction_time);
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
                        // console.log(tdate + today);
                        if (tdate > today) {
                            $("#viewEditForm #modalcancelbtn").show().prop('disabled', false).attr('data-bs-target', '#cancelscheduledmodal');

                        } else {
                            $("#viewEditForm #modalcancelbtn").hide().prop('disabled', true).attr('data-bs-target', '#cancelscheduledmodal');
                        }
                    }

                    // if (d.transaction_status === 'Finalizing' || d.transaction_status === 'Dispatched') {
                    //     $("#edit-status option[value='Accepted']").prop('disabled', true);
                    //     $("#edit-status option[value='Pending']").prop('disabled', true);
                    // } else {
                    //     $("#edit-status option[value='Accepted']").prop('disabled', false);
                    //     $("#edit-status option[value='Pending']").prop('disabled', false);
                    // }

                    if (d.void_request === 1) {
                        $('#editbtn').hide().attr('disabled', true);
                        $("#viewEditForm #requestvoidbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                        $("#viewEditForm #modalcancelbtn").hide().prop('disabled', true).attr('data-bs-target', '');
                    }

                    // if (d.transaction_status === 'Finalizing' || d.transaction_status === 'Dispatched') {
                    //     $('#edit-chemBrandUsed input.form-control.form-add').attr('disabled', false);
                    //     $('#edit-chemBrandUsed input.form-control.form-add').attr('name', 'edit-amountUsed[]');
                    // }

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
                        $('#edit-session, #view-expiry, #view-start, #view-start + input.form-control').attr('disabled', true);
                        $('#edit-session, #view-expiry, #view-start, #view-start + input.form-control').val('');
                    }

                    $('#edit-note').val(d.notes ?? '');
                    $('#view-note').html(d.notes == null || d.notes == '' ? 'No existing note.' : d.notes);

                    $('#edit-status').text(d.transaction_status);

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
                console.log(error);
            }
        }

        $(document).on('focus', '#view-start, #view-start + input', async function (e) {
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

        $(document).on('click', '#editbtn', async function () {
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

        $(document).on('click', '.finalize-peek-trans-btn', function () {
            $('#finalizetransactionmodal').modal('hide');
        })
        $(document).on('click', '.check-void-req-btn', function () {
            $('#requestedvoidtransactions').modal('hide');
        })

        // open details
        $(document).on('click', '#tableDetails, .finalize-peek-trans-btn, .check-void-req-btn', async function () {
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
            }, function (data) {
                $(`#edit-${row}`).append(data);
                console.log(status);
            })
        }

        // new
        $(document).on('change', '#add-status', function () {
            let sel = $(this);
            if (sel.val() === 'Voided') {
                sel.next().fadeOut().fadeIn(750).html("Note: Voiding a transaction completely will require Manager approval. Ignore to continue.");
                $(".amt-used-alert").fadeOut(1000);
            } else if (sel.val() === 'Accepted') {
                sel.next().fadeOut().fadeIn(750).html("Make sure to double check the details at least before the dispatch date.");
                $(".amt-used-alert").fadeOut(1000);
            } else if (sel.val() === 'Finalizing') {
                sel.next().fadeOut().fadeIn(750).html("Note: Transactions with this status will be subjected to completion, please make sure to double check the details.");
                $(".amt-used-alert").fadeOut().fadeIn(750).html('Note: Only report the amount the technician used.');
            } else if (sel.val() === 'Cancelled') {
                sel.next().fadeOut().fadeIn(750).html("Cancelled transactions should be accepted first.");
                $(".amt-used-alert").fadeOut(1000);
            } else if (sel.val() === 'Dispatched') {
                sel.next().fadeOut().fadeIn(750).html("Note: Make sure the technicians are ready.");
                $(".amt-used-alert").fadeOut().fadeIn(750).html('Note: Only put the amount the technician will bring.');
            } else if (sel.val() === 'Completed') {
                sel.next().fadeOut().fadeIn(750).html("Make sure to double check details. Setting this transaction to complete will make this viewonly.");
                $(".amt-used-alert").fadeOut().fadeIn(750).html('Note: Make sure to put only the actual amount of the used chemical.');
            } else {
                sel.next().fadeOut(1000);
                $(".amt-used-alert").fadeOut(1000);
            }
            $(document).on('click', '#editbtn', function () {
                $(".amt-used-alert").hide();
            })
        });

        // new
        $("#addModal, #details-modal").on('hidden.bs.modal', async function () {
            $("#add-status").next().hide();
        });

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
            if (row === 1) {
                alert('Transaction should have at least one technician.');
            } else {
                $(this).parent().remove();
                // console.log('tech row removed');
                await check_emptyrow('technicianName');
            }
        })

        $(document).on('click', '#deleteTech', async function () {
            let firstTech = $("#firstTech").length;
            let rowId = $(this).data('row-id');
            let row = $('#addTechContainer').children().length;
            let totalTechs = row + firstTech;
            if (totalTechs > 1) {
                $(this).parent().parent().remove();
            } else {
                alert('Transaction should have at least one technician.');
                // console.log('tech row removed');
                // await check_emptyrow('technicianName');
            }
        });

        $(document).on('change', '#edit-chemBrandUsed select.form-select, select.form-select.chem-brand-select', function () {
            let span = $(this).closest('.row').find('span');

            $.get(transUrl, {
                getunit: 'true',
                chemid: $(this).val()
            })
                .done(function (d) {
                    span.text(d);
                })
                .fail(function (err) {
                    console.log(err);
                    $span.text('-');
                });
        });

        // // Update unit span when checkbox is changed (even if select is not changed)
        // $(document).on('change', '.whole-container-chk', function() {
        //     let $chk = $(this);
        //     let $row = $chk.closest('.row');
        //     let $select = $row.find('select.form-select, select.form-select.chem-brand-select');
        //     let $span = $row.find('span');
        //     if ($chk.is(':checked')) {
        //         $span.text("Container/s");
        //     } else {
        //         // Fetch unit for the selected chemical
        //         $.get(transUrl, {
        //                 getunit: 'true',
        //                 chemid: $select.val()
        //             })
        //             .done(function(unit) {
        //                 $span.text(unit);
        //             })
        //             .fail(function() {
        //                 $span.text('-');
        //             });
        //     }
        // });

        $("#viewEditForm").on('click', '#edit-chemBrandUsed button.ef-del-btn.btn.btn-grad', async function () {
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

        $(document).on('click', '#edit-addTech', async function () {
            // $.get(transUrl, { editTechAdd: 'true' }, function (data) {
            //     $('#edit-technicianName').append(data);
            // })
            await edit('technicianName');
        })

        $(document).on('click', '#edit-addMoreChem', async function () {
            let stats = $(this).data('status');
            get_addrow('chemBrandUsed', stats);
        })

        $("#finalizeForm").on('click', "#finalize-addMoreChem", function () {
            $.get(transUrl, {
                addrow: 'true',
                status: 'Finalizing'
            }, function (data) {
                $("#finalize-chemBrandUsed").append(data);
                // console.log(data);
            }, 'html');

        });
        $("#completeForm").on('click', "#complete-addMoreChem", function () {
            $.get(transUrl, {
                addrow: 'true',
                status: 'Completed'
            }, function (data) {
                $("#complete-chemBrandUsed").append(data);
            }, 'html');

        });
        $("#dispatchForm").on('click', "#dispatch-addMoreChem", function () {
            $.get(transUrl, {
                addrow: 'true',
                status: 'Dispatched'
            }, function (data) {
                $("#dispatch-chemBrandUsed").append(data);
            }, 'html');

        });

        $("#finalizeForm").on('click', '#finalize-chemBrandUsed button', async function () {
            let row = $(this).closest('div.row');
            let length = $('#finalize-chemBrandUsed').children('.row').length;
            if (length === 1) {
                alert('One or more chemicals are required in order to proceed.');
                console.log($('#finalize-chemBrandUsed'));
            } else {
                row.remove();
            }
        });
        $("#completeForm").on('click', '#complete-chemBrandUsed button', async function () {
            let row = $(this).closest('div.row');
            let length = $('#complete-chemBrandUsed').children('.row').length;
            if (length === 1) {
                alert('One or more chemicals are required in order to proceed.');
            } else {
                row.remove();
            }
        });

        $("#dispatchForm").on('click', '#dispatch-chemBrandUsed button', async function () {
            let row = $(this).closest('div.row');
            let length = $('#dispatch-chemBrandUsed').children('.row').length;
            if (length === 1) {
                alert('One or more chemicals are required in order to proceed.');
            } else {
                row.remove();
            }
        });



        $(document).on('click', '#deleteChem, .delete-chem-row', function () {
            let firstChem = $("#first-chem").length;
            let emptyAddContainer = $("#add-chemContainer").children().length;
            let totalItems = firstChem + emptyAddContainer;
            if (totalItems > 1) {
                $(this).parent().parent().remove();
            } else {
                alert("Chemical / Item used should not be zero.");
            }
        });

        $(document).on("shown.bs.modal", "#addModal", function () {
            $("#reported_pest_container").empty();
            $("#edit-probCheckbox").empty();
        });
        $(document).on("shown.bs.modal", "#inspection_select_modal", function () {
            $("#add-probCheckbox").empty();
            $("#edit-probCheckbox").empty();
        });
        $(document).on("shown.bs.modal", "#details-modal", function () {
            $("#add-probCheckbox").empty();
            $("#reported_pest_container").empty();
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
        $(document).on('change', '#add-package', function () {
            let package = $(this).val();
            // console.log(package);
            if (package === 'none') {
                aps = $('#add-packageStart').val();
                a_s = $('#add-session').val();
                ae = $('#add-packageExpiry').val();
                $('#add-session').attr('disabled', true).val('');
                $('#add-treatment').attr('disabled', false).val(at);
                $("#add-packageStart, #add-packageStart + input[readonly='readonly']").attr('disabled', true).val('');
                $('#add-packageExpiry').attr('disabled', true).val('');
            } else {
                at = $('#add-treatment').val();
                $('#add-session').attr('disabled', false).val(a_s);
                $('#add-treatment').attr('disabled', true).val('');
                $("#add-packageStart, #add-packageStart + input[readonly='readonly']").attr('disabled', false).val(aps);
                $('#add-packageExpiry').attr('disabled', false).val(ae);
            }
        });

        $(document).on('focusout', 'form input, form select, form textarea', function () {
            if ($(this).val() == '' || $(this).val() == '#') {
                $(this).addClass('border border-danger');
            } else {
                $(this).removeClass('border border-danger');
            }
        });

        // submit
        $('#addTransaction').on('submit', async function (e) {
            e.preventDefault();
            let status = $("#sortstatus").val();
            console.log($(this).serializeArray());
            try {
                const trans = await $.ajax({
                    type: 'POST',
                    url: submitUrl,
                    data: $(this).serialize() + "&addSubmit=true",
                    dataType: 'json'
                });

                if (trans.success) {
                    // console.log(trans.success);
                    // console.log(trans.iterate);
                    await loadpage(1, status);
                    $('#confirmAdd').modal('hide');
                    $('#addTransaction')[0].reset();
                    // $('#tableAlert').removeClass('visually-hidden').html(trans.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                    show_toast(trans.success);
                }
                if (trans) {
                    console.log(trans);
                }

            } catch (error) {
                console.log(error);
                $("#add-alert").removeClass('visually-hidden').html(error.responseText).hide().fadeIn(400).delay(2000).fadeOut(1000);
            }
        })

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
                    // $('#tableAlert').removeClass('visually-hidden').html(update.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                    show_toast(update.success);
                }
            } catch (error) {
                console.log(error);
                // alert(error.responseText);
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
                    // $('#tableAlert').removeClass('visually-hidden').html(del.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                    show_toast(del.success);
                }
            } catch (error) {
                // console.log(error.responseText);
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
        $(function () {
            let delay = null;

            $('#searchbar').keyup(function () {
                clearTimeout(delay);
                $('#table').empty();
                $('#loader').removeClass('visually-hidden');

                delay = setTimeout(async function () {
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

        $(document).ready(function () {
            loadpage(1);
        })


        $("#sortstatus").on('change', async function () {
            let status = $("#sortstatus option:selected").val();
            $("#searchbar").val('');
            await loadpage(1, status);
        })

        async function loadpage(page = 1, status = '') {
            await load_pagination_buttons(page, status);
            await load_paginated_table(page, status);
            get_counts();
        }

        $('#pagination').on('click', '.page-link', async function (e) {
            e.preventDefault();
            let status = $("#sortstatus option:selected").val();

            let currentpage = $(this).data('page');
            // console.log(currentpage);
            // window.history.pushState(null, "", "?page=" + currentpage);
            await loadpage(currentpage, status);
        });

        $('#table').ready(function () {
            $('.pending-btn').hover(function () {
                $(this).html('Approve/Accept');
            }, function () {
                $(this).html('Pending');
            });
        })

        $(document).ready(function () {
            $('#table').on('mouseenter', '.pending-btn', function () {
                $(this).html('Approve/Accept Transaction');
            });
            $('#table').on('mouseleave', '.pending-btn', function () {
                $(this).html('Pending');
            });
            $('#table').on('mouseenter', '.accepted-btn', function () {
                $(this).html('Dispatch Transaction');
            });
            $('#table').on('mouseleave', '.accepted-btn', function () {
                $(this).html('Accepted');
            });
            $('#table').on('mouseenter', '.dispatched-btn', function () {
                $(this).html('Finalize Transaction');
            });
            $('#table').on('mouseleave', '.dispatched-btn', function () {
                $(this).html('Dispatched');
            });
            $('#table').on('mouseenter', '.finalizing-btn', function () {
                $(this).html('Complete Transaction');
            });
            $('#table').on('mouseleave', '.finalizing-btn', function () {
                $(this).html('Finalizing');
            });
            $('#table').on('mouseenter', '.cancel-btn', function () {
                $(this).html('Reschedule');
            });
            $('#table').on('mouseleave', '.cancel-btn', function () {
                $(this).html('Cancelled');
            });
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




        $(document).on('click', '#add_create_new_ir, #add_inspection', function () {
            $("#new_inspection_report")[0].reset();
            $.get(
                transUrl,
                {
                    getProb: 'true'
                },
                function (d) {
                    $("#reported_pest_container").empty();
                    $("#reported_pest_container").append(d);
                }).fail(function (e) {
                    console.log(e);
                });

            $.get(
                transUrl, {
                get_branch: 'add_branch'
            },
                function (d) {
                    $("#ir_branch").empty();
                    $("#ir_branch").append(d);
                }).fail(function (e) {
                    console.log(e);
                });

            $.get(
                transUrl,
                {
                    get_ir: 'true'
                },
                function (d) {
                    $("#inspection_select").empty();
                    $("#inspection_select").append(d);
                },
                'html'
            )
                .fail(function (e) {
                    console.log(e);
                })
        });

        $("#inspection_select_modal").on('change', '#existing_pc__yes, #existing_pc__no', function () {
            let checked = $("#existing_pc__no").is(':checked');
            let no_history_checked = $("#no_treatment_history").is(':checked');
            $("#existing_provider_last_treatment, #last_treatment_date, #last_treatment_date + input").prop('disabled', function () {
                if ((checked && no_history_checked) || !checked) {
                    return true;
                } else {
                    return false;
                }
            });
            $(".existing_label").toggleClass('d-none', !checked);
        });

        $(document).on('submit', '#new_inspection_report', function (e) {
            e.preventDefault();
            console.log($(this).serialize());

            $.ajax({
                method: "POST",
                url: submitUrl,
                dataType: 'json',
                data: $(this).serialize() + "&new_ir=true"
            }).done(function (d) {
                $("#new_inspection_report")[0].reset();

                $.get(
                    transUrl,
                    {
                        get_ir: 'true'
                    },
                    function (d) {
                        $("#inspection_select").empty();
                        $("#inspection_select").append(d);
                    },
                    'html'
                )
                    .fail(function (e) {
                        console.log(e);
                    })
                $("#new_ir_alert").text(d.success).fadeIn().delay(5000).fadeOut(2000);
            }).fail(function (e) {
                console.log(e);
                $("#new_ir_alert").text(e.responseText).fadeIn().delay(5000).fadeOut(2000);
            })
        });

        let ltd = document.getElementById('last_treatment_date');
        last_treatment_date = flatpickr(ltd, {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        });

        let ltd_details = document.getElementById('ir_last_treatment');
        details_ltd = flatpickr(ltd_details, {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        });

        $(document).on("change", "select#inspection_select", function (e) {
            let ir = $(this).val();
            console.log(ir);
            if (ir != '') {
                $("#create_inspection_container").addClass('d-none');
            } else {
                $("#create_inspection_container").removeClass('d-none');
            }
        });

        async function load_ir_table(page = 1) {
            let branch = <?= $_SESSION['branch'] ?>;
            await $.get(
                'contents/trans.ir.pagination.php',
                {
                    table: 'true',
                    branch: branch,
                    currentpage: page
                }, function (d) {
                    $("#ir_table").empty();
                    $("#ir_table").append(d);
                    return true;
                },
                'html'
            ).fail(function (e) {
                console.log(e);
                return false;
            });
        }

        async function load_ir_pagination(page = 1) {
            let branch = <?= $_SESSION['branch'] ?>;
            await $.get('contents/trans.ir.pagination.php', {
                paginate: 'true',
                active: page,
                branch: branch
            }, function (d) {
                $("#ir_pagination").empty();
                $("#ir_pagination").append(d);
                return true;
            }, 'html').fail(function (e) {
                console.log(e);
                return false;
            });
        }

        $("#inspection_report_modal").on('click', '.page-link', async function (e) {
            e.preventDefault();
            let branch = <?= $_SESSION['branch'] ?>;
            let currentpage = $(this).data('page');

            await load_ir_table(currentpage);
            await load_ir_pagination(currentpage);
        });

        $(document).on('shown.bs.modal', '#inspection_report_modal', async function () {
            await load_ir_table();
            await load_ir_pagination();
        });

        let ir_toggled = false;
        function toggle_ir() {
            $(".ir-input").toggleClass('form-control-plaintext form-control');
            $("#ir_last_treatment, #ir_last_treatment + input, #existing_btn_group").toggleClass('d-none', ir_toggled);
            $(".ir-input").prop('readonly', ir_toggled);

            // select inputs
            $("#ir_property_type, #ir_exposed_soil").prop('disabled', ir_toggled);
            $("#ir_property_type, #ir_property_type_display, #ir_exposed_soil, #ir_exposed_soil_display, #ir_existing_pc").toggleClass('d-none');

            $("#no_trt_history_chkbx, #ir_treatment_history_display, .display-toggle, .ir-confirm-btn, #ir_last_treatment_display").toggleClass('d-none');
            $("#ir_edit_toggle").text(ir_toggled ? 'Edit' : 'Cancel edit');
            // $("")
            return ir_toggled = !ir_toggled ? true : false;
        }

        $("#ir_details_modal").on('click', '#ir_edit_toggle', function () {
            toggle_ir();
        });

        $("#ir_details_modal").on('change', '#ir_yes, #ir_no', function () {
            let checked = $("#ir_yes").is(':checked');
            let no_history_checked = $("#ir_no_treatment_history").is(':checked');

            $("#ir_no_treatment_history, #ir_latest_treatment, #ir_last_treatment, #ir_last_treatment + input").prop('disabled', checked);
            if (!checked) {
                $("#ir_latest_treatment, #ir_last_treatment, #ir_last_treatment + input").prop('disabled', no_history_checked);
            }
        });

        $("#ir_details_modal").on('change', '#ir_no_treatment_history', function () {
            let checked = $(this).is(':checked');
            $("#ir_latest_treatment, #ir_last_treatment, #ir_last_treatment + input").prop('disabled', checked);
        })

        async function load_report(ir_id) {
            $.get(transUrl, { ir_details: 'true', id: ir_id }, function (d) {
                console.log(d);
                $("#ir_inspection_id").text(d.id);
                $("#ir_customer").val(d.customer);
                $("#ir_property_type").val(d.property_type);
                $("#ir_floor_area").val(d.total_floor_area);
                $("#ir_floor_area_unit").text(d.floor_area_unit);
                $("#ir_total_floors").val(d.total_floor_num);
                $("#ir_total_rooms").val(d.total_room);
                $("#ir_property_type_display").text(d.property_type + ' Property');
                $("#ir_exposed_soil_display").text(d.exposed_soil_outside_property == 'no_termite' ? 'No termite' : d.exposed_soil_outside_property);
                $("#ir_location").val(d.property_location);
                $("#ir_exposed_soil").val(d.exposed_soil_outside_property);
                $("#ir_location_seen").val(d.reported_pest_problem_location);
                let epc = d.existing_pest_provider;
                $("#ir_existing_pc").text(epc == 1 ? "Yes" : "No");
                if (epc == 1) {
                    $("#ir_existing_pc").text("Yes");
                    $("#ir_yes").prop('checked', 'checked');
                    $("#ir_no").prop('checked', false);
                    $("#ir_no_treatment_history").prop('disabled', true);
                } else {
                    $("#ir_no_treatment_history").prop('disabled', false);
                    $("#ir_existing_pc").text("No");
                    $("#ir_yes").prop('checked', false);
                    $("#ir_no").prop('checked', 'checked');
                }
                let lt = d.last_treatment;
                $("#ir_latest_treatment").val(lt == null ? "None" : lt);
                let ltd = d.last_treatment_date;
                if (ltd == null) {
                    $("#ir_last_treatment_display").text('None');
                    $("#ir_last_treatment + input").prop('placeholder', '--/--/--');
                    details_ltd.clear();
                } else {
                    details_ltd.setDate(ltd);
                    $("#ir_last_treatment_display").text(d.ltd);
                }
                // $("#ir_last_treatment").val(ltd == null ? "None" : details_ltd.setDate(ltd));
                $("#ir_note").val(d.notes);
                $("#ir_treatment_history_display").text(function () {
                    if (lt == null && ltd == null) {
                        $("#ir_no_treatment_history").prop('checked', 'checked');
                        $("#ir_latest_treatment, #ir_last_treatment, #ir_last_treatment + input").prop('disabled', true);
                        return "Note: This customer has no treatment history.";
                    }
                    $("#ir_latest_treatment, #ir_last_treatment, #ir_last_treatment + input").prop('disabled', false);
                    $("#ir_no_treatment_history").prop('checked', false);
                    return '';
                });
                d.created_by = d.created_by == '' ? 'No user recorded.' : d.created_by;
                d.updated_by = d.updated_by == '' ? 'No user recorded.' : d.updated_by;
                let addinfo = `Created at ${d.add_at} by ${d.created_by}` + (d.upat == d.add_at ? '' : `<br> Updated at ${d.up_at} by ${d.updated_by}`) + `<br> ${d.branch.name} - ${d.branch.location}`;
                $("#ir_metadata").html(addinfo);
            }, 'json')
                .fail(function (e) {
                    console.log(e);
                })
            if (ir_toggled) {
                toggle_ir();
            }

            $.get(
                transUrl,
                {
                    ir_pest_problems: 'true',
                    id: ir_id
                },
                function (d) {
                    $("#ir_pproblems_list").empty();
                    $("#ir_pproblems_list").append(d);
                },
                'html'
            )
                .fail(function (e) {
                    console.log(e);
                });

            $.get(transUrl, { ir_problems_array: 'true', id: ir_id }, function (d) {
                $("#ir_pest_problem_container").empty();
                $("#ir_pest_problem_container").append(d);
                // console.log(d);
            }, 'html')
                .fail(function (e) {
                    console.log(e);
                });
        }

        $("#ir_table").on('click', '.ir-detail-btn', function () {
            let ir_id = $(this).data('ir-id');
            $("#ir_details_id").val(ir_id);
            // console.log(ir_id);
            load_report(ir_id);
            $("#ir_back_btn").attr('data-bs-target', '#inspection_report_modal');

            $("#inspection_report_modal").modal('hide');
            $("#ir_details_modal").modal('show');
        });

        $("#addModal").on('click', "#show_ir_details", function () {
            $("#ir_back_btn").attr("data-bs-target", "#addModal");
        })

        $("#inspection_select_modal").on('change', '#no_treatment_history', function () {
            let checked = $(this).is(':checked');
            $(".existing-pc-form").prop('disabled', checked);
        });

        $(document).on('submit', '#ir_edit_form', function (e) {
            e.preventDefault();
            console.log($(this).serializeArray());

            $.ajax({
                method: 'POST',
                data: $(this).serialize() + "&modify_ir=true",
                dataType: 'json',
                url: submitUrl
            })
                .done(function (d) {
                    console.log(d);
                    $("#ir_edit_confirm").modal('hide');
                    toggle_ir();
                    show_toast(d.success);
                    load_report($("#ir_details_id").val());
                    $("#ir_details_modal").modal('show');
                    $("#ir_modify_alert").text(d.success).fadeIn().delay(5000).fadeOut();
                })
                .fail(function (e) {
                    $("#ir_modify_alert").text(e.responseText).fadeIn().delay(5000).fadeOut();
                    console.log(e);
                })
        });

        $("#inspection_report_modal").on('click', '.ir-delete-btn', function () {
            let id = $(this).data('ir-id-delete');
            $("#ir_delete_id").val(id);
            $("#inspection_report_modal").modal('hide');
            $("#ir_delete_modal").modal('show');
        });

        $(document).on('submit', '#delete_ir_form', function (e) {
            e.preventDefault();
            console.log($(this).serializeArray());

            $.ajax({
                method: 'POST',
                data: $(this).serialize() + "&delete_ir=true",
                dataType: 'json',
                url: submitUrl
            })
                .done(function (d) {
                    console.log(d);
                    $("#ir_delete_modal").modal('hide');
                    show_toast(d.success);
                    $(this)[0].reset();
                    $("#inspection_report_modal").modal('show');
                })
                .fail(function (e) {
                    console.log(e);
                    $("#ir_delete_alert").text(e.responseText).fadeIn().delay(5000).fadeOut();
                })
        });
    </script>

</body>

</html>