<?php
/**
 * Template Name: project
 */
if(!is_user_logged_in()){
    wp_redirect( home_url()."#loginbox=1");
    exit;
}
get_header(); 

       $user_id = get_current_user_id();  
       //$meta_key= get_user_meta($user_id, $key, $single);
       //query_posts('author='.$user_id.', post_type=projects');
       
       $draft_project=array('lang' => ICL_LANGUAGE_CODE, 'post_type' => 'projects', 'author' => $user_id , 'post_status'=>'draft');
       $draft_objs =  get_posts($draft_project);
       if(count($draft_objs)){
?>           


  <div class="mid_leftarea">
        	<div class="box_mid">
            	<div class="box_left"></div>
                <div class="box_right"></div>
                <div class="category_list">
                	
 <?php
 foreach($draft_objs as $draft_ob) { 
 ?>    
 
 <div class="project-cover" id="project_cover<?php echo $draft_ob->ID; ?>">
     <div class="draft_area">Draft</div>
  <div class="cover-img"> <a class="cover-img-link" href="<?php echo get_permalink(get_ID('portfolio'))."?project_id=".$draft_ob->ID; ?>">
          <?php if(has_post_thumbnail( $draft_ob->ID )){ 
              $src = wp_get_attachment_image_src( get_post_thumbnail_id($draft_ob->ID), 'thumbnail');
              $mkstep=2;
              ?>
          <img alt="<?php echo get_the_title($draft_ob->ID); ?>" src="<?php echo $src[0] ?>" class="cover-img-el cover-img-standard">
          <?php }else{ 
              $mkstep=1; 
              ?>
          <img alt="<?php echo get_the_title($draft_ob->ID); ?>" src="<?php bloginfo('stylesheet_directory'); ?>/images/1.jpg" class="cover-img-el cover-img-standard">
          <?php } ?> 
      </a></div>
  <div class="cover-info-stats">
    <div class="cover-info">
        <div class="cover-name"> <a class="projectName cover-name-link" href="<?php echo get_permalink(get_ID('portfolio'))."?project_id=".$draft_ob->ID; ?>"><?php echo get_the_title($draft_ob->ID); ?></a> </div>
      <div class="cover-by-wrap">
        <div class="cover-by">by</div>
        <div class="cover-by-link"><a title="Bill Sanderson" href="//" class="cover-link">
            <?php $author_id=$draft_ob->post_author; 
                echo the_author_meta( 'display_name' , $author_id );
                ?>    
            </a> </div>
      </div>
    </div>
    <div class="cover-fields"> 
     <?php 
      $categories = get_the_category($draft_ob->ID); 
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
  
              <div class="project_ediotor_menu_bar">
                  <ul>
                      <li>
                          <a href="<?php echo get_permalink(get_ID('editor'))."?project_id=".$draft_ob->ID; ?>">Edit Project</a>
                      </li>
                      <li>
                          <a href="<?php echo get_permalink(get_ID('editor'))."?project_id=".$draft_ob->ID; ?>#publishProject=1" >Publish Project</a>
                      </li>
                      <li>
                          <a href="<?php echo get_permalink(get_ID('portfolio'))."?project_id=".$draft_ob->ID; ?>">View Project</a>
                      </li>
                      <li>
                          <a href="#" onclick='jQuery.ConfirmDelete(event,"<?php echo $draft_ob->ID; ?>","<?php echo get_the_title($draft_ob->ID); ?>")'>Delete Project</a>
                      </li>
                  </ul>
              </div>
</div>
                    
 <?php }?>                  
                    
                    
                </div>
            </div>
        </div>
       <?php } else{
     $dyn_no_left="yes";
 } ?>
        <div class="<?php echo ($dyn_no_left=="yes")?"no_left_panle_mid_area":"mid_rightarea"?>">
        	<div class="<?php echo ($dyn_no_left=="yes")?"no_left_box_mid":"box_mid"?>">
            	<div class="box_left"></div>
                <div class="box_right"></div>
                <div>
                <?php
               
                $publish_project=array('lang' => ICL_LANGUAGE_CODE, 'post_type' => 'projects', 'author' => $user_id , 'post_status'=>'publish', 'nopaging'   => true);
                
               // $n=array('lang' => ICL_LANGUAGE_CODE,'post_type' => 'projects', 'post_status'=>'publish');
                $publish_project_query = new WP_Query( $publish_project );

                
                $publish_project_objs =  $publish_project_query->posts;
                //echo "<pre>";
               // var_dump($publish_project_objs);
                if(count($publish_project_objs)){
                    ?>
                    <div class="create_project_toolbar" align="center">

                        <a href="<?php echo get_permalink(get_ID('editor')); ?>">Create Project</a>
                    </div>
                    
                    
                    <?php
                    foreach($publish_project_objs as $project) { 
                        //$GLOBALS['project']=$project_ob;
                        ?>
                    <div  id="project_cover<?php echo $project->ID; ?>" class="project_cover_hover_action">
                       <?php get_template_part("content", "project"); ?>
 
  
                        <div class="project_ediotor_menu_bar">
                         <ul>
                      <li>
                          <a href="<?php echo get_permalink(get_ID('editor'))."?project_id=".$project->ID; ?>">Edit Project</a>
                      </li>
                      <li>
                          <a href="#" onclick='jQuery.UnpublishProject(event,"<?php echo $project->ID; ?>","<?php echo get_the_title($project->ID); ?>")'>Unpublish Project</a>
                      </li>
                      <li>
                          <a href="<?php echo get_permalink($project->ID); ?>">View Project</a>
                      </li>
                      <li>
                          <a href="#" onclick='jQuery.ConfirmDelete(event,"<?php echo $project->ID; ?>","<?php echo get_the_title($project->ID); ?>")'>Delete Project</a>
                      </li>
                  </ul>
                        </div>
                       </div>
                        
                    <?php   
                    }
                    
                    
                }else
                echo do_shortcode('[my_project_list]');
                ?>
                    
                <div class="clear"></div>
                </div>
            </div>
        </div>

<?php get_footer(); ?>
