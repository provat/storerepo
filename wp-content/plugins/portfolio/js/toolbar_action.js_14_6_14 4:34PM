jQuery(function($){
    
     var docheight=$(document).height();
     $('.back-loader').css({"height": docheight});
     $("#replaceImageFile").hide();
    
    if($(".user_project_id").val()=="")
        $("#continu_project").hide();
    
    // save projects
    
    $("#save_project").click( function(){
        var myAllimage = [];
       $( "#dynamic_port_folio_images ul li.grid" ).each(function( index ) {
          myAllimage.push(($( this ).attr('image_id')));
         console.log( index + ": " + $( this ).attr('image_id') );
       });
       $(".project_toolbar").append('<span class="loader_img"></span>');
       $("#continu_project").addClass("invijible_btn");
       $("#save_project").addClass("invijible_btn");
       portfolio_id=$(".user_project_id").val();
       security=$("#security_portfolio").val();
       project_title=$("#project_title_hidden_record").val();
       
        $.post( myAjax.ajaxurl, { 
            action: "save_portfolio",
            project_id: portfolio_id,
            project_title: project_title,
            image_id: myAllimage,
            security:security})
        .done(function( data ) {
           //alert( "Data Loaded: " + data );
           resault= $.parseJSON(data);  
           $(".user_project_id").val(resault.project_id);
           $(".project_toolbar .loader_img").remove();
           $("#continu_project").show();
           $("#continu_project").removeClass("invijible_btn");
           $("#save_project").removeClass("invijible_btn");
        });
        
    });
    
    
   // upload image on hover click
  $(".portfolio_upload_image_button .hover_upload_form").click(function(event){
      $("#upload_on_mouse_hove_in_editor").click();
             event.preventDefault();
  })
    
  //Delete image
  
  $(document ).on( 'click', '.projecteditor-icon-module-trash', function( event ){
        event.preventDefault();
        $('.back-loader').show();
        var image_id = $(this).parent(".image_editor_icon").attr("image_id");
        //alert(image_id);
        security_portfolio=security=$("#security_portfolio").val();
        
        $.post( myAjax.ajaxurl, { 
            action: "delete_media_image",
            image_id: image_id,
            security:security_portfolio})
        .done(function( data ) {
           resault= $.parseJSON(data);
            if(resault.deleted==1){
                remove_cls_name="#gridlist"+resault.image_id;
                $(remove_cls_name).remove();
            }
            $('.back-loader').hide();
           //alert( "Data Loaded: " + data );
        });
        
    });
    
    //replace image
    
     $(document ).on( 'click', '.projecteditor-icon-module-replace', function( event ){
        var image_id = $(this).parent(".image_editor_icon").attr("image_id");
        $("#replace_image_id").val(image_id);
        
        $('#replaceImageFile').click();
             event.preventDefault();
      });
      
    // create image caption dialog
    
    $(document ).on( 'click', '.projecteditor-icon-module-caption  ', function( event ){
        event.preventDefault();
        //alert("working");
        
        var image_id = $(this).parent(".image_editor_icon").attr("image_id");
        var dynamic_id="li#gridlist"+image_id;
        var dynamic_caption_editor_id="#caption_editor"+image_id;
        var append_obj=$('#dynamic_port_folio_images '+dynamic_id);
        var old_caption_text;

        //alert($("#caption_text"+image_id).length);
        if($("#caption_text"+image_id).length!=0)
          old_caption_text=$("#caption_text"+image_id).html();  
       else
          old_caption_text="";
      
        if($(dynamic_caption_editor_id).length==0){

            append_obj.append('<div class="caption_editor" id="caption_editor'+image_id+'"><div><textarea name="caption_edotor" id="caption_editor_content'+image_id+'"  class="caption_editor'+image_id+'"  style="width:100%">'+old_caption_text+'</textarea></div><div class="button_panel"><input type="button"  class="save_caption_btn" name="Save" Value="Save" /><input type="button" name="cancle" Value="Cancel" class="cancle_caption_btn"/></div></div>');
            
            //alert("caption_editor_content"+image_id);
          //  tinyMCE.execCommand('mceAddControl', false, "caption_editor_content"+image_id);
	  tinyMCE.init({
		mode : "specific_textareas",editor_selector : "caption_editor"+image_id,theme : "advanced",
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,undo,redo,|,forecolor,backcolor",
		theme_advanced_toolbar_location : "top",
        	theme_advanced_toolbar_align : "center",
		});

            $('html,body').animate({
                scrollTop:$(dynamic_caption_editor_id).offset().top-50
             });
            $("#caption_text"+image_id).remove(); 

        }/*else{
            $(dynamic_caption_editor_id).remove();
        
        }*/
        
        

    });  
    
    
     // save image caption
    
    $(document).on('click', '.save_caption_btn', function(event){
        var image_id = $(this).closest('li').attr("image_id");
        //alert(image_id);
        security=$("#security_portfolio").val();
        var loding_section=$(this).closest('div');
        var caption_editor_obj="caption_editor_content"+image_id;
        //alert(caption_editor_obj);
        //var caption_editor_content=$(caption_editor_obj).val(); 
        var caption_editor_content= tinyMCE.get(caption_editor_obj).getContent();
        if(caption_editor_content=="")
            return false;
        
        $(loding_section).append('<span class="loader_img"></span>');
        $(" .save_caption_btn").addClass("invijible_btn");
        $(" .cancle_caption_btn").addClass("invijible_btn");
        
        $.post( myAjax.ajaxurl, { 
            action: "save_portfolio_image_caption",
            image_id: image_id,
            img_caption: caption_editor_content,
            security:security})
        .done(function( data ) {
          
           resault = $.parseJSON(data);  
           var loding_section=$(this).closest('div');
           var caption_editor_obj="#caption_editor"+resault.image_id;
           var dynamic_id="li#gridlist"+resault.image_id;
           var append_obj=$('#dynamic_port_folio_images '+dynamic_id);
           
           $(" .save_caption_btn").removeClass("invijible_btn");
           $(" .cancle_caption_btn").removeClass("invijible_btn");
           
           $(caption_editor_obj).remove();
           var existing_cap_open_close_id = $("#ct"+resault.image_id);
           
           var extra_ele = '';
           var extra_ele2 = '';
           if(!existing_cap_open_close_id.length)
        	   extra_ele = '<div class="cpt_main"><span class="cptop" id="ct'+resault.image_id+'" divid="'+resault.image_id+'">Open Caption</span><br>';
           if(extra_ele != '')
        	   extra_ele2 = '</div>'; 
           $("#caption_text"+image_id).remove(); 
           $(append_obj).append(extra_ele + '<div class="caption_text" id="caption_text'+resault.image_id+'">'+resault.img_caption+"</div>" + extra_ele2);
           // alert(caption_editor_obj);
           
        });
        
    });
      
    // image caption cancle action
    
    $(document).on('click', '.cancle_caption_btn', function (event){
         var image_id = $(this).closest('li').attr("image_id");
         var dynamic_caption_editor_id="#caption_editor"+image_id;
         var dynamic_id="li#gridlist"+image_id;
           //caption_text=$('#caption_editor_content'+image_id).val();
           //var caption_text;
         $("#ct" + image_id).html("Open Caption");
         
         
         $(dynamic_caption_editor_id).remove();
         //if(caption_text!="")
         
         
         $.getJSON( myAjax.ajaxurl,{action:'get_portfolio_image_caption', image_id: image_id})
           .done(function(resault,statusTxt,xhr) {
              
              var dynamic_id="li#gridlist"+resault.image_id;
             if(resault.img_caption!="")
             $(dynamic_id).append('<div class="caption_text" id="caption_text'+image_id+'">'+resault.img_caption+"</div>");
         });
         
         $('html,body').animate({
                scrollTop:$(dynamic_id).offset().top
             });
         
    });   
      
      
   $(document).on("click",".button_panel .cancle_delete_project_btn, .button_panel .cancle_unpublish_project_btn", function(){
       $('#continu_project_dialog').dialog("close"); 
   });
   
   $(document).on("click",".button_panel .delete_project_btn", function(){
       project_id=$(this).attr("project_id");
       
       
       var data = {
                action: 'delete_project',
                referer: 'continu_project_dialog',
                project_id: project_id,
                security: $('#continu_project_dialog').attr('data-security')
            };
            
            $("#continu_project_dialog").append("<div class='overrite_layer'><span class='loader_img'></span></div>");
            
            $.ajax({
                url:myAjax.ajaxurl,
                data: data,
                type:"post",
                success: function( result3 ){
                    $(".overrite_layer").remove();
                    $('#continu_project_dialog').dialog("close");
                    var resault = $.parseJSON(result3);
                    var idname="#project_cover"+resault.project_id;
                    $(idname).remove();
                   
                }
            });
       
       
       
   });
   
   
   
   $(document).on("click",".button_panel .unpublish_project_btn", function(){
       project_id=$(this).attr("project_id");
      
       
       var data = {
                action: 'unpublish_project',
                referer: 'continu_project_dialog',
                project_id: project_id,
                security: $('#continu_project_dialog').attr('data-security')
            };
            
            $("#continu_project_dialog").append("<div class='overrite_layer'><span class='loader_img'></span></div>");
            
            $.ajax({
                url:myAjax.ajaxurl,
                data: data,
                type:"post",
                success: function( result3 ){
                    //$('#continu_project_dialog').dialog("close");
                    var resault = $.parseJSON(result3);
                    var idname="#project_cover"+resault.project_id;
                    //$(idname).remove();
                    //alert(resault.redirect_to);
                    window.location.href=resault.redirect_to
                   
                }
            });
       
       
       
   });

   
    //load project record   
   $.load_project_images();
});




