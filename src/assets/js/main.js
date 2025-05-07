"use strict";

(function($) {
    "use strict";

    //Testes Futuros
    
    /*$('.sub-nav').delegate('li a', 'click', function() {
        var target = $(this).attr('id');

        $(".content-wrapper").load(target);
    });*/

    /************************************************
    Alternar Preloader no card ou boc
    ************************************************/

    $('body').delegate('[data-bs-toggle="loader"]', 'click',  function() {
        var target = $(this).attr('data-bs-target');
        $('#'+target).show();
    });

    /************************************************
    Toggle Sidebar Nav
    ************************************************/

    $('body').delegate('.toggle-sidebar', 'click',  function(e) {
        var $sidebar = $(".sidebar");
        $sidebar.toggleClass("collapsed");

        if(localStorage.getItem("asideMode") === 'collapsed') {
            localStorage.setItem("asideMode", 'expanded')
        } else {
            localStorage.setItem("asideMode", 'collapsed')
        }

        return false;
    });

    var p;

    $.fn.setAsideMode = function() {
        if(localStorage.getItem("asideMode") === null) {
        } else if(localStorage.getItem("asideMode") === 'collapsed') {
            $('.sidebar').addClass('collapsed');
        } else {
            $('.sidebar').removeClass('collapsed');
        }
    }

    if($(window).width() > 768) {
        $.fn.setAsideMode();
    }

    /************************************************
    Sidebar Nav Accordion
    ************************************************/

    $('body').delegate( '.navigation li:has(.sub-nav) > a', 'click', function() {
        $(this).siblings('.sub-nav').slideToggle();
        $(this).parent().toggleClass('open');
        return false;
    });

    /************************************************
    Posição do submenu de estado colapsado da barra lateral
    ************************************************/

    $('body').delegate( '.navigation ul li:has(.sub-nav)', 'mouseover', function() {
        if($(".sidebar").hasClass("collapsed")) {
            var $menuItem = $(this), $submenuWrapper = $('> .sub-nav', $menuItem);

            // grab the menu item's position relative to its positioned parent
            var menuItemPos = $menuItem.position();

            // place the submenu in the correct position relevant to the menu item
            $submenuWrapper.css({
                top: menuItemPos.top,
                left: menuItemPos.left + $menuItem.outerWidth()
            });
        }
    });

    /************************************************
    Alternar controles em dispositivos pequenos
    ************************************************/

    $('body').delegate('.toggle-controls', 'click', function() {
        $('.controls-wrapper').toggle().toggleClass('d-none');
    });

    /************************************************
    Toast de Mensagens 
    ************************************************/

    $('body').delegate( '[data-bs-toggle="toast"]', 'click', function() {
        var dataAlignment = $(this).attr( 'data-alignment' );
        var dataPlacement = $(this).attr( 'data-placement' );
        var dataContent   = $(this).attr( 'data-content' );
        var dataStyle     = $(this).attr( 'data-style' );

        if( $('.toast.' + dataAlignment + '-' + dataPlacement).length  ) {
            $('.toast.' + dataAlignment + '-' + dataPlacement ).append('<div class="alert alert-dismissible fade show alert-' + dataStyle + ' "> ' + dataContent + '<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="bi bi-x font-size-18"></span></button></div>');
        } else {
            $('body').append('<div class="toast ' +  dataAlignment + '-' + dataPlacement + '"> <div class="alert alert-dismissible fade show alert-' + dataStyle + ' "> ' + dataContent + '<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="bi bi-x font-size-18"></span></button></div> </div>');
        }
    });

    var toastr_defaults = {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: false,
        onclick: null,
        showDuration: 300,
        hideDuration: 1000,
        timeOut: 5000,
        extendedTimeOut: 1000,
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    }

    // Controle de sessão
    loadSession();
    
    $(document).ajaxComplete(function() {
        // toda vez que roda um Ajax, zeramos o interval para iniciar o contador novamente
        clearTimeout(window.session_exp);
        clearTimeout(window.session_tout);
        loadSession();
    });

    // Defining toastr defaults
    toastr.options = toastr_defaults;

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
        $("body").addClass("mobile");

    $(".doc-modal").on("click", ".modal-footer .btn-print", function() {
        $(".doc-modal .modal-body").printThis({
            loadCSS: "assets/css/app/report.css"
        });
    });
})(jQuery);

