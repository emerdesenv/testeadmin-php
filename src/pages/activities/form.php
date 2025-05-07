<?php
    include "../../functions/main_config.php";

    $id_form = isset($_GET["id"]) ? $_GET["id"] : false;
    $edit    = $data_db->loadGenericObject($id_form, "atividade");
?>

<?php if($edit) { ?>
    <input type="hidden" name="id" value="<?= $edit["id"]; ?>" />
<?php } ?>

<div class="mb-3">
    <label class="form-label" for="codigo">Código</label>
    <input required type="text" class="form-control" name="codigo" id="codigo" maxlength="30" value="<?= $edit ? $edit["codigo"] : ""; ?>" <?= $edit ? "disabled" : ""; ?>>
</div>

<div class="mb-3">
    <label class="form-label" for="nome">Nome</label>
    <input required type="text" class="form-control" name="nome" id="nome" maxlength="45" value="<?= $edit ? $edit["nome"] : ""; ?>">
</div>

<div class="mb-3">
    <label class="form-label" for="descricao">Descrição</label>
    <input required type="text" class="form-control" name="descricao" id="descricao" maxlength="60" value="<?= $edit ? $edit["descricao"] : ""; ?>">
</div> 

<div class="mb-3">
    <label class="form-label" for="seletor_menu">Menu</label>
    <select name="idMenu" id="seletor_menu" class="form-control" >
        <option value="">Selecione um menu</option>
        <?php $data_db->listCombo("menu", "id", "descricao", ($edit ? $edit["idMenu"] : ""), "ORDER BY descricao"); ?>
    </select>
</div>

<div class="mb-3">
    <label class="form-label" for="ordem">Ordem</label>
    <input type="number" class="form-control" name="ordem" id="ordem" maxlength="9" value="<?= $edit["ordem"]; ?>">
</div>

<div class="mb-3">
    <label class="form-label" for="link">Link</label>
    <input type="text" class="form-control" name="link" id="link" maxlength="45" value="<?= $edit ? $edit["link"] : ""; ?>">
</div>

<script>
    $("select[name='idMenu']").select2({
        placeholder: "Selecione um menu",
        minimumResultsForSearch: 5,
        dropdownParent: $(".modal-form")
    });
</script>