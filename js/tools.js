var ww, wh, fator;
var animationSlideOn = false, animationFotosOn = false, animationLocalizacaoOn = false, animationContatoOn = false;
var animationInfraEnd = false, animationHomeEnd = false, animationLocalizacaoEnd = false, animationImplantacaoOn = false;
var intervalo, qtdItensHome, qtdItenInfra;
$(function(){
	$(window).resize(function(){
		ww = $(window).width();
		wh = $(window).height();
		fator = ww / 1920;
	});
	$('input').setMask();

	$('input[alt="phonesp"]').setMask({mask: "(99) 9999-99999", autoTab:false});
	$('input[alt="phonesp"]').on('input', function(){
		phone = $(this).val().replace(/\D/g, '');
		$(this).unsetMask();
		if(phone.length == 11) {
			$(this).setMask({mask: "(99) 99999-9999", autoTab:false});
		} else {
			$(this).setMask({mask: "(99) 9999-99999", autoTab:false});
		}
	}).trigger('input');

	$('input').not('input[alt="phonesp"]').setMask({
		autoTab:false
	});

	$('a.click_link').click(function(e){
		/*
		e.preventDefault();

		page = $(this).data('page');
		title = $(this).data('title');

		if (page_aux == page) return false;

		animation(page, title);
		*/
	});

	//browser = window.navigator.userAgent;
	//msie = browser.indexOf("MSIE");
	//if (msie >= 0){
	//	version = parseInt(browser.substring(msie + 5, browser.indexOf(".", msie)));
	//	if (version <= 10){
	//	}
	//}

});

window.onpopstate = function(event) {
	animation(event.state.pagina, event.state.titulo);
};

function animation(page, title){
	if (page == '' || page == undefined || title == '' || title == undefined) return false;

	$('#menu ul li a').removeClass('ativo');
	$('#menu ul li a.'+page).addClass('ativo');
	slide = ['home', 'infraestrutura'];

	//verifica quais animações estão ativas e defaz antes de carregar as novas
	if (animationSlideOn){
		elAnimation('.side-1', {left:(fator*950)*-1}, 750, 'easeInOutQuad', 200);
		elAnimation('.side-2', {right:-ww}, 950, 'easeInOutQuad');

		if (page == 'infraestrutura'){
			elAnimation('.box', {opacity:0}, 500, 'easeInOutQuad', 500);
			animationInfraEnd = false;
		}  else if (page == 'home'){
			animationHomeEnd = false;
		}

		animationSlideOn = false;
	}

	if (animationImplantacaoOn){
		elAnimation('.wrap-content', {height:429}, 500, 'easeInOutQuad');
		elAnimation('.wrap-implantacao', {opacity:0}, 500, 'easeInOutQuad');
		animationImplantacaoOn = false;
	}

	if (animationFotosOn){
		elAnimation('.wrap-content', {height:429}, 500, 'easeInOutQuad');
		elAnimation('.wrap-fotos', {opacity:0}, 500, 'easeInOutQuad');
		animationFotosOn = false;
	}


	if (animationContatoOn){
		elAnimation('.wrap-contato', {opacity: 0}, 750, 'easeInOutQuad');
		animationContatoOn = false;
	}

	if (animationLocalizacaoOn){
		elAnimation('.side-1', {left:(fator * 910)*-1, width:fator * 910}, 750, 'easeInOutQuad', 200);
		elAnimation('.side-2', {right:(fator * 1010)*-1, width:fator * 1010}, 950, 'easeInOutQuad');
		animationLocalizacaoOn = false;
		animationLocalizacaoEnd = false;
	}
	/* Fim */

	//caregar a página e executa a animação apropriada
	setTimeout(function(){
		$('.wrap-content').load(url+page+'.php', function(){
			$('footer .indent nav').fadeIn();
			if ($.inArray(page, slide) != -1){
				animationSlideOn = true;

				side1 = fator * 950;
				elStart('.banner-1 .side-1', {left:-side1, width:side1});
				elStart('.banner-1 .side-2', {right:-ww, width:ww});

				$('.banner').not('.banner-1').hide();
				elAnimation('.banner-1 .side-1', {left:0}, 750, 'easeInOutQuad', 200);
				elAnimation('.banner-1 .side-2', {right:0}, 950, 'easeInOutQuad');


				if (page == 'infraestrutura'){
					//elAnimation('.box', {opacity:0.95}, 500, 'easeInOutQuad', 500);
					qtdItenInfra = $('.banner').length;
					preloadImage(url+'images/bg-banner-infra-01.jpg');
					preloadImage(url+'images/bg-banner-infra-02.jpg');
					preloadImage(url+'images/bg-banner-infra-03.jpg');
					preloadImage(url+'images/bg-banner-infra-04.jpg');

					applyCarouselChange(qtdItenInfra);

					animationInfraEnd = true;
				} else if (page == 'home'){
					qtdItensHome = $('.banner').length;

					preloadImage(url+'images/bg-banner-01.jpg');
					preloadImage(url+'images/bg-banner-02.jpg');
					preloadImage(url+'images/bg-banner-03.jpg');

					applyCarouselChange(qtdItensHome);
					animationHomeEnd = true;
				}
			} else if (page == 'implantacao'){
				elAnimation('.wrap-content', {height:666}, 500, 'easeInOutQuad');
				elAnimation('.wrap-implantacao', {opacity:1}, 500, 'easeInOutQuad', 500);
			} else if (page == 'fotos'){
				$('footer .indent nav').hide();
				elAnimation('.wrap-content', {height:666}, 500, 'easeInOutQuad');
				elAnimation('.wrap-fotos', {opacity:1}, 500, 'easeInOutQuad', 500);
			} else if (page == 'contato'){
				elAnimation('.wrap-contato', {opacity: 1}, 750, 'easeInOutQuad');
			} else if (page == 'localizacao'){
				elAnimation('.side-1', {left:0}, 750, 'easeInOutQuad', 200);
				elAnimation('.side-2', {right:0}, 950, 'easeInOutQuad');
				animationLocalizacaoEnd = true;
			}
		});
	}, 950);

	window.document.title = title + ' - Portal das Laranjeiras';
	window.history.pushState({pagina: page, titulo: title}, document.title, url+page);
	page_aux = page;
}

