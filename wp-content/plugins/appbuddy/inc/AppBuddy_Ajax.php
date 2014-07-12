<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * AppBuddy_Ajax class.
 */
class AppBuddy_Ajax {


	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->setup_actions();
	}


	/**
	 * setup_actions function.
	 * 
	 * @access private
	 * @return void
	 */
	private function setup_actions() {
		add_action('wp_ajax_nopriv_lost_password', array( $this, 'apppbuddy_reset_password') );
		add_action('wp_ajax_nopriv_validate_password', array( $this, 'apppbuddy_validate_password_code') );
	}
	
	
	/**
	 * apppbuddy_reset_password function.
	 * 
	 * @access public
	 * @return void
	 */
	public function apppbuddy_reset_password() {
	
		$nonce = $_POST['nonce'];
		$email = $_POST['email'];
		
		if ( !wp_verify_nonce( $nonce, 'new_password' ) ) return;
		
		$user = get_user_by( 'email', $email );
		
		if( $user ) {
		
			$time = current_time( 'mysql' );
			// create a unique code to use one time
			$hash = hash( 'md5', $email . $time );
			
			update_user_meta( $user->ID, 'app_hash', $hash );
			
			$subject = __('App Password Reset', 'appbuddy');
			$message = __('Enter the code into the app to reset your password. Code: ', 'appbuddy') . $hash;
			wp_mail( $email, $subject, $message );
			
			$return = array(
				'message' =>  __('Please check your email and enter the retrieval code below.', 'appbuddy')
			);
			wp_send_json_success( $return );
				
		} else {
		
			$return = array(
				'message' =>  __('The email you have entered is not valid.', 'appbuddy')
			);
			wp_send_json_error( $return );
			
		}
	}

	
	/**
	 * apppbuddy_validate_password_code function.
	 * 
	 * @access public
	 * @return void
	 */
	public function apppbuddy_validate_password_code() {
		global $wpdb;
	
		$nonce 		= $_POST['nonce'];
		$code 		= $_POST['code'];
		$password 	= $_POST['password'];
		
		if ( !wp_verify_nonce( $nonce, 'new_password' ) ) return;
		
		$user = get_users( array( 'meta_key' => 'app_hash', 'meta_value' => $code ) );
	
		if( $user ) {
		
			wp_update_user( array ('ID' => $user[0]->data->ID, 'user_pass' => $password ) ) ;
			// delete our one time access code
			delete_user_meta( $user[0]->data->ID, 'app_hash');
			
			wp_set_auth_cookie( $user[0]->data->ID );
			do_action('wp_signon', $user[0]->data->user_login);
			
			$return = array(
				'message' => __('Password has been changed.', 'appbuddy')
			);
			wp_send_json_success( $return );	
			
		} else {
		
			$return = array(
				'message' =>  __('The code you have entered is not valid.', 'appbuddy')
			);
			wp_send_json_error( $return );
		}
	}

}
$AppBuddy_Ajax = new AppBuddy_Ajax();