jQuery( document ).ready(function( $ ){
    /**
     * We hook into the form submission and submit it via ajax.
     * the action maps to our php function, which is added as
     * an action, and we serialize the entire content of the form.
     */
    $( document ).on('click', '.ajax-login-register-container .cancel', function(){
        $(this).closest('.ajax-login-register-container').dialog('close');
    });
     $( document ).on('click', _ajax_login_settings.register_handle, function( event ){
	event.preventDefault();
        call_register_dialog();
      });
         
    /*$( document ).on('submit', '#ajax-login-register-dialog form, #register_form_part2', function( event ){
	register_form_part2_validate_calback();
       
    });*/
	

   function register_form_validate_calback(){
       
       $("#ajax-login-register-dialog form").validate({
            rules: {
                
                login: {              //input name: email
                    required: true,   //required boolean: true/false
                    email: true       //required boolean: true/false
                },
                
                password: {           //input name: fullName
                    required: true   //required boolean: true/false
                       
                },
		confirm_password: {           //input name: fullName
                    required: true,   //required boolean: true/false
                    equalTo: user_password   
                }
                
            },
            messages: {               //messages to appear on error
                password: {
                      required:"Please enter your password.",
                         },
                login: {
			required: "Enter a valid email."},
		confirm_password: {           //input name: fullName
                    required: 'Please enter confirm password',   //required boolean: true/false
                    equalTo: 'confirm password did not matched'  
                }
              
               
            },
            submitHandler: function() {
           
            		var email = $("#user_login").val();
			var password = $("#user_password").val();	
			$.ajax({
			    data: "action=register_submit&" + $( this ).serialize(),
			    dataType: 'json',
			    success: function( msg ) {
				$('#ajax-login-register-dialog').dialog('open');
					$("#register_button_id" ).removeAttr("disabled");
					var data = {
					action: 'load_template',
					template: 'register-form-part2',
					referer: 'register_form',
					security:  $('#ajax-login-register-dialog').attr('data-security')
					};
				   	$.ajax({ 
						data: data,
						success: function( msg ){
						    $('.ajax-login-register-container').dialog({
							width: '500px'
						    });
						    $("#register_button_id" ).removeAttr("disabled");
						    $( "#ajax-login-register-target" ).fadeIn().html( msg ); // Give a smooth fade in effect
						    $("#hdnEmail").val(email);
						    $("#hdnPassword").val(password);
						    register_form_part2_validate_calback();
						}
				    	});
			
			
			
				//ajax_login_register_show_message( msg );
				//if ( msg.status == 0 ) window.location.replace( _ajax_login_settings.redirect );
			    }
			});
          }
        });  

       
   }
    

    
    /**
     * Open the dialog box based on the handle, send the AJAX request.
     */
    /*if ( _ajax_register_settings.register_handle.length ){
        $( document ).on('click', _ajax_register_settings.register_handle, function( event ){
            event.preventDefault();
            call_register_dialog();   
            
        });
    }*/
    
    
   function call_register_dialog(){
        
        $('#ajax-login-register-dialog').dialog('open');
             
            
            var data = {
                action: 'load_template',
                referer: 'register_form',
                template: 'register-form',
                security: $('#ajax-login-register-dialog').attr('data-security')
            };

            $.ajax({
                data: data,
                success: function( msg ){
                    $( "#ajax-login-register-target" ).fadeIn().html( msg ); // Give a smooth fade in effect
                    // initiallize validation
                    register_form_validate_calback();
                }
            });
        
    }



function register_form_part2_validate_calback(){

       $("#ajax-login-register-dialog form").validate({
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
		//event.preventDefault();
		$.ajax({
		    data: "action=register_submit_part2&" + $( '#register_form_part2' ).serialize(),
		    dataType: 'json',
		    success: function( msg ) {
			ajax_login_register_show_message( msg );
			setTimeout(function(){ self.parent.location.reload(), '5000'});
			//if ( msg.status == 0 ) window.location.replace( _ajax_login_settings.redirect );
		    }
		});
	    	
          }
        });  

       
   }
});
