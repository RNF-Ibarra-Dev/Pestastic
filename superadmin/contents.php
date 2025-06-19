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
                        <table class="table align-middle table-hover p-2 w-100">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">
                                        <input type="checkbox" onclick="" class="form-check-input">
                                    </th>
                                    <th scope="col">Treatment</th>
                                    <th scope="col">Branch</th>
                                </tr>
                            </thead>
                            <tbody id="treatment">
                            </tbody>
                        </table>
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
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="trtmnt_mdl" tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Add Treatment</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                               shit
                            </div>
                            <div id="passwordHelpBlock" class="form-text">
                                Note: Approving stock entries will officially make the stocks a part of the
                                inventory.
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto visually-hidden" id="approve-errmsg">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                            <button type="button" data-bs-toggle="modal" class="btn btn-grad" data-bs-target="#cnfrm_trtmnt">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade text-dark modal-edit" data-bs-backdrop="static" id="cnfrm_trtmnt" tabindex="0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-modal-title text-light">
                            <h1 class="modal-title fs-5">Add Treatment</h1>
                            <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x text-light"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                               
                            </div>
                            <div id="passwordHelpBlock" class="form-text">
                                Note: Approving stock entries will officially make the stocks a part of the
                                inventory.
                            </div>
                            <p class="text-center alert alert-info w-75 mx-auto visually-hidden" id="approve-errmsg">
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-toggle="modal" data-bs-target="#trtmnt_mdl">Go Back</button>
                            <button type="submit" class="btn btn-grad">Fuck and submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <?php include('footer.links.php'); ?>
    <script>
        const dataurl = "tablecontents/contents.data.php";

        async function append(container) {
            $.get(dataurl, { append: container })
                .done(function (d) {
                    $(`#${container}`).append(d);
                })
                .fail(function (e) {
                    console.log(e.responseText);
                })
        }

        $(document).ready(async function () {
            await append('treatment');
        });
    </script>
</body>

</html>