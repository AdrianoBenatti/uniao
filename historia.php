<?php
$title = "História - ";
$id = anti_sql_injection($_GET['id']) ? anti_sql_injection($_GET['id']) : 1;

?>


<script>

	$(function () {
		animationFotosOn = true;

		var carousel = $('.carousel-logo');

		carousel.owlCarousel({
			items: 1,
			smartSpeed: 500
		});


		$('.seta_esq').click(function () {
			carousel.trigger('prev.owl.carousel');
		});
		$('.seta_dir').click(function () {
			carousel.trigger('next.owl.carousel');
		});
	});
</script>

<?php
$query = mysql_query("SELECT * FROM historia") or die (mysql_error());
$historia = mysql_fetch_array($query);


?>


<div class="historia">
	<div class="ultimo-jogo">

		<div class="centraliza">

			<div class="text reveal" data-delay="800">


				<h1><?php echo($historia['titulo']) ?></h1>

				<h2><?php echo date('d/m/Y', strtotime($historia['fundacao'])); ?></h2>

				<p>
					<?php echo utf8_encode($historia['texto']) ?>
				</p>

			</div>


			<div class="historia">
				<div class="wrap-fotos reveal" data-delay="600">
					<h2>Emblema atual e antigos</h2>
					<div class="indent">
						<div class="wrap-carousel">
							<div class="setas">
								<a href="javascript:;" class="seta seta_esq"><</a>
								<a href="javascript:;" class="seta seta_dir">></a>
							</div>
							<div class="carousel-logo">

								<img src="<?php echo $url ?>images/logo3.png" alt="União Esporte Clube"/>

								<img src="<?php echo $url ?>images/logo2.png" alt="União Esporte Clube"/>

								<img src="<?php echo $url ?>images/logo1.png" alt="União Esporte Clube"/>


							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>

</div>

