<?php
autorizar("galerias");
$local_files = "../media/galerias/";
$ativo = 1;
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
				$image = WideImage::load($tempfile);
				$image = $image->resize(140, 140, 'outside')->crop('center', 'middle', 140, 140)->saveToFile($newFile);
				if ($nome_imagem!=$nome_imagem_bd && !empty($nome_imagem_bd)){
					$imagem = $local_files . $nome_imagem_bd;
					if (file_exists($imagem)) unlink($imagem);
				}
				unlink($tempfile);
			}
			$nome_imagem = $nome_imagem;
	}else{
	   $nome_imagem = $nome_imagem_bd;
	}

	$data = array(
		"titulo" => $titulo,
		"data" => formatDate($data, "Y-m-d"),
		"descricao" => $descricao,
		"ativo" => $ativo
	);

	if($acao == "Incluir") $id = null;

	$id = save($data, "galerias", $id);

	if(!file_exists($local_files.$id)) mkdir($local_files.$id);

	if(!$continue) header("Location: {$url}galerias"); else header("Location: {$url}$page/Alterar/$id");
}else{
	if ($acao == "Alterar") {
		$sql = "SELECT * FROM galerias WHERE id = $id";
		$query = mysql_query($sql, $connect);
		if ($dados = mysql_fetch_array($query)){
			foreach($dados as $key=>$value) {
				 $$key = utf8_encode($value);
			}
		}
	}else{
		$id = proxID("galerias");
		$dir = $local_files.$id;
		mysql_query("DELETE FROM galerias_imagens WHERE galerias_id = '$id'");
		if($id != ""){
			if(file_exists($dir)) rrmdir($local_files.$id);
			if(!file_exists($dir)) mkdir($local_files.$id);
		}
	}
}
?>
<script>
$(document).ready(function(){
	$("#form1").validate({
		submitHandler: function(form){
			$('#descricao').html(CKEDITOR.instances.descricao.getData());
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
			data:"required",
			titulo:"required"
			<?php if($acao=="Incluir"): ?>,
			nome_imagem:"required",
			<?php endif; ?>
		},
		messages: {
			data:"Preencha a data",
			titulo:"Preencha o título"<?php if($acao=="Incluir"): ?>,
			nome_imagem:"Selecione a imagem para mobile",
			<?php endif; ?>
		}
	});
	flutua();
	tabear('#tabs');
	initSaves();
	$('#descricao').ckeditor({toolbar:'MyToolbar'});
	//buildUploadify('<?php echo $id; ?>', 'uploadify', 'galeria', '../../../media/temp/', true, 'galeria', 'galerias');

	$("#galeria").uploadify({
		'uploader'  	: url + 'js/tools/uploadify/uploadify.swf',
		'script' 		: url + 'js/tools/uploadify/uploadify.php',
		'cancelImg'		: url + 'js/tools/uploadify/cancel.png',
		'buttonImg'		: url + 'js/tools/uploadify/button.png',
		'scriptData'	: {'idn':'<?php echo $id; ?>', 'massive':true, 'tabela':'galerias', 'sid':'<?php echo $sid ?>'},
		'folder'		: '../../../media/temp/',
		'multi'			: true,
		'auto'			: true,
		'method'		: 'post',
		'fileExt'		: '*.jpg;*.jpeg;*.png;',
		'fileDesc'		: 'Imagens',
		'onAllComplete'	: function() {
			$("#dialog_galerias").dialog('destroy');
			$('.galeria').load(url + 'galeriaFotos.php?id=<?php echo $id; ?>&tabela=galerias');
		}
	});

	$('.galeria').load(url + 'galeriaFotos.php?id=<?php echo $id ?>&tabela=galerias');
	$('#data').datepicker();
	$('.data').datepicker();
});
</script>

