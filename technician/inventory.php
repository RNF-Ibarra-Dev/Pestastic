<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician - Inventory</title>
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
            <div class="hstack gap-3 mt-5 mx-4">
                <input class="form-control form-custom me-auto p-2 text-light" type="search" placeholder="Search . . ."
                    id="searchbar" name="searchforafuckingchemical" autocomplete="one-time-code">
                <div class="vr"></div>
                <button type="button" id="loadChem" class="btn btn-sidebar text-light py-3 px-4" data-bs-toggle="modal"
                    data-bs-target="#addModal"><i class="bi bi-plus-square"></i></button>
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
                            <!-- <th>Actions</th> -->
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
        const pagination = 'contents/inv.pagination.php';
        $(document).ready(async function(){
            await loadpage();
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
            console.log(currentpage);
            window.history.pushState(null, "", "?page=" + currentpage);
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
    </script>

    <?php include('footer.links.php'); ?>
</body>

</html>