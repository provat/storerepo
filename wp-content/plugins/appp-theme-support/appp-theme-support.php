<?php
/*
Plugin Name: Appp Theme Support
Plugin URI: http://everystore.com
Description: This plugin helps to build required functionalty for Appp-child theme.
Text Domain: app-theme-support
Version: 1.0.0
Author: Everystore Team
Author URI: http://everystore.co
*/

class App_Theme_Support {

  // A single instance of this class.
	public static $instance     = null;
	public static $this_plugin  = null;
	public static $plugin_url   = null;
	public static $plugin_path  = null;
  const VERSION               = '1.0.0';

	/**
	 * Creates or returns an instance of this class.
	 * @since  1.0.0
	 * @return App_Test A single instance of this class.
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

		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		// Register geolocation js
		add_action( 'wp_footer', array( $this, 'scripts_styles' ) );

		// Ajax for saving user geoloation meta
		add_action( 'wp_ajax_ats_load_home', array( $this, 'ats_load_home' ) );
		add_action( 'wp_ajax_nopriv_ats_load_home', array( $this, 'ats_load_home' ) );

		add_action( 'wp_ajax_display_projects_accor_locatione', array( $this, 'display_projects_accor_location' ) );
    add_action( 'wp_ajax_profile', array( $this, 'profile' ) );
    add_action( 'wp_ajax_display_follow_projects', array( &$this, 'display_follow_projects' ) );

    add_action( 'wp_ajax_get_the_apps', array( $this, 'get_the_apps' ) );
    add_action( 'wp_ajax_about_us', array( $this, 'about_us' ) );
    add_action( 'wp_ajax_advertise_here', array( $this, 'advertise_here' ) );
    add_action( 'wp_ajax_terms_and_conditions', array( $this, 'terms_and_conditions' ) );
    add_action( 'wp_ajax_contact_us', array( $this, 'contact_us' ) );
	}


	public static function ats_load_home() {
	  global $wpdb;
    $post_per_page = 4;
    $paged = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;

    $offset = ($paged - 1) * $post_per_page;
    $offset = ($offset <= 0) ? 0 : $offset;

    $sql_query_join = "SELECT ID, latitude, longitude FROM ".$wpdb->prefix."posts WHERE  post_type = 'projects' ";
    $sql_query_join.= "AND post_status = 'publish' ORDER BY post_date DESC LIMIT $offset, $post_per_page";

    $results = $wpdb->get_results($sql_query_join);
    if(count($results) < $post_per_page)
      $last_page = 1;
    else
      $last_page = 2;


    $content = '';
    $output = '';
    foreach($results as $project){


      $content.= '<div class="project-cover" id="project_cover'. $project->ID .'">';
      $content.= '<div class="cover-img">';
      $content.= '<a class="cover-img-link" href="'. get_permalink($project->ID) .'">';

      if(has_post_thumbnail( $project->ID )){
        $src = wp_get_attachment_image_src( get_post_thumbnail_id($project->ID), 'thumbnail');
        $content.= '<img  src="' .$src[0] .'" class="cover-img-el cover-img-standard" width="202" height="158">';
      } else {
        $content.= '<img  src="/images/1.jpg" class="cover-img-el cover-img-standard" >';
      }
      $content.= '</a>';
      $content.= '</div>';

      $content.= '<div class="cover-info-stats">';
      $content.= '<div class="cover-info-home">';
      $content.= '<div class="cover-name">';
      $content.= '<a class="projectName cover-name-link" href="'. get_permalink($project->ID) .'">';
      $content.= get_the_title($project->ID) .'</a>&nbsp;&nbsp;';
      if(!empty($project->distance)) {
        $dis = round(($project->distance * 1.609),2);
        if($dis<1) {
          $dis = $dis * 1000; echo round(($dis),2)." "."Meters";
        } else {
          echo round(($project->distance * 1.609),2)." "."KM";
        }
      }
      $content.= '</div>';
      $post_date = get_post_meta($project->ID, 'project_date', true);
      if($post_date == '') {
        $post_date = time();
      }
      $is_multiple_address = get_post_meta($project->ID, 'multiple_address_status', true);
      $country_name = get_post_meta($project->ID, 'countryName', true);
      // get city name

      $sql = "SELECT city FROM {$wpdb->prefix}project_multiple_address WHERE project_id=$project->ID";
      $row = $wpdb->get_row($sql);
      $content.= '<div class="cover-by-wrap">';
      $content.= '<div class="cover-by">';

      if($is_multiple_address == 'Y')
        $content.= 'Several cities';
      else {
        if(!empty($row)) {
          if($row->city != '') {
            $content.= $row->city;
          }
        }
      }
      $content.= ','.$country_name;
      $content.= '</div>';
      $content.= '<div class="cover-by">'. date('d.m.y', $post_date) .'</div>';
      $content.= '</div>';
      $content.= '</div>';

      $categories = get_the_category($project->ID);
      if($categories) {
  	    foreach($categories as $category) {
          $output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr($category->name) . '">'.$category->cat_name.'</a>'.',';
        }
      }
      if( strlen(strip_tags($output)) > 35) {
    	  $content.= '<div class="cover-fields" style="margin-right:10px;margin-left:5px;">';
    	  $content.= '<div class="horizontal_scroller " id="horizontal_scroller'. $project->ID .'" >';
    		$content.= '<div class="scrollingtext" id="scrollingtext'. $project->ID .'" >'. $output .'</div>';
    	  $content.= '</div>';
    	  $content.= '</div>';
      } else {
  	    $content.= '<div class="cover-fields">'. substr($output,0,-1) .'</div>';
      }


      $project_viewer = get_post_meta($project->ID, "project_viewer", 'single');
      $follow_count = $wpdb->get_results("select count(*) as cnt from " . $wpdb->prefix . "followers where post_id='".$project->ID."'");

      $content.= '<div class="cover-stat-fields-wrap">';
      $content.= '<div class="cover-stat-wrap">';
      $content.= '<span class="cover-stat">';
      $content.= '<span class="stat-label">';
      $content.= '<img src="'.get_stylesheet_directory_uri().'/images/follow_icon3.png" />';
      $content.= '</span>';
      $content.= '<span class="stat-value">'. $follow_count[0]->cnt .'</span>';
      $content.= '</span>';
      $content.= '<span class="cover-stat">';
      $content.= '<span class="stat-label"><img src="'.get_stylesheet_directory_uri().'/images/viewer.png" /></span>';
      $content.= '<span class="stat-value">'. (($project_viewer!='') ? $project_viewer : '0') .'</span>';
      $content.= '</span>';
      $content.= '</div>';
      $content.= '</div>';
      $content.= '</div>';
      $content.= '</div>';
    }

    //return $content;
    $data['sql_query_join'] = $sql_query_join;
    $data['content'] = $content;
    $data['paged'] = $paged;
    $data['lpage'] = $last_page;
    $data['cc'] = count($results);

    print json_encode($data);die();
	}


	/* ---------- Store Near ME ------------- */


