<?php

/**
 * This is the template for our register form. It should contain as less logic as possible
 */
$country_query =  $wpdb->get_results("select * from ".$wpdb->prefix."country order by id ASC"); 

$month_arr = array('January','February','March','April','May','June','July','August','September','October','November','December');
?>

<!-- Register Modal -->
<?php if ( get_option('users_can_register') ) : ?>
    <div class="ajax-login-register-login-container">
        <?php if ( is_user_logged_in() ) : ?>
            <p><?php printf('%s <a href="%s" title="Logout">%s</a>.', __('You are already registered',''), wp_logout_url( site_url() ), __('Logout', 'ajax_login_register') ); ?></p>
        <?php else : ?>
            <form action="javascript://" id="fclogin_form_part2" name="registerform" class="ajax-login-default-form-container <?php print get_option('ajax_login_register_default_style'); ?>">

                <?php if ( get_option('ajax_login_register_facebook') ) : ?>
                    <!--<div class="fb-login-container">
                        <a href="#" class="fb-login"><img src="<?php print plugin_dir_url( dirname( __FILE__ ) ); ?>assets/images/fb-login-button.png" /></a>
                    </div>-->
                <?php endif; ?>

                <div class="form-wrapper">
                    <?php wp_nonce_field( 'login_submit', 'security' ); ?>
                    <div class="ajax-login-register-status-container">
                        <div class="ajax-login-register-msg-target"></div>
                    </div>
			<input type="hidden" name="hdnEmail" id="hdnEmail" value="" />
			<input type="text" name="hdnUserId" id="hdnUserId" value="" />
                    <div class="noon"><label id="nameLabel"><?php _e('First Name', 'ajax_login_register'); ?></label>
			<input type="text" name="first_name" id="first_name" />
		    </div>
                    <div class="noon" id="divLastName"><label><?php _e('Last Name', 'ajax_login_register'); ?></label>
			<input type="text" name="last_name" id="last_name" /></div>
                    <div class="noon"><label><?php _e('Profile Name', 'ajax_login_register'); ?></label>
			<span><input type="text" name="path id="path" readonly="readonly" value="<?php echo home_url(); ?>/" style="width:275px;background-color:#E0E0E0;"/></span><input type="text" name="profile_name" id="profile_name" class="" style="width:150px;" /></div>
		    <div class="noon">
		    <div style="width:220px;float:left;">
		    <label><?php _e('Country', 'ajax_login_register'); ?></label>
		    <select name="country" id="country" style="width:200px;height:28px;padding-top:3px" >
			<option value="">Select Country</option>
			<?php if(count($country_query) > 0){
			foreach($country_query as $row){ ?>
			<option value="<?php echo $row->country_name; ?>"><?php echo $row->country_name; ?></option>
			<?php } } ?>
		    </select>
		    </div>
		    <div style="width:223px;float:left;">
		    <label><?php _e('City', 'ajax_login_register'); ?></label>
		    <input type="text" name="city" id="city" style="width:215px;" />
		    </div>
		    <div class="clear"></div>
		    </div>	
		    <div class="noon"><label><?php _e('Account owners birthday', 'ajax_login_register'); ?></label>
    			<select name="birth_month" style="width:145px;height:28px;padding-top:3px">
			<option value="">Month</option>
			<?php for($i=0;$i<count($month_arr);$i++){ 
			if($i<10) 
				$val = '0'.$i;
			else
				$val = $i;
			?>
			<option value="<?php echo $i; ?>"><?php echo $month_arr[$i]; ?></option>
			<?php } ?>
			</select>&nbsp;
			<select name="birth_day" style="width:145px;height:28px;padding-top:3px">
			<option value="">Day</option>
			<?php for($i=1;$i<32;$i++){ 
			if($i<10) 
				$val = '0'.$i;
			else
				$val = $i;
			?>
			<option value="<?php echo $val; ?>"><?php echo $val; ?></option>
			<?php } ?>
			</select>&nbsp;
			<select name="birth_year" style="width:145px;height:28px;padding-top:3px">
			<option value="">Year</option>
			<?php for($i=date('Y');$i>1913;$i--){ ?>
			<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
			<?php } ?>
			</select>
			
		    </div>
                    <div class="button-container" id="register_button_pane">
                        <input id="register_button_id" type="submit" value="<?php _e('Submit','ajax_login_register'); ?>"  name="register" class="green" />
                        <input type="button" value="Cancel" class="text cancel" id="ajax-login-register-close" />
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
<?php else : ?>
    <p><?php _e('Registration is currently closed.',''); ?></p>
<?php endif; ?>
<!-- End 'modal' -->
