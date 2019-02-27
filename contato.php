
<script>
	$(function () {
		$("#fContato").validate({
			submitHandler: function (form) {
				$('#valida').val('validado');
				$('.wrap-loading').show();
				$.post(url + "ajax/envia-contato.php", $(form).serialize(), function (data) {
					$('.wrap-loading').hide();
					if (data != '1') {
						mensagem = "Enviado com sucesso, em breve entraremos em contato!";
						classe = "Sucesso";
					} else {
						mensagem = "Enviado com sucesso, em breve entraremos em contato!";
						classe = "Sucesso";
					}



					jAlert(mensagem, classe);
					$('#fContato').each (function(){
						this.reset();
					});
					jQuery("input[name='telefone']").val( '' );
					jQuery("input[name='celular']").val( '' );

				});
			},
			rules: {
				nome: "required",
				celular: "required",
				email: {
					required: true,
					email: true
				}
			},
			messages: {
				nome: "Digite o nome",
				celular: "Digite o celular",
				email: {
					required: "Digite o email",
					email: "informe um email valido"
				}
			}
		});
	});
</script>

<div class="wrap-loading"></div>

<div class="formulario">

<div class="ultimo-jogo">

	<div class="form">

		<h2>Entre em contato com nossa diretoria!</h2>

		<form action="javascript:;" id="fContato" method="post">
			<input type="hidden" name="valida" id="valida"/>
			<input type="hidden" name="url" value="<?php echo $url ?>">

			<div class="campos">
				<input type="text" name="nome" placeholder="Nome">
				<input type="text" name="email" placeholder="Email">
				<input type="text" name="celular" placeholder="Celular" alt="phonesp">
				<input type="text" name="telefone" placeholder="Telefone" alt="phonesp">
			</div>

			<div class="mensagem">
				<h3>Deixe sua mensagem:</h3>
				<textarea name="obs" placeholder="Mensagem"></textarea>
				<input type="submit" value="Enviar">
			</div>

		</form>

	</div>

</div>

</div>

