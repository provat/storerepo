<?php
$project_id=$_REQUEST['project_id'];
?>
<div class="projects_settings_frms">
    
    <span>Category</span>
    <div>
   <?php 
   
   $rew_args = array(
             		
             		'child_of'                 => 0,
             		'parent'                   => '',
             		'orderby'                  => 'name',
             		'order'                    => 'ASC',
             		'hide_empty'               => 0,
             		'hierarchical'             => 1,
             		'exclude'                  => '1',
             		'include'                  => '',
             		'number'                   => ''
             	
             	);
             
   $rew_categories = get_categories( $rew_args );
   $categories = get_the_category($project_id);
   if($categories){
   foreach($categories as $cat_data)
       $cat_ids[]=$cat_data->term_id;
   }
   //var_dump($rew_categories);
   //wp_dropdown_categories('orderby=name&exclude=1&hide_empty=0'); 
   
   ?> 
        <select name="project_cat" id="project_categories" class="chosen-select dropdown_select_cat" multiple>
          
            <?php foreach($rew_categories as $rew_category){ ?>
            <option value="<?php echo $rew_category->term_id; ?>" <?php if(@in_array($rew_category->term_id,$cat_ids)){ echo "selected"; }?> ><?php echo $rew_category->name; ?></option>
            <?php } ?>
            
        </select>   

	 
    </div>
    
    <?php $country_list = $wpdb->get_results("select * from wp_country order by country_name"); ?>
    <span> Description </span>
    <div><textarea name="project_description" id="project_description"><?php echo get_post_field('post_content', $project_id);?></textarea></div>
     <span> Date Of Project </span>
    <div> <input type="date" name="project_date" id="project_date"  value=""/> </div>
 
    	<div class="project_title address_dynamic">
        <h2 class = "address-heading">---------------Registered Address ------------</h2>
        <span>Country</span>
        <select name="project_country" id="project_country1" class = "project_country" style="border: 1px solid #CCCCCC;padding: 6px 10px;width: 98%;height:28px;background-color:#fff;height:70px" multiple="multiple"><option value="">select</option>
		<?php if(count($country_list) > 0){
		foreach($country_list as $row_con){ ?>
		<option value="<?php echo $row_con->country_code; ?>"><?php echo $row_con->country_name; ?></option>
		<?php } } ?>
	</select>
  <br/><span></span>&nbsp;&nbsp;<span><input type="checkbox" name="country_all1" id="country_all" value="Y">Check if geo tagged for all country</span><br/><br/>
        <span>Street Address</span>
        <input type="text" name="project_street" class = "project_street" id="project_street1"  value=""/>      
  <br/><span></span>&nbsp;&nbsp;<span><input type="checkbox" name="street_all" id="street_all1" value="Y">Check if geo tagged for all street</span><br/><br/>
  
        <span>State</span>
        <input type="text" name="project_state" class = "project_state" id="project_state1"  value=""/>      
	<br/><span></span>&nbsp;&nbsp;<span><input type="checkbox" name="state_all" id="state_all1" value="Y">Check if geo tagged for all states</span><br/><br/>
        <span>City</span>
        <input type="text" name="project_city" class = "project_city" id="project_city1"  value=""/>
	<br/><span></span>&nbsp;&nbsp;<span><input type="checkbox" name="city_all" id="city_all1" value="Y">Check if geo tagged for all cities</span><br/><br/>  
	 <span>ZipCode</span>
        <input type="text" name="project_zip" class = "project_zip" id="project_zip1"  value=""/>
  <br/><span></span>&nbsp;&nbsp;<span><input type="checkbox" name="zip_all" id="zip_all1" value="Y">Check if geo tagged for all zip</span><br/>  
 <div class = "child-btn">
      <div class = "add-more">Add More Address</div>
      <br>
  </div>
  </div>
  <input type = "hidden" class = "count_div">
  <input type = "hidden" class = "formation_id">
 
</div>

 <div class="button_panel">
     <input type="button" value="Save" name="save_poject"  id="project_settings_save">
     <input type="button" value="Publish" name="publish_poject" id="publish_project_opt_settings" >
     <input type="button" value="Cancel" name="cancel" id="upload_cover_cancle_btn">
</div>

