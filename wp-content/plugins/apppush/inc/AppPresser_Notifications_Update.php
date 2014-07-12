<?php
/**
 * Updates or creates a new post with notifications post meta.
 * @since  1.0.0
 */
class AppPresser_Notifications_Update {

	/**
	 * Pushwoosh API url
	 * @var string
	 */
	public $api_url = 'https://cp.pushwoosh.com/json/1.3/';

	/**
	 * Our WordPress hooks
	 * @since  1.0.0
	 */
	public function hooks() {

		// send push notification for notifications cpt
		add_action( 'publish_'. AppPresser_Notifications::$cpt, array( $this, 'send_push_notification' ), 10, 2 );

		// send push notifications from any cpt
		$post_types = appp_get_setting( 'notifications_post_types' );

		if ( is_array( $post_types ) ) {

			// loop through our post types from our settings
			foreach ( $post_types as $type ) {

				// And hook in our metabox and publish methods
				add_action( "add_meta_boxes_$type", array( $this, 'add_meta_box' ) );
				add_action( "publish_$type", array( $this, 'save' ), 10, 2 );

			}

		}

	}

	/**
	 * Check if we want to output debug form data to the content
	 * @since  1.0.0
	 * @return boolean True if debug mode is on
	 */
	public function is_debug() {
		return ( isset( $_POST['app_debug'] ) || defined( 'APPPRESSER_DEBUG' ) && APPPRESSER_DEBUG );
	}

	public function send_post_request( $action, $data ) {
		// Build url
		$url  = $this->api_url . $action;

		// Encode our data
		$data = json_encode( array( 'request' => $data ) );

		// Define the args for the API
		$args['body'] = $data;
		$args['headers']['Content-Type'] = 'Content-Type: application/json';

		// call the API via the WP HTTP API
		$response = wp_remote_post( esc_url_raw( $url ), $args );
		$response = wp_remote_retrieve_body( $response );

		if ( ! $response )
			return false;

		return $response;

	}

	public function notification_send( $send_date, $content, $badges ){

		$pw_auth = appp_get_setting( 'notifications_pushwoosh_api_key' );

		if ( $pw_auth ) {

			$pw_application = appp_get_setting( 'notifications_pushwoosh_app_key' );

			$response = $this->send_post_request( 'createMessage', array(
				'application'   => $pw_application,
				'auth'          => $pw_auth,
				'notifications' => array( array(
					'send_date'  => $send_date, // now
					'content'    => $content,
					'ios_badges' => absint( $badges ),
					'data'       => array( 'custom' => 'json data' ),
					'link'       => 'http://pushwoosh.com/'
				) ) )
			);

			if ( $this->is_debug() ) {

				wp_die( '<xmp>$response: '. print_r( $response, true ) .'</xmp>' );

			}

		}

	}

	public function send_push_notification( $post_id, $post ){

		$title = $post->post_title;

		if ( ! empty( $post->post_excerpt ) )
			$title .= ' - ' . $post->post_excerpt;

		$this->notification_send( 'now', $title, 1 );

	}

	public function add_meta_box( $post ) {

		// Don't show metabox once post has been published
		if ( 'publish' == $post->post_status )
			return;

		add_meta_box(
			'appnotifications',
			__( 'AppNotifications', 'apppresser-push' ),
			array( $this, 'notification_metabox' ),
			$post->post_type,
			'side',
			'high'
		);

	}

	public function notification_metabox( $post ) {

		$post_type = get_post_type_object( $post->post_type );

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'appnotifications_inner_custom_box', 'appnotifications_inner_custom_box_nonce' );
		echo '<input type="checkbox" name="send_push_notification" value="1"> '. sprintf( __( 'Send a push notification when this %s is published.', 'apppresser-push' ), $post_type->labels->singular_name );

	}

	public function save( $post_id, $post ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['appnotifications_inner_custom_box_nonce'], $_POST['send_push_notification'] ) )
			return;

		$nonce = $_POST['appnotifications_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'appnotifications_inner_custom_box' ) )
			return;

		// If this is an autosave, our form has not been submitted,
		// so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		// send push notification
		$this->send_push_notification( $post_id, $post );

	}

}
