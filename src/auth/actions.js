var toastr_defaults = {
    closeButton: true,
    newestOnTop: true,
    progressBar: true,
    showDuration: 300,
    hideDuration: 1000,
    timeOut: 5000,
    extendedTimeOut: 1000
}

toastr.options = toastr_defaults;

var timerInterval = 0;

var enter_btn = document.getElementById("btn_enter");

$(".form-login").on("submit", function(event) {
    event.preventDefault();

    var action    = $(this).attr("action"),
        form_data = $(this).serializeArray(),
        method    = "POST";

    if(isElementInViewport(enter_btn)) { 
        enter_btn.disabled = true;
        enter_btn.textContent = "Processando...";
    }
    
    var final_array = form_data.filter(function(field) {
        return field.value.trim() !== "";
    });
    
    $.ajax({
        method : method, 
        url    : action+"?action=validate_login", 
        data   : final_array 
    })
    .done(function(data) {
        enter_btn.textContent = "Entrar";
  
        var response = JSON.parse(data);
  
        if(response.detail.method == "login") {
            clearInterval(timerInterval);
            window.location.href = response.detail.url;
        } else if(response.detail.statusCode == 200) {
            $(".text-phone").html(response.detail.phone);

            $("#user").prop("readonly", true);
        } else if(response.detail.statusCode == 400) {
            if(response.detail.fileds) {
                renderErrorsFields(response.detail.fileds);
            } else {
                toastr.error(response.detail.message);
            }
           
            if(isElementInViewport(enter_btn)) { enter_btn.disabled = false; }
        }
    });

    return false;
});

$(".opt-type-users").on("change", "select[name='perfilUsuario']", function() {
    if(this.value == 0) {
        $("#pass").val("");
        $("#user").val("");

        enter_btn.disabled = false;
    } else {
        clearFieldsUser();
    }
});

$(".content-select-product").on("change", "select[name='idProduto']", function() {
    var perfil = $("[name='perfilUsuario']").find(":selected").val();

    if(perfil != 0) { clearFieldsUser(); }
});

$("#selector_product").select2({
    placeholder: "Selecione um Produto",
    minimumResultsForSearch: 5
});

$("#selector_type_user").select2({
    placeholder: "Selecione um Perfil",
    minimumResultsForSearch: 5
});

function renderErrorsFields(errors) {
    for(let index = 0; index < errors.length; index++) {
        const element = errors[index];
        
        toastr.error(element.message);
    }
}

function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();

    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

function clearFieldsUser() {
    $("#pass").val("");
    $("#user").val("");
    
    $(".btn-enter").css("display", "none");
    $("#user").removeAttr("readonly");

    next_btn.disabled = false;
}