<?php
session_start();
/**
 * 
 * Template Name:Search Template
 * 
 */
get_header();?>
<?php
global $wpdb;
//$profile_name = basename($_SERVER['REQUEST_URI']);
$search_fld = $_REQUEST['search'];
$category_fld = $_REQUEST['category'];
$country_fld = $_REQUEST['country'];
$state_fld = $_REQUEST['state'];
$city_fld = $_REQUEST['city'];
$loc_post_id = '';
$cat_post_id = '';
$where_clause = "post_type = 'projects' AND post_status='publish'";
if($search_fld!='')
{
	$_SEESION['search'] = $search_fld;
	$where_clause .= " AND post_title like '%$search_fld%'";
}
if($category_fld!='')
{
	$_SEESION['category'] = $category_fld;
	$category_array=array( 'post_type' => 'projects', 'post_status'=>'publish','category'=> $category_fld,'posts_per_page'=> 9,'offset'=> 0);
	$query_category =  get_posts($category_array);
	if(count($query_category)){
	    foreach($query_category as $cat) { 
		$cat_post_id .= $cat->ID.',';
	    }
	} 
	//$post_id = substr($post_id,0,-1);
	//$where_clause .= " AND P1.ID IN (".$post_id.")";
}
if($country_fld!='')
{
	$_SEESION['country'] = $country_fld;
	$country_query = $wpdb->get_results("select post_id from wp_postmeta where meta_key = 'countryCode' AND meta_value = '".$country_fld."'");
	if(count($country_query) > 0)
	{
		foreach($country_query as $country_row) {
			$loc_post_id .= $country_row->post_id.',';
		}
	}
	//$where_clause .= " AND P2.meta_key = 'countryCode' AND P2.meta_value = '".$country_fld."'";

	if($state_fld!='')
	{       $loc_post_id = '';
		$_SEESION['state'] = $state_fld;
		$state_query = $wpdb->get_results("select post_id from wp_postmeta where meta_key = 'geoState' AND meta_value = '".$state_fld."'");
		if(count($state_query) > 0)
		{
			foreach($state_query as $state_row) {
				$loc_post_id .= $state_row->post_id.',';
			}
		}
		//$where_clause .= " AND P2.meta_key = 'geoState' AND P2.meta_value like '%$state_fld%'";
	
		if($city_fld!='')
		{       $loc_post_id = '';
			$_SEESION['city'] = $city_fld;
			$city_query = $wpdb->get_results("select post_id from wp_postmeta where meta_key = 'geoCity' AND meta_value = '".$city_fld."'");
			if(count($city_query) > 0)
			{
				foreach($city_query as $city_row) {
					$loc_post_id .= $city_row->post_id.',';
				}
			}
			//$where_clause .= " AND P2.meta_key = 'geoCity' AND P2.meta_value like '%$city_fld%'";
		}
	}
}


$post_id = '';
if($cat_post_id!='')
{
	$post_id = $cat_post_id;
}
if($loc_post_id!='')
{
	$post_id .= $loc_post_id;
}
	$post_id = substr($post_id,0,-1);
	$where_clause .= " AND ID IN (".$post_id.")";
//$where_clause .= " GROUP By P1.ID, P1.post_title";
?>
<?php
$args = array(
  'type' => 'projects',
  'name' => 'category_parent',
  'exclude' => '1',
  'orderby' => 'name',
  'parent' => 0
  );
$categories = get_categories( $args );
//echo '<a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '</a><br/>';

?>
 <!--	<div class="mid_leftarea">
        	<div class="box_mid">
            	<div class="box_left"></div>
                <div class="box_right"></div>
                <div class="category_list">
                <h3>categories</h3>
                    <ul class="category_list">
		    <?php foreach ( $categories as $category ) { ?>
                        <li><a href="<?php echo home_url(); ?>/search/?category=<?php echo $category->term_id; ?>"><?php echo $category->name; ?></a></li>
		    <?php } ?>
		    <li><a href="<?php echo home_url(); ?>/search">View All</a></li>
                    </ul>
                </div>
            </div>
        </div>-->
 <?php //get_sidebar(); ?>
        <div class="mid_rightarea">
        	<div class="box_mid">
            	<div class="box_left"></div>
                <div class="box_right"></div>
                <div>
                    <div class="project_list">
                	<?php
			 //$publish_project=array( 'post_type' => 'projects', 'post_status'=>'publish'); 
			//$publish_project_objs = new WP_Query( 'author_name='.$profile_name ); print_r($publish_project_objs);
                        //$publish_project_objs =  get_posts($publish_project);
			global $wpdb;
			//$publish_project_objs = $wpdb->get_results("select * from wp_posts where post_title like '%$search_fld%' AND post_type = 'projects' AND post_status='publish'");

echo $project_sql = "select * from wp_posts where ".$where_clause;
$publish_project_objs = $wpdb->get_results($project_sql);

			//print_r($publish_project_objs);
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
        <div class="cover-by-link"><a title="Bill Sanderson" href="<?php echo home_url().'/profile/'.$user_profile; ?>" class="cover-link">
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
			} else echo 'No projects available';
                        ?>
                        
                    </div>  
                    
                <div class="clear"></div>
                </div>
            </div>
        </div>


 <?php get_footer();?>
