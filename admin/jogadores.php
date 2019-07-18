<?php autorizar("jogadores"); ?>
<?php $query = mysql_query("SELECT * FROM jogadores ORDER BY id ASC", $connect); ?>

<script>
	$(document).ready(function() {
		generateDataTable('jogadores','id','desc');
	});
</script>
<div id="conteudo">
	<div class="center">
		<div class="header">
			<h1>Jogadores E.C União Santa Luiza</h1>
			<div class="buttons">
				<input type="button" onclick="location.href='<?php echo $url; ?>jogadoresManutencao/Incluir/0'" class="btn-padrao criar" value="Novo" />
				<input type="button" onclick="javascript:self.print();" class="btn-padrao pedidos" value="Imprimir" />
				<input type="button" onclick="deleteSelected();" class="btn-padrao excluir" value="Excluir Selecionados" />
			</div>
		</div>
		<div class="clr"></div>
		<?php  if (mysql_num_rows($query) == 0){ ?>
			<div class="note-msg">Nenhum registro cadastrado</div>
		<?php }else{?>
			<table border="0" cellspacing="1" class="adminlist" id="jogadores" bordercolor="#FFFFFF" width="100%">
				<thead>
				<tr>
					<td width="5%"></td>
					<th width="5%" align="center">ID</th>
					<th width="50%" align="center">Nome do Jogador</th>
					<th width="40%" align="center">Posição</th>
				</tr>
				</thead>
				<tfoot></tfoot>
				<tbody>
				<?php
				while($dados = mysql_fetch_array($query)){
					foreach($dados as $key=>$value) {
						$$key = $value;
					}
					$altlink = "{$url}jogadoresManutencao/Alterar/".$id;
					?>
					<tr>
						<td width="2%" align="center" class="check"><input type="checkbox" name="id[]" value="<?php echo $id ?>" id="id[]" /></td>
						<td width="2%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo $id ?></td>
						<td width="50%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo $nome ?></td>
						<td width="40%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo $posicao ?></td>
						<td width="6%" align="center"><a href="<?php echo $altlink ?>" title="Alterar"><img src="images/alterar.png" alt="Alterar" /></a></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
	</div>
</div>
