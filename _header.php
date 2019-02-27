<script>


	$(function () {
		$('.click_mobile').click(function () {
			$('#menu-mobile').toggleClass('active');
			$('.click_mobile').toggleClass('active');
			$('.close').toggleClass('active');


		});

		$('.close').click(function () {
			$('#menu-mobile').toggleClass('active');
			$('.click_mobile').toggleClass('active');
			$('.close').toggleClass('active');


		});

	});
</script>


<div class="header">


	<div class="menu">

		<figure>
			<a href="<?php echo $url . "home" ?>"><img src="images/logo.png"></a>
		</figure>


		<div class="topnav">
			<div class="w_click_mobile">
				<a href="javascript:;" class="click_mobile">
					<img src="<?php echo $url ?>images/menu.png">
				</a>
			</div>

			<nav id="menu-mobile">
				<a href="<?php echo $url . "home" ?>">Home</a>
				<a href="<?php echo $url . "historia" ?>">História</a>
				<a href="<?php echo $url . "artilheiros" ?>">Artilheiros</a>
				<a href="<?php echo $url . "fotos" ?>">Fotos</a>
				<a href="<?php echo $url . "gols" ?>">Gols</a>
				<a href="<?php echo $url . "contato" ?>">Contato</a>
			</nav>

			<a href="javascript::" id="close" class="close">
				<img src="<?php echo $url ?>images/cancel.png">
			</a>
		</div>


		<div class="links">

			<a href="<?php echo $url . "home" ?>">Home</a>
			<a href="<?php echo $url . "historia" ?>">História</a>
			<a href="<?php echo $url . "artilheiros" ?>">Artilheiros</a>
			<a href="<?php echo $url . "fotos" ?>">Fotos</a>
			<a href="<?php echo $url . "gols" ?>">Gols</a>
			<a href="<?php echo $url . "contato" ?>">Contato</a>

		</div>

		<div class="sociais">

			<figure>
				<a href="https://m.facebook.com/daviduniao" target="_blank"><img src="images/facebook.png">&nbsp;/daviduniao</a>
			</figure>

			<figure>
				<a href="https://www.instagram.com/uniaosantaluiza/" target="_blank"><img src="images/instagram.png">&nbsp;/uniaosantaluiza</a>
			</figure>

			<figure>
				<a href="whatsapp.php" target="_blank"><img src="images/whatsapp.png">&nbsp;&nbsp;19 99372-8524</a>
			</figure>


		</div>


	</div>

</div>