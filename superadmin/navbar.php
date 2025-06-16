<nav class="navbar navbar-expand-sm navbar-dark bg-light bg-opacity-25 shadow navbar-sticky">
    <div class="container-fluid">
        <button type="button" data-bs-target="#settings" data-bs-toggle="modal"
            class="navbar-brand btn user-icon ms-auto"><i alt="user"
                class="rounded-circle bi bi-person ms-auto"></i></button>
    </div>
</nav>


<div class="modal fade" id="settings" tabindex="-1" data-bs-backdrop="false">
    <div class="modal-dialog mt-5 me-4 w-25 shadow-lg">
        <div class="modal-content text-dark rounded-4 border-0 shadow-lg">
            <div class="modal-header position-relative">
                <h1 class="modal-title fs-5 mx-auto">Settings</h1>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"
                    style="position: absolute; right: 0.4rem;"></button>
            </div>
            <div class="modal-body pb-2 px-2 gap-2 d-flex flex-column">
                <ul class="list-group">
                    <li class="list-group-item p-0 rounded-top-4">
                        <a href="accountsettings.php"
                            class="nav-link btn btn-sidebar m-0 py-2 rounded-top-4 fw-light d-flex flex-column align-items-center justify-content-center gap">
                            <i class="bi bi-gear fs-5 account-settings-icon"></i>
                            <span class="fs-5">Account</span>
                            <span class="secondary-body-text fw-light text-muted">Change Password | Change Name</span>
                        </a>
                    </li>
                    <li class="list-group-item p-0 rounded-bottom-4">
                        <a href="contents.php"
                            class="nav-link btn btn-sidebar m-0 py-2 fw-light d-flex align-items-center flex-column justify-content-center gap rounded-bottom-4">
                            <i class="bi bi-gear fs-5 account-settings-icon"></i>
                            <span class="fs-5">Content</span>
                            <span class="secondary-body-text fw-light text-muted">Treatments | Customer Problems |
                                Branches</span>
                        </a>
                    </li>
                    <!-- <li class="list-group-item p-0 rounded-bottom-4">
                        <a href=""
                            class="nav-link btn btn-sidebar m-0 py-2 rounded-bottom-4 fw-light d-flex align-items-center justify-content-center gap">
                            <i class="bi bi-gear fs-5 account-settings-icon"></i>
                            <span class="fs-5">Manage Account</span>
                        </a>
                    </li> -->
                </ul>
                <ul class="list-group">
                    <li class="list-group-item p-0 rounded-4">
                        <a class="nav-link btn btn-sidebar m-0 py-2 rounded-4 fw-light d-flex align-items-center justify-content-center gap-2"
                            href="../includes/logout.inc.php">
                            <i class="bi fs-5 bi-box-arrow-left account-settings-icon"></i>
                            <span class="fs-5">Log out</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>