/************************************************
Formatação de Valores
************************************************/

function withSeparator(vr) {
	return parseFloat(vr).toLocaleString("pt-br");
}

/************************************************
Método resposável por fazer os tratamentos de erros no formulário
************************************************/

function showFormErrors(errors, clearAll = true) {
	if(clearAll) {
		$(".modal-form form *").removeClass("is-invalid");
	}

	errors.forEach(function (error, index) {
		if(error.field == "all") {
			toastr.error(error.message);
        } else {
			var $field = $("[name='"+error.field+"']");
			$field.addClass("is-invalid");
            var $feedback = $field.siblings(".invalid-feedback");
            
			if($feedback.length == 0) {
				$field.after("<small class='invalid-feedback'></small>");
				$feedback = $field.siblings(".invalid-feedback");
            }
            
			$feedback.text(error.message);
		}
	});
}

function clearElementError($JQElement) {
	$JQElement = $($JQElement);
	$JQElement.removeClass("is-invalid");
}

/************************************************
Método resposável por atualizar a sessão no front-end
************************************************/

function loadSession() {
	window.session_exp = setTimeout(function() {
		toastr.options = {
			timeOut: 60000,
			progressBar: true,
			positionClass: "toast-top-center",
			onclick: function() { location.reload(); }
        };
        
		toastr.info("Sua sessão expira em 1 minuto");

		window.session_tout = setTimeout(function() { window.location.href = "/logout?expired=true"; }, 60000); // 1 minuto
	}, 180 * 60000); // 3 hora
}

function afterFormLoadedActions() {
	$.applyDataMask();
	addAsterisk();
}

/************************************************
Método resposável por adicionar obrigatoriedade nos campos que possuem o required
************************************************/

function addAsterisk() {
	$("[required]").closest(".col-md-12").addClass("required").children("label").attr("title", "Campo obrigatório");
    $(":input").not("[required]").closest(".col-md-12").removeClass("required").children("label").removeAttr("title");
}

/************************************************
Método resposável por verificar os campos obrigatórios
************************************************/

function checkRequiredFields(form) {
	if(form.checkValidity() === false) {
		event.preventDefault();
		event.stopPropagation();
		form.classList.add('was-validated');

		$(".nav-tabs a").removeClass("with-error");

		// Aba com o primeiro campo com erro
		var $invalids = $(".form-control:invalid");
		var tab = $invalids.first().closest(".tab-pane").attr("id");

		$('.nav-tabs a[href="#' + tab + '"]').tab("show");
		$invalids.first().focus();

		$invalids.filter(function() {
			var tab = $(this).closest(".tab-pane").attr("id");
			var $nav_link = $('.nav-tabs a[href="#' + tab + '"]');

			if(!$nav_link.hasClass("with-error")) {
				$nav_link.addClass("with-error");
            }
		});

		$invalids.filter(function() {
			if($(this).next("small.invalid-feedback").length > 0) return false;
            
			var label = $(this).prev("label").text();

            if($(this)[0].localName == "select") {
                var $feedback = $("<small />", { class: "invalid-feedback", text: "Selecione o campo "+label });
                if($(this).next("span.select2-container").next("small.invalid-feedback").length === 0) {
                    $(this).next().after($feedback);
                }
            } else {
                var $feedback = $("<small />", { class: "invalid-feedback", text: "Preencha o campo "+label });
                $(this).after($feedback);
            }
        });
        
		return false;
	} else return true;
}

/************************************************
Método resposável por acionar as propriedades de Print
************************************************/

$(document).ready(function () {
	$('.modal.printable').on('shown.bs.modal', function () {
		$('body').addClass('modal-printable');
	}).on('hidden.bs.modal', function () {
		$('body').removeClass('modal-printable');
	});
});

/************************************************
Método resposável por fazer a cópia dos dados para a área de transferência
************************************************/

$(".copy-text").on("click", function() {
	copyToClipboard("doc-body");
});

function copyTextToClipboard(text) {
    text = text ? text.trim() : text;

	navigator.clipboard.writeText(text).then(function() {
		toastr.success("copiado para área de transferência.");
	}, function(err) {
		console.error('Async: Erro ao copiar o texto: ', err);
	});
}

