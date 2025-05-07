var dataTableObj = {
    loadParam : function(reference) {
        let _GET = {};
        let _URL =  "pages/"+reference.url+"/controller";

        if(list_filter.includes("client")) {
            filterClient(reference, _GET);
        }

        if(list_filter.includes("schedule")) {
            filterSchedule(reference, _GET);
        }

        let parameters = [];

        for(var name_parameter in _GET) {
            parameters.push(name_parameter +"="+ _GET[name_parameter]);
        }

        dataTableObj.loadDataTable(_URL, parameters.join("&"));
    },
    loadDataTable : function(_URL, _Get) {
        $datatable.ajax.url(_URL+'?'+_Get).load(function(data) {
            $(".content").removeClass("loading");
            $("#index").removeClass("loading");

            if(reference.url == "distribute_tickets") {
                addPopoverListTable();
            }
        });
    }
}

/****************************************************************************
* Funções de renderização dos filtros de cada tela
******************************************************************************/

function filterClient(reference, _GET) {
    if(typeof reference.id_user_client != "undefined") {
        _GET["id_user_client"] = reference.id_user_client;
    }

    if(typeof reference.status_client_active != "undefined") {
        _GET["status_client_active"] = reference.status_client_active;
    }

    if(typeof reference.status_client_inactive != "undefined") {
        _GET["status_client_inactive"] = reference.status_client_inactive;
    }

    prepareToRenderClient(reference);
}