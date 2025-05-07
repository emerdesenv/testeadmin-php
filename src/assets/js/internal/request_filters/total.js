var TotalsObj = {
    totals: {
        "index" : ["totals_dashboard"],
        ""      : ["totals_dashboard"]
    },
    initialize: function() {
        var url = location.href.replace("#", "");

        $.each(this.totals[url.split('/').pop()], function() {
            TotalsObj.loadTotal(this);
        });
    },
    loadParam: function (params) {
        $(".Totals").addClass("loading");

        let _URL = params.url;
        let _GET = {};

        if(list_filter.includes("period")) {
            if((typeof params.period_init != "undefined") && (typeof params.period_final != "undefined")) {
                _GET['period_init'] = params.period_init;
                _GET['period_final'] = params.period_final;
            }
        }
        
        TotalsObj.loadTotal(_URL, _GET);
    },
    loadTotal: function(reference, params = false) {
        var url_request = (typeof reference.url !== "undefined") ? reference.url : reference;
        var $section    = $("#"+url_request);

        $.getJSON("totals/"+url_request, params, function(data) {
            if(data.permission == true) {
                var all_cart_itens = data.total;
                var cart_data      = data.data;
                var totals         = data.size;
                
                for(x = 0; x < totals; x++) {
                    if(typeof cart_data[x] === "undefined") continue;
            
                    var status  = cart_data[x];
                    var percent = 0;

                    if(status.itens != 0) {
                        percent = (status.itens / all_cart_itens) * 100;
                    }

                    $section.find(".card-stats:eq(" + x + ") h4").text(parseInt(status.itens).toLocaleString("pt-br"));
                    $section.find(".card-stats:eq(" + x + ") small b").text(parseInt(status.carts).toLocaleString("pt-br"));
                    $section.find(".card-stats:eq(" + x + ") .progress-bar").css("width", percent+"%").text(percent.toFixed(2)+"%");
                }
            }

            $(".Totals").removeClass("loading");
        });
    },
    destroyTotals: function(reference) {
        var url_request = reference.url;
        var totals      = reference.size;
        var $section    = $("#"+url_request);

        for(x = 0; x < totals; x++) {
            var percent = 0;
    
            $section.find(".card-stats:eq(" + x + ") h4").text(0);
            $section.find(".card-stats:eq(" + x + ") small b").text(0);
            $section.find(".card-stats:eq(" + x + ") .progress-bar").css("width", percent+"%").text(percent.toFixed(2)+"%");
        }
    }
}