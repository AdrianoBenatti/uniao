<?php autorizar("usuarios"); ?>
<?php $query = mysql_query("SELECT * FROM usuarios ORDER BY nome ASC", $connect); ?>
<script>
$(document).ready(function() {
	generateDataTable('usuarios','id','desc');
});
</script>
<div id="conteudo">
	<div class="center">
		<div class="header">
			<h1>Usuários</h1>
			<div class="buttons">
				<input type="button" onclick="location.href='<?php $url; ?>usuariosManutencao/Incluir/0'" class="btn-padrao criar" value="Novo" />
				<input type="button" onclick="javascript:self.print();" class="btn-padrao pedidos" value="Imprimir" />
				<input type="button" onclick="deleteSelected();" class="btn-padrao excluir" value="Excluir Selecionados" />
			</div>
		</div>
		<div class="clr"></div>
		<?php  if (mysql_num_rows($query) == 0){ ?>
			<div class="note-msg">Nenhum registro cadastrado</div>
		<?php }else{?>
		<table border="0" cellspacing="1" class="adminlist" id="usuarios" bordercolor="#FFFFFF" width="100%">
			<thead>
				<tr>
					<td width="2%"></td>
					<th width="2%" align="center">ID</th>
					<th width="80%" height="15" align="left">Nome</th>
					<th width="14%" align="left">Último Acesso</th>
					<th width="2%"></th>
				</tr>
			</thead>
			<tfoot></tfoot>
			<tbody>	
		<?php
			while($dados = mysql_fetch_array($query)){
				foreach($dados as $key=>$value) {
					$$key = utf8_encode($value);
				}
			$altlink = "{$url}usuariosManutencao/Alterar/{$id}";
		?>
				<tr>
					<td width="2%" align="center" class="check"><input type="checkbox" name="id[]" value="<?php echo $id ?>" id="id[]" /></td>
					<td width="2%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo $id ?></td>
					<td width="80%" align="left" onClick="goTo('<?php echo $altlink ?>');"><?php echo $nome ?></td>
					<td width="14%" align="left" onClick="goTo('<?php echo $altlink ?>');"><?php if (!$ultimo_acesso) echo "Nenhum acesso"; else echo formatDate($ultimo_acesso, "d/m/Y"); ?></td>
					<td width="2%" align="center"><a href="<?php echo $altlink ?>" title="Alterar"><img src="images/alterar.png" alt="Alterar" /></a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php } ?>
	</div>	
</div>
