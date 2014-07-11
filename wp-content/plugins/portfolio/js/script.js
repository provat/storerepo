jQuery(function($){

    var ul = $('#dynamic_port_folio_images ul');

    $('#drop a').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').click();
    });

    // Initialize the jQuery File Upload plugin
    $('#upload, #upload_on_hover, #upload_on_hover2').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
            $( "#upload_form_pop_up" ).dialog('close');
            //$('.project_toolbar').hide();
         
            var tpl = $('<li class="working removeonly"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name)
                         .append('<i>' + formatFileSize(data.files[0].size) + '</i>');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

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
            
            var strmy;
            strmy='<span class="projecteditor-icon-module-reorder"><div class="tooltipi-reorder"> Reorder </div></span>';
            strmy+='<span class="projecteditor-icon-module-caption"><div class="tooltipi-caption">Add Caption</div></span>';
            strmy+='<span class="projecteditor-icon-module-tagging"><div class="tooltipi-tagging">Tagging</div></span>';
            strmy+='<span class="projecteditor-icon-module-replace"><div class="tooltipi-replace"> Replace </div></span>';
            strmy+='<span class="projecteditor-icon-module-trash"><div class="tooltipi-trash"> Trash </div></span>';
             
            //var resault= result3;
            var resault = $.parseJSON(result3); 
            $(".removeonly").hide();
            var tpl = $('<li class="grid" image_id="'+resault.id+'" id="gridlist'+resault.id+'">'+resault.attachment+'<div class="image_editor_icon" image_id="'+resault.id+'">'+strmy+'</div></li>');
            $(".user_project_id").val(resault.project_id);
            tpl.appendTo(ul);  
            $('.project_toolbar').show('slow');
           
            
        },
        

    });


    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
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


