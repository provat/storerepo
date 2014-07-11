jQuery(function($){
    var loading  = false; //to prevents multipal ajax loads
   
     
 
$(window).scroll(function() {
   if($(window).scrollTop() + $(window).height() > $(document).height()-3) {
       //$(window).unbind('scroll'); 
      
       	//alert("test");
        load_project_by_country();

   }
});


function load_project_by_country(){
        
        var page= $("#page_no").val();
        var countryCode=$("#countryCode").val();
        
     
      
        var data = {
                action: 'display_projects_accor_country',
                page: page,
                countryCode: countryCode,
                                
            };
          
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
                 jQuery("#project_list").append(resault.content);
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

});