function copyToClipboard(containerid) {
	if(document.selection) {
		var range = document.body.createTextRange();
		range.moveToElementText(document.getElementById(containerid));
		range.select().createTextRange();
		document.execCommand("copy");
	} else if(window.getSelection) {
		var range = document.createRange();
		range.selectNode(document.getElementById(containerid));
		window.getSelection().removeAllRanges()
		window.getSelection().addRange(range);
		document.execCommand("copy");
		toastr.success("Texto copiado.");
		window.getSelection().removeAllRanges()
	}
}

/************************************************
Método resposável por fazer os tratamentos de validações de CPF/CNPJ
************************************************/

function validateIdentity(value_field) {
	var $field_param = $(value_field),
		cpf = value_field.value;

	clearElementError($field_param);

	if(cpf == '') {
        cpf = false;
    }

	if(cpf) {
		var cpf = cpf.replace(/\D/g, '');

		if(cpf.length == 11) {// checa se é cpf
			if(validateCPF(cpf) == false ) {
				showFormErrors([{field: value_field.id.toUpperCase(), message: "CPF inválido!"}], false);
				return false;
			} else {
				return true;
			}
		 } else {
			showFormErrors([{field: value_field.id.toUpperCase(), message: "CPF inválido!"}], false);
			return false;
		}
    }
    
	return false;
}

function validateCPF(s) {
    var i;
    var c  = s.substr(0,9);
    var dv = s.substr(9,2);
    var d1 = 0;

    for(i = 0; i < 9; i++) {
        d1 += c.charAt(i)*(10-i);
    }
    
    if(d1 == 0) return false;

    d1 = 11 - (d1 % 11);
    
    if(d1 > 9) d1 = 0;
    if(dv.charAt(0) != d1) {
        return false;
    }

    d1 *= 2;

    for(i = 0; i < 9; i++) {
        d1 += c.charAt(i)*(11-i);
    }

    d1 = 11 - (d1 % 11);

    if(d1 > 9) d1 = 0;
    if(dv.charAt(1) != d1) {
        return false;
    }
    
    return true;
}

function validaCNPJ(s) {
    var i;
    var c  = s.substr(0,12);
    var dv = s.substr(12,2);
    var d1 = 0;

    for(i = 0; i < 12; i++) {
        d1 += c.charAt(11-i)*(2+(i % 8));
    }

    if(d1 == 0) return false;

    d1 = 11 - (d1 % 11);

    if(d1 > 9) d1 = 0;

    if(dv.charAt(0) != d1) {
        return false;
    }

    d1 *= 2;

    for(i = 0; i < 12; i++) {
        d1 += c.charAt(11-i)*(2+((i+1) % 8));
    }

    d1 = 11 - (d1 % 11);

    if(d1 > 9) d1 = 0;
    if(dv.charAt(1) != d1) {
        return false;
    }

    return true;
}

function validaCPFCNPJ(CpfCnpjField){
	var $Campo  = $(CpfCnpjField),
		CpfCnpj = CpfCnpjField.value;

	clearElementError($Campo);

	if(CpfCnpj == '')
		CpfCnpj = false;

	if(CpfCnpj) {
		var CpfCnpj = CpfCnpj.replace(/\D/g, '');
		
		if(CpfCnpj.length == 11) {// checa se é cpf
			if(validateCPF(CpfCnpj) == false ) {
				showFormErrors([{field: CpfCnpjField.id, message: "CPF inválido!"}], false);
				return false;
			} else {
				return true;
			}
		 } else if(CpfCnpj.length == 14) {// checa se é cnpj
			if(validaCNPJ(CpfCnpj) == false ) {
				showFormErrors([{field: CpfCnpjField.id, message: "CNPJ inválido!"}], false);
				return false;
			} else {
				return true;
			}
		} else {
			showFormErrors([{field: CpfCnpjField.id, message: "CPF/CNPJ inválido!"}], false);
			return false;
		}
    }
    
	return false;
}

/************************************************
Método resposável por atualizar os dados retornados da API de CEP
************************************************/

