<?php
/**
 * The template for displaying Category pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

add_action('wp_head', 'display_project_by_category_script',8);

get_header(); 
$cur_cat_id = get_cat_id( single_cat_title("",false) );

?>
<?php get_sidebar(); ?>
<div class="mid_rightarea">
        	<div class="box_mid">
            	<div class="box_left"></div>
                <div class="box_right"></div>
                <div>
                    <div class="project_list" id="project_list">
                        <input type="hidden" name="category_id" id="category_id" value="<?php echo $cur_cat_id ?>">
                        <input type="hidden" name="limit" id="page_no" value="4" />
                        <input type="hidden" name="last_page" id="last_page" value="2" />
                	<?php
                        $publish_project=array( 'lang' => ICL_LANGUAGE_CODE, 'post_type' => 'projects', 'post_status'=>'publish','category'=> $cur_cat_id,'posts_per_page'=> 9,'offset'=> 0);
                        $publish_project_objs =  get_posts($publish_project);
                        if(count($publish_project_objs)){
                            foreach($publish_project_objs as $project) { 
                                get_template_part("content", "project");
                            }
                        }
                        ?>
                        
                    </div>
                    <div id="loadingDiv" style="display:none;" align="center"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/80.gif" /></div>
                    
                <div class="clear"></div>
                </div>
            </div>
        </div>
<?php get_footer(); ?>