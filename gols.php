<?php
$title = "Gols - ";
$id = anti_sql_injection($_GET['id']) ? anti_sql_injection($_GET['id']) : 1;

$query = mysql_query("SELECT * FROM gols") or die (mysql_error());
$gols = mysql_fetch_array($query);
?>


<script>

$(function () {
		$('#number').each(function () {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 15000,
				easing: 'swing',
				step: function (now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
	});

</script>


<div class="ultimo-jogo gols">
	<div class="centraliza">

		<div class="contador reveal" id="contador" data-delay="400">
			<p>Gols</p>
			<div id="number"><?php echo($gols['gols']) ?></div>
		</div>

	</div>
</div>