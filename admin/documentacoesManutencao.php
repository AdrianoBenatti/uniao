<?php
autorizar("documentacoes");
$ativo = 1;
$local_files = "../media/documentacoes/";
if ($_POST) {
	foreach ($_POST as $k => $v ) {
		if(!is_array($v)) $$k = utf8_decode(removeWordChars($v));
	}
	$id = $_GET['id'];

	if (!empty($nome_arquivo)){
		$nome_arquivo = utf8_encode($nome_arquivo);
		$tempfile = $temp_files . $nome_arquivo;
		$nome_arquivo = $id."_".retirar_caracteres_especiais($nome_arquivo);
		if (file_exists($tempfile)){
			$newFile = $local_files . $nome_arquivo;
			copy($tempfile, $newFile);
			if ($nome_arquivo!=$nome_arquivo_bd && !empty($nome_arquivo_bd)){
				$doc = $local_files . $nome_arquivo_bd;
				if (file_exists($doc)) unlink($doc);
			}
			unlink($tempfile);
		}
		$nome_arquivo = $nome_arquivo;
	}else{
	   $nome_arquivo = $nome_arquivo_bd;
	}

	if($acao == "Incluir") $id = null;

	$data = array(
		"nome_documento"=> $nome_documento,
		"documento"		=> $nome_arquivo,
		"ativo"			=> $ativo,
	);


	$id = save($data, "documentacoes", $id);

	if(!$continue) header("Location: {$url}documentacoes"); else header("Location: {$url}$page/Alterar/$id");
}else{
	if ($acao == "Alterar") {
		$sql = "SELECT * FROM documentacoes WHERE id = $id";
		$query = mysql_query($sql, $connect);
		if ($dados = mysql_fetch_array($query)){
			foreach($dados as $key=>$value) {
				 $$key = utf8_encode($value);
			}
		}
	}else{
		$id = proxID("documentacoes");
	}
}
?>
<script>
$(document).ready(function(){
	$("#form1").validate({
		submitHandler:function(form){
			form.submit();
			lockButton(form);
		},
		errorElement: "div",
        wrapper: "div",
        errorPlacement: function(error, element) {
            error.appendTo('.erro');
            error.addClass('errorMessage');
        },
        showErrors: function(errorMap, errorList) {
			if (this.numberOfInvalids()==0) {$('.error-msg, .erro-server').remove(); $('.erro').hide();}
			else{$('.erro').show();}
			this.defaultShowErrors();
		},
		rules:{
			nome_documento:"required"<?php if($acao=="Incluir"): ?>,
			nome_arquivo:"required"<?php endif; ?>
		},
		messages:{
			nome_documento:"Preencha o nome do documento",
			nome_arquivo:"Selecione o documento"
		}
	});
	flutua();
	tabear('#tabs');
	initSaves();
	var totalfilesize = 0;

	uploader = buildPlupload('documento', {
		multi_selection: false,
		filters : {
			max_file_size : '5MB',
			mime_types: [
				{title : "Documentos", extensions : "pdf,doc,docx,xls,xlsx,txt"}
			]
		},
		init: {
			FileUploaded: function(up, file, info) {
				try {
					json = JSON.parse(info.response);
					$('#upload .thumb').remove();
					$('#upload').append(
						'<div class="thumb">'+
							'<p>'+json.fileName+'</p>'+
						'</div>'
					);
					$('#' + file.id).fadeOut();
					$("#nome_arquivo").val(json.fileName);
				} catch (e) {
					jAlert('Erro ao realizar upload, tente novamente', 'Atenção');
					console.log(e);
				}
			}
		}
	}).init();

	/* $('#documento').uploadify({
		'uploader'  : url + 'js/tools/uploadify/uploadify.swf',
		'script' 	: url + 'js/tools/uploadify/uploadify.php',
		'cancelImg'	: url + 'js/tools/uploadify/cancel.png',
		'buttonImg'	: url + 'js/tools/uploadify/button.png',
		'scriptData': {'idn':'<?php echo $id; ?>', 'massive':false, 'sid':'<?php echo $sid ?>'},
		'folder'    : '../../../media/temp/',
		'fileSizeLimit' : '5120',
		'multi'     : false,
		'auto'      : false,
		'fileExt'   : '*.pdf;*.doc;*.docx;*.xls;*.xlsx;*.txt;',
		'fileDesc'  : 'Documentos',
		'onAllComplete': function(event,data,fileObj) {
			document.getElementById('form1').submit();
		},
		'onSelect'  : function(event,ID,fileObj) {
			totalfilesize = fileObj.size;
			var verifica = chkfilesize(totalfilesize);
			$("#nome_arquivo").val(1);
		},
		'onCancel'	: function(){
			$("#nome_arquivo").val('');
		},
		'onComplete': function(event, ID, fileObj, filename){
			$("#nome_arquivo").val(filename);
		}
	}); */

	function chkfilesize(totalfilesize){
		if(totalfilesize > 5242880){
	        alert('O tamanho máximo do documento deve ser de 5MB');
	        var mensagem = "Por favor, selecione um documento de até 5MB";
	        return false;
	    }else{
	        return true;
	    }
	}

});
</script>
<div class="center">
	<div id="tabs">
		<?php if ($acao == "Alterar" && !$dados['id']): ?>
		<div class="note-msg">Registro foi excluído ou não foi encontrado.</div>
		<?php else: ?>
			<form name="form1" id="form1" method="post" action="<?php echo $url; ?>documentacoesManutencao/<?php echo $acao ?>/<?php echo $id ?>" onsubmit="lockButton(this);">
				<input type="hidden" name="acao" value="<?php echo $acao ?>" id="acao"/>
				<input type="hidden" name="id[]" value="<?php echo $id ?>" id="id[]" />
				<div class="sidebar left">
					<h2>Documentações Pharma Special &gt; <?php echo $acao; ?></h2>
					<ul>
						<li id="atabs-1" class="hover"><a href="#tabs-1" onClick="CngClass(this);">Geral</a></li>
					</ul>
				</div>
				<div class="contentManutencao right">
					<div class="t-prod" id="menu">
						<h2>Documentações Pharma Special &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>documentacoes'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<input name="reiniciar" type="button" id="reiniciar" value="Reiniciar" class="btn-padrao" onclick="location.href=''" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'documentacoes', '<?php echo $url; ?>documentacoes', true);" /><?php endif; ?>
						<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" onclick="" />
						<input name="gravar" type="button" id="gravar" value="Salvar e Continuar Editando" class="btn-padrao pedidos" onclick="saveContinue();" />
						<input name="carregando" type="button" id="carregando" value="Carregando..." class="gravar btn-padrao" disabled="disabled" />
					</div>
					<div class="t-prod HighIndex" id="navContainer">
						<h2>Documentações Pharma Special &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>documentacoes'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<input name="reiniciar" type="button" id="reiniciar" value="Reiniciar" class="btn-padrao" onclick="location.href=''" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'documentacoes', '<?php echo $url; ?>documentacoes', true);" /><?php endif; ?>
						<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" />
						<input name="gravar" type="button" id="gravar" value="Salvar e Continuar Editando" class="btn-padrao pedidos" onclick="saveContinue();" />
						<input type="hidden" name="continue" value="0" id="continue"/>
						<input name="carregando" type="button" id="carregando" value="Carregando..." class="gravar btn-padrao" disabled="disabled" />
					</div>
					<div class="erro"><strong></strong><img src="<?php echo $url ?>images/ic-alert.gif"></div>
					<div class="indent" id="tabs-1">
						<div class="titulo">
							<h2>GERAL</h2>
						</div>
						<div class="formulario" id="formulario">
							<fieldset>
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="180">
											<label>ID:</label>
											<label for="nome_documento">Nome do Documento</label>
											<label for="ativo">Ativo</label>
											<label for="documento" class="upl">Documento</label>
										</td>
										<td>
											<label><?php echo $id ?></label>
											<input type="text" name="nome_documento" id="nome_documento" maxlength="110" value="<?php echo $nome_documento ?>" />
											<div class="clear"></div>
											<select name="ativo" class="small2">
												<option <?php if ($ativo == 0) echo 'selected="selected" ' ?> value="0">Não</option>
												<option <?php if ($ativo == 1) echo 'selected="selected" ' ?> value="1">Sim</option>
											</select>
											<div class="clear"></div>

											<div id="upload" class="single">
												<a href="javascript:;" id="documento" class="plup_button">SELECIONAR IMAGEM</a>
												<div class="clear"></div>
												<div class="thumb">
													<?php if($documento): ?>
															<p><?php echo $documento ?></p>
													<?php endif; ?>
												</div>
											</div>

											<!-- <div id="uploadify" class="single"><input type="file" name="documento" id="documento" /></div>
											<?php if($documento): ?>
												<a href="<?php echo $url.$local_files.$documento ?>" target="_blank" class="doc_name" style="margin-left:10px; margin-top:8px; float:left;"><?php echo $documento ?></a>
											<?php endif; ?> -->

											<input type="hidden" name="nome_arquivo" id="nome_arquivo" value="" />
			 								<input type="hidden" name="nome_arquivo_bd" id="nome_arquivo_bd" value="<?php echo $documento ?>" />
											<div class="clear"></div>
										</td>
									</tr>
								</table>
							</fieldset>
						</div>
					</div>
				</div>
			</form>
		<?php endif; ?>
	</div>
</div>