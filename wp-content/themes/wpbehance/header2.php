<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'template_url' ); ?>/css/responsive.css" />
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>

<script type="text/javascript">
/*Menu dropdown start*/
jQuery(document).ready(function($) {
    $('.logopanel_right .m-menu').hide();
    $('.logopanel_right a.menu').click(function(e){
        e.preventDefault();
        $(this).next().stop(true,true).slideToggle();
    });
});
</script>
<script>var share_loc = '<?php echo $_REQUEST["storenear"]; ?>'; </script>
<script type="text/javascript">
	jQuery(document).ready(function($){

		if (!$.browser.opera) {



			$('select.select2').each(function(){
				var title = $(this).attr('title');
				if( $('option:selected', this).val() != ''  ) title = $('option:selected',this).text();
				$(this)
					.css({'z-index':10,'opacity':0,'-khtml-appearance':'none'})
					.after('<span class="select2">' + title + '</span>')
					.change(function(){
						val = $('option:selected',this).text();
						$(this).next().text(val);
						})
			});

			$('._42ft _4jy0 layerConfirm').click(function(){
				alert("hellooo");
			});
		};





	});
</script>

<!-- search panel -->

<script type="text/javascript">
 var overlayClicked = function(event) {
	jQuery(event.currentTarget).hide();
 };
 var searchFormClicked = function(event) {
	event.stopPropagation();
 };
	jQuery(document).ready(function($){
		jQuery('.search_icon a').click(function(){
			//var html = jQuery('.codenegar_ajax_search_wrapper').html();
			//var overlay = jQuery('<div id="overlay" onclick="overlayClicked(event)"><div onclick="searchFormClicked(event)" class="popupContainer">'+html+'</div></div>');
			//overlay.appendTo(document.body)
			jQuery("#overlay").css({'display' : 'block'});
			jQuery(".codenegar_ajax_search_wrapper").css({'display' : 'block'});
		});

	});

	jQuery(window).scroll(function () {
	  if (jQuery(document).scrollTop() == 0) {
		jQuery('.header_bg1').removeClass('tiny');
	  } else {
		jQuery('.header_bg1').addClass('tiny');
	  }
	});

	jQuery(document).ready(function(){
		var maxHeight = 0;
			jQuery(".similar_project_area .s_project_list .project-cover").each(function(){
			   if (jQuery(this).height() > maxHeight) { maxHeight = jQuery(this).height();
                 }
			});
			jQuery(".similar_project_area .s_project_list .project-cover").height(maxHeight);
	});

	jQuery(document).ready(function(){
		var maxHeight = 0;
			jQuery(".portfolio_name").each(function(){
			   if (jQuery(this).height() > maxHeight) { maxHeight = jQuery(this).height();
                 }
			});
			jQuery(".portfolio_name").height(maxHeight);
	});

	jQuery(document).ready(function(){
		var maxHeight = 0;
			jQuery("#project_details_image_section_id .all-content-box .project-cover").each(function(){
			   if (jQuery(this).height() > maxHeight) { maxHeight = jQuery(this).height();
                 }
			});
			jQuery("#project_details_image_section_id .all-content-box .project-cover").height(maxHeight);
	});
</script>


<!--- search panel ---->

<!-- Mobile Menu Script-->
<!--<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery-1.9.min.js"></script>-->
<!--<script src="<?php bloginfo('url'); ?>/plugins/portfolio/js/jquery-ui.min.js"></script>-->
<link type="text/css" rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/jquery.mmenu.css" />
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.mmenu.js"></script>
<script>
	/*$(document).ready(function(e) {
		var $menu = $('#menu-right');
		$menu.mmenu({
		position	: 'left',
		});
    	 });*/
	jQuery(document).ready(function($) {
	$('#menu-right').mmenu({
		classes: 'mm-light'
	});
	});

</script>

<!--<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
FB.init({
appId:'1440128186206541',
cookie:true,
status:true,
xfbml:true
});

