var cols = [
    {
        data: "codigo",
    },
    {
        data: "nome",
        className: "text-left"
    }
];

order = [1, 'asc'];

$(document).ready(function() {
    $(".modal-form").on("blur", "input[name='codigo']", function() {
        clearElementError($(this));
    });
});