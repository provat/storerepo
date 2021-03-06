<?php
/**
 * The sidebar containing the secondary widget area
 *
 * Displays on posts and pages.
 *
 * If no active widgets are in this sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
$country_code= get_query_var('location');
?>

<div class="mid_leftarea">
        	<div class="box_mid">
            	<div class="box_left"></div>
                <div class="box_right"></div>
                <div class="category_list">
                	<h3>categories</h3>
                        
                   <?php  
                    $project_args = array(
             		'type'                     => 'projects',
             		'child_of'                 => 0,
             		'parent'                   => '',
             		'orderby'                  => 'name',
             		'order'                    => 'ASC',
             		'hide_empty'               => 1,
             		'hierarchical'             => 1,
             		'exclude'                  => '1',
             		'include'                  => '',
             		'number'                   => ''
             	
             	);
             
                    
                    
             $project_categories = get_categories( $project_args );
             
             if($project_categories){
             ?>     
                    <ul class="category_list">
                <?php          
             foreach($project_categories as $rew_category){
                 
                 $publish_project=array( 'lang' => ICL_LANGUAGE_CODE, 'post_type' => 'projects', 'post_status'=>'publish','category'=> $rew_category->term_id,'numberposts'=>1, 'meta_query' => array(
		array(
			'key' => 'countryCode',
			'value' => $country_code,
		)
	));
                 $publish_project_objs =  get_posts($publish_project);
                 if(count($publish_project_objs)>=1){
                 
                 ?>
                        <li><a href="<?php echo get_category_link( $rew_category->term_id )?>"><?php echo $rew_category->name; ?></a></li>
                        
                   <?php  
                     }
                   }
                 ?>     
                       
                    </ul>
                        
                  <?php
                 }
                 ?>         
                </div>
                
                 <?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
	
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			
                       <?php endif; ?>
            </div>
        </div>

