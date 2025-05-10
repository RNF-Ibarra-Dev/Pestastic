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
                        <span class="d-flex flex-row justify-content-center">
                            <i class="bi bi-clipboard-data fs-3 me-3"></i>
                            <p class="text-center align-text-center fs-3">
                                Transactions Status</p>
                        </span>
                        <canvas id="transPie" width="250" height="
                        250"></canvas>
                        <p class="text-muted mt-3">Check <a href="transactions.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            to display all transactions.</p>
                    </div>
                </div>
                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <p class="text-center align-text-center fs-3 mx-auto"><i class="bi bi-exclamation-circle"></i>
                        Pending Transactions</p>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption class="mt-auto">Check your <a href="transactions.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            for more.</caption>
                        <thead>
                            <tr class="text-center">
                                <th scope="col">ID</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="pendingtrans" class=""></tbody>
                    </table>
                </div>

            </div>

            <div class="row m-2 gap-2">

                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <p class="text-center fs-3 mx-auto">Pending Chemical Requests</p>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption>Recently requested addition of chemicals. Check <a href="inventory.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">inventory</a>
                            page for more.</caption>
                        <thead>
                            <tr class="text-center">
                                <th scope="col">Name</th>
                                <th scope="col">Brand</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id="pendingchem" class=""></tbody>
                    </table>
                </div>
                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <p class="text-center fs-3 mx-auto">Low Stock Chemicals</p>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption>Check <a href="inventory.php?chem=low"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">inventory</a>
                            to see all chemicals low in stock.</caption>
                        <thead>
                            <tr class="text-center">
                                <th scope="col">Name</th>
                                <th scope="col">Brand</th>
                                <th scope="col">Level</th>
                            </tr>
                        </thead>
                        <tbody id="lowchemicals"></tbody>
                    </table>
                </div>
                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <p class="text-center fs-3 mx-auto">Available Equipments</p>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption>Check your <a href="equipments.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">equipments</a>
                            for more.</caption>
                        <thead>
                            <tr class="text-center align-middle">
                                <th scope="col">Equipment</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id="eqtable">
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
            await append_table('eqtable');
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
                    labels: ['Pending', 'Accepted', 'Voided', 'Completed'],
                    datasets: [{
                        data: [data[0], data[1], data[2], data[3]],
                        borderWidth: 1,
                        backgroundColor: [
                            'rgba(255, 255, 255, 0.30)',
                            'rgba(255, 255, 255, 0.50)',
                            'rgba(255, 255, 255, 0.75)',
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