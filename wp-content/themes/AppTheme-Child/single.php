<?php
/**
 * The Template for displaying all single posts.
 *
 * @package AppPresser Theme
 */

get_header(); ?>
<?php
/**
 * Our custom code
 */
global $wpdb;

$post_id = get_the_ID();
$user_id = get_current_user_id();
$follower_count = $wpdb->get_results("select * from " . $wpdb->prefix . "followers where post_id='".$post_id."'");
$follower_query = $wpdb->get_row("select * from " . $wpdb->prefix . "followers where post_id='".$post_id."' and user_id = '".$user_id."'");

$like_count = $wpdb->get_results("select * from " . $wpdb->prefix . "like where post_id='".$post_id."'");
$like_query = $wpdb->get_row("select * from " . $wpdb->prefix . "like where post_id='".$post_id."' and user_id = '".$user_id."'");

$project_viewer_count = (get_post_meta($post_id, "project_viewer", 'single')+1);
update_post_meta($post_id, "project_viewer", $project_viewer_count);
$project_viewer = get_post_meta($post_id, "project_viewer", 'single');

$project_multiple = $wpdb->get_results("select * from " . $wpdb->prefix . "project_multiple_address where project_id='".$post_id."'");
$project_reg_addr = $wpdb->get_results("select * from " . $wpdb->prefix . "project_multiple_address where project_id='".$post_id."' and is_main_address = 'Yes'");
$project_child_addr = $wpdb->get_results("select * from " . $wpdb->prefix . "project_multiple_address where project_id='".$post_id."' and is_main_address = 'No'");


if (function_exists('st_makeEntries')) :
  add_shortcode('sharethis', 'st_makeEntries');
