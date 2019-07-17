<?php autorizar("fotos"); ?>
<?php $query = mysql_query("SELECT * FROM fotos ORDER BY id ASC", $connect);
/*$sql = "SELECT imagem1 FROM fotos";
$result = mysql_query($sql);
$registro = mysql_fetch_assoc($result);
$imagem = $registro['imagem1'];*/
?>

<script>
	$(document).ready(function() {
		generateDataTable('fotos','id','desc');
	});
</script>
<div id="conteudo">
	<div class="center">
		<div class="header">
			<h1>Patrocinadores E.C União Santa Luiza</h1>
			<div class="buttons">
				<input type="button" onclick="location.href='<?php $url; ?>fotosManutencao/Incluir/0'" class="btn-padrao criar" value="Novo" />
				<input type="button" onclick="javascript:self.print();" class="btn-padrao pedidos" value="Imprimir" />
				<input type="button" onclick="deleteSelected();" class="btn-padrao excluir" value="Excluir Selecionados" />
			</div>
		</div>
		<div class="clr"></div>
		<?php  if (mysql_num_rows($query) == 0){ ?>
			<div class="note-msg">Nenhum registro cadastrado</div>
		<?php }else{?>
			<table border="0" cellspacing="1" class="adminlist" id="fotos" bordercolor="#FFFFFF" width="100%">
				<thead>
				<tr>
					<td width="6%"></td>
					<th width="10%" align="center">ID</th>
					<th width="40%" height="15" align="left">Descrição</th>
					<th width="40%" height="15" align="left">Imagem</th>
					<th width="4%"></th>
				</tr>
				</thead>
				<tfoot></tfoot>
				<tbody>
				<?php
				while($dados = mysql_fetch_array($query)){
					foreach($dados as $key=>$value) {
						$$key = utf8_encode($value);
					}
					$altlink = "{$url}fotosManutencao/Alterar/{$id}";
					?>
					<tr>
						<td width="4%" align="center" class="check"><input type="checkbox" name="id[]" value="<?php echo $id ?>" id="id[]" /></td>
						<td width="20%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo $id ?></td>
						<td width="35%" align="left" onClick="goTo('<?php echo $altlink ?>');"><?php echo $descricao ?></td>
						<td width="35%" align="left" onClick="goTo('<?php echo $altlink ?>');"> <?php if($imagem): ?><img src="<?php echo $url."../media/fotos/".$imagem; ?>" style="margin:0 0 10px 10px;  height: 50px;" /><?php endif; ?></td>
						<td width="6%" align="center"><a href="<?php echo $altlink ?>" title="Alterar"><img src="images/alterar.png" alt="Alterar" /></a></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
	</div>
</div>
