jQuery(function($){

	/*var loading  = false; //to prevents multipal ajax loads
	$(window).scroll(function() {
	    if($(window).scrollTop() + $(window).height() > $(document).height()-3) {       
	    	loadMoreContent2();
	    }
	});*/
	var long = $("#curr_long").val();
	var lat = $("#curr_lat").val();	
	var page = $("#page_no").val();
  
	var data = {
		action: 'appp_home_content',
		page: page,
		latitude: lat,
		longitude: long
	};

	if($("#last_page").val() == 2 && loading == false) {

		loading = true; //prevent further ajax loadin	
		$("#loadingDiv").show();
 
		$.ajax({
		    url: 'http://localhost/everystore/wp-admin/admin-ajax.php',
		    data: data,
		    type:"post",
		    success: function( msg ) {
			    resault = jQuery.parseJSON(msg);
			    $("#loadingDiv").hide();              
			    jQuery("#projectDiv").append(resault.content);
			    var mypage = parseInt(resault.paged) + 1;
			    $("#page_no").val(mypage);
			    $("#last_page").val(resault.lpage);
	            loading=false;	                 
            }
        });
        
	}  
    
	function loadMoreContent2()
	{ 
		var long = $("#curr_long").val();
		var lat = $("#curr_lat").val();	
		var page = $("#page_no").val();
	  
		var data = {
			action: 'appp_home_content',
			page: page,
			latitude: lat,
			longitude: long
		};
	
		if($("#last_page").val() == 2 && loading == false) {
	
			loading = true; //prevent further ajax loadin	
			$("#loadingDiv").show();
	 
			$.ajax({
			    url: 'http://localhost/everystore/wp-admin/admin-ajax.php',
			    data: data,
			    type:"post",
			    success: function( msg ) {
				    resault = jQuery.parseJSON(msg);
				    $("#loadingDiv").hide();              
				    jQuery("#projectDiv").append(resault.content);
				    var mypage = parseInt(resault.paged) + 1;
				    $("#page_no").val(mypage);
				    $("#last_page").val(resault.lpage);
		            loading=false;	                 
	            }
	        });
	        
		}  
	}    
});