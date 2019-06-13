$(document).ready(function(){
	//Date
	jQuery.fn.dataTableExt.oSort['date-asc']  = function(a,b) {
		var ukDatea = a.split('/');
		var ukDateb = b.split('/');
		
		var x = (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
		var y = (ukDateb[2] + ukDateb[1] + ukDateb[0]) * 1;
		
		return ((x < y) ? -1 : ((x > y) ?  1 : 0));
	};
	
	jQuery.fn.dataTableExt.oSort['date-desc'] = function(a,b) {
		var ukDatea = a.split('/');
		var ukDateb = b.split('/');
		
		var x = (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
		var y = (ukDateb[2] + ukDateb[1] + ukDateb[0]) * 1;
		
		return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
	};
	
	//Datetime
	function trim(str) {
        str = str.replace(/^\s+/, '');
        for (var i = str.length - 1; i >= 0; i--) {
                if (/\S/.test(str.charAt(i))) {
                        str = str.substring(0, i + 1);
                        break;
                }
        }
        return str;
	}

	function dateHeight(dateStr){
		if (trim(dateStr) != '') {
			var frDate = trim(dateStr).split(' ');
			var frTime = frDate[1].split(':');
			var frDateParts = frDate[0].split('/');
			var day = frDateParts[0] * 60 * 24;
			var month = frDateParts[1] * 60 * 24 * 31;
			var year = frDateParts[2] * 60 * 24 * 366;
			var hour = frTime[0] * 60;
			var minutes = frTime[1];
			var x = day+month+year+hour+minutes;
		} else {
			var x = 99999999999999999; //GoHorse!
		}
		return x;
	}

	jQuery.fn.dataTableExt.oSort['date-time-asc'] = function(a, b) {
        var x = dateHeight(a);
        var y = dateHeight(b);
        var z = ((x < y) ? -1 : ((x > y) ? 1 : 0));
        return z;
	};

	jQuery.fn.dataTableExt.oSort['date-time-desc'] = function(a, b) {
        var x = dateHeight(a);
        var y = dateHeight(b);
        var z = ((x < y) ? 1 : ((x > y) ? -1 : 0));
        return z;
	};

	//Decimal
	jQuery.fn.dataTableExt.oSort['numeric-comma-asc']  = function(a,b) {
		var x = (a == "-") ? 0 : a.replace( /,/, "." );
		var y = (b == "-") ? 0 : b.replace( /,/, "." );
		x = parseFloat( x );
		y = parseFloat( y );
		return ((x < y) ? -1 : ((x > y) ?  1 : 0));
	};
	
	jQuery.fn.dataTableExt.oSort['numeric-comma-desc'] = function(a,b) {
		var x = (a == "-") ? 0 : a.replace( /,/, "." );
		var y = (b == "-") ? 0 : b.replace( /,/, "." );
		x = parseFloat( x );
		y = parseFloat( y );
		return ((x < y) ?  1 : ((x > y) ? -1 : 0));
	};
	
	jQuery.fn.dataTableExt.aTypes.unshift(function(sData) {
		var sValidChars = "0123456789-,";
		var Char;
		var bDecimal = false;
	
		/* Check the numeric part */
		for (i = 0; i < sData.length; i++) {
			Char = sData.charAt(i);
			if (sValidChars.indexOf(Char) == -1) {
				return null;
			}
	
			/* Only allowed one decimal place... */
			if (Char == ",") {
				if (bDecimal) {
					return null;
				}
				bDecimal = true;
			}
		}
	
		return 'numeric-comma';
	});
	
	jQuery.fn.dataTableExt.aTypes.unshift(function(sData) {
		if (sData !== null && sData.match(/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20|21)\d\d$/)) {
			return 'date';
		}
		return null;
	});
	
	jQuery.fn.dataTableExt.aTypes.unshift(function(sData) {
		if (sData !== null && sData.match(/^\d{2}[- \/.]\d{2}[- \/.]\d{4}\s*?\d{2}[- :.]\d{2}[- :.]\d{2}$/)) {
			return 'date-time';
		}
		return null;
	});
	
});