jQuery(function($){
   
    jQuery.ConfirmDelete=function(event, project_id, project_title) {
     event.preventDefault();
  

    $('#continu_project_dialog').dialog("option","width","auto"); 
    $('#continu_project_dialog').dialog("open").siblings('.ui-dialog-titlebar').html("Delete Project");
    $("#continu_project_dialog #continu_project_dialog_target").html('<div>Do You Want to delete <b>"'+project_title+'"</b> ?</div><div class="button_panel"><input class="delete_project_btn" type="button" value="Delete" name="Save" project_id="'+project_id+'"><input class="cancle_delete_project_btn" type="button" value="Cancel" name="cancle"></div>');
     
    
  return false; //The actual submission of the form happens in the click handler for the delete button
  }   
  
   jQuery.UnpublishProject=function(event, project_id, project_title){
       
       event.preventDefault();
  

    $('#continu_project_dialog').dialog("option","width","auto"); 
    $('#continu_project_dialog').dialog("open").siblings('.ui-dialog-titlebar').html("Unpublish Project");
    $("#continu_project_dialog #continu_project_dialog_target").html('<div>Do You Want to Unpublish  <b>"'+project_title+'"</b> ?</div><div class="button_panel"><input class="unpublish_project_btn" type="button" value="Unpublish" name="Save" project_id="'+project_id+'"><input class="cancle_unpublish_project_btn" type="button" value="Cancel" name="cancle"></div>');
     
    
  return false;
       
   }
    
});
