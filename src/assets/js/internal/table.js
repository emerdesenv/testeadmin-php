var table_checked = [];

$(function() {
    if(typeof(table_options) === "undefined") {
        window.table_options = ["edit", "delete"];
    }

    if(typeof(modal_options) === "undefined") {
        window.modal_options = ["save", "cancel"];
    }

    var $modal             = $(".confirm-delete");
    var $modal_edit        = $(".modal-form");
    var $modal_delete_bulk = $(".confirm-delete-bulk");
   
    var url           = location.href.split("/");
    var generic       = url[url.length - 1].replace("#", "");
    var table_refresh = "table-main";
    
    var url_param = "";
    var dt_cols   = [];
    $tables_names = [];
  
    let rowcheck  = (typeof check !== "undefined") ? check : true;
    let rowoption = (typeof Rwoptions !== "undefined") ? Rwoptions : true;

    $(document).on("click", ".btn-add-general", function() {
        $modal_edit.find(".modal-body:first").load("pages/"+generic+"/form", function() {
            $modal_edit.removeClass("edit").addClass("add").modal("show").find(".modal-title:first").text("Adicionar");
        });
    });

    $("table.dataTable").on("click", ".dropdown-menu a.delete", function() {
        var generic_id = getRowId(this);
        $modal.find(".modal-title").text("Excluir registro?");

        alertModalOnDelete(generic, $modal);

        $modal.find(".btn-danger").attr("data-generic-id", generic_id);
        $modal.modal("show");
    });

    $("table.dataTable").on("click", ".dropdown-menu a.edit", function() {
        var generic_id = getRowId(this);
        editRow(generic_id);
    });

    $(".modal.confirm-delete").on("click", ".delete-row", function() {
        var generic_id = $(this).attr("data-generic-id");
        deleteUnit(false, $modal, generic_id);
    });

    function deleteUnit(parameter, modal, generic_id) {
        var url_param = parameter == true ? "controller" : "controller";
        table_refresh = parameter == true ? "table-main" : "table-main";

        $.ajax({
            url  : "pages/"+generic+"/"+url_param+"?id="+generic_id,
            type : "DELETE",
            success: function() {
                $("."+table_refresh).DataTable().ajax.reload();
                toastr.success("Deletado com sucesso.");
            },
            error: function() { toastr.error("Problemas ao deletar registro!"); }
        });

        modal.modal("hide");
    }

    $(".confirm-delete-bulk, .modal.confirm-delete, .confirm-delete-bulk").on("click", ".cancel-delete", function() {
        $(this).closest(".modal").modal("hide");    
    });

    $("body").on('dblclick', '.dataTable tbody tr', function () {
        if(permissionDoubleClik()) {
            return false;
        };

        if(!checkPermissions(this, "alteracao")) {
            return false;
        }

        editRow($(this).attr("id").replace("row_", ""));
    });

    $("body").on("change", ".dataTable input[type='checkbox']", function () {
        $inputs = $(".dataTable tbody input[type='checkbox']:checked");

        if(generic != "agents" && generic != "clients" && generic != "users" && generic != "states") {
            $(".dataTables_wrapper select.bulk-actions").attr("disabled", !($inputs.length > 1));
        }
    });

    cols.forEach(function (col) {
        if(typeof(col) === "object") {
            dt_cols.push(col);
        } else {
            dt_cols.push({ data: col });
        }
    });

    if(rowoption == true) {
        dt_cols.push({
            data: null,
            className: "dropdown",
            orderable: false,
            defaultContent: '<button class="more-options" title="Mais Opções"><span class="bi bi-three-dots-vertical font-size-18 align-middle"></span></button>'
        });
    }

    var checkboxes = false;

    if(rowcheck == true) {
        dt_cols.unshift({
            data: null,
            defaultContent: "",
            orderable: false
        });

        checkboxes = {
            selectRow: true,
            selectAllPages: false
        }
    }

    var extra        = (typeof opts !== "undefined") ? opts : false;
    let paging_dt    = (typeof paging !== "undefined") ? paging : true;
    var original_url = "pages/"+generic+"/controller.php";

    var length_list = 20;

    var checkboxes = "";
  
    if(generic != "index" && generic != "agents" && generic != "clients" && generic != "users" && generic != "activities") {
        checkboxes = "checkboxes";
    }

    $datatable = $(".dataTable").DataTable({
        dom        : '<"top">rt<"bottom"lpi>',
        order      : order,
        paging     : paging_dt,
        processing : (generic == "clients") ? true : false,
        serverSide : (generic == "clients") ? true : false,
        ajax       : "pages/"+generic+"/controller.php",
        language   : { 
            url: "assets/i18n/pt-br.json",
            decimal: ",",
            thousands: "."
        },
        columns    : dt_cols,
        pageLength : length_list,
        lengthMenu : [10, 20, 50, 100],
        columnDefs : [{
            targets: 0,
            checkboxes
        }, extra],
        responsive : true,
        createdRow: function(row, data) {
            if(data.idAgenciador) {
                $(row).attr("id_agent", data.idAgenciador);
            }

            if(table_options.length <= 2 && !(checkPermissions(row, "alteracao") || checkPermissions(row, "exclusao"))) {
                $(row).find("td:last-child button").addClass("locked");
            }

            $(row).attr("generic_id", data.DT_RowId.replace("row_", ""));
        },
        initComplete: function() {
            $(".content.loading").removeClass("loading");

            if(generic != "agents" && generic != "clients" && generic != "states" && generic != "activities" && generic != "users" && generic != "activities") {
                var $select = $("<select />", {
                    class    : "custom-select custom-select-sm form-control form-control-sm bulk-actions",
                    disabled : true,
                    style    : "margin-right: 10px;"
                });

                var $defaul  = $("<option />", { text: "Ações", value: "", selected: true, disabled: true });
                var $delete  = $("<option />", { text: "Deletar", value: "delete" });

                $select.append($defaul, $delete).insertBefore(".dataTables_wrapper .dataTables_length");
                
                $($select).attr("disabled", true);
            } else {
                if(typeof table_search !== "undefined" && table_search.length > 0) {
                    $(".search-options").show();
                }
                
                $(".dataTables_length").css("margin-left", "0px");
            }

            if(generic == "distribute_tickets") {
                addPopoverListTable();
            }
        }
    });

    $(".table-main").on("xhr.dt", function(e, settings, json, xhr) {
        if(json != null) {
            if(typeof json.totals_footer != "undefined") {
                mountFooter(json.totals_footer);
            }
        }
    });

    $(document).on("change", ".dataTables_wrapper select.bulk-actions", function() {
        var value_option = $(this).val();
      
        if(value_option == "") {
            return false;
        }

        $(this).prop("selectedIndex", 0);

        var no_permission = 0;
        table_checked     = [];
       
        $(".dataTable tbody :checkbox:checked").filter(function() {
            var $tr     = $(this).closest("tr");
            var agent = $tr.attr("id_agent");
            
            if(typeof agent === "undefined") {
                agent = localStorage.getItem("idAgent");
            }
            
            if(page_data[agent]["exclusao"] == "S") {
                if(generic == "users") {
                    var id_user = $tr.attr("id").replace("row_", "");
            
                    if(localStorage.getItem("idUserSession") == id_user) {
                        no_permission++;
                    } else {
                        table_checked.push($tr.attr("id").replace("row_", ""));
                    }
                } else {
                    table_checked.push($tr.attr("id").replace("row_", ""));
                }
            }
        });

       if(value_option == "delete") {
            $modal_delete_bulk.find(".modal-dialog").removeClass("modal-sm").addClass("modal-md");

            var text_body_delete = "";

            if(no_permission > 0 && table_checked.length == 0) {
                text_body_delete +=
                "Ação bloqueada, pois você não tem permissão para excluir." +
                "<div class='text-danger'>Ignorando <span class='font-weight-bold'>"+no_permission+"</span> registros que você não pode.</div>";
                $("#button_del").attr("disabled", true);
            } else {
                text_body_delete +=
                "Esta ação excluirá <span class='font-weight-bold'>"+table_checked.length+"</span> registros "+"que você tem permissão para excluir. " +
                "<div class='text-danger'>Ignorando <span class='font-weight-bold'>"+no_permission+"</span> registros que você não pode.</div>" +
                "<div class='mt-3 font-weight-bold'>Esta ação não pode ser desfeita, tem certeza?</div>";
                $("#button_del").attr("disabled", false);
            }

            $modal_delete_bulk.find(".modal-title").text("Deletar em Massa");
            $modal_delete_bulk.find(".modal-body").html(text_body_delete);
            $modal_delete_bulk.modal("show");

            //true para delete com o datatable genérico
            url_param     = "controller";
            table_refresh = "table-main";
        }
    });
    
    $modal_delete_bulk.on("click", ".delete-row", function() {
        $.ajax({
            type : "DELETE",
            url  : "pages/"+generic+"/"+url_param+"?id="+window.table_checked.join(","),
            success: function() {
                $("."+table_refresh).DataTable().ajax.reload();
                toastr.success("Deletado com sucesso.");
            },
            error: function() { toastr.error("Problemas ao deletar registro!"); }
        });

        $modal_delete_bulk.modal("hide");
    });

    // Busca por texto em toda a tabela
    $(".search-all").on("keyup", function (e) {
        if(e.keyCode == 13 && (generic == "clients") || generic != "clients") {
            $datatable.search(this.value).draw();

            if(this.value == "") {
                $(".search-query").parent().hide();
            } else {
                $(".search-query").parent().show();
            }
                
            $(".search-query").text(this.value);
        }
    });

    // Busca por número de bilhete
    $(".numberTicket").on("keyup", function (e) {
        let str = $(this).val();

        if(str.length == 0) {
            $(".content").addClass("loading");
            
            $datatable.ajax.url(original_url).load(function() {
                $(".content").removeClass("loading");
            });
        }
    });

    $(".dropdown-menu.search-type a").on("click", function() {
        let type = $(this).data("type");

        if(type == "all") {
            $(".search-all").show().focus();
           
            $datatable.ajax.url(original_url).load(function() {
                $(".content").removeClass("loading");
            });
        }
    });

    function getRowId(element) {
        var $tr = $(element).closest("tr");

        if($tr.hasClass("child")) {
            $tr = $tr.prev("tr");
        }

        return $tr.attr("id").replace("row_", "");
    }

    function editRow(generic_id) {
        $.ajax("pages/"+generic+"/form?id="+generic_id)
        .done(function(data) {
            $modal_edit.removeClass("add").addClass("edit").find(".modal-body:first").html(data);
            $modal_edit.modal("show").find(".modal-title:first").text("Editar");
        });
    }

    function mountFooter(data) {
        $.each(data, function( index, value ) {
            if(typeof value.display != "undefined") {
                $("."+index).text(value.display);
            } else {
                $("."+index).text(value);
            }
        });
    }
    
    function afterModalShowUp() {
        $(".modal-content form").removeClass("was-validated");

        // temos telas que não precisam salvar/cancelar
        $modal_edit.find(".modal-footer .btn-save").toggle(window.modal_options.includes("save"));
        $modal_edit.find(".modal-footer .btn-cancel").toggle(window.modal_options.includes("cancel"));

        // alguns modais tem ações extras (relatorios, ...)
        if($(".modal-body .more-options-modal").length > 0) {
            $(".modal-form .modal-footer .more-options-modal").remove();
            $(".more-options-modal").appendTo($(".modal-form .modal-footer"));
        }

        if($modal_edit.hasClass("edit")) {
            $(".more-options-modal").show();
        }
    }

    $(".printable").on("hide.bs.modal", function() {
        if(window.reportFrom == "modal") {
            $(".modal-form").modal("show");
            window.reportFrom = false;
        }
    });

    $modal_edit.on("show.bs.modal", afterModalShowUp);

    $modal_edit.on("shown.bs.modal", function (e) {

        window.reportFrom = false;
        $caller = $(e.relatedTarget);

        if($caller.hasClass("btn-danger")) {
            $modal_edit.find(".modal-body").load("pages/"+generic+"/form", function() {
                $modal_edit.find("input:visible:enabled:first").focus();
                afterFormLoadedActions();
            });

            $modal_edit.find(".modal-title").text("Adicionar");
        }

        $modal_edit.find("input:visible:enabled:first").focus();
        afterFormLoadedActions();
    });

    $(".dataTable").on("click", "button.more-options", function(e) {

        var $button = $(this),
        $row        = $button.closest("tr"),
        edit = del  = separator = null;

        if($button.next(".dropdown-menu").length == 0) {
            var $dropdown = $("<div />", {class: "dropdown-menu dropdown-menu-right", "aria-labelledby": "drop_"+$row[0].id});

            if(table_options.includes("edit") && checkPermissions($row[0], "alteracao")) {
                edit = "<a class='dropdown-item edit' href='#'>Editar</a>";
            }

            if(generic != "clients" && generic != "agents" && table_options.includes("delete") && checkPermissions($row[0], "exclusao")) {
                del = "<a class='dropdown-item delete text-danger' href='#'>Deletar</a>";
            }

            if((table_options.includes("delete") && checkPermissions($row[0], "exclusao") || 
                table_options.includes("edit") && checkPermissions($row[0], "alteracao")) && table_options.includes("separator")) {
                separator = "<div class='dropdown-divider'></div>";
            }
            
            $dropdown.append(edit, del, separator);

            if(table_options.includes("delete") && checkPermissions($row[0], "exclusao") || 
                table_options.includes("edit") && checkPermissions($row[0], "alteracao")) {
                $button.attr("data-bs-toggle", "dropdown");
                
                $button.after($dropdown);

                $(this).click();
            }
        }
    });

    $(document).on("click", ".dropdown-reports a", function() {
        if($(this).attr('data-type')) {
            window["ReportsObj"][$(this).attr('data-type')].call();
        }
    });

    $.fn.DataTable.ext.pager.numbers_length = 5;
});

