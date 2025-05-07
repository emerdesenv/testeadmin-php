<?php
    $modal_size = "md";
    include "components/main.php";
?>

<table id="<?= $page_url; ?>" class="dataTable table-main table table-hover table-striped mb-4 bg-white table-bordered">
    <thead>
        <tr>
            <th width="200">Código</th>
            <th width="300">Nome</th>
            <th width="400">Descrição</th>
            <th width="250">Menu</th>
            <th>Link</th>
            <th width="45">Ações</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    page_data = <?= json_encode($page); ?>;
</script>

<?php require "components/template/footer.php"; ?>