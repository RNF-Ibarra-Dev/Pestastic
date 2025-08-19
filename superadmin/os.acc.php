<?php
require_once("startsession.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Operation Supervisor Accounts</title>
    <?php
    include("header.links.php");
    ?>
</head>

<body class="bg-official text-light">

    <div class="sa-bg container-fluid p-0 min-vh-100 d-flex">
        <!-- sidebar -->
        <?php
        include("sidenav.php");
        ?>
        <!-- main/side content -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php
            include("navbar.php");
            ?>

            <!-- manage account content -->
            <div class="bg-light bg-opacity-25 rounded p-3 mx-3 my-3 user-select-none">
                <h1 class="fs-1 text-center fw-bold text-light">Operation Supervisors Accounts</h1>
            </div>
            <div class="hstack gap-2 mt-2 mx-3">
                <select
                    class="form-select select-transparent bg-light bg-opacity-25 py-2 border-0 h-100 text-light w-25"
                    id="sortbranches" aria-label="Default select example">
                </select>
                <input class="form-control form-custom me-auto py-2 align-middle px-3 rounded-pill text-light"
                    type="search" placeholder="Search account . . ." id="searchbar" autocomplete="one-time-code">
            </div>
            <div class="table-responsive-sm d-flex justify-content-center">
                <table class="table align-middle m-3 table-hover os-table w-100 text-light">
                    <caption class="text-light">List of all active operation supervisors. To create an account, proceed
                        to 'Create Account'
                        page. </caption>
                    <thead>
                        <tr class="text-center">
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Username</th>
                            <th scope="col">Employee ID</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="os_table">

                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mb-5 visually-hidden" id="loader">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div id="os_pagination"></div>


            <!-- form -->
            <form id="editform" class="row g-2 text-dark">
                <input type="hidden" name="id" id="id_input">
                <div class="modal fade text-dark modal-edit" id="editModal" tabindex="-1" aria-labelledby="tech-"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="tech-">Edit
                                    <span id="display_name"></span>'s
                                    Information
                                </h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi text-light bi-x"></i></button>

                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <div class="col-lg-6 mb-2">
                                        <label for="fname" class="form-label fw-light">First
                                            Name</label>
                                        <input type="text" name="fname" class="form-control" id="fname" required>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label for="lname" class="form-label fw-light">Last
                                            Name</label>
                                        <input type="text" name="lname" class="form-control" id="lname" required>
                                    </div>
                                </div>
                                <div class="invalid-feedback mb-2">
                                    Should not contain numbers and any special characters.
                                </div>

                                <div class="row mb-2">
                                    <div class="col-lg-4 mb-2">
                                        <label for="usn" class="form-label fw-light">Username</label>
                                        <input type="text" name="usn" class="form-control" id="usn" required>
                                    </div>

                                    <div class="col-lg-8 mb-2">
                                        <label for="email" class="form-label fw-light">Email
                                            Address</label>
                                        <input type="email" name="email" class="form-control" id="email" required>
                                        <div class="form-text">
                                            Please choose a valid email.
                                        </div>
                                    </div>
                                </div>
                                <h1 class="fs-5 mb-3">Additional Information</h1>
                                <div class="row mb-2">
                                    <div class="col-lg-4 mb-2">
                                        <label for="contact-number" class="form-label fw-light">Contact
                                            Number</label>
                                        <input type="number" name="contactNo" class="form-control" id="contact-number"
                                            required>
                                    </div>

                                    <div class="col-lg-4 mb-2">
                                        <label for="emp-id" class="form-label fw-light">Employee
                                            ID</label>
                                        <input type="number" name="empId" class="form-control" id="emp-id" required>
                                    </div>

                                    <div class="col-lg-4 mb-2">
                                        <label for="birthdate" class="form-label fw-light">Birthdate</label>
                                        <input type="date" name="birthdate" class="form-control" id="birthdate"
                                            required>
                                    </div>
                                </div>
                                <div class="mb-2 col">
                                    <label for="address" class="form-label fw-light">Address</label>
                                    <input type="text" name="address" class="form-control" id="address" required>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-6 mb-2">
                                        <label for="pass" class="form-label fw-light">Password</label>
                                        <input type="password" name="pass" class="form-control" id="pass">
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="passrepeat" class="form-label fw-light">Repeat
                                            Password</label>
                                        <input type="password" name="pwdRepeat" class="form-control" id="passrepeat">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    To use the same password, kindly leave the password forms blank.
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-grad" data-bs-target="#modalVerify"
                                    data-bs-toggle="modal">Proceed</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- verification modal (input SA/Manager password) -->
                <div class="modal fade text-dark modal-edit" id="modalVerify" tabindex="0"
                    aria-labelledby="verifyChanges" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyChanges">Verify Password</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">

                                <div class="row mb-2">
                                    <div class="col-lg-6 mb-2">
                                        <label for="pass" class="form-label fw-light"> Manager
                                            <?= $_SESSION['saUsn'] ?>'s Password</label>
                                        <input type="password" name="saPwd" class="form-control" id="pass">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    Please verify using the manager account password
                                    to proceed.
                                </div>
                                <p class="alert alert-info py-2 text-center mt-2 fw-medium w-75 mx-auto mb-0"
                                    id="editAlert" style="display: none"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                    data-bs-target="#editModal">Go back</button>
                                <button type="submit" class="btn btn-grad" name="submit-os-edit">Save
                                    changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form action="../includes/deleteAcc.inc.php" method="post" id="deleteform">
                <!-- delete modal -->
                <div class="modal fade text-dark modal-edit" id="deleteModal" tabindex="0"
                    aria-labelledby="verifyChanges" aria-hidden="true">
                    <input type="hidden" name="id" id="del_input_id">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5" id="verifyChanges">Account Deletion</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="pass" class="form-label fw-light"> Are you sure you want to
                                        delete this account? Enter manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control" id="pass">
                                    </div>
                                </div>
                                <div id="passwordHelpBlock" class="form-text">
                                    Note: deletion of accounts are irreversible. Proceed with caution.
                                </div>
                                <p class="alert alert-info py-2 text-center mt-2 fw-medium w-75 mx-auto mb-0"
                                    id="deleteAlert" style="display: none"></p>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-grad" name="deleteOS">Save
                                    changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </main>
    </div>
    <div class="toast-container m-2 me-3 bottom-0 end-0 position-fixed">
        <div class="toast align-items-center" role="alert" id="toast" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body text-dark ps-4 text-success-emphasis" id="toastmsg">
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php
    include("footer.links.php");
    ?>
    <script>
        function show_toast(message) {
            $('#toastmsg').html(message);
            var toastid = $('#toast');
            var toast = new bootstrap.Toast(toastid);
            toast.show();
        }
        const dataurl = "tablecontents/os.data.php";

        $(document).ready(async function () {
            await load_page();
        });

        async function get_table(cur_page = 1, branch = null) {
            $.get("tablecontents/os.table.pagination.php", {
                ostable: true,
                currentpage: cur_page,
                branch: branch
            }, function (d) {
                $("#os_table").empty();
                $("#os_table").append(d);
            }, 'html')
                .fail(function (e) {
                    console.error(e);
                    $("#os_table").html("<tr><td scope='row' colspan='5' class='text-center'>AJAX Error.</td></tr>")
                });
        }

        async function get_pagination(cur_page = 1, branch = null) {
            $.get("tablecontents/os.table.pagination.php", {
                pagenav: true,
                branch: branch,
                active: cur_page
            }, function (d) {
                $("#os_pagination").empty();
                $("#os_pagination").append(d);
            }, 'html')
                .fail(function (e) {
                    console.error(e);
                    $("#os_pagination").html("<p class='text-center fw-bold'>Warning: Failed at fetching pagination buttons</p>");
                });
        }

        async function load_page(curpage = 1, branch = null) {
            try {
                await get_table(curpage, branch);
                await get_pagination(curpage, branch);
            } catch (error) {
                console.error(error);
            }
        }

        $("#os_pagination").on('click', '.page-link', async function (e) {
            e.preventDefault();
            let branch = $("#sortbranches").val();
            let currentpage = $(this).data('page');
            await load_page(currentpage, branch);
        })

        $(document).ready(function () {
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

        $(document).on('change', '#sortbranches', function () {
            $("#searchbar").val('');
            let branch = $(this).val();
            load_page(1, branch);
        });

        $(function () {
            let delay = null;

            $('#searchbar').keyup(function () {
                clearTimeout(delay);
                $('#os_table').empty();
                $('#loader').removeClass('visually-hidden');

                delay = setTimeout(async function () {
                    var search = $('#searchbar').val();
                    let branch = $("#sortbranches").val();
                    try {
                        const searchtransaction = await $.ajax({
                            url: 'tablecontents/os.table.pagination.php',
                            type: 'GET',
                            dataType: 'html',
                            data: {
                                search: search,
                                branch: branch
                            }
                        });
                        if (searchtransaction) {
                            if (!search == '') {
                                $('#os_table').empty();
                                $('#loader').addClass('visually-hidden');
                                $('#os_table').append(searchtransaction);
                                $('#pagination').empty();
                            } else {
                                $('#loader').addClass('visually-hidden');
                                await load_page(1, branch);
                            }
                        }
                    } catch (error) {
                        console.log(error);
                    }
                }, 250);
            });

        });

        const today = new Date();
        const maxAllowedDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
        birthdate = flatpickr($("#birthdate"), {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            enableTime: true,
            // maxDate: maxAllowedDate
        });

        $("#os_table").on('click', '.edit-btn', function () {
            let id = $(this).data('edit');
            console.log(id);
            $.get(dataurl, {
                accountinfo: true,
                id: id
            }, function (d) {
                console.log(d);
                $("#id_input").val(d.baID);
                $("#fname").val(d.baFName);
                $("#lname").val(d.baLName);
                $("#usn").val(d.baUsn);
                $("#email").val(d.baEmail);
                $("#contact-number").val(d.baContact);
                $("#address").val(d.baAddress);
                $("#emp-id").val(d.baEmpId);

                birthdate.setDate(d.baBirthdate);

                $("#editModal").modal('show');
            }, 'json')
                .fail(function (e) {
                    console.log(e);
                });
        });

        $(document).on('submit', '#editform', function (e) {
            e.preventDefault();
            const data = $(this).serialize();
            console.log(data);
            $.ajax({
                method: 'POST',
                url: "../includes/editAcc.inc.php",
                data: data + "&configure_os=true",
                dataType: 'json'
            })
                .done(function (d) {
                    $("#modalVerify").modal('hide');
                    show_toast(d.success);
                })
                .fail(function (e) {
                    console.error(e);
                    $("#editAlert").text(e.responseText).fadeIn().delay(3000).fadeOut(2000);
                })
        });

        $("#os_table").on('click', '.delete-btn', function () {
            let id = $(this).data('delete');
            $("#delete_input_id").val(id);
            $("#deleteModal").modal('show');
        });

        $(document).on('submit', '#deleteform', function (e) {
            e.preventDefault();
            const data = $(this).serialize();
            $.ajax({
                method: 'POST',
                url: "../includes/deleteAcc.inc.php",
                data: data + "&deleteOS=true",
                dataType: 'json'
            })
                .done(function (d) {
                    $("#deleteModal").modal('hide');
                    show_toast(d.success);
                })
                .fail(function (e) {
                    console.error(e);
                    $("#deleteAlert").text(e.responseText).fadeIn().delay(3000).fadeOut(2000);
                })
        });
    </script>
</body>

</html>