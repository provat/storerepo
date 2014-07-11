<?php
 $project_id=$_REQUEST['project_id'];
?>
<div class="content_area">
  <div class="upload_cover_area">
      <?php if(has_post_thumbnail( $project_id )){
          
         $src = wp_get_attachment_image_src( get_post_thumbnail_id($project_id), 'full');
           
            echo "<img src=".$src[0]."?img1=".rand()."/>";
         
      }else{
          
          
        ?> 
    <div class="upload_cover_btn">Uplaod Cover</div>
      <?php } ?>
  </div>
    <div class="project_title">
        <span>Project Title</span>
        <input type="text" name="project_title" id="project_title_on_cover_image_form"  value="<?php echo get_the_title($project_id); ?>"/>
        
    </div>  
  <div class="upload_cover">
  <input type="button" value="Crop" name="crop_image" id="cover_image_crop_btn" style="display: none;"> 
  <input type="button" value="Cancle" name="crop_image_cancle" id="cover_image_crop_cancle_btn" style="display: none;">
   <?php if(has_post_thumbnail( $project_id )){
      ?>
  <input type="button" value="Replace Cover Image" name="replace_cover_image" id="replace_cover_image_btn"> 
   <?php
   }
   ?>
  <input type="button" value="Continue" name="Save" id="project_contunue_for_publish">
  <input type="button" value="Cancel" name="cancel" id="upload_cover_cancle_btn">
  <input type="hidden" name="crop_cover_image_x1" id="crop_cover_image_x1">
  <input type="hidden" name="crop_cover_image_y1" id="crop_cover_image_y1">
  <input type="hidden" name="crop_cover_image_x2" id="crop_cover_image_x2">
  <input type="hidden" name="crop_cover_image_y2" id="crop_cover_image_y2">
  <input type="hidden" name="crop_cover_image_w" id="crop_cover_image_w">
  <input type="hidden" name="crop_cover_image_h" id="crop_cover_image_h">
</div>
</div>


<form id="upload_cover_image" method="POST" action="<?php echo get_option( 'siteurl' ); ?>/wp-admin/admin-ajax.php" enctype="multipart/form-data" style="display: none;">
        <input type="hidden" name="action" value="uplad_cover_image_for_portfolio"  />	
        <input type="hidden" name="project_id" class="user_project_id" value="<?php echo $project_id; ?>"/>
        <?php wp_nonce_field( 'uplad_cover_image_for_portfolio', 'security' ); ?>
        
        <input type="file" name="upl_cover" id="upl_cover_image" />
        
</form>
