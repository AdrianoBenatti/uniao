<?php
autorizar("cadastros_pharma");
if ($_POST) {
	foreach ($_POST as $k => $v ) {
		if(!is_array($v)) $$k = utf8_decode(removeWordChars($v));
	}
	$id = $_GET['id'];

	if ($aprovado == "1" && $data_aprovacao == "") {
		$data_aprovacao = date('Y-m-d H:i:s');
		sendEmail($email, 'Seu cadastro foi aprovado!', '
			<p>Seu login foi aprovado! Agora você pode desfrutar de nossas formulas</p>
			<p>Para acessar nossas formulas acesse o link: <a href="http://www.pharmaspecial.com.br/produtos" target="_blank">pharmaspecial.com.br/produtos</a></p><br>
		', 'anapaula@pharmaspecial.com.br');
	}

	$data = array(
		"nome" => $nome,
		"email" => $email,
		"tipo" => $tipo,
		"razao_social" => $razao_social,
		"cnpj" => $cnpj,
		"profissao" => $profissao,
		"conselho_de_classe" => $conselho_de_classe,
		"aprovado" => $aprovado,
		"data_aprovacao" => $data_aprovacao
	);

	if(empty($data_aprovacao)) unset($data['data_aprovacao']);

	if(($alterarsenha=="1" || $acao == "Incluir") && $senha) $data['senha'] = md5($senha);

	if($acao == "Incluir") $id = null;

	$id = save($data, "cadastros_pharma", $id);

	if(!$continue) header("Location: {$url}cadastrosPharma"); else header("Location: {$url}$page/Alterar/$id");
}else{
	if ($acao == "Alterar") {
		$sql = "SELECT * FROM cadastros_pharma WHERE id = $id";
		$query = mysql_query($sql, $connect);
		if ($dados = mysql_fetch_array($query)){
			foreach($dados as $key=>$value) {
				 $$key = utf8_encode($value);
			}
		}

	}else{
		$id = proxID("cadastros_pharma");
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
			razao_social:{
				required: function () {
					return $("#tipo").val() === "farmaceutico";
				}
			},
			cnpj:{
				required: function () {
					return $("#tipo").val() === "farmaceutico";
				}
			},
			profissao:{
				required: function () {
					return $("#tipo").val() === "prescritor";
				}
			},
			conselho_de_classe:{
				required: function () {
					return $("#tipo").val() === "prescritor";
				}
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
				email: 'Informe um email válido'
			},
			razao_social:{
				required: "Informe a razão social"
			},
			cnpj:{
				required: "Informe o CNPJ"
			},
			profissao:{
				required: "Informe a profissão"
			},
			conselho_de_classe: {
				required: "Informe o conselho de classe"
			},
			senha:{
				required: 'Digite a senha',
				minlength:'A senha deve conter no mínimo 6 caracteres'
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
			<form name="form1" id="form1" method="post" action="<?php echo $url; ?>cadastrosPharmaManutencao/<?php echo $acao ?>/<?php echo $id ?>" onsubmit="lockButton(this);">
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
						<h2>Cadastros Pharma &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>cadastrosPharma'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<input name="reiniciar" type="button" id="reiniciar" value="Reiniciar" class="btn-padrao" onclick="location.href=''" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'cadastros_pharma', '<?php echo $url; ?>cadastrosPharma', true);" /><?php endif; ?>
						<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" />
						<input name="gravar" type="button" id="gravar" value="Salvar e Continuar Editando" class="btn-padrao pedidos" onclick="saveContinue();" />
						<input name="carregando" type="button" id="carregando" value="Carregando..." class="gravar btn-padrao" disabled="disabled" />
					</div>
					<div class="t-prod HighIndex" id="navContainer">
						<h2>Cadastros Pharma &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>cadastrosPharma'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<input name="reiniciar" type="button" id="reiniciar" value="Reiniciar" class="btn-padrao" onclick="location.href=''" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'cadastros_pharma', '<?php echo $url; ?>cadastrosPharma', true);" /><?php endif; ?>
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
											<label for="nome">Nome: <span class="req">*</span></label>
											<label for="email">E-mail: <span class="req">*</span></label>
											<label for="tipo">Tipo: <span class="req">*</span></label>
											<label for="aprovado">Aprovado: <span class="req">*</span></label>

											<label class="tipo-farmaceutico" for="razao_social">Razão Social: <span class="req">*</span></label>
											<label class="tipo-farmaceutico" for="cnpj">CNPJ: <span class="req">*</span></label>

											<label class="tipo-prescritor" for="profissao">Área de atuação: <span class="req">*</span></label>
											<label class="tipo-prescritor" for="conselho_de_classe">Conselho de Classe: <span class="req">*</span></label>

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
											<select name="tipo" id="tipo">
												<option value="farmaceutico">Farmacêutico</option>
												<option value="prescritor" <?php if($tipo === "prescritor") echo 'selected="selected"' ?>>Prescritor</option>
											</select>
											<select name="aprovado" id="aprovado">
												<option value="0">Não</option>
												<option value="1" <?php if($aprovado === "1") echo 'selected="selected"' ?>>Sim</option>
											</select>

											<input class="tipo-farmaceutico" type="text" name="razao_social" id="razao_social" value="<?php echo utf8_encode($razao_social)   ?>" />
											<input class="tipo-farmaceutico" type="text" name="cnpj" id="cnpj" value="<?php echo $cnpj ?>" alt="cnpj" />

											<input class="tipo-prescritor" type="text" name="profissao" id="profissao" value="<?php echo $profissao ?>" />
											<input class="tipo-prescritor" type="text" name="conselho_de_classe" id="conselho_de_classe" value="<?php echo $conselho_de_classe ?>" />

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