function FacebookInviteFriends()
{
FB.ui({
method: 'apprequests',
message: 'Your Message diaolog'
});
}
</script>-->
<!-- Mobile Menu Script-->
<!--<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
FB.init({
    appId:'707395002639702',
    frictionlessRequests: true,
    status:true,
    xfbml:true
});

function FacebookInviteFriends()
{
    FB.ui({
        method: 'apprequests',
        message: 'Friend request for my app. This is test only'
    });
}
</script>-->
	<?php if(is_single()){?>
    	<style type="text/css">
			@media(max-width: 768px){
				.customsignin,
				.customheader_bg3{
					display:none;
				}
			}
		</style>

    <?php }
    else
    {
        /*Cooe here*/
    }
    ?>

</head>

<body <?php body_class(); ?>>
<div id="overlay" onclick="overlayClicked(event)" style="display: none;">
  <div onclick="searchFormClicked(event)" class="popupContainer">
  <?php ajax_autosuggest_form(); ?>
  </div>
</div>
<!--========================Header Start=====================-->
<!--<div class="logopanel_right"> <a href="#" title="MENU" class="menu">MENU</a>
  <div class="mobile-menu dropmenu_area">
    <ul>
      <li><a href="#">Discover</a></li>
      <li>

     <?php //echo do_shortcode('[fib]'); ?>
        </li>
    </ul>
  </div>
</div>-->





<!-- <div class="header_bg_container"> -->
<div class="header_bg1">
	<div class="main_wrap">
   		 	<div class="search_icon" style="display:none;">
            	<a href="#">
               		<img src="http://everystore.co/wp-content/themes/wpbehance/images/search-image.png">
                </a>
            </div>
            <div class="logo"><a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo.png" alt="logo" /></a></div>



            <div class="signin_area customsignin">

            <ul>
                <?php if ( is_user_logged_in() ) {
                    global $current_user;
                    get_currentuserinfo();

            $user = wp_get_current_user();
            $user_status = get_user_meta($user->ID, 'pw_user_status', 'true');
            ?>
                    <li class="sign_pop">
                        <div class="signin_popup">
                            <ul>
                                <?php /*?><li><a href="<?php echo get_permalink(get_ID('projects')); ?>">My Projects</a></li>
                                <li><a href="<?php echo get_permalink(get_ID('myprofile')); ?>">My Profile</a></li><?php */?>
                                <li><a href="<?php echo get_permalink(get_ID('myprofile')); ?>">Followed Stores</a></li>
                                <li><a href="<?php echo get_permalink(get_ID('myprofile')); ?>?mode=wishlist">My Wishlist</a></li>
                                <li><a href="<?php echo get_permalink(get_ID('myprofile')); ?>?mode=editprofile">Edit Profile</a></li>
                <?php if($user_status == 'approved'){ ?>
                                <li><a href="<?php echo get_permalink(get_ID('projects')); ?>">My Stores</a></li>
                <?php } ?>
                            </ul>
                        </div>
                    <?php echo "Hi <span class='no_translate'>".$current_user->display_name."</span>"; ?>
                    </li>
                    <li><a href="<?php echo wp_logout_url( home_url() ); ?>" ><img src="<?php bloginfo('stylesheet_directory'); ?>/images/login_icon.png" alt="Logout" />Logout</a></li>


                    <?php }else{ ?>
                   <li>
                    <a href="#" class="register-link">Sign Up</a> <?php if ( get_option('ajax_login_register_facebook') && get_option('users_can_register') ) : ?> <?php wp_nonce_field( 'login_submit', 'security_login' ); ?>
                        <a class="fb-login" href="#">
                            <span class="f_test" style="display:none;">F&nbsp;</span>Connect
                        </a>
                    <?php endif; ?>
                    </li>
                    <li><a href="#" class="login-link"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/login_icon.png" alt="login img" />Log In</a></li>
                   <?php } ?>


            </ul>
        </div>

    <div class="clear"></div>
    </div>
