<aside class="sa-sidebar col-sm-2 d-flex flex-column shadow bg-light bg-opacity-25">
    <div class="mt-2 d-flex align-content-center">

        <p class="text-wrap fs-4 mb-0 ms-3 mt-1 mx-auto"><img src="../img/pestasticlogoonly.png" alt="Pestastic_logo"
                class="mt-2 img-fluid align-self-center me-4 mb-2" style="max-height: 2.5rem">Inventory</p>
    </div>
    <ul class="mt-3 navbar-nav align-content-start">
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 text-light fw-light fs-6 ps-2 <?= $page == 'index.php' ? 'btn-active' : ''; ?>"
                href="index.php">
                <i class="bi bi-house me-3"></i>
                <div class="text-light fw-light fs-6">Dashboard</div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 p-2 <?= $page == 'inventory.php' ? 'btn-active' : ''; ?>"
                href="inventory.php">
                <i class="bi bi-archive me-3"></i>
                <div class="text-light fw-lighter fs-7">Inventory</div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 p-2 <?= $page == 'equipments.php' ? 'btn-active' : ''; ?>"
                href="equipments.php">
                <i class="bi bi-tools me-3"></i>
                <div class="text-light fw-lighter fs-7">Equipment</div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'transactions.php' ? 'btn-active' : ''; ?>"
                href="transactions.php">
                <i class="bi bi-file-text me-3"></i>
                <div class="text-light fw-lighter fs-7">Transactions</div>
            </a>
        </li>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 " href="../includes/logout.inc.php">
                <i class="bi bi-box-arrow-left me-3"></i>
                <div class="text-light fw-lighter fs-7">Log out</div>
            </a>
        </li>
    </ul>

</aside>