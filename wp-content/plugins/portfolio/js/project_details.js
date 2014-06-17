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
	
	});

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
});	

function removeFromWl(a) {
	alert(a);
}