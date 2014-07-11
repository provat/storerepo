<?php
/*
Plugin Name: AppSwiper
Plugin URI: http://apppresser.com
Description: Touch slider and carousel for the AppPresser theme.
Text Domain: apppresser-swipers
Domain Path: /languages
Version: 1.0.1
Author: AppPresser Team
Author URI: http://apppresser.com
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

class AppPresser_Swipers {

	// A single instance of this class.
	public static $instance    = null;
	public static $this_plugin = null;
	const APPP_KEY             = 'swiper_key';
	const PLUGIN               = 'AppSwiper';
	const VERSION              = '1.0.1';

	/**
	 * Creates or returns an instance of this class.
	 * @since  1.0.0
	 * @return AppPresser_Swipers A single instance of this class.
	 */
	public static function run() {
		if ( self::$instance === null )
			self::$instance = new self();

		return self::$instance;
	}

	/**
	 * Setup our plugin
	 * @since 1.0.0
	 */
	public function __construct() {

		self::$this_plugin = plugin_basename( __FILE__ );

		// is main plugin active? If not, throw a notice and deactivate
		if ( ! in_array( 'apppresser/apppresser.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			add_action( 'all_admin_notices', array( $this, 'apppresser_required' ) );
			return;
		}

		// Load translations
		load_plugin_textdomain( 'apppresser-swipers', false, 'apppresser-swipers/languages' );

		// Define plugin constants
		$this->basename			=	plugin_basename( __FILE__ );
		$this->directory_path	=	plugin_dir_path( __FILE__ );
		$this->directory_url	=	plugin_dir_url( __FILE__ );

		// Enqueue scripts & styles
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts_styles' ) );

		// Add slider image sizes
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		require_once $this->directory_path . 'inc/Swiper_CPT.php';
		require_once $this->directory_path . 'inc/Swiper_Shortcodes.php' ;

		Swiper_CPT::run();
		Swiper_Shortcodes::run();
	}

	public function apppresser_required() {
		echo '<div id="message" class="error"><p>'. sprintf( __( '%1$s requires the AppPresser Core plugin to be installed/activated. %1$s has been deactivated.', 'apppresser-swipers' ), self::PLUGIN ) .'</p></div>';
		deactivate_plugins( self::$this_plugin, true );
	}

	function scripts_styles() {
		// Only use minified files if SCRIPT_DEBUG is off
		$min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'idangerous-swiper', $this->directory_url ."js/idangerous.swiper-2.2$min.js", null, '2.2', true );
		wp_register_script( 'picturefill', $this->directory_url .'js/picturefill.js', array( 'idangerous-swiper' ), self::VERSION, true );
		wp_register_script( 'apppresser-swiper', $this->directory_url ."js/apppresser-swiper$min.js", array( 'picturefill', 'jquery', 'idangerous-swiper' ), self::VERSION, true );
	}

	function init() {

		appp_updater_add( __FILE__, self::APPP_KEY, array(
			'item_name' => self::PLUGIN, // must match the extension name on the site
			'version'   => self::VERSION,
		) );

		// Adds image sizes for slider
		add_image_size( 'phone', 500, 300, true ); // iPhone slider image size
		add_image_size( 'tablet', 1024, 400, true ); // iPad slider image
		// Add option section
		add_action( 'apppresser_add_settings', array( $this, 'swiper_options' ) );
	}

	function swiper_options( $appp ) {
		$appp->add_setting_label( __( 'AppSwipers', 'apppresser-swipers' ) );
		$appp->add_setting( self::APPP_KEY, __( 'AppSwipers License Key', 'apppresser-swipers' ), array( 'type' => 'license_key', 'helptext' => __( 'Adding a license key enables automatic updates.', 'apppresser-swipers' ) ) );
	}

}
AppPresser_Swipers::run();
