jQuery(function($){

    

   

    // Initialize the jQuery File Upload plugin
    $('#replaceImageFileForm').fileupload({

        

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
           
            
            
            var replace_li_id="#gridlist"+$("#replace_image_id").val();
            
            var ul = $('#dynamic_port_folio_images ul '+replace_li_id);
            
            var img=$('#dynamic_port_folio_images ul '+replace_li_id+ " img");
            
            var tpl = $('<li class="working removeonly"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name)
                         .append('<i>' + formatFileSize(data.files[0].size) + '</i>');
            
           // ul.attr('class',"working removeonly")
            // Add the HTML to the UL element
            img.remove();
            data.context = tpl.appendTo(ul);
            //$(replace_li_id).html(tpl);
            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
            }
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
        },
        
        success:function (result3, textStatus, jqXHR) {
            var resault = $.parseJSON(result3);
            var replace_li_id="#gridlist"+resault.image_id;
            //alert(result3);
            
            //$('#dynamic_port_folio_images ul '+replace_li_id).append("<img src='"+ resault.url+"'/>");
            if($("#caption_text"+resault.image_id).length!=0)
               $('#dynamic_port_folio_images ul '+replace_li_id + ' #caption_text'+resault.image_id).before(resault.attachment); 
            else    
              $('#dynamic_port_folio_images ul '+replace_li_id).append(resault.attachment);
          
            $('#dynamic_port_folio_images ul '+replace_li_id+ " .removeonly").remove();
           
            
        },
        

    });


    
    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }

});


