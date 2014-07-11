<?php
global $wpdb;
if (!isset($wpdb)) {
    require_once('../../../../wp-config.php');
    require_once('../../../../wp-load.php');
    require_once('../../../../wp-includes/wp-db.php');
}
wp_reset_query();
$post_per_page = 6;
$paged = ( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;
$offset = ($paged - 1)*$post_per_page;
$offset = ($offset <= 0) ? 0 : $offset;

$publish_project=array( 'post_type' => 'projects', 'post_status'=>'publish', 'posts_per_page'=> $post_per_page,'paged' => $paged,
'offset' => $offset,'orderby' => 'post_date','order'=> 'DESC',);
$publish_project_objs =  new WP_Query($publish_project);


    while ( $publish_project_objs->have_posts() ) : $publish_project_objs->the_post(); 
        get_template_part("content", "project");
    endwhile;

//if($publish_project_objs->post_count==0)
//echo 'end';

?>
