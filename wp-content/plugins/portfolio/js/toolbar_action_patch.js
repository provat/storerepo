jQuery(function($){

	var sampleTags =["life","meaning","new tag","tag2","tag3","tqg4"];
	
	// create image tagging dialog   
	$.getJSON(myAjax.ajaxurl, {action:'load_all_tag_list'})
           .done(function(resault, statusTxt, xhr) {
        	   sampleTags = resault;
        	   //alert(sampleTags);
           });
             

	$(document ).on( 'click', '.projecteditor-icon-module-tagging  ', function( event ) {
		event.preventDefault();
        //alert("working");
        
        var image_id = $(this).parent(".image_editor_icon").attr("image_id");
        var project_id = $(".user_project_id").val();
        var dynamic_id = "li#gridlist" + image_id;
        var dynamic_tagging_editor_id = "#tagging_editor" + image_id;
        var append_obj = $('#dynamic_port_folio_images ' + dynamic_id);
        var old_tagging_text;
        
        //remove previous editor
        $(".tagging_editor").remove();
        
        if($("#tagging_text" + image_id).length != 0)
          old_tagging_text = $("#tagging_text"+image_id).html();  
        else
          old_tagging_text = "";
      
        if($(dynamic_tagging_editor_id).length == 0){
            
            append_obj.append('<div class="tagging_editor" id="tagging_editor' + image_id + '"></div>');
            $('html,body').animate({
                scrollTop: $(dynamic_tagging_editor_id).offset().top-50
            });
            //$("#tagging_text"+image_id).remove(); 
            //call a template            
            var data = {
                action: 'load_portfolio_template',
                referer: 'continu_project_dialog',
                template: 'project_tagging_settings_form',
                image_id: image_id,
                project_id: project_id,
                security: $('#continu_project_dialog').attr('data-security')
            };
            
            $.ajax({
                url:myAjax.ajaxurl,
                data: data,
                type:"post",
                success: function( msg ) {
                    $( dynamic_tagging_editor_id ).html( msg ); // Give a smooth fade in effect
                    //$("#dynamic_port_folio_images").before(msg);
                    //$("#taginput").click();
                    // initiallize validation
                    //login_form_validate_calback();  
                    /*var config = {
                      '.chosen-select'           : {width:"30%"},
                    }
                      
                    for (var selector in config) {
                         $(selector).chosen(config[selector]);
                    }*/
                    //$(".chosen-select").trigger("chosen:updated");
					$('#image_FieldTags').tagit({ 
					    availableTags: sampleTags
					}); 
                }
            });

        } else {
            $(dynamic_tagging_editor_id).remove();
        }
        
    }); 



    $(document).on('click', '.cancle_tagging_btn', function (event){
         var image_id = $(this).closest('li').attr("image_id");
         var dynamic_caption_editor_id="#tagging_editor"+image_id;
         var dynamic_id="li#gridlist"+image_id;
           //caption_text=$('#caption_editor_content'+image_id).val();
           //var caption_text;
         
         
         
         $(dynamic_caption_editor_id).remove();
          
         $('html,body').animate({
                scrollTop:$(dynamic_id).offset().top
             });

        }); 


     $(document).on('click', '.save_tagging_btn', function (event){ 
        var image_id = $(this).closest('li').attr("image_id");
       // alert(image_id);
        security=$("#security_portfolio").val();
        var loding_section=$(this).closest('div');
        var image_tag_obj=".image_tag_field"+image_id;
        var img_status = $("input:radio[name ='image_status"+image_id+"']:checked").val();
        var buy_now_link_obj="#buy_now_link"+image_id;
        var buy_now_price_obj="#buy_now_price"+image_id;
        var enable_buy_now_obj="#enable_buy_now"+image_id;

        var image_tag_val=$(image_tag_obj).val();
        var buy_now_link_val=$(buy_now_link_obj).val();
        var buy_now_price_val=$(buy_now_price_obj).val();
        var enable_buy_now_val;

        if ($( enable_buy_now_obj ).prop( "checked" ) )
        enable_buy_now_val=1;
        else
        enable_buy_now_val=0;
        

/****closed by me****/
        //if(image_tag_val=="")
            //return false;
        
        $(loding_section).append('<span class="loader_img" id="ll_limg'+image_id+'"></span>');
        $(" .save_tagging_btn").addClass("invijible_btn");
        $(" .cancle_tagging_btn").addClass("invijible_btn");
                	
        $.post( myAjax.ajaxurl, { 
            action: "save_portfolio_image_tagging",
            image_id: image_id,
            image_tag:image_tag_val,
            img_status: img_status,
            buy_now_link: buy_now_link_val,
            buy_now_price: buy_now_price_val,
            enable_buy_now: enable_buy_now_val,
            security:security})
        .done(function( data ) {
          
           resault= $.parseJSON(data);  
           var loding_section=$(this).closest('div');
           var caption_editor_obj="#caption_editor"+resault.image_id;
           var dynamic_id="li#gridlist"+resault.image_id;
           var append_obj=$('#dynamic_port_folio_images '+dynamic_id);
           
           $(" .save_tagging_btn").removeClass("invijible_btn");
           $(" .cancle_tagging_btn").removeClass("invijible_btn");
           
           var rloader_obj="#ll_limg"+resault.image_id;

           $(rloader_obj).remove(); 
           $(" .cancle_tagging_btn").val("Close");
           //$(caption_editor_obj).remove();
           
           //$(append_obj).append('<div class="caption_text" id="caption_text'+resault.image_id+'">'+resault.img_caption+"</div>");
           // alert(caption_editor_obj);
           
          });
        }); 


});	
