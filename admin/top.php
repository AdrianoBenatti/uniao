<div id="logo">
	<div class="logo"><img class="pharma" src="<?php echo $url ?>images/logo_pharma.jpg" alt=""></div>
	<div class="logo"><img class="pic" src="<?php echo $url ?>images/logoPic.png" alt=""></div>
</div>
<?php if(logado()): ?>
<div class="menu">
	<ul>
		<li><a href="javascript:void(0);">Sistema</a>
			<div class="sub">
				<ul>
					<li><a href="<?php echo $url; ?>usuarios">Usuários</a></li>
				</ul>
			</div>
		</li>
		<li class="first"><a href="<?php echo $url; ?>nfe">NF-e</a></li>
		<li><a href="javascript:void(0);">PIC</a>
				<div class="sub">
					<ul>
						<li><a href="<?php echo $url; ?>cadastrosPic">Cadastros</a></li>
						<li><a href="<?php echo $url; ?>produtosPic">Produtos</a></li>
						<li><a href="<?php echo $url; ?>newsletterPic">NewsLetter</a></li>
						<li><a href="<?php echo $url; ?>representadas">Representadas</a></li>
						<li><a href="<?php echo $url; ?>picday">PIC Day</a></li>
						<li><a href="<?php echo $url; ?>bannersPic">Banners</a></li>
						<li><a href="<?php echo $url; ?>noticias/pic">Noticias</a></li>
						<li><a href="<?php echo $url; ?>representantes">Representantes</a></li>
						<li><a href="<?php echo $url; ?>campanhasPic">Campanhas</a></li>
						<li><a href="<?php echo $url; ?>trabalheconosco/pic">Trabalhe Conosco</a></li>
						<li><a href="<?php echo $url; ?>vagas">Vagas</a></li>
						<li><a href="<?php echo $url; ?>videos/pic">Vídeos</a></li>
					</ul>
				</div>
		</li>
		<li><a href="javascript:void(0);">PharmaSpecial</a>
				<div class="sub">
					<ul>
						<li><a href="<?php echo $url; ?>cadastrosPharma">Cadastros</a></li>
						<li><a href="<?php echo $url; ?>documentacoes">Documentações</a></li>
						<li><a href="<?php echo $url; ?>noticias/pharma">Noticias</a></li>
						<li><a href="<?php echo $url; ?>bannersPharma">Banners</a></li>
						<li><a href="<?php echo $url; ?>produtosPharma">Produtos</a></li>
						<li><a href="<?php echo $url; ?>formulas">Fórmulas</a></li>
						<li><a href="<?php echo $url; ?>newsletterPharma">NewsLetter</a></li>
						<li><a href="<?php echo $url; ?>campanhasPharma">Campanhas</a></li>
						<li><a href="<?php echo $url; ?>trabalheconosco/pharma">Trabalhe Conosco</a></li>
						<li><a href="<?php echo $url; ?>midias">Specially For You</a></li>
						<li><a href="<?php echo $url; ?>vagas">Vagas</a></li>
						<li><a href="<?php echo $url; ?>videos/pharma">Vídeos</a></li>
					</ul>
				</div>
		</li>
		<li class="last"></li>
	</ul>
	<div class="user right">Ol&aacute;, <span><?php echo $_SESSION["nome"]; ?></span> | <a href="<?php echo $url; ?>sair">Sair</a> </div>
</div>
<?php endif; ?>
