<?php
$title = "Patrocinadores - ";
$id = anti_sql_injection($_GET['id']) ? anti_sql_injection($_GET['id']) : 1;

?>


<div class="home">
	<div class="ultimo-jogo">


		<?php
		$query = mysql_query("SELECT * FROM patrocinadores") or die (mysql_error());
				while ($v = mysql_fetch_array($query)): ?>
			<div class="patrocinadores">
				<div class="centraliza patrocinio primeiro">
					<h2><?php echo $v['nome'] ?></h2>
					<div class="jogo-anterior">
						<?php if($v['imagem'] != ''): ?>
						<figure>
							<img src="<?php echo $url . "media/patrocinadores/" . $v['imagem']; ?>" alt="<?php echo($v['nome']) ?>"/>
						</figure>
						<?php else: ?>
						<?php endif; ?>
						<?php if($v['imagem2'] != ''): ?>
						<figure>
							<img src="<?php echo $url . "media/patrocinadores/" . $v['imagem2']; ?>" alt="<?php echo($v['nome']) ?>"/>
						</figure>
						<?php else: ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endwhile; ?>


	</div>
</div>