<?php
$project_id=$_GET['project_id'];
?>
<div class="project_title"><h1 id="project_title">
    <?php 
if(empty($project_id)){
    $project_title= "Untitle Project";
}else{
    $project_title= get_the_title($project_id); 
} 
echo $project_title;
?>

</h1><input type="hidden" name="project_title_hidden_record" id="project_title_hidden_record" value="<?php echo $project_title; ?>"><span class="edit_title">Edit</span></div>

<input type="hidden" name="project_id" class="user_project_id" value="<?php echo $project_id; ?>"/>
<input type="hidden" name="num_images_in_portfolio" id="num_images_in_portfolio">
<?php wp_nonce_field( 'portfolio_secure_check', 'security_portfolio' ); ?>

<form id="replaceImageFileForm" method="POST" action="<?php echo get_option( 'siteurl' ); ?>/wp-admin/admin-ajax.php" enctype="multipart/form-data">
    <input type="hidden" name="action" value="replace_media_image_for_portfolio"  />
    <input type="hidden" name="replace_image_id" id="replace_image_id" />
    <input type="file" id="replaceImageFile" name="upl_replace" />
    <?php wp_nonce_field( 'portfolio_secure_check', 'security_portfolio' ); ?>
</form>

<div class="portfolio_upload_image_button">
    <div class="hover_upload_form">
        <span>Upload Images</span>
        
    </div>
        
</div>

<script type="text/javascript">
jQuery(document).ready(function($){
$('.grid img').each(function(i, item) {
var img_height = $(item).height();
var div_height = 300;
if(img_height>div_height){
$(item).css({'width': 'auto', 'height': div_height });
var img_width = $(item).width();
var div_width = $(item).parent().width();
var newMargin = (div_width-img_width)/2+'px';
}else{
var newMargin = (div_height-img_height)/2+'px';
}
});
});
</script>
<div id="dynamic_port_folio_images">
    
<ul>
				<!-- The file uploads will be shown here -->
			</ul>
</div>
<div class="portfolio_upload_image_button"><div class="hover_upload_form">
        <span>Upload Images</span>
        
    </div></div>

<form id="upload_on_hover" method="POST" action="<?php echo get_option( 'siteurl' ); ?>/wp-admin/admin-ajax.php" enctype="multipart/form-data" style="display: none;">
        <input type="hidden" name="action" value="add_media_image_for_portfolio"  />			
         <input type="hidden" name="project_id" class="user_project_id" value="<?php echo $project_id; ?>"/>
         <?php wp_nonce_field( 'portfolio_secure_check', 'security_portfolio' ); ?>
         <input type="file" name="upl" multiple  id="upload_on_mouse_hove_in_editor"/>
			
		</form>
