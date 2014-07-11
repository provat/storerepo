<?php
 $project_id=$_REQUEST['project_id'];


?>
<div class="project_title_editor">
    <input type="text" name="project_title_text" id="project_title_text" class="input_text" value="<?php echo (empty($project_id))?"Untitle Project":get_the_title($project_id); ?>"/>  
    
</div>

<div class="button_panel">
<input class="save_project_title_btn" type="button" value="Save" name="Save">
<input class="cancle_project_title_btn" type="button" value="Cancle" name="cancle">
</div>