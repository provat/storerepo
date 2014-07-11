jQuery(function($){

	/*jQuery.load_project_images = function(){     
    
    portfolio_id=$("#details_page_project_id").val();
         
    $.getJSON( myAjax.ajaxurl,{action:'display_projects_images', project_id: portfolio_id})
           .done(function(resault,statusTxt,xhr) {
           	$("#project_details_image_section_id").html(resault.img_content);
           	$(".group1").colorbox({rel:'group1'});
            $(".group1").colorbox({maxWidth:'95%', maxHeight:'95%'});
           });
     }; */

/*

	$(document).on("click",".filter_tags li a", function(event){
		//event.preventDefault();
		var name=$(this).text();
		if(name=="All")
			name="";	
	
		portfolio_id = $("#details_page_project_id").val();
	
		$.getJSON( myAjax.ajaxurl,{action:'display_projects_images', project_id: portfolio_id, terms_name:name })
	   .done(function(resault,statusTxt,xhr) {
		   $("#project_details_image_section_id").html(resault.img_content);
		   $(".group1").colorbox({rel:'group1'});
		   $(".group1").colorbox({maxWidth:'95%', maxHeight:'95%'});
	   });
	
	});*/

	$(document).on("click",".add_to_my_wishlist  a", function(event){
	  event.preventDefault();
	  var image_id=$(this).attr("image_id");
	  var project_id=$(this).attr("project_id");
	  //alert(image_id);
	  //alert(project_id);
	
	  var data = {
	                action: 'add_to_my_wishlist',
	                image_id: image_id,
	                project_id: project_id
	                
	            };
	
	
	      $.ajax({
	                url:myAjax.ajaxurl,
	                data: data,
	                type:"post",
	                success: function( msg ){
	                resault= $.parseJSON(msg);
	                if(resault.msg=="login"){
	                   _ajax_login_settings.redirect=resault.llink;
	                  $('#ajax-login-register-login-dialog').dialog('open');           
	                  var data = {
	                    action: 'load_template',
	                    referer: 'login_form',
	                    template: 'login-form',
	                    security: $('#ajax-login-register-login-dialog').attr('data-security')
	                  };
	
	                  $.ajax({
	                  data: data,
	                  success: function( msg ){
	                    $( "#ajax-login-register-login-target" ).fadeIn().html( msg ); // Give a smooth fade in effect
	                    // initiallize validation
	                    //login_form_validate_calback();
	
	                    
	                  }
	                  });
	
	
	                }else{
	                  var wish_list_bbp_obj="#add_wishlist_bbn"+resault.image_id;
	                  $(wish_list_bbp_obj).text(resault.msg);
	                  $(wish_list_bbp_obj).addClass("wishlist_success");
	                }
	
	         
	                }
	            });
	
	
	});
	
	
	/**
	 * Add to wishlist for lightbox
	 */
	$(document).on("click",".add_to_my_wishlist_lb  a", function(event){
		  event.preventDefault();
		  var image_id=$(this).attr("image_id");
		  var project_id=$(this).attr("project_id");
		  //alert(image_id);
		  //alert(project_id);
		
		  var data = {
		                action: 'add_to_my_wishlist_lb',
		                image_id: image_id,
		                project_id: project_id
		                
		             };		
		
		  $.ajax({
			  url:myAjax.ajaxurl,
		      data: data,
		      type:"post",
		      success: function( msg ) {
			      resault = $.parseJSON(msg);
			      if(resault.status == "error") {
			    	  $("#lb_msg").html("<font color='red'>Please login first, then try.</font>");
			    	  
			
			      } else {
		              var wish_list_bbp_obj = "#add_wishlist_bbn_lb";
		              $(wish_list_bbp_obj).text(resault.msg);
		              $(wish_list_bbp_obj).addClass("wishlist_success");
			      }	
		         
		      }
		  });		
		
	});
	//$(".remove_from_wl").on("click", function () {
	/*$(document).on("click", ".remove_from_wl  a", function(event){
		event.preventDefault();
	    var img_id = $(this).attr("image_id");
	    var prj_id = $(this).attr("project_id");
	    console.log(img_id+'-'+prj_id);
	});*/
/*Several Address Starts*/
$(document).on("click",".several_address", function(event){
event.preventDefault();	
var response = $('.main-div-several-address').html();
//$('.main-div-several-address').css('display','block');
console.log(response);
var popup_length = $('several_address_param').length;
console.log(popup_length);
var max_height = 240 * popup_length;
$.colorbox({title:'Response',width:'700',height:max_height,html:response});
});
/*Several Address Ends*/
var locations = [];
var loopcounter = $(".map-hidden").length;
$(".map-hidden").each(function(index, value) { 
	loopcounter--;
    console.log('div' + index + ':' + $(this).find('.lat').attr('value')); 
    locations.push([$(this).find('.address').attr('value'),$(this).find('.lat').attr('value'),$(this).find('.long').attr('value'),loopcounter])
});
var register_lat = $('.reg_geolocation').attr('data-lat');
var register_long = $('.reg_geolocation').attr('data-long');
console.log(register_lat+"/"+register_long);
console.log(locations);
function initializeMap()
{

	var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    center: new google.maps.LatLng(register_lat, register_long),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                  });

                  var infowindow = new google.maps.InfoWindow();

                  var marker, i;

                  for (i = 0; i < locations.length; i++) {  
                    marker = new google.maps.Marker({
                      position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                          draggable:true,
    					  animation: google.maps.Animation.DROP,
                      map: map
                    });

                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                      return function() {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                      }
                    })(marker, i));
                  }


}
/*Function Map Loading For Ipad*/
function initializeIpadMap()
{

	var map = new google.maps.Map(document.getElementById('mapIpad'), {
                    zoom: 10,
                    center: new google.maps.LatLng(register_lat, register_long),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                  });

                  var infowindow = new google.maps.InfoWindow();

                  var marker, i;

                  for (i = 0; i < locations.length; i++) {  
                    marker = new google.maps.Marker({
                      position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                          draggable:true,
    					  animation: google.maps.Animation.DROP,
                      map: map
                    });

                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                      return function() {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                      }
                    })(marker, i));
					  google.maps.event.addDomListener(window, 'resize', function() {
					    map.setCenter(myLatlng);
					  });
                  }


}
/*Function Map Loading For Ipad*/

