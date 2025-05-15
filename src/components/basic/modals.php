<?php if($is_table || vModal()) { ?>
    <div class="modal fade confirm-delete" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" style="display: none;"></div>

                <div class="modal-footer">
                    <button type="button" data-generic-id="" href="#" class="delete-row btn btn-danger text-white">Deletar</button>
                    <button type="button" href="#" class="cancel-delete btn btn-light">Manter</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade confirm-delete-bulk" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body"></div>

                <div class="modal-footer">
                    <button type="button" id="button_del" data-generic-id="" href="#" class="delete-row btn btn-danger text-white">Deletar em massa</button>
                    <button type="button" href="#" class="cancel-delete btn btn-light">Manter</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade leftright-slide right-align modal-form" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form-table">
        <div class="modal-dialog modal-<?= isset($modal_size) ? $modal_size : "md"; ?>" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-form-table">Adicionar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="<?="pages/$page_url/controller"?>" method="POST" id="modal_form" class="needs-validation" novalidate enctype="multipart/form-data">
                    <div class="modal-body"></div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary text-white btn-save" title="Salvar">Salvar</button>
                        <button type="button" class="btn btn-white btn-cancel" title="Cancelar" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade doc-modal printable" role="dialog" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" id="doc-body">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-print text-white">Imprimir</button>
                    <button type="button" class="copy-text btn btn-primary text-white">Copiar</button>
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <?php if($page_url == "index" or $page_url == "teste" or $page_url == "schedules") { ?>
        <div class="modal fade event-form-modal" role="dialog" tabindex="-1" aria-hidden="true" id="event_modal">
            <div class="modal-dialog modal-xxg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-2 pb-2 content-container">
                            <div class="col-md-3">
                                <div class="bg-white mx-1 rounded content-teste">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-white mx-1 rounded content-schedules">

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mx-1 rounded content-form">
                                    <form class="col-md-12 pt-1 needs-validation event-form" novalidate>
                                        <input type="hidden" id="event_id">
                                        <input type="hidden" id="client_id" value="">
                                        
                                        <div class="row g-1 px-3 pt-4 pb-2 bg-white rounded edit-schedule d-none">
                                            <div class="content-title mt-0">Detalhes do atendimento</div>
                                            
                                            <div class="col-md-12 mt-1">
                                                <label for="obs_pendente_edicao" class="form-label">Título</label>
                                                <input id="obs_pendente_edicao" name="obsPendenteEdicao" type="text" class="form-control" required maxlength="60">
                                            </div>
    
                                            <div class="col-md-12 mt-2">
                                                <label for="data_hora_agendamento_edicao" class="form-label">Data/Hora</label>
                                                <input id="data_hora_agendamento_edicao" name="dataHoraAgendamentoEdicao" type="datetime-local" class="form-control" required>
                                            </div>
    
                                            <div class="col-md-12 mt-2">
                                                <label for="obs_atendimento_edicao" class="form-label">Observações</label>
                                                <textarea id="obs_atendimento_edicao" name="obsAtendimentoEdicao" type="text" class="form-control inp-obs-atend"></textarea>
                                            </div>
    
                                            <?php if($update_status) { ?>
                                                <div class="col-md-12 mt-2 d-flex flex-row">
                                                    <div class="col-md-12 form-check form-switch opt-add-event">
                                                        <input id="event_completed" name="eventCompleted" type="checkbox" class="form-check-input"><span class="slider round"></span>
                                                        <label for="event_completed" class="form-check-label">Cliente foi atendido?</label>
                                                    </div>
                                                </div>
                                            <?php } ?>
    
                                            <div class="opt-new-event">
                                                <div class="form-check form-switch">
                                                    <input id="new_schedule" name="newSchedule" type="checkbox" class="form-check-input"><span class="slider round"></span>
                                                    <label for="new_schedule" class="form-check-label"><span>Novo Agendamento</span></label>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="row g-1 px-3 pt-4 pb-3 bg-white rounded new-schedule">
                                            <div class="content-title mt-0">NOVO (Próximo) agendamento</div>
                                            
                                            <div class="col-md-12 mt-1">
                                                <label for="obs_pendente" class="form-label">Título</label>
                                                <input id="obs_pendente" name="obsPendente" type="text" class="form-control" value="" required>
                                            </div>

                                            <div class="col-md-12 mt-2">
                                                <label for="data_hora_agendamento" class="form-label">Data/Hora</label>
                                                <input id="data_hora_agendamento" name="dataHoraAgendamento" type="datetime-local" class="form-control" value="" required>
                                            </div>
    
                                            <div class="col-md-12 mt-2">
                                                <label for="obs_atendimento" class="form-label">Observações</label>
                                                <textarea id="obs_atendimento" name="obsAtendimento" type="text" class="form-control optional inp-obs-atend"></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>  
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary text-white btn-save" title="Salvar">Salvar</button>
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade client-search-modal" role="dialog" tabindex="-1" aria-hidden="true" id="client_search_modal">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="selected-date-title"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3 col-md-12 content-search-client">
                            <label for="client_search" class="form-label">Buscar Cliente</label>
                            <div class="input-group">
                                <input id="client_search" type="text" class="form-control optional" placeholder="CPF, Celular ou Nome">
                                
                                <div class="input-group-append">
                                    <button id="search_client_button" class="btn btn-secondary" type="button"><i class='bi bi-search icon-search-cli font-size-18' title='Informações'></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 col-md-12">
                            <label for="id_cliente" class="form-label">Cliente</label>
                            <select id="id_cliente" name="idCliente" class="form-control select-client-filter" style="width: 100%;" required>
                                <option value="defaultOption">Selecione um cliente</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade info-modal" role="dialog" tabindex="-1" aria-hidden="true" id="info_modal">
            <div class="modal-dialog modal-xxg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-2 pb-2 content-container">
                            <div class="col-md-4">
                                <div class="bg-white mx-1 rounded content-teste">

                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="bg-white mx-1 rounded content-schedules">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-print text-white">Imprimir</button>
                        <button type="button" class="copy-text btn btn-primary text-white">Copiar</button>
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
<?php } } ?>