    public function display_projects_accor_location() {

      global $wpdb;
      $post_per_page = 32;
      $paged = ( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;

      $latitude=$_POST['latitude'];
      $longitude=$_POST['longitude'];

      $offset = ($paged - 1)*$post_per_page;
      $offset = ($offset <= 0) ? 0 : $offset;



      if( $latitude !='' &&  $longitude !='')
      {

        $lon = $longitude;
        $lat = $latitude;
        $distance = 20;


        $sql = "SELECT project_id,zip,lat,lon,( 3959 * acos( cos( radians('$lat') ) * cos( radians( lat ) ) * cos( radians( lon ) - radians('$lon') ) + sin( radians('$lat') ) * sin( radians( lat ) ) ) ) AS distance FROM wp_project_multiple_address ";
        $sql.= " having distance<='$distance' ORDER BY distance ASC LIMIT 0, 10";
        $rows = $wpdb->get_results($sql);
        $ids = array();
        if(count($rows) > 0) {
          foreach($rows as $r => $row) {
            $ids[] = $row->project_id;
            $GLOBALS['distance'][$row->project_id]=$row;
          }
          $ids = implode(",", $ids);

          $sql_query_join = "SELECT ID, latitude, longitude, ( 3959 * acos( cos( radians('$lat') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('$lon') ) + sin( radians('$lat') ) * sin( radians( latitude ) ) ) ) AS distance FROM ".$wpdb->prefix."posts WHERE ID in ($ids)";
          $sql_query_join.= " AND post_status = 'publish' order by distance asc LIMIT ".$offset." ,".$post_per_page;

        } else {
          $sql_query_join = " SELECT ID, latitude, longitude FROM ".$wpdb->prefix."posts WHERE  post_type = 'projects' AND (post_status = 'publish') ORDER BY post_date DESC LIMIT ".$offset." ,".$post_per_page;
        }

      }
      else
      {
        $sql_query_join = " SELECT ID, latitude, longitude FROM ".$wpdb->prefix."posts WHERE  post_type = 'projects' AND (post_status = 'publish') ORDER BY post_date DESC LIMIT ".$offset." ,".$post_per_page;
      }
      $resaults = $wpdb->get_results($sql_query_join);

      if(count($resaults)<$post_per_page)
         $last_page=1;
      else
         $last_page=2;

      ob_start();
      foreach($resaults as $projects){
        $GLOBALS['project']=$projects;
        get_template_part("content", "project");
      }

      $data['sql_query'] = $sql;
      $data['sql_query_join'] = $sql_query_join;
      $data['content'] = ob_get_contents();
      $data['paged'] = $paged;
      $data['lpage'] = $last_page;
      $data['qq'] = $sql_query;
      $data['cc'] = count($resaults);

      ob_end_clean();
      print json_encode($data);
      die();
    }
    /* ---------- Store Near ME ------------- */


/* ------  Profile --------- */
function profile()
{
    global $wpdb;
    $post_per_page = 12;
    $paged = ( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;
    $offset = ($paged - 1)*$post_per_page;
    $offset = ($offset <= 0) ? 0 : $offset;


        $user_id = get_current_user_id();
          if(empty($user_id)){
              echo '{"status":"error"}';
              exit;
          }
    if(isset($_POST['tab']) && $_POST['tab']==1)
    {
          $resaults = $wpdb->get_results($wpdb->prepare("select p.ID from " . $wpdb->prefix . "followers fp, ".$wpdb->prefix."posts p where fp.post_id=p.ID and user_id =%d ORDER BY p.post_date,p.ID DESC LIMIT %d, %d", $user_id,$offset,$post_per_page));
    }
    elseif (isset($_POST['tab']) && $_POST['tab']==2) {
        $resaults = $wpdb->get_results($wpdb->prepare("select pi.*, wl.project_id from " . $wpdb->prefix . "wishlist wl, ".$wpdb->prefix."posts p, ".$wpdb->prefix."portfolio_images pi where pi.image_id=wl.image_id AND wl.image_id=p.ID and wl.user_id =%d ORDER BY p.post_date DESC ", $user_id));
    }
    elseif (isset($_POST['tab']) && $_POST['tab']==3) {
       $resaults = $wpdb->get_results($wpdb->prepare("select * from " . $wpdb->prefix . "users  where  ID =%d ORDER BY ID DESC ", $user_id));
    }
    else
    {

    }


    if(count($resaults)<$post_per_page)
        $last_page=1;
    else
        $last_page=2;

    ob_start();

      $content = '';
      //$content.= $_POST['tab'];
      $content.= '<div class="mid_wrapper">';
      $content.= '<div class="main_wrap">';
      $content.= '<div class="tab_box_margin">';
      $content.= '<div class="no_left_box_mid">';
      $content.= '<div class="box_left"></div>';
      $content.= '<div class="box_right"></div>';
      $content.= '<div>';
      $content.= '<div class="tab_box">';
      $content.= '<ul><li id="project_follows"><a href="#" class = "followed-page" data-pageid = "1">Stores Followed</a></li><li  id="project_wishlist" class = "wishlist-page" data-pageid = "2"><a href="#">My Wishlist</a></li><li id="edit_profile"><a href="#" class = "edit-page" data-pageid = "3">Edit Profile</a></li></ul>';
      $content.= '</div>';
   //   $content.=  wp_nonce_field( 'edit_my_profile', 'security' );
      $content.= '<input type="hidden" name="limit" id="page_no" value="1" />';
      $content.= '<input type="hidden" name="last_page" id="last_page" value="2" />';
      $content.= '<div id="my_profile_content" class="my_profile_content">';



      foreach($resaults as $project){

      if(isset($_POST['tab']) && $_POST['tab']==1)
    {
      $content.= '<div class="project-cover" id="project_cover'. $project->ID .'">';

      $content.= '<div class="cover-img">';
      $content.= '<a class="cover-img-link" href="'. get_permalink($project->ID) .'">';

      if(has_post_thumbnail( $project->ID )){
        $src = wp_get_attachment_image_src( get_post_thumbnail_id($project->ID), 'thumbnail');
        $content.= '<img  src="' .$src[0] .'" class="cover-img-el cover-img-standard" width="202" height="158">';
      } else {
        $content.= '<img  src="/images/1.jpg" class="cover-img-el cover-img-standard" >';
      }
      $content.= '</a>';
      $content.= '</div>';

      $content.= '<div class="cover-info-stats">';
      $content.= '<div class="cover-info-home">';
      $content.= '<div class="cover-name">';
      $content.= '<a class="projectName cover-name-link" href="'. get_permalink($project->ID) .'">';
      $content.= get_the_title($project->ID) .'</a>&nbsp;&nbsp;';
      if(!empty($project->distance)) {
        $dis = round(($project->distance * 1.609),2);
        if($dis<1) {
          $dis = $dis * 1000; echo round(($dis),2)." "."Meters";
        } else {
          echo round(($project->distance * 1.609),2)." "."KM";
        }
      }
      $content.= '</div>';
      $post_date = get_post_meta($project->ID, 'project_date', true);
      if($post_date == '') {
        $post_date = time();
      }
      $is_multiple_address = get_post_meta($project->ID, 'multiple_address_status', true);
      $country_name = get_post_meta($project->ID, 'countryName', true);
      // get city name

      $sql = "SELECT city FROM {$wpdb->prefix}project_multiple_address WHERE project_id=$project->ID";
      $row = $wpdb->get_row($sql);
      $content.= '<div class="cover-by-wrap">';
      $content.= '<div class="cover-by">';

      if($is_multiple_address == 'Y')
        $content.= 'Several cities';
      else {
        if($row->city != '') {
          $content.= $row->city;
        }
      }
      $content.= ','.$country_name;
      $content.= '</div>';
      $content.= '<div class="cover-by">'. date('d.m.y', $post_date) .'</div>';
      $content.= '</div>';
      $content.= '</div>';

      $categories = get_the_category($project->ID);
      if($categories) {
        foreach($categories as $category) {
          $output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr($category->name) . '">'.$category->cat_name.'</a>'.',';
        }
      }
      if( strlen(strip_tags($output)) > 35) {
        $content.= '<div class="cover-fields" style="margin-right:10px;margin-left:5px;">';
        $content.= '<div class="horizontal_scroller " id="horizontal_scroller'. $project->ID .'" >';
        $content.= '<div class="scrollingtext" id="scrollingtext'. $project->ID .'" >'. $output .'</div>';
        $content.= '</div>';
        $content.= '</div>';
      } else {
        $content.= '<div class="cover-fields">'. substr($output,0,-1) .'</div>';
      }


      $project_viewer = get_post_meta($project->ID, "project_viewer", 'single');
      $follow_count = $wpdb->get_results("select count(*) as cnt from " . $wpdb->prefix . "followers where post_id='".$project->ID."'");

      $content.= '<div class="cover-stat-fields-wrap">';
      $content.= '<div class="cover-stat-wrap">';
      $content.= '<span class="cover-stat">';
      $content.= '<span class="stat-label">';
      $content.= '<img src="'.get_stylesheet_directory_uri().'/images/follow_icon3.png" />';
      $content.= '</span>';
      $content.= '<span class="stat-value">'. $follow_count[0]->cnt .'</span>';
      $content.= '</span>';
      $content.= '<span class="cover-stat">';
      $content.= '<span class="stat-label"><img src="'.get_stylesheet_directory_uri().'/images/viewer.png" /></span>';
      $content.= '<span class="stat-value">'. (($project_viewer!='') ? $project_viewer : '0') .'</span>';
      $content.= '</span>';
      $content.= '</div>';
      $content.= '</div>';
      $content.= '</div>';
      $content.= '</div>';
    }
        elseif (isset($_POST['tab']) && $_POST['tab']==2) {

      $content.='<div class="project-cover wishlist-product"><div class="cover-img" style="height:290px">';

      if($project->img_status == 1)
        $vvv = "<div class='potfolio_area'>New</div>";
      elseif($project->img_status==2)
        $vvv = "<div class='potfolio_area'>Featured</div>";
      else
        $vvv ="";
      $content .= $vvv;
      $caption = get_post_field('post_excerpt', $project->image_id);

      $img_caption = ($caption!="")? $caption : "Untitle Caption";

      //$src = wp_get_attachment_image_src($res->image_id , 'full');

      $content.='<a class="cover-img-link group1" href="'.get_permalink($project->project_id).'"  title="" >';
      //$content.=remove_width_and_height_attribute(wp_get_attachment_image( $res->image_id, 'thumbnail' ));

      $src = wp_get_attachment_image_src($project->image_id , 'full');
      if($src[2] > 270)
        $height = '270';
      else
        $height = $src[2];
      if($src[1] > 180)
        $width = '180';
      else
        $width = $src[1];
      $content.='<img src="'.$src[0].'" width="'.$width.'" height="'.$height.'" class="" title="'.htmlspecialchars($img_caption).'"/>';

      $content.='</a></div>';
      $content.='<div class="cover-info-stats"><div class="cover-info">';
      $content.='<div class="portfolio_name">';


      if(get_post_field('post_excerpt', $project->image_id)!="") {
        $pex = get_post_field('post_excerpt', $project->image_id);
        $excerpt = strip_tags($pex);
        if(strlen($excerpt) > 90) {
          $final_excerpt = display_limited_words_with_formatting($pex, 90);
          $content.= $final_excerpt . '..<a href="'.$src[0].'" class="socialGallery" image_id="'.$res->image_id.'" project_id="'.$project_id.'" buy_now_link="'.$buy_now_link.'">more</a>';
        } else {
          $final_excerpt = display_limited_words_with_formatting($pex, 0);
          $content.= '<a href="" class="projectName portfolio_name-link">'.$final_excerpt.'</a>';
        }

      }

      $content.='</div>';
      $content.='</div>';

      $content.='<div class="cover-stat-fields-wrap">';

      $content.='</div>';
      $content.='</div>';

      /* if($res->enable_buy_now==1){
        $content.='<div class="buy_now_stats">';
        $content.='<div class="buy_now_btn add_to_my_wishlist"><a href="#" image_id="'.$res->image_id.'" project_id="'.$project_id.'" id="add_wishlist_bbn'.$res->image_id.'">Remove from Wishlist<a>';
        $content.='</div>';
        $content.='<div class="buy_now_btn"><a href="javascript:void(0)"  onclick="window.open(\''.$res->buy_now_option.'\',\'_blank\',\'toolbar=yes, scrollbars=yes, resizable=yes, top=200, left=200, width=1000, height=550\');">';
        if($res->buy_now_price!='') {
          $content.='<span class="buy_now_price_span">';
          $content.=$res->buy_now_price;
          $content.='</span>';
        } else {
          $content.='<span class="buy_now_price_span">0</span>';
        }
          $content.='Buy Now</a></div>';
          $content.='</div>';
      } */

      // add "remove from wishlist button"
      $content.= '<div class="buy_now_stats">';
      $content.= '<div class="buy_now_btn add_to_my_wishlist">';
      $content.= '<a class="remove_from_wl" href = "#" image_id="' . $project->image_id . '" project_id="' . $project->project_id . '" id="add_wishlist_bbn' . $project->image_id . '" data-pageid = "2.1">';
      $content.= 'Remove from Wishlist</a>';
      $content.= '</div>';

      // add buy now button if buy now option is there
      if($project->buy_now_price!='') {
        if($project->buy_now_price!='')
          $bn_price = $project->buy_now_price;
        else
          $bn_price = 0;
        $content.= '<div class="buy_now_btn">';
        $content.= '<a href="javascript:void(0)" onclick="">';
        $content.= '<span class="buy_now_price_span">' . $bn_price . '</span>Buy Now';
        $content.= '</a>';
        $content.= '</div>';
      }

      $content.= '</div>';

      $content.='</div>';


      }
      elseif (isset($_POST['tab']) && $_POST['tab']==3) {
        /*----- Code ------*/

        $content.= '<div id="update_profile_msg"></div>';
        $content.= '<form enctype="multipart/form-data"  method="POST" id="edit_my_profile" action="javascript://" novalidate="novalidate">';
        $content.= '<div  class="profile_image">';
        $content.= '<span>Profile Image</span>';
        $content.= '<img src="'. bloginfo("template_url") .'/images/user_img.jpg">';
        $content.= '<input type="file" name="profile_image" value="Upload">';
        $content.= '<div class="clear"></div>';
        $content.= '</div>';
        $content.= '<div class="noon">';
        $content.= '<span id="nameLabel">';
        $content.= '</span>';
        $content.= '<input type="text" name="first_name" id="first_name"  value="'.get_user_meta($user_id, "first_name", true).'"/>';
        $content.= '<div class="clear"></div>';
        $content.= '</div>';
        $content.= '<div class="noon" id="divLastName">';
        $content.= '<span>';

        $content.= '</span>';
        $content.= '<input type="text" name="last_name" id="last_name" value="'.get_user_meta($user_id, "last_name", true) .'"/>';
        $content.= '<div class="clear"></div>';
        $content.= '</div>';
        $content.= '<div class="noon">';
        $content.= '<span>';
        $content.= '</span>';
        $content.= '<div class="profilename">';
        $content.= '<input type="text" name="path" id="path" readonly="readonly" value="'. home_url() .'"/>';
        $content.= '<input type="text" name="profile_name" id="profile_name" value="'.get_user_meta($user_id, "profile_name", true) .'" />';
        $content.= '</div>';
        $content.= '<div class="clear"></div>';
        $content.= '</div>';
        $content.= '<div class="noon">';
        $content.= '<div class="noon_country">';
        $content.= '<span>';
        $content.= '</span>';
        $content.= '<select name="country" id="country" style="" >';
        $content.= '<option value="">Select Country</option>';
        if(count($country_query) > 0){
        foreach($country_query as $row){
        $content.= '<option value="'.$row->country_name .'" '.get_user_meta($user_id, "country", true)==$row->country_name?"selected":"".'>"'. $row->country_name.'</option>';
        } }
      $content.= '</select>';
      $content.= '</div>';
      $content.= '<div class="noon_country">';
      $content.= '<span>';

      $content.= '</span>';
      $content.= '<input type="text" name="city" id="city" style="" value="'. get_user_meta($user_id, "city", true) .'" />';
      $content.= '</div>';
      $content.= '<div class="clear"></div>';
      $content.= '</div>';

      $content.= '</form>';
          }
      else
      {

      }

  }
      $content.= '</div>';
    //  $content.= '<div id="loadingDiv" style="display:none;" align="center"><img src="'.bloginfo('stylesheet_directory').'/images/80.gif"/></div>';
      $content.= '<div class="clear"></div>';
      $content.= '</div>';
      $content.= '</div>';
      $content.= '</div>';
      $content.= '<div class="clear"></div>';
      $content.= '</div>';
      $content.= '</div>';
      $data['content'] = $content;

      print json_encode($data);
      die();

}

/* ------ Profile ---------- */

/* ---- Delete From Whilist --- */
  public function delete_from_my_wishlist() {
    global $wpdb;
    $user_id = get_current_user_id();
    $image_id = $_POST['image_id'];
    $project_id = $_POST['project_id'];
    $data = array();

    $sql = "DELETE FROM {$wpdb->prefix}wishlist WHERE user_id=$user_id AND image_id=$image_id AND project_id=$project_id";
    $query = $wpdb->query($sql);
    if($query)
      $data['msg'] = 'ok';
    else
      $data['msg'] = 'error';

    print json_encode($data);exit;
  }

/* ---- Delete From Whilist --- */




/*Get The Apps*/
function get_the_apps()
{
      $content.= '';
      $content.= 'Get the apps content goes here.......................';
      $data['content'] = $content;

      print json_encode($data);
      die();
}
/*Get The Apps*/


/*About Us*/
function about_us()
{
      $content.= '';
      $content.= 'About us content goes here.......................';
      $data['content'] = $content;

      print json_encode($data);
      die();
}
/*About Us*/

/*Advertise Here*/
function advertise_here()
{
      $content.= '';
      $content.= 'About us content goes here.......................';
      $data['content'] = $content;

      print json_encode($data);
      die();
}
/*Advertise Here*/

/*Terms & Condtions*/
function terms_and_conditions()
{
      $content.= '';
      $content.= 'Terms & conditions content goes here.......................';
      $data['content'] = $content;

      print json_encode($data);
      die();
}
/*Terms & Condtions*/

/*Contact Us*/
function contact_us()
{
      $content.= '';
      $content.= 'Contact us content goes here.......................';
      $data['content'] = $content;

      print json_encode($data);
      die();
}
/*Contact Us*/


  /**
	 * Register geolocation script
	 * @since  1.0.0
	 */
	public function scripts_styles() {

		wp_register_script( 'app-theme-support', self::$plugin_url ."js/appp-theme-support.js", array( 'jquery' ), self::VERSION );

		wp_localize_script( 'app-theme-support', 'ats_object', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),

		) );
		wp_enqueue_script( 'app-theme-support' );
	}
}
App_Theme_Support::go();


