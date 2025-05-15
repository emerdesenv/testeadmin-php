/****************************************************************************
 * Funções iniciais do filtro
******************************************************************************/

$(document).ready(function() {
    $('body').on('click', function (e) {
        $('[data-bs-toggle="popover"]').each(function () {
            if(!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });

        if($(e.target).parents(".daterangepicker").length > 0) {
            return false;
        }
    });

    $(".dd-filters").on("click", function(e) {
        e.stopPropagation();
    });

    $(document).on('click', '.prev, .next', function (e) {
        e.stopPropagation();
    });

    if(list_filter.includes("agent")) {
        $("#idAgenciador").select2({
            placeholder: "Selecione um Agenciador",
            minimumResultsForSearch: 5,
            dropdownParent: $(".filter-modal")
        });

        $select_agent = $(".select-agent");
    }

    $("#button_filters").on("click", function() {
        $('#alert').html("");
        $("#alert").removeClass("alert alert-danger");
        $("#alert").hide();
    });
});

/****************************************************************************
 * Funções para requisição do datatable, gráficos ou totalizadores
******************************************************************************/

$(".apply-filters").on("click", function() {
    var url = getURLParam();

    if(!url.includes("index")) {
        $(".content").addClass("loading");
    }

    if(vis_graphic) { applyRequestGraphic(); }
    if(vis_table) { applyRequestDatatable(); }
    if(vis_totals && (url.includes("index") || url == "") || vis_totals) { applyRequestTotals(); }
});

$(".clear-filters").on("click", function() {
    var url = getURLParam();

    if(!url.includes("index")) {
        $(".content").addClass("loading");
    }

    if(vis_graphic) { clearRequestGraphic(); }
    if(vis_table) { clearRequestDatatable(); }
    if(vis_totals && (url.includes("index") || url == "")) { clearRequestTotals(); }
    if(vis_totals && (url.includes("index") || url == "") || vis_totals) { clearRequestTotals(); }
});

/****************************************************************************
 * Funções genéricas
******************************************************************************/

function getURLParam() {
    let url     = location.href.split("/");
    let generic = url[url.length - 1].replace("#", "");

    return generic;
}

/****************************************************************************
* Função para pegar somente os dados da sessão
******************************************************************************/

function prepareToRenderBadgesHeader(action = "apply") {
    setTimeout(function() {
        var generic = getURLParam();
                
        $.getJSON("functions/generic_ajax.php?action=get_header_page&url_page="+generic)
        .done(function(data) {
            if(list_filter.includes("agent")) {
                prepareToRenderAgent(data);
            }
        });
    }, 200);
}

/****************************************************************************
* Funções de renderização do conteúdo retornado
******************************************************************************/

function prepareToRenderAgent(reference) {
    if(typeof reference.header.agenciador != "undefined") {
        $('.header-agent, .label-badge-user').html(reference.header.agenciador);
    }
}

function prepareToRenderClient(reference) {
    if(reference.id_user_client) {
        var data = $(".select-user-filter").select2("data");

        renderDataHeader(data[0].text, true, "badge-user-dash", "label-badge-user", "user_client");
    } else {
        renderDataHeader("", false, "badge-user-dash", "label-badge-user", "user_client");
    }

    if(reference.status_client_active || reference.status_client_inactive) {
        var status_client = reference.status_client_active && reference.status_client_inactive ? "Todos" : 
            reference.status_client_active && !reference.status_client_inactive ? "Ativo" : "Inativo";

        renderDataHeader(status_client, true, "badge_status_client", "badge-status-client", "status_client_active");
    } else {
        renderDataHeader(status_client, false, "badge_status_client", "badge-status-client", "status_client_active", true, 
        ["status_client_active", "status_client_inactive"]);
    }
}

/****************************************************************************
* Metódo dinamico para renderização dos badges no header da página
******************************************************************************/

function renderDataHeader(reference, action, id_field, class_field, name_field, checkbox = false, list_status) {
    if(action == true) {
        $("#"+id_field).hide();
        $("#"+id_field).removeClass();
        $("#"+id_field).show();
        $("#"+id_field).addClass("badge bg-primary");
        $("."+class_field).html(reference);
    } else {
        if(checkbox) {
            for (let index = 0; index < list_status.length; index++) {
                const element = list_status[index];

                $("#"+element).prop("checked", false);
            }
        } else {
            $("input[name='"+name_field+"']").val("");
        }
        
        $("#"+id_field).removeClass();
        $("#"+id_field).hide();
    }
}