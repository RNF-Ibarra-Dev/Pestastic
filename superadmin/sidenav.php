<aside class="sa-sidebar col-sm-2 d-flex flex-column shadow bg-light bg-opacity-25">
    <div class="mt-2 d-flex align-content-center">
        <a href="index.php" class="bg-dark bg-opacity-25 mx-auto border border-light rounded-pill btn btn-sidebar overflow-hidden shadow-sm">
            <img src="../img/logo.svg" alt="Pestastic_logo"
                class="mx-auto my-2 img-fluid" style="max-height: 5rem">
        </a>
    </div>
    <hr>
    <ul class=" navbar-nav align-content-start">
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 text-light fw-light fs-6 ps-2 <?= $page == 'index.php' ? 'btn-active' : ''; ?>"
                href="index.php">
                <i class="bi bi-house me-3"></i>
                <div class="text-light fw-light fs-6">Dashboard</div>
            </a>
        </li>
        <!-- Manage accounts dropdown -->
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 side-dropdown <?= $page == 'tech.acc.php' || $page == 'os.acc.php' ? 'btn-active' : ''; ?>"
                data-bs-toggle="collapse"
                aria-expanded="<?= $page == 'tech.acc.php' || $page == 'os.acc.php' ? 'true' : 'false'; ?>"
                data-bs-target="#accounts">
                <i class="bi bi-people me-3"></i>
                <div class="text-light fw-light fs-6">Employee Accounts</div>
                <i class="bi bi-chevron-compact-left ms-auto me-3 "></i>
            </a>
            <ul id="accounts"
                class="navbar-nav collapse navbar-collapse acc-dropdown <?= $page == 'tech.acc.php' || $page == 'os.acc.php' ? 'show' : ''; ?>">
                <li class="nav-item">
                    <a class="nav-link btn btn-sidebar my-1 ps-3 <?= $page == 'tech.acc.php' ? 'btn-active-dropdown' : ''; ?>"
                        href="tech.acc.php">
                        <i class="bi bi-person-badge me-3"></i>
                        <div class="text-light fw-lighter fs-7">Technicians</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sidebar my-1 ps-3 <?= $page == 'os.acc.php' ? 'btn-active-dropdown' : ''; ?>"
                        href="os.acc.php">
                        <i class="bi bi-person-badge me-3"></i>
                        <div class="text-light fw-lighter fs-7">Operations Supervisors</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 p-2 <?= $page == 'itemstock.php' ? 'btn-active' : ''; ?>"
                href="itemstock.php">
                <i class="bi bi-boxes me-3"></i>
                <div class="text-light fw-lighter fs-7">Inventory</div>
            </a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'equipments.php' ? 'btn-active' : ''; ?>"
                href="equipments.php">
                <i class="bi bi-tools me-3"></i>
                <div class="text-light fw-lighter fs-7">Equipment</div>
            </a>
        </li> -->
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'queue.php' ? 'btn-active' : ''; ?>"
                href="queue.php">
                <i class="bi bi-list me-3"></i>
                <div class="text-light fw-lighter fs-7">Queue</div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'transactions.php' ? 'btn-active' : ''; ?>"
                href="transactions.php">
                <i class="bi bi-file-text me-3"></i>
                <div class="text-light fw-lighter fs-7">Transactions</div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'account.php' ? 'btn-active' : ''; ?>"
                href="account.php">
                <i class="bi bi-person-add me-3"></i>
                <div class="text-light fw-lighter fs-7">Create Employee Account</div>
            </a>
        </li>
    </ul>

</aside>