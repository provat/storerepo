<?php
/**
 * AppPresser Child Theme Settings Setup.
 *
 * @package AppTheme Child Theme
 * @since   1.0.0
 */
class AppPresser_Child_Theme_Settings {

	public function __construct() {
		// Add it late to be below extension options
		add_action( 'apppresser_add_settings', array( $this, 'theme_options' ), 80 );
		add_action( 'apppresser_tab_bottom_general', array( $this, 'add_row_text' ) );
	}

	/**
	 * Add a setting label and license input to the settings page
	 * @since  1.0.0
	 * @param  AppPresser_Admin_Settings object $appp The AppPresser_Admin_Settings instance
	 */
	public function theme_options( $appp ) {
		// Add a settings section to the AppPresser settings page
		$appp->add_setting_label( __( 'Child Theme Settings', 'apppresser' ) );

		// Add a setting to the AppPresser settings page
		$appp->add_setting( 'your_setting_id', __( 'Your Setting', 'apppresser' ), array(
			'type' => 'text',
			'helptext' => __( 'This is a helpful description about the setting.', 'apppresser' ),
		) );
	}

	public function add_row_text() {
		echo '<tr><td colspan="2">'. __( 'Some notes about your child theme.', 'appchild-theme' ) .'</td></tr>';
	}

}

new AppPresser_Child_Theme_Settings();