function checkPermissions(row, per) {
    url     = location.href.split("/"),
    generic = url[url.length - 1].replace("#", "");

    var return_data = false,
    id_agent      = row.getAttribute("id_agent");

    if(generic == "users" || generic == "states") {
        var id_ref = row.getAttribute("id").replace("row_", "");//Gera erro em telas HD

        if(per == "exclusao") {
            if(localStorage.getItem("idUserSession") == id_ref) {
                return return_data;
            } else if(localStorage.getItem("idStateSession") == id_ref) {
                return return_data;
            }
        }
    }

    if(id_agent == undefined) {
        $.each(page_data, function(i, item) {
            if(item[per] == "S") {
                return_data = true;
            }
        });
    } else {
        return_data = page_data[id_agent] ? (page_data[id_agent][per] == "S") : "N";
    }

    return return_data;
}

function permissionDoubleClik() {
    url     = location.href.split("/"),
    generic = url[url.length - 1].replace("#", "");

    var return_response = false;

    if(generic == "index" || generic == "sales") {
        return_response = true;
    }

    return return_response;
}

function alertModalOnDelete(generic, $modal) {
    var html = "Ao efetuar essa ação os dados das seguintes tabelas serão excluidos:";

    if(generic == "activities") {
        $(".modal-dialog").removeClass("modal-sm");
        $(".modal-dialog").addClass("modal-md");

        html += "<br><br><div class='text-danger'>";
        html += "<b><li>permissao</li></b>";
        html += "</div>";
    }

    if(generic == "activities") {
        html += "<div class='mt-3 font-weight-bold'>Esta ação não pode ser desfeita, tem certeza?</div>";

        $(".modal-body").css("display", "block");
        $modal.find(".modal-body").html(html);
    }
}