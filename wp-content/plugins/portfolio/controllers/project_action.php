<?php
Class projects {
     public function __construct(){
        add_action( 'init', array( &$this, 'init' ) );
       
        add_action( 'wp_footer', array( &$this, 'footer' ) );   
       
    }
    
    
    
    public function init(){
         add_action( 'wp_ajax_load_portfolio_template', array( &$this, 'load_template' ) );
         add_action( 'wp_ajax_update_project_title', array( &$this, 'update_project_title' ) );
         add_action( 'wp_ajax_uplad_cover_image_for_portfolio', array( &$this, 'uplad_cover_image_for_portfolio' ) );
         add_action( 'wp_ajax_save_project_image_order', array( &$this, 'save_project_image_order' ) );
         add_action( 'wp_ajax_crop_cover_image', array( &$this, 'crop_cover_image' ) );
         add_action( 'wp_ajax_save_or_publish_project', array( &$this, 'save_or_publish_project' ) );
         add_action( 'wp_ajax_delete_project', array( &$this, 'delete_project' ) );
         add_action( 'wp_ajax_unpublish_project', array( &$this, 'unpublish_project' ) );
         add_action( 'wp_ajax_load_all_tag_list', array( &$this, 'load_all_tag_list' ) );
         add_action( 'wp_ajax_save_portfolio_image_tagging', array( &$this, 'save_portfolio_image_tagging' ) );
         
         
         
         
    }
    
    
   public function uplad_cover_image_for_portfolio(){
       
       check_ajax_referer( 'uplad_cover_image_for_portfolio','security');
       
       $project_id=$_POST['project_id'];
       $project_title=$_POST['project_title'];
        
        $allowed = array('png', 'jpg', 'gif','zip');
        $project_id=$_POST['project_id'];

        if(isset($_FILES['upl_cover']) && $_FILES['upl_cover']['error'] == 0){



              $extension = pathinfo($_FILES['upl_cover']['name'], PATHINFO_EXTENSION);

              if(!in_array(strtolower($extension), $allowed)){
                      echo '{"status":"error"}';
                      exit;
              }

              
          $user_id = get_current_user_id();     
          if(empty($user_id)){
              echo '{"status":"error"}';
              exit;
          }

        
      

        $filename=$_FILES['upl_cover']['name'];


        $wp_filetype = wp_check_filetype(basename($filename), null );
        $wp_upload_dir = wp_upload_dir();

        
          $file_array = array(
              'name' => basename( $wp_upload_dir['url']."/".$_FILES['upl_cover']['name'] ),
              'tmp_name' => $_FILES['upl_cover']['tmp_name']
          );

          // Check for download errors
          if ( is_wp_error( $tmp ) ) {
              @unlink( $file_array[ 'tmp_name' ] );
              return $tmp;
          }
          
          $id = media_handle_sideload( $file_array,$project_id);
          // Check for handle sideload errors.
          if ( is_wp_error( $id ) ) {
              @unlink( $file_array['tmp_name'] );
              return $id;
          }
          $old_image_id = get_post_meta( $project_id, "_thumbnail_id", true );
          
          if(!empty($old_image_id))
              update_post_meta( $project_id, '_thumbnail_id', $id, $old_image_id);
         else 
            add_post_meta($project_id, '_thumbnail_id', $id, true);
          
          $images['attachment'] = remove_width_and_height_attribute(wp_get_attachment_image( $id, 'full' )); 
          $images['id']=$id;
          $images['project_id']=$project_id;

        
         


        }
       
        print json_encode($images);
         die();
       
   } 
   
   
    public function update_project_title(){
        
        check_ajax_referer( $_POST['referer'],'security');
        $project_title=$_POST['project_title'];
        $project_id=$_POST['project_id'];
        
         if (is_user_logged_in() ) {
             
          $this->update_title($project_id,$project_title);     
          $project['project_id']=$project_id;
          $project['project_title']=$project_title;
        
         }
          print json_encode($project);
         die();
    }
    
    public function update_title($project_id, $project_title){
        
        $my_post = array(
            'ID'           => $project_id,
            'post_title' => $project_title
               
            );
             
          wp_update_post( $my_post ); 
          return true;
        
    }
    
    
    public function delete_project(){
        
       check_ajax_referer( $_POST['referer'],'security');
       $project_id= $_POST['project_id'];
        wp_delete_post($project_id);
        
        $projects['project_id']=$project_id;
        $projects['redirect_to']=  get_permalink(53);
          print json_encode($projects);
          die();
        
    }
    
    public function unpublish_project(){
        $project_id= $_POST['project_id'];
         check_ajax_referer( $_POST['referer'],'security');
        
         $my_post = array(
            'ID'           => $project_id,
            
            'post_status'  =>'draft'
               
            );
             
          wp_update_post($my_post);
        
        $projects['redirect_to']=  get_permalink(get_ID('projects'));
        print json_encode($projects);
        die();
    }
    
    public function save_or_publish_project(){
        global $wpdb;
        check_ajax_referer( $_POST['referer'],'security');
        $geoplugin = getLocationInfoByIp();
        
        $project_id=$_POST['project_id'];
        $project_content=$_POST['project_desc'];
        $project_category=$_POST['project_cats'];
        $project_status=$_POST['project_status'];
        $project_title=$_POST['project_title'];
        $project_country=$_POST['project_country'];
        $project_state=$_POST['project_state'];
        $project_city=$_POST['project_city'];
        $project_zip = $_POST['project_zip'];
        $country_all=$_POST['country_all'];
        $state_all=$_POST['state_all'];
        $city_all=$_POST['city_all'];
  if($project_country!=''){
    $country_code = '';
    $country_name = '';
    if(count($project_country) > 1)
    {
      for($i=0;$i<count($project_country); $i++)
      {
        $country_code .= $project_country[$i].',';
        $country_name_q = $wpdb->get_row("select * from wp_country where country_code = '".$project_country[$i]."'");
        $country_name .= $country_name_q->country_name.',';
      }
      $country_code = substr($country_code,0,-1);
      $country_name = substr($country_name,0,-1);
    }
    else{
      $country_code = $project_country;
      $country_name_q = $wpdb->get_row("select * from wp_country where country_code = '".$country_code."'");
      $country_name = $country_name_q->country_name;
    }
  }else{
    $country_name = $geoplugin['country_name'];
    $country_code = $geoplugin['country'];
  }  

  if($project_state!='')
  $state = $project_state;
  else
  $state = $geoplugin['geoState'];     

  if($project_zip!='')
  $zip = $project_zip;  

  if($project_city!='')
  $city = $project_city;
  else
  $city = $geoplugin['geoCity'];       
        
        if(get_post_status( $project_id )=="publish")
            $project_status_new="publish";
        else 
            $project_status_new=$project_status;
        
       
        $my_post = array(
            'ID'           => $project_id,
            'post_title' =>$project_title,
            'post_content' => $project_content,
            'post_category' =>$project_category,
            'post_status'  =>$project_status_new
               
            );
             
          wp_update_post($my_post);
          $pre_ip_address=get_post_meta( $project_id, "ip_address", true );
          update_post_meta($project_id, "ip_address",$geoplugin['ip_address'], $pre_ip_address);
          
          $pre_country_name=get_post_meta( $project_id, "countryName", true );
          update_post_meta($project_id, "countryName",$country_name, $pre_country_name);
          
          $pre_country_code=get_post_meta( $project_id, "countryCode", true );
          update_post_meta($project_id, "countryCode",$country_code, $pre_country_code);
          
          $pre_geoLatitude=get_post_meta( $project_id, "geoLatitude", true );
          update_post_meta($project_id, "geoLatitude",$geoplugin['geoLatitude'], $pre_geoLatitude);
          
          
          $pre_geoLongitude=get_post_meta( $project_id, "geoLongitude", true );
          update_post_meta($project_id, "geoLongitude",$geoplugin['geoLongitude'], $pre_geoLongitude);
          
          $pre_geoState=get_post_meta( $project_id, "geoState", true );
          update_post_meta($project_id, "geoState",$state, $pre_geoState);
          
          $pre_geoCity=get_post_meta( $project_id, "geoCity", true );
          update_post_meta($project_id, "geoCity",$city, $pre_geoCity);

          $pre_geoZip=get_post_meta( $project_id, "geoZip", true );
          update_post_meta($project_id, "geoZip",$zip, $pre_geoZip);

    if($country_all=='Y')
    {
            update_post_meta($project_id, "countryAll",$country_all);
    }
    if($state_all=='Y')
    {
            update_post_meta($project_id, "stateAll",$state_all);
    }
    if($city_all=='Y')
    {
            update_post_meta($project_id, "cityAll",$city_all);
    }
          
          
          $wpdb->query( 
            $wpdb->prepare("UPDATE  ".$wpdb->prefix."posts SET latitude=%s, longitude=%s
                      WHERE ID=%d ",
                      $geoplugin['geoLatitude'], $geoplugin['geoLongitude'], $project_id
            )
            );
          
          
          $projects['status']=$project_status;
          $projects['plink']=  get_permalink($project_id);
          print json_encode($projects);
          die();
        
        
    }
    
    //crop cover images
    
    function crop_cover_image(){
        
        $project_id=$_POST['project_id'];
        $image_x1=$_POST['image_x1'];
        $image_y1=$_POST['image_y1'];
        $image_x2=$_POST['image_x2'];
        $image_y2=$_POST['image_y2'];
        $image_h=$_POST['image_h'];
        $image_w=$_POST['image_w'];
        
         $img_id = get_post_thumbnail_id($project_id); // 35 being the ID of the Post
         $img_url = wp_get_attachment_image_src($img_id, "full");
         $img_url = $img_url[0];
         $site_url=get_bloginfo("siteurl");
         $img_path=  str_replace($site_url, ABSPATH, $img_url);
       
        

        $img = wp_get_image_editor( $img_path);
        if ( ! is_wp_error( $img ) ) {
            //$img->resize( 500, NULL, false );
             $img->crop( $image_x1, $image_y1, $image_w, $image_h, $image_x2, $image_y2, false );
             //$filename = $img->generate_filename( 'croped', ABSPATH.'wp-content/uploads/2013/11/', NULL );
             $img->save($img_path);
             
        }
        
        $project_data['img_url']=$img_url;
        $project_data['img_w']=$image_w;
        $project_data['img_h']=$image_h;
        //$project_data['attachment'] = remove_width_and_height_attribute(wp_get_attachment_image( $img_id, 'medium' )); 
         print json_encode($project_data);
         die();
    }
    
    public function save_project_image_order(){
        global $wpdb;
        $project_id=$_POST['project_id'];
        $sortable_container_val=$_POST['sortable_container_val'];
        $project['project_id']=$project_id;
        
        $user_id = get_current_user_id();     
          if(empty($user_id)){
              echo '{"status":"error"}';
              exit;
          }
        
        if($sortable_container_val!=""){
            $sortable_container_array=  explode(",", $sortable_container_val);
            $i=1;
            foreach($sortable_container_array as $img_id){
               
            $wpdb->query( 
            $wpdb->prepare("UPDATE  ".$wpdb->prefix."portfolio_images SET order_pos=%d
                      WHERE image_id=%d AND user_id=%d AND portfolio_id=%d",
                      $i, $img_id, $user_id, $project_id 
            )
            );
            $i++;
            }
            $project['relode']="yes"; 
          }else{
            $project['relode']="no";
          } 
        
        
         print json_encode($project);
         die();
        
    }
    
    public function footer(){
        
        if ( is_user_logged_in() ) { 
        load_template( plugin_dir_path( dirname( __FILE__ ) ) . 'views/continue_dialog.php' );
        }
        
    }
    
    public function load_all_tag_list(){
      
      $args=array('hide_empty'=>false, 'orderby'=> 'name', 'order'=>'ASC');          
      $images_tag = get_tags($args);
      $tags=array();
      foreach($images_tag as $row_tag){
       $tags[]=$row_tag->name;
       } 

      print json_encode($tags);
      die();

    }

    public function save_portfolio_image_tagging(){
     global $wpdb;
      check_ajax_referer('portfolio_secure_check','security');
      $image_id=$_POST['image_id'];
      $image_tag=$_POST['image_tag'];
      $img_status=$_POST['img_status'];
      $buy_now_link=$_POST['buy_now_link'];
      $buy_now_price=$_POST['buy_now_price'];
      $enable_buy_now=$_POST['enable_buy_now'];
      
      $user_id = get_current_user_id();     
          if(empty($user_id)){
              echo '{"status":"error"}';
              exit;
          }
      
      $tag_arr = explode(",",$image_tag);
      $old_tag = $wpdb->get_results("select * from wp_term_relationships where object_id = '".$image_id."'");

      if(count($old_tag) > 0 ){
  foreach($old_tag as $tag_row){
          if(!in_array($tag_row->term_taxonomy_id,$tag_arr))
    {
            $old_tag = $wpdb->get_results("delete from wp_term_relationships where term_taxonomy_id = '".$tag_row->term_taxonomy_id."'");
    }
  }
      }

      wp_set_post_tags( $image_id, $image_tag, true );

      $wpdb->query( 
            $wpdb->prepare( 
                  "
                  UPDATE ".$wpdb->prefix."portfolio_images SET img_status=%d, buy_now_option=%s, buy_now_price=%s, enable_buy_now=%d WHERE image_id=%d",
                   $img_status , $buy_now_link, $buy_now_price, $enable_buy_now, $image_id
            )
            );



  
      $images['image_id']=$image_id;
      $images['mesage']="Saved";
  
      print json_encode($images);
          
         die();
         exit;



    }
    
    
    public function load_template(){
        if (is_user_logged_in() ) {
        check_ajax_referer( $_POST['referer'],'security');
        load_template( plugin_dir_path( dirname( __FILE__ ) ) . "views/" . $_POST['template'] . '.php' );
        }
        die();
    }
    
}new projects;
