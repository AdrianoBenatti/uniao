jQuery.fn.toggleAttr = function(attr) {
	return this.each(function() {
	var $this = $(this);
		$this.attr(attr) ? $this.removeAttr(attr) : $this.attr(attr, attr);
	});
};
// LOCKBUTTON
function lockButton(form) {
	if ($(form).valid()) {
		$('#gravar').hide();
		$('#gravar.pedidos').hide();
		$('#reiniciar').hide();
		$('#excluir').hide();
		$('#voltar').hide();
		$('#carregando').show();
	}else{
		$('.valid').each(
			function() {
				var id = $(this).parents('.indent').attr('id');
				$('.sidebar #a'+id+' .alert').each(function() {$(this).remove();});
				$('.sidebar #a'+id).removeClass('taberror');
			}
		);
		$('.error').each(
			function() {
				var id = $(this).parents('.indent').attr('id');
				$('.sidebar #a'+id).append('<span class="alert"></span>');
				$('.sidebar #a'+id).addClass('taberror');
			}
		);
	}
}

// PLACEHOLDER
var common = {
	init : function() {
		common.enablePlaceholder();
	},
	enablePlaceholder : function() {
		$('input[placeholder], textarea[placeholder]').placeholder();
	}
}

function flutua() {
	var msie6 = $.browser == 'msie' && $.browser.version < 7;
	if (!msie6 && $('#menu').offset()!=undefined) {
		var top = $('#menu').offset().top - parseFloat($('#menu').css('margin-top').replace(/auto/, 0));
		$(window).scroll(function (event) {
			var y = $(this).scrollTop();
			if (y >= top) {
				$('#navContainer').fadeIn().dequeue();
			} else {
				$('#navContainer').fadeOut().dequeue();
			}
		});
	}
}

// HOVER DO MENU
$(function() {
	x = '.menu ul li';
    $(x).hover(function() {
		$(this).addClass("hover");
		$(this).find('.sub').addClass("mostrar");
    }, function() {
		$(this).removeClass("hover");
		$(this).find('.sub').removeClass("mostrar");
    });
});

// ORDER UP/DOWN
$(function() {
	hovers = '.tabela .th td';
    $(hovers).not("#chk, #nohover").click(function() {
		if ($(this).attr("class") == "" || $(this).attr("class") == "hvrdown") {
			$(".hvrup").removeClass("hvrup")
			$(".hvrdown").removeClass("hvrdown")
			$(this).addClass("hvrup");
		}
		else {
			$(".hvrup").removeClass("hvrup")
			$(".hvrdown").removeClass("hvrdown")
			$(this).removeClass("hvrup");
			$(this).addClass("hvrdown");
		}
    });
});

// CHECK ALL
function sel_all() {
	$('table').find(':checkbox').attr('checked',true);
}

// UNCHECK ALL
function desel_all() {
	$('table').find(':checkbox').attr('checked',false);
}

// SEGUIR HREF DO ELEMENTO
function seguir(x) {
	location.href = $(x).attr("href");
}

// CHANGE CLASS ABAS DA SIDEBAR
function CngClass(obj){
	$("ul li.hover").removeClass("hover");
	$(obj).parent().addClass("hover");
}

// DESABILITAR GERENCIAR ESTOQUE
function UsarConfig() {
	if ($('#usarconfig').is(':checked')) {
		$('#gestoque').attr('disabled',true);
	} else {
		$('#gestoque').removeAttr('disabled');
	}
}

$(function() {
	clk = '#master h2 ul li a';
    $(clk).click(function() {
		$(this).parents("#tabs2").find('#first').click();
    });
});

// CHANGE CLASS ABAS DAS CATEGORIAS
function CngHvr(x){
	$("#master h2 ul li a.hvr").removeClass("hvr");
	$(x).addClass("hvr");
}

// CHANGE CLASS SUB ABAS PAINEL
function CngHvr2(x){
	$(".painel #formulario .sub h2 ul li a.hvr").removeClass("hvr");
	$(x).addClass("hvr");
}

