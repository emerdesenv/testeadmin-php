var list_filter   = ["client"];
var vis_table     = true;
var vis_graphic   = false;
var vis_totals    = false;
var check         = false;

$("select[name='idUsuarioFiltro']").select2({
    placeholder: "Selecione um Usuário",
    minimumResultsForSearch: 5,
    dropdownParent: $(".filter-modal")
});

var cols = [
    {
        data: "id",
        className: "text-center"
    },
    "nome",
    {
        data: "CPF",
        className: "text-left"
    },
    {
        data: "numeroBeneficio",
        className: "points-table text-right"
    },
    "celular",
    {
        data: "cidade",
        className: "text-left"
    },
    {
        data: "ativo",
        className: "text-center"
    },
    {
        className: "text-center",
        orderable: false,
        render: function(data, a, b) {
            return "<a class='new-schedule text-muted' data-client='"+b.id+"'> <i class='bi bi-plus-lg font-size-18' title='Novo Agendamento'></i></a>";
        }
    },
    {
        data: null, type: "text", 
        className: "text-center",
        orderable: false,
        render: function(data, a, b) {
            return "<a class='info-client text-muted' data-client='"+b.id+"'> <i class='bi bi-info-circle-fill font-size-18' title='Informações'></i></a>";
        }
    }
];

order = [1, 'asc'];

$(".btn-print").css("display", "none");
$(".copy-text").css("display", "none");

$(document).ready(function() {
    $(".modal-form").on("blur", "input[name='CPF'], input[name='celular'], input[name='email'], input[name='nome'], input[name='dataNascimento']", function() {
        clearElementError($(this));
    });

    $(document).on("blur", "#cep", function() {
        loadDataCEP(this);
    });

    $(document).on("blur", "#cpf", function() {
        var searchTerm = $('#cpf').val().replace(/\D/g, '');
        var id_client = $('input[name=id]').val();
        id_client = id_client ? "&id_client="+id_client : "";

        if(validateIdentity(this)) {
            $.ajax({
                type : "GET",
                url  : "pages/clients/ajax?action=check_duplicate_cpf&cpf="+searchTerm+id_client
            }).done(function(data){
                if(data.trim() === "true") {
                    showFormErrors([{field: 'CPF', message: "CPF já registrado!"}], false);
                }
            });
        }
    });

    $(document).on("click", ".copy-text-info .icon-clone-text", function() {
        var text = $(this).parent().text();
        
        copyTextToClipboard(text);
    });

    $(document).on("change", "#ativo", function(e) {
        status_selected = status_selected ? false : true;

        if(status_selected) {
            $("#status").val("S");
            $(".modal-form .form-control").attr("disabled", false);
            $(".modal-form .check-house").attr("disabled", false);
            $(".input-validate").attr("required", true);

            $(".options-status").css("display", "block");
          
            $("#cpf").blur();
            $("#cep").blur();
        } else {
            clearElementError(".modal-form .form-control");

            $(".options-status").css("display", "none");
          
            $("#status").val("N");
            $(".modal-form .form-control").attr("disabled", true);
            $(".modal-form .check-house").attr("disabled", true);
            $(".input-validate").removeAttr("required");
        }

        addAsterisk();
    });
});

$(".dataTable").on("click", "a.info-client", function(e) {
    let id_client  = $(this).attr("data-client");
    let info_modal = $(".info-modal");

    info_modal.find(".content-clients").addClass("loading");
    info_modal.find(".content-schedules").addClass("loading");

    info_modal.find(".content-clients").load("pages/clients/tabs/client?id_client=" + id_client + "&page_param=true&page=index", function () {
        $(this).addClass('report');
        info_modal.find(".content-clients").removeClass("loading");
    });

    info_modal.find(".content-schedules").load("pages/schedules/tabs/schedules?id_client=" + id_client + "&page_param=index", function () {
        $(this).addClass('report');
        info_modal.find(".content-schedules").removeClass("loading");
    });

    info_modal.modal("show");
});

$(".dataTable").on("click", "a.new-schedule", function(e) {
    let id_client = $(this).attr("data-client");
    
    if(id_client) {
        $('#event_modal').find('#client_id').val(id_client);
        $('#event_modal').data('modal-type', 'selectable');
        prepareAndShowModal();
    } else {
        toastr.error("Não foi possível encontrar esse cliente.");
    }
});

$('.event-form').off('submit').on('submit', function(e) {
    e.preventDefault();
    var { success, action, event_data, event } = prepareEventData(this);
    
    if(!success) {
        return false;
    }

    submitEventData("clients", action, event_data, function(response) {
        if(response.success) {
            toastr.success('Agendamento salvo com sucesso.');
        } else {
            toastr.error('Não foi possível salvar o agendamento.');
        };
    });
});

function loadOnlyTotalSchedules() {
    var id_client = $('#idCliente').val();
    
    $.ajax({
        type : "GET",
        url  : "pages/schedules/ajax?action=get_total_schedules&id_client="+id_client
    }).done(function(data){
        response = JSON.parse(data);
        var totalSchedules = response.totalAgendamentos;
        $(".total-schedules").html(totalSchedules);
    });
};