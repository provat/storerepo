window.ats_object = window.ats_object || { ajax_url : apppCore.ajaxurl };

(function(window, document, $, undefined) {
	var url = ats_object.ajax_url;
	var pageID = ats_object.pageID;
	window.AppTest = {};
	AppTest.ajaxloaded   = false;
	
	AppTest.testCall = function(e) {		
		//console.log("Hurray");
		if(pageId == 2) {
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'ats_load_home',
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$("#content").html(response.content);
					//alert(response.data);
				}
			});
			//console.log("Hurray");
			window.pageId = 0;
		}
		
		
	};

	AppTest.init = function() {		
		AppTest.testCall();
	};
	
	$(document).ready( AppTest.init ).bind( 'load_ajax_content_done', function() {
		AppTest.ajaxloaded   = true;
		AppTest.init();
	});
	
	
	
	/**
	 * Suddha's work
	 */
	$(document ).on("click",".portfolio,.store-near-me,.followed-stores,.wishlist,.get-the-apps,.about-us,.advertise-here,.terms-and-conditions,.contact-us", function(event){
		var Pid = $(this).data('pageid');

		if(Pid==1)
		{
			$(".ajax-spinner").css('display','block');
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'ats_load_home',
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}else if(Pid==2)
		{
			$(".ajax-spinner").css('display','block');			
			if(navigator.geolocation){
		      // timeout at 60000 milliseconds (60 seconds)
		      var options = {timeout:60000};
		      navigator.geolocation.getCurrentPosition(showLocation, 
		                                               errorHandler,
		                                               options);
		   	}
		}
		else if(Pid==3)
		{
			$(".ajax-spinner").css('display','block');		
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'profile',
					'tab' : '1'
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					console.log(response);
					$(".ajax-spinner").css('display','none');
					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}
		else if(Pid==4)
		{
			$(".ajax-spinner").css('display','block');		

			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'profile',
					'tab' : '2'
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}
				else if(Pid==6)
		{
			$(".ajax-spinner").css('display','block');		

			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'get_the_apps'
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}
		else if(Pid==7)
		{
			$(".ajax-spinner").css('display','block');		

			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'about_us'
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}
		else if(Pid==8)
		{
			$(".ajax-spinner").css('display','block');		

			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'advertise_here'
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}
		else if(Pid==9)
		{
			$(".ajax-spinner").css('display','block');		

			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'terms_and_conditions'
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}
		else if(Pid==10)
		{
			$(".ajax-spinner").css('display','block');		

			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'contact_us'
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}
		else
		{

		}


	});

	function showLocation(position) {

  			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					  action: 'display_projects_accor_location',
                 	  latitude: position.coords.latitude,
	                  longitude: position.coords.longitude
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');
					$("#content").html(response.content);
					//alert(response.data);
				}
			});
}


function errorHandler(err) {
  if(err.code == 1) {
    alert("Error: Access is denied!");
  }else if( err.code == 2) {
    alert("Error: Position is unavailable!");
  }
}


	/**
	 * Store - Near - Me
	 */




	/**
	 * Store Followed By Default
	 */




	 /*---- Page Wise ----- */
	$(document ).on("click",".followed-page,.wishlist-page,.remove_from_wl,.edit-page", function(event){
			var Pid = $(this).data('pageid');

		if(Pid==1)
		{
			$(".ajax-spinner").css('display','block');
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'profile',
					'tab' : '1'
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}else if(Pid==2)
		{
			$(".ajax-spinner").css('display','block');		

			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'profile',
					'tab' : '2'
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}
		else if(Pid==2.1){

			var img_id = $(this).attr("image_id");
      		var prj_id = $(this).attr("project_id");
    
		    $.ajax({
		          url:url,
				  type: 'POST',
				  dataType: "json",
				  data : {
		              action: 'delete_from_my_wishlist',
		              image_id: img_id,
		              project_id: prj_id            
		          },
		          success: function(data){
		            result = $.parseJSON(data);
		                if(result.msg== "ok")           
		              //window.location.href = 'http://everystore.co/myprofile/?mode=wishlist';
		              $("#add_wishlist_bbn"+img_id).html("Removed");
		            if(result.msg == 'error')
		              alert('There may be some error');
		          }
		      });
		}
		else if(Pid==3)
		{
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'profile',
					'tab' : '3'
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}
		else
		{

		}

	});

    /* ---- Page Wise Ends ---- */

	$(document ).on("click",".followed-page,.wishlist-page,.remove_from_wl,.edit-page", function(event){
			var Pid = $(this).data('pageid');

		if(Pid==1)
		{
			$(".ajax-spinner").css('display','block');
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'profile',
					'tab' : '1'
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}else if(Pid==2)
		{
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'profile',
					'tab' : '2'
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}
		else if(Pid==2.1){
			var img_id = $(this).attr("image_id");
      		var prj_id = $(this).attr("project_id");
    
		    $.ajax({
		          url:url,
				  type: 'POST',
				  dataType: "json",
				  data : {
		              action: 'delete_from_my_wishlist',
		              image_id: img_id,
		              project_id: prj_id            
		          },
		          success: function(result){
          
		              $("#add_wishlist_bbn"+img_id).html("Removed");

		          }
		      });
		}
		else if(Pid==3)
		{
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'profile',
					'tab' : '3'
					/*'longitude': position.coords.longitude,
					'latitude': position.coords.latitude*/
				},
				success: function( response ) {
					//console.log(response);
					//var result = JSON.parse(response);
					//var result = $.parseJSON( response );
					//apppresser.log('onSuccessGeoUser response',response);
					$(".ajax-spinner").css('display','none');

					$("#content").html(response.content);
					//alert(response.data);
				}
			});
		}
		else
		{

		}

	});


/* Menu Wise */

})(window, document, jQuery);