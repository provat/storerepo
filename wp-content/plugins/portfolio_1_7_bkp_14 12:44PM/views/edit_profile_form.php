<?php
global $wpdb;
$user_id = get_current_user_id();
$country_query =  $wpdb->get_results("select * from ".$wpdb->prefix."country order by id ASC"); 

$month_arr = array('January','February','March','April','May','June','July','August','September','October','November','December');
?>
<div id="update_profile_msg"></div>
<form enctype="multipart/form-data"  method="POST" id="edit_my_profile" action="javascript://" novalidate="novalidate">
<div  class="profile_image">
  <span>Profile Image</span>
  <img src="<?php bloginfo("template_url") ?>/images/user_img.jpg">
  <input type="file" name="profile_image" value="Upload">
<div class="clear"></div>
</div>
<div class="noon">
  <span id="nameLabel">
    <?php _e('First Name', 'ajax_login_register'); ?>
  </span>
    <input type="text" name="first_name" id="first_name"  value="<?php echo get_user_meta($user_id, "first_name", true);  ?>"/>
<div class="clear"></div>
</div>
<div class="noon" id="divLastName">
  <span>
    <?php _e('Last Name', 'ajax_login_register'); ?>
  </span>
  <input type="text" name="last_name" id="last_name" value="<?php echo get_user_meta($user_id, "last_name", true);  ?>"/>
<div class="clear"></div>
</div>
<div class="noon">
  <span>
    <?php _e('Profile Name', 'ajax_login_register'); ?>
  </span>
<div class="profilename">
  <input type="text" name="path" id="path" readonly="readonly" value="<?php echo home_url(); ?>"/>
  <input type="text" name="profile_name" id="profile_name" value="<?php echo get_user_meta($user_id, "profile_name", true);  ?>" />
</div>
<div class="clear"></div>
</div>
<div class="noon">
  <div class="noon_country">
    <span>
      <?php _e('Country', 'ajax_login_register'); ?>
    </span>
    <select name="country" id="country" style="" >
      <option value="">Select Country</option>
      <?php if(count($country_query) > 0){
			foreach($country_query as $row){ ?>
      <option value="<?php echo $row->country_name; ?>" <?php echo get_user_meta($user_id, "country", true)==$row->country_name?"selected":"" ?>><?php echo $row->country_name; ?></option>
      <?php } } ?>
    </select>
  </div>
  <div class="noon_country">
    <span>
      <?php _e('City', 'ajax_login_register'); ?>
    </span>
    <input type="text" name="city" id="city" style="" value="<?php echo get_user_meta($user_id, "city", true);  ?>" />
  </div>
  <div class="clear"></div>
</div>
<div class="noon">
  <span>
    <?php _e('Birthday', 'ajax_login_register'); 
    $birthday=get_user_meta($user_id, "birth_day", true);
    $birthday_arr=  explode("-", $birthday);
    ?>
  </span>
  <select name="birth_month" class="birthday_input">
    <option value="">Month</option>
    <?php for($i=0;$i<count($month_arr);$i++){ 
			if($i<10) 
				$val = '0'.$i;
			else
				$val = $i;
			?>
    <option value="<?php echo $i; ?>" <?php echo $birthday_arr[1]==$i?"selected":""?> ><?php echo $month_arr[$i]; ?></option>
    <?php } ?>
  </select>
  &nbsp;
  <select name="birth_day" class="birthday_input">
    <option value="">Day</option>
    <?php for($i=1;$i<32;$i++){ 
			if($i<10) 
				$val = '0'.$i;
			else
				$val = $i;
			?>
    <option value="<?php echo $val; ?>" <?php echo $birthday_arr[2]==$val?"selected":""?> ><?php echo $val; ?></option>
    <?php } ?>
  </select>
  &nbsp;
  <select name="birth_year" class="birthday_input">
    <option value="">Year</option>
    <?php for($i=date('Y');$i>1913;$i--){ ?>
    <option value="<?php echo $i; ?>" <?php echo $birthday_arr[0]==$i?"selected":""?>><?php echo $i; ?></option>
    <?php } ?>
  </select>
  <div class="clear"></div>
</div>
<div class="noon">
  <span>Facebook Profile Url</span>
  <input type="text" name="fburl" id="city" style="" value="<?php echo get_user_meta($user_id, "fb_link", true);  ?>"/>
<div class="clear"></div>
</div>
<div class="noon">
  <span>Twitter Profile Url</span>
  <input type="text" name="twurl" id="city" style="" value="<?php echo get_user_meta($user_id, "twitter_link", true);  ?>" />
<div class="clear"></div>
</div>
<div class="noon">
  <span>Google plus Profile Url</span>
  <input type="text" name="gpurl" id="city" style="" value="<?php echo get_user_meta($user_id, "google_plus_link", true);  ?>" />
<div class="clear"></div>
</div>
<div class="noon">
  <span>New Password</span>
  <input type="text" name="password" id="password" style="" />
<div class="clear"></div>
</div>
<div class="noon">
  <span>Confirm New Password</span>
  <input type="text" name="conpassword" id="conpassword" style="" />
<div class="clear"></div>
</div>
<div class="button-container" id="register_button_pane">
  <input id="register_button_id" type="submit" value="<?php _e('Save','ajax_login_register'); ?>"  name="register" class="green" />
<div class="clear"></div>
</div>
</form>