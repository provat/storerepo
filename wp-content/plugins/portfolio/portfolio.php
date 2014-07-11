<?php
/*
Plugin Name: portfolio
Plugin URI: http://ankan.com/?return=true
Description: Custom Content Creator
Version: 1.0.0
Author: Ankan
Author URI: http://ankan.com/
*/
require_once 'inc/function.php';
//require_once 'controllers/geoplugin.class.php';
require_once 'controllers/upload.php';
require_once 'controllers/project_action.php';
require_once 'controllers/my_project_list.php';
require_once 'controllers/display_project_list.php';
require_once 'controllers/edit_user_profile.php';
if(is_admin()){
    require_once 'admin/project_metha_box.php';
}



function addall_portfolio_imageUpload_JS_enqueuer() {

    $plugins_url = plugins_url('',__FILE__);

    //$my_url_site=str_replace("http://","https://",$my_url_site);



    wp_register_script('jquery_ui',$plugins_url."/js/jquery-ui.min.js");

    wp_enqueue_script('wp_ajax_url', $plugins_url.'/js/test.js');
    wp_localize_script( 'wp_ajax_url', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));



    if (is_user_logged_in() ) {
    wp_register_script('jquery_ui_core',$plugins_url."/js/jquery-ui-core.js");


    wp_register_script( "project_action",$plugins_url.'/js/project_action.js');

    wp_register_script( "crop_image_plugin",$plugins_url.'/js/jquery.imgareaselect.pack.js');
    wp_register_script( "cus_themes",$plugins_url.'/js/jquery.knob.js',false,false,true );
    wp_register_script( "myall_plugins",$plugins_url.'/js/jquery.ui.widget.js',false,false,true );
    wp_register_script( "myall2_plugins",$plugins_url.'/js/jquery.iframe-transport.js',false,false,true );
    wp_register_script( "myall3_plugins",$plugins_url.'/js/jquery.fileupload.js',false,false,true );
    wp_register_script( "myall4_plugins",$plugins_url.'/js/script.js',false,false,true );
    wp_register_script( "image_replace",$plugins_url.'/js/image_replace.js',false,false,true );
    //wp_register_script( "cover_image_upload",$plugins_url.'/js/upload_cover_image.js',false,false,true );
    wp_register_script( "toolbar_action",$plugins_url.'/js/toolbar_action.js',false,false,true );
    wp_register_script( "toolbar_action_patch",$plugins_url.'/js/toolbar_action_patch.js',false,false,true );
    wp_register_script( "jquery_dropdown",$plugins_url.'/js/chosen.jquery.js',false,false,true );
    wp_register_script( "jquery_tag_it",$plugins_url.'/js/tag-it.js',false,false,true );
    wp_register_script( "dropdown_config",$plugins_url.'/js/dropdown_config.js',false,false,true );
    wp_enqueue_style( 'upd_css',$plugins_url.'/css/style.css' );
    wp_enqueue_style( 'crop_css',$plugins_url.'/css/imgareaselect-default.css' );
    wp_enqueue_style( 'choosen_dropdown_css',$plugins_url.'/css/chosen.css' );
    wp_enqueue_style( 'tag_it_css',$plugins_url.'/css/jquery.tagit.css' );
    wp_enqueue_style( 'tag_it_css_jq',$plugins_url.'/css/tagit.ui-zendesk.css' );
    wp_enqueue_style( 'jquery-ui',$plugins_url.'/css/jquery-ui.css' );

        wp_enqueue_script( 'wp_ajax_url' );

	    if(!is_admin()){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery_ui' );
		//wp_enqueue_script( 'jquery_ui_core' );
		wp_enqueue_script('cus_themes');
		wp_enqueue_script('myall_plugins');
		wp_enqueue_script('myall2_plugins');
		wp_enqueue_script('myall3_plugins');
		wp_enqueue_script('crop_image_plugin');

		wp_enqueue_script('toolbar_action');

		wp_enqueue_script('jquery_tag_it');
		wp_enqueue_script('toolbar_action_patch');
		wp_enqueue_script('myall4_plugins');

		wp_enqueue_script('image_replace');

		wp_enqueue_script('jquery_dropdown');
		wp_enqueue_script('dropdown_config');

		wp_enqueue_script('project_action');


	  }

   }
}

function share_your_location_script(){
   $plugins_url = plugins_url('',__FILE__);
   wp_register_script( "display_project_list",$plugins_url.'/js/loard_project_list.js');
   wp_enqueue_script('display_project_list');

}

function my_profile_script(){
   $plugins_url = plugins_url('',__FILE__);
   wp_register_script( "my_profile_validator_js",$plugins_url.'/js/jquery.validate.min.js');
   wp_register_script( "my_profile_js",$plugins_url.'/js/my_profile.js');

   wp_enqueue_script('my_profile_validator_js');
   wp_enqueue_script('my_profile_js');

}

function display_project_by_country_script(){
   $plugins_url = plugins_url('',__FILE__);
   wp_register_script( "project_by_location_js",$plugins_url.'/js/loard_project_list_by_country.js');
   wp_enqueue_script('project_by_location_js');

}

function display_project_by_category_script(){
   $plugins_url = plugins_url('',__FILE__);
   wp_register_script( "project_by_category_js",$plugins_url.'/js/loard_project_list_by_category.js');
   wp_enqueue_script('project_by_category_js');

}


function display_project_details_script(){
   $plugins_url = plugins_url('',__FILE__);
   wp_register_script( "project_by_details_js",$plugins_url.'/js/project_details.js');
   wp_enqueue_script('project_by_details_js');

}

add_action( 'init', 'addall_portfolio_imageUpload_JS_enqueuer' );


function get_upload_form(){
    if (is_user_logged_in() ) {
      $user_id = get_current_user_id();

         $user_status=get_user_meta($user_id, "pw_user_status", true);
       if($user_status=="approved"){
         get_portfolio_toolbar();
         include_once 'get_upload_form.php';
         include_once 'views/portfolio_images.php';
        }else
        echo "You are not able to upload project. After admin approval you will be able to create/publish project";
    }

}

function get_portfolio_toolbar(){

     include_once 'views/get_portfolio_toolbar.php';
}

add_shortcode( 'upload_form', 'get_upload_form' );



