jQuery(function($){

    if(share_loc=='true')
    getPosition('location');
    else 
    getPosition(''); 
    //fail();
    
    
 var loading  = false; //to prevents multipal ajax loads
$(window).scroll(function() {
   if($(window).scrollTop() + $(window).height() > $(document).height()-3) {
       //$(window).unbind('scroll'); 
       
       	//alert("test");
        loadMoreContent();

   }
});

    
function loadMoreContent()
{ 
       
      
       var long = $("#curr_long").val();
       var lat=$("#curr_lat").val();
       
            
    
	var page= $("#page_no").val();
	//alert("page_val:"+ page);
        //alert("long:"+long);
        //alert("latu:"+lat);
        //alert("Last Page: "+ $("#last_page").val());
        
        //if(long!="" && lat!=""){
          
         var data = {
                action: 'display_projects_accor_location',
                page: page,
                latitude: lat,
                longitude: long
                                
            };
        
        /*}else{
        var data = {
                action: 'display_projects_accor_date',
                page: page,
                                
            };
        } */  
        
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


function getPosition(mode) { 
    if(mode=='location-other')
    window.location.href=mySiteUrl.siteUrl+'?storenear=true';
    jQuery( "#projectDiv" ).html( '<span class="loader_img"></span>' );

    if(mode == '')
    {
    	success('');
    }
    else{
	    navigator.geolocation.getCurrentPosition(success, fail,{
		enableHighAccuracy:true,
		timeout:27000,
		maximumAge:Infinity
	    });  
    }
}   

function success(position) {

jQuery( "#projectDiv" ).html( '<span class="loader_img"></span>' );

if(position=='')
{
 var data = {
                action: 'display_projects_accor_location',
                latitude: '',
                longitude: ''
                
            };
	
	jQuery("#curr_long").val('');
	jQuery("#curr_lat").val('');
}
else{
 var data = {
                action: 'display_projects_accor_location',
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
                
            };
	
	jQuery("#curr_long").val(position.coords.longitude);
	jQuery("#curr_lat").val(position.coords.latitude);
}


    
jQuery.ajax({
            url:myAjax.ajaxurl,
            data: data,
            type:"post",
            success: function( msg ){
                resault= jQuery.parseJSON(msg);
                jQuery("#projectDiv").html(resault.content); 
                 var mypage = parseInt(resault.paged) + 1;
                 jQuery("#page_no").val(mypage);
                 jQuery("#last_page").val(resault.lpage);
            }
        });        


}

function fail(e) {
    //alert("Your ");
    jQuery( "#projectDiv" ).html( '<span class="loader_img"></span>' ); 
   
 var data = {
                action: 'display_projects_accor_date'
                                
            };
            
            
jQuery.ajax({
            url:myAjax.ajaxurl,
            data: data,
            type:"post",
            success: function( msg ){
                 resault= jQuery.parseJSON(msg);
                 jQuery("#projectDiv").html(resault.content);
                 var mypage = parseInt(resault.paged) + 1;
                 jQuery("#page_no").val(mypage);
                 jQuery("#last_page").val(resault.lpage);
            }
        });
    
    
}
