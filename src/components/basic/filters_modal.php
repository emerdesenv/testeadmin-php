<div class="modal fade filter-modal" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filtrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-2">

                    <?php if(in_array("agent", FILTER_PAGES[$page_url])) { ?>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="idAgenciador">Agenciador</label>
                            <select name="idAgenciador" id="idAgenciador" class="form-control select-agent" style="width: 100%;">
                                <?php echo listAgentsModal(); ?>
                            </select>
                        </div>
                    <?php } ?>

                    <?php if(in_array("teste", FILTER_PAGES[$page_url])) { ?>
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="idUsuarioFiltro">Usuário</label>
                            <select name="idUsuarioFiltro" id="idUsuarioFiltro" class="form-control select-user-filter" style="width: 100%;">
                                <option value="">Selecione um Usuário</option>
                                <?= $data_db->listCombo("usuario", "id", "nome", isset($_SESSION["user_client"]) ? $_SESSION["user_client"]["id"] : "", "WHERE Ativo = 'S' ORDER BY codigo"); ?>
                            </select>
                        </div>

                        <div class="mb-3 col-md-2">
                            <label class="form-label" for="status_client_active">Status Cliente</label>
                            <div class="custom-control form-check">
                                <input type="checkbox" class="form-check-input" id="status_client_active" name="status_client_active" value="S"
                                    <?=isset($_SESSION["status_client_active"]) && $_SESSION["status_client_active"] == 'S' ? "checked" : ""; ?>>
                                <label class="form-check-label" for="status_client_active">Ativo</label>
                            </div>
                        </div>

                        <div class="mb-3 col-md-2">
                            <label class="form-label" for="status_client_inactive">&nbsp;</label>
                            <div class="custom-control form-check">
                                <input type="checkbox" class="form-check-input" id="status_client_inactive" name="status_client_inactive" value="N"
                                    <?=isset($_SESSION["status_client_inactive"]) && $_SESSION["status_client_inactive"] == 'N' ? "checked" : ""; ?>>
                                <label class="form-check-label" for="status_client_inactive">Inativo</label>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="alert" style="display:none;"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <small class="text-muted mr-3">Utilize os campos acima para filtrar os dados</small>
                <button class="btn btn-white clear-filters" data-bs-dismiss="modal">Limpar</button>
                <button class="btn btn-primary apply-filters text-white" data-bs-dismiss="modal">Aplicar</button>
            </div>
        </div>
    </div>
</div>