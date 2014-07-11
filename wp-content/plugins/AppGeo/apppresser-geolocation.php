<?php
/*
Plugin Name: AppGeo
Plugin URI: http://apppresser.com
Description: Integrates device geolocation with AppPresser
Text Domain: apppresser-geolocation
Domain Path: /languages
Version: 1.0.4
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

class AppPresser_Geolocation {

	// A single instance of this class.
	public static $instance     = null;
	public static $this_plugin  = null;
	public static $plugin_url   = null;
	public static $plugin_path  = null;
	public static $fields_added = false;
	public static $sc_added     = false;
	public $geo_cookie          = false;
	const APPP_KEY              = 'appgeo_key';
	const PLUGIN                = 'AppGeo';
	const VERSION               = '1.0.4';

	/**
	 * Creates or returns an instance of this class.
	 * @since  1.0.0
	 * @return AppPresser_Geolocation A single instance of this class.
	 */
	public static function go() {
		if ( self::$instance === null )
			self::$instance = new self();

		return self::$instance;
	}

	/**
	 * Add hooks
	 * @since  1.0.0
	 */
	public function __construct() {
		self::$this_plugin = plugin_basename( __FILE__ );
		self::$plugin_url  = plugins_url( '/' , __FILE__ );
		self::$plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );

		// is main plugin active? If not, throw a notice and deactivate
		if ( ! in_array( 'apppresser/apppresser.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			add_action( 'all_admin_notices', array( $this, 'apppresser_required' ) );
			return;
		}

		// Load translations
		load_plugin_textdomain( 'apppresser-geolocation', false, 'apppresser-geolocation/languages' );

		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		add_action( 'plugins_loaded', array( $this, 'hook' ) );

		// Add cordova geo plugins
		add_filter( 'apppresser_phonegap_plugin_packages', array( $this, 'phonegap_geo' ) );

		// Register geolocation js
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts_styles' ) );

		// Add shortcode
		add_shortcode( 'app-geolocation', array( $this, 'geolocation' ) );

		// Ajax for saving user geoloation meta
		add_action( 'wp_ajax_appp_geo_user', array( $this, 'appp_geo_user' ) );
		add_action( 'wp_ajax_nopriv_appp_geo_user', array( $this, 'appp_geo_user' ) );

		// User profile fields
		add_action( 'show_user_profile', array( $this, 'extra_user_profile_fields' ) );

		// Add geolocation data to user meta
		add_action( 'init', array( $this, 'geolocation_user_meta_cookie' ) );
		add_action( 'wp_footer', array( $this, 'geolocation_user_meta_save' ) );

		// Add map to posts with geolocation data
		add_filter( 'the_content', array( $this, 'geolocation_post_map' ) );

		// Add geolocation fields to geolocation shortcode
		add_action( 'appp_before_geolocation_buttons', array( $this, 'geolocation_shortcode_fields' ), 1, 1 );
		add_action( 'appp_after_geolocation_buttons', array( $this, 'geolocation_map_preview' ), 1, 1 );

		// process geolocation post data
		add_action( 'appp_after_process_geolocation', array( $this, 'geolocation_process_post_meta' ), 1 );

		// Camera add-on shortcode geolocation atts
		add_filter( 'shortcode_atts_appp_shortcode_camera', array( $this, 'camera_shortcode_atts' ), 1, 3);
		// Add geolocation fields to camera addon shortcode
		add_action( 'appp_before_camera_buttons', array( $this, 'geolocation_shortcode_fields' ), 1, 1 );
		// Add map_preview to camera
		add_action( 'appp_after_camera_buttons', array( $this, 'geolocation_map_preview' ), 1, 1 );
		// Process camera post geolocation data
		add_action( 'appp_after_process_uploads', array( $this, 'geolocation_process_post_meta' ), 1 );
	}

	public function geolocation_map_preview( $atts ){
		if ( $atts['map_preview'] == 'true' && ! isset( $this->map_image_added ) ) {
			echo '<img id="appp_map_preview_img" src="">';
			$this->map_image_added = true;
		}
	}

	public function apppresser_required() {
		echo '<div id="message" class="error"><p>'. sprintf( __( '%1$s requires the AppPresser Core plugin to be installed/activated. %1$s has been deactivated.', 'apppresser-geolocation' ), self::PLUGIN ) .'</p></div>';
		deactivate_plugins( self::$this_plugin, true );
	}

	/**
	 * Update APPP_KEY and include files
	 * @since  1.0.0
	 */
	public function hook() {

		appp_updater_add( __FILE__, self::APPP_KEY, array(
			'item_name' => self::PLUGIN, // must match the extension name on the site
			'version'   => self::VERSION,
		) );

		// Include geolocation settings
		require_once( self::$plugin_path.'inc/AppPresser_GeoLocation_Update.php' );
		$this->geo_update = new AppPresser_GeoLocation_Update();
		$this->geo_update->hooks();

		require_once( self::$plugin_path.'inc/AppPresser_GeoLocation_Settings.php' );
		$this->geo_settings = new AppPresser_GeoLocation_Settings();
		$this->geo_settings->hooks();
	}

	/**
	 * Calculate saved time settings for cookies
	 * @since  1.0.0
	 * @return int   Time in seconds for the cookie expiration
	 */
	public function time_amount() {
		if ( isset( $this->time_x ) )
			return $this->time_x;

		$this->time_x = absint( appp_get_setting( 'geolocation_time_x', 1 ) ) * absint( appp_get_setting( 'geolocation_time_incr', HOUR_IN_SECONDS ) );

		return $this->time_x;
	}

	/**
	 * Creates cookie that determins if and when a logged in user's geolocation should be saved
	 * @since  1.0.0
	 */
	public function geolocation_user_meta_cookie() {
		global $user_ID;
		if ( ! $user_ID )
			return;
		if ( ! appp_get_setting( 'geolocation_track_users' ) )
			return;

		if ( ! isset( $_COOKIE['Appp_Geolocation'] ) ) {
			setcookie( 'Appp_Geolocation', 'true', time() + $this->time_amount() );
		}

		$this->geo_cookie = isset( $_COOKIE['Appp_Geolocation'] ) ? $_COOKIE['Appp_Geolocation'] : false;

	}

	/**
	 * echos the span to trigger jquery ajax check for updating user location meta
	 * @since  1.0.0
	 */
	public function geolocation_user_meta_save() {
		global $user_ID;
		// if ( $user_ID && appp_get_setting( 'geolocation_track_users' ) && $this->geo_cookie )
		if ( $user_ID && appp_get_setting( 'geolocation_track_users' ) )
			echo '<span id="appgeo-geolocate-user-trigger"></span>';
	}

	/**
	 * Ajax php action to save users longitude & latitude
	 * @since  1.0.0
	 */
	public function appp_geo_user() {
		global $user_ID;
		if ( $user_ID && appp_get_setting( 'geolocation_track_users' ) ) {
			if ( isset( $_POST['longitude'] ) && $_POST['longitude'] )
				update_user_meta( $user_ID, 'appp_longitude', sanitize_text_field( $_POST['longitude'] ) );
			if ( isset( $_POST['latitude'] ) && $_POST['latitude'] )
				update_user_meta( $user_ID, 'appp_latitude', sanitize_text_field( $_POST['latitude'] ) );
			wp_send_json_success( $_POST['longitude'] );
			// wp_send_json_error( '' );
			// wp_send_json_success();
		}
	}

	/**
	 * Show user location on wp-admin/profile.php
	 * @since  1.0.0
	 */
	public function extra_user_profile_fields() {
		echo '<h3>'.__( 'GeoLocation', 'apppresser-geolocation' ).'</h3>';
		do_action( 'appp_user_profile_fields', $user_id );

	}

	/**
	 * Filters a post's content and outputs a map if the post has geolocation post meta
	 * @since  1.0.0
	 */
	public function geolocation_post_map( $content ) {
		// If setting is off, then bail
		if ( 'on' != appp_get_setting( 'geolocation_map' ) )
			return $content;

		// If 'map_preview' is set to true.
		if ( $this->request_map( $content ) ) {
			$content .= $this->post_map_image( get_the_ID() );
		}

		return $content;
	}

	/**
	 * Checks post content for 'map_preview' shortcode parameter
	 * @since  1.0.3
	 * @param  string $content Content to check
	 * @return bool            True if map_preview exists and is not set to false
	 */
	public function request_map( $content ) {
		// If no map_preview flag, return false
		if ( false === stripos( $content, 'map_preview' ) ) {
			return false;
		}
		// Check if map_preview is NOT set to FALSE (w/ or w/o quotes)
		// Any other string, we'll consider to be true
		return ! preg_match( '/map_preview=(\"|\')?false(\"|\')?(?=.*])/i', $content );
	}

	/**
	 * Function that displays a Google map image of a specified post ID
	 * @since  1.0.0
	 * @param  int  $post_id Post ID
	 * @return mixed         Map image markup if success
	 */
	public function post_map_image( $post_id ) {
		// Only one "appp_map_preview_img" id per page
		if ( isset( $this->map_image_added ) )
			return;

		$this->map_image_added = true;

		$longitude = get_post_meta( $post_id, 'appp_longitude', 1 );
		$latitude = get_post_meta( $post_id, 'appp_latitude', 1 );

		if ( $longitude && $latitude ) {
			return '<img id="appp_map_preview_img" src="http://maps.googleapis.com/maps/api/staticmap?zoom=17&size=600x300&maptype=roadmap&markers=color:red%7Ccolor:red%7Clabel:%7C'. $latitude .','. $longitude .'&sensor=false">';
		}

	}

	/**
	 * Adds additional geolocation based attributes to the camera shortcode (if camera add-on is activated)
	 * @since  1.0.0
	 */
	public function camera_shortcode_atts( $merged, $defaults, $atts ) {

		wp_enqueue_script( 'appp-geolocation' );

		$merged['geolocation'] = isset( $atts['geolocation'] ) && ( $atts['geolocation'] != false || $atts['geolocation'] != 'false' ) ? 'true' : 'false';
		$merged['map_preview'] = isset( $atts['map_preview'] ) && ( $atts['map_preview'] != false || $atts['map_preview'] != 'false' ) ? 'true' : 'false';

		return $merged;

	}

	/**
	 * Geolocation shortcode form fields
	 * @since  1.0.0
	 */
	public function geolocation_shortcode_fields( $atts ) {

		if ( self::$fields_added || ! isset( $atts['geolocation'] ) || ! $atts['geolocation'] || $atts['geolocation'] == 'false' )
			return;

		?>
		<input type="hidden" name="appp_longitude" id="appp_longitude" value="">
		<input type="hidden" name="appp_latitude" id="appp_latitude" value="">
		<input type="hidden" name="appp_altitude" id="appp_altitude" value="">
		<input type="hidden" name="appp_accuracy" id="appp_accuracy" value="">
		<input type="hidden" name="appp_altitudeaccuracy" id="appp_altitudeaccuracy" value="">
		<input type="hidden" name="appp_heading" id="appp_heading" value="">
		<input type="hidden" name="appp_speed" id="appp_speed" value="">
		<input type="hidden" name="appp_timestamp" id="appp_timestamp" value="">
		<span id="appgeo-geolocate-post-trigger"></span>
		<?php
		if ( isset( $_GET['app_debug'] ) ) {
			echo '<input type="hidden" name="app_debug" value="true">';
		}
		// Ensures these fields are only added once
		self::$fields_added = true;
	}

	/**
	 * Process geolocation post meta
	 * @since  1.0.0
	 */
	public function geolocation_process_post_meta( $post_id ) {
		if ( ! $post_id )
			return;

		$meta_keys = array(
			'appp_longitude',
			'appp_latitude',
			'appp_altitude',
			'appp_accuracy',
			'appp_altitudeaccuracy',
			'appp_heading',
			'appp_speed',
			'appp_timestamp',
		);

		foreach ( $meta_keys as $meta_key ) {
			// If we have our data,
			if ( isset( $_POST[ $meta_key ] ) && $_POST[ $meta_key ] ) {
				// Then add it to our post-meta
				update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[ $meta_key ] ) );
			}

		}
	}

	/**
	 * Include Phonegap geolocation plugins
	 * @since  1.0.0
	 */
	public function phonegap_geo( $plugins ) {
		// @todo conditionally include these only when needed
		return array_merge( $plugins, array( 'geolocation' ) );
	}

	/**
	 * Register geolocation script
	 * @since  1.0.0
	 */
	public function scripts_styles() {

		// Only use minified files if SCRIPT_DEBUG is off
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'appp-geolocation', self::$plugin_url ."js/appp-geolocation$min.js", array( 'cordova-core', 'jquery' ), self::VERSION );

		wp_localize_script( 'appp-geolocation', 'geo_object', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		) );
	}

	/**
	 * 'app-geolocation' shortcode handler
	 * @since  1.0.0
	 * @param  array  $atts    Shortcode attributes
	 * @param  string $content Content within shortcoes
	 * @return string          HTML output for geolocation upload form
	 */
	public static function geolocation( $atts = null, $content = null ) {
		if ( self::$sc_added )
			return;

		wp_enqueue_script( 'appp-geolocation' );

		global $post;

		extract( $appp_atts = shortcode_atts( array(
			'geolocation' => 'true',
			'force_login' => 'false', // false or true, if true users must be logged in
			'action' => 'this', // this, new
			'post_type' => 'post', // any post type, only works with new
			'post_title' => 'false', // false or true, if yes shows a text box for the post title
			'post_content' => 'false', // false or true, if yes shows a textarea box for the post content
			'post_status' => 'publish', // draft or publish
			'map_preview' => 'false', // false or true, if true shows a map preview

		), $atts, 'appp_shortcode_geolocation' ) );

		ob_start();
		// if must be logged in
		if ( !is_user_logged_in() && $force_login == 'true' ) {
			echo '<p class="geolocation-login-text"><a class="modal-toggle" href="'. wp_login_url( get_permalink() ) .'">'. __( 'Please login', 'apppresser-geolocation' ) .'</a></p>';
		} else {
			?>
			<form method="post" name="appp_geolocation_form">
			<input type="hidden" name="appp_geo_post_id" id="appp_geo_post_id" value="<?php echo absint( $post->ID );?>">
			<?php wp_nonce_field( 'apppgeolocation-nonce', 'apppgeolocation-post' );
			do_action( 'appp_before_geolocation_buttons', $appp_atts );
			// post title
			if ( $post_title && $post_title != 'false' ) {
				echo apply_filters( 'appp_geolocation_post_title_label', '<label>' . __( 'Title:', 'apppresser-geolocation' ) . '</label>' );?>
				<input type="text" name="appp_geo_post_title" id="appp_geo_post_title" class="appp_geo_post_title" value="">
			<?php }
			// post content
			if ( $post_content && $post_content != 'false' ) {
				echo apply_filters( 'appp_geolocation_post_content_label', '<label>' . __( 'Content:', 'apppresser-geolocation' ) . '</label>' );
				$settings = array( 'media_buttons' => false, 'textarea_rows' => 5 );
				wp_editor( '', 'appp_post_content', $settings );
			}
			// create field for each shortcode att
			foreach ( $appp_atts as $att_key => $att_value) {
				//exclude some atts
				if ( $att_key != 'post_content' && $att_key != 'post_title' ) {?>
					<input type="hidden" name="appp_<?php echo $att_key;?>" id="appp_<?php echo $att_key;?>" value="<?php echo $att_value;?>">
				<?php }
			}
			?>
			<input class="btn btn-primary" type="submit" name="submit_geolocation_post" value="<?php esc_attr_e( 'Post Location', 'apppresser-geolocation' ); ?>">
			<?php if ( isset( $_POST['submit_geolocation_post'] ) ) { ?>
				<div id="geo_status"><?php _e( 'Location Recorded!', 'apppresser-geolocation' ); ?></div>
			<?php } ?>
			</form>
			<?php
			do_action( 'appp_after_geolocation_buttons', $appp_atts );
		}
		// grab the data from the output buffer and add it to our $content variable
		$content = ob_get_contents();
		ob_end_clean();

		self::$sc_added = true;
		return $content;
	}

}
AppPresser_Geolocation::go();

/**
 * Helper function wrapper for AppPresser_Geolocation::geolocation, the 'ap-geolocation' shortcode handler
 * @since  1.0.0
 * @param  array  $atts    Shortcode attributes
 * @param  string $content Content within shortcoes
 * @return string          HTML output for geolocation upload form
 */
function appp_geolocation( $atts = null, $content = null, $echo = true ) {
	$geolocation = AppPresser_Geolocation::geolocation( $atts, $content );
	if ( $echo )
		echo $geolocation;

	return $geolocation;
}
