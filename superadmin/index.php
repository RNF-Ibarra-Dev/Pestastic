<?php
require("startsession.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager | Pestastic Inventory</title>
    <?php
    include("header.links.php");
    ?>
</head>


<body class="bg-official text-light">

    <div class="sa-bg container-fluid p-0 h-100 d-flex">
        <!-- sidebar -->

        <?php
        include("sidenav.php");
        ?>

        <!-- main content or right side -->
        <main class="sa-content col-sm-10 p-0 container-fluid">
            <!-- navbar -->
            <?php
            include("navbar.php");
            ?>
            <!-- information container -->


            <div class="mx-2 bg-light bg-opacity-25  rounded-3 p-3 shadow">
                <h1 class="text-center display-6 fw-medium">Welcome Manager <strong class="text-capitalize ">
                        <?= $_SESSION['saUsn'] ?></strong>
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
                        class="me-4 ms-2 bi bi-geo-alt-fill flex-grow-0 my-auto shadow-sm fs-4 bg-color-accent bg-opacity-25 py-2 px-3 rounded-circle align-middle text-center"></i>
                    <div class="d-flex flex-column">
                        <p class="fw-bold fs-5">Dispatched Technicians</p>
                        <span id="dispatched_tech" class="fw-medium fs-5"></span>
                    </div>
                </div>
                <div class="col bg-light rounded-3 bg-opacity-25 d-flex py-3">
                    <i
                        class="me-4 ms-2 bi bi-calendar2-check-fill flex-grow-0 my-auto shadow-sm fs-4 bg-info bg-opacity-50 py-2 px-3 rounded-circle align-middle text-center"></i>
                    <div class="d-flex flex-column">
                        <p class="fw-bold fs-5">Weekly Transactions</p>
                        <span id="weekly_completed" class="fw-medium fs-5"></span>
                    </div>
                </div>
                <div class="col bg-light rounded-3 bg-opacity-25 d-flex py-3">
                    <i
                        class="me-4 ms-2 bi bi-exclamation-triangle-fill flex-grow-0 my-auto shadow-sm fs-4 bg-warning bg-opacity-50 py-2 px-3 rounded-circle align-middle text-center"></i>
                    <div class="d-flex flex-column">
                        <p class="fw-bold fs-5">Urgent Restocks</p>
                        <span id="urgent_restocks" class="fw-medium fs-5"></span>
                    </div>
                </div>
                <div class="col bg-light rounded-3 bg-opacity-25 d-flex py-3">
                    <i
                        class="me-4 ms-2 bi bi-journal-bookmark-fill flex-grow-0 my-auto shadow-sm fs-4 bg-danger bg-opacity-50 py-2 px-3 rounded-circle align-middle text-center"></i>
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
                                class="bi bi-clipboard-data-fill  fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                            <p class="text-center fw-medium align-text-center fs-3 w-75 mx-auto">
                                Transactions Status Summary</p>
                        </div>
                        <canvas id="transPie" style="max-height: 30rem !important;"></canvas>
                        <p class="text-light fw-light mt-3">Check <a href="transactions.php"
                                class="color-accent link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            to see all transactions.</p>
                    </div>
                </div>
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 pb-0 shadow">
                    <div class="d-flex flex-column h-100">
                        <div class="clearfix">
                            <i
                                class="bi bi-calendar2-check-fill fs-4 shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                            <p class="text-center fw-medium align-text-center fs-3 w-75 mx-auto">
                                Yearly Completed Transactions</p>
                        </div>
                        <canvas id="yearly_completion" class="my-auto"></canvas>
                        <p class="text-muted mt-3 text-light">Note: Only those with data are displayed.</p>
                    </div>
                </div>
                <div class="col bg-light bg-opacity-25  rounded-3 p-3 pb-0 shadow">
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
            </div>

            <div class="row m-2 gap-2 flex-wrap">
                <div class="col-auto w-100 bg-light bg-opacity-25  rounded-3 p-3 pb-0 shadow">
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
                <div class="col-auto w-100 bg-light bg-opacity-25  rounded-3 p-3 shadow">
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

            <div class="row flex-wrap m-2 gap-2">
                <div class="col-auto w-100 bg-light bg-opacity-25  rounded-3 p-3 pb-0 shadow">
                    <div class="d-flex flex-column h-100">
                        <div class="clearfix">
                            <i
                                class="bi bi-boxes fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded-3 py-1"></i>
                            <p class="text-center fw-medium align-text-center fs-3 w-75 mx-auto">
                                Items Used Weekly (<?= date("F") ?>)</p>
                        </div>
                        <canvas id="item_trend" style="min-height: 20rem !important;" class="my-auto"></canvas>
                        <p class="text-muted mt-3 text-light">Note: This displays data only for this month.</p>
                    </div>
                </div>
                <div class="col-auto w-100 bg-light bg-opacity-25  rounded-3 p-3 shadow d-flex flex-column">
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
                <div class="col-auto w-100 bg-light bg-opacity-25  rounded-3 p-3 shadow">
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
            </div>

            <div class="row flex-wrap m-2 gap-2">
                <div class="col-auto w-100 bg-light bg-opacity-25  rounded-3 p-3 shadow">
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
                <div class="col-auto w-100 bg-light bg-opacity-25 rounded p-3 shadow">
                    <div class="clearfix">
                        <i
                            class="bi bi-exclamation-triangle-fill fs-4  shadow-sm float-start bg-light bg-opacity-25 px-2 rounded py-1"></i>
                        <p class="text-center fw-medium fs-3 w-75 mx-auto">Expiring Items</p>
                    </div>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption class="text-light text-muted">Check <a href="inventory.php?chem=low"
                                class="color-accent link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">inventory</a>
                            to see expired items.</caption>
                        <thead>
                            <tr class="text-center align-middle">
                                <th scope="col">ID</th>
                                <th scope="col">Item</th>
                                <th scope="col">Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody id="expiring_items"></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>


    <?php include("footer.links.php"); ?>

    <script>
        const dataurl = 'tablecontents/index.data.php';

        $(document).ready(async function () {
            await append('pendingtrans');
            await append('pendingchem');
            await append('lowchemicals');
            await append('finalizing_table');
            await append('top_technicians');
            await append('tech_workload');
            await append('expiring_items');
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
                alert(`Error appending container ${container}.`);
                console.error(error);
            }
        }


        // charts
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
                    // console.log(data);
                    create_chart('transPie', data);
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
                    labels: [...data.status],
                    datasets: [{
                        data: [...data.count],
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
                            yAlign: 'bottom',
                            bodyAlign: 'center',
                            titleAlign: 'center',
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            displayColors: false
                        },

                    },

                }
            });
        }

        function fetch_bar(container) {
            $.get(dataurl, { bar: container }, function (d) {
                create_bar(container, d);
                // console.log(d);
            }, 'json')
                .fail(function (e) {
                    alert(`Failed to fetch bar chart ${container}.`);
                    console.log(e);
                });
        }

        const itemTrend = $("#item_trend");

        function create_bar(canvasid, data) {
            const ctx = $(`#${canvasid}`);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [...data.weeks],
                    datasets: [{
                        data: [...data.count],
                        borderWidth: 0.8,
                        backgroundColor: [
                            'rgba(255, 255, 255, 0.98)',
                            'rgba(255, 255, 255, 0.84)',
                            'rgba(255, 255, 255, 0.70)',
                            'rgba(255, 255, 255, 0.56)',
                            'rgba(255, 255, 255, 0.42)',
                            'rgba(255, 255, 255, 0.28)',

                        ],
                        borderColor: '#fff',
                        borderRadius: 5,
                        barThickness: 'flex',
                        maxBarThickness: 30,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            bodyColor: 'white',
                            titleColor: 'white',
                            yAlign: 'right',
                            bodyAlign: 'center',
                            titleAlign: 'center',
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                font:{
                                    size: 12
                                },
                                color: '#fff',
                                // minRotation: 90
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.8)',
                                borderWidth: 1,
                            }
                        },
                        y: {

                            grid: {
                                color: 'rgba(255, 255, 255, 0.8)',
                                borderWidth: 1
                            },
                            ticks: {
                                color: '#fff',
                                font:{
                                    size: 12
                                }
                            },
                        }
                    },

                }
            })
        }

        function fetch_line(container) {
            $.get(dataurl, { line: container }, function (d) {
                create_line(container, d);
            }, 'json')
                .fail(function (e) {
                    alert(`Failed to fetch line chart ${container}.`);
                    console.log(e);
                });
        }

        function create_line(canvasid, data) {
            const ctx = $(`#${canvasid}`);
            // console.log(data.month);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [...data.months],
                    datasets: [{
                        data: [...data.counts],
                        borderWidth: 0.8,
                        backgroundColor: 'rgba(255, 255, 255, 0.98)',
                        borderColor: '#fff',
                    }]
                },
                options: {
                    // indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            bodyColor: 'white',
                            titleColor: 'white',
                            yAlign: 'bottom',
                            bodyAlign: 'center',
                            titleAlign: 'center',
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            displayColors: false
                        },
                    },
                    hover: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#fff',
                                // minRotation: 90
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.8)',
                                borderWidth: 1,
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.8)',
                                borderWidth: 1
                            },
                            ticks: {
                                color: '#fff',
                                stepSize: 1
                            },
                            min: 0,
                            max: function () {
                                var highest = Math.max(...data.counts);
                                return highest + 3;
                            }
                        }
                    },

                }
            })
        }

        $(function () {
            get_chart_data('status');
            fetch_bar('item_trend');
            fetch_line('yearly_completion');
        });
    </script>
</body>

</html>