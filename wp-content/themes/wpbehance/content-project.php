<?php
global $project;
global $distance;
?>
<div class="project-cover" id="project_cover<?php echo $project->ID; ?>">

  <div class="cover-img">
    <a class="cover-img-link" href="<?php echo get_permalink($project->ID); ?>">
    <?php
    if(has_post_thumbnail( $project->ID )){
      $src = wp_get_attachment_image_src( get_post_thumbnail_id($project->ID), 'thumbnail');?>
      <img  src="<?php echo $src[0] ?>" class="cover-img-el cover-img-standard" width="202" height="158">
    <?php } else { ?>
      <img  src="<?php bloginfo('stylesheet_directory'); ?>/images/1.jpg" class="cover-img-el cover-img-standard" >
    <?php } ?>
    </a>
  </div>

  <div class="cover-info-stats">
    <div class="cover-info-home">
      <div class="cover-name">
        <a class="projectName cover-name-link" href="<?php echo get_permalink($project->ID); ?>">
          <?php echo get_the_title($project->ID); ?></a>&nbsp;&nbsp;<?php  if(!empty($project->distance)){ $dis = round(($project->distance * 1.609),2); if($dis<1) { $dis = $dis * 1000; echo round(($dis),2)." "."Meters"; } else { echo round(($project->distance * 1.609),2)." "."KM"; } } ?>

      </div>
      <?php
      $post_date = get_post_meta($project->ID, 'project_date', true);
      if($post_date == '') {
        $post_date = time();
      }
      $is_multiple_address = get_post_meta($project->ID, 'multiple_address_status', true);
      $country_name = get_post_meta($project->ID, 'countryName', true);
      // get city name
      global $wpdb;
      $sql = "SELECT city FROM {$wpdb->prefix}project_multiple_address WHERE project_id=$project->ID";
      $row = $wpdb->get_row($sql);
      ?>
     <div class="cover-by-wrap">
        <div class="cover-by">
          <?php
          if($is_multiple_address == 'Y')
            echo 'Several cities';
          else {
            if($row->city != '') {
              echo $row->city;
            }
          }
          echo ','.$country_name;
          ?>
        </div>
        <div class="cover-by"><?php echo date('d.m.y', $post_date);?></div>
      </div>

    </div>

    <?php
    $categories = get_the_category($project->ID);
    //$separator = '<span class="separator">,</span>';
    if($categories) {
	    foreach($categories as $category) {
        $output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr($category->name) . '">'.$category->cat_name.'</a>'.',';
      }
    }
    ?>
    <?php if( strlen(strip_tags($output)) > 35) { ?>
  	<div class="cover-fields" style="margin-right:10px;margin-left:5px;">
  	  <div class="horizontal_scroller " id="horizontal_scroller<?php echo $project->ID; ?>" >
  		<!--<div id="div<?php //echo $project->ID; ?>"><?php //echo $output; ?></div>-->
  		<div class="scrollingtext" id="scrollingtext<?php echo $project->ID; ?>" ><?php echo $output; ?></div>
  	  </div>
  	</div>
    <?php } else { ?>
	  <div class="cover-fields"><?php echo substr($output,0,-1); ?></div>
    <?php } ?>

    <?php
    $project_viewer = get_post_meta($project->ID, "project_viewer", 'single');
    //$like_count = $wpdb->get_results("select * from " . $wpdb->prefix . "like where post_id='".$project->ID."'");
    $follow_count = $wpdb->get_results("select count(*) as cnt from " . $wpdb->prefix . "followers where post_id='".$project->ID."'");

    ?>
    <div class="cover-stat-fields-wrap">
      <div class="cover-stat-wrap">
      	<span class="cover-stat">
        	<span class="stat-label">
        	  <!--<img src="<?php //bloginfo('stylesheet_directory'); ?>/images/user.png" />  -->
        	  <img src="<?php bloginfo('template_url') ?>/images/follow_icon3.png" />
        	</span>
            <span class="stat-value"><?php echo $follow_count[0]->cnt;//echo count($like_count); ?></span>
        </span>
        <span class="cover-stat">
        	<span class="stat-label"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/viewer.png" /></span>
            <span class="stat-value"><?php echo ($project_viewer!='')? $project_viewer : '0'; ?></span>
        </span>
      </div>
    </div>
  </div>
</div>
