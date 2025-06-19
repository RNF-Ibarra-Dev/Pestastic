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
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 p-2 <?= $page == 'accountsettings.php' ? 'btn-active' : ''; ?>"
                href="accountsettings.php">
                <i class="bi bi-info-circle me-3"></i>
                <div class="text-light fw-lighter fs-7">Manage Information</div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'contents.php' ? 'btn-active' : ''; ?>"
                href="contents.php">
                <i class="bi bi-card-text me-3"></i>
                <div class="text-light fw-lighter fs-7">Manage Contents</div>
            </a>
        </li>
    </ul>

</aside>