// SELECIONAR TIPO DE PESSOA NO CLIENTE
function select_p() {
	sp = $("#pessoa option:selected").attr("value");
	if (sp == "fisica") {
		$(":input","#juridica").not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
		$("#"+sp).slideDown();
		$("#juridica").slideUp();
	}
	else if (sp == "juridica") {
		$(":input","#fisica").not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
		$("#"+sp).slideDown();
		$("#fisica").slideUp();
	}
	else {
		$("#fisica, #juridica").slideUp();
		$(":input","#fisica, #juridica").not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
		}
}

// ALTERAR ENDERE�O INCOMPLETO
function altend(x) {
	if (x == 'ok') {
		$('#altend2').hide();
		$('#altend1').slideDown();
	}
	else {
		$('#altend1').hide();
		$('#altend2').slideDown();
	}
}

function getEndereco() {
	// Se o campo CEP não estiver vazio
	if($($("#cep").val()) != ""){
		/*
			Para conectar no serviço e executar o json, precisamos usar a função
			getScript do jQuery, o getScript e o dataType:"jsonp" conseguem fazer o cross-domain, os outros
			dataTypes não possibilitam esta interação entre domínios diferentes
			Estou chamando a url do serviço passando o parâmetro "formato=javascript" e o CEP digitado no formulário
			http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep").val()
		*/
		$.getScript("http://www.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep").val(), function(){
			// o getScript dá um eval no script, então é só ler!
			//Se o resultado for igual a 1
	  		if(resultadoCEP["resultado"]){
				// troca o valor dos elementos
				$("#endereco").val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));
				$("#bairro").val(unescape(resultadoCEP["bairro"]));
				$("#cidade").val(unescape(resultadoCEP["cidade"]));
				var combo = document.form1.uf;
				for (x=1;x<combo.options.length;x++){
			    	if(combo.options[x].text == resultadoCEP["uf"]) {
  						combo.options[x].selected = true;
			    	}
				}
			}else{
				jAlert("Endereço não encontrado", "Atenção");
			}
		});
	}
}

function retirarAcento(objResp) {
	var varString = new String(objResp);
	var stringAcentos =   new String('áàãâäÁÀÃÂÄéèêëÉÈÊËíìîïÍÌÎÏóòõôöÓÒÕÔÖúùûüÚÙÛÜçÇñÑ&%$ ');
	var stringSemAcento = new String('aaaaaAAAAAeeeeEEEEiiiiIIIIoooooOOOOOuuuuUUUUcCnN____');

	var i = new Number();
	var j = new Number();
	var cString = new String();
	var varRes = '';

	for ( i = 0; i < varString.length; i++) {
		cString = varString.substring(i, i + 1);
		for ( j = 0; j < stringAcentos.length; j++) {
			if (stringAcentos.substring(j, j + 1) == cString) {
				cString = stringSemAcento.substring(j, j + 1);
			}
		}
		varRes += cString;
	}
	return varRes;
}

function clean(campo) {
	 document.getElementById(campo).value='';
}

function destaque(id, tabela) {
	msg = "Deseja tornar essa not\u00edcia como destaque?";
	jConfirm(msg, "Atenção", function(r){
		if (r){
			window.location.href="alteradestaque.php?id="+id+"&tabela="+tabela
		}
	});
}

function linkar(id, tabela) {
	msg = "Deseja tornar essa not\u00edcia como destaque?";
	jConfirm(msg, "Atenção", function(r){
		if (r){
			window.location.href="alteralinkar.php?id="+id+"&tabela="+tabela
		}
	});
}

