<script>


	$(function () {
		$('#number1').each(function () {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 5000,
				easing: 'swing',
				step: function (now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
		$('#number2').each(function () {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 5000,
				easing: 'swing',
				step: function (now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
		$('#number3').each(function () {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 5000,
				easing: 'swing',
				step: function (now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
		$('#number4').each(function () {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 5000,
				easing: 'swing',
				step: function (now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
		$('#number5').each(function () {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 5000,
				easing: 'swing',
				step: function (now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
		$('#number6').each(function () {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 5000,
				easing: 'swing',
				step: function (now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
		$('#number7').each(function () {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 5000,
				easing: 'swing',
				step: function (now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
		$('#number8').each(function () {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 5000,
				easing: 'swing',
				step: function (now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
		$('#number9').each(function () {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 5000,
				easing: 'swing',
				step: function (now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
	});


</script>


<div class="ultimo-jogo">

	<div class="artilharia">

		<h1>Artilharia</h1>


		<div class="dados">

			<div class="parte1">

			<div class="jogador">
				<p>Felipe Henrike (Felipinho)</p>

				<div class="number" id="number1"> 46</div>
				gols<br><br>
			</div>

			<div class="jogador">
				<p>Rafael Daloz</p>

				<div class="number" id="number2"> 32</div>
				gols<br><br>
			</div>
			<div class="jogador">
				<p>Guilherme Binotte</p>

				<div class="number" id="number4"> 15</div>
				gols<br><br>
			</div>
			<div class="jogador">
				<p>Leonardo Rodolfo (Leo)</p>

				<div class="number" id="number3"> 12</div>
				gols<br><br>
			</div>
			</div>


			<div class="parte2">

				<div class="jogador">
				<p>Joanes Etienne</p>

				<div class="number" id="number5"> 10</div>
				gols<br><br>
			</div>
			<div class="jogador">
				<p>Leandro Noronha</p>

				<div class="number" id="number6"> 10</div>
				gols<br><br>
			</div>
			<div class="jogador">
				<p>Eduardo Pereira (Du)</p>

				<div class="number" id="number7"> 9</div>
				gols<br><br>
			</div>
			<div class="jogador">
				<p>Romain Monval</p>

				<div class="number" id="number8"> 9</div>
				gol<br><br>
			</div>
			<div class="jogador">
				<p>Elias Vicente (Liu)</p>

				<div class="number" id="number9"> 9</div>
				gols<br><br>
			</div>

			</div>

		</div>

	</div>

</div>

