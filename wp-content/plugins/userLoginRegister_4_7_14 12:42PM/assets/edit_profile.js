jQuery(function($){
    
     $(".tab_box #edit_profile").click(function(){
        //if($(this).hasClass("active")){
            $(".tab_box #project_follows").removeClass("active");
        //}
            
            alert("ankan");
            var data = {
                action: 'load_edit_profile_template',
               
                template: 'edit_profile_form',
               
                
                
            };

            $.ajax({
                data: data,
                success: function( msg ){
                    $( "#my_profile_content" ).html( msg ); // Give a smooth fade in effect
                                        
                }
            });
            
            
        
    });
    
    
    
    
});