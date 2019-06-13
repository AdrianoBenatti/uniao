/* Brazilian initialisation for the jQuery UI date picker plugin. */
/* Written by Rafael Moni. */
jQuery(function($){
	$.timepicker.regional['pt-BR'] = {
		currentText: 'Agora',
		closeText: 'Fechar',
		ampm: false,
		amNames: ['AM', 'A'],
		pmNames: ['PM', 'P'],
		timeFormat: 'hh:mm ss',
		timeSuffix: '',
		timeOnlyTitle: 'Horário',
		timeText: 'Horário',
		hourText: 'Hora',
		minuteText: 'Minuto',
		secondText: 'Segundo',
		millisecText: 'Millisegundo',
		timezoneText: 'Fuso Horário'
	};
	$.timepicker.setDefaults($.timepicker.regional['pt-BR']);
});