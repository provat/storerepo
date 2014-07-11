<?php
global $wpdb;
$project_id=$_POST['project_id'];
$user_id = get_current_user_id(); 

$resault= $wpdb->get_results( 
            $wpdb->prepare( 
                  "
                  SELECT * FROM ".$wpdb->prefix."portfolio_images pi, ".$wpdb->prefix."posts p
                   WHERE  p.ID=pi.image_id AND portfolio_id=%d ORDER BY pi.order_pos ASC
                   
                  ",
                  $project_id  
            )
            );

//var_dump($resault);

?>
<div class="sortable_container">
    <input type="hidden" name="sortable_container_values" id="sortable_container_values"/>
    <ul class="sortable_block" type="none">
        <?php
        foreach($resault as $res){
            ?>
        <li class='ui-state-default' image_id="<?php echo $res->image_id; ?>"> <div class="sortable_icon"></div>
            <div class="sortable_image"><?php echo remove_width_and_height_attribute(wp_get_attachment_image( $res->image_id, 'medium' )); ?>
            </div> 
            </li>
          <?php   
        }
        ?>
    </ul>
    
</div>

<div class="button_panel">
    <input type="button" value="Save" name="save_image_order"  id="save_image_order">
 
  <input type="button" value="Cancel" name="cancel" id="upload_cover_cancle_btn">
</div>