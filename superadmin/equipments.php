<?php
require("startsession.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Equipments</title>
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

            <div class="hstack gap-3 mt-4 mx-3">
                <input class="form-control form-custom me-auto p-2 text-light" type="search" placeholder="Search . . ."
                    id="searchbar" name="searchforafuckingchemical" autocomplete="one-time-code">
                <div class="vr"></div>
                <button type="button" id="loadChem" class="btn btn-sidebar text-light py-3 px-4" data-bs-toggle="modal"
                    data-bs-target="#addModal" data-bs-toggle="tooltip" title="Add Stock"><i
                        class="bi bi-plus-square"></i></button>
            </div>

            <div class="container">
                <div class="row row-cols-1 row-cols-md-5 g-4 mt-1">
                    <div class="col">
                        <div class="card h-100">
                            <img src="../img/template.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural
                                    lead-in to additional content. This content is a little bit longer.</p>
                            </div>
                            <div class="card-footer bg-transparent">Footer</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="../img/template.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a short card.</p>
                            </div>
                            <div class="card-footer bg-transparent">Footer</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="../img/template.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural
                                    lead-in to additional content.</p>
                            </div>
                            <div class="card-footer bg-transparent">Footer</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="../img/template.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural
                                    lead-in to additional content. This content is a little bit longer.</p>
                            </div>
                            <div class="card-footer bg-transparent">Footer</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="../img/template.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural
                                    lead-in to additional content. This content is a little bit longer.</p>
                            </div>
                            <div class="card-footer bg-transparent">Footer</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="../img/template.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural
                                    lead-in to additional content. This content is a little bit longer.</p>
                            </div>
                            <div class="card-footer bg-transparent">Footer</div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>
    <?php include('footer.links.php'); ?>
</body>

</html>