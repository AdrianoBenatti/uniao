<script>

$(function(){
		animationFotosOn = true;

		var carousel = $('.carousel-logo');

		carousel.owlCarousel({
			items:1,
			smartSpeed:500
		});


		$('.seta_esq').click(function(){
			carousel.trigger('prev.owl.carousel');
		});
		$('.seta_dir').click(function(){
			carousel.trigger('next.owl.carousel');
		});
	});
</script>


	<div class="ultimo-jogo">

		<div class="centraliza">

			<div class="text reveal" data-delay="800">

<h1>E.C.  UNIÃO SANTA LUIZA</h1>

<h2>Fundado em: 20/3/14</h2>

<p>
A ideia de montar um time comecou com o presidente e técnico David N. dos anjos
junto com alguns amigos. Em 20/3/14  foi montado nosso primeiro time, com o nome de Unibol,
participamos do  campeonato de mini campo durou apenas alguns meses. Até que decidimos mudar
de nome e se tornar E.C. UNIÃO. Disputamos o nosso primeiro campeonato amador, e  conseguimos
ir ate longe, chegando nas oitavas de final. No segundo ano do time ganhamos dois festivais seguidos,
e no amador nao conseguimos ir tao longe quanto no primeiro ano. No terceiro ano decidimos mudar de nome
novamente, fazendo uma homenagem ao bairro de nova odessa, entao decidimos colocar o nome de
E.C. UNIÃO SANTA LUIZA. Foi um ano de varias mudancas no clube.
No quarto ano ganhamos 1 festival e chegamos nas quartas de final do amador. Foi um grande
ano com varios pontos positivos. Alem de tudo isso nosso proposito sempre foi apoiar e ajudar os
nossos irmãos haitianos dando a oportunidade de jogarem no clube.
Primeiro haitiano a jogar no clube Joanes Etienne.
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

						<img src="<?php echo $url ?>images/logo3.png" alt="União Esporte Clube" />

						<img src="<?php echo $url ?>images/logo2.png" alt="União Esporte Clube" />

						<img src="<?php echo $url ?>images/logo1.png" alt="União Esporte Clube" />


					</div>
				</div>
			</div>
		</div>
</div>

</div>

</div>



