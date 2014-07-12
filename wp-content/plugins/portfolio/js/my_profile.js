jQuery(function($){
    var loading  = false; //to prevents multipal ajax loads
    var click_on_portfolio=1;
    load_project_follow();
    
    
if(click_on_portfolio==1){  
$(window).scroll(function() {
   if($(window).scrollTop() + $(window).height() > $(document).height()-3) {
       //$(window).unbind('scroll'); 
      
       	//alert("test");
        load_project_follow();

   }
});
}
    
    
    $(".tab_box #project_follows").click(function(event){
        //if($(this).hasClass("active")){
            click_on_portfolio=1;
            event.preventDefault();
            $(".tab_box #edit_profile").removeClass("active");
            $(".tab_box #project_wishlist").removeClass("active");
            $(this).addClass('active');
        //}
          $("#page_no").val(1);
          $("#last_page").val(2);
          jQuery("#my_profile_content").html("");
         load_project_follow();
    });
    

    $(".tab_box #project_wishlist").click(function(event){
        event.preventDefault();
        click_on_portfolio=2;
        $(this).addClass('active');
        $(".tab_box #edit_profile").removeClass("active");
        $(".tab_box #project_follows").removeClass("active");


        var data = {
                action: 'display_my_wishlist',
                
                                
            };

        
        $("#loadingDiv").show();
       
        $.ajax({
            url:myAjax.ajaxurl,
            data: data,
            type:"post",
            success: function( msg ){
                
                 resault= jQuery.parseJSON(msg);
                  $("#loadingDiv").hide();              
                 jQuery("#my_profile_content").html(resault.content);
                 
                 
            }
         });


       }); 

    
    function load_project_follow(){
      
        var page= $("#page_no").val();
      
        var data = {
                action: 'display_follow_projects',
                page: page,
                                
            };

          //alert($("#last_page").val()); 

       if($("#last_page").val()==2 && loading==false){
        
        loading = true; //prevent further ajax loadin    
        
         $("#loadingDiv").show();
       
        $.ajax({
            url:myAjax.ajaxurl,
            data: data,
            type:"post",
            success: function( msg ){
                
                 resault= jQuery.parseJSON(msg);
                  $("#loadingDiv").hide();              
                 jQuery("#my_profile_content").append(resault.content);
                 if(resault.content!=""){
                 var mypage = parseInt(resault.paged) + 1;
                 $("#page_no").val(mypage);
                 }
                 $("#last_page").val(resault.lpage);
                
                 loading=false;
                 
            }
        });
     } 
        
    }
    
    
    
     $(".tab_box #edit_profile").click(function(event){
        //if($(this).hasClass("active")){
        click_on_portfolio=2;
        event.preventDefault();
            $(".tab_box #project_follows").removeClass("active");
            $(".tab_box #project_wishlist").removeClass("active");
            $(this).addClass('active');
        //}
            
           $("#loadingDiv").show();
            var data = {
                action: 'load_edit_profile_template',
               
                template: 'edit_profile_form',
               
                
                
            };

            $.ajax({
                url:myAjax.ajaxurl,
                data: data,
                type:"post",
                success: function( msg ){
                     $("#loadingDiv").hide();    
                    $( "#my_profile_content" ).html( msg ); // Give a smooth fade in effect
                    update_profile_validate_calback();
                                        
                }
            });
            
            
        
    });
    
    
    function update_profile_validate_calback(){
     
       $("form#edit_my_profile").validate({
            rules: {
                
                first_name: {required: true },     
		last_name: {required: true }, 
		profile_name: {required: true },    
		country: {required: true },
		city: { required: true}       
            },
            messages: {               //messages to appear on error
                first_name: {required:"Please enter your first name."},
		last_name: {required:"Please enter your last name."},
		profile_name: {required:"Please enter your profile name."},
		country: {required:"Please enter your country."},
		city: {required:"Please enter your city."}
            },
            submitHandler: function() {
                  //alert("aaa");
		
		$.ajax({
                    type: "POST",
                    url:myAjax.ajaxurl,
		    data: "action=update_my_profile&" + $( "form#edit_my_profile" ).serialize(),
		    dataType: 'json',
		    success: function( data ) {
                        
			$("#update_profile_msg").html( data.msg );
			
			
		    }
		});
	    	
          }
        });  

       
   }
    
   
   
    $( document ).on('submit', '#edit_profile', function( event ){
        event.preventDefault();
       
       update_profile_validate_calback();
                 
           
        
    });
    
});