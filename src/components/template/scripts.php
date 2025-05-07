<script src="node_modules/jquery/dist/jquery.min.js"></script>
<script src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="assets/js/mask/jquery.mask.min.js"></script>
<script src="assets/js/internal/letter_avatar.js"></script>

<script src="node_modules/toastr/build/toastr.min.js"></script>
<script src="node_modules/js-cookie/dist/js.cookie.js"></script>

<link rel="stylesheet" type="text/css" href="node_modules/toastr/build/toastr.min.css" />
<link rel="stylesheet" type="text/css" href="node_modules/bootstrap-icons/font/bootstrap-icons.min.css">

<script src="assets/js/main.js"></script>

<link rel="stylesheet" type="text/css" href="assets/css/select2/select2.min.css" />
<script src="assets/js/select2/select2.min.js"></script>

<link href="assets/css/cdn-fullcalendar/main.min.css" rel="stylesheet">
<script src="assets/js/cdn-fullcalendar/main.min.js"></script>

<?php if($is_table) { ?>
    <script type="text/javascript" charset="utf8" src="node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>

    <!-- Responsável pela responsividade do sistema -->

    <script type="text/javascript" src="assets/js/cdn-datatable/dataTables.responsive.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/cdn-datatable/dataTables.responsive.css"/>

    <script type="text/javascript" src="assets/js/cdn-datatable/date-eu.js"></script>
    <script type="text/javascript" src="assets/js/cdn-datatable/formatted-numbers.js"></script>
    <script type="text/javascript" src="assets/js/cdn-datatable/any-number.js"></script>

    <!-- Responsável pelos checkbox do datatable -->

    <script type="text/javascript" charset="utf8" src="node_modules/jquery-datatables-checkboxes/js/dataTables.checkboxes.min.js"></script>
    <link rel="stylesheet" type="text/css" href="node_modules/jquery-datatables-checkboxes/css/dataTables.checkboxes.css"/>
    
    <script type="text/javascript" charset="utf8" src="assets/js/datatables/accent-neutralise.js"></script>
    <link rel="stylesheet" type="text/css" href="node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css"/>

    <script type="text/javascript" src="assets/js/internal/form.js"></script>
    <script type="text/javascript" src="assets/js/internal/table.js"></script>
   
    <script type="text/javascript" src="assets/js/internal/request_filters/datatable.js"></script>
<?php } ?>

<script type="text/javascript" src="assets/js/internal/request_filters/report.js"></script>

<!-- JS dos Filtros -->

<?php if(vTable() and vFilters($page_url)) { ?>
    <script type="text/javascript" src="assets/js/internal/prepare_filters/datatable.js"></script>
<?php } ?>

<?php if(vGraphics($page_url) and vFilters($page_url)) { ?>
    <script type="text/javascript" src="assets/js/internal/prepare_filters/graphic.js"></script>
<?php } ?>

<?php if(vFilters($page_url)) { ?>
    <script type="text/javascript" src="assets/js/internal/filter_settings.js"></script>
<?php } ?>

<!-- DatePicker -->

<script type="text/javascript" charset="utf8" src="assets/js/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" charset="utf8" src="assets/js/bootstrap-datepicker/locales/bootstrap-datepicker.pt-BR.min.js"></script>
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css">

<!-- Moment JS -->

<script type="text/javascript" src="assets/js/momentjs/moment.min.js"></script>
<script src="assets/js/momentjs/pt-br.js"></script>

<!-- DateRangePicker -->

<script type="text/javascript" src="assets/js/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="assets/js/daterangepicker/daterangepicker.css" />

<!-- Totalizadores -->

<?php if(vTotals($page_url)) { ?>
    <script type="text/javascript">
        $(function() {
            TotalsObj.initialize();
        });
    </script>

    <script type="text/javascript" src="assets/js/internal/request_filters/total.js"></script>
    <script type="text/javascript" src="assets/js/internal/prepare_filters/total.js"></script>
<?php } ?>

<!-- Graficos -->

<?php if(vGraphics($page_url)) { ?>
    <script src="node_modules/chart.js/dist/chart.umd.js"></script>
    <script type="text/javascript" src="assets/js/gauge/gauge.min.js"></script>

    <script type="text/javascript">
        $(function() {
            GraphicObj.initialize();
        });
    </script>

    <script type="text/javascript" src="assets/js/internal/request_filters/graphic.js"></script>
    <script type="text/javascript" src="assets/js/internal/prepare_filters/graphic.js"></script>
<?php } ?>

<!-- CSS de Impressão -->

<script type="text/javascript" src="assets/js/internal/print.js"></script>