<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Pestastic Queue</title>
    <?php include('header.links.php'); ?>
    <style>
        #queuecontainer {
            overflow-x: auto !important;
            white-space: nowrap !important;
            -webkit-overflow-scrolling: touch !important;
        }

        #cardcontainer>.col {
            flex: 0 0 auto !important;
            width: 24rem !important;
        }

        #queuecontainer::-webkit-scrollbar {
            display: none !important;
        }

        #queuecontainer {
            -ms-overflow-style: none !important;
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

            <div class="hstack gap-3 mt-4 mx-3">
                <input class="form-control form-custom me-auto p-2 text-light" type="search" placeholder="Search . . ."
                    id="searchbar" name="searchforafuckingchemical" autocomplete="one-time-code">
                <div class="vr"></div>
                <button type="button" id="loadChem" class="btn btn-sidebar text-light py-3 px-4" data-bs-toggle="modal"
                    data-bs-target="#addModal" data-bs-toggle="tooltip" title="Add Stock"><i
                        class="bi bi-plus-square"></i></button>
            </div>

            <div class="container d-flex flex-nowrap " id="queuecontainer">
                <div class="spinner-border text-light mt-4 mx-auto" style="display: none;" id="loader" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class=" d-flex flex-nowrap row row-cols-1 row-cols-md-3 g-4 mt-2 mb-4 px-4" id="cardcontainer">
                    <!-- ajax -->

                </div>
            </div>

            <div class="container-fluid">
                <div class="row" id="ondispatch"></div>
            </div>

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

        <!-- technicians -->
        <div class="modal fade text-dark modal-edit" id="technicians" tabindex="-1" aria-labelledby="create"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-modal-title text-light">
                        <h1 class="modal-title fs-5">Deployed Technicians for Transaction <span
                                id="deployedtransid"></span></h1>
                        <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                class="bi text-light bi-x"></i></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="fw-light">Assigned Technicians:</h5>
                        <ul class="list-group list-group-flush" id="technicianscont"></ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        const dataUrl = "tablecontents/queue.data.php";
        const submitUrl = "tablecontents/queue.config.php";


        function show_toast(message) {
            $('#toastmsg').html(message);
            var toastid = $('#toast');
            var toast = new bootstrap.Toast(toastid);
            toast.show();
        }

        $(document).ready(async function () {
            await load();
        });

        $(document).on('click', '#dispatchedtechbtn', async function () {
            let id = $(this).data('tech');
            $('#deployedtransid').html(id);
            const deploy = await deployed_tech(id);
            if (deploy) {
                $('#technicians').modal('show');
            }
        });

        $(document).on('hidden.bs.modal', '#technicians', function () {
            $('#technicianscont').empty();
        });

        async function deployed_tech(transid) {
            try {
                const deployed = await $.ajax({
                    url: dataUrl,
                    method: 'GET',
                    dataType: 'html',
                    data: {
                        transid: transid,
                        dispatched: 'true'
                    }
                });

                if (deployed) {
                    $('#technicianscont').html(deployed);
                    return true;
                }
            } catch (error) {
                // console.log(error);
                return error;
            }
        }

        async function active_transaction(){
            try {
                
            } catch (error) {
                
            }
        }

        async function load() {
            try {
                const load = await $.ajax({
                    method: "GET",
                    url: dataUrl,
                    dataType: 'html',
                    data: {
                        queue: 'true'
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


        $(function () {
            let delay = null;

            $('#searchbar').keyup(function () {
                clearTimeout(delay);
                $('#cardcontainer').empty();
                $('#loader').attr('style', 'display: block !important');

                delay = setTimeout(async function () {
                    var search = $('#searchbar').val();
                    try {
                        const searcheq = await $.ajax({
                            url: dataUrl,
                            type: 'GET',
                            dataType: 'html',
                            data: {
                                search: search,
                            }
                        });
                        if (searcheq) {
                            if (!search == '') {
                                $('#cardcontainer').empty();
                                $('#loader').attr('style', 'display: none !important');
                                $('#cardcontainer').append(searcheq);
                            } else {
                                $('#loader').attr('style', 'display: none !important');
                                await load();
                            }
                        }
                    } catch (error) {
                        console.log(error);
                    }
                }, 250);
            });

        });
    </script>
    <?php include('footer.links.php'); ?>
</body>

</html>