<?php

class AppPresser_Camera_Ajax {

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
		add_action('wp_ajax_upload_image', array( $this, 'upload_photo') );
	}
	
	
	/**
	 * appbuddy_set_upload_dir function.
	 * 
	 * @access public
	 * @param mixed $upload
	 * @return void
	 */
	function appbuddy_set_upload_dir( $upload ) {
		$current_user = wp_get_current_user();
	
	    $upload['subdir'] = '/appcamera/' . $current_user->ID ;
	    $upload['path'] = $upload['basedir'] . $upload['subdir'];
	    $upload['url']  = $upload['baseurl'] . $upload['subdir'];
	    return $upload;
	}


	/**
	 * upload_photo function.
	 * 
	 * @access public
	 * @return void
	 */
	public function upload_photo() {
	
		global $_FILES, $_POST, $user_ID;
		
		header( "Content-Type: application/json" );

		// Make sure you're submitting files
		if ( empty( $_FILES ) 
			|| ! isset( $_FILES['appp_cam_files'] ) 
			|| isset( $_FILES['appp_cam_files']['error'] ) 
			&& $_FILES['appp_cam_files']['error'] !== 0 && !is_user_logged_in() )
		return;

		$files = array_filter( $_FILES['appp_cam_files'] );
		// Make sure you're submitting files
		if ( empty( $files ) )
			return;
			
		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'apppcamera-nonce' ) ) return;
	
		// make sure to include the media uploader
		if ( ! function_exists( 'wp_handle_upload' ) )
			require_once ABSPATH .'wp-admin/includes/file.php';

		$uploadedfile = $_FILES['appp_cam_files'];
		$upload_overrides = array( 'test_form' => false );
		
		$upload_info = wp_upload_dir();
		$upload_dir = $upload_info['basedir'];
		$upload_url = $upload_info['baseurl'];
		
		//filter upload dir into a user ID folder
		add_filter( 'upload_dir', array($this, 'appbuddy_set_upload_dir') );
		
		$uploaded_file = wp_handle_upload( $uploadedfile, $upload_overrides );
		
		remove_filter( 'upload_dir', array($this, 'appbuddy_set_upload_dir') );
		
		$image = wp_get_image_editor( $uploaded_file['file'] );
		if ( ! is_wp_error( $image ) ) {
		    $image->resize( 600, 600, false );
		    $saved = $image->save();
		}
		
		$imgrpl = str_replace( $upload_dir, $upload_url, $saved['path'] );
		
		unlink($uploaded_file['file']);

		if ( $uploaded_file ) {
			$output = json_encode($imgrpl);						
			echo $output;						
		} else {
			echo 'Image not uploaded';
		}
		die();
	}

}


/**
 * appbuddy_hidden_file_input function.
 * 
 * @access public
 * @return void
 */
function appbuddy_hidden_file_input() {
	echo '<input type="hidden" name="attach-image" id="attach-image" value="">';
}
add_action( 'bp_after_activity_post_form', 'appbuddy_hidden_file_input' );