function generateDataTable(idGrid, orderField, orderFieldDir, en){
	if(en==undefined) lang = url+"js/pt-br.txt";
	else lang = url+"js/en-us.txt";
	//totalCol = $("#"+ idGrid +" tr.th td").size() -1;
	aaSorting = [];
	$("table thead td").each(function(index){
		if($(this).attr('name')==orderField){
			aaSorting.push([index,orderFieldDir]);
			return false;
		}
	});
	var oTable = $('#'+idGrid).dataTable({
		//"aoColumnDefs": [
		//	{ "bSortable": false,  "aTargets": [ totalCol, totalCol-1 ] }
		//],
		"bStateSave": true,
		"oLanguage": {
			"sUrl": lang
		},
		"sDom": '<"top"flpi>rt<"bottom"flpi><"clear">',
		"aaSorting": aaSorting,
		"fnDrawCallback": function() {
			$(".adminlist").show();
		},
		"bAutoWidth": false
	});
	var oSettings = oTable.fnSettings();
	var columns = $('table .th-sub td');
	columns.each(function(index){
		search_string = oSettings.aoPreSearchCols[index].sSearch;
		if ( search_string !== ""){
			$(this).find('input').val(search_string);
			$(this).find('select').val(search_string.replace('^', '').replace('$', ''));
		}
	});
	$(".search input").keyup( function (){
		oTable.fnFilter( this.value, $(this).attr('index'));
	});
	$('table tr.search select').change(function () {
		oTable.fnFilter( "^"+$(this).val()+"$", $(this).attr('index'), true);
	});
	return oTable;
}

function generateDataTable2 (idGrid, orderField, orderFieldDir, autosave){
	totalCol = $("#"+ idGrid +" tr.th td").size() -1;
	aaSorting = [];
	$("#" + idGrid + " thead td").each(function(index){
		if($(this).attr('name')==orderField){
			aaSorting.push([index,orderFieldDir]);
			return false;
		}
	});
	var oTable = $("#" + idGrid).dataTable({
		"bProcessing": false,
		"aoColumnDefs": [
			{ "bSortable": false,  "aTargets": [ 0,totalCol ] }
		],
		"oLanguage": {
			"sUrl": url+"js/pt-br.txt"
		},
		"sDom": '<"top"lpi>rt<"bottom"lpi><"clear">',
		"bPaginate": true,
		"bLengthChange": true,
		"bFilter": true,
		"bInfo": true,
		"bStateSave": autosave==undefined? true: autosave,
		"bAutoWidth": false,
		"bRetrieve":true,
		"aaSorting": aaSorting,
		"fnDrawCallback": function() {
			$(".nav").show();
			$("#"+ idGrid).show();
		}
	});
	var oSettings = oTable.fnSettings();
	var columns = $('#' + idGrid + ' .th-sub td');
	columns.each(function(index){
		search_string = oSettings.aoPreSearchCols[index].sSearch;
		if ( search_string !== ""){
			$(this).find('input').val(search_string);
			$(this).find('select').val(search_string.replace('^', '').replace('$', ''));
		}
	});
	$("#" + idGrid+" .search input").keyup( function (){
		oTable.fnFilter( this.value, $(this).attr('index'));
	});
	$("#" + idGrid+" .search select").change( function () {
		oTable.fnFilter( "^"+$(this).val()+"$", $(this).attr('index'), true);
	});
	return oTable;
}

