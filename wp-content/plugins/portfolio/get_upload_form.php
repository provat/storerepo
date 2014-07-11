 <script>
jQuery(function($){     
<?php 
$project_id=$_GET['project_id'];
if(get_number_of_image($project_id)<1) { ?>
$( "#upload_form_pop_up" ).dialog({
         autoOpen: true, 
         modal: true
   });
$('.project_toolbar').hide();
<?php }else{ ?>
    $( "#upload_form_pop_up" ).dialog({
        autoOpen: false,  
        modal: true
   }); 
  $('.project_toolbar').show(); 
<?php } ?>
});
</script>
<div id="upload_form_pop_up">
<form id="upload" method="POST" action="<?php echo get_option( 'siteurl' ); ?>/wp-admin/admin-ajax.php" enctype="multipart/form-data">
<input type="hidden" name="action" value="add_media_image_for_portfolio"  />
<?php wp_nonce_field( 'portfolio_secure_check', 'security_portfolio' ); ?>
<input type="hidden" name="project_id" class="user_project_id" value="<?php echo $project_id; ?>"/>
    Select images for your project<br/>
 (Upload multiple images)
   

				<a>Browse</a>
				<input type="file" name="upl" multiple />
			

			
		</form>
</div>    
