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
<?php wp_head(); global $post;?>
<script type="text/javascript">
window.pageId = '<?php echo $post->ID;?>';
</script>
<<<<<<< HEAD
</head>

<body <?php body_class(); ?>>

=======
<!-- Invite Friends -->
<script src = "http://connect.facebook.net/en_US/all.js"></script>

<script type="text/javascript">
	FB.init({ 
       appId:'731511263580569', cookie:true, 
       status:true, xfbml:true 
    });

     

function FacebookInviteFriends() 
{
FB.ui({ method: 'apprequests', 
   message: 'My diaolog...'});
}
</script>
<!-- Invite Friends -->

</head>

<body <?php body_class(); ?>>
<div class="ajax-spinner" style="display: none;"><i class="fa fa-spinner fa-spin fa-3x"></i></div>
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
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


	  <div class="log-out-button">
<<<<<<< HEAD
	    <a class="btn btn-success btn-large ajaxify" href="#">
=======
	    <a class="btn btn-success btn-large ajaxify portfolio" data-pageid="1" href="#">
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
	    <?php _e( 'Discover Srores', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
<<<<<<< HEAD
	    <a class="btn btn-success btn-large noajax store-near-me" href="#" data-storeid="12">
=======
	    <a class="btn btn-success btn-large noajax store-near-me" href="#" data-pageid="2">
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
	    <?php _e( 'Stores Near Me', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
<<<<<<< HEAD
	    <a class="btn btn-success btn-large noajax" href="#">
=======
	    <a class="btn btn-success btn-large noajax followed-stores" href="#" data-pageid="3">
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
	    <?php _e( 'Followed Stores', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
<<<<<<< HEAD
	    <a class="btn btn-success btn-large noajax" href="#">
=======
	    <a class="btn btn-success btn-large noajax wishlist" href="#" data-pageid="4">
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
	    <?php _e( 'My Wishlist', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
<<<<<<< HEAD
	    <a class="btn btn-success btn-large noajax" href="#">
=======
	    <a class="btn btn-success btn-large noajax" href="#" onclick="FacebookInviteFriends();" >
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
	    <?php _e( 'Invite Friends', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
<<<<<<< HEAD
	    <a class="btn btn-success btn-large noajax" href="#">
=======
	    <a class="btn btn-success btn-large noajax get-the-apps" href="#" data-pageid="6">
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
	    <?php _e( 'Get The Apps', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
<<<<<<< HEAD
	    <a class="btn btn-success btn-large noajax" href="#">
=======
	    <a class="btn btn-success btn-large noajax about-us" href="#" data-pageid="7">
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
	    <?php _e( 'About Us', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
<<<<<<< HEAD
	    <a class="btn btn-success btn-large noajax io-modal-open" href="#">
=======
	    <a class="btn btn-success btn-large noajax io-modal-open advertise-here" href="#" data-pageid="8">
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
	    <?php _e( 'Advertise Here', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
<<<<<<< HEAD
	    <a class="btn btn-success btn-large noajax io-modal-open" href="#">
=======
	    <a class="btn btn-success btn-large noajax io-modal-open terms-and-conditions" href="#" data-pageid="9">
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
	    <?php _e( 'Terms And Conditions', 'apptheme' ); ?>
	    </a>
	  </div>
	  <div class="log-out-button">
<<<<<<< HEAD
	    <a class="btn btn-success btn-large noajax io-modal-open" href="#">
=======
	    <a class="btn btn-success btn-large noajax io-modal-open contact-us" href="#" data-pageid="10">
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
	    <?php _e( 'Contact Us', 'apptheme' ); ?>
	    </a>
	  </div>
    <?php if( is_user_logged_in() ) : ?>
		<div class="log-out-button"><a class="btn btn-success btn-large noajax" href="<?php echo wp_logout_url( home_url() ); ?>" title="<?php _e( 'Sign Out', 'apptheme' ); ?>"><?php _e( 'Sign Out', 'apptheme' ); ?></a></div>
	  <?php else: ?>
		<div class="log-out-button"><a class="btn btn-success btn-large noajax io-modal-open" href="#loginModal" title="<?php _e( 'Sign In', 'apptheme' ); ?>"><?php _e( 'Sign In', 'apptheme' ); ?></a></div>
	  <?php endif; ?>
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

			<!-- </div> --><!-- .site-title-wrap -->

		</section><!-- .header-inner -->

	</header><!-- #masthead -->

	<div id="main" <?php body_class( 'site-main' ); ?>>
