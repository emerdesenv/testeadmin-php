var GraphicObj = {
    graphics: {
        "index" : [],
        ""      : []
    },
    graphics_size: [
        "sales_for_hour_editions", "sales_last_editions", "registers_week_active", "sales_last_editions_cumulative", "payment_type",
        "sales_for_day", "sales_on_day", "sales_for_edition", "sales_for_day_period", "clients_city", "clients_age", "payment_gateway", "payment_origin"
    ],
    initialize: function() {
        var url = location.href.replace("#", "");
       
        $.each(this.graphics[url.split('/').pop()], function(e) {
            GraphicObj.loadGraph(this);
        });

        $(".apply-expand").append("<a class='icon-expand'><span class='nav-icon'><span class='nav-icon bi bi-arrows-fullscreen'></span></span></a>");
      
        $(".icon-expand").on("click", function() {
            $(this).closest(".card").toggleClass("expanded");

            var status = true;
            var canv_id = $(this).closest(".card").find("canvas").attr("id");

            if(eval("typeof chart_"+canv_id) !== "undefined") {
                graphic = eval("chart_"+canv_id);

                graphic.options.plugins.legend.display = status;
               
                graphic.update();
            }

            if(canv_id != "sales_at_the_time") {
                var graphic = eval("chart_"+canv_id);

                if(!$(this).closest(".card").hasClass("expanded")) {
                    graphic.canvas.parentNode.style.width = '100%';

                    if(canv_id == "sales_for_edition" || canv_id == "sales_for_day_period") {
                        graphic.canvas.parentNode.style.height = '430px';
                    } else if(GraphicObj.graphics_size.includes(canv_id)) {
                        graphic.canvas.parentNode.style.height = '268px';
                    }
                } else {
                    graphic.canvas.parentNode.style.width = '100%';
                    graphic.canvas.parentNode.style.height = '775px';
                }
            }
        });
    },
    loadParam: function (graphicName, params) {
        let _URL = params.url;
        let _GET = {};

        if(list_filter.includes("single") || list_filter.includes("selected_day")) {
            _GET['dateSelected'] = params.dateSelected;
        }
        
        if(list_filter.includes("agent") && list_filter.includes("edition")) {
            if(typeof params.id_agent != "undefined") {
                _GET['id_agent'] = params.id_agent;
            }

            if(typeof params.id_edition != "undefined") {
                _GET['id_edition'] = params.id_edition;
            }
        }

        if(list_filter.includes("period")) {
            if((typeof params.period_init != "undefined") && (typeof params.period_final != "undefined")) {
                _GET['period_init'] = params.period_init;
                _GET['period_final'] = params.period_final;
            }
        }

        var $section = $("#"+graphicName);
        $section.closest(".card-body").addClass("loading");
        
        GraphicObj.loadGraph(_URL, _GET, graphicName);
    },
    loadGraph: function(reference, params = false, graphicName = false) {
        let url     = location.href.split("/");
        let generic = url[url.length - 1].replace("#", "");
        
        generic = generic ? generic : "index";

        var graphic     = (typeof reference.graphic !== "undefined") ? reference.graphic : reference;
        var url_request = (typeof reference.url !== "undefined") ? reference.url : graphic;

        if(graphicName) {
            graphic = url_request = graphicName;
        }
    
        $.getJSON("graphics/"+generic+"/"+url_request, params, function(data) {
            if(data.permission == true) {

                Chart.register({
                    id: graphic,
                    afterDraw: function(chart) {
                        if(chart.data.datasets[0] && chart.data.datasets[0].data.length == 0) {
                            var ctx    = chart.ctx;
                            var width  = chart.width;
                            var height = chart.height;

                            chart.clear();
                            ctx.save();
                            
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            ctx.fillText('Sem informações para mostrar', width / 2, height / 2);
                            ctx.restore();
                        }
                    }
                });

                //Algumas pré configurações após o load

                var $section = $("#"+graphic);
                $section.closest(".card-body").removeClass("loading");

                var legend_active = true
                var type_graph = "bar";
               
                if(generic == "editions_comparative" && graphic == "sales_last_editions_cumulative" || generic != "editions_comparative") {
                    type_graph = "line";
                }

                //Informações do rodapé

                const footer = (tooltipItems) => {

                    if(generic != "editions_comparative") {
                        return false;
                    }

                    let total_base = percent_c1 = percent_c2 = 0;
                   
                    tooltipItems.forEach(function(tooltipItem) {
                        if(tooltipItem.datasetIndex == 0) { percent_c1 = tooltipItem.formattedValue; } 
                        else if(tooltipItem.datasetIndex == 1) { percent_c2 = tooltipItem.formattedValue; } 
                        else { total_base = tooltipItem.formattedValue; }
                    });

                    if(total_base > 0) {
                        var result_sb_c1 = total_base - percent_c1;
                        var percent_value_c1 = (result_sb_c1 / percent_c1) * 100;

                        var result_sb_c2 = total_base - percent_c2;
                        var percent_value_c2 = (result_sb_c2 / percent_c2) * 100;

                        percent_c1 = "% C1: "+percent_value_c1.toFixed(2)+"%";
                        percent_c2 = "% C2: "+percent_value_c2.toFixed(2)+"%";
                    } else {
                        var percent_value_c1 = percent_c1 > 0 ? "-"+(percent_c1 / 2 * 100) : 0;
                        var percent_value_c2 = percent_c2 > 0 ? "-"+(percent_c2 / 2 * 100) : 0;

                        percent_c1 = "% C1: "+percent_value_c1+"%";
                        percent_c2 = "% C2: "+percent_value_c2+"%";
                    }

                    return "\n "+percent_c1+" \n "+percent_c2;
                };
 
                //Informações das labels

                const label = (tooltipItems, data) => {
                    return conteudo = tooltipItems.dataset.label+": "+tooltipItems.formattedValue;
                };

                /* filter: (legendItem: LegendItem) => {
                    console.log(legendItem);
                    return legendItem.index < 2
                },*/

                //Informações da estrutura
               
                if(graphic == "clients_age" || graphic == "clients_city" || graphic == "payment_type" || 
                    graphic == "payment_origin" || graphic == "payment_gateway") {
                    window["chart_"+graphic] = new Chart($section, {
                        type: "doughnut", 
                        data: data, 
                        options: {
                            plugins: {
                                legend: { 
                                    display: legend_active, 
                                    position: "right", 
                                    align: "start" 
                                }
                            },
                            tooltips: {
                                callbacks: {
                                    label: label, 
                                    title: function(tooltipItem, data) {
                                       
                                        var data_index = tooltipItem[0].index;
                                        var data_label = data.labels[data_index];

                                        return data_label;
                                    }
                                }
                            },
                            responsive: true,
                            maintainAspectRatio: false 
                        }
                    });
                } else if(graphic == "sales_on_day" || graphic == "sales_for_day" || graphic == "sales_for_day_period" || 
                            graphic == "sales_for_edition" || graphic == "sales_last_editions_cumulative" || graphic == "registers_week_active" || 
                            graphic == "sales_last_editions" || graphic == "sales_for_hour_editions") {
                    window["chart_"+graphic] = new Chart($section, {
                        type: type_graph, 
                        data: data, 
                        options: {
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: label,
                                        footer: footer
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    id: 'x',
                                    grid: { display: false }
                                }
                            },
                            responsive: true, maintainAspectRatio: false
                        }
                    });
                } else if(graphic == "sales_at_the_time") {
                    var opts = {
                        angle: -0.2,
                        lineWidth: 0.2,
                        radiusScale: 1,
                        pointer: { length: 0.5, strokeWidth: 0.040, color: '#000000' },
                        staticZones: data.static,
                        staticLabels: {
                            font: "13px sans-serif",
                            labels: data.labels,
                            color: "#607077",
                        }
                    };

                    var target = document.getElementById(graphic);
                    var gauge  = new Gauge(target).setOptions(opts);
                    
                    gauge.maxValue = data.static[4].max;
                    gauge.setMinValue(0);
                    gauge.animationSpeed = 10;
                    gauge.setTextField(document.getElementById("gauge-value"));
                    gauge.set(data.actual);
                }
            }
        });
    },
    reloadGraph: function(gaphIndex, params = false) {
        var graphic = gaphIndex.graph.length;
       
        for(i = 0; i < graphic; i++) {
            GraphicObj.loadParam(gaphIndex.graph[i], params);
        }
    },
    destroyChart : function (gaphIndex) {
        fLen = gaphIndex.length;

        for(i = 0; i < fLen; i++) {
            if(window["chart_"+gaphIndex[i]] != null) {
                window["chart_"+gaphIndex[i]].destroy();
            }
        }
    }
}