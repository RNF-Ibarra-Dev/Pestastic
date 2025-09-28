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

            let header = $(th).find("input").length > 0;

            let content = header ? null : $(th).text().trim();

            headers[i] = content;

        });

        $table.find("tbody tr").each(function () {
            $(this).find("td").each(function (i, td) {
                if (headers[i]) {
                    $(td).attr("data-label", headers[i] + ": ");
                } else {
                    $(td).removeAttr("data-label");
                }
            });
        });
    }

    $(document).ajaxComplete(function () {
        $("table:not(table.fc-scrollgrid)").each(function () {
            applyTableLabels($(this));
        });
    })
</script>