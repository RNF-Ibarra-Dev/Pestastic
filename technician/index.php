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
            <div class="row m-2 gap-2">
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 pb-0 shadow">
                    <div>
                        <div class="clearfix">
                            <i
                                class="bi bi-clipboard-data-fill  fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                            <p class="text-center fw-medium align-text-center fs-3 w-75 mx-auto">
                                Transactions Status</p>
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
                        <caption class="mt-auto text-light fw-light">Check <a href="transactions.php"
                                class="color-accent link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            for more. Click approve to accept transaction.</caption>
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
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 shadow">
                    <div class="clearfix">
                        <i
                            class="ms-2 fs-3 bi bi-clock-fill  float-start fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                        <p class="text-center fs-3 mx-auto w-75 fw-medium">Pending Item Entries</p>
                    </div>
                    <table class="table-hover rounded-3 overflow-hidden table">
                        <caption class="fw-light text-light">Recently requested addition of chemicals. Check <a
                                href="itemstock.php"
                                class="color-accent link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">inventory</a>
                            page for more.</caption>
                        <thead>
                            <tr class="text-center">
                                <th scope="col" class="fs-5 fw-bold">Name</th>
                                <th scope="col" class="fs-5 fw-bold">Brand</th>
                                <th scope="col" class="fs-5 fw-bold">Date Received</th>
                            </tr>
                        </thead>
                        <tbody id="pendingchem" class=""></tbody>
                    </table>
                </div>
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 shadow">
                    <div class="clearfix">
                        <i
                            class="bi bi-exclamation-circle  fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                        <p class="text-center fw-medium fs-3 w-75 mx-auto">Running Out Items</p>
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
            const tables = await Promise.all([
                await append_table('pendingtrans'),
                await append_table('pendingchem'),
                await append_table('lowchemicals'),
                await append_table('finalizing_table'),
            ])
            if (!tables) {
                alert('Loading table failed.');
            }
        });


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