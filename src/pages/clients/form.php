<?php
    include "../../functions/main_config.php";
   
    $id_form = isset($_GET["id"]) ? $_GET["id"] : false;
    $edit    = $data_db->loadGenericObject($id_form, "cliente");
    
    $required     = ($edit && $edit["ativo"] == "N") ? "" : "required";
    $disabled     = ($edit && $edit["ativo"] == "N") ? "disabled" : "";
    $mask         = ($edit && $edit["ativo"] == "N") ? false : true;
    $status_clent = ($edit && $edit["ativo"] == "S") ? "true" : "false";
?>

<?php if($edit) { ?>
    <input type="hidden" name="id" value="<?= $edit["id"]; ?>" />
    <input type="hidden" name="status" id="status" value="<?= $edit["ativo"]; ?>" />
    <input type="hidden" name="idUsuario" id="idUsuario" value="<?= $_SESSION["user"]["id"]; ?>" />
<?php } ?>

<input type="hidden" name="idUsuario" id="idUsuario" value="<?= $_SESSION["user"]["id"]; ?>" />

<div class="row g-2">
    <div class="mb-3 col-md-12">
        <label class="form-label" for="nome">Nome Completo</label>
        <input <?= $required; ?> type="text" class="form-control input-validate" name="nome" id="nome" maxlength="100" <?= $disabled; ?> value="<?= $edit ? trim($edit["nome"]) : ""; ?>">
    </div>

    <div class="mb-3 col-md-12">
        <label class="form-label" for="cpf">CPF</label>
        <input <?= $required; ?> type="text" class="form-control input-validate" name="CPF" id="cpf" maxlength="15" <?= $mask ? "data-mask='###.999.999-99'" : ""; ?> <?= $disabled; ?> value="<?= $edit ? $edit["CPF"] : ""; ?>">
    </div>
</div>

<div class="row g-2">
    <div class="mb-3 col-md-6">
        <label class="form-label" for="celular">Celular</label>
        <input <?= $required; ?> type="tel" class="form-control" name="celular" id="celular" maxlength="15" <?= $mask ? "data-mask='(99) 99999-9999'" : ""; ?> <?= $disabled; ?> value="<?= $edit ? $edit["celular"] : ""; ?>">
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label" for="sexo">Sexo</label>
        <select <?= $required; ?> name="sexo" id="sexo" class="form-control" <?= $disabled; ?>>
            <option value="">Selecione o sexo</option>
            <option <?=($edit and $edit["sexo"] == "F") ? "selected='selected'" : ""; ?> value="F">Feminino</option>
            <option <?=($edit and $edit["sexo"] == "M") ? "selected='selected'" : ""; ?> value="M">Masculino</option>
            <option <?=($edit and $edit["sexo"] == "O") ? "selected='selected'" : ""; ?> value="O">Outro</option>
            <option <?=($edit and $edit["sexo"] == "X") ? "selected='selected'" : ""; ?> value="X">Prefiro não dizer</option>
        </select>
    </div>
</div>

<div class="row endereco g-2">
    <div class="mb-3 col-md-6">
        <label class="form-label" for="cidade">Cidade</label>
        <input <?= $required; ?> type="text" class="form-control input-validate" name="cidade" id="cidade" maxlength="100" data-cep="Cidade" <?= $disabled; ?> value="<?= $edit ? $edit["cidade"] : ""; ?>">
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label" for="seletor-uf">UF</label>
        <select <?= $required; ?> name="idUf" id="seletor-uf" data-cep="UF" class="form-control input-validate" <?= $disabled; ?>>
            <option selected disabled value="">UF</option>
            <?= $data_db->listCombo("uf", "id", "siglaUF", ($edit ? $edit["idUf"] : getenv("UF_DEFAULT")) , "siglauf", ["siglaUF", "nomeLocalidade"]); ?>
        </select>
    </div>
</div>

<div class="row g-2">
    <div class="mb-3 col-md-12">
        <label class="form-label" for="numeroBeneficio">Número Benefício</label>
        <input type="tel" class="form-control" name="numeroBeneficio" id="numeroBeneficio" maxlength="15" <?= $disabled; ?> value="<?= $edit ? $edit["numeroBeneficio"] : ""; ?>">
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label" for="telefone">Telefone</label>
        <input type="tel" class="form-control" name="telefone" id="telefone" maxlength="15" <?= $mask ? "data-mask='(99) 99999-9999'" : ""; ?> <?= $disabled; ?> value="<?= $edit ? $edit["telefone"] : ""; ?>">
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label" for="data_nascimento">Data de Nascimento</label>
        <input type="date" class="form-control input-validate" name="dataNascimento" id="data_nascimento" maxlength="10" <?= $disabled; ?> value="<?= $edit ? $edit["dataNascimento"] : ""; ?>">
    </div>

    <div class="mb-3 col-md-12">
        <label class="form-label" for="email">E-mail</label>
        <input type="email" class="form-control input-validate" name="email" id="email" maxlength="80" <?= $disabled; ?> value="<?= $edit ? $edit["email"] : ""; ?>">
    </div>
</div>

<div class="row g-2">
    <div class="mb-3">
        <div class="mb-3 form-check form-switch">
            <input class="form-check-input slider-round check-house" type="checkbox" name="residenciaPropria" id="residenciaPropria" <?= $disabled; ?> <?= ($edit and $edit["residenciaPropria"] == "S") ? "checked='checked'" : "";?> value="S"><span class="slider round"></span>
            <label class="form-check-label">Residência própria?</label>
        </div>

        <?php if($edit) { ?>

        <hr />

        <div class="mb-3 form-check form-switch">
            <input class="form-check-input slider-round" type="checkbox" name="ativo" id="ativo" <?= $edit["ativo"] == "S" ? "checked='checked'" : ""; ?>><span class="slider round"></span>
            <label class="form-check-label"><span>Ativo</span></label>
        </div>  
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    var status_selected = <?= $status_clent; ?>;

    $("select[name='sexo']").select2({
        placeholder: "Selecione o sexo",
        minimumResultsForSearch: 5,
        dropdownParent: $(".modal-form")
    });

    $("select[name='idUf']").select2({
        placeholder: "Selecione um estado",
        minimumResultsForSearch: 5,
        dropdownParent: $(".modal-form")
    });
</script>