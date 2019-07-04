<?php autorizar("jogos"); ?>
<?php $query = mysql_query("SELECT * FROM jogos ORDER BY id ASC", $connect); ?>

<script>
	$(document).ready(function() {
		generateDataTable('jogos','id','desc');
	});
</script>
<div id="conteudo">
	<div class="center">
		<div class="header">
			<h1>Jogos E.C União Santa Luiza</h1>
			<div class="buttons">
				<input type="button" onclick="location.href='<?php echo $url; ?>jogosManutencao/Incluir/0'" class="btn-padrao criar" value="Novo" />
				<input type="button" onclick="javascript:self.print();" class="btn-padrao pedidos" value="Imprimir" />
				<input type="button" onclick="deleteSelected();" class="btn-padrao excluir" value="Excluir Selecionados" />
			</div>
		</div>
		<div class="clr"></div>
		<?php  if (mysql_num_rows($query) == 0){ ?>
			<div class="note-msg">Nenhum registro cadastrado</div>
		<?php }else{?>
			<table border="0" cellspacing="1" class="adminlist" id="jogos" bordercolor="#FFFFFF" width="100%">
				<thead>
				<tr>
					<th width="10%" align="center">ID</th>
					<th width="20%" align="center">Jogo Anterior</th>
					<th width="20%" align="center">Imagem Jogo Anterior</th>
					<th width="20%" align="center">Próximo Jogo</th>
					<th width="20%" align="center">Imagem Próximo Jogo</th>
					<td width="10%"></td>
				</tr>
				</thead>
				<tfoot></tfoot>
				<tbody>
				<?php
				while($dados = mysql_fetch_array($query)){
					foreach($dados as $key=>$value) {
						$$key = $value;
					}
					$altlink = "{$url}jogosManutencao/Alterar/".$id;
					?>
					<tr>
						<td width="10%" align="center" class="check"><input type="checkbox" name="id[]" value="<?php echo $id ?>" id="id[]" /></td>
						<td width="20%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo $data_ant ?></td>
						<td width="30%" align="left" onClick="goTo('<?php echo $altlink ?>');"> <?php if($img_ant): ?><img src="<?php echo $url."../media/jogos/".$img_ant; ?>" style="margin:0 0 10px 10px;  height: 50px;" /><?php endif; ?></td>
						<td width="20%" align="center" onClick="goTo('<?php echo $altlink ?>');"><?php echo $data_prox  ?></td>
						<td width="30%" align="left" onClick="goTo('<?php echo $altlink ?>');"> <?php if($img_prox): ?><img src="<?php echo $url."../media/jogos/".$img_prox; ?>" style="margin:0 0 10px 10px;  height: 50px;" /><?php endif; ?></td>
						<td width="10%" align="center"><a href="<?php echo $altlink ?>" title="Alterar"><img src="images/alterar.png" alt="Alterar" /></a></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
	</div>
</div>
