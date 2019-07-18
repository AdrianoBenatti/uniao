<?php
$title = "Jogadores - ";
$id = anti_sql_injection($_GET['id']) ? anti_sql_injection($_GET['id']) : 1;

$query = mysql_query("SELECT * FROM jogadores ORDER BY id ASC") or die (mysql_error());
$j = mysql_fetch_array($query)
?>

<div class="ultimo-jogo">

	<div class="jogadores">


		<?php while ($j = mysql_fetch_array($query)): ?>
				<?php echo "<p><b>Nome Jogador:</b> " . $j['nome'] . " - " . $j['posicao'] . "</p>" ?>
		<?php endwhile; ?>


	</div>
</div>