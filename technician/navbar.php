<style>
    li a div p {
        text-align: justify !important;
        text-justify: inter-word !important;
    }

    li>a>div {
        padding-right: 1rem !important;
    }
</style>

<nav
    class="navbar navbar-expand-sm my-2 mx-2 rounded-3 border border-light navbar-dark bg-dark bg-opacity-50 pt-1 pb-2 shadow">
    <div class="container-fluid">
        <div class="pestastic-navbar-text user-select-none">
            <p class="text-wrap fs-1 m-0 ms-2 text-align-center fw-bold">Pestastic Inventory Management</p>
            <img src="../img/logo.svg"
                class="d-none btn w-25 bg-light bg-opacity-25 mt-1 p-1 rounded-3 logo-img-small-screen">
            <?php
            echo "<p class='ms-3 my-0 fw-medium fs-5 fw-light text-light' id='datetime'>" . date('l, F jS, Y \| h:i:s A') . "</p>";
            ?>
        </div>

        <div class="gap-3 d-flex ms-auto border bg-dark bg-opacity-50  rounded-pill px-2 py-1">
            <button type="button" data-bs-target="#notifications" data-bs-toggle="modal"
                class="navbar-brand btn user-icon rounded-circle m-0 shadow-lg p-0"><i alt="notification"
                    class="rounded-circle bi bi-app-indicator ms-auto">
                </i><span id="notifbtn"></span>
                <!-- <span class="visually-hidden">unread messages</span> -->
            </button>
            <button type="button" data-bs-target="#settings" data-bs-toggle="modal"
                class="navbar-brand btn user-icon rounded-circle m-0 shadow-lg p-0"><i alt="user"
                    class="rounded-circle bi bi-person ms-auto"></i></button>
            <p class="text-light my-auto me-2 fs-5 text-wrap text-capitalize user-select-none" id="nav_name">
                <?= ($_SESSION['firstName'] ?? '') . ' ' . ($_SESSION['lastName'] ?? '') ?>
            </p>

        </div>
    </div>
</nav>


<div class="modal fade bg-dark bg-opacity-25" id="settings" tabindex="-1">
    <div class="modal-dialog me-4 w-25 shadow-lg" style="margin-top: 5rem ;">
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
    <div class="modal-dialog me-4 w-25 shadow-lg" style="margin-top: 5rem ;">
        <div class="modal-content text-dark rounded-4 border-0 shadow-lg">
            <div class="modal-header position-relative">
                <h1 class="modal-title fs-4 fw-bold mx-auto">Alerts</h1>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    let notif_audio = new Audio('../sounds/notification.wav');

    async function get_notif() {
        $.ajax({
            url: 'contents/notifications.php',
            method: 'GET',
            dataType: 'json',
            data: {
                notifications: true
            }
        })
            .done(async function (d) {
                $("#notifContainer").empty();
                $("#notifbtn").empty();
                $('#notifContainer').append(d.notif);
                $('#notifbtn').append(d.countbadge);
                if (d.new) {
                    await show_toast("New alert. Total alerts: " + d.count);
                    notif_audio.play();
                    showDesktopNotification("New alert. Total alerts: " + d.count);
                }
            })
            .fail(function (e) {
                console.log(e);
            });
    }
    get_notif();
    setInterval(get_notif, 6000);

    function updateDateTime() {
        document.getElementById('datetime').textContent = moment().format('dddd, MMMM Do, YYYY | hh:mm:ss A');
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);

    $(function () {
        var windowWidth = $(window).width();
        if (windowWidth <= 768) {
            $('#notifications > div.modal-dialog, #settings > div.modal-dialog').addClass('modal-dialog-centered');
        }

        $(window).resize(function () {
            var windowWidth = $(window).width();
            if (windowWidth <= 768) {
                $('#notifications > div.modal-dialog, #settings > div.modal-dialog').addClass('modal-dialog-centered');
            } else {
                $('#notifications > div.modal-dialog, #settings > div.modal-dialog').removeClass('modal-dialog-centered');
            }
        });
    })

    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    function showDesktopNotification(message) {
        if (Notification.permission === "granted") {
            new Notification("Pestastic Inventory", {
                body: message,
                icon: "../img/logo.svg"
            });
        }
    }

    const pestastic_icon = "../img/logo.svg";
    const icon_alert = "../img/logo-alert.svg";

    var bell = "\u{1F514}";
    var og_title = document.title;
    var title_blinking = false;
    var blink_interval;
    async function title_notify() {
        if (!title_blinking) {
            title_blinking = true;
            blink_interval = setInterval(function () {
                document.title = document.title == og_title ? `${bell} - New Notification | ${og_title}` : og_title;
            }, 1500);
        }
    }

    function stop_notif() {
        if (title_blinking) {
            title_blinking = false;
            clearInterval(blink_interval);
            document.title = og_title;
        }
    }

    async function check_new_transactions() {
        const tech = <?= $_SESSION['techId'] ?>;
        $.get('contents/polling.php', { tech_id: tech }, function (d) {
            if (d.new) {
                show_toast("You're assigned to a new transaction. Transaction ID: " + d.latest_id);
                notif_audio.play();
                showDesktopNotification("You're assigned to a new transaction. Transaction ID: " + d.latest_id);
                $("link[rel='icon']").attr('href', icon_alert);
                if (document.hasFocus()) {
                    title_notify();
                }
            }
        }, 'json').fail(function (e) {
            console.log(e);
        });
    }
    setInterval(check_new_transactions, 3000);

    $(window).on('focus', function () {
        $("link[rel='icon']").attr('href', pestastic_icon);
        stop_notif();
    });
</script>