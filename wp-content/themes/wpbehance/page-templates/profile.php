<?php
/**
 * 
 * Template Name: Profile
 * 
 */
 get_header();?>
<?php
global $wpdb;

$profile_name = substr(basename($_SERVER['REQUEST_URI']),1);
$profile_user = $wpdb->get_row(  "select * from ".$wpdb->prefix."usermeta where meta_key = 'profile_name' AND meta_value = '".$profile_name."'");
$user_id = $profile_user->user_id;
?>
 <div class="mid_leftarea">
        	<div class="box_mid">
            	<div class="box_left"></div>
                <div class="box_right"></div>
                <div class="category_list">
                	<h3>categories</h3>
                    <ul class="category_list">
                        <li><a href="#">Lorem Ipsum is simply</a></li>
                        <li><a href="#">Lorem Ipsum is  dummy</a></li>
                        <li><a href="#">Lorem Ipsum is simply Lorem Ipsum is simply</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mid_rightarea">
        	<div class="box_mid">
            	<div class="box_left"></div>
                <div class="box_right"></div>
                <div>
                    <div class="project_list">
                	<?php
			if($user_id)
			{
                        $publish_project=array( 'post_type' => 'projects', 'post_status'=>'publish', 'author'=>$user_id); 
			//$publish_project_objs = new WP_Query( 'author_name='.$profile_name ); print_r($publish_project_objs);
                        $publish_project_objs =  get_posts($publish_project);
                        if(count($publish_project_objs)){
                            foreach($publish_project_objs as $project) { //print_r($project); ?>
               
<div class="project-cover" id="project_cover<?php echo $project->ID; ?>">
<div class="cover-img"> <a class="cover-img-link" href="<?php echo get_permalink($project->ID); ?>">
      <?php if(has_post_thumbnail( $project->ID )){ 
              $src = wp_get_attachment_image_src( get_post_thumbnail_id($project->ID), 'thumbnail');
              ?>
          <img  src="<?php echo $src[0] ?>" class="cover-img-el cover-img-standard" >
          <?php }else{ ?>
          <img  src="<?php bloginfo('stylesheet_directory'); ?>/images/1.jpg" class="cover-img-el cover-img-standard" >
          <?php } ?>    
      </a></div>
  <div class="cover-info-stats">
    <div class="cover-info">
      <div class="cover-name"> <a class="projectName cover-name-link" href="<?php echo get_permalink($project->ID); ?>"><?php echo get_the_title($project->ID); ?></a> </div>
      <div class="cover-by-wrap">
        <div class="cover-by">by</div>
	 <?php $user_profile = get_user_meta($project->post_author, 'profile_name', true);  ?> 
        <div class="cover-by-link"><a title="Bill Sanderson" href="<?php echo home_url().'/profile/?'.$user_profile; ?>" class="cover-link">
                <?php $author_id=$project->post_author; 
                echo the_author_meta( 'display_name' , $author_id );
                ?></a> </div>
      </div>
    </div>
    <div class="cover-fields"> 
    <?php 
      $categories = get_the_category($project->ID); 
      $separator = '<span class="separator">,</span>';
      if($categories){
	foreach($categories as $category) {
            $output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr($category->name) . '">'.$category->cat_name.'</a>'.$separator;
        }
        echo $output;
      } 
      ?>
    </div>
    <div class="cover-stat-fields-wrap">
      <div class="cover-stat-wrap"> 
      	<span class="cover-stat">
        	<span class="stat-label"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/user.png" /></span>
            <span class="stat-value">657</span>
        </span>
        <span class="cover-stat">
        	<span class="stat-label"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/viewer.png" /></span>
            <span class="stat-value">12480</span>
        </span>
      </div>
    </div>
  </div>
</div>

			<?php
                            }
                        }
			} else echo 'No projects available';
                        ?>
                        
                    </div>  
                    
                <div class="clear"></div>
                </div>
            </div>
        </div>


 <?php get_footer();?>
