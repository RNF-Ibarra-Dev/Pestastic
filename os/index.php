<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operations Supervisor | Pestastic Inventory</title>
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

            <div class="mx-2 bg-light bg-opacity-25 rounded p-3 shadow user-select-none">
                <h1 class="fw-medium text-center display-6">Welcome Operations Manager<strong
                        style="text-transform: capitalize;">
                        <?= $_SESSION['fname'] . ' ' . $_SESSION['lname'] ?></strong>
                </h1>
            </div>
            <div class="row m-2 gap-2 user-select-none">
                <div class="col bg-light rounded-3 bg-opacity-25 d-flex py-3 justify-content-around">
                    <i
                        class="bi bi-box-fill flex-grow-0 my-auto shadow-sm fs-4 bg-success bg-opacity-50 py-2 px-3 rounded-circle align-middle text-center"></i>
                    <div class="d-flex flex-column">
                        <p class="fw-bold fs-5">Available in stock items</p>
                        <span id="avail_item_count" class="fw-medium fs-5"></span>
                    </div>
                </div>
                <div class="col bg-light rounded-3 bg-opacity-25 d-flex py-3 justify-content-around">
                    <i
                        class="bi bi-geo-alt-fill flex-grow-0 my-auto shadow-sm fs-4 bg-color-accent bg-opacity-25 py-2 px-3 rounded-circle align-middle text-center"></i>
                    <div class="d-flex flex-column">
                        <p class="fw-bold fs-5">Dispatched Technicians</p>
                        <span id="dispatched_tech" class="fw-medium fs-5"></span>
                    </div>
                </div>
                <div class="col bg-light rounded-3 bg-opacity-25 d-flex py-3 justify-content-around">
                    <i
                        class="bi bi-calendar2-check-fill flex-grow-0 my-auto shadow-sm fs-4 bg-info bg-opacity-50 py-2 px-3 rounded-circle align-middle text-center"></i>
                    <div class="d-flex flex-column">
                        <p class="fw-bold fs-5">Weekly Transactions</p>
                        <span id="weekly_completed" class="fw-medium fs-5"></span>
                    </div>
                </div>
                <div class="col bg-light rounded-3 bg-opacity-25 d-flex py-3 justify-content-around">
                    <i
                        class="bi bi-exclamation-triangle-fill flex-grow-0 my-auto shadow-sm fs-4 bg-warning bg-opacity-50 py-2 px-3 rounded-circle align-middle text-center"></i>
                    <div class="d-flex flex-column">
                        <p class="fw-bold fs-5">Urgent Restocks</p>
                        <span id="urgent_restocks" class="fw-medium fs-5"></span>
                    </div>
                </div>
                <div class="col bg-light rounded-3 bg-opacity-25 d-flex py-3 justify-content-around">
                    <i
                        class="bi bi-journal-bookmark-fill flex-grow-0 my-auto shadow-sm fs-4 bg-danger bg-opacity-50 py-2 px-3 rounded-circle align-middle text-center"></i>
                    <div class="d-flex flex-column">
                        <p class="fw-bold fs-5">Current Approvals</p>
                        <span id="today_pending" class="fw-medium fs-5"></span>
                    </div>
                </div>
            </div>
            <div class="row m-2 gap-2">
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 pb-0 shadow">
                    <div>
                        <div class="clearfix">
                            <i
                                class="bi bi-clipboard-data-fill fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                            <p class="text-center fw-medium align-text-center fs-3 w-75 mx-auto">
                                Transactions Status Summary</p>
                        </div>
                        <canvas id="transPie" style="max-height: 30rem !important;"></canvas>
                        <p class="text-muted mt-3">Check <a href="transactions.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            to display all transactions.</p>
                    </div>
                </div>
                <div class="col bg-light bg-opacity-25  rounded p-3 shadow">
                    <div class="clearfix">
                        <i
                            class="bi bi-stopwatch-fill fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded py-1"></i>
                        <p class="text-center fw-medium align-text-center fs-3 w-75 mx-auto">
                            Pending Transactions</p>
                    </div>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption class="mt-auto">Check <a href="transactions.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            for more.</caption>
                        <thead>
                            <tr class="text-center align-middle">
                                <th scope="col">Transaction ID</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">View</th>
                            </tr>
                        </thead>
                        <tbody id="pendingtrans" class="text-center align-middle"></tbody>
                    </table>
                </div>

            </div>

            <div class="row m-2 gap-2">

                <div class="col bg-light bg-opacity-25  rounded p-3 shadow">
                    <div class="clearfix">
                        <i
                            class="ms-2 fs-3 bi bi-clock-fill float-start fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded py-1"></i>
                        <p class="text-center fw-medium fs-3 mx-auto w-75">Pending Item Entries</p>
                    </div>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption>Recently requested addition of chemicals. Check <a href="inventory.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">inventory</a>
                            page for more.</caption>
                        <thead>
                            <tr class="text-center">
                                <th scope="col">Name</th>
                                <th scope="col">Brand</th>
                                <th scope="col">Date Received</th>
                            </tr>
                        </thead>
                        <tbody id="pendingchem" class=""></tbody>
                    </table>
                </div>
                <div class="col bg-light bg-opacity-25  rounded p-3 shadow">
                    <div class="clearfix">
                        <i
                            class="bi bi-exclamation-circle fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded py-1"></i>
                        <p class="text-center fw-medium fs-3 w-75 mx-auto">Running Out Items</p>
                    </div>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption>Check <a href="inventory.php?chem=low"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">inventory</a>
                            to see all running out items.</caption>
                        <thead>
                            <tr class="text-center align-middle">
                                <th scope="col">Item</th>
                                <th scope="col">Container Left</th>
                            </tr>
                        </thead>
                        <tbody id="lowchemicals"></tbody>
                    </table>
                </div>
                <div class="col bg-light bg-opacity-25  rounded p-3 shadow">
                    <div class="clearfix">
                        <i
                            class="bi bi-arrow-repeat fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded py-1"></i>
                        <p class="text-center fw-medium fs-3 w-75 mx-auto">Finalizing Transactions</p>
                    </div>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption>Check <a href="transactions.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            for more.</caption>
                        <thead>
                            <tr class="text-center align-middle">
                                <th scope="col">Transaction ID</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody id="finalizing_table">
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <?php include('footer.links.php'); ?>
    <script>
        const dataurl = 'contents/dashboard.data.php';


        $(document).ready(async function () {
            await append_table('pendingtrans');
            await append_table('pendingchem');
            await append_table('lowchemicals');
            await append_table('finalizing_table');
            await get_count('avail_item_count');
            await get_count('dispatched_tech');
            await get_count('weekly_completed');
            await get_count('urgent_restocks');
            await get_count('today_pending');
        })

        async function get_count(container) {
            return $.get(dataurl, { count: container }, function (d) {
                $(`#${container}`).empty();
                $(`#${container}`).append(d);
            }, 'html')
                .fail(function (e) {
                    alert(`Failed to append data to ${container} container.`);
                    console.log(e);
                })
        }

        async function append_table(container) {
            try {
                const append = await $.ajax({
                    method: 'GET',
                    dataType: 'html',
                    url: dataurl,
                    data: {
                        append: container
                    }
                });

                if (append) {
                    $(`#${container}`).empty();
                    $(`#${container}`).append(append);
                }
            } catch (error) {
                alert(error);
            }
        }


        // pie pie pie pie pie pie pi epi epi epi epi epie 

        async function get_chart_data(container) {
            try {
                const chartData = await $.ajax({
                    method: 'GET',
                    type: 'json',
                    url: dataurl,
                    data: {
                        getChart: container
                    }
                });

                if (chartData) {
                    data = JSON.parse(chartData);
                    console.log(data);
                    create_chart('transPie', data.count);
                }
            } catch (error) {
                console.log(error.responseText);
            }
        }

        const transPie = $('#transPie');
        console.log(transPie);
        $(function () {
            get_chart_data('status');
        })

        Chart.defaults.color = '#000';

        function create_chart(canvasid, data) {
            const ctx = $(`#${canvasid}`);

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: [`Pending: ${data[0]}`, `Accepted: ${data[1]}`, `Voided: ${data[2]}`, `Completed: ${data[3]}`, `Cancelled: ${data[4]}`, `Finalizing: ${data[5]}`, `Dispatched: ${data[6]}`],
                    datasets: [{
                        data: [data[0], data[1], data[2], data[3], data[4], data[5], data[6]],
                        borderWidth: 1,
                        backgroundColor: [
                            'rgba(255, 255, 255, 0.14)',
                            'rgba(255, 255, 255, 0.28)',
                            'rgba(255, 255, 255, 0.42)',
                            'rgba(255, 255, 255, 0.56)',
                            'rgba(255, 255, 255, 0.70)',
                            'rgba(255, 255, 255, 0.84)',
                            'rgba(255, 255, 255, 1)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white'
                            },
                        },
                        tooltip: {
                            bodyColor: 'white',
                            titleColor: 'white',
                            yAlign: 'bottom'
                        },

                    }
                }
            });
        }
    </script>
</body>

</html>