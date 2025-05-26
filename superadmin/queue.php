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

        .active {
            cursor: grabbing;
            cursor: -webkit-grabbing;
            transform: scale(1.01) !important;
            transition: 0.4s;
        }

        #cardcontainer {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
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



            <div class="row row-col-2 m-0 d-flex justify-content-around">
                <!-- calendar -->
                <div class="col-4 bg-dark bg-opacity-25 border border-dark rounded mt-2 shadow-sm d-flex flex-column pb-3">
                    <div class="bg-light bg-opacity-25 my-2 p-2 shadow-sm rounded-pill">
                        <h4 class="fw-light text-center d-flex align-items-center justify-content-center m-0"><i
                                class="bi bi-file-earmark-text me-2 "></i>
                            Ongoing Transactions</h4>
                    </div>
                    <div class="flex-grow-1 d-flex" id="ongoing">
                    </div>
                </div>

                <!-- upcoming -->
                <div class="col-7 bg-dark bg-opacity-25 border border-dark rounded mt-2 shadow-sm">
                    <div class="m-2 d-flex border-light bg-light bg-opacity-25 shadow-sm p-2 align-middle rounded-pill">
                        <button type="button" class="btn btn-sidebar rounded-pill me-2 text-light">
                            <i class="bi bi-sort-up h5 m-0 d-flex align-items-center" id='sortrecent'></i>
                        </button>
                        <h4 class="fw-light m-0 justify-content-center flex-grow-1 d-flex align-items-center"><i
                                class="bi bi-journal me-2"></i>
                            Upcoming
                            Transactions</h5>
                            <div style="width: 35px !important;" class="m-0 p-0"></div>
                    </div>
                    <div class="d-flex flex-nowrap bg-light shadow-sm rounded bg-opacity-25" id="queuecontainer">

                        <div class=" d-flex flex-nowrap w-75 row row-cols-1 row-cols-md-3 g-4 mt-2 mb-4 px-4 align-items-center"
                            id="cardcontainer">
                            <!-- ajax -->
                        </div>
                    </div>
                </div>
            </div>


            <div class="container-fluid">
                <div class="row d-flex justify-content-around">
                    <div class="col-7 bg-dark bg-opacity-25 border border-dark rounded shadow-sm my-3">
                        <div class="bg-light bg-opacity-25 my-2 p-2 rounded-pill shadow-sm">
                            <h4 class="fw-light text-center d-flex align-items-center justify-content-center m-0"><i
                                    class="bi bi-calendar-week me-2"></i>Weekly
                                Transactions</h5>
                        </div>
                        <div class="container bg-light bg-opacity-25 rounded shadow-sm">
                            <div id="upcoming"></div>
                        </div>
                    </div>
                    <div class="col-4 bg-dark bg-opacity-25 border border-dark rounded shadow-sm my-3">
                        <div class="bg-light bg-opacity-25 shadow-sm rounded-pill p-2 my-2">
                            <h4 class="fw-light justify-content-center m-0 d-flex align-items-center"><i
                                    class="bi bi-calendar-date me-2"></i>Transaction
                                Calendar
                            </h4>
                        </div>
                        <div class="bg-light shadow-sm bg-opacity-25 rounded p-2" id="calendar"></div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row d-flex justify-content-around">
                    <div class="col-4 bg-dark bg-opacity-25 border border-dark rounded overflow-hidden shadow-sm">
                        <div
                            class="bg-light bg-opacity-25 my-2 p-2 rounded-pill shadow-sm d-flex justify-content-between">
                            <button type="button" class="btn btn-sidebar rounded-pill me-2 text-light"
                                id='sortTechStatus'>
                                <i class="bi bi-filter h5 m-0 d-flex align-items-center"></i>
                            </button>
                            <h4 class="fw-light text-center d-flex align-items-center justify-content-center m-0">
                                <i class="bi bi-people me-2"></i>Technician Status
                            </h4>
                            <div id="techStatBtnFiller" class="m-0 p-0"></div>
                        </div>
                        <div class="list-group list-group-flush d-flex overflow-auto" id="technicianStatus"
                            style="height: 15rem !important"></div>
                    </div>


                    <div class="col-5 bg-dark bg-opacity-25 border border-dark d-flex flex-column rounded shadow-sm">
                        <div
                            class="bg-light bg-opacity-25 my-2 p-2 rounded-pill shadow-sm d-flex justify-content-center">
                            <h4 class="fw-light text-center d-flex align-items-center justify-content-center m-0"><i
                                    class="bi bi-journal-minus me-2"></i> Unassigned Transactions</h4>
                        </div>
                        <div class="d-flex flex-nowrap bg-light shadow-sm rounded bg-opacity-25" id="incTransContainer">
                            <div id="incompleteTransactions" class="my-auto p-0 d-flex flex-nowrap g-4 justify-content-center"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hstack gap-3 mt-4 mx-3">
                <input class="form-control form-custom me-auto p-2 text-light" type="search" placeholder="Search . . ."
                    id="searchbar" name="searchforafuckingchemical" autocomplete="one-time-code">
                <div class="vr"></div>
                <button type="button" id="loadChem" class="btn btn-sidebar text-light py-3 px-4" data-bs-toggle="modal"
                    data-bs-target="#addModal" data-bs-toggle="tooltip" title="Add Stock"><i
                        class="bi bi-plus-square"></i></button>
            </div>
        </main>


        <!-- hidden stuffs -->

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
        <!-- technicians -->
        <div class="modal fade text-dark modal-edit" id="technicians" tabindex="-1" aria-labelledby="create"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-modal-title text-light">
                        <h1 class="modal-title fs-5">Assigned Technicians for Transaction <span
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

        <div class="modal fade text-dark modal-edit" id="reschedModal" tabindex="-1" aria-labelledby="create"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-modal-title text-light">
                        <h1 class="modal-title fs-5">Reschedule Transaction <span id="reschedtransid"></span></h1>
                        <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                class="bi text-light bi-x"></i></button>
                    </div>
                    <form id="reschedForm">
                        <div class="modal-body">

                            <input type="hidden" name="reschedid" id="reschedId">
                            <label for="rescheddate" class="form-label">Select New Schedule</label>
                            <input type="date" name="reschedDate" class="form-control w-50" id="reschedDate">
                            <div class="form-text ms-1"></div>
                            <label for="reschedTime" class="form-label">Select Time:</label>
                            <input type="text" name="reschedTime" class="form-control w-50" id="reschedTime">
                            <div class="form-text ms-1">Select time between 9:00 AM and 3:00 PM.</div>
                            <p class='text-center alert alert-info p-3 w-75 mx-auto my-0 mt-3' style="display: none;"
                                id="reschedalert"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-grad">Reschedule Transaction</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="modal fade text-dark modal-edit" id="cancelModal" tabindex="-1" aria-labelledby="create"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-modal-title text-light">
                        <h1 class="modal-title fs-5">Cancel Transaction <span id="cancelIdDisplay"></span></h1>
                        <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                class="bi text-light bi-x"></i></button>
                    </div>
                    <form id="cancelForm">
                        <div class="modal-body">
                            <input type="hidden" name="cancelIdName" id="cancelId">
                            <label for="cancelPass" class="form-label">Cancel this transaction? Enter
                                <?= $_SESSION['saUsn'] ?>'s password to proceed</label>
                            <input type="password" name="cancelPass" class="form-control w-50" id="cancelPass">
                            <div class="form-text ms-1">Cancelling Transaction is undoable, continue?</div>
                            <p class='text-center alert alert-info p-3 w-75 mx-auto my-0 mt-3' style="display: none;"
                                id="cancelAlert"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-grad">Cancel Transaction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<!-- 
        <div class="modal fade text-dark modal-edit" id="reviewModal" tabindex="-1" aria-labelledby="create"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-modal-title text-light">
                        <h1 class="modal-title fs-5">Review Transaction</h1>
                        <button type="button" class="btn ms-auto p-0" data-bs-dismiss="modal"><i
                                class="bi text-light bi-x"></i></button>
                    </div>
                    <form id="reviewForm">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="reviewIdInput">
                            <label for="reviewId">Transaction ID:</label>
                            <p class="fw-light" id="reviewId"></p>
                            <label for="cname">Customer Name:</label>
                            <input type="text" name="name" id="cname">


                            <p class='text-center alert alert-info p-3 w-75 mx-auto my-0 mt-3' style="display: none;"
                                id="cancelAlert"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-grad" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-grad">Edit Transaction</button>
                            <button type="submit" class="btn btn-grad">Approve Transaction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> -->



    </div>
    <?php
    include('footer.links.php');
    ?>

    <script>
        const dataUrl = "tablecontents/queue.data.php";
        const submitUrl = "tablecontents/queue.config.php";

        let asc = false;
        $(document).on('click', '#sortrecent', async function () {
            if (asc === false) {
                $('#sortrecent').removeClass('bi-sort-up').addClass('bi-sort-down');
                asc = true;
                await load_cards(true);
                return asc;
            } else {
                $('#sortrecent').removeClass('bi-sort-down').addClass('bi-sort-up');
                asc = false;
                await load_cards(false);
                return asc;
            }
        });

        // $(document).on('click', '#reviewBtn', async function () {
        //     let id = $(this).data('review');
        //     $('#reviewIdInput').attr("value", id);
        //     $('#reviewId').html(id);
        //     $('#reviewModal').modal('show');
        // });

        // $(document).on('submit', '#reviewForm', async function(e){
        //     e.preventDefault();
        //     console.log($(this).serialize());
        // });

        $(document).ready(function () {
            let techStatBtnWidth = $("#sortTechStatus").outerWidth();
            $('#techStatBtnFiller').attr("style", `width: ${techStatBtnWidth}px !important`);
        });

        $(document).ready(async function () {
            await load_cards();
            await click_drag('queuecontainer');
            await fetch_data('ongoing');
            await load_technician_status();
            await load_incomplete_transactions();
        });

        async function load_incomplete_transactions() {
            try {
                const inc = await $.ajax({
                    method: 'GET',
                    url: dataUrl,
                    dataType: 'html',
                    data: {
                        inc: 'true'
                    }
                });

                if (inc) {
                    $("#incompleteTransactions").empty();
                    $("#incompleteTransactions").append(inc);
                }
            } catch (error) {
                console.log(error);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    start: 'prev',
                    center: 'title',
                    end: 'next'
                },
                height: 400,
                selectable: true,
                handleWindowResize: true,
                events: {
                    url: dataUrl,
                    method: 'GET',
                    extraParams: {
                        transactions: 'true'
                    },
                    failure: function (e) {
                        alert('Transaction event fetch failed.');
                        console.log(e);
                    },
                    color: '#00000033',
                    textColor: 'white'
                },
                hiddenDays: [0],
                dayHeaderFormat: {
                    weekday: 'short'
                },
            });

            var upcomingid = document.getElementById('upcoming');
            var transCalendar = new FullCalendar.Calendar(upcomingid, {
                initialView: 'dayGridWeek',
                hiddenDays: [0],
                dayHeaderFormat: {
                    weekday: 'short',
                    day: 'numeric',
                    omitCommas: true
                },
                headerToolbar: {
                    start: 'prev',
                    center: 'title',
                    end: 'next'
                },
                handleWindowResize: true,
                events: {
                    url: dataUrl,
                    method: 'GET',
                    extraParams: {
                        transactions: 'true',
                        data: 'titleonly'
                    },
                    failure: function (e) {
                        alert('Transaction event fetch failed.');
                        console.log(e);
                    },
                    color: '#00000033',
                    textColor: 'white'
                },
                eventContent: function (arg) {
                    return {
                        html: arg.event.title
                    };
                },
                height: 250
            });

            transCalendar.render();
            calendar.render();


        });

        $(async function () {
            let status = ['Available', 'Dispatched', 'Unavailable', 'On Leave', 'none'];
            // const totalStatus = 4; 
            statusNum = 0;
            $(document).on('click', '#sortTechStatus', async function () {
                load_technician_status(status[statusNum]);
                // console.log(status[statusNum]);
                if (statusNum >= 4) {
                    statusNum = 0;
                } else {
                    statusNum++;
                }
                return statusNum;
            });
        })


        async function load_technician_status(sort = 'none') {
            try {
                const techStat = await $.ajax({
                    method: 'GET',
                    url: dataUrl,
                    dataType: 'html',
                    data: {
                        techStats: 'true',
                        sort: sort
                    }
                });

                if (techStat) {
                    $('#technicianStatus').empty();
                    $('#technicianStatus').html(techStat);
                    return true;
                }
            } catch (error) {
                console.log(error);
            }
        }

        $(document).on('click', '#cancel', async function () {
            let id = $(this).data('cancel');
            $('#cancelIdDisplay').html(id);
            $('#cancelId').attr('value', id);
            $('#cancelModal').modal('show');
        });



        $(document).on('submit', '#reschedForm', async function (e) {
            e.preventDefault();
            try {
                const resched = await $.ajax({
                    url: submitUrl,
                    method: 'POST',
                    dataType: 'json',
                    data: $(this).serialize() + "&resched=true"
                });

                if (resched) {
                    await show_toast(resched.success);
                    await load_cards();
                    $('#reschedModal').modal('hide');
                }
            } catch (error) {
                let err = error.responseText;
                $('#reschedalert').html(err).fadeIn(350).delay(2000).fadeOut(500);
            }
        });

        $(document).on('submit', '#cancelForm', async function (e) {
            e.preventDefault();
            try {
                const cancel = await $.ajax({
                    method: 'POST',
                    url: submitUrl,
                    dataType: 'json',
                    data: $(this).serialize() + "&cancel=true"
                });

                if (cancel) {
                    show_toast(cancel.success);
                    await load_cards();
                    $('#cancelModal').modal('hide');
                }
            } catch (error) {
                let err = error.responseText;
                $('#cancelAlert').html(err).fadeIn(350).delay(2000).fadeOut(500);
            }
        });

        let reschedDatee = document.getElementById('reschedDate');
        reschedDate = flatpickr(reschedDatee, {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            minDate: new Date().fp_incr(1),
            disable: [
                function (date) {
                    return (date.getDay() === 0 || date.getDay() === 6);
                }
            ],
            locale: {
                "firstDayOfWeek": 1
            }
        });

        let reschedTimee = document.getElementById('reschedTime');
        reschedTime = flatpickr(reschedTimee, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            minTime: "9:00",
            maxTime: "15:00"
        });

        $(document).on('click', '#resched', async function () {
            let id = $(this).data('resched');
            let time = $(this).data('otime');
            let date = $(this).data('odate');

            $('#reschedtransid').html(id);
            $('#reschedId').attr('value', id);

            if (date) {
                reschedDate.setDate(date, true);
            } else {
                reschedDate.clear();
            }

            if (time) {
                reschedTime.setDate(time, true);
            } else {
                reschedTime.clear();
            }

            $('#reschedModal').modal('show');
            $(document).on('hidden-bs-modal', '#reschedModal', function () {
                reschedTime.clear();
                reschedDate.clear();
            });
        });

        async function click_drag(container) {
            const slider = document.getElementById(container);
            let isDown = false;
            let startX;
            let scrollLeft;

            slider.addEventListener('mousedown', (e) => {
                isDown = true;
                $(slider).addClass('active');
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });
            slider.addEventListener('mouseleave', () => {
                isDown = false;
                $(slider).removeClass('active');

            });
            slider.addEventListener('mouseup', () => {
                isDown = false;
                $(slider).removeClass('active');
            });
            slider.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - slider.offsetLeft;
                const walk = x - startX;
                slider.scrollLeft = scrollLeft - walk;
                // console.log(walk);
            });
        }

        function show_toast(message) {
            $('#toastmsg').html(message);
            var toastid = $('#toast');
            var toast = new bootstrap.Toast(toastid);
            toast.show();
        }



        $(document).on('click', '#dispatchedtechbtn', async function () {
            let id = $(this).data('tech');
            $('#deployedtransid').html(id);
            const deploy = await deployed_tech(id);
            // if (deploy) {
            $('#technicians').modal('show');
            // }
        });

        $(document).on('hidden.bs.modal', '#technicians', function () {
            $('#technicianscont').empty();
        });

        async function fetch_data(container) {
            try {
                const data = await $.ajax({
                    method: 'GET',
                    url: dataUrl,
                    dataType: 'html',
                    data: {
                        getdata: container,
                    }
                });
                if (data) {
                    $(`#${container}`).empty().html(data);
                    return true;
                }
            } catch (error) {
                return error;
            }
        }

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
                }
            } catch (error) {
                console.log(error);
            }
        }


        async function load_cards(sort = asc) {
            try {
                const load = await $.ajax({
                    method: "GET",
                    url: dataUrl,
                    dataType: 'html',
                    data: {
                        queue: 'true',
                        sort: sort
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
</body>

</html>