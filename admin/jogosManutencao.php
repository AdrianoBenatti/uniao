<?php
autorizar("jogos");
$local_files = "../media/jogos/";
$temp_files = "../media/temp/";

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
			$image = WideImage::load($tempfile);
			$image = $image->resize(900, null, 'outside', 'down')->saveToFile($newFile);
			if ($nome_imagem!=$nome_imagem_bd && !empty($nome_imagem_bd)){
				$file = $local_files . $nome_imagem_bd;
				if (file_exists($file)) unlink($file);
			}
			unlink($tempfile);
		}

	}else{
		$nome_imagem = $nome_imagem_bd;
	}

	if (!empty($nome_imagem2)){
		$nome_imagem2 = utf8_encode($nome_imagem2);
		$tempfile2 = $temp_files . $nome_imagem2;
		$nome_imagem2 = $id."_".retirar_caracteres_especiais($nome_imagem2);
		if (file_exists($tempfile2)){
			$newFile = $local_files . $nome_imagem2;
			$image = WideImage::load($tempfile2);
			$image = $image->resize(900, null, 'outside', 'down')->saveToFile($newFile);
			if ($nome_imagem2!=$nome_imagem2_bd && !empty($nome_imagem2_bd)){
				$file = $local_files . $nome_imagem2_bd;
				if (file_exists($file)) unlink($file);
			}
			unlink($tempfile2);
		}
	}else{
		$nome_imagem2 = $nome_imagem2_bd;
	}



	$data = array(
		'img_prox' => $nome_imagem,
		'img_ant' => $nome_imagem2,
		'txt_ant' => utf8_encode($txt_ant),
		'txt_prox' => utf8_encode($txt_prox),
		'data_prox' => $data_prox,
		'data_ant' => $data_ant
	);


	if($acao == "Incluir") $id = null;

	$id = save($data, "jogos", $id);


	if(!$continue) header("Location: {$url}jogos"); else header("Location: {$url}$page/Alterar/$id");

}else{
	if ($acao == "Alterar") {
		$sql = "SELECT * FROM jogos WHERE id = $id";
		$query = mysql_query($sql, $connect);
		if ($dados = mysql_fetch_array($query)){
			foreach($dados as $key=>$value) {
				$$key = utf8_encode($value);
			}
		}
	}else{
		$id = proxID("jogos");
	}
}
?>
<script>
	$(document).ready(function(){
		$("#form1").validate({
			submitHandler:function(form){
				form.submit();
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
			rules: {
				nome: "required",
				imagem: "required"
			},
			messages: {
				nome: "Preencha o nome do patrocinador!",
				imagem: "Selecione a imagem!"
			}
		});
		flutua();
		tabear('#tabs');
		initSaves();

		uploader = buildPlupload('imagem', {
			multi_selection: false,
			filters : {
				max_file_size : '100MB',
				mime_types: [
					{title : "Image files", extensions : "jpg,gif,png,jpeg"}
				]
			},
			init: {
				FileUploaded: function(up, file, info) {
					try{
						json = JSON.parse(info.response);
						$('#upload.imagem .thumb').remove();
						$('#upload.imagem').append(
							'<div class="thumb">'+
							'<img src="<?php echo $url . $mediaUrl ?>temp/'+json.fileName+'" height="100" />'+
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


		uploader = buildPlupload('imagem2', {
			multi_selection: false,
			filters : {
				max_file_size : '100MB',
				mime_types: [
					{title : "Image files", extensions : "jpg,gif,png,jpeg"}
				]
			},
			init: {
				FileUploaded: function(up, file, info) {
					try{
						json = JSON.parse(info.response);
						$('#upload2.imagem2 .thumb2').remove();
						$('#upload2.imagem2').append(
							'<div class="thumb2">'+
							'<img src="<?php echo $url . $mediaUrl ?>temp/'+json.fileName+'" height="100" />'+
							'</div>'
						);
						$('#' + file.id).fadeOut();
						$("#nome_imagem2").val(json.fileName);
					} catch (e) {
						jAlert('Erro ao realizar upload, tente novamente', 'Atenção');
						console.log(e);
					}
				}
			}
		}).init();

	});
</script>
<div class="center">
	<div id="tabs">
		<?php if ($acao == "Alterar" && !$dados['id']): ?>
			<div class="note-msg">Registro foi excluído ou não foi encontrado.</div>
		<?php else: ?>
			<form name="form1" enctype="multipart/form-data" id="form1" method="post" action="<?php echo $url; ?>jogosManutencao/<?php echo $acao ?>/<?php echo $id ?>" onsubmit="lockButton(this);">
				<input type="hidden" name="acao" value="<?php echo $acao ?>" id="acao"/>
				<input type="hidden" name="id[]" value="<?php echo $id ?>" id="id[]" />
				<div class="sidebar left">
					<h2>Jogos E.C União Santa Luiza &gt; <?php echo $acao; ?></h2>
					<ul>
						<li id="atabs-1" class="hover"><a href="#tabs-1" onClick="CngClass(this);">Geral</a></li>
					</ul>
				</div>
				<div class="contentManutencao right">
					<div class="t-prod" id="menu">
						<h2>Jogos E.C União Santa Luiza &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>jogos'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'jogos', '<?php echo $url; ?>jogos', true);" /><?php endif; ?>
						<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" />
						<input name="gravar" type="button" id="gravar" value="Salvar e Continuar Editando" class="btn-padrao pedidos" onclick="saveContinue();" />
						<input name="carregando" type="button" id="carregando" value="Carregando..." class="gravar btn-padrao" disabled="disabled" />
					</div>
					<div class="t-prod HighIndex" id="navContainer">
						<h2>Jogos E.C União Santa Luiza &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>jogos'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'jogos', '<?php echo $url; ?>jogos', true);" /><?php endif; ?>
						<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" />
						<input name="gravar" type="button" id="gravar" value="Salvar e Continuar Editando" class="btn-padrao pedidos" onclick="saveContinue();" />
						<input type="hidden" name="continue" value="0" id="continue"/>
						<input name="carregando" type="button" id="carregando" value="Carregando..." class="gravar btn-padrao" disabled="disabled" />
					</div>
					<div class="erro"><strong></strong><img src="<?php echo $url ?>images/ic-alert.gif"></div>
					<div class="indent" id="tabs-1">
						<div class="titulo">
							<h2>Jogo Anterior</h2>
						</div>
						<div class="formulario" id="formulario">
							<fieldset>
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="180">
											<label>ID:</label>
											<label for="date" style="margin: 0 0 95px;">Data: <span class="req"></span></label>
											<label for="descricao">Descrição: <span class="req">*</span></label>
											<label for="imagem">Imagem Jogo Anterior: <span class="req">*</span></label>
										</td>

										<td>
											<input type="text" name="id" id="id" value="<?php echo $id ?>" readonly="readonly" />
											<div class="clear"></div>
											<input type="date" name="data_ant" id="data_ant" value="<?php echo $data_ant ?>" />
											<div class="clear"></div>
											<textarea name="txt_ant" id="txt_ant"><?php echo  utf8_decode($txt_ant) ?></textarea>


											<div id="upload2" class="single imagem2">
												<a href="javascript:;" id="imagem2" class="plup_button">SELECIONAR IMAGEM</a>
												<div class="clear"></div>
												<div class="thumb2">
													<?php if($img_ant): ?>
														<img src="<?php echo $url."../media/jogos/".$img_ant; ?>" height="100" />
													<?php endif; ?>
												</div>

											</div>
											<input type="hidden" name="nome_imagem2" id="nome_imagem2" value="" />
											<input type="hidden" name="nome_imagem2_bd" id="nome_imagem2_bd" value="<?php echo $img_ant ?>" />
											<div class="clear"></div>

										</td>
										</td>
									</tr>
								</table>
								<div class="titulo">
									<h2>Próximo Jogo</h2>
								</div>
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="180">
											<label>ID:</label>
											<label for="date" style="margin: 0 0 95px;">Data: <span class="req"></span></label>
											<label for="descricao">Descrição: <span class="req">*</span></label>
											<label for="imagem">Imagem Próximo Jogo: <span class="req">*</span></label>
										</td>

										<td>
											<input type="text" name="id" id="id" value="<?php echo $id ?>" readonly="readonly" />
											<div class="clear"></div>
											<input type="date" name="data_prox" id="data_prox" value="<?php echo $data_prox ?>" />
											<div class="clear"></div>
											<textarea name="txt_prox" id="txt_prox"><?php echo utf8_decode($txt_prox) ?></textarea>


											<div id="upload" class="single imagem">
												<a href="javascript:;" id="imagem" class="plup_button">SELECIONAR IMAGEM</a>
												<div class="clear"></div>
												<div class="thumb">
													<?php if($img_prox): ?>
														<img src="<?php echo $url."../media/jogos/".$img_prox; ?>" height="100" />
													<?php endif; ?>
												</div>
											</div>
											<input type="hidden" name="nome_imagem" id="nome_imagem" value="" />
											<input type="hidden" name="nome_imagem_bd" id="nome_imagem_bd" value="<?php echo $img_prox ?>" />
											<div class="clear"></div>

										</td>
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

