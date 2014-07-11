<?php
/**
 * Template Name: View Project
 */
get_header(); 
global $wpdb;
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
                	<?php 
                         $project_id= $_REQUEST['project_id'];
                        
                        ?>   
                       <div>
                           <div class="project_title">
                               <h1 id="project_title"> <?php get_the_title($project_id); ?> </h1></div>
                        <?php
                        
                         $resault= $wpdb->get_results( 
            $wpdb->prepare( 
                  "
                  SELECT pi.* FROM ".$wpdb->prefix."portfolio_images pi, ".$wpdb->prefix."posts p
                   WHERE p.ID=pi.image_id AND portfolio_id=%d ORDER BY pi.order_pos ASC
                  ",$project_id ));
                         
                         //var_dump($resault);
                         if(count($resault)){
                             ?>
                           
                           <div id="dynamic_port_folio_images234">
                               <ul>
                               
                           <?php
                         foreach($resault as $res){
                          
                          echo '<li class="grid">';
                            
                          echo remove_width_and_height_attribute(wp_get_attachment_image( $res->image_id, 'medium' ));
                          if(get_post_field('post_excerpt', $res->image_id)!="")
                          echo '<div  class="caption_text">'.get_post_field('post_excerpt', $res->image_id).'</div>';
                           echo "</li>" ; 
                         }
                         ?>
                               </ul>        
                         </div>
                         <?php
                         }
                        ?>
                    
                       </div>
                       
                <div class="clear"></div>
                </div>
            </div>
        </div>

<?php get_footer(); ?>