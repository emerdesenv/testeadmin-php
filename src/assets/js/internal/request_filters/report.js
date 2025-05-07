var ReportsObj = {
    print: function() {
        window.print();
    }, report_analytic: function(reference) {
        let id     = reference;
        let params = {};

        if(typeof id === "object") {
            id = getRowId(this);
        }

        if(typeof id !== "undefined") {
            params.id = id;
        }

        params.analytic = true;

        ReportsObj.reportPrintGeneryc("Emissão de Relatório: Analítico", "analytic", ".doc-modal", params);
    }, report_financial: function(reference) {
        $(".copy-text").css("display", "block");

        ReportsObj.reportPrintGeneryc("Emissão de Relatório: Financeiro", "financial", ".doc-modal");
    }, report_schedule: function(reference) {
        $(".copy-text").css("display", "block");
        $(".btn-print").css("display", "block");
        
        ReportsObj.reportPrintGeneryc("Emissão de Relatório: Agendamento", "schedule", ".doc-modal");
    }, reportPrintGeneryc: function(menssage, method, modal, params) {
        var parameter_formated = $.extend({}, params, {page: location.href.split("/")[3].replace("#", "")});

        $(modal+" .modal-body").load("pages/reports/"+method, parameter_formated, function() {
            $(modal).find(".modal-title").text(menssage);
            $(modal).modal("show");
        });
    }
}

function getRowId(element) {
    var $tr = $(element).closest("tr");

    if($tr.hasClass("child")) {
        $tr = $tr.prev("tr");
    }

    return $tr.attr("id").replace("row_", "");
}