<div class="mb-3">
    <label class="form-label" for="seletor_agent">Agenciador</label>
    <select name="idAgenciador" id="seletor_agent" class="form-control" required <?= $edit ? "disabled" : ""; ?>>
        <option value="">Selecione o Agenciador</option>
        <?php getAllAgentsToSelect($edit); ?>
    </select>
</div>

<div class="mb-3">
    <label class="form-label" for="seletor_user">Usuário</label>
    <select name="idUsuario" id="seletor_user" class="form-control" required <?= $edit ? "disabled" : ""; ?>>
        <option value="">Selecione o Usuário</option>
        <?= $data_db->listCombo("usuario", "id", "nome", $edit["idUsuario"] , "WHERE Ativo = 'S' ORDER BY codigo"); ?>
    </select>
</div>

<div class="mb-3">
    <label class="form-label" for="seletor_activitie">Atividade</label>
    <select name="idAtividade" id="seletor_activitie" class="form-control" required <?= $edit ? "disabled" : ""; ?>>
        <option value="">Selecione a Atividade</option>
        <?= $data_db->listCombo("atividade", "id", "descricao", $edit["idAtividade"] , "ORDER BY descricao"); ?>
    </select>
</div>

<div class="mb-3 form-check form-switch">
    <input class="form-check-input slider-round" type="checkbox" name="inclusao" <?= ($edit and $edit["inclusao"] == "S") ? "checked='checked'" : ""; ?>><span class="slider round"></span>
    <label class="form-check-label"><span>Incluir novos registros</span></label>
</div>

<div class="mb-3 form-check form-switch">
    <input class="form-check-input slider-round" type="checkbox" name="alteracao" <?= ($edit and $edit["alteracao"] == "S") ? "checked='checked'" : ""; ?>><span class="slider round"></span>
    <label class="form-check-label"><span>Alterar registros</span></label>
</div>

<div class="mb-3 form-check form-switch">
    <input class="form-check-input slider-round" type="checkbox" name="exclusao" <?= ($edit and $edit["exclusao"] == "S") ? "checked='checked'" : ""; ?>><span class="slider round"></span>
    <label class="form-check-label"><span>Excluir registros</span></label>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(".tab-pane:not(.active) :input").attr("disabled", true);

        $("#seletor_agent").select2({
            placeholder: "Selecione um Agenciador",
            minimumResultsForSearch: 5,
            dropdownParent: $(".modal-form")
        });

        $("#seletor_user").select2({
            placeholder: "Selecione um usuário",
            minimumResultsForSearch: 5,
            dropdownParent: $(".modal-form")
        });
        
        $("#seletor_activitie").select2({
            placeholder: "Selecione uma opção",
            minimumResultsForSearch: 5,
            dropdownParent: $(".modal-form")
        });
    });
</script>