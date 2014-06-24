<?php
Class portfolio {
    
    public function __construct(){
        add_action( 'init', array( &$this, 'init' ) );
       
        add_action( 'wp_footer', array( &$this, 'footer' ) );   
       
    }
    
    
    
    public function init(){
       
       
        
        add_action("wp_ajax_load_portfolio_images", array( &$this,'load_portfolio_images'));
        add_action("wp_ajax_add_media_image_for_portfolio", array( &$this,'add_media_image_for_portfolio'));
        
        add_action("wp_ajax_save_portfolio", array( &$this,'save_portfolio'));
        add_action("wp_ajax_delete_media_image", array( &$this,'delete_media_image'));
        add_action("wp_ajax_replace_media_image_for_portfolio", array( &$this,'replace_media_image_for_portfolio'));
        add_action("wp_ajax_save_portfolio_image_caption", array( &$this,'save_portfolio_image_caption'));
        add_action("wp_ajax_get_portfolio_image_caption", array( &$this,'get_portfolio_image_caption'));
        
        
        
        
        
    }
    
    
    
    public function add_media_image_for_portfolio(){

        global  $wpdb;   
        // A list of permitted file extensions
        $allowed = array('png', 'jpg', 'gif','zip');
        $project_id=$_POST['project_id'];

        if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){


              $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

              if(!in_array(strtolower($extension), $allowed)){
                      echo '{"status":"error"}';
                      exit;
              }

              
          $user_id = get_current_user_id();     
          if(empty($user_id)){
              echo '{"status":"error"}';
              exit;
          }

        check_ajax_referer('portfolio_secure_check','security_portfolio');  
        //echo $_FILES['upl']['name'];

        $filename=$_FILES['upl']['name'];


        $wp_filetype = wp_check_filetype(basename($filename), null );
        $wp_upload_dir = wp_upload_dir();

        
          $file_array = array(
              'name' => basename( $wp_upload_dir['url']."/".$_FILES['upl']['name'] ),
              'tmp_name' => $_FILES['upl']['tmp_name']
          );

          // Check for download errors
          if ( is_wp_error( $tmp ) ) {
              @unlink( $file_array[ 'tmp_name' ] );
              return $tmp;
          }

          $id = media_handle_sideload( $file_array, 0 );
          // Check for handle sideload errors.
          if ( is_wp_error( $id ) ) {
              @unlink( $file_array['tmp_name'] );
              return $id;
          }
          
                 
          
            $wpdb->query( 
            $wpdb->prepare( 
                  "
                  INSERT INTO ".$wpdb->prefix."portfolio_images 
                   (user_id, image_id, portfolio_id)
                   VALUES (%d, %d, %d)
                  ",
                  $user_id, $id, $project_id 
            )
            );

          $img_url = wp_get_attachment_image_src( $id, 'full' );
          if($img_url[1] > '900')
	  {
		$images['attachment'] = remove_width_and_height_attribute(wp_get_attachment_image( $id, 'double_medium' )); 
	  }
	  else{
          	$images['attachment'] = '<img src="'.$img_url[0].'" width="'.$img_url[1].'" height="'.$img_url[2].'" />';
	  }
          //$images['attachment'] = remove_width_and_height_attribute(wp_get_attachment_image( $id, 'full' )); 
	
          $images['id']=$id;
          $images['project_id']=$project_id;

         print json_encode($images);


        }



          die();
          exit;
  }
  
   public function replace_media_image_for_portfolio(){
       
       
       $user_id = get_current_user_id();     
          if(empty($user_id)){
              echo '{"status":"error"}';
              exit;
          }
        check_ajax_referer('portfolio_secure_check','security_portfolio');
        //echo $_FILES['upl']['name'];

        $allowed = array('png', 'jpg', 'gif','zip');
        $replace_image_id=$_POST['replace_image_id'];
        
        if(isset($_FILES['upl_replace']) && $_FILES['upl_replace']['error'] == 0){   
          
        $filename=$_FILES['upl_replace']['name'];


        $wp_filetype = wp_check_filetype(basename($filename), null );
        $wp_upload_dir = wp_upload_dir();

        
          $file_array = array(
              'name' => basename( $wp_upload_dir['url']."/".$_FILES['upl_replace']['name'] ),
              'tmp_name' => $_FILES['upl_replace']['tmp_name']
          );
       
       $overrides = array('test_form'=>false);

       $time = current_time( 'mysql' );
        
        
       // $file = wp_handle_sideload( $file_array, $overrides, $time );
        $file = wp_handle_upload($_FILES['upl_replace'],$overrides, $time);
       
	if ( isset($file['error']) ){
		 echo '{"status":"error"}';
               die();
        }
       }
       
       update_attached_file( $replace_image_id, $file['url'] );
       $generate_metadata=wp_generate_attachment_metadata( $replace_image_id, $file['file'] );
       wp_update_attachment_metadata( $replace_image_id, $generate_metadata );
       $images['attachment']=remove_width_and_height_attribute(wp_get_attachment_image( $replace_image_id, 'full' ));  

       $url = $file['url'];
       $images['url']=$url;
       //$images['data']=$file;
       //$images['metadata']=$generate_metadata;
       $images['image_id']=$replace_image_id;
       $images['id']=$replace_image_id;
        print json_encode($images);
        
        die();
        exit;
       
   }
  
  public function save_portfolio(){
      
    check_ajax_referer('portfolio_secure_check','security');
      
    $user_id = get_current_user_id();     
          if(empty($user_id)){
              echo '{"status":"error"}';
              exit;
          }  
          
     $project_id=$_POST['project_id'];  
             
     $post_title=$_POST['project_title']; 
     $image_id=$_POST['image_id'];
     //print_r($image_id);
     
    if(empty($project_id)){
        
             $project_id= $this->create_project($user_id,$post_title);
             $this->update_image_to_project($project_id,$user_id,$image_id);
             //$data['image_id']=count($image_id);
             $data['project_id']=$project_id;  
    }
    else if(!empty($project_id)){  
     $data['project_id']=$project_id;  
     $my_post = array(
      'ID'           => $project_id,
      'post_title'   => $post_title, 
      'post_content' => ''
     );
     wp_update_post( $post ); 
    } 
    
    
        print json_encode($data);
        
        die();
        exit;
  }
  
  public function create_project($user_ID,$post_title){
      
      $defaults = array(
    'post_status'           => 'draft', 
    'post_type'             => 'projects',
    'post_title'            => $post_title,
    'post_author'           => $user_ID,
    'ping_status'           => get_option('default_ping_status'), 
    'post_parent'           => 0,
    'menu_order'            => 0,
    'to_ping'               =>  '',
    'pinged'                => '',
    'post_password'         => '',
    'guid'                  => '',
    'post_content_filtered' => '',
    'post_excerpt'          => '',
    'import_id'             => 0
    );
      
    return $project_id=  wp_insert_post( $defaults); 
  }

  
  public function update_image_to_project($project_id, $user_ID,$image_IDs){
       global  $wpdb;   
       if(is_array($image_IDs)){
          
           
        foreach($image_IDs as $img_id) { 
            
            
        $wpdb->query( 
            $wpdb->prepare("UPDATE  ".$wpdb->prefix."portfolio_images SET portfolio_id=%d
                      WHERE image_id=%d AND user_id=%d",
                      $project_id, $img_id, $user_ID 
            )
            );
        }
       }else{
           
            $wpdb->query( 
            $wpdb->prepare("UPDATE  ".$wpdb->prefix."portfolio_images SET portfolio_id=%d
                      WHERE image_id=%d AND user_id=%d",
                      $project_id, $image_IDs, $user_ID ));
           
       }
      
      
  }

  
  public function load_portfolio_images(){
      global $wpdb;
      
      
      
      $user_id = get_current_user_id();     
          if(empty($user_id)){
              echo '{"status":"error"}';
              exit;
          }
          
         if(!empty($_GET['project_id'])){
             $project_id= $_GET['project_id'];
         }else
             $project_id=0;
          
         $resault= $wpdb->get_results( 
            $wpdb->prepare( 
                  "
                  SELECT * FROM ".$wpdb->prefix."portfolio_images pi, ".$wpdb->prefix."posts p
                   WHERE p.post_author=%d AND p.ID=pi.image_id AND portfolio_id=%d ORDER BY pi.order_pos ASC
                   
                  ",
                  $user_id, $project_id  
            )
            );
         $i=0;
         foreach($resault as $res){
             //$images[$i]['attachment'] = remove_width_and_height_attribute(wp_get_attachment_image( $res->image_id, 'meddium' )); 
	      $src = wp_get_attachment_image_src($res->image_id , 'full');
	      if($src[2] > 500)
		$height = '500';
	      else
		$height = $src[2];
	      if($src[1] > 900)
		$width = '900';
	      else
		$width = $src[1];
	      $images[$i]['attachment'].='<img src="'.$src[0].'" width="'.$width.'" height="'.$height.'" class=""/>'; 

             $images[$i]['img_caption']=get_post_field('post_excerpt', $res->image_id);
             $images[$i]['id']=$res->image_id;
             $i++;
         }
          print json_encode($images);
          
          die();
         exit;
          
          
  }
  
  
  public function save_portfolio_image_caption(){
      
      check_ajax_referer('portfolio_secure_check','security');
      $image_id=$_POST['image_id'];
      $img_caption=$_POST['img_caption'];
      
      $user_id = get_current_user_id();     
          if(empty($user_id)){
              echo '{"status":"error"}';
              exit;
          }
      
       $my_post = array(
      'ID'           => $image_id,
      'post_excerpt' => $img_caption
               
  );

// Update the post into the database
  wp_update_post( $my_post );
  
  $images['image_id']=$image_id;
  $images['img_caption']=$img_caption;
  
      print json_encode($images);
          
          die();
         exit;
      
      
  }
  
  
  public function get_portfolio_image_caption(){
      
      $image_id= $_REQUEST['image_id'];
      //check_ajax_referer('portfolio_secure_check','security'); 
      
      $images['img_caption']=get_post_field('post_excerpt', $image_id);
      $images['image_id']=$image_id;
      
      print json_encode($images);
          
          die();
         exit;
      
  }
          
  
  public function delete_media_image(){
      
      
      global $wpdb;
      $user_id = get_current_user_id();     
          if(empty($user_id)){
              echo '{"status":"error"}';
              exit;
          }
       
      $image_id= $_POST['image_id'];
      check_ajax_referer('portfolio_secure_check','security');    
      
      if(wp_delete_attachment( $image_id )){
          $rec['image_id']=$image_id;
          $rec['deleted']=1;
      }
      
            
      print json_encode($rec);
          
          die();
         exit;
      
  }
  
  
  public function footer(){
      //echo $str='<div class="back-loader" style="display: none;"><span class="back-load"></span></div>';
  }


}// end of class
new portfolio;
