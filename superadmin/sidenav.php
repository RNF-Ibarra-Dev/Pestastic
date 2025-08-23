<aside class="sa-sidebar col-sm-2 d-flex flex-column shadow bg-light bg-opacity-25">
    <div class="mt-2 d-flex align-content-center">
        <a href="index.php" class="bg-dark bg-opacity-25 mx-auto border border-light rounded-pill btn btn-sidebar overflow-hidden shadow-sm">
            <img src="../img/logo.svg" alt="Pestastic_logo"
                class="mx-auto my-2 img-fluid" style="max-height: 5rem">
        </a>
    </div>
    <ul class="mt-3 navbar-nav align-content-start">
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 text-light fw-medium fs-5 ps-2 <?= $page == 'index.php' ? 'btn-active' : ''; ?>"
                href="index.php">
                
                <div class="text-light fw-medium fs-5"><i class="bi <?= $page == 'index.php' ? "bi-house-fill color-accent" : "bi-house" ?> me-3"></i>Dashboard</div>
            </a>
        </li>
        <!-- Manage accounts dropdown -->
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 side-dropdown <?= $page == 'tech.acc.php' || $page == 'os.acc.php' ? 'btn-active' : ''; ?>"
                data-bs-toggle="collapse"
                aria-expanded="<?= $page == 'tech.acc.php' || $page == 'os.acc.php' ? 'true' : 'false'; ?>"
                data-bs-target="#accounts">
                
                <div class="text-light fw-medium fs-5"><i class="bi <?= $page == 'tech.acc.php' || $page == 'os.acc.php' ? "bi-people-fill color-accent" : "bi-people" ?> me-3"></i>Employee Accounts</div>
                <i class="bi bi-chevron-compact-left ms-auto me-3 "></i>
            </a>
            <ul id="accounts"
                class="navbar-nav collapse navbar-collapse acc-dropdown <?= $page == 'tech.acc.php' || $page == 'os.acc.php' ? 'show' : ''; ?>">
                <li class="nav-item">
                    <a class="nav-link btn btn-sidebar my-1 ps-3 <?= $page == 'tech.acc.php' ? 'btn-active-dropdown' : ''; ?>"
                        href="tech.acc.php">
                       
                        <div class="text-light fw-medium fs-5"> <i class="bi <?= $page == 'tech.acc.php' ? "bi-person-badge-fill color-accent" : "bi-person-badge" ?> me-3"></i>Technicians</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sidebar my-1 ps-3 <?= $page == 'os.acc.php' ? 'btn-active-dropdown' : ''; ?>"
                        href="os.acc.php">
                        
                        <div class="text-light fw-medium fs-5"><i class="bi <?= $page == 'os.acc.php' ? "bi-person-badge-fill color-accent" : "bi-person-badge" ?> me-3"></i>Operations Supervisors</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 p-2 <?= $page == 'itemstock.php' ? 'btn-active' : ''; ?>"
                href="itemstock.php">
                
                <div class="text-light fw-medium fs-5"><i class="bi <?= $page == 'itemstock.php' ? "bi-box-seam-fill color-accent" : "bi-box-seam" ?> me-3"></i>Inventory</div>
            </a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'equipments.php' ? 'btn-active' : ''; ?>"
                href=".php">
                <i class="bi bi-tools me-3"></i>
                <div class="text-light fw-medium fs-5">Equipment</div>
            </a>
        </li> -->
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'queue.php' ? 'btn-active' : ''; ?>"
                href="queue.php">
                
                <div class="text-light fw-medium fs-5"><i class="bi bi-list <?= $page == 'queue.php' ? "color-accent" : "" ?> me-3"></i>Queue</div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'transactions.php' ? 'btn-active' : ''; ?>"
                href="transactions.php">
                
                <div class="text-light fw-medium fs-5"><i class="bi <?= $page == 'transactions.php' ? "bi-file-text-fill color-accent" : "bi-file-text" ?> me-3"></i>Transactions</div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'account.php' ? 'btn-active' : ''; ?>"
                href="account.php">
                
                <div class="text-light fw-medium fs-5"><i class="bi <?= $page == 'account.php' ? "bi-person-fill-add color-accent" : "bi-person-add" ?> me-3"></i>Create Employee Account</div>
            </a>
        </li>
    </ul>

</aside>