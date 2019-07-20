<?php
$title = "Artilheiros - ";
$id = anti_sql_injection($_GET['id']) ? anti_sql_injection($_GET['id']) : 1;

$query = mysql_query("SELECT * FROM artilheiros order by id") or die (mysql_error());

?>


<div class="artilheiros">

	<div class="ultimo-jogo">

		<div class="artilharia">

			<h1>Artilharia</h1>


			<div class="dados">

				<div class="parte1">

					<?php while ($j = mysql_fetch_array($query)): ?>
					<div class="jogador">
						<p><?php echo($j['nome_artilheiro']) . " - " . $j['gols_artilheiro'] . " gols" ?></p>
					</div>
					<?php endwhile; ?>

				</div>
			</div>
		</div>
	</div>
</div>