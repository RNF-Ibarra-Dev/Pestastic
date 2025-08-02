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

            <div class="row m-2 gap-2">
                <div class="col-5 bg-light bg-opacity-25 border rounded p-3 shadow">
                    <h1 class=" display-6 mx-auto">Welcome Operations Manager<br><strong
                            style="text-transform: capitalize;">
                            <?= $_SESSION['fname'] . ' ' . $_SESSION['lname'] ?></strong>
                    </h1>
                    <p class="fs-3"></p>
                </div>
                <div class="col bg-light bg-opacity-25 border rounded p-3 pb-0 shadow">
                    <div>
                        <div class="clearfix">
                            <i
                                class="bi bi-clipboard-data fs-4 border shadow-sm float-start bg-dark bg-opacity-25 px-2 rounded py-1"></i>
                            <p class="text-center align-text-center fs-3 w-75 mx-auto">
                                Transactions Status Summary</p>
                        </div>
                        <canvas id="transPie" width="250" height="
                        250"></canvas>
                        <p class="text-muted mt-3">Check <a href="transactions.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            to display all transactions.</p>
                    </div>
                </div>
                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <div class="clearfix">
                        <i
                            class="bi bi-stopwatch fs-4 border shadow-sm float-start bg-dark bg-opacity-25 px-2 rounded py-1"></i>
                        <p class="text-center align-text-center fs-3 w-75 mx-auto">
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
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody id="pendingtrans" class="text-center align-middle"></tbody>
                    </table>
                </div>

            </div>

            <div class="row m-2 gap-2">

                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <div class="clearfix">
                        <i
                            class="ms-2 fs-3 bi bi-clock float-start fs-4 border shadow-sm float-start bg-dark bg-opacity-25 px-2 rounded py-1"></i>
                        <p class="text-center fs-3 mx-auto w-75">Pending Item Entries</p>
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
                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <div class="clearfix">
                        <i class="bi bi-exclamation-circle fs-4 border shadow-sm float-start bg-dark bg-opacity-25 px-2 rounded py-1"></i>
                        <p class="text-center fs-3 w-75 mx-auto">Running Out Items</p>
                    </div>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption>Check <a href="inventory.php?chem=low"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">inventory</a>
                            to see all low stock items.</caption>
                        <thead>
                            <tr class="text-center align-middle">
                                <th scope="col">Item</th>
                                <th scope="col">Container Count</th>
                            </tr>
                        </thead>
                        <tbody id="lowchemicals"></tbody>
                    </table>
                </div>
                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <div class="clearfix">
                        <i class="bi bi-arrow-repeat fs-4 border shadow-sm float-start bg-dark bg-opacity-25 px-2 rounded py-1"></i>
                        <p class="text-center fs-3 w-75 mx-auto">Finalizing Transactions</p>
                    </div>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption>Check <a href="inventory.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">inventory</a>
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
        })

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
                    labels: ['Pending', 'Accepted', 'Voided', 'Completed', 'Cancelled', 'Finalizing', 'Dispatched'],
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