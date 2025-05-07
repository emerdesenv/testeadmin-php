var cols = [
    "nome", "codigo", "descricao",
    { data: "inclusao",  className: "text-center"},
    { data: "alteracao", className: "text-center"},
    { data: "exclusao",  className: "text-center"}
];

order = [1, 'asc'];

$(document).ready(function() {
    $(".modal-form").on("change", "select[name='idAgenciador'], select[name='idUsuario']", function() {

        var $id_activitie = $("select[name='idAtividade']");
        $id_activitie.attr("disabled", true).find("option:eq(0)").text("Carregando...");
        
        $.getJSON("pages/permissions/ajax.php?action=list_activities", {
            idUsuario    : $("select[name='idUsuario']").val(),
            idAgenciador : $("select[name='idAgenciador']").val()
        })
        .done(function(data) {
            $id_activitie.find("option").not(":eq(0)").remove();
            $id_activitie.attr("disabled", false).find("option:eq(0)").text("Selecione a Atividade");

            $.each(data, function(i, item) {
                $id_activitie.append($("<option />", { text: item.descricao, value: item.id }))
            });

            $id_activitie.trigger("change");
        });
    });

    $(".modal-form form").unbind("submit").on("submit", function() {
       
        if(!checkRequiredFields(this)) return false;

        var $id_field = $(".modal-form form input[name='id']"),
        method  = ($id_field.length > 0) ? "PUT" : "POST",
        action  = $(this).attr("action"),
        $submit = $(this).find("button[type='submit']"),
        $tabs   = $(this).find(".nav-tabs"),
        from    = $tabs.find("a.active").attr("id");

        var formData = (method == "PUT" && $tabs.length > 0) ? $tabs.find(".tab-pane.active").serializeArray() : $(this).serializeArray();
        $submit.attr("disabled", true);

        $.ajax({
            method : method,
            url    : action+"?from="+from,
            data   : formData
        }).done(function(data) {
            toastr.success(data);
            $(".modal-form").modal("toggle");
            $(".dataTable").DataTable().ajax.reload();
            $submit.removeAttr("disabled");
        }).fail(function(data) {
            $submit.removeAttr("disabled");
            var data = JSON.parse(data.responseText);
            showFormErrors(data);
        });

        return false;
    });

    $('.modal-form').on('shown.bs.tab', 'a[data-bs-toggle="tab"]', function (e) {
        $(".tab-pane.active :input").attr("disabled", false);
        $(".tab-pane:not(.active) :input").attr("disabled", true);
    });
});