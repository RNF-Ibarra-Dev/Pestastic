<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician - Inventory</title>
    <?php include('header.links.php'); ?>

    <style>
        select option {
            background: rgb(17 71 84);
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

            <div class="hstack gap-3 mt-3 mx-4">
                <input class="form-control form-custom me-auto p-2 text-light" type="search"
                    placeholder="Search transactions. . ." id="searchbar" autocomplete="one-time-code">
                <div class="vr"></div>
                <select class="form-select select-transparent text-light w-25"
                    style="background: transparent !important;" id="sortstatus" aria-label="Default select example">
                    <option value='' selected>Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Completed">Completed</option>
                    <option value="Voided">Voided</option>
                </select>
                <button type="button" id="logtransbtn" class="btn btn-sidebar text-light py-3 px-4"
                    data-bs-toggle="modal" data-bs-target="#logtransactions"><i class="bi bi-plus-square"></i></button>
            </div>

            <!-- table -->
            <div class="table-responsive-sm d-flex justify-content-center">
                <table class="table text-center align-middle table-hover m-4 os-table w-100 text-light">
                    <thead>
                        <tr>
                            <th scope="col">Transaction ID</th>
                            <th>Treatment Date</th>
                            <th>Treatment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="inventorytable">
                        <!-- ajax chem table -->
                    </tbody>
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


            <!-- details modal -->
            <div class="modal modal-lg fade text-dark modal-edit" id="details" tabindex="-1" aria-labelledby="create"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Transaction Details</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                    class="bi text-light bi-x"></i></button>
                        </div>
                        <div class="modal-body">
                            <!-- <p class="fs-6 fw-light text-muted">Note that the 
                            </p> -->
                            <div class="row mb-2">
                                <div class="col-lg-6 mb-2">
                                    <label for="transId" class="form-label fw-light">Transaction ID
                                    </label><br>
                                    <span id="transId" class="fw-light"></span>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <label for="customer" class="form-label fw-light">Customer Name
                                    </label><br>
                                    <span id="customer" class="fw-light"></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="dropdown-center col-lg-6 mb-2">
                                    <label for="technicians" class="form-label fw-light">Technicians
                                    </label>
                                    <ul class="list-group list-group-flush" id="technicians"></ul>
                                </div>
                            </div>

                            <hr class="my-2">

                            <div class="row mb-2">
                                <div class="col-lg-6 mb-2">
                                    <label for="date" class="form-label fw-light">Treatment
                                        Date</label><br>
                                    <span class="fw-light" id="date"></span>
                                </div>

                                <div class="col-lg-6 mb-2">
                                    <label for="treatment" class="form-label fw-light">Treatment</label><br>
                                    <span class="fw-light" id="treatment"></span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-12 mb-2">
                                    <label for="pestproblems" class="form-label fw-light">Pest
                                        Problem</label>
                                    <ul class="list-group list-group-flush" id="pestproblems"></ul>

                                </div>
                            </div>

                            <div class="row mb-2" did="row">
                                <div class="col-lg-6 mb-2">
                                    <label for="chemical" class="form-label fw-light">Chemical
                                        Used</label><br>
                                    <ul class="list-group list-group-flush" id="chemical"></ul>
                                </div>
                            </div>

                            <p class="fw-light text-muted mt-2">If this transaction is marked as pending, the Operations
                                Supervisor or the Manager has not approved it yet. Bear with us.</p>

                            <p class="mb-0 mt-4" id='metadata'><small id=time class="text-muted"></small>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close Transaction
                                Details</button>
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
                    $('#inventorytable').empty();
                    $('#inventorytable').append(table);
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

        $('#pagination').on('click', '.page-link', async function(e) {
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

        async function loadpage(defpage = 1, status = '') {
            await load_table(defpage, status);
            await load_pagination_buttons(defpage, status);

        }

        // console.log(status);

        $("#sortstatus").on('change', async function() {
            let status = $("#sortstatus option:selected").val();
            $("#searchbar").val('');
            if (status != '') {
                await loadpage(1, status);
            } else{
                await loadpage();
            }
        })


        $(document).ready(async function() {
            await loadpage();
        });

        // modal related data fetch

        $(document).on('click', '#logtransbtn', async function() {
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

        $(document).on('click', '#addMoreTech', async function() {
            await add_row('addTechContainer', 'tech');
        })
        $(document).on('click', '#addMoreChem', async function() {
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

        $(document).on('click', '#deleterow', async function() {
            let row = $(this).closest('div.row');
            row.remove();
            // $(this).parent().remove();
            console.log('tech row removed');
        })

        $(document).on('click', '#deleteChemRow', async function() {
            let row = $(this).closest('div.row');
            row.remove();
            console.log('chem row removed');
        })

        $('#logtransaction').on('shown.bs.modal', function() {
            $('#add-technicianName').prop('disabled', true);
        })

        // submit log
        const submit = "contents/trans.submit.php";
        $('#logtransaction').on('submit', async function(e) {
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

        $(document).on('click', '#tableDetails', async function(e) {
            e.preventDefault();
            let transId = $(this).data('trans-id');
            await view_details(transId);
        })

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
        $(function() {
            let delay = null;

            $('#searchbar').keyup(function() {
                clearTimeout(delay);
                $('#table').empty();
                $('#loader').removeClass('visually-hidden');

                delay = setTimeout(async function() {
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
                                $('#inventorytable').empty();
                                $('#loader').addClass('visually-hidden');
                                $('#inventorytable').append(searchtransaction);
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
    </script>
</body>

</html>