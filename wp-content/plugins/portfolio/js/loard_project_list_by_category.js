jQuery(function($){
    var loading  = false; //to prevents multipal ajax loads
   
     
 
$(window).scroll(function() {
   if($(window).scrollTop() + $(window).height() > $(document).height()-3) {
       //$(window).unbind('scroll'); 
      
       	//alert("test");
        load_project_by_category();

   }
});


function load_project_by_category(){
        
        var page= $("#page_no").val();
        var category_id=$("#category_id").val();
        
     
      
        var data = {
                action: 'display_projects_by_category',
                page: page,
                category_id: category_id,
                                
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