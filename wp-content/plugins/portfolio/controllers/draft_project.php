<?php
function get_draft_project(){
    
    if ( is_user_logged_in() ) {
       
       $user_id = get_current_user_id();  
       $meta_key= get_user_meta($user_id, $key, $single);
       query_posts('author='.$user_id.', post_type=portfolio');
       
       $draft_project=array( 'post_type' => 'portfolio', 'author' => $user_id , 'post_status'=>'draft');
       $draft_objs =  get_posts($draft_project);
       if(count($draft_objs)){
           foreach($draft_objs as $draft_ob) { 
           ?>
<div class="draft_list">
    
     <?php echo get_the_post_thumbnail( $draft_ob->ID, $size, $attr ); ?> 
    
</div>

           <?php
           }
           
       }
          
              
          }
         
      
 }
?>