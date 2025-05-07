<?php
    $modal_size = "md";
    include "components/main.php";
?>

<table id="<?=$page_url?>" class="dataTable table-main table table-hover table-striped mb-4 bg-white table-bordered">
    <thead>
        <tr>
            <th width="40"></th>
            <th width="200">Menu</th>
            <th width="200">Usuário</th>
            <th>Atividade</th>
            <th width="60">Inclusão</th>
            <th width="60">Alteração</th>
            <th width="60">Exclusão</th>
            <th width="45">Ações</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    page_data = <?= json_encode($page); ?>;
</script>

<?php require "components/template/footer.php"; ?>