</div>
<div class="header_bg2">
	<div class="main_wrap webversion">
<!--<div id="fb-root"></div>
<a href='#' onclick="FacebookInviteFriends();">
Facebook Invite Friends Link
</a>-->
    	<div class="menu_area">
        	<ul>
            	<li><a href="<?php bloginfo('url'); ?>">Discover</a></li>
                <li class="invitefriend"><?php echo do_shortcode('[fib]'); ?></li>
			<!--<li class="invitefriend">
			<div id="fb-root"></div>
			<a class="fiblinkimage" title="Invite Friends" href='#' onclick="FacebookInviteFriends();">
			Invite Friends
			</a>
			</li>-->
		<?php if(is_front_page()){ ?>
                <li class="nearfriend"><a href="javascript: void(0);" onclick="javascript: getPosition('location');">Stores near me</a></li>
		<?php } else{ ?>
                <li class="nearfriend"><a href="<?php echo bloginfo('url');?>?storenear=true">Stores near me</a></li>
		<?php } ?>
            </ul>
        </div>
        <div class="language" style="position:relative;"><?php //do_action('icl_language_selector'); ?><?php echo do_shortcode('[google-translator]');?><?php //dynamic_sidebar( 'sidebar-2' ); ?></div>
    <div class="clear"></div>
    </div>
</div>

<!----------------------------------mobile version---------------------------------------->
<div class="mobileVersion">
        		<a href="#menu-right" name="menu-right" class="mobileMenu"></a>
                <div id="menu-right">

                  <ul>
                    <?php
            		    if ( is_user_logged_in() ) {
                        global $current_user;
                        get_currentuserinfo();
                    ?>
                    <li>
                    <?php echo "<span class='no_translate'>".'HI '.$current_user->display_name.'</span>'; ?>
                    </li>
                    <?php }?>
                  	<li>
                      <a href="#" class="register-link">Sign Up</a> <?php if ( get_option('ajax_login_register_facebook') && get_option('users_can_register') ) : ?> <?php wp_nonce_field( 'login_submit', 'security_login' ); ?><a class="fb-login" href="#">F Connect</a> <?php endif; ?>
                    </li>
                    <li><a href="#" class="login-link"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/login_icon.png" alt="login img" />Log In</a></li>

                    <li><a href="<?php bloginfo('url'); ?>">Discover Stores</a></li>
                    <?php if(is_front_page()){ ?>
              		    <li class="nearfriend"><a href="javascript: void(0);" onclick="javascript: getPosition('location');">Stores near me</a></li>
              			<?php } else{ ?>
              		        <li class="nearfriend"><a href="<?php echo bloginfo('url');?>?storenear=true">Stores near me</a></li>
              			<?php } ?>
                        <li><a href="<?php echo get_permalink(get_ID('myprofile')); ?>">Followed Stores</a></li>
                        <li><a href="<?php bloginfo('url'); ?>">My Wishlist</a></li>
		                <li class="invitefriend"><a class="fiblinkimage" title="Invite Friends" onclick="SCFacebookInviteFriends();" href="#">Invite Friends</a></li>
              			<!--<li class="invitefriend">
              			<div id="fb-root"></div>
              			<a class="fiblinkimage" title="Invite Friends" href='#' onclick="FacebookInviteFriends();">
              			Invite Friends
              			</a>
              			</li>-->


                    <li><a href="<?php bloginfo('url'); ?>/get-the-apps/">Get The Apps</a></li>
                    <li><a href="<?php bloginfo('url'); ?>/about-us/">About Us</a></li>
                    <li><a href="<?php bloginfo('url'); ?>/advertise-here/">Advertise Here</a></li>
                    <li><a href="<?php bloginfo('url'); ?>/terms-and-conditions/">Terms and Conditions</a></li>
                    <li><a href="<?php bloginfo('url'); ?>/contact-us/">Contact Us</a></li>

            		    <?php
            		    if ( is_user_logged_in() ) {
                        global $current_user;
                        get_currentuserinfo();

                		    $user = wp_get_current_user();
                		    $user_status = get_user_meta($user->ID, 'pw_user_status', 'true');
            		    ?>
                    <li class="sign_pop">
                        <div class="signin_popup">
                            <ul>
                            	<?php /*?><li><a href="<?php echo get_permalink(get_ID('projects')); ?>">My Projects</a></li>
                                <li><a href="<?php echo get_permalink(get_ID('myprofile')); ?>">My Profile</a></li><?php */?>
                                <li><a href="<?php echo get_permalink(get_ID('myprofile')); ?>">Followed Stores</a></li>

                                <li><a href="<?php echo get_permalink(get_ID('myprofile')); ?>?mode=editprofile">Edit Profile</a></li>
				                      <?php if($user_status == 'approved'){ ?>
                                <li><a href="<?php echo get_permalink(get_ID('projects')); ?>">My Stores</a></li>
				                      <?php } ?>
                            </ul>
                        </div>
                    </li>
                    <li><a href="<?php echo wp_logout_url( home_url() ); ?>" ><img src="<?php bloginfo('stylesheet_directory'); ?>/images/login_icon.png" alt="Logout" />Logout</a></li>


                    <?php }else{ ?>

                   <?php } ?>

                    </ul>
                </div>
            </div>