<div id="tabs">
	<?php if ($acao == "Alterar" && !$dados['id']): ?>
	<div class="note-msg">Registro foi excluído ou não foi encontrado.</div>
	<?php else: ?>
		<form name="form1" id="form1" method="post" action="<?php echo $url; ?>galeriasManutencao/<?php echo $acao ?>/<?php echo $id ?>" onsubmit="lockButton(this);">
			<input type="hidden" name="acao" value="<?php echo $acao ?>" id="acao"/>
			<input type="hidden" name="id[]" value="<?php echo $id ?>" id="id[]" />
			<div class="sidebar left">
				<h2>Galerias &gt; <?php echo $acao; ?></h2>
				<ul>
					<li id="atabs-1" class="hover"><a href="#tabs-1" onClick="CngClass(this);">Geral</a></li>
					<?php if($acao=="Alterar"): ?>
					<li id="atabs-2"><a href="#tabs-2" onClick="CngClass(this);">Imagens</a></li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="content right">
				<div class="t-prod" id="menu">
					<h2>Galerias &gt; <?php echo $acao; ?></h2>
					<input type="button" onclick="location.href='<?php echo $url; ?>galerias'" class="btn-padrao voltar" id="voltar" value="Voltar" />
					<input name="reiniciar" type="button" id="reiniciar" value="Reiniciar" class="btn-padrao" onclick="location.href=''" />
					<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'galerias', '<?php echo $url; ?>galerias', true);" /><?php endif; ?>
					<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" />
					<input name="gravar" type="button" id="gravar" value="Salvar e Continuar Editando" class="btn-padrao pedidos" onclick="saveContinue();" />
					<input name="carregando" type="button" id="carregando" value="Carregando..." class="gravar btn-padrao" disabled="disabled" />
				</div>
				<div class="t-prod HighIndex" id="navContainer">
					<h2>Galerias &gt; <?php echo $acao; ?></h2>
					<input type="button" onclick="location.href='<?php echo $url; ?>galerias'" class="btn-padrao voltar" id="voltar" value="Voltar" />
					<input name="reiniciar" type="button" id="reiniciar" value="Reiniciar" class="btn-padrao" onclick="location.href=''" />
					<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'galerias', <?php echo $imagem ?>, '<?php echo $url; ?>galerias', true);" /><?php endif; ?>
					<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" />
					<input name="gravar" type="button" id="gravar" value="Salvar e Continuar Editando" class="btn-padrao pedidos" onclick="saveContinue();" />
					<input type="hidden" name="continue" value="0" id="continue"/>
					<input name="carregando" type="button" id="carregando" value="Carregando..." class="gravar btn-padrao" disabled="disabled" />
				</div>
				<div class="erro"><strong></strong><img src="<?php echo $url; ?>images/ic-alert.gif"></div>
				<div class="indent" id="tabs-1">
					<div class="titulo">
						<h2>GERAL</h2>
					</div>
					<div class="formulario" id="formulario">
						<fieldset>
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="100">
										<label>ID:</label>
										<label for="titulo">Título: <span class="req">*</span></label>
										<label for="data">Data: <span class="req">*</span></label>
										<label for="descricao" class="cke">Descrição: <span class="req">*</span></label>
										<label for="ativo">Ativo</label>
									</td>
									<td>
										<input type="text" name="id" id="id" value="<?php echo $id ?>" readonly="readonly" />
										<input type="text" name="titulo" id="titulo" value="<?php echo $titulo ?>" />
										<input type="text" name="data" id="data" value="<?php echo formatDate($data, "d/m/Y") ?>" class="small" />
										<div class="clear"></div>
										<textarea name="descricao" id="descricao"><?php echo $descricao ?></textarea>
			 							<select name="ativo" id="ativo">
											<option value="1"<?php if($ativo) echo ' selected="selected"'; ?>>Sim</option>
											<option value="0"<?php if(!$ativo) echo ' selected="selected"'; ?>>Não</option>
										</select>
									</td>
								</tr>
							</table>
						</fieldset>
					</div>
				</div>
				<?php if($acao=="Alterar"): ?>
				<div class="indent" id="tabs-2">
					<div class="titulo">
						<h2>IMAGENS</h2>
				    	<div class="sucesso msg">Ordenação salva com sucesso!</div>
				    	<div class="error msg">Erro ao gravar, tente novamente mais tarde!</div>
					</div>
					<div class="formulario" id="formulario">
						<div id="uploadify" class="multi"><input type="file" name="galeria" id="galeria" /></div>
						<div class="galeria"></div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</form>
	<?php endif; ?>
</div>
