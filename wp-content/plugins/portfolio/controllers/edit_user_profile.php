<?php
Class UserProfile {
     public function __construct(){
        add_action( 'init', array( &$this, 'init' ) );
       
        
       
    }
    
    public function init(){
            add_action( 'wp_ajax_load_edit_profile_template', array( &$this, 'load_template' ) );
            add_action( 'wp_ajax_update_my_profile', array( &$this, 'update_profile' ) );
            
    }
    
    public function load_template(){
    if (is_user_logged_in() ) { 
        //check_ajax_referer( $_POST['referer'],'security');
        $dir_f_name=dirname( __FILE__ );
        $plug_path=plugin_dir_path($dir_f_name);
        $plug_path=  str_replace($dir_f_name, "", $plug_path);
        
        load_template(  $plug_path. "views/" . $_POST['template'] . '.php' );
        }
        die();
    } 
    
    
    public function update_profile(){
        global $wpdb;
        //var_dump($_POST);
        if (is_user_logged_in() ) { 
        $user_id = get_current_user_id();    
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $profile_name = $_POST['profile_name'];
	$country = $_POST['country'];
	$city = $_POST['city'];
        $birth_day  = $_POST['birth_year'].'-'.$_POST['birth_month'].'-'.$_POST['birth_day'];
	$username = $first_name.' '.$last_name;
        $google_plus_link=$_POST['gpurl'];
        $twitter_link=$_POST['twurl'];
        $fb_link=$_POST['fburl'];
     
        
        $exist_profile = $wpdb->get_row( "select meta_value from ".$wpdb->prefix."usermeta where meta_key = 'profile_name' AND meta_value = '".$profile_name."' AND user_id!='".$user_id."'"); 
        
          
            
            if($profile_name!=  get_user_meta($user_id, "profile_name", true))
            {   
                if(!$exist_profile){
                update_user_meta( $user_id, 'profile_name', $profile_name );
                }else{
                  $data['msg']="Profile name already exist. Please use another one"; 
                  return;
                } 
            }
            wp_update_user(array( 'ID' => $user_id,'display_name' => $username));
            update_user_meta( $user_id, 'first_name', $first_name );
            update_user_meta( $user_id, 'last_name', $last_name );
                
            update_user_meta( $user_id, 'country', $country );
            update_user_meta( $user_id, 'city', $city );
            update_user_meta( $user_id, 'birth_day', $birth_day );
            update_user_meta( $user_id, 'fb_link', $fb_link );
            update_user_meta( $user_id, 'twitter_link', $twitter_link );
            update_user_meta( $user_id, 'google_plus_link', $google_plus_link );
            $data['msg'] = "Profile updated successfully";
        
        }
        print json_encode($data);
        die();
        
        
    }
    
 }new UserProfile;   
?>