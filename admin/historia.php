<?php autorizar("historia"); ?>
<?php $query = mysql_query("SELECT * FROM historia ORDER BY id ASC", $connect); ?>

<script>
	$(document).ready(function() {
		generateDataTable('historia','id','desc');
	});
</script>
<div id="conteudo">
	<div class="center">
		<div class="header">
			<h1>Jogadores E.C União Santa Luiza</h1>
			<div class="buttons">
				<input type="button" onclick="location.href='<?php echo $url; ?>historiaManutencao/Incluir/0'" class="btn-padrao criar" value="Novo" />
				<input type="button" onclick="javascript:self.print();" class="btn-padrao pedidos" value="Imprimir" />
				<input type="button" onclick="deleteSelected();" class="btn-padrao excluir" value="Excluir Selecionados" />
			</div>
		</div>
		<div class="clr"></div>
		<?php  if (mysql_num_rows($query) == 0){ ?>
			<div class="note-msg">Nenhum registro cadastrado</div>
		<?php }else{?>
			<table border="0" cellspacing="1" class="adminlist" id="historia" bordercolor="#FFFFFF" width="100%">
				<thead>
				<tr>
					<td width="5%"></td>
					<th width="5%" align="center">ID</th>
					<th width="30%" align="center">Título</th>
					<th width="30%" align="center">Data Fundação</th>
					<th width="30%" align="center">História</th>
				</tr>
				</thead>
				<tfoot></tfoot>
				<tbody>
				<?php
				while($dados = mysql_fetch_array($query)){
					foreach($dados as $key=>$value) {
						$$key = $value;
					}
					$altlink = "{$url}historiaManutencao/Alterar/".$id;
					?>
					<tr>
						<td width="2%" align="center" class="check"><input type="checkbox" name="id[]" value="<?php echo $id ?>" id="id[]" /></td>
						<td width="2%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo $id ?></td>
						<td width="30%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo utf8_encode($titulo)  ?></td>
						<td width="30%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo $fundacao ?></td>
						<td width="30%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo substr($texto,0,50)."..." ?></td>
						<td width="6%" align="center"><a href="<?php echo $altlink ?>" title="Alterar"><img src="images/alterar.png" alt="Alterar" /></a></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
	</div>
</div>