function elStart(el, obj){
	$(el).css(obj);
}

function elAnimation(el, obj, tempo, animacao, timeout){
	if (timeout == '' || timeout == undefined) timeout = 0;
	setTimeout(function(){
		$(el).stop().animate(obj, tempo, animacao);
	}, timeout);
}

function applyCarouselChange(quantidade){
	return false;
	inicio = 1;
	aux = 1;
	proximo = 2;

	if (intervalo != '' && intervalo != undefined) clearInterval(intervalo);

	intervalo = setInterval(function(){
		var ua = window.navigator.userAgent;
    	var msie = ua.indexOf("MSIE ");

    	/*
	    if (msie > 0)
	    {
	    } else {
			elAnimation('.banner-'+inicio, {opacity:0}, 500, 'easeInOutQuad');
			elAnimation('.banner-'+proximo, {opacity:1}, 500, 'easeInOutQuad', 500);
	    }
	    */

		$('.banner-'+inicio).fadeOut();
		$('.banner-'+proximo).fadeIn(500);

		$('.banner-'+inicio).removeClass('ativo');
		$('.banner-'+proximo).addClass('ativo');

		aux++;
		if (aux == quantidade) aux = 0;

		inicio = proximo;
		proximo = aux + 1;
	}, 7000);
}

function preloadImage(url){
    var img = new Image();
    img.src=url;
}

function gtag_report_conversion(sendTo, url) {
	console.log('gtag', url);

	var callback = function (data) {
		console.log(data);
		if (typeof(url) != 'undefined') {
			window.location.href = url;
		}
	};
	gtag('event', 'conversion', {
		'send_to': sendTo,
		'event_callback': callback
	});
	return false;
}

$(function () {
	$(window).load(function(){
		$(window).scroll(function () {
			var st = $(this).scrollTop() + ($(this).height());
			$('.reveal, .revealX, .revealXR').each(function () {
				if ($(this).offset().top <= st) {
					var delay = $(this).data('delay') ? $(this).data('delay') : 0;
	
					setTimeout(() => {
						let self = $(this);
					$(this).addClass('reveal--in');
					setTimeout(() => {
						self.removeClass('reveal--in reveal revealX revealXR');
				}, 1300)
				}, delay);
				}
			});
		});
		$(window).trigger('scroll');
	})
	
	});