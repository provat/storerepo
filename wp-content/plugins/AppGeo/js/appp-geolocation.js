window.geo_object = window.geo_object || { ajax_url : apppCore.ajaxurl };

(function(window, document, $, undefined) {

	window.AppGeo       = {};
	var shortcode_fired = false;
	AppGeo.ajaxloaded   = false;
	AppGeo.get          = navigator.geolocation;

	AppGeo.geoLocate_post = function() {

		// Only do it once
		if ( shortcode_fired === true && ! AppGeo.ajaxloaded )
			return;

		AppGeo.get.getCurrentPosition( AppGeo.onSuccessGeoPost, AppGeo.onErrorGeo );

		// check every 15 seconds
		setInterval( function(){
			AppGeo.get.getCurrentPosition( AppGeo.onSuccessGeoPost, AppGeo.onErrorGeo )
		},15000);

		shortcode_fired   = true;
		AppGeo.ajaxloaded = false;
	};

	AppGeo.onSuccessGeoPost = function( position ) {
		// Make sure our geolocation form is available
		if ( document.getElementById('appp_longitude') === null || ! position )
			return;

		AppGeo.position = position;

		apppresser.log('onSuccessGeoPost position',position);

		var element = null;
		// Coordinate parameters to map to the dom elements
		var elements = [
			'longitude',
			'latitude',
			'altitude',
			'accuracy',
			'altitudeAccuracy',
			'heading',
			'speed',
			'timestamp'
		];
		var count = elements.length - 1;
		// Loop through the parameters and add the values to the corresponding dom element
		while ( count >= 0 ) {
			element = document.getElementById( 'appp_' + elements[count].toLowerCase() );
			// Make sure the element exists
			if ( element ) {
				// Set its value
				element.value = position.coords[ elements[count] ];
			}
			count--;
		};

		var $map = $('#appp_map_preview_img');

		// if map preview
		if ( position && $map.length ){
			$map.attr( 'src', 'http://maps.googleapis.com/maps/api/staticmap?zoom=17&size=600x300&maptype=roadmap&markers=color:red%7Ccolor:red%7Clabel:%7C' + position.coords[ 'latitude' ] + ',' + position.coords[ 'longitude' ] + '&sensor=false' );
		}

	};

	AppGeo.onErrorGeo = function(error) {
		if ( typeof apppCore.log === 'function' )
			apppCore.log( 'code: '+ error.code +'\n'+'message: '+ error.message +'\n' );
	};

	// store location data for user
	AppGeo.geoLocate_user = function() {
		navigator.geolocation.getCurrentPosition( AppGeo.onSuccessGeoUser, AppGeo.onErrorGeo );
	};

	AppGeo.onSuccessGeoUser = function(position) {

		var url = geo_object.ajax_url;
		var cookie = apppCore.ReadCookie( 'Appp_Geolocation' );

		if ( ! url || cookie )
			return;

		$.ajax({
			type: 'POST',
			dataType: "json",
			url: url,
			data: {
				'action': 'appp_geo_user',
				'longitude': position.coords.longitude,
				'latitude': position.coords.latitude
			},
			success: function( response ) {
				apppresser.log('onSuccessGeoUser response',response);
				//alert(response.data);
			}
		});
	};

	AppGeo.init = function() {

		if ( null !== document.getElementById('appgeo-geolocate-post-trigger') ) {
			AppGeo.geoLocate_post();
		}
		if ( null !== document.getElementById('appgeo-geolocate-user-trigger') ) {
			AppGeo.geoLocate_user();
			// check every minute for new location
			setInterval( function(){ AppGeo.geoLocate_user() }, 60000 );

		}
	};

	$(document).ready( AppGeo.init ).bind( 'load_ajax_content_done', function() {
		AppGeo.ajaxloaded = true;
		AppGeo.init();
	});

})(window, document, jQuery);
