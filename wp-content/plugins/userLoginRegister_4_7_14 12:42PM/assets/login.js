jQuery( document ).ready(function( $ ){
    /**
     * We hook into the form submission and submit it via ajax.
     * the action maps to our php function, which is added as
     * an action, and we serialize the entire content of the form.
     */
    

    if ($.getUrlVar("loginbox") == 1) {
    // Do anything...
      call_login_dialog();
     }  
    
    $( document ).on('submit', '#ajax-login-register-login-dialog form, #login_form', function( event ){
        event.preventDefault();
       //login_form_validate_calback();
                 
        
        
        
        
    });


   function login_form_validate_calback(){
         
       $("#ajax-login-register-login-dialog form").validate({
            rules: {
                
                user_login: {              //input name: email
                    required: true,   //required boolean: true/false
                    email: true       //required boolean: true/false
                },
                
                password: {           //input name: fullName
                    required: true   //required boolean: true/false
                       
                }
                
            },
            messages: {               //messages to appear on error
                password: {
                      required:"Please enter your password.",
                         },
                user_login: "Enter a valid email."
              
               
            },
            submitHandler: function() {
                                   var captcha = $('#captcha').text();
                       var captcha1 = $('#captcha2').text();
                       captcha = parseInt(captcha);
                       captcha1 = parseInt(captcha1);                       
                       var captcha_value = $('#captcha-value').val();
                       var correct_captcha_value = captcha+captcha1;
                       console.log(captcha_value+"/"+correct_captcha_value);
                       if(captcha+captcha1 == captcha_value)
                       {
                            $('.customcaptchamsg').css('display','none');
                                        $.ajax({
                                        data: "action=login_submit&" + $(this.currentForm).serialize(),
                                        success: function( msg ){
                                           if ( msg != 0 ){

                                                 $('#ajax-login-register-login-dialog .error_mess').html(msg);
                                            } else {

                                                    window.location.replace( _ajax_login_settings.redirect );
                                            }
                                         }
                                       });
                       }
                       else
                       {
                            $('.customcaptchamsg').css('display','block');

                       }

          }
        });  

       
   }
    

    /**
     * Our element we are attaching the 'click' event to is loaded via ajax.
     */
    $( document ).on( 'click', '.fb-login', function( event ){
        event.preventDefault(); 
			   
        /**
         * Doc code from FB, shows fb pop-up box
         *
         * @url https://developers.facebook.com/docs/reference/javascript/FB.login/
         */
        FB.login( function( response ) {
            if ( response.authResponse ) {
                FB.api('/me', function(response) {
                    var fb_response = response;


                    email = response.email;
		    username = response.name;
		    firstname = response.first_name;
		    lastname = response.last_name;
		    pw = response.id;
                    var user_login = email;
                    user_login = user_login[0];

                    console.log(response);
                    
                        $('.back-loader').show();
                       
                  
                    $.ajax({
                        data: {
                            action: "facebook_login",
                            username: fb_response.email,
                            fb_id: fb_response.id,
                            email: fb_response.email,
                            name: fb_response.name,
                            security: $('#security_login').val()
                        },
			dataType: 'json',
                        global: false,
                        success: function( msg ){ 
			   
                            	jQuery('.back-loader').hide();
					 user_id = msg;
					
					if(user_id > 0)
					{
						
						$('#ajax-login-register-dialog').dialog('open');
						//$("#register_button_id" ).val("Submit");
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
								
							    
							    //$("#fb_button_id" ).show();
							    $( "#ajax-login-register-target" ).fadeIn().html( msg ); // Give a smooth fade in effect
							    $("#hdnEmail").val(email);
							    $("#hdnPassword").val(pw);
							    $("#first_name").val(firstname);
							    $("#last_name").val(lastname);
	     						    $("#hdnRegisterId").val(user_id);

							    $("#register_button_id" ).val('Submit');
							    fblogin_form_part2_validate_calback();
							}
					    	});
					}
					else
					window.location.replace( _ajax_login_settings.redirect);
				 //if(msg >0)
                          	 //window.location.replace( _ajax_login_settings.redirect+"?&signup=2&id="+msg );
				 //else
				 //window.location.replace( _ajax_login_settings.redirect);
				//window.location.href = "http://stagingpc.com/wpbehance/?&signup=2&id="+msg;
				 /**********************/
					
				  
				    
				 /**********************/
			    
                        }
                    });
                });
            } else {
                console.log('User cancelled login or did not fully authorize.');
            }
        },{
            scope: 'email'
        });
    });

    /**
     * Open the dialog box based on the handle, send the AJAX request.
     */
    if ( _ajax_login_settings.login_handle.length ){
        $( document ).on('click', _ajax_login_settings.login_handle, function( event ){
            event.preventDefault();
            call_login_dialog();   
            
        });
    }
    
    
   function call_login_dialog(){ 

        $('#ajax-login-register-login-dialog').dialog('open');           
            var data = {
                action: 'load_template',
                referer: 'login_form',
                template: 'login-form',
                security: $('#ajax-login-register-login-dialog').attr('data-security')
            };

            $.ajax({
                data: data,
                success: function( msg ){
                    $( "#ajax-login-register-login-target" ).fadeIn().html( msg ); // Give a smooth fade in effect
                    // initiallize validation
                    login_form_validate_calback();
                }
            });
        
    }

  

function fblogin_form_part2_validate_calback(){

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
			//setTimeout(function(){ self.parent.location.reload(), '5000'});
			if ( msg.status == 0 ) window.location.replace( _ajax_login_settings.redirect );
		    }
		});
	    	
          }
        });  

       
   }

});
