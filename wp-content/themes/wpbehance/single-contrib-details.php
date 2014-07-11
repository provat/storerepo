<?php
get_header(); 
//$profile_name = substr(basename($_SERVER['REQUEST_URI']),1);
//$profile_user = $wpdb->get_row(  "select * from ".$wpdb->prefix."usermeta where meta_key = 'profile_name' AND meta_value = '".$profile_name."'");
//$author_id = $profile_user->user_id;
$author_id=$_REQUEST['id'];
$user_profile = get_user_meta($author_id, 'profile_name', true);
?>

<!--========================Middle Start=====================-->
<div class="mid_wrapper">
    <div class="main_wrap">
        	<div class="no_left_box_mid">
            	<div class="box_left"></div>
                <div class="box_right"></div>
                <div class="port_feature">
                	<h1 class="potfolio_title"> <?php echo $user_profile; ?></h1>
                	 <div class="user_area">
                     	<div class="user_area_img"><img src="<?php bloginfo('template_url') ?>/images/user_img.jpg" alt="img" /></div>
                        <div class="user_area_text">
                        	<div class="user_featurearea"><strong>Name:</strong> <?php echo  $author_name = get_the_author_meta( 'display_name' , $author_id ); ?><br />
                            <strong>Member Since:</strong>  Jan 05, 2013<br />
                            <strong>Place:</strong>  <?php echo  $author_name = get_the_author_meta( 'city' , $author_id ); ?>, <?php echo  $author_name = get_the_author_meta( 'country' , $author_id ); ?>.</div>
                            <div class="user_social_area">
                      <a href="#"><img src="<?php bloginfo('template_url') ?>/images/facebook.png" alt="img" /></a><a href="#"><img src="<?php bloginfo('template_url') ?>/images/twitter.png" alt="img" /></a><a href="#"><img src="<?php bloginfo('template_url') ?>/images/google+.png" alt="img" /></a>
                      </div>
                        </div>
                     </div>
                <div class="clear"></div> 
                </div>
                
                <div>
                 <?php
                 // get user projects
                 global $wpdb;
                




 $sql_query=$wpdb->prepare("SELECT  p.ID FROM ".$wpdb->prefix."posts p WHERE  p.post_author=%d  AND p.post_type = 'projects' AND (p.post_status = 'publish')  ORDER BY p.post_date DESC LIMIT 0, 6", $author_id);

 $resaults=$wpdb->get_results($sql_query);
 //var_dump($resaults);
 foreach($resaults as $projects){
         $GLOBALS['project']=$projects;
         get_template_part("content", "project");
 } 
                
                 ?>
                    
                   
                   
                <div class="clear"></div>
                </div>
            </div>

    <div class="clear"></div>
    </div>
</div>
<!--========================Middle End=====================--> 
<?php get_footer(); ?>