/**
 * Display Similar Posts in single page
 */
function display_similar_posts_on_single_page() {
  global $project, $wpdb;
  $output = '';
  $content = '';
  $content.= '<div class="project-cover" id="project_cover'. $project->ID .'">';
  $content.= '<div class="cover-img">';
  $content.= '<a class="cover-img-link" href="'. get_permalink($project->ID) .'">';

  if(has_post_thumbnail( $project->ID )){
    $src = wp_get_attachment_image_src( get_post_thumbnail_id($project->ID), 'thumbnail');
    $content.= '<img  src="' .$src[0] .'" class="cover-img-el cover-img-standard" width="202" height="158">';
  } else {
    $content.= '<img  src="/images/1.jpg" class="cover-img-el cover-img-standard" >';
  }
  $content.= '</a>';
  $content.= '</div>';

  $content.= '<div class="cover-info-stats">';
  $content.= '<div class="cover-info-home">';
  $content.= '<div class="cover-name">';
  $content.= '<a class="projectName cover-name-link" href="'. get_permalink($project->ID) .'">';
  $content.= get_the_title($project->ID) .'</a>&nbsp;&nbsp;';
  if(!empty($project->distance)) {
    $dis = round(($project->distance * 1.609),2);
    if($dis<1) {
      $dis = $dis * 1000; echo round(($dis),2)." "."Meters";
    } else {
      echo round(($project->distance * 1.609),2)." "."KM";
    }
  }
  $content.= '</div>';
  $post_date = get_post_meta($project->ID, 'project_date', true);
  if($post_date == '') {
    $post_date = time();
  }
  $is_multiple_address = get_post_meta($project->ID, 'multiple_address_status', true);
  $country_name = get_post_meta($project->ID, 'countryName', true);
  // get city name

  $sql = "SELECT city FROM {$wpdb->prefix}project_multiple_address WHERE project_id=$project->ID";
  $row = $wpdb->get_row($sql);
  $content.= '<div class="cover-by-wrap">';
  $content.= '<div class="cover-by">';

  if($is_multiple_address == 'Y')
    $content.= 'Several cities';
  else {
    if(!empty($row)) {
      if($row->city != '') {
        $content.= $row->city;
      }
    }
  }
  $content.= ','.$country_name;
  $content.= '</div>';
  $content.= '<div class="cover-by">'. date('d.m.y', $post_date) .'</div>';
  $content.= '</div>';
  $content.= '</div>';

  $categories = get_the_category($project->ID);
  if($categories) {
    foreach($categories as $category) {
      $output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr($category->name) . '">'.$category->cat_name.'</a>'.',';
    }
  }
  if( strlen(strip_tags($output)) > 35) {
    $content.= '<div class="cover-fields" style="margin-right:10px;margin-left:5px;">';
    $content.= '<div class="horizontal_scroller " id="horizontal_scroller'. $project->ID .'" >';
  	$content.= '<div class="scrollingtext" id="scrollingtext'. $project->ID .'" >'. $output .'</div>';
    $content.= '</div>';
    $content.= '</div>';
  } else {
    $content.= '<div class="cover-fields">'. substr($output,0,-1) .'</div>';
  }


  $project_viewer = get_post_meta($project->ID, "project_viewer", 'single');
  $follow_count = $wpdb->get_results("select count(*) as cnt from " . $wpdb->prefix . "followers where post_id='".$project->ID."'");

  $content.= '<div class="cover-stat-fields-wrap">';
  $content.= '<div class="cover-stat-wrap">';
  $content.= '<span class="cover-stat">';
  $content.= '<span class="stat-label">';
  $content.= '<img src="'.get_stylesheet_directory_uri().'/images/follow_icon3.png" />';
  $content.= '</span>';
  $content.= '<span class="stat-value">'. $follow_count[0]->cnt .'</span>';
  $content.= '</span>';
  $content.= '<span class="cover-stat">';
  $content.= '<span class="stat-label"><img src="'.get_stylesheet_directory_uri().'/images/viewer.png" /></span>';
  $content.= '<span class="stat-value">'. (($project_viewer!='') ? $project_viewer : '0') .'</span>';
  $content.= '</span>';
  $content.= '</div>';
  $content.= '</div>';
  $content.= '</div>';
  $content.= '</div>';

  return $content;
}


/**
 * Display link with proper "http://"
 */
function make_url_with_http_for_appp_theme($link) {
  $needle = "http";
  $has_http = strpos($link, $needle);

  if($link != '') {
    if($has_http !== false)
      return $link;
    else
      return "http://".$link;
  } else {
    return '';
  }
}