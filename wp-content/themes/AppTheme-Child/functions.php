<?php
/**
 * Get the AppPresser engine started
 */
require_once( get_template_directory() . '/inc/init.php' );

/**
 * Example for how to remove actions hooked in with AppTheme.
 * Modify or delete.
 */
add_action( 'after_setup_theme', 'appp_child_after_setup_theme' );
function appp_child_after_setup_theme() {
	appp_remove_hook( 'appp_page_title', 'do_page_title' );
	// Add our notice that we're using a child-theme
	add_action( 'appp_page_title', 'appp_child_replace_title' );
}

function appp_child_replace_title() {
	?>
	<h1 class="site-title page-title">I'M A CHILD THEME</h1>
	<?php
}


function prefix_appp_remove_search() {
    appp_remove_hook( 'appp_left_panel_before', 'left_panel_search', 20 );
}
add_action( 'after_setup_theme', 'prefix_appp_remove_search' );

/* function appp_custom_template_include( $template ) {
	$appp = get_option( 'appp_settings' );

	if ( is_page( $appp['appp_home_page'] ) ) {
		return WP_CONTENT_DIR . '/themes/THEME DIRECTORY/TEMPLATE FILE';
	}

	return $template;
}
add_filter( 'template_include', 'appp_custom_template_include' ); */