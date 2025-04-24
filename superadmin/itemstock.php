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
    <title>Manager - Chemical Stock</title>
    <!-- <link rel="stylesheet" href="../../css/style.css"> -->
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
            <div class="hstack gap-3 mt-5 mx-4">
                <input class="form-control form-custom me-auto p-2 text-light" type="search" placeholder="Search . . ."
                    id="searchbar" name="searchforafuckingchemical" autocomplete="one-time-code">
                <button type="button" id="approvemulti" class="btn btn-sidebar py-3 px-4 text-light"
                    data-bs-toggle="tooltip" title="Multiple Approval"><i class="bi bi-list-check"></i></button>
                <div class="vr"></div>
                <button type="button" id="loadChem" class="btn btn-sidebar text-light py-3 px-4" data-bs-toggle="modal"
                    data-bs-target="#addModal" data-bs-toggle="tooltip" title="Add Stock"><i class="bi bi-plus-square"></i></button>
            </div>

            <!-- edit chemical -->
            <form id="editChemForm">
                <div class="row g-2 text-dark">
                    <div class="modal fade text-dark modal-edit" id="editModal" tabindex="-1" aria-labelledby="edit"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Edit Chemical Details</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <!-- <input type="hidden" name="edit" value="edit-chemical"> -->
                                    <input type="hidden" name="id" id="id" class="form-control">
                                    <div class="row mb-2">

                                        <div class="col-lg-6 mb-2">
                                            <label for="name" class="form-label fw-light">Chemical Name</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                autocomplete="off">
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label for="chemBrand" class="form-label fw-light">Chemical Brand</label>
                                            <input type="text" name="chemBrand" id="chemBrand" class="form-control"
                                                autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-6 mb-2">
                                            <label for="chemLevel" class="form-label fw-light">Chemical Level </label>
                                            <input type="text" name="chemLevel" id="chemLevel" class="form-control">
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label for="expDate" class="form-label fw-light">Expiry Date</label>
                                            <input type="date" name="expDate" id="expDate" min="2025-01-01"
                                                class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-grad" DISABLED-id="submitEdit"
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
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control" id="pwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-1 w-50 mx-auto my-0 visually-hidden'
                                    id="incPass"></p>
                                <!-- <div id="passwordHelpBlock" class="form-text">
                                Note: deletion of chemicals are irreversible.
                            </div> -->
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
                    <div class="modal fade text-dark modal-edit" id="addModal" tabindex="-1" aria-labelledby="create"
                        aria-hidden="true">
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
                                        <div class="col-lg-6 mb-2">
                                            <label for="name" class="form-label fw-light">Chemical Name</label>
                                            <input type="text" name="name" id="add-name" class="form-control form-add"
                                                autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label for="chemBrand" class="form-label fw-light">Chemical Brand</label>
                                            <input type="text" name="chemBrand" id="add-chemBrand"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-6 mb-2">
                                            <label for="chemLevel" class="form-label fw-light">Chemical Level </label>
                                            <input type="text" name="chemLevel" id="add-chemLevel"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label for="expDate" class="form-label fw-light">Expiry Date</label>
                                            <input type="date" name="expDate" id="add-expDate" min="2025-01-01"
                                                class="form-control form-add">
                                            <div id="passwordHelpBlock" class="form-text">
                                                Note: specify expiry date or default date will be set.
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-center alert alert-info w-75 mx-auto visually-hidden"
                                        id="emptyInput"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-grad" disabled-id="submitAdd"
                                        data-bs-toggle="modal" data-bs-target="#confirmAdd">Proceed & Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- add confirmation -->
                <div class="modal fade text-dark modal-edit" id="confirmAdd" tabindex="0" aria-labelledby="confirmAdd"
                    aria-hidden="true">
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
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control" id="addPwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-1 w-50 mx-auto my-0 visually-hidden'
                                    id="add-incPass"></p>
                                <!-- <div id="passwordHelpBlock" class="form-text">
                                Note: deletion of chemicals are irreversible.
                            </div> -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#addModal"
                                    data-bs-toggle="modal">Go back</button>
                                <button type="submit" class="btn btn-grad" id="submitAdd" disabled-id="edit-confirm"
                                    disabled-name="delete">Add New Chemical</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- delete modal -->
            <form id="deleteForm">
                <div class="modal fade text-dark modal-edit" id="deleteModal" tabindex="0"
                    aria-labelledby="verifyChanges" aria-hidden="true">
                    <input type="hidden" id="idForDeletion" name="id">
                    <input type="hidden" id="delChemId" name="chemid">
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
                                        delete this product? Enter manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control" id="manPass">
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
                                <button type="button" class="btn btn-grad" id="delsub" name="delete">Delete
                                    Chemical</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- tabble -->
            <div class="table-responsive-sm d-flex justify-content-center">
                <table class="table align-middle table-hover m-4 os-table w-100 text-light">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">Name</th>
                            <th>Brand</th>
                            <th>Current Level</th>
                            <th>Expiry Date</th>
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

    </div>


    <script>
       
        $(document).ready(async function () {
            // get_data();
            // get_id();
            get_sa_id();
            await loadpage(1);
        });

        async function loadpagination(pageno) {
            try {
                return $.ajax({
                    type: 'GET',
                    url: 'tablecontents/pagination.php',
                    data: {
                        pagenav: 'true',
                        active: pageno
                    },
                    success: async function (res) {
                        $('#pagination').empty();
                        $('#pagination').append(res);
                        // set active page
                        // $(`.page-item:nth-child(${pageno + 2})`).addClass('active');

                        // load corresponding table to page. 
                        // await loadtable(pageno);

                        // set url param
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
                    url: 'tablecontents/pagination.php',
                    data: {
                        table: 'true',
                        // sends the current page no.
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
            console.log(currentpage);

            // $('#chemicalTable').empty();
            window.history.pushState(null, "", "?page=" + currentpage);
            // await loadtable(currentpage);

            // $('#pagination').empty();
            // await loadpagination(currentpage);
            await loadpage(currentpage);
        })

        async function loadpage(page) {
            await loadtable(page);
            await loadpagination(page);
        }


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
                            url: 'tablecontents/chemicals.php',
                            type: 'GET',
                            dataType: 'html',
                            data: {
                                search: search
                            },
                            success: async function (searchChem, status) {
                                if (!search == '') {
                                    $('#chemicalTable').empty();
                                    // $('#loader').addClass('visually-hidden');
                                    $('#loader').attr('style', 'display: none !important;');
                                    $('#chemicalTable').append(searchChem);
                                } else {
                                    // $('#chemicalTable').empty();
                                    // $('#loader').addClass('visually-hidden');
                                    $('#loader').attr('style', 'display: none !important;');
                                    await loadpage(1);
                                    // $('#chemicalTable').append(load);
                                    // console.log(status);
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
            $.post('tablecontents/chemicals.php', {
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
        $(function () {
            $('#editChemForm').on('submit', async function (e) {
                e.preventDefault();
                try {
                    const data = await $.ajax({
                        type: 'POST',
                        url: 'tablecontents/chemicals.php',
                        data: $(this).serialize() + '&action=edit',
                        dataType: 'json'
                    });
                    if (data.success) {
                        // console.log(data.success);
                        $("#chemicalTable").empty();
                        // const theFuckingData = await get_data();
                        // $('#chemicalTable').append(theFuckingData);
                        await loadpage(1);
                        $('#confirmEdit').modal('hide');
                        // $('#tableAlert').removeClass('visually-hidden').html(data.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        $('#tableAlert').css('display', 'block').html(data.success).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        $('#editChemForm')[0].reset();

                        // get_data().then(function () {
                        //     $('#editModal').modal('hide');
                        // })
                    }
                } catch (error) {
                    // responseJSON converts the error data passed into JSON?
                    switch (error.responseJSON.type) {
                        case 'update':
                            // console.log(error.responseJSON.pwd);
                            $('input#pwd').addClass('border border-warning-subtle').fadeIn(400);
                            $('#incPass').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(1500).fadeOut(1000);
                            break;
                        case 'pwd':
                            // console.log(error.responseJSON.pwd);
                            $('input#pwd').addClass('border border-danger-subtle').fadeIn(400);
                            $('#incPass').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(1500).fadeOut(1000);
                            break;
                        case 'emptyinput':
                            // console.log(error.responseJSON.pwd);
                            $('input#pwd').addClass('border border-danger-subtle').fadeIn(400);
                            $('#incPass').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(1500).fadeOut(1000);
                            break;
                        default:
                            alert('unknown error.');
                            break;
                    }
                    // console.log(error.responseJSON.wrongPwd);
                    // $('input#pwd').addClass('border border-danger-subtle').fadeIn(400);
                    // $('#incPass').removeClass('visually-hidden').html(error.responseJSON.wrongPwd).hide().fadeIn(400);
                    // console.log(error.responseJSON.error, error.status, error.statusText, error.responseText);
                }


            })

        })

        async function delete_chem(id, pwd, chemId) {
            // var saID = $('#idForDeletion').val();
            // var saPass = $('#manPass').val();
            // var chemID = $('#delChemId').val();
            // console.log(chemID);
            try {
                const del = await $.ajax({
                    type: 'POST',
                    url: 'tablecontents/chemicals.php',
                    data: {
                        action: 'delete',
                        saID: id,
                        pass: pwd,
                        chemID: chemId,
                        // $('#addForm').serialize();
                    },
                    dataType: 'json'
                    // ,success: function (response) {
                    //     console.log(response);
                    //     $('#chemicalTable').empty();
                    //     get_data();
                    //     $('#deleteModal').modal('hide');
                    // }
                });

                if (del && del.success) {
                    $('#chemicalTable').empty();
                    await loadpage(1);
                    $('#deleteModal').modal('hide');
                    $('#deleteForm')[0].reset();
                }
            } catch (error) {
                switch (error.responseJSON.type) {
                    case 'delete':
                        console.log(error.responseJSON.error);
                        $('input#manPass').addClass('border border-warning').fadeIn(400);
                        $('#del-emptyInput').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    case 'pwd':
                        console.log(error.responseJSON.error);
                        $('input#manPass').addClass('border border-warning').fadeIn(400);
                        $('#del-emptyInput').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    case 'empty':
                        console.log(error.responseJSON.error);
                        $('input#manPass').addClass('border border-warning').fadeIn(400);
                        $('#del-emptyInput').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    default:
                        alert('unknown error.');
                        break;
                }
            }
        }

        // delete item
        $(document).on('click', '#delbtn', async function () {
            $('#deleteForm')[0].reset();
            get_sa_id();
            var chemID = $(this).data('id');
            // $('#manPass').prop('autocomplete', 'new-password');
            // $('#manPass').disableAutoFill();
            // $('#delChemId').val(chemID);
            var saID = $('#idForDeletion').val();
            $('#delsub').off('click').on('click', async function () {
                try {
                    var saPass = $('#manPass').val();
                    console.log(chemID + saID + saPass);
                    await delete_chem(saID, saPass, chemID);
                } catch (error) {
                    alert('Submit error. Try again.');
                    // alert(error);
                }
            })

        })

        $('#addForm').on('submit', async function (e) {
            e.preventDefault();
            // var chemId = $('#add-id').val();
            // var name = $('#add-name').val();
            // var brand = $('#add-chemBrand').val();
            // var level = $('#add-chemLevel').val();
            // var expdate = $('#add-expDate').val();

            // e.preventDefault();
            try {
                const add = await $.ajax({
                    type: 'POST',
                    url: 'tablecontents/chemicals.php',
                    data: $(this).serialize() + '&action=add',
                    dataType: 'json'
                });
                // console.log('heelk');
                if (add && add.success) {
                    $('#chemicalTable').empty();
                    await loadpage(1);
                    $('#confirmAdd').modal('hide');
                    // $('#tableAlert').removeClass('visually-hidden').html(add.success).hide().fadeIn(400).delay(2000).fadeOut(1000).addClass('visually-hidden');
                    $('#tableAlert').css('display', 'block').html(data.success).hide().fadeIn(400).delay(2000).fadeOut(1000);

                    $('#addForm')[0].reset();
                    // success yah
                }
                // console.log('rire');

            } catch (error) {
                switch (error.responseJSON.type) {
                    case 'addFailed':
                        console.log(error.responseJSON.error);
                        $('input.form-add').addClass('border border-warning').fadeIn(400);
                        $('#addPwd').addClass('border border-warning').fadeIn(400);
                        $('#emptyInput').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        $('#add-incPass').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    case 'emptyInput':
                        console.log(error.responseJSON.error);
                        $('input.form-add').addClass('border border-warning').fadeIn(400);
                        $('#addPwd').addClass('border border-warning').fadeIn(400);
                        $('#emptyInput').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        $('#add-incPass').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    case 'addFailed':
                        console.log(error.responseJSON.error);
                        $('input.form-add').addClass('border border-warning').fadeIn(400);
                        $('#emptyInput').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    // confirmation modal part:
                    case 'wrongPwd':
                        console.log(error.responseJSON.error);
                        $('#addPwd').addClass('border border-warning').fadeIn(400);
                        $('#add-incPass').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    case 'emptyPwd':
                        console.log(error.responseJSON.error);
                        $('#addPwd').addClass('border border-warning').fadeIn(400);
                        $('#add-incPass').removeClass('visually-hidden').html(error.responseJSON.error).hide().fadeIn(400).delay(2000).fadeOut(1000);
                        break;
                    default:
                        alert('unknown error.');
                        break;
                }
            }
        })

        // get specific chemical information when edit btn is clicked
        $(document).on('click', '#editbtn', function () {
            var chemId = $(this).data('id');
            var name = $(this).data('name');
            var brand = $(this).data('brand');
            var level = $(this).data('level');
            var expdate = $(this).data('expdate');

            $('#id').val(chemId);
            $('#name').val(name);
            $('#chemBrand').val(brand);
            $('#chemLevel').val(level);
            $('#expDate').val(expdate);
        })

    </script>
    <?php include('footer.links.php'); ?>
</body>

</html>