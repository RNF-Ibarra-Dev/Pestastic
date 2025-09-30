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
    (() => {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // const forms = document.querySelectorAll('.needs-validation');
        const forms = $("form[novalidate]");

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })()

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
    })
</script>