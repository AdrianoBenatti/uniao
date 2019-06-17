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

		<a class="foto" href="<?php echo $url ?>images/46.jpeg"><img src="<?php echo $url ?>images/46.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/45.jpeg"><img src="<?php echo $url ?>images/45.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/44.jpeg"><img src="<?php echo $url ?>images/44.jpeg" alt="União Esporte Clube"/></a>

		<a class="foto" href="<?php echo $url ?>images/43.jpeg"><img src="<?php echo $url ?>images/43.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/42.jpeg"><img src="<?php echo $url ?>images/42.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/41.jpeg"><img src="<?php echo $url ?>images/41.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/40.jpeg"><img src="<?php echo $url ?>images/40.jpeg" alt="União Esporte Clube"/></a>

		<a class="foto" href="<?php echo $url ?>images/39.jpeg"><img src="<?php echo $url ?>images/39.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/38.jpeg"><img src="<?php echo $url ?>images/38.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/37.jpeg"><img src="<?php echo $url ?>images/37.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/36.jpeg"><img src="<?php echo $url ?>images/36.jpeg" alt="União Esporte Clube"/></a>

		<a class="foto" href="<?php echo $url ?>images/35.jpeg"><img src="<?php echo $url ?>images/35.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/34.jpeg"><img src="<?php echo $url ?>images/34.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/33.jpeg"><img src="<?php echo $url ?>images/33.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/32.jpeg"><img src="<?php echo $url ?>images/32.jpeg" alt="União Esporte Clube"/></a>

		<a class="foto" href="<?php echo $url ?>images/31.jpeg"><img src="<?php echo $url ?>images/31.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/30.jpeg"><img src="<?php echo $url ?>images/30.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/29.jpeg"><img src="<?php echo $url ?>images/29.jpeg" alt="União Esporte Clube"/></a>
		<a class="foto" href="<?php echo $url ?>images/28.jpeg"><img src="<?php echo $url ?>images/28.jpeg" alt="União Esporte Clube"/></a>

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


	</div>

</div>