function loadDataCEP(cep, return_result = false, reference = false) {

    if(!return_result) {
        var $Group    = $(cep).closest(".endereco");

        var $address  = $Group.find("input[data-cep='Endereco']"),
            $district = $Group.find("input[data-cep='Bairro']"),
            $City     = $Group.find("input[data-cep='Cidade']"),
            $state    = $Group.find("select[data-cep='UF']");

        clearElementError($(cep));

        $address.val(""); $district.val(""); $City.val("");
    }

    var nr_cep = cep.value.replace(/\D/g, '');
    $state.find("option[value='']").prop("selected", true);

    if(nr_cep.length == 0) {
		return false;
	} else if(cep.value.length == 9) {
        var url_awesomeapi = "https://cep.awesomeapi.com.br/json/";
        var url_apicep     = "https://cdn.apicep.com/file/apicep/";

        $.ajax({
            method : "GET",
            url    : url_awesomeapi+nr_cep,
        }).done(function(data) { 
            if(!return_result) {
                $address.val(data.address);
                $district.val(data.district);
                $City.val(data.city);

                $state.find("option[siglaUF='"+data.state+"']").prop("selected", true);
                return true;
            }
        }).fail(function() {
            $.ajax({
                method : "GET",
                url    : url_apicep+cep.value+".json",
            }).done(function(data) {
                if(!return_result) {
                    $address.val(data.address);
                    $district.val(data.district);
                    $City.val(data.city);

                    $state.find("option[siglaUF='"+data.state+"']").prop("selected", true);
                    return true;
                }
            }).fail(function() {
                if(!return_result) {
                    showFormErrors([{field: cep.id.toUpperCase(), message: "CEP não encontrado!"}], false);
                } else {
                    $(".alert-"+reference).addClass("alert-warning");
                    $(".content-search-cep-"+reference).removeClass("loading");
                    $("#msg_mural_"+reference).html("<b>CEP não encontrado!</b>");
                }
            });
        });
	} else {
        if(!return_result) {
		    showFormErrors([{field: cep.id.toUpperCase(), message: "CEP inválido!"}], false);
        } else {
            $(".alert-"+reference).addClass("alert-danger");
            $(".content-search-cep-"+reference).removeClass("loading");
            $("#msg_mural_"+reference).html("<b>CEP inválido!</b>");
        }
	}
}

function verifyRange(x, y, cep) {
    if((x < y) && (x <= cep) && (cep <= y)) {
        return true;
    } else if((y <= x) && (y <= cep) && (cep <= x)) {
        return true;
    }

    return false;
}

/************************************************
Método resposável por definir quais os gráficos ou totalizadores da tela
************************************************/

function getConfigsGraphic(generic) {
    var gaphic_index = "";

    if(generic == "index" || generic == "") {
        gaphic_index = { 'graph': [] };
    }

    return gaphic_index;
}

function getSizeTotals(totals_type) {
    var total_index = "";

    if(totals_type == "totals_dashboard") { total_index = 4; }
   
    return total_index;
}

/************************************************
Métodos resposáveis por formatar/criar/atualizar/validar datas
************************************************/

function extractDate(date) {
    return date.toISOString().split('T')[0];
}

function extractTime(date) {
    return date.toISOString().slice(11, 19);
}

function getCurrentDate() {
    return extractDate(new Date());
}

function getMaximumSchedulingDate() {
    var max_date = new Date();
    max_date.setFullYear(max_date.getFullYear() + 1);
    return extractDate(max_date);
}    

function validateDate(element) {
    var reported_date = $(element).val();
    var current_date  = getCurrentDate();
    var maximum_date  = getMaximumSchedulingDate();

    if(moment(reported_date).isBefore(current_date)) {
        showFormErrors([{field: $(element).attr('name'), message: "Insira uma data maior ou igual a data atual!"}], false);
        return false;
    } else if(moment(reported_date).isAfter(maximum_date)) {
        showFormErrors([{field: $(element).attr('name'), message: "Novos agendamentos somente para no máximo 1 ano!"}], false);
        return false;
    } else if(moment(reported_date).isAfter(current_date) || moment(reported_date).isSame(current_date)) {
        clearElementError($(element));
        return true;
    };
}

function addOneMonthToDate(element) {
    var current_date = new Date();
    current_date.setMonth(current_date.getMonth() + 1);

    if(current_date.getDate() === 1) {
        current_date.setDate(0);
    }

    var formattedDate = moment(current_date).format('YYYY-MM-DDTHH:mm');

    $(element).val(formattedDate);
}

