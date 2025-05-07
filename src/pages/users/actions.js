var vis_table     = true;
var vis_graphic   = false;
var vis_totals    = false;
var check         = false;

var cols = [
    "codigo", "nome", "email",
    {
        data: "dataNascimento",
        type: "date-eu",
        className: "text-center"
    },
    {
        data: "tipoUsuario",
        className: "text-center",
        render: function(tipoUsuario) {
            return "<span class='"+tipoUsuario.badge+"'>"+tipoUsuario.texto+"</span>";
        }
    },
    { data: "modelo", className: "text-center"},
    { data: "ativo", className: "text-center"}
];

order = [1, 'asc'];

$(document).ready(function() {
    $(".modal-form").on("blur", "input[name='codigo']", function() {
        clearElementError($(this));
    });
});

function genericCustomValidations() {
    var errors = [];

    if($("#senha").val() != $("#senha2").val()) {
        errors.push(
            { field: "senha", message: "Senha não pode ser diferente" },
            { field: "senha2", message: "Confirmação deve ser igual" }
        );
    }

    return errors;
}

$(function() {

    $modal = $(".modal-form");

    $modal.on("blur", "input[type='password']", function() {
        $password  = $("#senha");
        $password2 = $("#senha2");

        if($password.val() != $password2.val()) {
            let errors = [
                { field: "senha", message: "Senha não pode ser diferente" },
                { field: "senha2", message: "Confirmação deve ser igual" },
            ];

            showFormErrors(errors);
        } else {
            $password.removeClass("is-invalid").next("small").text("");
            $password2.removeClass("is-invalid").next("small").text("");
        }
    });

    $modal.on("blur", "input[name='codigo']", function() {
        $user = $("#codigo");
        $user.removeClass("is-invalid").next("small").text("");
    });
});