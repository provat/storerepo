<?php
/**
 * Template Name: Editor Page
 */
if(!is_user_logged_in()){
    wp_redirect( home_url()."#loginbox=1");
    exit;
}
//add_action('wp_head', 'image_editor_script',8);
get_header(); ?>

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
                	<?php  echo do_shortcode('[upload_form]');  ?>   
                        
                <div class="clear"></div>
                </div>
            </div>
        </div>


<?php

add_action('wp_footer','my_admin_print_footer_scripts',99);
function my_admin_print_footer_scripts()
{
    ?>
   <script type="text/javascript">/* <![CDATA[ */
        jQuery(function($)
        {
            var i=1;
            $('.customEditor textarea').each(function(e)
            {
                var id = $(this).attr('id');
 
                if (!id)
                {
                    id = 'customEditor-' + i++;
                    $(this).attr('id',id);
                }
 
                tinyMCE.execCommand('mceAddControl', false, id);
                 
            });
        });
    /* ]]> */</script>
    <?php
}
?>

<div  style="display:none;">    
<?php 
$settings = array( 'media_buttons' => false );
the_editor('aaaa','myeditor_aaa',$settings); 
?>
</div>

<?php get_footer(); ?>
