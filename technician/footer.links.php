<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
<script src='fullcalendar/dist/index.global.js'></script>
<script>
    function applyTableLabels($table) {
        let headers = [];
        $table.find("thead th").each(function (i, th) {
            headers[i] = $(th).text().trim();
        });

        $table.find("tbody tr").each(function () {
            $(this).find("td").each(function (i, td) {
                $(td).attr("data-label", headers[i].length ? headers[i] + ': ' : '');
            });
        });
    }

    $(document).ajaxComplete(function () {
        // on page load
        $("table").each(function () {
            applyTableLabels($(this));
        });
    });

    $(document).on('keydown', 'input[type="number"]', function (e) {
        if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
            e.preventDefault();
        }
    });

    $(document).on('keydown', 'input.name-input', function (e) {
        if (e.key.length > 1 || e.ctrlKey || e.metaKey) {
            return;
        }
        let pattern = /^[a-zA-Z ]$/;

        if (!pattern.test(e.key)) {
            e.preventDefault();
        }
    });

    $(document).on('keydown', 'input.empid-input', function (e) {
        let input = $(this).val();
        if (input.length >= 3 && e.key.length === 1) {
            e.preventDefault();
        }
    });
    $(document).on('keydown', 'input.contact-no-input', function (e) {
        let input = $(this).val();
        if (input.length >= 11 && e.key.length === 1) {
            e.preventDefault();
        }
    });
</script>