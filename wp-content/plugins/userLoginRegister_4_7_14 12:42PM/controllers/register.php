<?php
session_start();
/**
 * This is file is repsonible for custom logic needed by all templates. NO
 * admin code should be placed in this file.
 */
Class Register Extends AjaxLogin {

    /**
     * Array of scripts, note name, must match FILE name!
     */
    public $scripts = array('register');


    /**
     * Array of stylesheets, note name, must match FILE name!
     */
    public $styles = array('register');


    /**
     * Run the following methods when this class is loaded
     */
    public function __construct(){
        add_action( 'init', array( &$this, 'init' ) );
        add_action( 'wp_footer', array( &$this, 'footer' ) );

        parent::__construct();
    }


    /**
     * During WordPress' init load various methods.
     */
    public function init(){
        add_action( 'wp_ajax_nopriv_register_submit', array( &$this,'register_submit' ) );
        add_action( 'wp_ajax_register_submit', array( &$this,'register_submit' ) );
	add_action( 'wp_ajax_nopriv_register_submit_part2', array( &$this,'register_submit_part2' ) );
        add_action( 'wp_ajax_register_submit_part2', array( &$this,'register_submit_part2' ) );
        add_shortcode( 'ajax_register', array( &$this, 'register_shortcode' ) );
    }


    /**
     * Any additional code to be ran during wp_footer
     *
     * If the user is not logged in we display the hidden jQuery UI dialog containers
     */
    public function footer(){ 
        if ( !is_user_logged_in() ) { 
		//if($_SESSION['user_id']=='')
        	    load_template( plugin_dir_path( dirname( __FILE__ ) ) . 'views/register-dialog.php' );
		//else
		   // load_template( plugin_dir_path( dirname( __FILE__ ) ) . 'views/register-dialog-part2.php' );
        }
    }


    /**
     * Registers a new user, checks if the user email or name is
     * already in use.
     *
     * @uses check_ajax_referer() http://codex.wordpress.org/Function_Reference/check_ajax_referer
     * @uses get_user_by_email() http://codex.wordpress.org/Function_Reference/get_user_by_email
     * @uses get_user_by() http://codex.wordpress.org/Function_Reference/get_user_by
     * @uses wp_create_user() http://codex.wordpress.org/Function_Reference/wp_create_user
     *
     * @param $login
     * @param $password
     * @param $email
     * @param $is_ajax
     */
    public function register_submit( $login=null, $password=null, $email=null, $is_ajax=true ) {

        if ( $is_ajax ) check_ajax_referer('register_submit','security');

        // TODO consider using wp_generate_password( $length=12, $include_standard_special_chars=false );
        // and wp_mail the users password asking to change it.
        $user = array(
            'login'    => empty( $_POST['login'] ) ? $login : $_POST['login'],
            'email'    => empty( $_POST['email'] ) ? $email : $_POST['email'],
            'password' => empty( $_POST['password'] ) ? $password : $_POST['password'],
            'fb_id'    => empty( $_POST['fb_id'] ) ? false : $_POST['fb_id']
        );

        $valid['email'] = $this->validate_email( $user['email'], false );
        $valid['username'] = $this->validate_username( $user['login'], false );
        $user_id = null;

        if ( $valid['email']['status'] == 1 // default error
            || $valid['username']['status'] == 2 // invalid user
            || $valid['username']['status'] == 3 // invalid email
            ) {
            $msg = $this->status[2]; // invalid user
        } else {

            //$user_id = wp_create_user( $user['login'], $user['password'], $user['login'] );
	    //$_SESSION['user_id'] = $user_id;
            if ( ! is_wp_error( $user_id ) ) {

                update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
                update_user_meta( $user_id, 'fb_id', $user['fb_id'] );

                wp_update_user( array( 'ID' => $user_id, 'role' => 'subscriber' ) );
                //$wp_signon = wp_signon( array( 'user_login' => $user['login'], 'user_password' => $user['password'], 'remember' => true ), false );
                $msg = $this->status[0]; // success
            } else {
                $msg = $this->status[2]; // invalid user
            }
        }

        if ( $is_ajax ) {
            print json_encode( $msg );
            die();
        } else {
            return $msg;
        }
    }

	

   public function register_submit_part2( $first_name=null, $last_name=null, $profile_name=null, $country=null,$city=null, $birth_month=null,$birth_day=null, $birth_year=null, $is_ajax=true ) {

        if ( $is_ajax ) check_ajax_referer('register_submit','security');

        // TODO consider using wp_generate_password( $length=12, $include_standard_special_chars=false );
        // and wp_mail the users password asking to change it.
	
	$user_id = $_POST['hdnRegisterId'];
	$email = $_POST['hdnEmail'];
	$password = $_POST['hdnPassword'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $profile_name = $_POST['profile_name'];
	$country = $_POST['country'];
	$city = $_POST['city'];
        $birth_day  = $_POST['birth_year'].'-'.$_POST['birth_month'].'-'.$_POST['birth_day'];
	$username = $first_name.' '.$last_name;
	global $wpdb;
	$exist_profile = $wpdb->get_row(  "select * from ".$wpdb->prefix."usermeta where meta_key = 'profile_name' AND meta_value = '".$profile_name."'"); 
		
		if($user_id==''){
			
			if(!$exist_profile){
			    	$user_id = wp_create_user( $email, $password, $email );

				wp_update_user(array( 'ID' => $user_id,'display_name' => $username));
				update_user_meta( $user_id, 'first_name', $first_name );
				update_user_meta( $user_id, 'last_name', $last_name );
				update_user_meta( $user_id, 'profile_name', $profile_name );
				update_user_meta( $user_id, 'country', $country );
				update_user_meta( $user_id, 'city', $city );
				update_user_meta( $user_id, 'birth_day', $birth_day );
				$msg = $this->status[0]; // success
                                
                                $creds = array(
                                'user_login'    => $email,
                                'user_password' => $password,
                                'remember'      => null
                                );
                                
                                $user = wp_signon( $creds, false );
			}else{
				$msg = $this->status[4]; // fail
			}
		}
		else{
			if(!$exist_profile){
			wp_update_user(array( 'ID' => $user_id,'display_name' => $username));
		        update_user_meta( $user_id, 'first_name', $first_name );
		        update_user_meta( $user_id, 'last_name', $last_name );
			update_user_meta( $user_id, 'profile_name', $profile_name );
			update_user_meta( $user_id, 'country', $country );
			update_user_meta( $user_id, 'city', $city );
			update_user_meta( $user_id, 'birth_day', $birth_day );
			 // success
			 $creds = array(
			    'user_login'    => $email,
			    'user_password' => $password,
			    'remember'      => null
			    );
			$user = wp_signon( $creds, false );
			$msg = $this->status[0];
			}else{
				$msg = $this->status[4]; // fail
			}

		}

                //wp_update_user( array( 'ID' => $user_id, 'role' => 'subscriber' ) );
                //$wp_signon = wp_signon( array( 'user_login' => $user['login'], 'user_password' => $user['password'], 'remember' => true ), false );
                
           

        if ( $is_ajax ) {
            print json_encode( $msg );
            die();
        } else {
            return $msg;
        }
    }


    /**
     * Load the login shortcode
     */
    public function register_shortcode(){
        load_template( plugin_dir_path( dirname( __FILE__ ) ) . 'views/register-form.php' );
    }


     

    
}
new Register;
