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
//App_Theme_Support::ats_load_home();


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