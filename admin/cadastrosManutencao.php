<?php
autorizar("cadastros");
if ($_POST) {
	foreach ($_POST as $k => $v ) {
		if(!is_array($v)) $$k = utf8_decode(removeWordChars($v));
	}
	$id = $_GET['id'];

	$data = array(
		"nome" => $nome,
		"email" => $email,
		"funcao" => $funcao,
		"aprovado" => $aprovado,
		"data_aprovacao" => $data_aprovacao
	);

	if(empty($data_aprovacao)) unset($data['data_aprovacao']);

	if(($alterarsenha=="1" || $acao == "Incluir") && $senha) $data['senha'] = md5($senha);

	if($acao == "Incluir") $id = null;

	$id = save($data, "cadastros", $id);

	if(!$continue) header("Location: {$url}cadastros"); else header("Location: {$url}$page/Alterar/$id");
}else{
	if ($acao == "Alterar") {
		$sql = "SELECT * FROM cadastros WHERE id = $id";
		$query = mysql_query($sql, $connect);
		if ($dados = mysql_fetch_array($query)){
			foreach($dados as $key=>$value) {
				 $$key = utf8_encode($value);
			}
		}

	}else{
		$id = proxID("cadastros");
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
			email: {
				required: true,
				email: true
			},
			funcao: {
				required: true,
			},
			senha:{
				required: function () {
					return $("#acao").val() == "Incluir" || $("#alterarsenha").attr("checked") == true;
				},
				minlength:6
			},
			senha2:{
				required: function () {
					return $("#acao").val() == "Incluir" || $("#alterarsenha").attr("checked") == true;
				},
				equalTo:"#senha"
			}
		},
		messages: {
			nome:'Você não preencheu seu nome!',
			email: {
				required: 'Informe o e-mail',
				email: 'Informe um email válido',
				funcao: "Informe a função do usuário na diretoria"
			},
			senha2:{
				required:'Repita a senha',
				equalTo:'As senhas não coincidem'
			}
		}
	});
	$("#alterarsenha").click(function(){
		var checked = $(this).attr('checked');
		if (checked) {
			$(".senha").removeClass("hide");
		} else{
			$(".senha").addClass("hide");
		};
	});
	$("#tipo").change(function(){
		var val = $(this).val();

		$('[class*="tipo-"]').hide();
		$('input[class*="tipo-"]').not('.tipo-'+val).val("");
		$('.tipo-'+val).show();
	});
	$("#tipo").trigger('change');


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
			<form name="form1" id="form1" method="post" action="<?php echo $url; ?>cadastrosManutencao/<?php echo $acao ?>/<?php echo $id ?>" onsubmit="lockButton(this);">
				<input type="hidden" name="acao" value="<?php echo $acao ?>" id="acao"/>
				<input type="hidden" name="id[]" value="<?php echo $id ?>" id="id[]" />
				<input type="hidden" name="data_aprovacao" value="<?php echo $data_aprovacao ?>" />
				<div class="sidebar left">
					<h2>Cadastros Pharma &gt; <?php echo $acao; ?></h2>
					<ul>
						<li id="atabs-1" class="hover"><a href="#tabs-1" onClick="CngClass(this);">Geral</a></li>
					</ul>
				</div>
				<div class="contentManutencao right">
					<div class="t-prod" id="menu">
						<h2>Cadastros E.C União Santa Luiza &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>cadastros'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'cadastros', '<?php echo $url; ?>cadastros', true);" /><?php endif; ?>
						<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" />
						<input name="carregando" type="button" id="carregando" value="Carregando..." class="gravar btn-padrao" disabled="disabled" />
					</div>
					<div class="t-prod HighIndex" id="navContainer">
						<h2>Cadastros Pharma &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>cadastros'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'cadastros', '<?php echo $url; ?>cadastros', true);" /><?php endif; ?>
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
											<label for="nome">Nome: <span class="req">*</span></label>
											<label for="email">E-mail: <span class="req">*</span></label>
											<label for="tipo">Função: <span class="req">*</span></label>
											<label for="aprovado">Aprovado: <span class="req">*</span></label>

											<?php if($acao=='Alterar'): ?><label for="alterarsenha">Alterar Senha: </label><?php endif; ?>
											<div class="senha<?php if($acao=='Alterar') echo ' hide'; ?>">
												<label for="senha">Senha: <span class="req">*</span></label>
												<label for="senha2">Repita a Senha: <span class="req">*</span></label>
											</div>
										</td>
										<td>

											<input type="text" name="id" id="id" value="<?php echo $id ?>" readonly="readonly" />
											<input type="text" name="nome" id="nome" value="<?php echo $nome ?>" />
											<input type="text" name="email" id="email" value="<?php echo $email ?>" />
											<input type="text" name="funcao" id="funcao" value="<?php echo $funcao ?>" />
											<select name="aprovado" id="aprovado">
												<option value="0">Não</option>
												<option value="1" <?php if($aprovado === "1") echo 'selected="selected"' ?>>Sim</option>
											</select>
											<?php if($acao=='Alterar'): ?><input type="checkbox" name="alterarsenha" id="alterarsenha" value="1" /><?php endif; ?>
											<div class="senha<?php if($acao=='Alterar') echo ' hide'; ?>">
												<input type="password" name="senha" id="senha" autocomplete="off" />
												<input type="password" name="senha2" id="senha2" autocomplete="off" />
											</div>
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