<!-----------------------------------------mobile version---------------------------->

<?php
global $post;
$this_page_id = $post->ID;
$page_id = array(1307, 1309, 1311, 1313, 1315);
if(!in_array($this_page_id, $page_id)) {?>
<div class="header_bg3 customheader_bg3">

	<div class="main_wrap">

	<?php
	$country = $_REQUEST['country'];
	$state = $_REQUEST['state'];
	$city = $_REQUEST['city'];

	if($country!='')
	$_SESSION['country'] = $country;

	if($state!='')
	$_SESSION['state'] = $state;

	if($city!='')
	$_SESSION['city'] = $city;

	if($country=='')
	$country = $_SESSION['country'];

	if($state=='')
	$state = $_SESSION['state'];

	if($city=='')
	$city = $_SESSION['city'];

	if($_REQUEST['mode'] == '1' || $_REQUEST['allcon']=='true')
	{
		$_SESSION['country'] ='';
		$_SESSION['state'] ='';
		$_SESSION['city'] ='';
		$_SESSION['category'] ='';
		$category_fld = '';
		$country_fld = '';
		$country = '';
		$state = '';
		$city = '';
	}
	?>
	<?php
		$category_fld = $_REQUEST['category'];
		$country_fld = $country;

		if($country_fld=='')
		$country_fld = $_SESSION['country'];


		if($category_fld!=''){
			$thisCat = get_category($category_fld,false);
			$creative_txt = $thisCat->name;
		}
		else{
			$creative_txt = 'Categories';
		}

		if($country_fld!='' && $_REQUEST['allcon']!='true'){
			$worldwide_txt = $country_fld;
		}
		else{
			$worldwide_txt = 'Worldwide';
		}

	?>
    	<div class="creative_area"><span class="left"><?php echo $creative_txt; ?></span><div class="creative_field">
                <div class="creative_field_popup">
                    <div  class="creative_field_content_left">
                  <?php
                    $project_args = array(

             		'child_of'                 => 0,
             		'parent'                   => '',
             		'orderby'                  => 'count',
             		'order'                    => 'DESC',
             		'hide_empty'               => 1,
             		'hierarchical'             => 1,
             		'exclude'                  => '1',
             		'include'                  => '',
             		'number'                   => '10'

             	);

             $project_categories = get_categories( $project_args );
             if($project_categories){
             ?>
                    <div class="populer_title">Popular</div>
                    <ul>
		    <li><a href="<?php echo home_url(); ?>/search/?country=<?=$country;?>&state=<?=$state;?>&city=<?=$city;?>">All</a></li>
              <?php
             foreach($project_categories as $rew_category){
                 ?>
                        <li><a href="<?php //echo get_category_link( $rew_category->term_id )?><?php echo home_url(); ?>/search/?category=<?php echo $rew_category->term_id; ?>&country=<?=$country;?>&state=<?=$state;?>&city=<?=$city;?>" <?php if($category_fld==$rew_category->term_id){ ?>style="color:#000000 !important;" <?php } ?>/><?php echo $rew_category->name; ?></a></li>
                <?php
                 }
                 ?>
                    </ul>
             <?php
             }
                 ?>
                    </div>

                     <div  class="creative_field_content_right">

                         <?php
                    $cat_alp_project_args = array(

             		'child_of'                 => 0,
             		'parent'                   => '',
             		'orderby'                  => 'name',
             		'order'                    => 'ASC',
             		'hide_empty'               => 1,
             		'hierarchical'             => 1,
             		'exclude'                  => '1',
             		'include'                  => '',
             		'number'                   => ''

             	);

             $project_categories_alp= get_categories( $cat_alp_project_args );
             //var_dump($project_categories_alp);
             $tot_rec=count($project_categories_alp);
             if($project_categories_alp){
                 //echo count($project_categories_alp);
             ?>
                    <div class="populer_title">Alphabetical</div>
                    <div class="p_creativefield_wrapper" style="height:auto">

              <?php
             for($i=1; $i<=$tot_rec; $i++){

                 $myvar=$i;
                 $first_letter1=substr($project_categories_alp[$myvar-1]->name, 0, 1);
                 $first_letter2=substr($project_categories_alp[$myvar]->name, 0, 1);
                 $first_letter3=substr($project_categories_alp[$myvar+1]->name, 0, 1);
                 //echo $first_letter1.":".$i,":".$first_letter2;
                 $flag=1;
                 if(strtolower($first_letter1)!=strtolower($first_letter2) ){
                     echo "<div class='letter_group'><div class='first_lll'>".$first_letter2."</div><ul>";
                     $flag=2;
                 }
                 ?>
                        <li><a <?php if($category_fld==$project_categories_alp[$i]->term_id){ ?>style="color:#000000 !important;" <?php } ?> href="<?php //echo get_category_link( $project_categories_alp[$i]->term_id )?><?php echo home_url(); ?>/search/?category=<?php echo $project_categories_alp[$i]->term_id; ?>"><?php echo $project_categories_alp[$i]->name; ?></a></li>
                <?php
                  if(strtolower($first_letter2)!=strtolower($first_letter3)){
                     echo "</ul></div>";
                  }

                 }
                 ?>

             <?php
             }
                 ?>
                 </div>

                     </div>
                </div>
                <a href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/select_img.png" alt="select img" /></a></div>
                </div> <!------------>

	<input type="hidden" name="hdnCatId" id="hdnCatId" value="<?=$_REQUEST['category'];?>" />
	<input type="hidden" name="hdnCountryId" id="hdnCountryId" value="<?=$country;?>" />
	<input type="hidden" name="hdnStateId" id="hdnStateId" value="<?=$state;?>" />
	<input type="hidden" name="hdnCityId" id="hdnCityId" value="<?=$city;?>" />
        <div class="worldwide_area">
            <span class="left">
              <?php //echo $worldwide_txt;
                 if($country != "") {
                   $row = $wpdb->get_row("SELECT country_name from wp_country WHERE country_code='$country'");

                   $worldwide_txt = $row->country_name;
                   if($city != '')
                     $worldwide_txt.= ','.$city;
                 }
                 echo $worldwide_txt;
              ?>
            </span>
            <div class="worldwide_field">
                <div class="worldwide_field_popup">
                    <span class="header_title">Search Location</span>
                     <div class="location_form">
                        <div class="country_container"> <label>Country</label>

                          <?php
                            global $wpdb;
                            $resault = $wpdb->get_results("select * from wp_country order by country_name ASC");
                            /*$resault= $wpdb->get_results(
                              "SELECT pm.* FROM ".$wpdb->prefix."postmeta pm, ".$wpdb->prefix."posts p
                               WHERE p.ID=pm.post_id AND p.post_status='publish' AND pm.meta_key='countryName' GROUP BY pm.meta_value ORDER BY pm.meta_value ASC
                              ");*/

                         //var_dump($resault);
                         if(count($resault)){
                             ?>
                      <select name="country_list" id="search_country_list">
                        <!--<option value="">Select Country</option>-->
			<option value="" <?php if($_REQUEST['country']==''){ ?>selected<?php } ?>>All</option>
                        <?php
                             foreach($resault as $res){
                                 ?>
                                 <option value="<?php echo $res->country_code; ?>" <?php if($_REQUEST['country']==$res->country_code){ ?> selected <?php } ?>><?php echo $res->country_name; ?></option>

                             <?php
                             }
                            ?>
                        </select>
                         <?php
                         }


                ?>


						<div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                        <span id="view_state_list_dropdown"></span>
                        <div class="clear"></div>
                        <div class="city_field"><label>City</label><input type="text" name="s_country_city" id="s_country_city" value="<?php echo $city; ?>">
                        <div class="clear"></div>
                        </div>
                        <div class="apply_btn"><input type="button" value="APPLY" id="s_by_location_apply">
                        <div class="clear"></div>
                        </div>
                    </div>

                </div>
                <a href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/select_img.png" alt="select img" /></a>
            </div>
        </div>
        <div class="search_rightarea"><?php ajax_autosuggest_form(); ?>
    	<!--<div class="search_bg">
	<form name="frmSearch" id="frmSearch" action="<?php echo home_url(); ?>/search/"><input type="button" name="btnSubmit" id="btnSubmit" value="" class="btnSubmit" onclick="javascript:document.frmSearch.submit();"/><input name="search" id="search_rlts" type="text" class="search_input" placeholder="Search" value="<?php echo$_REQUEST['search']; ?>"/></form></div>-->

	<?php
	if($_REQUEST['hdnSrcMode']!=''){
		$search_mode = $_REQUEST['hdnSrcMode'];
		$_SESSION['search_mode'] = $search_mode;
	}
	else
	{
		$search_mode = $_SESSION['search_mode'];
	}
	?>
   <!--<div class="searchcategory">
		<form name="frmSearchMode" id="frmSearchMode" action="<?php echo home_url(); ?>/search/?mode=1" method="post">
		<input type="hidden" name="hdnSrcMode" id="hdnSrcMode" value="" />
        	<input type="radio" name="searchmode" id="searchmode1" <?php if($search_mode=='All' || $search_mode==''){ ?>checked="checked"<?php } ?> value="All" onclick="javascript:setSearchMode('All')"/>  Everything
<input type="radio" name="searchmode" id="searchmode2" value="Store" onclick="javascript:setSearchMode('Store')" <?php if($search_mode=='Store'){ ?>checked="checked"<?php } ?> /> Stores
		<input type="radio" name="searchmode" id="searchmode2" value="Product" onclick="javascript:setSearchMode('Product')" <?php if($search_mode=='Product'){ ?>checked="checked"<?php } ?> /> Products</form>

<script>
function setSearchMode(val)
{
	document.getElementById('hdnSrcMode').value = val;
	document.frmSearchMode.submit();
}
</script>
        </div>-->
        </div>
    <div class="clear"></div>
    </div>
</div>
<?php }?>

<!-- </div> -->

<!--========================Header End=====================-->
<!--========================Middle Start=====================-->
<div class="mid_wrapper">
    <div class="main_wrap">

