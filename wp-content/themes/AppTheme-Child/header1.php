<?php
/**
 * @package AppPresser Theme
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="body-container">

<section class="snap-drawer">
	<div class="shelf-top">

	<?php appp_left_panel_before(); // Hook for search, user profile, and cart items ?>

	</div><!-- .shelf-top -->

	<nav id="site-navigation" class="navigation-main" role="navigation">
		<div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'apptheme' ); ?>"><?php _e( 'Skip to content', 'apptheme' ); ?></a></div>
		<?php
		if( has_nav_menu( 'primary' ) )
			wp_nav_menu( array( 'theme_location' => 'primary' ) );
		?>
	</nav><!-- #site-navigation -->

	<?php if( is_user_logged_in() ) : ?>
		<div class="log-out-button"><a class="btn btn-success btn-large noajax" href="<?php echo wp_logout_url( home_url() ); ?>" title="<?php _e( 'Sign Out', 'apptheme' ); ?>"><?php _e( 'Sign Out', 'apptheme' ); ?></a></div>
	<?php else: ?>
		<div class="log-out-button"><a class="btn btn-success btn-large noajax io-modal-open" href="#loginModal" title="<?php _e( 'Sign In', 'apptheme' ); ?>"><?php _e( 'Sign In', 'apptheme' ); ?></a></div>
	<?php endif; ?>
	  <div class="log-out-button">
	    <a class="btn btn-success btn-large noajax" href="<?php echo home_url();?>">
	    <?php _e( 'Discover Srores', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
	    <a class="btn btn-success btn-large noajax" href="#">
	    <?php _e( 'Stores Near Me', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
	    <a class="btn btn-success btn-large noajax" href="#">
	    <?php _e( 'Followed Stores', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
	    <a class="btn btn-success btn-large noajax" href="#">
	    <?php _e( 'My Wishlist', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
	    <a class="btn btn-success btn-large noajax" href="#">
	    <?php _e( 'Invite Friends', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
	    <a class="btn btn-success btn-large noajax" href="#">
	    <?php _e( 'Get The Apps', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
	    <a class="btn btn-success btn-large noajax" href="<?php echo get_permalink(1072)?>">
	    <?php _e( 'About Us', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
	    <a class="btn btn-success btn-large noajax io-modal-open" href="#">
	    <?php _e( 'Advertise Here', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
	    <a class="btn btn-success btn-large noajax io-modal-open" href="#">
	    <?php _e( 'Terms And Conditions', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
	    <a class="btn btn-success btn-large noajax io-modal-open" href="#">
	    <?php _e( 'Contact Us', 'apptheme' ); ?>
	    </a>
	  </div>

</section>

<div id="page" class="hfeed site">
	<?php do_action( 'appp_before' ); ?>

	<header id="masthead" class="site-header" role="banner">

		<section class="header-inner">

			<div class="pull-left">
				<?php do_action( 'appp_header_left' ); ?>
				<a href="#" class="nav-left-btn" id="nav-left-open"><i class="fa fa-reorder fa-lg"></i></a>
			</div>

			<!-- <div class="site-title-wrap"> -->
				<?php //do_action( 'appp_page_title' ); ?>

				<div class="header_bg1">
				  <div class="main_wrap">
				    <div class="logo">
				      <a href="<?php bloginfo('url'); ?>">
				        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo.png" alt="logo" />
				      </a>
				    </div>
				  </div>
				</div>

        <div class="header_bg2">
          <div class="menu_area">
        	  <ul>
            	<li><a href="<?php bloginfo('url'); ?>">Discover</a></li>
              <li class="invitefriend"><?php echo do_shortcode('[fib]'); ?></li>
            </ul>
          </div>
          <div class="language" style="position:relative;">
            <?php echo do_shortcode('[google-translator]');?>
          </div>
          <div class="clear"></div>
        </div>

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
        $category_fld = $_REQUEST['category'];
    		$country_fld = $country;
    		if($country_fld=='')
    		  $country_fld = $_SESSION['country'];
    		if($category_fld!=''){
    			$thisCat = get_category($category_fld,false);
    			$creative_txt = $thisCat->name;
    		} else {
    			$creative_txt = 'Categories';
    		}
    		if($country_fld!='' && $_REQUEST['allcon']!='true') {
    			$worldwide_txt = $country_fld;
    		}	else {
    			$worldwide_txt = 'Worldwide';
    		}
      	?>
        <div class="header_bg3 customheader_bg3">
	        <div class="main_wrap">
	          <div class="creative_area">
	            <span class="left"><?php echo $creative_txt; ?></span>
	            <div class="creative_field">
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
                  if($project_categories) {
                  ?>
                    <div class="populer_title">Popular</div>
                      <ul>
                        <li>
                          <a href="<?php echo home_url(); ?>/search/?country=<?=$country;?>&state=<?=$state;?>&city=<?=$city;?>">All</a>
                        </li>
                        <?php
                        foreach($project_categories as $rew_category) {
                        ?>
                        <li>
                          <a href="<?php echo home_url(); ?>/search/?category=<?php echo $rew_category->term_id; ?>
                            &country=<?=$country;?>&state=<?=$state;?>&city=<?=$city;?>"
                            <?php if($category_fld==$rew_category->term_id){ ?>style="color:#000000 !important;" <?php } ?> >
                            <?php echo $rew_category->name; ?>
                          </a>
                        </li>
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
                  $project_categories_alp = get_categories( $cat_alp_project_args );
                  $tot_rec = count($project_categories_alp);
                  if($project_categories_alp) {
                  ?>
                    <div class="populer_title">Alphabetical</div>
                    <div class="p_creativefield_wrapper" style="height:auto">
                      <?php
                      for($i = 1; $i <= $tot_rec; $i++) {
                        $myvar=$i;
                        $first_letter1=substr($project_categories_alp[$myvar-1]->name, 0, 1);
                        $first_letter2=substr($project_categories_alp[$myvar]->name, 0, 1);
                        $first_letter3=substr($project_categories_alp[$myvar+1]->name, 0, 1);

                        $flag=1;
                        if(strtolower($first_letter1)!=strtolower($first_letter2) ){
                          echo "<div class='letter_group'><div class='first_lll'>".$first_letter2."</div><ul>";
                          $flag=2;
                        }
                        ?>
                        <li>
                          <a <?php if($category_fld==$project_categories_alp[$i]->term_id){ ?>style="color:#000000 !important;" <?php } ?>
                            href="<?php echo home_url(); ?>/search/?category=<?php echo $project_categories_alp[$i]->term_id; ?>">
                              <?php echo $project_categories_alp[$i]->name; ?>
                          </a>
                        </li>
                        <?php
                        if(strtolower($first_letter2)!=strtolower($first_letter3)){
                           echo "</ul></div>";
                        }
                      }
                      ?>
                    </div>
                  <?php
                  }
                  ?>
                  </div>

                </div>

	            </div>

	          </div>

	        </div>

	      </div>
			<!-- </div> --><!-- .site-title-wrap -->



		</section><!-- .header-inner -->

	</header><!-- #masthead -->

	<div id="main" <?php body_class( 'site-main' ); ?>>
