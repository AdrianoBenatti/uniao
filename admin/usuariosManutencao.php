<?php
autorizar("usuarios");
if ($_POST) {
	foreach ($_POST as $k => $v ) {
		if(!is_array($v)) $$k = utf8_decode(removeWordChars($v));
	}
	$id = $_GET['id'];

	$data = array(
		"nome" => $nome,
		"email" => $email,
		"login" => $login,
		"funcao" => $funcao
	);

	if(($alterarsenha=="1" || $acao == "Incluir") && $senha) $data['senha'] = md5($senha);

	if($acao == "Incluir") $id = null;

	$id = save($data, "usuarios", $id);

	mysql_query("DELETE FROM usuarios_permissoes WHERE usuarios_id = '$id'", $connect);
	$query = mysql_query("SELECT * FROM tabelas ORDER BY id ASC", $connect);
	while($dados = mysql_fetch_array($query)){
		$val = $_POST[$dados['tabela']];
		if ($val == 1) {
			$data = array(
				"usuarios_id" => $id,
				"tabelas_id" => $dados['id']
			);
			save($data, "usuarios_permissoes");
		}
	}

	if(!$continue) header("Location: {$url}usuarios"); else header("Location: {$url}$page/Alterar/$id");
}else{
	if ($acao == "Alterar") {
		$sql = "SELECT * FROM usuarios WHERE id = $id";
		$query = mysql_query($sql, $connect);
		if ($dados = mysql_fetch_array($query)){
			foreach($dados as $key=>$value) {
				$$key = utf8_encode($value);
			}
		}
	}else{
		$id = proxID("usuarios");
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
				funcao:"required",
				login: {
					required: true,
					remote: url+'checkLogin.php?acao=<?php echo $acao ?>&id=<?php echo $id ?>&tabela=usuarios'
				},
				email: {
					required: true,
					email: true,
					remote: url+'checkEmail.php?acao=<?php echo $acao ?>&id=<?php echo $id ?>&tabela=usuarios'
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
				funcao:'Preencha a função do usuário no clube!',
				login: {
					required: 'Informe o login',
					remote: 'Login existente, informe outro'
				},
				email: {
					required: 'Informe o e-mail',
					email: 'Informe um email válido',
					remote: 'E-mail já existente, informe outro'
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
		$("#login").setMask({mask:'*',type:'repeat','maxLenght':20});
		$("#alterarsenha").click(function(){
			var checked = $(this).attr('checked');
			if (checked) {
				$(".senha").removeClass("hide");
			} else{
				$(".senha").addClass("hide");
			};
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
			<form name="form1" id="form1" method="post" action="<?php echo $url; ?>usuariosManutencao/<?php echo $acao ?>/<?php echo $id ?>" onsubmit="lockButton(this);">
				<input type="hidden" name="acao" value="<?php echo $acao ?>" id="acao"/>
				<input type="hidden" name="id[]" value="<?php echo $id ?>" id="id[]" />
				<div class="sidebar left">
					<h2>Usuários &gt; <?php echo $acao; ?></h2>
					<ul>
						<li id="atabs-1" class="hover"><a href="#tabs-1" onClick="CngClass(this);">Geral</a></li>
						<li id="atabs-2"><a href="#tabs-2" onClick="CngClass(this);">Permissões</a></li>
					</ul>
				</div>
				<div class="contentManutencao right">
					<div class="t-prod" id="menu">
						<h2>Usuários &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>usuarios'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'usuarios', '<?php echo $url; ?>usuarios', true);" /><?php endif; ?>
						<input name="gravar" type="submit" id="gravar" value="Salvar" class="btn-padrao pedidos" />
						<input name="gravar" type="button" id="gravar" value="Salvar e Continuar Editando" class="btn-padrao pedidos" onclick="saveContinue();" />
						<input name="carregando" type="button" id="carregando" value="Carregando..." class="gravar btn-padrao" disabled="disabled" />
					</div>
					<div class="t-prod HighIndex" id="navContainer">
						<h2>Usuários &gt; <?php echo $acao; ?></h2>
						<input type="button" onclick="location.href='<?php echo $url; ?>usuarios'" class="btn-padrao voltar" id="voltar" value="Voltar" />
						<?php if($acao=="Alterar"): ?><input name="excluir" type="button" id="excluir" value="Excluir" class="btn-padrao excluir" onclick="deleteSelected(<?php echo $id ?>, 'usuarios', '<?php echo $url; ?>usuarios', true);" /><?php endif; ?>
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
											<label for="login">Login: <span class="req">*</span></label>
											<label for="funcao">Função: <span class="req">*</span></label>
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
											<input type="text" name="login" id="login" value="<?php echo $login ?>" />
											<input type="text" name="funcao" id="funcao" value="<?php echo $funcao ?>" />
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
					<div class="indent" id="tabs-2">
						<div class="titulo">
							<h2>PERMISSÕES</h2>
						</div>
						<div class="formulario" id="formulario">
							<fieldset>
								<a href="javascript:void(0);" onclick="checkAll('.tbpermissoes')" class="abox">Selecionar/Deselecionar todos</a>
								<div class="clear"></div>
								<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbpermissoes">
									<tr>
										<td width="180">
											<?php
											$query = mysql_query("SELECT * FROM tabelas ORDER BY titulo ASC", $connect);
											while($dados = mysql_fetch_array($query))
												echo '<label for="p'.$dados['id'].'">'.utf8_encode($dados['titulo']).'</label>';
											?>
										</td>
										<td>
											<?php
											$permissoes = array();
											$query = mysql_query("SELECT * FROM tabelas ORDER BY titulo ASC", $connect);
											if($acao == "Alterar"){
												$query2 = mysql_query("SELECT tabelas_id FROM usuarios_permissoes WHERE usuarios_id = $id", $connect);
												while($p = mysql_fetch_array($query2)){
													$permissoes[] = $p['tabelas_id'];
												}
											}
											while($dados = mysql_fetch_array($query)){ ?>
												<input name="<?php echo $dados['tabela']; ?>" type="checkbox" id="p<?php echo $dados['id'] ?>" value="1"<?php if(in_array($dados['id'], $permissoes)) echo ' checked="checked"' ?> />
												<div class="clear"></div>
											<?php } ?>
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