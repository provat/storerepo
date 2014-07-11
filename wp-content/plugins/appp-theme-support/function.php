<?php
/**
 * load javascript file
 */
$plugin_url = plugin_dir_url(__FILE__);

add_action('wp_head', 'load_appchild_scripts');
function load_appchild_scripts() {
  global $plugin_url;

  wp_register_script( 'appchild-js', $plugin_url . 'js/general.js', false, '1.0.0');
  wp_enqueue_script( 'appchild-js' );
}
