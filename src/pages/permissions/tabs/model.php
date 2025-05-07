<div class="mb-3">
    <label class="form-label" for="seletor_agent_model">Agenciador</label>
    <select name="idAgenciador" id="seletor_agent_model" class="form-control" style="width: 100%;" required>
        <option value="">Selecione o Agenciador</option>
        <?php getAllAgentsToSelect($edit); ?>
    </select>
</div>

<div class="mb-3">
    <label class="form-label" for="seletor_model">Modelo (De)</label>
    <select name="modelo" id="seletor_model" class="form-control" style="width: 100%;" required>
        <option value="">Selecione um modelo</option>
        <?= $data_db->listCombo("usuario", "id", "nome", false, "WHERE modelo = 'S' ORDER BY codigo"); ?>
    </select>
</div>

<div class="mb-3">
    <label class="form-label" for="seletor_user_model">Usuário (Para)</label>
    <select name="idUsuario" id="seletor_user_model" class="form-control" style="width: 100%;" required>
        <option value="">Selecione o Usuário</option>
        <?= $data_db->listCombo("usuario", "id", "nome", $edit["codigo"] , "WHERE ativo = 'S' ORDER BY codigo"); ?>
    </select>
</div>

<script>
    $("#seletor_agent_model").select2({
        placeholder: "Selecione um Agenciador",
        minimumResultsForSearch: 5,
        dropdownParent: $(".modal-form")
    });

    $("#seletor_model").select2({
        placeholder: "Selecione um modelo de",
        minimumResultsForSearch: 5,
        dropdownParent: $(".modal-form")
    });
    
    $("#seletor_user_model").select2({
        placeholder: "Selecione um modelo para",
        minimumResultsForSearch: 5,
        dropdownParent: $(".modal-form")
    });
</script>