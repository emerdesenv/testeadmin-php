<?php
    $modal_size = "md";
    include "components/main.php";
?>

<table id="<?= $page_url; ?>" class="dataTable table-main table table-hover table-striped mb-4 bg-white table-bordered">
    <thead>
        <tr>
            <th width="40"></th>
            <th width="400">Código</th>
            <th>Nome</th>
            <th width="45">Ações</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    page_data = <?= json_encode($page); ?>;
</script>

<?php require "components/template/footer.php"; ?>

<style>
    @media screen and (max-width: 767px){
        .dataTables_paginate {
            float:none !important;
            padding: 10px 0px;
        }
    }
    .note-btn-group > .dropdown-menu {
        width: 200px;
    }
</style>

<!-- include summernote css/js -->
<link href="node_modules/summernote/dist/summernote-bs4.css" rel="stylesheet">
<script src="node_modules/summernote/dist/summernote-bs4.min.js"></script>
<script src="node_modules/summernote/dist/lang/summernote-pt-BR.min.js"></script>
