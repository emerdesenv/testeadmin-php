/****************************************************************************
 * Função para aplicar o filtro
******************************************************************************/

function applyRequestDatatable() {
    var generic = getURLParamTable();

    reference = {
        "url" : generic 
    }

    if(list_filter.includes("client")) {
        var status_client_active   = "";
        var status_client_inactive = "";

        reference ["id_user_client"] = $(".select-user-filter").find(":selected").val();
                       
        if($("#status_client_active").is(":checked")) { status_client_active = "S"; } 
        if($("#status_client_inactive").is(":checked")) { status_client_inactive = "N"; }

        reference ["status_client_active"]   = status_client_active;
        reference ["status_client_inactive"] = status_client_inactive;
    }

    if(list_filter.includes("schedule")) {
        var status_schedule_served  = "";
        var status_schedule_pending = "";
        var ignore_empty_dates      = "";

        reference ["id_user_client"]  = $(".select-user-filter").find(":selected").val();
        reference ["schedule_period"] = $(".select-period-filter").val();
        
        if($("#ignore_empty_dates").is(":checked")) { ignore_empty_dates = "S"}
        if($("#status_schedule_served").is(":checked")) { status_schedule_served = "S"; } 
        if($("#status_schedule_pending").is(":checked")) { status_schedule_pending = "N"; }

        reference ["ignore_empty_dates"]      = ignore_empty_dates;
        reference ["status_schedule_served"]  = status_schedule_served;
        reference ["status_schedule_pending"] = status_schedule_pending;
    }

    if(typeof dataTableObj != "undefined") {
        dataTableObj.loadParam(reference);
    }
}

/****************************************************************************
 * Funções para limpar o filtro
******************************************************************************/

function clearRequestDatatable() {
    var generic = getURLParamTable();

    $(".print-description").remove();

    reference = {
        "url" : generic 
    }

    if(list_filter.includes("client")) {
        reference ["id_user_client"]         = "";
        reference ["status_client_active"]   = "";
        reference ["status_client_inactive"] = "";
    }

    if(typeof dataTableObj != "undefined") {
        dataTableObj.loadParam(reference);
    }
}

/****************************************************************************
 * Funções genéricas
******************************************************************************/

function getURLParamTable() {
    let url     = location.href.split("/");
    let generic = url[url.length - 1].replace("#", "");

    return generic;
}