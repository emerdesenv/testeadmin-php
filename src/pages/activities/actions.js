var cols = ["codigo", "nome", "descricao", "descricao", "link"];

var check = false;
    
order = [1, 'asc'];

$(document).ready(function() {
    $(".modal-form").on("blur", "input[name='codigo']", function() {
        clearElementError($(this));
    });
});