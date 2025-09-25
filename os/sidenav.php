<aside class="sa-sidebar col-sm-2 my-2 rounded-end-3 d-flex flex-column shadow bg-light bg-opacity-25 hidden"
    id="sidenav">
    <button type="button" class="btn position-absolute z-3 fs-3 m-0 p-0 d-none" id="sidenav_toggle"><i
            class="bi bi-arrow-right-circle-fill text-light color-accent"></i></button>
    <div class="mt-2 d-flex" id="sidenav_header">
        <a href="index.php"
            class="bg-dark bg-opacity-50 mx-auto border border-light rounded-pill btn btn-sidebar overflow-hidden shadow-sm">
            <img src="../img/logo.svg" alt="Pestastic_logo" class="mx-auto my-2 img-fluid" style="max-height: 5rem">
        </a>
    </div>
    <ul class="mt-3 navbar-nav align-content-start">
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 text-light fw-medium fs-5 ps-2 <?= $page == 'index.php' ? 'btn-active' : ''; ?>"
                href="index.php">

                <div class="text-light fw-medium fs-5 d-flex justify-contents-evenly"><i
                        class="bi <?= $page == 'index.php' ? "bi-house-fill color-accent" : "bi-house" ?> me-3"></i>
                        <p class="m-0">Dashboard</p>
                </div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 p-2 <?= $page == 'inventory.php' ? 'btn-active' : ''; ?>"
                href="inventory.php">

                <div class="text-light fw-medium fs-5 d-flex justify-contents-evenly"><i
                        class="bi <?= $page == 'inventory.php' ? "bi-box-seam-fill color-accent" : "bi-box-seam" ?> me-3"></i>
                        <p class="m-0">Inventory</p>
                </div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'queue.php' ? 'btn-active' : ''; ?>"
                href="queue.php">

                <div class="text-light fw-medium fs-5 d-flex justify-contents-evenly"><i
                        class="bi bi-list <?= $page == 'queue.php' ? "color-accent" : "" ?> me-3"></i>
                        <p class="m-0">Queue</p></div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-sidebar my-1 ps-2 <?= $page == 'transactions.php' ? 'btn-active' : ''; ?>"
                href="transactions.php">

                <div class="text-light fw-medium fs-5 d-flex justify-contents-evenly"><i
                        class="bi <?= $page == 'transactions.php' ? "bi-file-text-fill color-accent" : "bi-file-text" ?> me-3"></i>
                        <p class="m-0">Transactions</p>
                </div>
            </a>
        </li>

    </ul>

</aside>


<script>
    const sidenav = $("aside");
    const button = $("#sidenav_toggle");

    // $(function () {
    //     $("aside#sidenav").toggleClass('hidden');

    // })
    $(document).on('mouseup', function (event) {
        if (!$(event.target).closest("aside#sidenav, #sidenav_toggle").length) {
            $("aside#sidenav").removeClass('shown').addClass('hidden');
        }
    })

    $(document).on('click', "#sidenav_toggle", function () {
        $("aside#sidenav").toggleClass('hidden shown');
        $('.sidenav_backdrop').toggleClass('visibility-hidden');
    })


</script>