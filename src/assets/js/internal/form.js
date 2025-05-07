$(function() {
    /**
     * Esta é uma função genérica que envia o formulário (após validações nativas do navegador)
     * e retorna erros nos campos que o controller achou
     *  * Ela pode ser sobreescrita, criando um arquivo actions.js dentro da pasta desejada src/pages/aqui
     *  um exemplo é o formulário de permissões ou usuários.
     * * Para criar validações customizadas, crie uma função "genericCustomValidations", dentro do actions.js correspondente
     */

    $(".modal-form form").on("submit", function() {
        // validação de campos obrigatórios html5 (valid/invalid)
        if(!checkRequiredFields(this)) return false;

        //Função para desabilitar valores inativados do select ao editar
        $('select option:selected').each(function() {
            $(this).attr('disabled', false);
        });

        var action    = $(this).attr("action"),
            formData  = $(this).serializeArray(),
            $submit   = $(this).find("button[type='submit']"),
            $id_field = $(".modal-form form input[name='id']"),
            method    = ($id_field.length > 0) ? "PUT" : "POST",
            errors    = [];
        
        // custom validations
        if(typeof genericCustomValidations === "function") {
            errors = genericCustomValidations();
        }
    
        var $fields = $(".modal-form form :input.is-invalid");

        if($fields.length > 0) {
            toastr.error("Preencha o formulário corretamente!");
            $(".modal-form .modal-body").animate({ scrollTop: $fields.get(0) }, "fast");
            return false;
        }

        if(errors.length > 0) {
            showFormErrors(errors);
            return false;
        }

        $submit.attr("disabled", true);

        $.ajax({
            method: method, url: action, data: formData 
        })
        .done(function(data) {
            toastr.success(data);
            $(".modal-form").modal("hide").find(".modal-body").empty();
            $(".dataTable").DataTable().ajax.reload();
            $submit.removeAttr("disabled");
        })   
        .fail(function(data) {
            $submit.removeAttr("disabled");
            var data = JSON.parse(data.responseText);
            showFormErrors(data);
        });

        return false;
    });
});  