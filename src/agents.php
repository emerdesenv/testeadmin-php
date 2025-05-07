<?php
    $modal_size = "md";
    include "components/main.php";
?>

<table id="<?= $page_url; ?>" class="dataTable table-main table table-hover table-striped mb-4 bg-white table-bordered">
    <thead>
        <tr>
            <th width="130">Nome</th>
            <th width="130">CNPJ | CPF</th>
            <th width="200">Cidade</th>
            <th>Email</th>
            <th width="150">Telefone</th>
            <th width="150">Celular</th>
            <th width="45">Ativo</th>
            <th width="45">Ações</th>            
        </tr>
    </thead>
</table>

<script type="text/javascript">
    page_data = <?= json_encode($page); ?>;
</script>

<?php require "components/template/footer.php"; ?>