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
			<div id="number">327</div>
		</div>

	</div>
</div>