<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Equipments</title>
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

            <div class="hstack gap-3 mt-4 mx-3">
                <input class="form-control form-custom me-auto p-2 text-light" type="search" placeholder="Search . . ."
                    id="searchbar" name="searchforafuckingchemical" autocomplete="one-time-code">
                <div class="vr"></div>
                <button type="button" id="loadChem" class="btn btn-sidebar text-light py-3 px-4" data-bs-toggle="modal"
                    data-bs-target="#addModal" data-bs-toggle="tooltip" title="Add Stock"><i
                        class="bi bi-plus-square"></i></button>
            </div>

            <div class="container">
                <div class="row row-cols-1 row-cols-md-4 g-4 mt-2 mb-4 px-4" id="cardcontainer">
                    <!-- ajax -->
                </div>
            </div>

            <form id="addequipment" enctype="multipart/form-data">
                <div class="row g-2 text-dark">
                    <div class="modal modal-lg fade text-dark modal-edit" id="addModal" tabindex="-1"
                        aria-labelledby="create" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Add New Equipment</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <p class="fs-6 fw-light">Provide the details of the equipment below.</p>
                                    <div class="row mb-2">
                                        <div class="col-lg-6 mb-2">
                                            <label for="ename" class="form-label fw-light">Equipment Name
                                            </label>
                                            <input type="text" name="eqname" id="ename" class="form-control form-add"
                                                autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-6 dropdown-center mb-2">
                                            <label for="avail" class="form-label fw-light">Set Availability
                                            </label>
                                            <select name="avail" class="form-select" id="avail">
                                                <option value="">----</option>
                                                <option value="Available">Available</option>
                                                <option value="Unavailable">Unavailable</option>
                                                <option value="In Repair">Repair In Progress</option>
                                            </select>
                                            <p class="text-muted fw-light">If left blank, this will automatically set to
                                                'Unavailable'.</p>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-6 mb-2">
                                            <label for="desc" class="form-label fw-light">Equipment Description
                                            </label>
                                            <textarea name="desc" id="desc" class="form-control"
                                                placeholder="You can add more equipment information here . . ."></textarea>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label for="eimg" class="form-label fw-light">Equipment Image
                                            </label>
                                            <input type="file" name="eimage" id="eimg" class="form-control form-add"
                                                autocomplete="one-time-code">
                                            <p class="text-muted fw-light">Only .jpg .jpeg .png format is allowed.</p>
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
                                    <label for="addPwd" class="form-label fw-light">Add new equipment? Enter manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="password" name="saPwd" class="form-control" id="addPwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-3 w-75 mx-auto my-0'
                                    id="addalert" style="display: none;"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#addModal"
                                    data-bs-toggle="modal">Go back</button>
                                <button type="submit" class="btn btn-grad" id="submitAdd">Add equipment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="editequipment" enctype="multipart/form-data">
                <div class="row g-2 text-dark">
                    <div class="modal modal-lg fade text-dark modal-edit" id="editModal" tabindex="-1"
                        aria-labelledby="create" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Edit Equipment Information</h1>
                                    <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                            class="bi text-light bi-x"></i></button>
                                </div>
                                <div class="modal-body">


                                    <p class="fs-6 fw-light">Provide the details of the equipment below.</p>
                                    <div class="row mb-2">
                                        <div class="col-lg-6 mb-2">
                                            <label for="ename" class="form-label fw-light">Equipment Name
                                            </label>
                                            <input type="text" name="eqname" id="edit-ename"
                                                class="form-control form-add" autocomplete="one-time-code">
                                        </div>
                                        <div class="col-lg-6 dropdown-center mb-2">
                                            <label for="avail" class="form-label fw-light">Set Availability
                                            </label>
                                            <select name="avail" class="form-select" id="edit-avail">
                                                <option value="">----</option>
                                                <option value="Available">Available</option>
                                                <option value="Unavailable">Unavailable</option>
                                                <option value="In Repair">Repair In Progress</option>
                                            </select>
                                            <p class="text-muted fw-light">If left blank, this will automatically set to
                                                'Unavailable'.</p>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-6 mb-2">
                                            <label for="desc" class="form-label fw-light">Equipment Description
                                            </label>
                                            <textarea name="desc" id="edit-desc" class="form-control"
                                                placeholder="You can add more equipment information here . . ."></textarea>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label for="eimg" class="form-label fw-light">Equipment Image
                                            </label>
                                            <input type="file" name="eimage" id="eimg" class="form-control form-add"
                                                autocomplete="one-time-code">
                                            <p class="text-muted fw-light">Only .jpg .jpeg .png format is allowed.
                                                Upload only when changing photo is necessary, leave if not.</p>
                                        </div>
                                    </div>
                                    <div id="editspinner" class="text-center align-middle" style="display: none;">
                                        <div class="spinner-border text-dark" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>

                                    <p class="text-center alert alert-info w-75 mx-auto visually-hidden"
                                        id="emptyInput"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                        data-bs-target="#confirmEdit" id="editproceedbtn">Proceed &
                                        Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- edit confirmation -->
                <div class="modal fade text-dark modal-edit" id="confirmEdit" tabindex="0" aria-labelledby="confirmAdd"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Verification</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="addPwd" class="form-label fw-light">Edit equipment information? Enter
                                        manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="hidden" name="eid" id="edit-eid">
                                        <input type="hidden" name="oldimgpath" id="eimg-hidden">
                                        <input type="password" name="saPwd" class="form-control" id="addPwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-3 w-75 mx-auto my-0' style="display: none;"
                                    id="editalert"></p>
                                <div id="passwordHelpBlock" class="form-text">
                                    Note: Changes can not be reverted. Proceed with caution and make sure to double
                                    check.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#editModal"
                                    data-bs-toggle="modal">Go back</button>
                                <button type="submit" class="btn btn-grad" id="submitAdd">Edit equipment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="deleteequipment">
                <div class="modal fade text-dark modal-edit" id="deletemodal" tabindex="0" aria-labelledby="confirmAdd"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-modal-title text-light">
                                <h1 class="modal-title fs-5">Deletion Verification</h1>
                                <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="bi bi-x text-light"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <label for="addPwd" class="form-label fw-light">You will delete equipment '<span
                                            id="eqname-delete">---</span>'. Enter
                                        manager
                                        <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                    <div class="col-lg-6 mb-2">
                                        <input type="hidden" name="eid" id="delete-id">
                                        <input type="hidden" name="eimg" id="delete-img">
                                        <input type="password" name="saPwd" class="form-control" id="addPwd">
                                    </div>
                                </div>
                                <p class='text-center alert alert-info p-3 w-75 mx-auto my-0' style="display: none;"
                                    id="deletealert"></p>
                                <div id="passwordHelpBlock" class="form-text">
                                    Note: Deleting is permanent. Please proceed with caution and make sure to double
                                    check.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-grad" data-bs-target="#deletemodal"
                                    data-bs-toggle="modal">Cancel</button>
                                <button type="submit" class="btn btn-grad">Delete Equipment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
        </main>

    </div>
    <script>
        const dataUrl = "tablecontents/equipments.data.php";
        const submitUrl = "tablecontents/equipments.config.php";

        $(document).on('submit', '#deleteequipment', async function (e) {
            e.preventDefault();
            console.log($(this).serialize());
            try {
                const del = await $.ajax({
                    method: 'POST',
                    url: submitUrl,
                    dataType: 'json',
                    data: $(this).serialize() + '&delete=true'
                });

                if (del.success) {
                    await load();
                    $('#deletemodal').modal('hide');
                    show_toast(del.success);
                }
            } catch (error) {
                let err = error.responseText;
                $('#deletealert').html(err).fadeIn(350).delay(1000).fadeOut(500);
            }
        })


        $(document).on('click', '#deletebtn', async function () {
            const data = $('#deletebtn').data('del');
            $('#eqname-delete').html(data.name);
            $('#delete-id').val(data.id);
            $('#delete-img').val(data.img);
            $('#deletemodal').modal('show');
        });

        function show_toast(message){
            $('#toastmsg').html(message);
            var toastid = $('#toast');
            var toast = new bootstrap.Toast(toastid);
            toast.show();
        }

        $(document).ready(async function () {
            await load();
        });

        async function load() {
            try {
                const load = await $.ajax({
                    method: "GET",
                    url: dataUrl,
                    dataType: 'html',
                    data: {
                        equipments: 'true'
                    }
                });

                if (load) {
                    $('#cardcontainer').empty();
                    $('#cardcontainer').append(load);
                    // show_toast('Table loaded');
                }
            } catch (error) {
                console.log(error);
            }
        }

        $(document).on('submit', '#editequipment', async function (e) {
            e.preventDefault();
            var editdata = new FormData(this);
            editdata.append('edit', 'true');
            for (const data of editdata.entries()) {
                console.log(data[0] + ': ' + data[1]);
            }

            try {
                const edit = await $.ajax({
                    method: 'POST',
                    url: submitUrl,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: editdata
                });
                if (edit) {
                    await load();
                    $('#confirmEdit').modal('hide');
                    show_toast(edit.success);
                }
            } catch (error) {
                $('#editalert').html(error.responseText).fadeIn(350).delay(1000).fadeOut(500);
            }
        })

        $(document).on('click', '#editbtn', async function () {
            let eid = $(this).data('id');
            console.log(eid);
            $('#editequipment')[0].reset();
            $('#editModal').modal('show');
            $('#editproceedbtn').attr('disabled', true);
            $('#editspinner').attr('style', 'display: block !important');
            $('#editModal :input').attr('disabled', true);
            $('#editModal').on('shown.bs.modal', async function () {
                setTimeout(async function () {
                    const modal = await edit_modal(eid);
                    if (modal) {
                        $('#editproceedbtn').attr('disabled', false);
                        $('#editModal :input').attr('disabled', false);
                        $('#editspinner').attr('style', 'display: none !important;');
                    }
                }, 500);
            })
        });

        async function edit_modal(id) {
            try {
                const load = await $.ajax({
                    method: 'GET',
                    url: dataUrl,
                    dataType: 'json',
                    data: {
                        eid: id,
                        editmodal: 'true'
                    }
                });

                if (load) {
                    let data = load.success;
                    $('#edit-eid').val(data.id);
                    $('#edit-ename').val(data.equipment);
                    $(`#edit-avail option[value='${data.availability}']`).attr('selected', true);
                    $('#edit-desc').val(data.description);
                    $('#eimg-hidden').val(data.equipment_image);
                    return true;
                }
            } catch (error) {
                console.log(error.responseText);
            }
        }


        $(document).on('submit', '#addequipment', async function (e) {
            e.preventDefault();
            var adddata = new FormData(this);
            adddata.append('add', 'true');
            // for (const data of img.entries()) {
            //     console.log(data[0], data[1]);
            // }
            // console.log(img);
            try {
                const add = await $.ajax({
                    method: 'POST',
                    url: submitUrl,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: adddata
                });

                if (add) {
                    await load();
                    $('#confirmAdd').modal('hide');
                    show_toast(add.success);
                }
            } catch (error) {
                let err = error.responseJSON.error;
                $('#addalert').html(err).fadeIn(350).delay(1000).fadeOut(500);
            }
        });

        function altimg(id) {
            $(`#${id}`).attr('src', '../img/template.jpg');
        }
    </script>
    <?php include('footer.links.php'); ?>
</body>

</html>