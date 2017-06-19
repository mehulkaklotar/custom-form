jQuery(document).ready(function(){

	if ( jQuery('#cform_submit').length ) {
		jQuery.getJSON('//freegeoip.net/json/?callback=?', function(data) {

			jQuery.getJSON('http://api.timezonedb.com/v2/get-time-zone?key=43H3IJ2V22TJ&format=json&by=zone&zone='+data.time_zone, function(res) {
				jQuery('#cform_time').val(res.formatted);
			});


		});
	}

	jQuery('#cform').submit(function(e){
		e.preventDefault();

		if( jQuery('#cform_time').val() == '' ) {
			return false;
		}

		var data = jQuery(this).serialize();
		jQuery.ajax({
			type:    "POST",
			url:     cform_vars.ajaxURL,
			data:    data,
			success: function(data) {
				jQuery('#cform').html('Your form has been submitted successfully');
			}
		});

	});

	return false;

});