function addOneMonthToDateRegisters(element) {
    var currentDate = new Date();
    currentDate.setMonth(currentDate.getMonth() + 1);

    if(currentDate.getDate() != new Date().getDate()) {
        currentDate.setDate(0);
    }

    var year  = currentDate.getFullYear();
    var month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
    var day   = currentDate.getDate().toString().padStart(2, '0');

    var formattedDate = year + '-' + month + '-' + day;
    $(element).val(formattedDate);
}

function checkupDates(event_type) {
    if(event_type == 'selectable') {
        return validateDate('#data_hora_agendamento');
    }

    if(event_type == 'editable') {
        if($('#new_schedule').is(':checked')) {
            return validateDate('#data_hora_agendamento');
        } else {
            var action = $('#data_hora_agendamento_edicao').is(':disabled') ? true : validateDate('#data_hora_agendamento_edicao');
            return action;
        };
    };    
};

/****************************************************************************
 * Funções genéricas
******************************************************************************/

function getURLParam() {
    let url     = location.href.split("/");
    let generic = url[url.length - 1].replace("#", "");

    return generic;
}

/************************************************
Eventos resposáveis pela modal #event_modal ou .event-form-modal
************************************************/
var date_backup;
var event_modal = $("#event_modal");

event_modal.on('shown.bs.modal', function (e) {
    let id_client   = $('#client_id').val();

    $(this).find("input:visible:enabled:first").focus();
    $(this).find(".content-clients").addClass("loading");
    $(this).find(".content-schedules").addClass("loading");

    $(this).find(".content-clients").load("pages/clients/tabs/client?id_client=" + id_client + "&page_param=true&page=index", function () {
        $(this).addClass('report');
        event_modal.find(".content-clients").removeClass("loading");
    });

    $(this).find(".content-schedules").load("pages/schedules/tabs/schedules?id_client=" + id_client + "&page_param=index", function () {
        $(this).addClass('report');
        event_modal.find(".content-schedules").removeClass("loading");
    });
}).on('hidden.bs.modal', function (e) {
    let url = getURLParam();
    $(this).find(".content-clients, .content-schedules").html('');
    url == 'index' ? $('#client-search').focus() : '';
}).on('click', '.btn-save', function (e) {
    e.preventDefault();
    $(this).closest('.modal-content').find('.event-form').submit();
}).on('blur', 'input[type="datetime-local"]', function (e) {
    validateDate(this);
});

function prepareEventData(submit) {
    let event_type = event_modal.data('modal-type');

    if ((checkRequiredFields(submit)) && checkupDates(event_type) && (validateClient())) {
        let event = {};
        let event_data = {};
        let action;
        
        if (event_type == 'selectable') {
            action = "POST";
            
            event = {
                "completed" : $('#event_completed').is(':checked') ? true : false,
                "start"     : $('#data_hora_agendamento').val(),
                "color"     : $('#event_completed').is(':checked') ? 'green' : '#ff7c7c'
            };

            event_data = {
                "idCliente"           : $('#client_id').val(),
                "obsPendente"         : $('#obs_pendente').val(),
                "obsAtendimento"      : $('#obs_atendimento').val(),
                "dataHoraAgendamento" : $('#data_hora_agendamento').val(),
                "dataHoraAtendimento" : moment().format('YYYY-MM-DDTHH:mm')
            };

        } else if (event_type == 'editable') {
            action = "PUT";
            
            event = {
                "completed"     : $('#event_completed').is(':checked') ? true : false,
                "start_edicao"  : $('#data_hora_agendamento_edicao').val(),
                "color"         : $('#event_completed').is(':checked') ? 'green' : '#ff7c7c'
            };

            event_data = {
                "id"                        : $('#event_id').val(),
                "atendido"                  : $('#event_completed').is(':checked') ? true : false,
                "idCliente"                 : $('#client_id').val(),
                "obsPendenteEdicao"         : $('#obs_pendente_edicao').val(),
                "obsAtendimentoEdicao"      : $('#obs_atendimento_edicao').val(),
                "dataHoraAgendamentoEdicao" : $('#data_hora_agendamento_edicao').val()
            };

            if ($('input[name=newSchedule]:checked').val()) {
                event_data["start"]               = $('#data_hora_agendamento').val();
                event_data["obsPendente"]         = $('#obs_pendente').val();
                event_data["obsAtendimento"]      = $('#obs_atendimento').val();
                event_data["dataHoraAgendamento"] = $('#data_hora_agendamento').val();
                event_data["newSchedule"]         = true;
            };
        };

        return { success: true, action, event_data, event };
    } else {
        return { success: false, message: "Erro na validação dos dados." };
    };
};