function generateDataTableMultiple (idGrid, orderField, orderFieldDir, n){
	var oTable = [];
	aaSorting = [];
	totalCol = $("#"+ idGrid +" tr.th td").size() -1;
	aaSorting[n] = [];
	$("#"+ idGrid +" thead td").each(function(index){
		if($(this).attr('name')==orderField){
			aaSorting[n].push([index,orderFieldDir]);
			return false;
		}
	});
	oTable[n] = $("#" + idGrid).dataTable({
		"bProcessing": false,
		//"aoColumnDefs": [
		//	{ "bSortable": false,  "aTargets": [ 0,totalCol ] }
		//],
		"aoColumnDefs": [
			{ "bSortable": false,  "aTargets": [ totalCol, totalCol ] }
		],
		"oLanguage": {
			"sUrl": url+"js/pt-br.txt"
		},
		"sDom": '<"top"lpi>rt<"bottom"lpi><"clear">',
		"bPaginate": true,
		"bLengthChange": true,
		"bFilter": true,
		"bStateSave": true,
		"bAutoWidth": false,
		"bRetrieve":true,
		"aaSorting": aaSorting[n],
		"fnDrawCallback": function() {
			$(".nav").show();
			$("#"+ idGrid).show();
		}
	});
	var oSettings = oTable[n].fnSettings();
	var columns = $("#"+ idGrid +"  .th-sub td");
	columns.each(function(index){
		search_string = oSettings.aoPreSearchCols[index].sSearch;
		if ( search_string !== ""){
			$(this).find('input').val(search_string);
			$(this).find('select').val(search_string.replace('^', '').replace('$', ''));
		}
	});
	$("#"+ idGrid +" tr.search input").keyup( function (){
		oTable[n].fnFilter( this.value, $(this).attr('index'));
	});
	$("#"+ idGrid +" tr.search select").change( function () {
		oTable[n].fnFilter( "^"+$(this).val()+"$", $(this).attr('index'), true);
	});
	return oTable[n];
}

function generateDataTableContas(idGrid, orderField, orderFieldDir){
	aaSorting = [];
	$("table thead td").each(function(index){
		if($(this).attr('name')==orderField){
			aaSorting.push([index,orderFieldDir]);
			return false;
		}
	});
	var oTable = $('#'+idGrid).dataTable({
		"bStateSave": true,
		"oLanguage": {
			"sUrl": url+"js/pt-br.txt"
		},
		"sDom": '<"top"lpi>rt<"bottom"lpi><"clear">',
		"aaSorting": aaSorting,
		"fnDrawCallback": function() {
			$(".adminlist").show();
		},
		"bAutoWidth": false
	});
	var oSettings = oTable.fnSettings();
	var columns = $('table .th-sub td');
	columns.each(function(index){
		search_string = oSettings.aoPreSearchCols[index].sSearch;
		if ( search_string !== ""){
			$(this).find('input').val(search_string);
			$(this).find('select').val(search_string.replace('^', '').replace('$', ''));
		}
	});
	$(".search input").keyup( function (){
		oTable.fnFilter( this.value, $(this).attr('index'));
	});
	$('table tr.search select').change(function () {
		oTable.fnFilter( "^"+$(this).val()+"$", $(this).attr('index'), true);
	});
	return oTable;
}

function addDesc(form){
	$.post(url + "incaltfotosdescricao.php", $(form).serialize(), function(data){
		if(data == "1") {
			$('.gal_imagens li#'+$("#idfoto").val()).find('img').attr('title',$("#descricao").val());
			$('.dialog-desc').dialog('close');
			jAlert('Descrição salvo com sucesso!', "Sucesso!");
			window.location.reload();
		}
		else {
			jAlert('Erro ao adicionar a descrição!', "Atenção!");
		}
	});
}

function salvarLista(sequence, tabela){
	$.post(url + "guardaOrdem.php?ordem="+encodeURI(sequence)+"&tabela="+tabela, function(data){
		if(!data) {
			$('.sucesso').show();
			setTimeout(function(){$('.sucesso').hide();}, 3000);
		}else{
			$('.error').show();
		}
	});
}

function deleteSelected(id, tabela, retorno, single){
	if(tabela == undefined) tabela = $('.adminlist').attr('id');
	if(single == true) id = $("input[name='id[]']:hidden");
	else id = $("input[name='id[]']:checked");
	msg = "Deseja realmente excluir este item ou o(s) iten(s) selecionado(s)?";

	if(id.size() == 0 && single == undefined){
		jAlert("Selecione pelo menos 1 item!", "Atenção!");
	}else{
		jConfirm(msg, "Atenção", function(r){
			if (r){
				$.post(url + "excluir.php?tabela="+tabela, id.serialize(), function(data){
					if(retorno) location.href = retorno;
					else window.location.reload();
					/*
					if(data){
						alert('Erro ao excluir, tente novamente mais tarde!');
					}else{
					}
					*/
				});
			}
		});
	}
}

