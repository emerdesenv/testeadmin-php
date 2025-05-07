<?php
    include "../../functions/main_config.php";

    $id_form = isset($_GET["id"]) ? $_GET["id"] : false;
    $edit    = $data_db->loadGenericObject($id_form, "texto");
?>

<?php if($edit) { ?>
    <input type="hidden" name="id" value="<?= $edit["id"]; ?>" />
<?php } ?>

<div class="row">
    <div class="mb-3 col-md-12">
        <label class="form-label" for="codigo">Código</label>
        <input required type="text" class="form-control" name="codigo" id="codigo" maxlength="60" value="<?= $edit ? $edit["codigo"] : ""; ?>">
    </div>
</div>

<div class="row">
    <div class="mb-3 col-md-12">
        <label class="form-label" for="nome">Nome</label>
        <input required type="text" class="form-control" name="nome" id="nome" maxlength="60" value="<?= $edit ? $edit["nome"] : ""; ?>">
    </div>
</div>

<div class="row">
    <div class="mb-3 col-md-12">
        <label class="form-label" for="descricao">Descrição</label>
        <textarea required name="descricao" id="descricao"><?= $edit ? $edit["descricao"] : ""; ?></textarea>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#descricao').summernote({
            placeholder: 'Descrição do Texto',
            dialogsInBody: true,
            minHeight: 300,
            lang: "pt-BR"
        });
    });
</script>

<!-- include summernote css/js -->
<link href="node_modules/summernote/dist/summernote-bs4.css" rel="stylesheet">
<script src="node_modules/summernote/dist/summernote-bs4.min.js"></script>
<script src="node_modules/summernote/dist/lang/summernote-pt-BR.min.js"></script>