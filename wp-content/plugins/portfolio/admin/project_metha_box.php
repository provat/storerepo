<?php


function portfolio_add_custom_box() {
    
    if(ICL_LANGUAGE_CODE!="en"){
    $screens = array( 'projects');

    foreach ( $screens as $screen ) {

        add_meta_box(
            'project_image_sectionid',
            'Copy Project Images from english',
            'project_inner_custom_box',
            $screen,'side','core'
        );
    }
    }else{
        
        add_meta_box(
            'project_image_list_sectionid',
            'Project Images',
            'project_image_list_custom_box',
            $screen[0],'normal','core'
        );
        
    }
}


function project_inner_custom_box( $post ) {
?>
<script type="text/javascript">
    
</script>    

 <?php   
  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'project_inner_custom_box', 'project_inner_custom_box_nonce' );

  /*
   * Use get_post_meta() to retrieve an existing value
   * from the database and use the value for the form.
   */
  

  echo '<label for="myplugin_new_field">';
       _e( "Copy Project Images", 'myplugin_textdomain' );
  echo '</label> ';
  echo '<input type="button" id="project_image_copy_btn" name="project_image_copy_btn" value="Copy" size="25" />';

}




function project_image_list_custom_box()
{
    
}


add_action( 'add_meta_boxes', 'portfolio_add_custom_box' );