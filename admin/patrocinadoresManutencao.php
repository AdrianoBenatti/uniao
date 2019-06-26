<?php
autorizar("patrocinadores");
$local_files = "../media/patrocinadores/";
$temp_files = "../media/temp/";

if ($_POST) {
	foreach ($_POST as $k => $v ) {
		if(!is_array($v)) $$k = utf8_decode(removeWordChars($v));
	}
	$id = $_GET['id'];
	$dataAtual = date('Y-m-d');
	$extensao = explode('.', $nome_imagem);

	if (!empty($nome_imagem)){
		if ($extensao[1] == 'pdf') {
			$nome_imagem = utf8_encode($nome_imagem);
			$tempfile = $temp_files . $nome_imagem;
			$nome_imagem = $id."_".retirar_caracteres_especiais($nome_imagem);
			if (file_exists($tempfile)){
				$newFile = $local_files . $nome_imagem;
				copy($tempfile, $newFile);
				unlink($tempfile);
			}

		} else {
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
		}
	}else{
		$nome_imagem = $nome_imagem_bd;
	}

	if (!empty($nome_imagem2)){
		$nome_imagem2 = utf8_encode($nome_imagem2);
		$tempfile = $temp_files . $nome_imagem2;
		$nome_imagem2 = $id."_".retirar_caracteres_especiais($nome_imagem2);
		if (file_exists($tempfile)){
			$newFile = $local_files . $nome_imagem2;
			$image = WideImage::load($tempfile);
			$image = $image->resize(900, null, 'outside', 'down')->saveToFile($newFile);
			if ($nome_imagem2!=$nome_imagem2_bd && !empty($nome_imagem2_bd)){
				$file = $local_files . $nome_imagem2_bd;
				if (file_exists($file)) unlink($file);
			}
			unlink($tempfile);
		}
	}else{
		$nome_imagem2 = $nome_imagem2_bd;
	}



	$data = array(
		"nome" => $nome,
		"imagem1" => $nome_imagem,
		"imagem2" => $nome_imagem2
	);

	if($acao == "Incluir") $id = null;

	$id = save($data, "patrocinadores", $id);


	if(!$continue) header("Location: {$url}patrocinadores"); else header("Location: {$url}$page/Alterar/$id");

}else{
	if ($acao == "Alterar") {
		$sql = "SELECT * FROM patrocinadores WHERE id = $id";
		$query = mysql_query($sql, $connect);
		if ($dados = mysql_fetch_array($query)){
			foreach($dados as $key=>$value) {
				$$key = utf8_encode($value);
			}
		}
	}else{
		$id = proxID("patrocinadores");
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
			},
			messages: {
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
					{title : "Image files", extensions : "jpg,gif,png"}
				]
			},
			init: {
				FileUploaded: function(up, file, info) {
					try{
						json = JSON.parse(info.response);
						$('#upload.imagem .thumb').remove();
						$('#upload.imagem').append(
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


		uploader = buildPlupload('imagem2', {
			multi_selection: false,
			filters : {
				max_file_size : '100MB',
				mime_types: [
					{title : "Image files", extensions : "jpg,gif,png"}
				]
			},
			init: {
				FileUploaded: function(up, file, info) {
					try{
						json = JSON.parse(info.response);
						$('#upload.imagem2 .thumb').remove();
						$('#upload.imagem2').append(
							'<div class="thumb">'+
							'<img src="<?php echo $mediaUrl ?>temp/'+json.fileName+'" height="100" />'+
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
			<form name="form1" enctype="multipart/form-data" id="form1" method="post" action="<?php echo $url; ?>patrocinadoresManutencao/<?php echo $acao ?>/<?php echo $id ?>" onsubmit="lockButton(this);">
				<input type="hidden" name="acao" value="<?php echo $acao ?>" id="acao"/>
				<input type="hidden" name="id[]" value="<?php echo $id ?>" id="id[]" />
				<div class="sidebar left">
					<h2>Patrocinadores E.C União Santa Luiza &gt; <?php echo $acao; ?></h2>
					<ul>
						<li id="atabs-1" class="hover"><a href="#tabs-1" onClick="CngClass(this);">Geral</a></li>
					</ul>
				</div>
				<div class="contentManutencao right">
					<div class="t-prod" id="menu">
						<h2>Patrocinadores E.C União Santa Luiza &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>patrocinadores'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'patrocinadores', '<?php echo $url; ?>patrocinadores', true);" /><?php endif; ?>
						<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" />
						<input name="gravar" type="button" id="gravar" value="Salvar e Continuar Editando" class="btn-padrao pedidos" onclick="saveContinue();" />
						<input name="carregando" type="button" id="carregando" value="Carregando..." class="gravar btn-padrao" disabled="disabled" />
					</div>
					<div class="t-prod HighIndex" id="navContainer">
						<h2>Campanhas Pharma Special &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>patrocinadores'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'patrocinadores', '<?php echo $url; ?>patrocinadores', true);" /><?php endif; ?>
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
											<label for="nome">nome: <span class="req">*</span></label>
											<label for="imagem" style="margin: 0 0 95px;">Imagem: <span class="req"></span></label>
											<label for="imagem2" class="upl">Imagem: <span class="req"></span></label>
										</td>

										<td>
											<input type="text" name="id" id="id" value="<?php echo $id ?>" readonly="readonly" />
											<div class="clear"></div>
											<input type="text" name="nome" id="nome" value="<?php echo $nome ?>" />
											<div class="clear"></div>

											<div id="upload" class="single imagem">
												<a href="javascript:;" id="imagem" class="plup_button">SELECIONAR IMAGEM</a>
												<div class="clear"></div>
												<div class="thumb">
													<?php if (!empty($imagem)):
														$ext_imagem = explode('.', $imagem);
														if($ext_imagem[1] != 'pdf'):?>
															<img src="<?php echo $url."../media/patrocinadores/thumb-".$imagem; ?>" height="100" />
														<?php else: ?>
															<p style="margin-left: 10px;"> <?php echo $imagem; ?>  </p>
														<?php endif; ?>
													<?php endif; ?>
												</div>
											</div>
											<input type="hidden" name="nome_imagem" id="nome_imagem" value="" />
											<input type="hidden" name="nome_imagem_bd" id="nome_imagem_bd" value="<?php echo $imagem; ?>" />
											<div class="clear"></div>

											<div id="upload" class="single imagem2">
												<a href="javascript:;" id="imagem2" class="plup_button">SELECIONAR IMAGEM</a>
												<div class="clear"></div>
												<div class="thumb">
													<?php if($imagem2): ?>
														<img src="<?php echo $url."../media/patrocinadores/thumb-".$imagem2; ?>" height="100" />
													<?php endif; ?>
												</div>
											</div>


											<input type="hidden" name="nome_imagem2" id="nome_imagem2" value="" />
											<input type="hidden" name="nome_imagem2_bd" id="nome_imagem2_bd" value="<?php echo $imagem2 ;?>" />
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