endif;
function make_tag_list($tag_name, $project_id) {
  global $wpdb;
  $tag = $tag_name;

  $sql = "SELECT t.term_id,tx.term_taxonomy_id FROM {$wpdb->prefix}terms AS t INNER JOIN {$wpdb->prefix}term_taxonomy ";
  $sql.= "AS tx ON t.term_id = tx.term_id WHERE t.slug LIKE '$tag' AND tx.taxonomy = 'post_tag'";
  $term = $wpdb->get_row($sql);
  $term_taxonomy_id = $term->term_taxonomy_id;

  $obj_sql = "SELECT ob.object_id,pi.* FROM {$wpdb->prefix}term_relationships AS ob RIGHT JOIN {$wpdb->prefix}portfolio_images ";
  $obj_sql.= "AS pi ON ob.object_id = pi.image_id WHERE ob.term_taxonomy_id = $term_taxonomy_id AND ";
  $obj_sql.= "ob.object_id != $project_id";
  $results = $wpdb->get_results($obj_sql);

  $image_content = '';

  if(count($results) > 0){
    $image_ids  = array();

    foreach($results as $res) {
      $src = wp_get_attachment_image_src($res->image_id , 'full');
      if($src[2] > 270) {
        $height = '270';
        $cover_img_height = 'auto';
      } else {
        $height = $src[2];
        $cover_img_height = '273px';
      }
      if($src[1] > 180)
        $width = '180';
      else
        $width = $src[1];

      $image_ids[] = $res->image_id;
      $image_content.= '<div class="project-cover" data-ntag="'.$tag.'"><div class="cover-img" style="height:'.$cover_img_height.';">';

      if($res->img_status == 1)
        $label_type = "<div class='potfolio_area nailthumb-container'>New</div>";
      elseif($res->img_status == 2)
        $label_type = "<div class='potfolio_area'>Featured</div>";
      else
        $label_type = "";
      $image_content .= $label_type;
      $caption = get_post_field('post_excerpt', $res->image_id);
      $img_caption = ($caption!="")?$caption:"Untitle Caption";


      $buy_now_link = '';
      $buy_now_price = 0;
      if($res->enable_buy_now == 1)
        $buy_now_link = $res->buy_now_option;
      if($res->buy_now_price != '') {
        $buy_now_price = $res->buy_now_price;
      }

      $image_content.= '<a class="cover-img-link gallery" href="#mfp-pop-'.$res->image_id.'" image_id="'.$res->image_id.'" project_id="'.$project_id.'"
           buy_now_link="'.$buy_now_link.'" buy_now_price="'.$buy_now_price.'">';


      $image_content.='<img src="'.$src[0].'" width="'.$width.'" height="'.$height.'"/>';
      $image_content.='</a></div>';

      /**
       * Pop-up area - starts
       */
      $image_content.= '<div id="mfp-pop-'.$res->image_id.'" class="white-popup mfp-hide">';
      $image_content.= '<div class="main_cont_area">';
      $image_content.= '<div class="clasa"><div class="clasa_semantic"><img src="'.$src[0].'"></div></div>';
      $image_content.= '<div class="clasb">'.$img_caption;

      $image_content.= '<div>'.do_shortcode('[sharethis]').'</div>';
      // Add-to-wishlist and buy now button area
      $image_content.= '<div class="buy_now_stats"><div class="buy_now_btn add_to_my_wishlist_lb">';
      $image_content.= '<a href="#" image_id="'.$res->image_id.'" project_id="'.$project_id.'" id="add_wishlist_bbn_lb" class="pop_add_wish_list">Add To Wishlist</a>';
      $image_content.= '</div>';
      if($buy_now_link != '') {
        $image_content.= '<div class="buy_now_btn" id="pop_buy_now"><a href="'.make_url_with_http_for_appp_theme($buy_now_link).'" target="_blank">';
        $image_content.= 'Buy Now</a></div>';
      }
      $image_content.= '<div id="lb_msg"></div>';
      $image_content.= '</div>';

      $image_content.= '<div class="clear"></div></div>';
      $image_content.= '</div>';
      $image_content.= '</div>';

      /**
       * Pop-up area - end
       */

      $image_content.='<div class="cover-info-stats"><div class="cover-info">';
      $image_content.='<div class="portfolio_name">';

      if(get_post_field('post_excerpt', $res->image_id)!="") {
        $pex = get_post_field('post_excerpt', $res->image_id);
        $excerpt = strip_tags($pex);

        if(strlen($excerpt) > 90) {
          $final_excerpt = display_limited_words_with_formatting($pex, 100);
          $image_content.= $final_excerpt . '..<a href="'.$src[0].'" class="socialGallery" image_id="'.$res->image_id.'" project_id="'.$project_id.'"
                           buy_now_link="'.make_url_with_http_for_appp_theme($buy_now_link).'" buy_now_price="'.$buy_now_price.'">more</a>';
        } else {
          $final_excerpt = display_limited_words_with_formatting($pex, 0);
          $image_content.= '<a href="" class="projectName portfolio_name-link">'.$final_excerpt.'</a>';
        }
      }
      $image_content.='</div>';
      $image_content.='</div>';
      $image_content.='<div class="cover-stat-fields-wrap">';
      $image_content.='</div>';
      $image_content.='</div>';

      $image_content.='<div class="buy_now_stats"><div class="buy_now_btn add_to_my_wishlist">';
      $image_content.='<a class="pop_add_wisl" href="#" image_id="'.$res->image_id.'" project_id="'.$project_id.'" id="add_wishlist_bbn'.$res->image_id.'">Add To Wishlist</a>';
      $image_content.='</div>';

      if($res->enable_buy_now == 1){
        if($buy_now_link != '') {
          $image_content.= '<div class="buy_now_btn"><a href="javascript:void(0)"  onclick="window.open(\''.make_url_with_http_for_appp_theme($buy_now_link).'\',\'_blank\',\'toolbar=yes, scrollbars=yes, resizable=yes, top=200, left=200, width=1000, height=550\');">';
          $image_content.= 'Buy Now</a></div>';
        }
      }
      $image_content.= '</div>';
      $image_content.= '</div>';
    }
  }
  return $image_content;
}
?>
<?php //appp_title_header(); ?>
<?php wp_nonce_field( 'followers', 'security' ); ?>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<input type="hidden" name="hdnPath" id="hdnPath" value="<?php echo FOLLOWERS_PLUGIN_URL; ?>" />
<input type="hidden" name="hdnPostId" id="hdnPostId" value="<?php echo $post_id; ?>" />
<input type="hidden" name="hdnUserId" id="hdnUserId" value="<?php echo $user_id; ?>" />

<div id="content" class="site-content" role="main">

