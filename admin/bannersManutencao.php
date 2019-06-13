<?php
autorizar("banners_pharma");
$ativo = 1;
$local_files = "../media/banners/";
if ($_POST) {

	foreach ($_POST as $k => $v ) {
		if(!is_array($v)) $$k = utf8_decode(removeWordChars($v));
	}
	$id = $_GET['id'];

	if (!empty($nome_imagem)){
			$nome_imagem = utf8_encode($nome_imagem);
			$tempfile = $temp_files . $nome_imagem;
			$nome_imagem = $id."_".retirar_caracteres_especiais($nome_imagem);
			if (file_exists($tempfile)){
				$newFile = $local_files . $nome_imagem;
				$newFileT = $local_files . "thmb-".$nome_imagem;

				WideImage::load($tempfile)
					->resize(1920, 402, 'outside', 'down')
					->crop("center", "middle", 1920, 402)
					->saveToFile($newFile)
				;
				WideImage::load($tempfile)
					->resize(null, 100, 'outside')
					->saveToFile($newFileT)
				;

				if ($nome_imagem!=$nome_imagem_bd && !empty($nome_imagem_bd)){
					$imagem = $local_files . $nome_imagem_bd;
					if (file_exists($imagem)) unlink($imagem);
					$imagem = $local_files . "thmb-".$nome_imagem_bd;
					if (file_exists($imagem)) unlink($imagem);
				}
				unlink($tempfile);
			}
			$nome_imagem = $nome_imagem;
	}else{
	   $nome_imagem = $nome_imagem_bd;
	}

	if (!empty($nome_imagem_mobile)){
		$nome_imagem_mobile = utf8_encode($nome_imagem_mobile);
		$tempfile = $temp_files . $nome_imagem_mobile;
		$nome_imagem_mobile = $id."_".retirar_caracteres_especiais($nome_imagem_mobile);
		if (file_exists($tempfile)){
			$newFile = $local_files . $nome_imagem_mobile;
			$newFileT = $local_files . "thmb-".$nome_imagem_mobile;

			WideImage::load($tempfile)
				->resize(1920, 402, 'outside', 'down')
				->crop("center", "middle", 1920, 402)
				->saveToFile($newFile)
			;
			WideImage::load($tempfile)
				->resize(null, 100, 'outside')
				->saveToFile($newFileT)
			;

			if ($nome_imagem_mobile!=$nome_imagem_mobile_bd && !empty($nome_imagem_mobile_bd)){
				$imagem_mobile = $local_files . $nome_imagem_mobile_bd;
				if (file_exists($imagem_mobile)) unlink($imagem_mobile);
				$imagem_mobile = $local_files . "thmb-".$nome_imagem_mobile_bd;
				if (file_exists($imagem_mobile)) unlink($imagem_mobile);
			}
			unlink($tempfile);
		}
	}else{
		$nome_imagem_mobile = $nome_imagem_mobile_bd;
	}

	if (!empty($nome_pdf)){
		$nome_pdf = utf8_encode($nome_pdf);
		$tempfile = $temp_files . $nome_pdf;
		$nome_pdf = $id."_".retirar_caracteres_especiais($nome_pdf);
		if (file_exists($tempfile)){
			$newFile = $local_files . $nome_pdf;

			rename($tempfile, $newFile);

			if ($nome_pdf!=$nome_pdf_bd && !empty($nome_pdf_bd)){
				$pdf = $local_files . $nome_pdf_bd;
				if (file_exists($pdf)) unlink($pdf);
				$pdf = $local_files . "thmb-".$nome_pdf_bd;
				if (file_exists($pdf)) unlink($pdf);
			}
		}
	}else{
		$nome_pdf = $nome_pdf_bd;
	}

	if($acao == "Incluir") $id = null;

	$data = array(
		"imagem" 	 	=> $nome_imagem,
		"imagem_mobile" => $nome_imagem_mobile,
		"ativo"		 	=> $ativo,
		"link"		 	=> $link,
		"acao_click" 	=> $acao_click,
		"pdf"		 	=> $nome_pdf,
	);
/*
	$data = array(
		"data_inicio" => formatDate($data_inicio,"Y-m-d"),
		"data_fim" => formatDate($data_fim,"Y-m-d"),
		"imagem" 	=> $nome_imagem,
		"ativo"		=> $ativo,

	);
*/
	$id = save($data, "banners_pharma", $id);

	if(!$continue) header("Location: {$url}bannersPharma"); else header("Location: {$url}$page/Alterar/$id");
}else{
	if ($acao == "Alterar") {
		$sql = "SELECT * FROM banners_pharma WHERE id = $id";
		$query = mysql_query($sql, $connect);
		if ($dados = mysql_fetch_array($query)){
			foreach($dados as $key=>$value) {
				 $$key = utf8_encode($value);
			}
		}
	}else{
		$id = proxID("banners_pharma");
	}
}
?>
<script>
$(document).ready(function(){
	var validate = $("#form1").validate({
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
			link:{
				url: true,
			},
			titulo:"required"<?php if($acao=="Incluir"): ?>,
			nome_imagem:"required",
			nome_imagem_mobile:"required",
			<?php endif; ?>
		},
		messages:{
			link:{
				url: "Digite uma url válida. Ex: http://www.google.com/",
			},
			titulo:"Preencha o título",
			nome_imagem:"Selecione o banner",
			nome_imagem_mobile: "Selecione o banner mobile"
		}
	});
	flutua();
	tabear('#tabs');
	initSaves();
	var totalfilesize = 0;

	$('#acao_click').change(function () {

		if ($(this).val() === "link") {
			$('.pdf').hide();
			$('.link').show();

			$('#link').rules('add', {
				url: true,
				messages: {
					url: "Digite uma url válida. Ex: http://www.google.com/",
				}
			});

		} else {
			$('.link').hide();
			$('#link').rules('remove');
			$('.erro div[for="link"]').remove();

			if($(this).val() === "pdf")
				$('.pdf').show();
			else
				$('.pdf').hide();
		}
	});

	uploader = buildPlupload('imagem', {
		multi_selection: false,
		filters : {
			max_file_size : '100MB',
			mime_types: [
				{title : "Image files", extensions : "jpg,gif,png"}
			]
		},
		init: {
			FileUploaded: function(up, file, info) {
				try {
					json = JSON.parse(info.response);
					$('#upload .thumb').remove();
					$('#upload').append(
						'<div class="thumb">'+
							'<img src="<?php echo $mediaUrl ?>temp/'+json.fileName+'" height="100" />'+
						'</div>'
					);
					$('#' + file.id).fadeOut();
					$("#nome_imagem").val(json.fileName);
				} catch (e) {
					jAlert('Erro ao realizar upload, tente novamente', 'Atenção');
					console.log(e);
				}
			}
		}
	}).init();

	uploader_mobile = buildPlupload('imagem_mobile', {
		multi_selection: false,
		filters : {
			max_file_size : '100MB',
			mime_types: [
				{title : "Image files", extensions : "jpg,gif,png"}
			]
		},
		init: {
			FileUploaded: function(up, file, info) {
				try {
					json = JSON.parse(info.response);
					$('#upload_mobile .thumb').remove();
					$('#upload_mobile').append(
						'<div class="thumb">'+
							'<img src="<?php echo $mediaUrl ?>temp/'+json.fileName+'" height="100" />'+
						'</div>'
					);
					$('#' + file.id).fadeOut();
					$("#nome_imagem_mobile").val(json.fileName);
				} catch (e) {
					jAlert('Erro ao realizar upload, tente novamente', 'Atenção');
					console.log(e);
				}
			}
		}
	}).init();

	uploader_pdf = buildPlupload('arquivo_pdf', {
		multi_selection: false,
		filters : {
			max_file_size : '100MB',
			mime_types: [
				{title : "Files", extensions : "pdf"}
			]
		},
		init: {
			FileUploaded: function(up, file, info) {
				try {
					json = JSON.parse(info.response);
					$('#upload_pdf .thumb').remove();
					$('#upload_pdf').append(
						'<div class="thumb">'+
						'<a target="_blank" href="<?php echo $mediaUrl ?>temp/'+json.fileName+'">'+json.fileName+'</a>'+
						'</div>'
					);
					$('#' + file.id).fadeOut();
					$("#nome_pdf").val(json.fileName);
				} catch (e) {
					jAlert('Erro ao realizar upload, tente novamente', 'Atenção');
					console.log(e);
				}
			}
		}
	}).init();

	function chkfilesize(totalfilesize){
		if(totalfilesize > 5242880){
	        alert('O tamanho máximo da imagem deve ser de 5MB');
	        var mensagem = "Por favor, selecione uma imagem de até 5MB";
	        return false;
	    }else{
	        return true;
	    }
	}

	$("#data_inicio").datepicker();
	$("#data_fim").datepicker();

});
</script>
<div class="center">
	<div id="tabs">
		<?php if ($acao == "Alterar" && !$dados['id']): ?>
		<div class="note-msg">Registro foi excluído ou não foi encontrado.</div>
		<?php else: ?>
			<form name="form1" id="form1" method="post" action="<?php echo $url; ?>bannersPharmaManutencao/<?php echo $acao ?>/<?php echo $id ?>" onsubmit="lockButton(this);">
				<input type="hidden" name="acao" value="<?php echo $acao ?>" id="acao"/>
				<input type="hidden" name="id[]" value="<?php echo $id ?>" id="id[]" />
				<div class="sidebar left">
					<h2>Banners Pharma Special &gt; <?php echo $acao; ?></h2>
					<ul>
						<li id="atabs-1" class="hover"><a href="#tabs-1" onClick="CngClass(this);">Geral</a></li>
					</ul>
				</div>
				<div class="contentManutencao right">
					<div class="t-prod" id="menu">
						<h2>Banners Pharma Special &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>bannersPharma'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<input name="reiniciar" type="button" id="reiniciar" value="Reiniciar" class="btn-padrao" onclick="location.href=''" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'banners_pharma', '<?php echo $url; ?>bannersPharma', true);" /><?php endif; ?>
						<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" onclick="" />
						<input name="gravar" type="button" id="gravar" value="Salvar e Continuar Editando" class="btn-padrao pedidos" onclick="saveContinue();" />
						<input name="carregando" type="button" id="carregando" value="Carregando..." class="gravar btn-padrao" disabled="disabled" />
					</div>
					<div class="t-prod HighIndex" id="navContainer">
						<h2>Banners Pharma Special &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>bannersPharma'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<input name="reiniciar" type="button" id="reiniciar" value="Reiniciar" class="btn-padrao" onclick="location.href=''" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'banners_pharma', '<?php echo $url; ?>bannersPharma', true);" /><?php endif; ?>
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
											<label for="ativo">Ativo:</label>
											<label for="acao_click">Ação do Click:</label>
											<label class="link" for="link" style="<?php echo $acao_click == 'pdf' || !$acao_click ? "display:none;" : ''; ?>">Link:</label>
											<label class="pdf upl" for="pdf" style="<?php echo $acao_click == 'link' || !$acao_click ? "display:none;" : ''; ?>">PDF:</label>
											<label for="banner" class="upl">Banner:</label>
											<label for="banner_mobile" class="upl">Banner Mobile:</label>
										</td>
										<td>
											<label><?php echo $id ?></label>
											<select id="ativo" name="ativo" class="small2">
												<option <?php if ($ativo == 0) echo 'selected="selected" ' ?> value="0">Não</option>
												<option <?php if ($ativo == 1) echo 'selected="selected" ' ?> value="1">Sim</option>
											</select>

											<div class="clear"></div>
											<select id="acao_click" name="acao_click">
												<option value="">Nenhuma ação</option>
												<option <?php echo $acao_click == 'link' ? "selected" : ''; ?> value="link">Acessar página na web</option>
												<option <?php echo $acao_click == 'pdf' ? "selected" : ''; ?> value="pdf">Baixar PDF</option>
											</select>

											<div class="clear"></div>
											<div class="link" style="<?php echo $acao_click == 'pdf' || !$acao_click ? "display:none;" : ''; ?>">
												<input type="text" name="link" id="link" maxlength="100" value="<?php echo $link ?>" />
											</div>

											<div class="clear"></div>
											<div class="pdf" style="<?php echo $acao_click == 'link' || !$acao_click ? "display:none;" : ''; ?>">
												<div id="upload_pdf" class="single">
													<a href="javascript:;" id="arquivo_pdf" class="plup_button">SELECIONAR ARQUIVO</a>
													<div class="clear"></div>
													<div class="thumb">
														<?php if($pdf): ?>
															<a href="<?php echo $url."../media/banners/".$pdf; ?>" target="_blank"><?php echo $pdf ?></a>
														<?php endif; ?>
													</div>
												</div>
												<input type="hidden" name="nome_pdf" id="nome_pdf" value="" />
												<input type="hidden" name="nome_pdf_bd" id="nome_pdf_bd" value="<?php echo $pdf ?>" />
											</div>


											<div class="clear"></div>
											<div id="upload" class="single">
												<a href="javascript:;" id="imagem" class="plup_button">SELECIONAR IMAGEM</a>
												<small style="margin-left: 10px;">Tamanho recomendado: 1920x402px</small>
												<div class="clear"></div>
												<div class="thumb">
													<?php if($imagem): ?>
															<img src="<?php echo $url."../media/banners/thmb-".$imagem; ?>" height="100" />
													<?php endif; ?>
												</div>
											</div>
											<input type="hidden" name="nome_imagem" id="nome_imagem" value="" />
			 								<input type="hidden" name="nome_imagem_bd" id="nome_imagem_bd" value="<?php echo $imagem ?>" />

											<div class="clear"></div>
											<div id="upload_mobile" class="single">
												<a href="javascript:;" id="imagem_mobile" class="plup_button">SELECIONAR IMAGEM</a>
												<small style="margin-left: 10px;">Tamanho recomendado: 700x402px</small>
												<div class="clear"></div>
												<div class="thumb">
													<?php if($imagem_mobile): ?>
														<img src="<?php echo $url."../media/banners/thmb-".$imagem_mobile; ?>" height="100" />
													<?php endif; ?>
												</div>
											</div>
											<input type="hidden" name="nome_imagem_mobile" id="nome_imagem_mobile" value="" />
			 								<input type="hidden" name="nome_imagem_mobile_bd" id="nome_imagem_mobile_bd" value="<?php echo $imagem_mobile ?>" />
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