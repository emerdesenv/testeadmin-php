<?php
    include "../../functions/main_config.php";

    $id_form = isset($_GET["id"]) ? $_GET["id"] : false;
    $edit    = $data_db->loadGenericObject($id_form, "usuario");
?>

<?php if($edit) { ?>
    <input type="hidden" name="id" value="<?= $edit["id"]; ?>" />
<?php } ?>

<div class="row g-2">
    <div class="mb-3 col-md-12">
        <label class="form-label" for="codigo_usuario">Código</label>
        <input required type="text" class="form-control" name="codigo" id="codigo_usuario" maxlength="45" value="<?= $edit ? $edit["codigo"] : ""; ?>" <?= $edit ? "disabled" : ""; ?>>
    </div>

    <div class="mb-3 col-md-12">
        <label class="form-label" for="nome_usuario">Nome</label>
        <input required type="text" class="form-control" name="nome" id="nome_usuario" maxlength="60" value="<?= $edit ? $edit["nome"] : ""; ?>">
    </div>
</div>

<div class="row g-2">
    <div class="mb-3 col-md-6">
        <label class="form-label" for="senha">Senha</label>
        <input <?= $edit ? "" : "required"; ?> type="password" class="form-control" name="senha" id="senha" maxlength="30" autocomplete="off">
    </div>
    
    <div class="mb-3 col-md-6">
        <label class="form-label" for="senha2">Confirmação de senha</label>
        <input <?= $edit ? "" : "required"; ?> type="password" class="form-control" name="senha2" id="senha2" maxlength="30" autocomplete="off">
    </div>
</div>

<div class="mb-3">
    <label class="form-label" for="email_usuario">E-mail</label>
    <input type="email" class="form-control" name="email" id="email_usuario" maxlength="80" value="<?= $edit ? $edit["email"] : ""; ?>">
</div>

<div class="mb-3">
    <label class="form-label">Tipo de Usuário</label>
    
    <div class="btn-group width-elements" role="tipoUsuario">
        <input type="radio" class="btn-check" name="tipoUsuario" value="0" <?= ($edit) ? ($edit["tipoUsuario"] == "0" ? "checked" : "") : ""; ?> id="btnradio1" autocomplete="off" checked>
        <label class="btn btn-outline-info col-md-6" for="btnradio1">Administrador</label>

        <input type="radio" class="btn-check" name="tipoUsuario" value="1" <?= ($edit) ? ($edit["tipoUsuario"] == "1" ? "checked" : "") : "checked"; ?> id="btnradio2" autocomplete="off">
        <label class="btn btn-outline-info col-md-6" for="btnradio2">Normal</label>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-md-6">
        <label for="data_nascimento">Data de Nascimento</label>
        <input type="date" class="form-control" name="dataNascimento" id="data_nascimento" maxlength="15" value="<?= $edit ? $edit["dataNascimento"] : ""; ?>">
    </div>
</div>

<div class="mb-3 form-check form-switch">
    <input class="form-check-input" type="checkbox" name="modelo" <?= ($edit and $edit["modelo"] == "S") ? "checked='checked'" : ""; ?>><span class="slider round"></span>
    <label class="form-check-label"><span>Usuário Modelo?</span></label>
</div>

<?php if($edit) { ?>
    <div class="mb-3 form-check form-switch">
        <input class="form-check-input" type="checkbox" name="ativo" <?= ($edit and $edit["ativo"] == "S") ? "checked='checked'" : ""; ?>><span class="slider round"></span>
        <label class="form-check-label"><span>Ativo?</span></label>
    </div>
<?php } ?>