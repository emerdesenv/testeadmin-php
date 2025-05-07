<?php
    include "../../functions/main_config.php";

    $id_form = isset($_GET["id"]) ? $_GET["id"] : false;
    $edit    = $data_db->loadGenericObject($id_form, "agenciador");
    $mask    = ($edit && $edit["nome"] == "N") ? false : true;
?>

<?php if($edit) { ?>
    <input type="hidden" name="id" id="id-edicao" value="<?= $edit["id"]; ?>" />
    <input type="hidden" name="status" id="status" value="<?= $edit["ativo"]; ?>" />
<?php } ?>

<div class="row g-2">

    <div class="mb-3 col-md-12">
        <label class="form-label" for="nome">Nome</label>
        <input required type="text" class="form-control input-validate" name="nome" id="nome" maxlength="80" value="<?= $edit ? $edit["nome"] : ""; ?>">
    </div>

    <div class="form-row">
        <div class="form-group mb-3 col-md-12">
            <label class="form-label">Tipo de Pessoa</label>

            <div class="btn-group width-elements" role="tipo">
                <input type="radio" class="btn-check" name="tipo" value="J" <?= ($edit) ? ($edit["tipo"] == "J" ? "checked" : "disabled") : "checked"; ?> id="btnradio1" autocomplete="off">
                <label class="btn btn-outline-info col-md-6" for="btnradio1">Jurídica</label>

                <input type="radio" class="btn-check" name="tipo" value="F" <?= ($edit) ? ($edit["tipo"] == "F" ? "checked" : "disabled") : ""; ?> id="btnradio2" autocomplete="off">
                <label class="btn btn-outline-info col-md-6" for="btnradio2">Física</label>
            </div>
        </div>
    </div>
</div>

<div class="row g-2">
    <div class="form-group col-md-12">
        <label class="form-label" for="CNPJ_CPF">CNPJ</label>
        <input required type="text" class="form-control" name="CNPJ_CPF" id="CNPJ_CPF" maxlength="18" value="<?= $edit ? $edit["CNPJ_CPF"]: ""; ?>" <?= $edit ? "disabled" : ""; ?>>
    </div>

    <div class="mb-3 col-md-12">
        <label class="form-label" for="email">E-mail</label>
        <input type="email" class="form-control input-validate" name="email" id="email" maxlength="45" value="<?= $edit ? $edit["email"] : ""; ?>">
    </div>
    
</div>

<hr class="mt-2 dashed">

<div class="row endereco g-2">
    <div class="mb-3 col-md-3">
        <label class="form-label" for="cep">CEP</label>
        <input required type="text" class="form-control input-validate" name="CEP" id="cep" maxlength="8" data-mask="99999-999" data-original="<?=$edit["CEP"]; ?>"  value="<?= $edit ? $edit["CEP"] : ""; ?>">
    </div>

    <div class="mb-3 col-md-9">
        <label class="form-label" for="endereco">Endereço</label>
        <input required type="text" class="form-control input-validate" name="endereco" id="endereco" maxlength="100" data-cep="Endereco"  value="<?= $edit ? $edit["endereco"] : ""; ?>">
    </div>

    <div class="mb-3 col-md-3">
        <label class="form-label" for="numero">Número</label>
        <input type="text" class="form-control" name="numero" id="numero" maxlength="15" data-cep="Numero"  value="<?= $edit ? $edit["numero"] : ""; ?>">
    </div>

    <div class="mb-3 col-md-9">
        <label class="form-label" for="complemento">Complemento</label>
        <input type="text" class="form-control" name="complemento" id="complemento" maxlength="60" value="<?= $edit ? $edit["complemento"] : ""; ?>">
    </div>

    <div class="mb-3 col-md-5">
        <label class="form-label" for="bairro">Bairro</label>
        <input required type="text" class="form-control input-validate" name="bairro" id="bairro" maxlength="100" data-cep="Bairro"  value="<?= $edit ? $edit["bairro"] : ""; ?>">
    </div>

    <div class="mb-3 col-md-5">
        <label class="form-label" for="cidade">Cidade</label>
        <input required type="text" class="form-control input-validate" name="cidade" id="cidade" maxlength="100" data-cep="Cidade"  value="<?= $edit ? $edit["cidade"] : ""; ?>">
    </div>

    <div class="mb-3 col-md-2">
        <label class="form-label" for="seletor-uf">UF</label>
        <select required name="idUf" id="seletor-uf" data-cep="UF" class="form-control input-validate" >
            <option selected disabled value="">UF</option>
            <?= $data_db->listCombo("uf", "id", "siglaUF", ($edit ? $edit["idUf"] : "") , "siglauf", ["siglaUF", "nomeLocalidade"]); ?>
        </select>
    </div>

    <div class="mb-3 col-md-6">
        <label for="telefone">Telefone</label>
        <input type="tel" class="form-control" name="telefone" id="telefone" maxlength="15" <?= $mask ? "data-mask='(99) 9999-9999'" : ""; ?>  value="<?= $edit ? $edit["telefone"] : ""; ?>">
    </div>

    <div class="mb-3 col-md-6">
        <label for="celular">Celular</label>
        <input type="tel" class="form-control input-validate" name="celular" id="celular" maxlength="15" <?= $mask ? "data-mask='(99) 99999-9999'" : ""; ?>  value="<?= $edit ? $edit["celular"] : ""; ?>">
    </div>

    <?php if($edit) { ?>
        <hr />

        <div class="mb-3 form-check form-switch">
            <input class="form-check-input slider-round" type="checkbox" name="ativo" id="ativo" <?= $edit["ativo"] == "S" ? "checked='checked'" : ""; ?>><span class="slider round"></span>
            <label class="form-check-label"><span>Ativo</span></label>
        </div>
    <?php } ?>
</div>

<script type="text/javascript">    
    $(function() {
        if($('input[name=tipo]:checked').val() == "J") {
            $(".modal-form input[name='CNPJ_CPF']").closest(".form-group").children("label").text("CNPJ");
            $("#CNPJ_CPF").mask("00.000.000/0000-00");
        } else {
            $(".modal-form input[name='CNPJ_CPF']").closest(".form-group").children("label").text("CPF");
            $("#CNPJ_CPF").mask("000.000.000-00");
        }

        $("#CNPJ_CPF").on("blur", function() {
            validaCPFCNPJ(this);
        });     
    });
</script>