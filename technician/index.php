<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician | Pestastic Inventory</title>
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

            <div class="mx-2 bg-light bg-opacity-25 rounded p-3 shadow">
                <h1 class="text-center fw-medium display-6">Welcome back Technician<strong>
                        <?= $_SESSION['firstName'] . ' ' . $_SESSION['lastName'] ?></strong>
                </h1>
            </div>
            <div class="row m-2 gap-2 user-select-none">
                <div class="col bg-light rounded-3 bg-opacity-25 d-flex py-3">
                    <i
                        class="me-4 ms-2 bi bi-box-fill flex-grow-0 my-auto shadow-sm fs-4 bg-success bg-opacity-50 py-2 px-3 rounded-circle align-middle text-center"></i>
                    <div class="d-flex flex-column">
                        <p class="fw-bold fs-5">Available in stock items</p>
                        <span id="avail_item_count" class="fw-medium fs-5"></span>
                    </div>
                </div>
                <div class="col bg-light rounded-3 bg-opacity-25 d-flex py-3">
                    <i
                        class="me-4 ms-2 bi bi-clock-history text-shadow flex-grow-0 my-auto shadow-sm fs-4 bg-warning bg-opacity-50 py-2 px-3 rounded-circle align-middle text-center"></i>
                    <div class="d-flex flex-column">
                        <p class="fw-bold fs-5">Pending Transactions</p>
                        <span id="pending_trans" class="fw-medium fs-5"></span>
                    </div>
                </div>
                <div class="col bg-light rounded-3 bg-opacity-25 d-flex py-3">
                    <i
                        class="me-4 ms-2 bi bi-card-list text-shadow flex-grow-0 my-auto shadow-sm fs-4 bg-color-accent bg-opacity-25 py-2 px-3 rounded-circle align-middle text-center"></i>
                    <div class="d-flex flex-column">
                        <p class="fw-bold fs-5">Scheduled Transactions</p>
                        <span id="queue_transaction" class="fw-medium fs-5"></span>
                    </div>
                </div>
                <div class="col bg-light rounded-3 bg-opacity-25 d-flex py-3">
                    <i
                        class="me-4 ms-2 bi bi-card-checklist text-shadow flex-grow-0 my-auto shadow-sm fs-4 bg-info bg-opacity-50 py-2 px-3 rounded-circle align-middle text-center"></i>
                    <div class="d-flex flex-column">
                        <p class="fw-bold fs-5">Completed Transactions</p>
                        <span id="completed_trans" class="fw-medium fs-5"></span>
                    </div>
                </div>
            </div>
            <div class="row m-2 gap-2">
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 pb-0 shadow">
                    <div>
                        <div class="clearfix">
                            <i
                                class="bi bi-clipboard-data-fill  fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                            <p class="text-center fw-medium align-text-center fs-3 w-75 mx-auto">
                                Transactions Status Summary</p>
                        </div>
                        <canvas id="transPie" style="max-height: 30rem !important;"></canvas>
                        <p class="text-light fw-light mt-3">Check <a href="transactions.php"
                                class="color-accent link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            to display all transactions.</p>
                    </div>
                </div>
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 shadow d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-stopwatch-fill  fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                        <p class="text-center fw-medium align-text-center fs-3 w-75 mx-auto">
                            Pending Status Transactions</p>
                    </div>
                    <table class="table-hover rounded-3 overflow-hidden table my-auto">
                        <caption class="mt-auto text-light fw-light">Your recent pending transactions.Check <a
                                href="transactions.php"
                                class="color-accent link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            for more.</caption>
                        <thead>
                            <tr class="text-center">
                                <th scope="col" class="fs-5 fw-bold">ID</th>
                                <th scope="col" class="fs-5 fw-bold">Customer</th>
                                <th scope="col" class="fs-5 fw-bold">View</th>
                            </tr>
                        </thead>
                        <tbody id="pendingtrans" class="text-center align-middle"></tbody>
                    </table>
                </div>
            </div>

            <div class="row m-2 gap-2">
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 shadow d-flex flex-column">
                    <div class="clearfix">
                        <i
                            class="bi bi-stopwatch-fill  fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                        <p class="text-center fw-medium align-text-center fs-3 w-75 mx-auto">
                            Scheduled Transactions</p>
                    </div>
                    <table class="table-hover rounded-3 overflow-hidden table my-auto">
                        <caption class="mt-auto text-light fw-light">Your recent scheduled transactions.Check <a
                                href="transactions.php"
                                class="color-accent link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            for more.</caption>
                        <thead>
                            <tr class="text-center text-wrap">
                                <th scope="col" class="fs-5 fw-bold">ID</th>
                                <th scope="col" class="fs-5 fw-bold">Customer</th>
                                <th scope="col" class="fs-5 fw-bold">Scheduled Date & Time</th>
                                <th scope="col" class="fs-5 fw-bold">View</th>
                            </tr>
                        </thead>
                        <tbody id="scheduled" class="text-center align-middle"></tbody>
                    </table>
                </div>
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 pb-0 shadow" style="min-height: 40rem;">
                    <div class="d-flex flex-column h-100">
                        <div class="clearfix">
                            <i
                                class="bi bi-person-badge-fill fs-4 shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                            <p class="text-center fw-medium align-text-center fs-3 w-75 mx-auto">
                                Top Technicians (<?= date("F") ?>)</p>
                        </div>
                        <ul class="text-shadow list-group list-group-flush rounded-3 shadow-sm my-auto"
                            id="top_technicians">
                        </ul>
                        <p class="text-muted mt-3 text-light">Note: This only displays the top 5 technicians with
                            completed transactions.</p>
                    </div>
                </div>
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 pb-0 shadow" style="min-height: 40rem;">
                    <div class="d-flex flex-column h-100">
                        <div class="clearfix">
                            <i
                                class="bi bi-person-badge-fill fs-4 shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                            <p class="text-center fw-medium align-text-center fs-3 w-75 mx-auto">
                                Weekly Technician Workload</p>
                        </div>
                        <ul class="text-shadow list-group list-group-flush rounded-3 shadow-sm my-auto"
                            id="tech_workload">
                        </ul>
                        <p class="text-muted mt-3 text-light">Note: Only the technicians with assigned transactions are
                            displayed.</p>
                    </div>
                </div>
            </div>

            <div class="row m-2 gap-2">
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 shadow">
                    <div class="clearfix">
                        <i
                            class="bi bi-exclamation-circle  fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                        <p class="text-center fw-medium fs-3 w-75 mx-auto">Items Running Out</p>
                    </div>
                    <table class="table-hover rounded-3 overflow-hidden table">
                        <caption class="text-light">Check <a href="itemstock.php?chem=low"
                                class="color-accent link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">inventory</a>
                            to see all running out items.</caption>
                        <thead>
                            <tr class="text-center align-middle">
                                <th scope="col" class="fw-bold fs-5">Item</th>
                                <th scope="col" class="fw-bold fs-5">Container Left</th>
                            </tr>
                        </thead>
                        <tbody id="lowchemicals"></tbody>
                    </table>
                </div>
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 shadow">
                    <div class="clearfix">
                        <i
                            class="bi bi-arrow-repeat  fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                        <p class="text-center fw-medium fs-3 w-75 mx-auto">Finalizing Transactions</p>
                    </div>
                    <table class="table-hover rounded-3 overflow-hidden table">
                        <caption class="text-light">Check <a href="transaction.php"
                                class="color-accent link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transaction</a>
                            to review and approve void requests.</caption>
                        <thead>
                            <tr class="text-center align-middle">
                                <th scope="col" class="fw-bold fs-5">Transaction ID</th>
                                <th scope="col" class="fw-bold fs-5">Customer Name</th>
                                <th scope="col" class="fw-bold fs-5">View</th>
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
        const dataurl = 'contents/index.data.php';
        const tech = <?= $_SESSION['techId'] ?>;
        console.log(tech);

        $(document).ready(async function () {
            await append('pendingtrans');
            await append('scheduled');
            await append('pendingchem');
            await append('lowchemicals');
            await append('finalizing_table');
            await append('top_technicians');
            await append('tech_workload');
            await get_count('avail_item_count');
            await get_count('queue_transaction');
            await get_count('completed_trans');
            await get_count('pending_trans');
        });


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

        async function append(container) {
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
                        Width: 1,
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