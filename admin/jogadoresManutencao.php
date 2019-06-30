<?php
autorizar("jogadores");
if ($_POST) {
	foreach ($_POST as $k => $v ) {
		if(!is_array($v)) $$k = utf8_decode(removeWordChars($v));
	}
	$id = $_GET['id'];

	$data = array(
		"nome" => $nome,
		"posicao" => $posicao
	);


	if($acao == "Incluir") $id = null;

	$id = save($data, "jogadores", $id);

	if(!$continue) header("Location: {$url}jogadores"); else header("Location: {$url}$page/Alterar/$id");
}else{
	if ($acao == "Alterar") {
		$sql = "SELECT * FROM jogadores WHERE id = $id";
		$query = mysql_query($sql, $connect);
		if ($dados = mysql_fetch_array($query)){
			foreach($dados as $key=>$value) {
				$$key = utf8_encode($value);
			}
		}

	}else{
		$id = proxID("jogadores");
	}
}
?>
<script>
	$(document).ready(function(){
		$("#form1").validate({
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
				nome:"required",
				posicao:"required",
			},
			messages: {
				nome:'Preencha o nome do jogador!',
				posicao:'Preencha a posição do jogador!',

			}
		});
		flutua();
		tabear('#tabs');
		initSaves();
	});
</script>
<div class="center">
	<div id="tabs">
		<?php if ($acao == "Alterar" && !$dados['id']): ?>
			<div class="note-msg">Registro foi excluído ou não foi encontrado.</div>
		<?php else: ?>
			<form name="form1" id="form1" method="post" action="<?php echo $url; ?>jogadoresManutencao/<?php echo $acao ?>/<?php echo $id ?>" onsubmit="lockButton(this);">
				<input type="hidden" name="acao" value="<?php echo $acao ?>" id="acao"/>
				<input type="hidden" name="id[]" value="<?php echo $id ?>" id="id[]" />
				<div class="sidebar left">
					<h2>Jogadores E.C União Santa Luiza &gt; <?php echo $acao; ?></h2>
					<ul>
						<li id="atabs-1" class="hover"><a href="#tabs-1" onClick="CngClass(this);">Geral</a></li>
					</ul>
				</div>
				<div class="contentManutencao right">
					<div class="t-prod" id="menu">
						<h2>Jogadores E.C União Santa Luiza &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>jogadores'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'jogadores', '<?php echo $url; ?>jogadores', true);" /><?php endif; ?>
						<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" />
						<input name="carregando" type="button" id="carregando" value="Carregando..." class="gravar btn-padrao" disabled="disabled" />
					</div>
					<div class="t-prod HighIndex" id="navContainer">
						<h2>Jogadores E.C União Santa Luiza &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>jogadores'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'jogadores', '<?php echo $url; ?>jogadores', true);" /><?php endif; ?>
						<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" />
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
											<label for="nome">Nome Jogador: <span class="req">*</span></label>
											<label for="posicao">Posição do jogador: <span class="req">*</span></label>
										</td>
										<td>
											<input type="text" name="id" id="id" value="<?php echo $id ?>" readonly="readonly" />
											<input type="text" name="nome" id="nome" value="<?php echo $nome ?>" />
											<input type="text" name="posicao" id="posicao" value="<?php echo $posicao ?>" />
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