<?php while ( have_posts() ) : the_post(); $project_id= get_the_ID();?>

  <div class="no_left_box_mid">
    <div class="box_left"></div>
    <div class="box_right"></div>
    <div class="port_feature">
      <h1 class="potfolio_title"><?php the_title(); ?></h1>
      <div class="potfolio_featurearea">
        <div class="potfolio_feat_text">
          <div class="trimed_content">
          <?php
          if(strlen(get_the_content()) > 72) {
            $limit = 72;
          } else {
            $limit = strlen(get_the_content()-1);
          }

          $content = content_limit($limit,  get_the_content());
          echo $content[0];
          if($content[1]!="")
              echo " <span class='open_read_more'> Read More ...</span>";
          ?>
          </div>

          <?php
          if($content[1]!=""){
          ?>
          <div class="left_trimed_content" style="display: none;"><?php echo $content[1] ?></div>
          <?php } ?>
        </div>

        <div class="clear"></div>
      </div>
      <div class="potfolio_rightarea">
        <div class="follow_area">
          <div class="port_area">
            <div class="port_text">
              <img src="<?php echo get_stylesheet_directory_uri() ?>/images/follow_icon.png"/>Store Views
            </div>
            <div class="port_count"><?php echo $project_viewer; ?></div>
          </div>
          <div class="port_area_last">
            <div class="port_text">
              <img src="<?php echo get_stylesheet_directory_uri() ?>/images/follow_icon3.png"/>Store Followers
            </div>
            <div class="port_count" id="follow_count"><?php echo count($follower_count); ?></div>
          </div>
          <div class="clear"></div>
        </div>
        <?php  if($user_id){ ?>
        <div class="potfolio_follow">
          <a href="#">
            <div id="unfollowLink" <?php if(!$follower_query){ ?>style="display:none;"<?php } ?>>
              <img src="<?php echo home_url(); ?>/wp-content/plugins/followers/images/following.png" alt="img" id="followingImg"/>
            </div>
          </a>
          <a href="#">
            <div id="followLink" <?php if($follower_query){ ?>style="display:none;" <?php } ?>>
              <img src="<?php echo home_url(); ?>/wp-content/plugins/followers/images/follow.png" alt="img" id="followImg"/>
            </div>
          </a>
          <img src="<?php echo home_url(); ?>/wp-content/plugins/followers/images/loading.gif" alt="loading" id="loadingImg"	style="display:none; margin-top:4px;"/>
          <div class="clear"></div>
        </div>
        <?php }  ?>

        <div class="social_area">
        <?php
        $images = get_children(array('post_parent' => get_the_ID(), 'post_type' => 'attachment', 'order' => 'DESC', 'numberposts' => -1, 'post_mime_type' => 'image',));
        $i = 0;
        foreach ($images as $image) { $i++;
          if($i==1)
          $attachment = wp_get_attachment_image_src($image->ID, "thumbnail");
        }
        ?>
          <a href="javascript:void(0)" onclick="javascript:window.open('https://www.facebook.com/dialog/feed?app_id=1440128186206541&link=<?php the_permalink();?>&picture=<?php echo $attachment[0];?>&name=Everystore&caption=<?php the_title(); ?>&description=<?php $content; ?>&redirect_uri=<?php echo home_url(); ?>/thank-you','', 'menubar=no,toolbar=no,resizable=yes,scrollbars=no,height=450,width=850');"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/facebook.png" alt="img" /></a>
          <a href="javascript:void(0)" onclick="javascript:window.open('https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>&media=<?php echo $attachment[0];?>','', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=520');"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/twitter.png" alt="img" /></a>
          <a href="https://plus.google.com/share?url={<?php the_permalink(); ?>}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=520');return false;"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/google+.png" alt="img" /></a>
        </div>
        <div class="clear"></div>
      </div>

      <div class="clear"></div>
        <div class="locationMapViewPanel">
          <?php
          if(has_post_thumbnail( $project_id )) {
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($project_id), 'full');
          } else {
            $src[0] = site_url()."/wp-content/themes/wpbehance/images/1.jpg";
          }
          if(!empty($project_reg_addr)) {
            $lat = $project_reg_addr[0]->lat;
            $lon = $project_reg_addr[0]->lon;
            $street_address = $project_reg_addr[0]->street_address;
            $city = $project_reg_addr[0]->city;
            $zip = $project_reg_addr[0]->zip;
            $state = $project_reg_addr[0]->state;
          } else {
            $lat = '';
            $lon = '';
            $street_address = '';
            $city = '';
            $zip = '';
            $state = '';
          }
          ?>
          <input type = "hidden" class = "reg_geolocation" data-lat = "<?php echo $lat ?>" data-long = "<?php echo $lon ?>">
          <?php
          foreach ($project_multiple as $project_multiple_key => $project_multiple_value) { ?>
          <div class = "map-hidden" id = "map-id">
            <input type = "hidden" class = "address" value = "<?php echo $project_multiple_value->street_address ?>">
            <input type = "hidden" class = "lat" value = "<?php echo $project_multiple_value->lat ?>">
            <input type = "hidden" class = "long" value = "<?php echo $project_multiple_value->lon ?>">
          </div>
          <? } ?>
          <!-- Viewing Several Addresses -->
          <div class = "main-div-several-address">
            <?php
            if(!empty($project_child_addr)) {
              echo "<h2 class = 'several-address-heading'>Store Locations</h2>";
              $click_here = " Click here for a list of all store locations";
            } else {
              $click_here = "";
            }

            foreach ($project_child_addr as $project_child_addr_key => $project_child_addr_value) { ?>
            <div class = "follow_area several_address_param" style = "">
              <label for = "Address">
                <span class = "several-address-description">
                <?php echo $project_child_addr_value->street_address.",".$project_child_addr_value->city.",".$project_child_addr_value->zip.",".$project_child_addr_value->state.",".$project_child_addr_value->country ?>
                </span>
              </label>
            </div>
            <? } ?>
          </div>
          <!-- Viewing Several Addresses -->

          <!-- start map image panel view -->
          <div class="panelImageMap">
            <div class="portfolio_banner"><img src="<?php echo $src[0]; ?>" alt="img" /></div>
            <!-- <div class = "porfolio_map" id = "map-canvas"></div> -->
            <div></div>

            <div class="mapLocationDescriptionPanel">
            	<div class = "follow_area map_reg_pc mapHeadingArea"><span><?php echo $street_address.",".$city.",".$zip.",".$state ?>&nbsp;&nbsp;<a href = "javascript:void(0)" class = "several_address"><span><?php echo $click_here; ?></span></a></div>
            	<div class = "porfolio_map" id="map" style="height: 311px!important;"></div>
            </div>
          </div>
          <!-- end map image panel view -->

             <div class = "map_popup"></div>
            <div class = "porfolio_map_ipad" id="mapIpad" style="width:100%;height: 100%!important;">
          </div>

          <script type="text/javascript">
              jQuery(document).ready(function($){
              $('.portfolio_banner img').each(function(i, item) {
              var img_height = $(item).height();
              var div_height = 300;
              if(img_height>div_height){
              //INCREASE HEIGHT OF IMAGE TO MATCH CONTAINER
              $(item).css({'width': 'auto', 'height': div_height });
              //GET THE NEW WIDTH AFTER RESIZE
              var img_width = $(item).width();
              //GET THE PARENT WIDTH
              var div_width = $(item).parent().width();
              //GET THE NEW HORIZONTAL MARGIN
              var newMargin = (div_width-img_width)/2+'px';
              //SET THE NEW HORIZONTAL MARGIN (EXCESS IMAGE WIDTH IS CROPPED)
             // $(item).css({'margin-left': newMargin });
              }else{
              //CENTER IT VERTICALLY (EXCESS IMAGE HEIGHT IS CROPPED)
              var newMargin = (div_height-img_height)/2+'px';
              //$(item).css({'margin-top': newMargin});
              }
              });
          });

          jQuery(document).ready(function($){
              jQuery(".tag-content-trigger").click(function(){
              	  var tag = jQuery(this).data("tag");

              	  jQuery(".tag-conttent-box").css({'display' : 'block'});
              	  jQuery(".tag-conttent-box").find(".project-cover").each(function(e){
                  	  if(jQuery(this).data("ntag") == tag)
                  		  jQuery(this).css({'display' : 'block'});
                  	  else
                  		  jQuery(this).css({'display' : 'none'});
                  })
              	  jQuery(".all-content-box").css({'display' : 'none'});
              });
              jQuery(".tag-content-all").click(function(){
              	jQuery(".tag-conttent-box").css({'display' : 'none'});
              	jQuery(".all-content-box").css({'display' : 'block'});
              });
          });
          </script>

          <div class="clear"></div>
          <input type="hidden" name="details_page_project_id" id="details_page_project_id" value="<?php echo $project_id; ?>">
          <div class="image_filter_area" id="image_filter_area_id">
           <?php
           $wp_prepare=$wpdb->prepare("SELECT pi.* FROM ".$wpdb->prefix."portfolio_images pi, ".$wpdb->prefix."posts p
           WHERE p.ID=pi.image_id AND portfolio_id=%d ORDER BY pi.order_pos ASC", $project_id );

           $resault = $wpdb->get_results($wp_prepare);

           if(count($resault)){

             $image_ids  = array();
               foreach($resault as $res){
                 $image_ids[]=$res->image_id;
               }

             $args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'names');
             $tags_obj = wp_get_object_terms($image_ids, 'post_tag',$args);
             $tags_names = array();
             $tag_string = '';
             if(count($tags_obj)){
               $tags_obj_= array_unique($tags_obj);
               $tag_string = '<ul class="filter_tags">';
               foreach($tags_obj_ AS $tag) {
                 $tag_string.='<li><a href="javascript: void(0);" class="tag-content-trigger" data-tag="'.$tag.'" tag_slug="'.$tag.'">'.$tag.'</a>';
               }
               $tag_string.= '<li><a href="javascript: void(0);" class="tag-content-all">All</a>';
               $tag_string.= "</ul>";
             }
             echo $tag_string;
           }
           ?>
          </div>

          <div class="project_details_image_section" id="project_details_image_section_id">
          <?php

          if(count($tags_obj)){
            $tags = array_unique($tags_obj);
            echo '<div class="tag-conttent-box">';
            foreach($tags as $tag) {
              $tag_list_content = make_tag_list($tag, $project_id);

              echo $tag_list_content;

            }
            echo '</div>';
          }
          ?>

          <?php

          /**
           *
           */
          global $wpdb;

          $tag = isset($_GET['tag']) ? sanitize_title($_GET['tag']) : '';
          if($tag == '') {

            $sql = "SELECT pi.* FROM ".$wpdb->prefix."portfolio_images pi, ".$wpdb->prefix."posts p ";
            $sql.= "WHERE p.ID = pi.image_id AND portfolio_id = $project_id ORDER BY pi.order_pos ASC";
            $results = $wpdb->get_results($sql);
          } else {

            $sql = "SELECT t.term_id,tx.term_taxonomy_id FROM {$wpdb->prefix}terms AS t INNER JOIN {$wpdb->prefix}term_taxonomy ";
            $sql.= "AS tx ON t.term_id = tx.term_id WHERE t.slug LIKE '$tag' AND tx.taxonomy = 'post_tag'";
            $term = $wpdb->get_row($sql);
            $term_taxonomy_id = $term->term_taxonomy_id;

            $obj_sql = "SELECT ob.object_id,pi.* FROM {$wpdb->prefix}term_relationships AS ob RIGHT JOIN {$wpdb->prefix}portfolio_images ";
            $obj_sql.= "AS pi ON ob.object_id = pi.image_id WHERE ob.term_taxonomy_id = $term_taxonomy_id AND ";
            $obj_sql.= "ob.object_id != $project_id";
            $results = $wpdb->get_results($obj_sql);
          }


          $image_content = '';

          if(count($results) > 0){
            $image_ids  = array();

            foreach($results as $res) {
              $src = wp_get_attachment_image_src($res->image_id , 'full');
              if($src[2] > 270) {
      	        $height = '270';
      	        $cover_img_height = 'auto';
              } else {
                $height = $src[2];
                $cover_img_height = '273px';
              }
              if($src[1] > 180)
      	        $width = '180';
              else
                $width = $src[1];

              $image_ids[] = $res->image_id;
              $image_content.= '<div class="project-cover"><div class="cover-img" style="height:'.$cover_img_height.';">';

              if($res->img_status == 1)
                $label_type = "<div class='potfolio_area nailthumb-container'>New</div>";
              elseif($res->img_status == 2)
                $label_type = "<div class='potfolio_area'>Featured</div>";
              else
                $label_type = "";
              $image_content .= $label_type;
              $caption = get_post_field('post_excerpt', $res->image_id);
              $img_caption = ($caption!="")?$caption:"Untitle Caption";


              $buy_now_link = '';
              $buy_now_price = 0;
              if($res->enable_buy_now == 1)
                $buy_now_link = $res->buy_now_option;
              if($res->buy_now_price != '') {
                $buy_now_price = $res->buy_now_price;
              }

              $image_content.= '<a class="cover-img-link gallery" href="#mfp-pop-'.$res->image_id.'" image_id="'.$res->image_id.'" project_id="'.$project_id.'"
                   buy_now_link="'.$buy_now_link.'" buy_now_price="'.$buy_now_price.'">';


              $image_content.='<img src="'.$src[0].'" width="'.$width.'" height="'.$height.'"/>';
              $image_content.='</a></div>';

              /**
               * Pop-up area - starts
               */
              $image_content.= '<div id="mfp-pop-'.$res->image_id.'" class="white-popup mfp-hide">';
              $image_content.= '<div class="main_cont_area">';
              $image_content.= '<div class="clasa"><div class="clasa_semantic"><img src="'.$src[0].'"></div></div>';
              $image_content.= '<div class="clasb">'.$img_caption;

              $image_content.= '<div>'.do_shortcode('[sharethis]').'</div>';
              // Add-to-wishlist and buy now button area
              $image_content.= '<div class="buy_now_stats"><div class="buy_now_btn add_to_my_wishlist_lb">';
              $image_content.= '<a href="#" image_id="'.$res->image_id.'" project_id="'.$project_id.'" id="add_wishlist_bbn_lb" class="pop_add_wish_list">Add To Wishlist</a>';
              $image_content.= '</div>';
              if($buy_now_link != '') {
                $image_content.= '<div class="buy_now_btn" id="pop_buy_now"><a href="'.make_url_with_http_for_appp_theme($buy_now_link).'" target="_blank">';
                $image_content.= 'Buy Now</a></div>';
              }
              $image_content.= '<div id="lb_msg"></div>';
              $image_content.= '</div>';

              $image_content.= '<div class="clear"></div></div>';
              $image_content.= '</div>';
              $image_content.= '</div>';

              /**
               * Pop-up area - end
               */

              $image_content.='<div class="cover-info-stats"><div class="cover-info">';
              $image_content.='<div class="portfolio_name">';

              if(get_post_field('post_excerpt', $res->image_id)!="") {
                $pex = get_post_field('post_excerpt', $res->image_id);
                $excerpt = strip_tags($pex);

                if(strlen($excerpt) > 90) {
                  $final_excerpt = display_limited_words_with_formatting($pex, 100);
                  $image_content.= $final_excerpt . '..<a href="'.$src[0].'" class="socialGallery" image_id="'.$res->image_id.'" project_id="'.$project_id.'"
                                   buy_now_link="'.make_url_with_http_for_appp_theme($buy_now_link).'" buy_now_price="'.$buy_now_price.'">more</a>';
                } else {
                  $final_excerpt = display_limited_words_with_formatting($pex, 0);
                  $image_content.= '<a href="" class="projectName portfolio_name-link">'.$final_excerpt.'</a>';
                }
              }
              $image_content.='</div>';
              $image_content.='</div>';
              $image_content.='<div class="cover-stat-fields-wrap">';
              $image_content.='</div>';
              $image_content.='</div>';

              $image_content.='<div class="buy_now_stats"><div class="buy_now_btn add_to_my_wishlist">';
              $image_content.='<a class="pop_add_wisl" href="#" image_id="'.$res->image_id.'" project_id="'.$project_id.'" id="add_wishlist_bbn'.$res->image_id.'">Add To Wishlist</a>';
              $image_content.='</div>';

              if($res->enable_buy_now == 1){
                if($buy_now_link != '') {
                  $image_content.= '<div class="buy_now_btn"><a href="javascript:void(0)"  onclick="window.open(\''.make_url_with_http_for_appp_theme($buy_now_link).'\',\'_blank\',\'toolbar=yes, scrollbars=yes, resizable=yes, top=200, left=200, width=1000, height=550\');">';
      	          $image_content.= 'Buy Now</a></div>';
                }
      	      }
              $image_content.= '</div>';
              $image_content.= '</div>';
            }
          }
          echo '<div class="all-content-box">';
          print $image_content;
          echo '</div>';
          ?>
            <div class="clear"></div>
          </div>

          <?php
          $post_categories = wp_get_post_categories($project_id);
          $catt = @implode(",",$post_categories);
          $smiler_obj = new WP_Query( array ( 'orderby' => 'rand', 'posts_per_page' => '8', 'cat'=>$catt,  'post_type' => 'projects', 'post_status'=>'publish'));

          if(count($smiler_obj->posts)) {
          ?>
            <div class="clear"></div>
            <div class="similar_project_area">
              <span class="similar_project_caption">Similar Stores</span>
              <div class="s_project_list" >
              <?php
              foreach($smiler_obj->posts as $projects) {
                $GLOBALS['project'] = $projects;
                //get_template_part("content", "project");
                echo display_similar_posts_on_single_page();
              }
              ?>
              </div>
            </div>
           <?php
           }?>
      </div>
      <div class="clear"></div>
    </div>
</div>

<?php endwhile; // end of the loop. ?>

</div><!-- #content -->

<?php get_footer(); ?>