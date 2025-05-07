
var check = false;
    
var cols = [
    "nome",
    {
        data: "CNPJ_CPF",
        className: "text-center"
    },
    "cidade",
    "email",
    "telefone",
    "celular",
    {
        data: "ativo",
        className: "text-center"
    }
];

order = [1, 'asc'];

$(document).ready(function() {
    $(".modal-form").on("blur", "input[name='nome'], input[name='CNPJ_CPF']", function() {
        clearElementError($(this));
    });

    $(document).on("blur", "#cep", function() {
        loadDataCEP(this);
    });

    $(document).on("blur", "#CNPJ_CPF", function() {
        validaCPFCNPJ(this);
    });

    $(document).on("click", ".copy-text-info .icon-clone-text", function() {
        var text = $(this).parent().text();
        
        copyTextToClipboard(text);
    });

    var labels = {
        "F": {
            "CNPJ_CPF": "CPF",
            "CNPJ_CPF_MASK": "000.000.000-00",
        },
        "J": {
            "CNPJ_CPF": "CNPJ",
            "CNPJ_CPF_MASK": "00.000.000/0000-00",
        }
    };
    
    $(".modal-form").on("change", "input[name='tipo']", function() {
        if(!$("#id-edicao").val()) {
            $.each(labels[this.value], function(i, item) {
                $(".modal-form input[name='"+i+"']").closest(".form-group").children("label").text(item);
            });

            $("#CNPJ_CPF").mask(labels[this.value]["CNPJ_CPF_MASK"]);
        } else if($('input[name=tipo]:checked').val() == "J") {
            $("#estilo-fisico").addClass("btn btn-outline-primary active");
        } else {
            $("#estilo-juridico").addClass("btn btn-outline-primary active");
        }
    });
});