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
                    <h1 class=" display-6 mx-auto">Welcome Technician<br><strong>
                            <?= $_SESSION['firstName'] . ' ' . $_SESSION['lastName'] ?></strong>
                    </h1>
                    <p class="fs-3"></p>
                </div>
                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <p class="text-center fs-3 mx-auto">Pending Transactions</p>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption>Check your <a href="transactions.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            for more.</caption>
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody id="pending" class=""></tbody>
                    </table>
                </div>
                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <p class="text-center fs-3 mx-auto">Recent Transactions</p>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption class="mt-auto">Check your <a href="transactions.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            for more.</caption>
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody id="completed" class=""></tbody>
                    </table>
                </div>

            </div>

            <div class="row m-2 gap-2">

                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <p class="text-center fs-3 mx-auto">Approved Transactions</p>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption>Check your <a href="transactions.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">transactions</a>
                            for more.</caption>
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody id="accepted" class=""></tbody>
                    </table>
                </div>
                <div class="col bg-light bg-opacity-25 border rounded p-3 shadow">
                    <p class="text-center fs-3 mx-auto">Available Chemicals</p>
                    <table class="table-hover rounded overflow-hidden table">
                        <caption>Check <a href="inventory.php"
                                class=" link-underline-opacity-0 link-underline link-body-emphasis link-underline-opacity-0-hover">inventory</a>
                            for more.</caption>
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Brand</th>
                                <th scope="col">Level</th>
                            </tr>
                        </thead>
                        <tbody id="chemicals"></tbody>
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
        const tech = <?= $_SESSION['techId'] ?>;
        console.log(tech);

        $(document).ready(async function () {
            const tables = await Promise.all([
                await append_table('pending'),
                await append_table('chemicals'),
                await append_table('completed'),
                await append_table('accepted')
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



    </script>
</body>

</html>