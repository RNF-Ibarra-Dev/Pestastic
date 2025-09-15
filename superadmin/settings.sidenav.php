<aside class="my-2 mx-0 rounded-end-3 sa-sidebar col-sm-2 d-flex flex-column shadow bg-light bg-opacity-25">
    <div class="mt-2 d-flex align-content-center">
        <a href="index.php"
            class="bg-dark bg-opacity-25 mx-auto border border-light rounded-pill btn btn-sidebar overflow-hidden shadow-sm">
            <img src="../img/logo.svg" alt="Pestastic_logo" class="mx-auto my-2 img-fluid" style="max-height: 5rem">
        </a>
    </div>
    <ul class="mt-3 navbar-nav align-content-start">
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 text-light fw-light fs-6 ps-2 <?= $page == 'index.php' ? 'btn-active' : ''; ?>"
                href="index.php">

                <div class="text-light fw-medium text-shadow fs-5"><i class="bi bi-house me-3"></i>Dashboard</div>
            </a>
        </li>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 p-2 <?= $page == 'accountsettings.php' ? 'btn-active' : ''; ?>"
                href="accountsettings.php">

                <div class="text-light fw-medium fs-5 text-shadow"><i
                        class="bi bi-info-circle <?= $page == 'accountsettings.php' ? 'color-accent' : '' ?> me-3"></i>Manage
                    Information</div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'contents.php' ? 'btn-active' : ''; ?>"
                href="contents.php">

                <div class="text-light fw-medium fs-5 text-shadow"><i
                        class="bi <?= $page == 'contents.php' ? 'bi-file-text-fill color-accent' : 'bi-file-text' ?> me-3"></i>Manage
                    Contents</div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'add-manager.php' ? 'btn-active' : ''; ?>"
                href="add-manager.php">

                <div class="text-light fw-medium fs-5 text-shadow"><i
                        class="bi <?= $page == 'add-manager.php' ? 'bi-person-fill-gear color-accent' : 'bi-person-gear' ?> me-3"></i>Add Manager</div>
            </a>
        </li>
    </ul>

</aside>