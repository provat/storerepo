<?php
/**
 *
 * Template Name:Home Page Template
 *
 */
add_action('wp_head', 'share_your_location_script',8);
//wp_enqueue_script('');

 get_header();?>



 <?php get_sidebar(); ?>
        <div class="mid_rightarea">
        	<div class="box_mid">
            	<div class="box_left"></div>
                <div class="box_right"></div>
                <div>
                    <div class="project_list">
			                <input type="hidden" name="site_url" id="site_url" value="<?php bloginfo('stylesheet_directory'); ?>" />
			                <input type="hidden" name="limit" id="page_no" value="2" />
                      <input type="hidden" name="last_page" id="last_page" value="" />
                      <input type="hidden" name="curr_lat" id="curr_lat" value="" />
                      <input type="hidden" name="curr_long" id="curr_long" value="" />
			                <input type="hidden" name="hdnUserId" id="hdnUserId" value="<?php echo get_current_user_id(); ?>" />
                    	<?php
                			$user_id = get_current_user_id();
                			$follow_query = $wpdb->get_results("select * from " . $wpdb->prefix . "followers where
                			user_id = '".$user_id."'");
                			?>
			                <input type="hidden" name="hdnFollowId" id="hdnFollowId" value="<?php echo count($follow_query); ?>" />

                      <div id="projectDiv"></div>
			                <div id="loadingDiv" style="display:none;" align="center"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/80.gif" /></div>
                  </div>

                  <div class="clear"></div>
                </div>
            </div>
        </div>


 <?php get_footer();?>
