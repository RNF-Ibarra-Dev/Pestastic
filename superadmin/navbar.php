<nav
    class="navbar navbar-expand-sm my-2 mx-2 rounded-3 border border-light navbar-dark bg-dark bg-opacity-50 pt-1 pb-2 shadow">
    <div class="container-fluid">
        <div>
            <p class="text-wrap fs-2 m-0 ms-2 text-align-center fw-bold">Pestastic Inventory Management</p>
            <?php
            echo "<p class='ms-3 my-0 fw-light text-light'>" . date('l, F jS, Y \| h:i A') . "</p>";
            ?>
        </div>

        <div class="gap-3 d-flex ms-auto border bg-dark bg-opacity-50 rounded-pill px-2 py-1">
            <button type="button" data-bs-target="#notifications" data-bs-toggle="modal"
                class="navbar-brand btn user-icon rounded-circle m-0 shadow-lg p-0" id="notifbtn"><i alt="notification"
                    class="rounded-circle bi bi-app-indicator ms-auto">
                </i>
                <!-- <span class="visually-hidden">unread messages</span> -->
            </button>
            <button type="button" data-bs-target="#settings" data-bs-toggle="modal"
                class="navbar-brand btn user-icon rounded-circle m-0 shadow-lg p-0"><i alt="user"
                    class="rounded-circle bi bi-person ms-auto"></i></button>
            <p class="text-light my-auto me-2 text-wrap text-capitalize user-select-none">
                <?= $_SESSION['fname'] . ' ' . $_SESSION['lname'] ?></p>

        </div>
    </div>
</nav>


<div class="modal fade bg-dark bg-opacity-25" id="settings" tabindex="-1">
    <div class="modal-dialog me-4 w-25 shadow-lg" style="margin-top: 5rem !important;">
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


<div class="modal fade bg-dark bg-opacity-25" id="notifications" tabindex="-1">
    <div class="modal-dialog me-4 w-25 shadow-lg" style="margin-top: 5rem !important;">
        <div class="modal-content text-dark rounded-4 border-0 shadow-lg">
            <div class="modal-header position-relative">
                <h1 class="modal-title fs-5 mx-auto">Alerts</h1>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"
                    style="position: absolute; right: 0.4rem;"></button>
            </div>
            <div class="modal-body py-4 px-2 gap-2 d-flex flex-column">
                <ul class="list-group" id="notifContainer">

                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(async function () {
        $.ajax({
            url: 'tablecontents/notifications.php',
            method: 'GET',
            dataType: 'json',
            data: {
                notifications: true
            }
        })
            .done(function (d) {
                $('#notifContainer').append(d.notif);
                $('#notifbtn').append(d.countbadge);
                console.log(d);
            })
            .fail(function (e) {
                console.log(e);
            });
    })
</script>