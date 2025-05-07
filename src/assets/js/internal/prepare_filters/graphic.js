/****************************************************************************
 * Função para aplicar o filtro
******************************************************************************/

function applyRequestGraphic() {
    var generic   = getURLParamGraphic();
    var gaphIndex = getConfigsGraphic(generic);

    reference = {
        'url' : generic 
    }

    if(list_filter.includes("period")) {
        reference ["period_init"]  = $("#date_init").val();
        reference ["period_final"] = $("#date_final").val();
    }

    GraphicObj.destroyChart(gaphIndex.graph);

    setTimeout(function(){
        GraphicObj.reloadGraph(gaphIndex, reference);
    }, 100);
}

/****************************************************************************
 * Funções para limpar o filtro
******************************************************************************/

function clearRequestGraphic() {
    var generic   = getURLParamGraphic();
    var gaphIndex = getConfigsGraphic(generic);

    reference = {
        'url' : generic 
    }

    if(list_filter.includes("period")) {
        reference ["period_init"]  = "";
        reference ["period_final"] = "";
    }

    GraphicObj.destroyChart(gaphIndex.graph);
    GraphicObj.reloadGraph(gaphIndex, reference);
}

/****************************************************************************
 * Funções genéricas
******************************************************************************/

function getURLParamGraphic() {
    let url     = location.href.split("/");
    let generic = url[url.length - 1].replace("#", "");

    return generic;
}