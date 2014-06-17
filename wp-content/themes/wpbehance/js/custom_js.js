jQuery(document).ready(function($){
$(".trimed_content .open_read_more").click(function(){
     
      if($(this).text()=="Close"){
           $(".rest_text_of").remove();
           $(this).text("Read More..");
       }else{
           var loding_section=$(this).closest('div');
           $(this).before("<span class='rest_text_of'>"+$(".left_trimed_content").html()+"</span>");
           $(this).text("Close");
       } 
       
      //$(".left_trimed_content").toggle();
    });



  $(".group1").colorbox({rel:'group1'});
  $(".group1").colorbox({maxWidth:'95%', maxHeight:'95%'});

  $(".location_form #search_country_list").change(function(){
  //alert("test");
  var country_code=$(this).val();
  var data = {
                action: 'get_states_for_search',
                country_code: country_code,
                                
            };
            
   $.ajax({
            url:myAjax.ajaxurl,
            data: data,
            type:"post",
            success: function( msg ){
                $(".location_form #view_state_list_dropdown").html(msg);
                
                 
            }
        });         
  
  });
  
  
  $(".location_form #s_by_location_apply").click(function(){
     
     var country=$(".location_form #search_country_list").val();
     
     //if(country=="")
         //return false;
     
     var state=$(".location_form #s_state").val();
     if(state=='undefined')
         state="";
     
     var city=$(".location_form #s_country_city").val();

     var category = $("#hdnCatId").val();
     //var url=mySiteUrl.siteUrl+"/location/"+country+"?state="+state+"&city="+city;
	
	if(category!='')
	var url=mySiteUrl.siteUrl+"/search/?category="+category+"&country="+country+"&state="+state+"&city="+city;
	else
	var url=mySiteUrl.siteUrl+"/search/?country="+country+"&state="+state+"&city="+city;

	if(country == '')
	{
	var url= url+"&allcon=true";
	}
     $(location).attr('href',url);
     
  });


  var country_code =$("#hdnCountryId").val();
  var state_id =$("#hdnStateId").val();
  if(country_code!='')
  {
  	var data = {
                action: 'get_states_for_search',
                country_code: country_code,
                state_id: state_id                
            };
            
   	$.ajax({
            url:myAjax.ajaxurl,
            data: data,
            type:"post",
            success: function( msg ){
                $(".location_form #view_state_list_dropdown").html(msg);
                
                 
            }
        }); 
   }    

});

function storeNear()
{
	window.location.href=mySiteUrl.siteUrl+'?mode=storenear';
}