var lastChecked = null;
function selectCheckbox(el, who, key){
	//if(who == "td") $(el).toggleAttr('checked');
	if(lastChecked == null) lastChecked = el;
	if(key){
		var checks = $('input:checkbox');
		var start = checks.index($(el));
        var end = checks.index(lastChecked);
        checar = $(lastChecked).attr('checked') == undefined? false: true;
        checks.slice(Math.min(start,end), Math.max(start,end)+ 1).attr('checked', checar);
	}
	lastChecked = el;
	/*
	// ANTIGO CLICK NA TD
	else{
		if(who == "td"){
			$(el).find('input:checkbox').toggleAttr('checked');
		}else if(who == "check"){
			$(el).toggleAttr('checked');
		}
	}*/
}

function saveContinue(){
	$('#continue').val(1);
	$('form[name="form1"]').submit();
}

function showFase(div){
	$('.fases').hide();
	$('#'+div).show();
}

function redirectHashClick(){
	if(location.hash){
		hash = location.hash.split('#');
		comp = hash[1].split('_');
		if(comp[0]) $('li.categoria'+comp[0]).find('a').click(); else $('li.categorias').first().find('a').click();
		if(comp[1]) $('#categoria'+comp[0]+' .sidebar ul li.categoria'+comp[0]+'_fase'+comp[1]).find('a').click(); else $('#torneio .categorias:visible').first().find('.sidebar ul li:first-child a').click();
		if(comp[2]) $('.rodadas:visible h3#rodada'+comp[2]).find('a').click(); else $('.rodadas:visible h3').first().find('a').click();
	}else{
		$('.categorias:visible').find('.sidebar ul li:first-child a').click();
	}
}

function clearFormInputs(form) {
	$(form).find(':input').each(function() {
		switch(this.type) {
			case 'password':
			case 'select-multiple':
			case 'select-one':
			case 'text':
			case 'textarea':
				$(this).val('');
			break;
			case 'checkbox':
			case 'radio':
				this.checked = false;
		}
	});
}

function tabear(el){
	$(el).find('.sidebar a').each(function(){
		$(this).click(function(){
			$('.content').find('.indent').hide();
			$($(this).attr('href')).show();
			return false;
		});
	});
	hash = location.hash;
	$('.content').find('.indent').hide();
	if(hash) $(hash).show();
	else $('.content').find('.indent').first().show();
}

function addSave(obj){
	var save = $(obj).parents('.indent').attr('id');
	if ($('.sidebar #a'+save+' .save').attr('class') == null) {
		$('.sidebar #a'+save).append('<span class="save"></span>');
	}
}

function initSaves(){
	$('input:not([readonly]), textarea:not([readonly])').live("keypress", function(){addSave($(this));});
	$('input:checkbox, input:radio, input:button').live("click", function(){addSave($(this));});
	$('select').live("change", function(){addSave($(this));});
}

function goTo(url){
	location.href=url;
}

function checkAll(el){
	check = $(el).find(':checkbox').first().attr('checked');
	$(el).find(':checkbox').each(function(){
		if(check == undefined) {
			$(this).attr('checked', true);
		}else{
			$(this).attr('checked', false);
		}
	});
}

/*
 * buildUploadify constroi/reconstroi uma instancia de uploadify,
 * os parametros são todos obrigatórios com exceção do 'form' e 'next'
 * form é utilizado quando a instancia estiver dentro de um dialog
 * next é utilizado quando precisa disparar outro uploadify
 *
 * Ex:
 *
 * HTML
 * <div id="uploadify" class="single"><input type="file" name="imagem" id="imagem" /></div>
 * <input type="hidden" name="nome_imagem" id="nome_imagem" value="" />
 * <input type="hidden" name="nome_imagem_bd" id="nome_imagem_bd" value="" />
 *
 * JS
 * buildUploadify('1', 'uploadify', '', '../../../media/temp/', false, 'imagem', 'linhas');
 *
 */
