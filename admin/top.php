<div id="logo">
	<div class="logo"><img class="uniao" src="<?php echo $url ?>images/logo.png" alt=""></div>
</div>
<?php if(logado()): ?>
<div class="menu">
	<ul>
		<li><a href="javascript:void(0);">E.C União Santa Luiza</a>
				<div class="sub">
					<ul>
						<li><a href="<?php echo $url; ?>cadastros">Cadastros</a></li>
					</ul>
				</div>
		</li>
		<li class="last"></li>
	</ul>
	<div class="user right">Ol&aacute;, <span><?php echo $_SESSION["nome"]; ?></span> | <a href="<?php echo $url; ?>sair">Sair</a> </div>
</div>
<?php endif; ?>
