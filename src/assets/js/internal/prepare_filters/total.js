/****************************************************************************
 * Função para aplicar o filtro
******************************************************************************/

function applyRequestTotals() {
    var totals_size = getSizeTotals(totals_type);

    reference = {
        'url'  : totals_type,
        'size' : totals_size
    }

    if(list_filter.includes("period")) {
        reference ["period_init"]  = $("#date_init").val();
        reference ["period_final"] = $("#date_final").val();
    }

    TotalsObj.loadParam(reference);
}

/****************************************************************************
 * Funções para limpar o filtro
******************************************************************************/

function clearRequestTotals() {
    var totals_size = getSizeTotals(totals_type);
    
    reference = {
        'url'  : totals_type,
        'size' : totals_size
    }

    if(list_filter.includes("period")) {
        reference ["period_init"]   = "";
        reference ["period_final"]  = "";
    }

    TotalsObj.destroyTotals(reference);
    TotalsObj.loadParam(reference);
}