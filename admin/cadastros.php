<?php autorizar("cadastros"); ?>
<?php $query = mysql_query("SELECT * FROM cadastros ORDER BY id ASC", $connect); ?>

<script>
$(document).ready(function() {
	generateDataTable('cadastros','id','desc');
});
</script>
<div id="conteudo">
	<div class="center">
		<div class="header">
			<h1>Cadastros E.C União Santa Luiza</h1>
			<div class="buttons">
				<input type="button" onclick="location.href='<?php echo $url; ?>cadastrosManutencao/Incluir/0'" class="btn-padrao criar" value="Novo" />
				<input type="button" onclick="javascript:self.print();" class="btn-padrao pedidos" value="Imprimir" />
				<input type="button" onclick="deleteSelected();" class="btn-padrao excluir" value="Excluir Selecionados" />
			</div>
		</div>
		<div class="clr"></div>
		<?php  if (mysql_num_rows($query) == 0){ ?>
			<div class="note-msg">Nenhum registro cadastrado</div>
		<?php }else{?>
		<table border="0" cellspacing="1" class="adminlist" id="cadastros" bordercolor="#FFFFFF" width="100%">
			<thead>
				<tr>
					<td width="2%"></td>
					<th width="2%" align="center">ID</th>
					<th width="30%" align="left">Nome</th>
					<th width="30%" align="left">E-mail</th>
					<th width="30%" align="left">Função</th>
					<th width="2%" align="center">Aprovado</th>
					<th width="2%" align="center">Alterar</th>
				</tr>
			</thead>
			<tfoot></tfoot>
			<tbody>
		<?php
			while($dados = mysql_fetch_array($query)){
				foreach($dados as $key=>$value) {
					$$key = $value;
				}
			$altlink = "{$url}cadastrosManutencao/Alterar/".$id;
		?>
				<tr>
					<td width="2%" align="center" class="check"><input type="checkbox" name="id[]" value="<?php echo $id ?>" id="id[]" /></td>
					<td width="2%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo $id ?></td>
					<td width="30%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo($nome)  ?></td>
					<td width="30%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo $email ?></td>
					<td width="30%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo $funcao ?></td>
					<td width="2%" align="center" onClick="goTo('<?php echo $altlink ?>');"><img src="<?php echo $url . "images/publicado" . $aprovado . ".png" ?>" /></td>
					<td width="2%" align="center"><a href="<?php echo $altlink ?>" title="Alterar"><img src="images/alterar.png" alt="Alterar" /></a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php } ?>
	</div>
</div>