function buildUploadify(id, instance, galInstance, tempFiles, massive, field, tabela, form, next){
	jAlert('Build Uploadify depreciado!', "Atenção!");
}

function calculaTotal(){
	q = parseInt($('#qtd').val());
	p = $('#preco').val();
	p = p.split('.').join('');
	p = p.split(',').join('.');
	p = parseFloat(p);
	if(p>0 && q>0){
		t = p*q;
		t = t.toFixed(2).replace('.', ',');
		if(t=="NaN") t = 0;
		$('#total').val(t);
	}
}

function formatCurrency(num, sepMilhar, sepDecimal, charMoeda) {
	num = num.toString().replace(/[R$ ]|\$/g, '');
	if (isNaN(num)) {
		num = "0";
	}
	sign = (num == ( num = Math.abs(num)));
	num = Math.floor(num * 100 + 0.50000000001);
	cents = num % 100;
	num = Math.floor(num / 100).toString();
	if (cents < 10) {
		cents = "0" + cents;
	}
	for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) {
		num = num.substring(0, num.length - (4 * i + 3)) + sepMilhar + num.substring(num.length - (4 * i + 3));
	}
	return (((sign) ? '' : '-') + charMoeda + num + sepDecimal + cents);
}

function formataDinheiro(valor, pais) {
	switch(pais) {
		case 3:
			return formatCurrency(valor, ',', '.', '$ ');
			break;
		case 2:
			return formatCurrency(valor, ',', '.', '$ ');
			break;
		default:
			return formatCurrency(valor, '.', ',', 'R$ ');
			break;
	}
}

/**/
function buildPlupload(field, options){
	if(options==undefined){
		jAlert('Informe as opções!', "Atenção!");
	}

	var uploader = new plupload.Uploader($.extend(true, {
		runtimes : 'html5,flash,silverlight',
		url : url + 'plupload.php',
		chunk_size : '500KB',
		max_retries: 50,
		browse_button : field,
		flash_swf_url : url  + 'js/Moxie.swf',
		silverlight_xap_url : url  + 'js/Moxie.xap',
		multipart_params: {
			sid: sid
		},
		preinit : {
			Init: function(up, info) {
				$('<div class="plup_queue '+field+'_list"></div>').insertAfter('#'+field);
				$('#'+field).addClass('plup_button');
			}
		},
		init: {
			UploadProgress: function(up, file) {
				$('#' + file.id + ' b').html(file.percent+'%');
				$('#' + file.id + ' span.percent').css('width', file.percent+'%');
			},
			FilesAdded: function(up, files){
				plupload.each(files, function(file) {
					var el =
						'<div id="'+file.id+'">' +
							'<span class="name">' + file.name + ' (' + plupload.formatSize(file.size) + ')' + '</span><b></b><br />' +
							'<span class="w_percent"><span class="percent"></span></span>'+
						'</div>'
					;
					$('.'+field+'_list').append(el);
				});
				uploader.start();
			},
			Error: function(up, err) {
				jAlert(err.message, "Atenção!");
			}
		}
	}, options));

	return uploader;
}
/**/

$(document).ready(function(){
	$('td.check').click(function(event){
		//selectCheckbox($(this).find('input'), "td", event.shiftKey);
	});
	$('td.check input').click(function(event){
		selectCheckbox(this, "check", event.shiftKey);
	});
	$('input:text').setMask({autoTab:false});
	$(".datepicker").datepicker();

});

$(function(){
	$('form .sidebar a').click(function(){
		var titulo = $(this).html();

		if($('#menu h2 span').length > 0){
			$('#menu h2 span').html(' - '+titulo);
		} else {
			$('#menu h2').append('<span> - '+titulo+'</span>');
		}
	});

	$('form .sidebar li.hover a').trigger('click');


});

String.prototype.replaceAll = function(from, to){
	var str = this;
	var pos = str.indexOf(from);
	while (pos > -1){
		str = str.replace(from, to);
		pos = str.indexOf(from);
	}
	return (str);
};

