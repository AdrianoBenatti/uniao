<?php include("functions.php"); ?>
<?php $id = $_GET['id']; ?>
<?php $tabela = $_GET['tabela']; ?>

<script>
$(function(){
	$('#<?php echo $tabela; ?> li').hover(
		function(){
			$(this).find('.del').show();
			$(this).find('.adddesc').show().stop().animate({'bottom':'-20px', 'padding':'3px 0'},300);
		},
		function(){
			$(this).find('.del').hide();
			$(this).find('.adddesc').hide().stop().css({'bottom':'-1px', 'padding':'0'});
		}
	);
	$("#<?php echo $tabela; ?> .adddesc").click(function() {
		$("#dialog_<?php echo $tabela; ?>").dialog("open");
		$('#dialog_<?php echo $tabela; ?> #descricao').val($(this).parent().find('img').attr('title'));
		$('#dialog_<?php echo $tabela; ?> #idfoto').val($(this).parent().attr('id'));
	});
	$("#dialog_<?php echo $tabela; ?>").dialog({
		autoOpen: false,
		height: 320,
		width: 350,
		modal: true,
		resizable: false,
		close: function() {
			$('#dialog_<?php echo $tabela; ?> #idfoto').val('');
			$('#dialog_<?php echo $tabela; ?> #descricao').val('');
		}
	});
	$("#<?php echo $tabela; ?>").sortable({
		deactivate: function(){
			li = $('#<?php echo $tabela; ?> li').size();
			var sequence = "|";
			$('#<?php echo $tabela; ?> li').each(function(){
				$(this).attr('pos', li);
				sequence += '|'+$(this).attr('id')+'-'+$(this).attr('pos');
				li--;
			});
			sequence += "||";
			salvarLista(sequence, $('#<?php echo $tabela; ?>').attr('id'));
			//$('.salvar').attr('onclick', 'salvarLista(\''+sequence+'\');');
		}
	});
	$("#<?php echo $tabela; ?>").disableSelection();
	$('#<?php echo $tabela; ?> .del').click(function(){
		if(confirm("Deseja remover esta imagem?")) {
			li = $(this).parent('li');
			img = $(this).parent('li').attr('id');
			$.post(url+"excluir.php?id=<?php echo $id; ?>&tabela=<?php echo $tabela; ?>&id_imagem="+img, function(data){
				if(data == "1") {
					li.find('.del').hide();
					li.animate({opacity:0}, 200, function(){
						$(this).remove();
					});
				}
				else {
					alert('Erro ao deletar imagem!');
				}
			});
		}
	});
	$('#dialog_<?php echo $tabela; ?> #gravar').click(function(){
		$.post(url + "incluirDescricaoFotos.php", $('#dialog_<?php echo $tabela; ?> form').serialize(), function(data){
			if(data == "1") {
				el = $('li.i'+$("#dialog_<?php echo $tabela; ?> #idfoto").val());
				desc = $("#dialog_<?php echo $tabela; ?> #descricao").val();
				el.find('img').attr('title', desc);
				el.find('.descricao-fotos').html(desc+"...");
				$('.dialog-desc').dialog('close');
				alert('Descrição salva com sucesso!');
				//window.location.reload();
			}
			else {
				alert('Erro ao adicionar a descrição!');
			}
		});
	});
});

</script>

<div class="dialog-desc" title="Adicionar Descrição" id="dialog_<?php echo $tabela; ?>">
	<form>
		<label for="name">Descrição</label><br />
		<textarea style="width: 100%" name="descricao" id="descricao"></textarea><br />
		<input type="button" name="gravar" value="Gravar" id="gravar" />
		<input type="hidden" name="idfoto" value="" id="idfoto" />
		<input type="hidden" name="tabela" value="<?php echo $tabela; ?>" id="tabela" />
	</form>
</div>

	<?php
		$tabela_id = explode("_", $tabela);
		$query = mysql_query("SELECT * FROM {$tabela}_imagens WHERE {$tabela_id[0]}_id = $id ORDER BY ordem DESC", conecta());
		$r = mysql_num_rows($query);
	?>
	<ul class="gal_imagens" id="<?php echo $tabela; ?>">
	<?php while($dados = mysql_fetch_array($query)): ?>
		<li id="<?php echo $dados['id']; ?>" class="i<?php echo $dados['id']; ?>">
			<b class="del"></b>
			<img src="<?php echo $url; ?>../media/<?php echo $tabela; ?>/<?php echo $id ?>/thmb-<?php echo utf8_encode($dados['imagem']); ?>" title="<?php echo $dados['descricao']; ?>" /></a>
			<b class="adddesc"></b>
			<div class="descricao-fotos"><?php echo shortenText($dados['descricao'], 35); ?></div>
		</li>
	<?php endwhile; ?>
	</ul>
