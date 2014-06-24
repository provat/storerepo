<?php
/**
 * 
 * Template Name: MyProfile
 * 
 */
if(!is_user_logged_in()){
    wp_redirect( home_url()."#loginbox=1");
    exit;
}
add_action('wp_head', 'my_profile_script',8);


 get_header();?>
<?php
global $wpdb;
?>

<?php
$tabname = $_REQUEST['mode'];
?>
<script>
jQuery(function($){
var tab = '<?php echo $tabname; ?>';
if(tab == 'editprofile')
$(".tab_box #edit_profile").click();

if(tab == 'wishlist')
$(".tab_box #project_wishlist").click();

if(tab == '')
$(".tab_box #project_follows").click();
});
</script>
 <!--========================Middle Start=====================-->
<div class="mid_wrapper">
  <div class="main_wrap">
    <div class="tab_box_margin">
      <div class="no_left_box_mid">
        <div class="box_left"></div>
        <div class="box_right"></div>
        <div>
        <div class="tab_box">
        	<ul>
                    <li <?php if($tabname==''){ ?>class="active" <?php } ?> id="project_follows"><a href="#">Stores Followed</a></li>
                    <li  id="project_wishlist" <?php if($tabname=='wishlist'){ ?>class="active" <?php } ?>><a href="#">My Wishlist</a></li>
                <li id="edit_profile" <?php if($tabname=='editprofile'){ ?>class="active" <?php } ?>><a href="#" >Edit Profile</a></li>
                
            </ul>
        </div>
             <?php wp_nonce_field( 'edit_my_profile', 'security' ); ?>
             <input type="hidden" name="limit" id="page_no" value="1" />
             <input type="hidden" name="last_page" id="last_page" value="2" />
            <div id="my_profile_content" class="my_profile_content"> 
                
         
            </div> 
            <div id="loadingDiv" style="display:none;" align="center"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/80.gif" /></div>
          <div class="clear"></div>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<!--========================Middle End=====================--> 
 <?php get_footer();?>
