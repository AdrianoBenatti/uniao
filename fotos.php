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

		<div class="wrap-fotos reveal" data-delay="600">
			<div class="indent">
				<div class="wrap-carousel">
					<div class="setas">
						<a href="javascript:;" class="seta seta_esq"><</a>
						<a href="javascript:;" class="seta seta_dir">></a>
					</div>
					<div class="carousel">

						<img src="<?php echo $url ?>images/time13.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/titulo1.jpg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/titulo2.jpg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/time8.jpg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/trofeus.jpg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/trofeus2.jpg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/jogada1.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/jogada2.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/jogada3.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/jogada4.jpeg" alt="União Esporte Clube"/>


						<img src="<?php echo $url ?>images/time1.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/time2.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/time3.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/time4.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/time5.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/time6.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/time7.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/time8.jpg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/time9.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/time10.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/time11.jpeg" alt="União Esporte Clube"/>

						<img src="<?php echo $url ?>images/time12.jpeg" alt="União Esporte Clube"/>
					</div>
				</div>
			</div>
		</div>
	</div>




	<div class="images">

		<a class="foto" href="<?php echo $url ?>images/24.jpeg"><img src="<?php echo $url ?>images/24.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/25.jpeg"><img src="<?php echo $url ?>images/25.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/26.jpeg"><img src="<?php echo $url ?>images/26.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/27.jpeg"><img src="<?php echo $url ?>images/27.jpeg" alt="União Esporte Clube"/></a>


		<a class="foto" href="<?php echo $url ?>images/2.jpeg"><img src="<?php echo $url ?>images/2.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/3.jpeg"><img src="<?php echo $url ?>images/3.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/4.jpeg"><img src="<?php echo $url ?>images/4.jpeg" alt="União Esporte Clube"/></a>

		<a class="foto" href="<?php echo $url ?>images/5.jpeg"><img src="<?php echo $url ?>images/5.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/6.jpeg"><img src="<?php echo $url ?>images/6.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/8.jpeg"><img src="<?php echo $url ?>images/8.jpeg" alt="União Esporte Clube"/></a>

		<a class="foto" href="<?php echo $url ?>images/9.jpeg"><img src="<?php echo $url ?>images/9.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/10.jpeg"><img src="<?php echo $url ?>images/10.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/11.jpeg"><img src="<?php echo $url ?>images/11.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/12.jpeg"><img src="<?php echo $url ?>images/12.jpeg" alt="União Esporte Clube"/></a>

		<a class="foto" href="<?php echo $url ?>images/13.jpeg"><img src="<?php echo $url ?>images/13.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/14.jpeg"><img src="<?php echo $url ?>images/14.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/15.jpeg"><img src="<?php echo $url ?>images/15.jpeg" alt="União Esporte Clube"/></a>

		<a class="foto" href="<?php echo $url ?>images/18.jpeg"><img src="<?php echo $url ?>images/18.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/19.jpeg"><img src="<?php echo $url ?>images/19.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/20.jpeg"><img src="<?php echo $url ?>images/20.jpeg" alt="União Esporte Clube"/></a>

	</div>

</div>




