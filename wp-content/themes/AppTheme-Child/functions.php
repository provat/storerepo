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
<<<<<<< HEAD
add_filter( 'template_include', 'appp_custom_template_include' ); */


/**
 * Display limited words with formatting
 * @$str - string, formatted string
 * @$string_length - integer, length of the string want to display
 */
function display_limited_words_with_formatting($str = '', $string_length = 0) {

  if($str == '')
    return;

  // If string length zero
  if($string_length == 0) {
  	$final_str = strip_tags($str, "<span><em><b></strong>");
  }

  // If string length not zero
  if($string_length > 0) {
	  // strip out all tags
	  $stripped_string = strip_tags($str);

	  // take the substring
	  $substr = substr($stripped_string, 0, $string_length);

	  // explode the substring w.r.t space
	  $exp_str = explode(" ", $substr);

	  /**
	   * Now store the exploded string into an array. Note that limit is total array length
	   * minus one. It is because when we do substr(), it may cut a word anywhere, for eg -
	   * "word" may be cut like "wo".
	   */
	  $new_str_array = array();
	  for($i = 0; $i < count($exp_str)-1; $i++) {
	    $new_str_array[$i] = $exp_str[$i];
	  }

	  // find the last element of the array
	  $last_element = end($new_str_array);

	  /**
	   * Now find last element's position into the formatted string, last because there may be more
	   */
	  $last_position = strrpos($str, $last_element);

	  // Let cut the formatted string upto last position
	  $final_substr = substr($str, 0, $last_position);

	  // Make it trimmed as there may be space
	  $trimmed_str = rtrim($final_substr);

	  // Allow all tags except <p> tag
  	$final_str = strip_tags($trimmed_str, "<span><em><b></strong>");
	}

  return trim($final_str);
}
=======
add_filter( 'template_include', 'appp_custom_template_include' ); */
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