function submitEventData(page, action, event_data, callback) {
    if(action == 'POST') {
        event_data['update_last_schedule'] = true;
    };

    $.ajax({
        url: "pages/index/controller",
        type: action,
        data: event_data,
        success: function(data) {
            var response = JSON.parse(data);
            callback ? callback(response) : "";

            $('#obs_pendente').val('');
            $('#obs_atendimento').val('');
            $('#event_modal').modal('hide');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Erro ao salvar o evento:', textStatus, errorThrown);
            toastr.error('Erro ao salvar o evento!');
        }
    });
};

function prepareAndShowModal(info) {
    let event_type  = event_modal.data('modal-type');

    $(".modal-content form").removeClass("was-validated");
    
    clearElementError(".event-form-modal .form-control");
    
    addOneMonthToDate('#data_hora_agendamento');

    if(event_type == 'editable') {
        // Configura exibição dos elementos
        event_modal.find(".new-schedule, .edit-schedule, .opt-add-event, .opt-new-event").removeClass('d-none');
        // Configura obrigatoriedade dos campos
        event_modal.find(".edit-schedule").find('input:not(#event_completed, #new_schedule)').addClass('required').prop('required', true);
        // Marcar os checkboxes como "checked"
        event_modal.find('#new_schedule, #event_completed').prop('checked', true);
        // Habilitar os elementos
        event_modal.find('#obs_pendente, #obs_atendimento, #data_hora_agendamento').prop('disabled', false);
        // Desabilitar os elementos
        event_modal.find('#data_hora_agendamento_edicao, #obs_pendente_edicao, #obs_atendimento_edicao').prop('disabled', true);

        // Adiciona valores ao modal
        event_modal.find('#client_id').val(info.event.extendedProps.idCliente);
        event_modal.find('#event_id').val(info.event.id);
        event_modal.find('#obs_pendente').val(info.event.extendedProps.obsPendente);
        event_modal.find('#obs_pendente_edicao').val(info.event.extendedProps.obsPendente);
        event_modal.find('#obs_atendimento_edicao').val(info.event.extendedProps.obsAtendimento);
        event_modal.find('#data_hora_agendamento_edicao').val(moment.utc(info.event.start).format('YYYY-MM-DDTHH:mm'));
        
        document.querySelectorAll('.fc-more-popover').forEach(function(popover) {
            popover.style.display = 'none';
        });     
    };

    if(event_type == 'selectable') {
        $('.client-search-modal').modal('hide');
 
        // Configura exibição dos elementos
        event_modal.find(".edit-schedule, .opt-add-event, .opt-new-event").addClass('d-none');
        event_modal.find(".new-schedule").removeClass('d-none');
        // Configura obrigatoriedade dos campos
        event_modal.find(".edit-schedule").find('input').removeClass("required").prop('required', false);
        // Marcar os checkboxes como "unchecked" 
        event_modal.find('#new_schedule, #event_completed').prop('checked', false);
        // Habilitar os elementos
        event_modal.find('#obs_atendimento, #obs_pendente, #data_hora_agendamento').prop('disabled', false);
        // Desabilitar os elementos
        event_modal.find('#obs_pendente_edicao, #obs_atendimento_edicao, #data_hora_agendamento_edicao').prop('disabled', true);
        // Resetar campos
        event_modal.find('#event_id, #obs_pendente, #obs_atendimento').val('');
    };

    event_modal.modal('show');

    $('#obs_pendente').focus();

    date_backup = $('#data_hora_agendamento_edicao').val();
};

function validateClient() {
    var span_title = $('#select2-idCliente-container').attr('title');

    if(span_title === "Selecione um cliente") {
        return false;
    } else {
        return true;
    };
};