<?php
$id = anti_sql_injection($_GET['id']) ? anti_sql_injection($_GET['id']) : 1;

$query = mysql_query("SELECT * FROM jogos") or die (mysql_error());
$j = mysql_fetch_array($query)
?>


<div class="banner"></div>
<div class="banner-mobile"></div>


<div class="home">
	<div class="ultimo-jogo">

		<div class="centraliza">

			<div class="jogo-anterior">

				<?php if ($j['img_ant'] != ''): ?>

				<h2>Última apresentação : <?php echo date('d/m/Y', strtotime($j['data_ant'])); ?></h2>
				<figure>
					<img src="<?php echo $url . "media/jogos/" . $j['img_ant']; ?>" alt="<?php echo($j['nome']) ?>"/>
				</figure>
				<p>
					<?php echo $j['txt_ant'] ?>
				</p>
				<?php else: ?>
				<p>
					Jogo Indefinido!
				</p>
				<?php endif; ?>
			</div>

			<div class="prox-jogo">

				<?php if ($j['img_prox'] != ''): ?>
				<h2>Próximo compromisso: <?php echo date('d/m/Y', strtotime($j['data_prox'])); ?></h2>
				<figure>
					<img src="<?php echo $url . "media/jogos/" . $j['img_prox']; ?>" alt="<?php echo($j['nome']) ?>"/>
				</figure>
				<p>
					<?php echo $j['txt_prox'] ?>
				</p>
				<?php else: ?>
					<p>
						Jogo Indefinido!
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>