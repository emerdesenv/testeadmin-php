<?php
    $modal_size = "md";
    include "components/main.php";
?>

<table id="<?= $page_url; ?>" class="dataTable table-main table table-hover table-striped mb-4 bg-white table-bordered">
    <thead>
        <tr>   
            <th width="45">Código</th>
            <th>Nome</th>
            <th width="120">CPF</th>
            <th width="100">Benefício</th>
            <th width="200">Celular</th>
            <th width="200">Cidade</th>
            <th width="40">Ativo</th>
            <th width="60">Agendar</th>          
            <th width="40">Info</th>
            <th width="45">Ações</th>
        </tr>
    </thead>
</table>

<script type="text/javascript"> 
    page_data = <?= json_encode($page); ?>;
</script>

<?php require "components/template/footer.php"; ?>