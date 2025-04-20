<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician - Pestastic Inventory</title>
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
                    <h1 class=" display-6 mx-auto">Welcome Operations Manager<br><strong style="text-transform: capitalize;">
                            <?= $_SESSION['fname'] . ' ' . $_SESSION['lname'] ?></strong>
                    </h1>
                    <p class="fs-3"></p>
                </div>
                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <div>
                        <canvas id="transPie" width="250" height="
                        250"></canvas>
                    </div>
                </div>
                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <p class="text-center align-text-center fs-3 mx-auto"><i class="bi bi-exclamation-circle"></i> Pending Transactions</p>
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
                            <tr>
                                <th scope="col">Equipment</th>
                                <th scope="col">Last</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>John</td>
                                <td>@social</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <?php include('footer.links.php'); ?>
    <script>
        const dataurl = 'contents/dashboard.data.php';

        // $(document).ready(async function () {
        //     const tables = await Promise.all([
        //         await append_table('pending'),
        //         await append_table('chemicals'),
        //         await append_table('completed'),
        //         await append_table('accepted')
        //     ])
        //     if (!tables) {
        //         alert('Loading table failed.');
        //     }
        // });

        $(document).ready(async function(){
            await append_table('pendingtrans');
            await append_table('pendingchem');
            await append_table('lowchemicals');
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

        async function get_chart_data(data) {
            try {
                const chartData = await $.ajax({
                    method: 'GET',
                    type: 'json',
                    url: dataurl,
                    data: {
                        getChart: data
                    }
                });

                if (chartData) {
                    // create_chart(canvasId, chartData);
                    console.log(chartData);
                }
            } catch (error) {
                console.log(error.responseText);
            }
        }

        const transPie = $('#transPie');
        console.log(transPie);
        $(function() {
            create_chart('transPie', yes);
            get_chart_data('status');
        })

        let yes = {
            pen: 2,
            acc: 4,
            comp: 4,
            voids: 2
        };

        Chart.defaults.color = '#000';

        function create_chart(canvasid, data) {
            const ctx = $(`#${canvasid}`);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Accepted', 'Completed', 'Voided'],
                    datasets: [{
                        // label: ['Pending', 'Accepted', 'Completed', 'Voided'],
                        data: [data.pen, data.acc, data.comp, data.voids],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
</body>

</html>