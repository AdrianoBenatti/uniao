<?php
$title = "Fotos - ";
$id = anti_sql_injection($_GET['id']) ? anti_sql_injection($_GET['id']) : 1;

$query = mysql_query("SELECT * FROM fotos") or die (mysql_error());

?>

<script>


	$(function () {
		$('#number').each(function () {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 4000,
				easing: 'swing',
				step: function (now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
	});


	$(function () {
		animationFotosOn = true;

		var carousel = $('.carousel');

		carousel.owlCarousel({
			items: 1,
			smartSpeed: 500,
			701: {
				items: 3,
			}
		});
		carousel.on('changed.owl.carousel', function (property) {
			var current = property.item.index + 1;
			$('.indice-foto p span').html(current);
		});

		$('.seta_esq').click(function () {
			carousel.trigger('prev.owl.carousel');
		});
		$('.seta_dir').click(function () {
			carousel.trigger('next.owl.carousel');
		});
	});


	$(function () {
		$('.images a').colorbox({rel: 'foto', maxWidth: '80%', maxHeight: '80%'});
	});
</script>


<div class="ultimo-jogo">

	<div class="centraliza">


		<div class="images">

			<?php while ($j = mysql_fetch_array($query)): ?>
				<div id="aux-images">
					<a class="foto" href="<?php echo $url . "media/fotos/" . $j['imagem']; ?>">
						<img src="<?php echo $url . "media/fotos/" . $j['imagem']; ?>" alt="<?php echo($j['nome']) ?>"/>
					</a>
					<p><?php echo($j['descricao']) ?></p>
				</div>
			<?php endwhile; ?>

		</div>

	</div>




