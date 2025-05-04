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
                <div class="row row-cols-1 row-cols-md-3 g-4 mt-2 mb-4 px-4" id="cardcontainer">
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
                                            <p class="text-muted fw-light">If left blank, this will automatically set to 'Unavailable'.</p>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-6 mb-2">
                                            <label for="desc" class="form-label fw-light">Equipment Description
                                            </label>
                                            <textarea name="desc" id="desc" class="form-control" placeholder="You can add more equipment information here . . ."></textarea>
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
            <form id="editequipment" enctype="multipart/form-data">
                <div class="row g-2 text-dark">
                    <div class="modal modal-lg fade text-dark modal-edit" id="editModal" tabindex="-1"
                        aria-labelledby="create" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-modal-title text-light">
                                    <h1 class="modal-title fs-5">Edit Equipment</h1>
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
                                            <p class="text-muted fw-light">If left blank, this will automatically set to 'Unavailable'.</p>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-6 mb-2">
                                            <label for="desc" class="form-label fw-light">Equipment Description
                                            </label>
                                            <textarea name="desc" id="desc" class="form-control" placeholder="You can add more equipment information here . . ."></textarea>
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

        </main>

    </div>
    <script>
        const dataUrl = "tablecontents/equipments.data.php";
        const submitUrl = "tablecontents/equipments.config.php";

        $(document).on('click', '#editbtn', async function(){
            let eid = $(this).data('id');
            console.log(eid);
            $('#editModal').modal('show');
        });


        $(document).ready(async function () {
            await load();
        });

        async function load(){
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
                }
            } catch (error) {
                console.log(error);
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
                    console.log(add.success);
                    await load();
                    $('#confirmAdd').modal('hide');
                }
            } catch (error) {
                console.log(error);
            }
        });

        function altimg(id) {
            $(`#${id}`).attr('src', '../img/template.jpg');
        }
    </script>
    <?php include('footer.links.php'); ?>
</body>

</html>