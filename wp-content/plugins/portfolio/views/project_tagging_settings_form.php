<?php
echo $project_id;
$image_id=$_REQUEST['image_id'];

global $wpdb;

$resault= $wpdb->get_results(
            $wpdb->prepare(
                  "
                  SELECT * FROM ".$wpdb->prefix."portfolio_images
                   WHERE image_id=%d
                  ",
                  $image_id
            )
            );


?>

<div class="tag1">



   <?php

   $tag_ids = wp_get_post_tags( $image_id);
   if($tag_ids){
    $cat_ids="";
    $i=0;
    $total_tag_id=count($tag_ids);
   foreach($tag_ids as $tagdata){
       $cat_ids.=$tagdata->name;
       if($i<$total_tag_id)
       $cat_ids.=",";
       $i++;
     }
   }

/*if($cat_ids=='')
{
$project_id = $_REQUEST['project_id'];
$post_categories = wp_get_post_categories( $project_id );
    $cat_ids="";
    $i=0;
    $total_cat_id=count($post_categories);
    foreach($post_categories as $catdata){
       $cat_ids.=$catdata;
       if($i<$total_cat_id)
       $cat_ids.=",";
       $i++;
     }
}*/
    //echo $cat_ids;
   ?>
       <span>Image Tag</span>


          <input name="image_tags" id="image_FieldTags" class="image_tag_field<?php echo $image_id; ?>" value="<?php echo $cat_ids; ?>">




</div>
<div class="tag2"><span>Image Status</span>
<input type="radio" name="image_status<?php echo $image_id; ?>" value="1" <?php echo ($resault[0]->img_status==1)?"checked":""; ?>> <label>New</label>
<input type="radio" name="image_status<?php echo $image_id; ?>" value="2" <?php echo $resault[0]->img_status==2?"checked":""; ?>> <label>Feature</label>
<input type="radio" name="image_status<?php echo $image_id; ?>" value="0" <?php echo $resault[0]->img_status==0?"checked":""; ?>> <label>None</label>

</div>

<br class="clear"/>

<div class="tag3">
<input type="checkbox" name="enable_buy_now<?php echo $image_id; ?>" value="1" <?php echo $resault[0]->enable_buy_now==1?"checked":""; ?> id="enable_buy_now<?php echo $image_id; ?>">
<span>Enable Buy Now Button?</span>

<label>Buy Now Link</label>
<input type="text" name="buy_now_link<?php echo $image_id; ?>" id="buy_now_link<?php echo $image_id; ?>" value="<?php echo $resault[0]->buy_now_option; ?>">

<label>Price</label>
<input type="text" name="buy_now_price<?php echo $image_id; ?>" id="buy_now_price<?php echo $image_id; ?>" value="<?php echo $resault[0]->buy_now_price; ?>">

</div>


  <div class="button_panel">
    <input type="button"  class="save_tagging_btn" name="Save" Value="Save" />
    <input type="button" name="cancle" Value="Cancel" class="cancle_tagging_btn"/>
  </div>


