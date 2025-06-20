<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Contents</title>
    <?php include('header.links.php'); ?>

</head>

<body class="bg-official text-light">

    <div class="sa-bg container-fluid p-0 h-100 d-flex">
        <!-- sidebar -->
        <?php include('settings.sidenav.php'); ?>
        <!-- main content -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php include('navbar.php'); ?>
            <!-- content -->
            <div class="container-fluid h-75 justify-content-center d-flex flex-column">
                <h2 class="fw-bold text-center">Manage Contents</h2>
                <div class="container bg-light bg-opacity-25 rounded border border-light py-4 px-3">
                    <p class="fw-medium fs-5 m-0">Treatments</p>
                    <hr class="mt-1 mb-2 opacity-50">
                    <div class="table-responsive justify-content-center">
                        <table class="table align-middle table-bordered table-hover p-2 w-100">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">
                                        <input type="checkbox" onclick="" class="form-check-input">
                                    </th>
                                    <th scope="col">Treatment</th>
                                    <th scope="col">Branch</th>
                                    <th scope="col"><i class="bi bi-pencil-square"></i></th>
                                </tr>
                            </thead>
                            <form id="trt_del_form">
                                <tbody id="treatment">
                                </tbody>
                            </form>
                        </table>
                        <p class="text-center alert alert-info w-75 mx-auto" style="display: none;" id="trtmnt_table_alert">
                        </p>
                    </div>

                    <p class="fw-medium fs-5 m-0">Problems</p>
                    <hr class="mt-1 mb-2 opacity-50">
                    <form action="" id="problemform">
                        <div id="problems"></div>
                    </form>

                    <p class="fw-medium fs-5 m-0">Branches</p>
                    <hr class="mt-1 mb-2 opacity-50">
                    <form action="" id="branchesform">
                        <div id="branches"></div>
                    </form>

                </div>
            </div>

        </main>

        <!-- modals -->
        <form id="treatment_form">
            <div class="modal modal-lg fade text-dark modal-edit" data-bs-backdrop="static" id="trtmnt_mdl"
                tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Add Treatment</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="trtmnt_input" class="form-label fw-light fs-5">Treatment Name:</label>
                                    <input type="text" id="trtmnt_input" name="treatment" class="form-control" autocomplete="one-time-code">
                                </div>
                                <div class="col">
                                    <label for="trt_addbranch" class="form-label fw-light fs-5">Branch:</label>
                                    <select name="trtmnt_branch" id="trt_addbranch" class="form-select">
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                            <button type="button" data-bs-toggle="modal" class="btn btn-grad"
                                data-bs-target="#cnfrm_trtmnt">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="cnfrm_trtmnt" tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Confirm Adding New Treatment</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <label for="approve-inputpwd" class="form-label fw-light">Enter manager
                                    <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                <div class="col-6 mb-2">
                                    <input type="password" name="trtmnt_pwd" class="form-control">
                                </div>
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                id="trtmnt_alert">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                data-bs-target="#trtmnt_mdl">Go Back</button>
                            <button type="submit" class="btn btn-grad">Add Treatment</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="edit_treatment_form">
            <input type="hidden" name="id" id="trt_edit_id" required>
            <div class="modal modal-lg fade text-dark modal-edit" data-bs-backdrop="static" id="trt_edit_modal"
                tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Edit Treatment</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="trt_edit_input" class="form-label fw-light fs-5">Treatment Name:</label>
                                    <input type="text" id="trt_edit_input" name="treatment" class="form-control" autocomplete="one-time-code">
                                </div>
                                <div class="col">
                                    <label for="trt_editbranch" class="form-label fw-light fs-5">Branch:</label>
                                    <select name="branch" id="trt_editbranch" class="form-select">
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                            <button type="button" data-bs-toggle="modal" class="btn btn-grad"
                                data-bs-target="#trt_edit_confirm">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="trt_edit_confirm" tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Confirm Changes</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <label for="approve-inputpwd" class="form-label fw-light">Enter manager
                                    <?= $_SESSION['saUsn'] ?>'s password to proceed.</label>
                                <div class="col-6 mb-2">
                                    <input type="password" name="pwd" class="form-control">
                                </div>
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto" style="display: none;"
                                id="trtmnt_edit_alert">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-toggle="modal"
                                data-bs-target="#trt_edit_modal">Go Back</button>
                            <button type="submit" class="btn btn-grad">Update Treatment</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <?php include('footer.links.php'); ?>
    <script>
        const dataurl = "tablecontents/contents.data.php";
        const configurl = "tablecontents/contents.config.php";

        async function append(container) {
            return $.get(dataurl, {
                    append: container
                })
                .done(function(d) {
                    $(`#${container}`).empty();
                    $(`#${container}`).append(d);
                    return true;
                })
                .fail(function(e) {
                    console.log(e.responseText);
                })
        }

        $(document).ready(async function() {
            await append('treatment');
            await append('trt_addbranch');

        });

        $(document).on('submit', '#treatment_form', async function(e) {
            e.preventDefault();
            // console.log($(this).serialize());
            $.ajax({
                    url: configurl,
                    dataType: 'json',
                    method: "POST",
                    data: $(this).serialize() + "&add-treatment=true"
                })
                .done(async function(d) {
                    console.log(d);
                    await append('treatment');
                    $('#cnfrm_trtmnt').modal('hide');
                    $('#trtmnt_table_alert').html(d.success).fadeIn(750).delay(5000).fadeOut(2000);
                })
                .fail(function(e) {
                    console.log(e);
                    console.log(e.responseJSON.error);
                    $('#trtmnt_alert').html(e.responseJSON.error).fadeIn(750).delay(5000).fadeOut(2000);
                })
                .always(function(a) {
                    console.log(a);
                })
        });

        // async function get()

        $(document).on('click', '.trt-edit', async function() {
            let id = $(this).data('trt');
            let branch = await append('trt_editbranch');

            $.get(dataurl, {
                    trt_details: id
                })
                .done(async function(d) {
                    let deets = JSON.parse(d);
                    console.log(deets);
                    // alert(deets.t_name);
                    if (branch) {
                        $("#trt_edit_id").val(deets.id)
                        $("#trt_edit_input").val(deets.t_name);
                        $("#trt_editbranch").val(deets.branch);
                        $('#trt_edit_modal').modal('show');
                    }
                })
                .fail(function(e) {
                    alert(e.responseText);
                    console.log('from fail');
                });
        });

        $(document).on('submit', '#edit_treatment_form', async function(e) {
            e.preventDefault();
            console.log($(this).serialize());
            $.ajax({
                    method: 'POST',
                    url: configurl,
                    dataType: 'json',
                    data: $(this).serialize() + "&edit=true"
                })
                .done(function(d) {
                    // alert(d);
                    append('treatment');
                    // console.log(d);
                    $('#trt_edit_confirm').modal('hide');
                    $('#trtmnt_table_alert').html(d.success).fadeIn(750).delay(5000).fadeOut(2000);
                })
                .fail(function(e) {
                    console.log(e);
                    $('#trtmnt_edit_alert').html(e.responseJSON.error).fadeIn(750).delay(5000).fadeOut(2000);
                })
        })
    </script>
</body>

</html>