function toggleBounce() {

  if (marker.getAnimation() != null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}



/*Map Ends*/
					
/*Map Shows As Popup In Ipad Landscape */

	/*var deviceAgent = navigator.userAgent.toLowerCase();
	//$('.main-div-several-address').append("<span>"+deviceAgent+"</span>");
	console.log(deviceAgent);
    var agentID = deviceAgent.match(/(iphone|ipod|ipad)/);
    if (agentID) 
    {
    //  alert("mvndkj");
    var response_map = $(".porfolio_map").html();
      $.colorbox({title:'Response',width:'700',height:max_height,html:response_map});
          	$('.main-div-several-address').append("<span>Mobile</span>");

    }
    else
    {
    	$('.main-div-several-address').append("<span>PC</span>");
    }*/


    var isiPad = /ipad/i.test(navigator.userAgent.toLowerCase());
    var isiPod = /ipod/i.test(navigator.userAgent.toLowerCase());    
     var isiPhone = /iphone/i.test(navigator.userAgent.toLowerCase());
     var isAndroid = /android/i.test(navigator.userAgent.toLowerCase());

		if (isiPad)
		{
			
			$('.porfolio_map').css('display','none');
			$('.map_popup').append("<a class = 'view_map' style = 'float:left'><span>See Map</span></a>");
			$(document).on("click",".view_map", function(event){
		      initializeIpadMap();
		      $('.porfolio_map_ipad').css('display','block');
		      $('.view_map').colorbox({open:false, href:'div#mapIpad', inline:true,width:'700',height:'700',onCleanup:function(){
                   
                   // $('.porfolio_map_ipad').css('display','none');
                     //hides the content div when the lightbox closes
                 }});
		    });
			/* var response_map = $(".map_main").clone();
     		$.colorbox({title:'Response',width:'700',height:'300',html:response_map}); 
			$('.main-div-several-address').append("<span>"+response_map+"</span>");*/

			$('.gm-style-iw').css({ "width": "300px", "min-height": "300"});			
		}
		else if (isiPod)
		{
			$('.porfolio_map').css('display','none');
			$('.map_popup').append("<a class = 'view_map' style = 'float:left'><span>See Map</span></a>");
			$(document).on("click",".view_map", function(event){
		      initializeIpadMap();
		      $('.porfolio_map_ipad').css('display','block');
		      $('.view_map').colorbox({open:false, href:'div#mapIpad', inline:true,width:'350',height:'350',onCleanup:function(){
                   
                   // $('.porfolio_map_ipad').css('display','none');
                     //hides the content div when the lightbox closes
                 }});
		   });
			/* var response_map = $(".map_main").clone();
     		$.colorbox({title:'Response',width:'700',height:'300',html:response_map}); 
			$('.main-div-several-address').append("<span>"+response_map+"</span>");*/
		}
		else if (isiPhone)
		{
			
			$('.porfolio_map').css('display','none');
			$('.map_popup').append("<a class = 'view_map' style = 'float:left'><span>See Map</span></a>");
			$(document).on("click",".view_map", function(event){
		      initializeIpadMap();
		      $('.porfolio_map_ipad').css('display','block');
		      $('.view_map').colorbox({open:false, href:'div#mapIpad', inline:true,width:'350',height:'350',onCleanup:function(){
                   
                   // $('.porfolio_map_ipad').css('display','none');
                     //hides the content div when the lightbox closes
                 }});
		   });
			/* var response_map = $(".map_main").clone();
     		$.colorbox({title:'Response',width:'700',height:'300',html:response_map}); 
			$('.main-div-several-address').append("<span>"+response_map+"</span>");*/
		}
		else if (isAndroid)
		{
			
			$('.porfolio_map').css('display','none');
			$('.map_popup').append("<a class = 'view_map' style = 'float:left'><span>See Map</span></a>");
			$(document).on("click",".view_map", function(event){	
		      initializeIpadMap();
		      $('.porfolio_map_ipad').css('display','block');
		      $('.view_map').colorbox({open:false, href:'div#mapIpad', inline:true,width:'350',height:'350',onCleanup:function(){
                   
                   // $('.porfolio_map_ipad').css('display','none');
                     //hides the content div when the lightbox closes
                 }});
		   });
		   /* });*/
			/* var response_map = $(".map_main").clone();
     		$.colorbox({title:'Response',width:'700',height:'300',html:response_map}); 
			$('.main-div-several-address').append("<span>"+response_map+"</span>");*/
		}
		else
		{

		/*$('.porfolio_map').css('display','none');
			$('.map_popup').append("<a class = 'view_map' style = 'float:left'><span>Click To View Map</span></a>");
			$(document).on("click",".view_map", function(event){
		      initializeIpadMap();
		      $('.porfolio_map_ipad').css('display','block');
		      $('.view_map').colorbox({open:false, href:'div#mapIpad', inline:true,width:'600',height:'600',onCleanup:function(){
                   
                   // $('.porfolio_map_ipad').css('display','none');
                     //hides the content div when the lightbox closes
                 }});
		   });*/
		initializeMap();
		}
/*Map Shows As Popup In Ipad Landscape Ends*/

/*		var maxHeight = 0;
			jQuery(".panelImageMap").each(function(){
			   if ($(this).height() > maxHeight) { maxHeight = $(this).height();
                 }

			});

			jQuery(".panelImageMap").height(maxHeight);*/





/*	Device Media Query */

if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 		/*code */

 	
}

/*-------------------*/

});	







function removeFromWl(a) {
	alert(a);
}