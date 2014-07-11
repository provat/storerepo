<?php
function get_number_of_image($project_id){

      $user_id = get_current_user_id();

      if(empty($project_id))
        $project_id=0;

      global $wpdb;
      $wpdb->get_results($wpdb->prepare(
                  "
                  SELECT * FROM ".$wpdb->prefix."portfolio_images pi, ".$wpdb->prefix."posts p
                   WHERE p.post_author=%d AND p.ID=pi.image_id AND pi.portfolio_id=%d

                  ",
                  $user_id, $project_id
            ) );
      return  $wpdb->num_rows;
  }


function remove_width_and_height_attribute( $html ) {
   return preg_replace( '/(height|width)="\d*"\s/', "", $html );
}


function get_ID($post_slug) {
    global $wpdb;

   $page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s ", $post_slug ) );

    return $page;
}

function getLocationInfoByIp(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
    $result  = array('country'=>'', 'city'=>'');
    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip = $forward;
    }else{
        $ip = $remote;
    }
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
    if($ip_data && $ip_data->geoplugin_countryName != null){
        $result['country'] = $ip_data->geoplugin_countryCode;
        $result['city'] = $ip_data->geoplugin_city;
        $result['ip_address']=$ip;
        $result['country_name']=$ip_data->geoplugin_countryName;
        $result['geoLatitude']=$ip_data->geoplugin_latitude;
        $result['geoLongitude']=$ip_data->geoplugin_longitude;
        $result['geoState']=$ip_data->geoplugin_region;
    }
    return $result;
}

function ziplatlotinfo($zipcode)
{
    $address = $zipcode;
    $coordinates = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=true');
    $coordinates = json_decode($coordinates);

    $lat =  $coordinates->results[0]->geometry->location->lat;
    $long = $coordinates->results[0]->geometry->location->lng;
    return $lat."|".$long;
}


add_action( 'init', 'create_project_post_type' );
function create_project_post_type() {

    $args_cg = array(
		'labels' => array
        (
            'name' => 'Stores',
            'singular_name' => 'Store',
            'add_new' => 'Add Store',
            'add_new_item' => 'Add New Store',
            'edit_item' => 'Edit Store',
            'new_item' => 'New Store',
            'all_items' => 'All Stores',
            'view_item' => 'View Stores',
            'search_items' => 'Search Stores',
            'not_found' => 'No Stores found',
            'not_found_in_trash' => 'No Stores found in Trash',
            'parent_item_colon' => 'Parent Page',
            'menu_name' => 'Stores',
            'rewrite' => true,
            'show_in_nav_menus' => true
        ),
    	'taxonomies' => array('category'),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => false,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array( 'title', 'editor', 'author','thumbnail','custom-fields' )




    );


	register_post_type( 'projects',$args_cg);
}

add_theme_support( 'post-thumbnails', array( 'portfolio' ) );


function content_limit($limit, $content) {
$theContent = $content;
$output = preg_replace('/<img[^>]+./','', $theContent);
$output = preg_replace( '/<blockquote>.*<\/blockquote>/', '', $output );
//$output = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $output );
$limits = $limit+1;
$content = explode(' ', $output, $limits);


$rest_content=$content[$limit];
if(count($content) >= $limits){
    array_pop($content);
    $tranked_content=implode(" ",$content);
}  else {
    $tranked_content = implode(" ",$content);
}

$content_data[0] = $tranked_content;



//var_dump($all_content);
$content_data[1]=$rest_content;
//$content_data[1]= str_replace($tranked_content,"", $output);

return $content_data;
}



// rewrite rulse for worldwide location

function prefix_worldwide_rewrite_rule() {
    add_rewrite_rule( 'location/([^/]+)', 'index.php?location=$matches[1]', 'top' );

}

add_action( 'init', 'prefix_worldwide_rewrite_rule' );



function prefix_register_query_var( $vars ) {
    $vars[] = 'location';
    return $vars;
}

add_filter( 'query_vars', 'prefix_register_query_var' );


function project_worldwide_template_include() {

            return get_template_directory() . '/project_by_location.php';
        }

function prefix_url_rewrite_templates() {

    if ( get_query_var( 'location' ) ) {
        add_filter( 'template_include', 'project_worldwide_template_include');
    }


}

add_action( 'template_redirect', 'prefix_url_rewrite_templates' );



// for profile rewrite rules


function prefix_profile_rewrite_rule() {
    add_rewrite_rule( 'profile/([^/]+)', 'index.php?profile=$matches[1]', 'top' );

}

add_action( 'init', 'prefix_profile_rewrite_rule' );



function prefix_register_profile_query_var( $vars ) {
    $vars[] = 'profile';
    return $vars;
}

add_filter( 'query_vars', 'prefix_register_profile_query_var' );


function my_profile_template_include() {

            return get_template_directory() . '/single-contrib-details.php';
        }

function prefix_url_profile_rewrite_templates() {

    if ( get_query_var( 'profile' ) ) {
        add_filter( 'template_include', 'my_profile_template_include');
    }


}

add_action( 'template_redirect', 'prefix_url_profile_rewrite_templates' );

/**
 * Include JS and CSS for lightbox like pop up in Project details page
 */
add_action("wp_footer", "add_js_css_for_lightbox_popup");
function add_js_css_for_lightbox_popup() {
  global $post;
  if(is_singular("projects")) {
    wp_enqueue_style("mfpcss", site_url() . "/wp-content/plugins/portfolio/css/magnific-popup.css");
    wp_enqueue_style("mfp-custom", site_url() . "/wp-content/plugins/portfolio/css/magnific-custom.css");

    wp_enqueue_script("mfp-js", site_url() . "/wp-content/plugins/portfolio/js/jquery.magnific-popup.min.js");
    wp_enqueue_script("mfp-initjs", site_url() . "/wp-content/plugins/portfolio/js/magnifying-popup-init.js");
  }
}


function getlatlang($location)
{
  $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='. urlencode($location) .'&sensor=false');
  $output= json_decode($geocode);

  return $output->results[0]->geometry->location;
}

// Disable Admin Bar for everyone
if (!function_exists('df_disable_admin_bar')) {

  function df_disable_admin_bar() {
<<<<<<< HEAD

=======
    
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
    // for the admin page
    remove_action('admin_footer', 'wp_admin_bar_render', 1000);
    // for the front-end
    remove_action('wp_footer', 'wp_admin_bar_render', 1000);
<<<<<<< HEAD

    // css override for the admin page
    function remove_admin_bar_style_backend() {
      echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>';
    }
    add_filter('admin_head','remove_admin_bar_style_backend');

=======
      
    // css override for the admin page
    function remove_admin_bar_style_backend() { 
      echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>';
    }   
    add_filter('admin_head','remove_admin_bar_style_backend');
    
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20
    // css override for the frontend
    function remove_admin_bar_style_frontend() {
      echo '<style type="text/css" media="screen">
      html { margin-top: 0px !important; }
      * html body { margin-top: 0px !important; }
      </style>';
    }
    add_filter('wp_head','remove_admin_bar_style_frontend', 99);
    }
}
add_action('init','df_disable_admin_bar');