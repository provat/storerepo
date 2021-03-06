<?php
Class DisplayProjects {

  public function __construct(){
    add_action( 'init', array( &$this, 'init' ) );
  }

  public function init(){
       add_action( 'wp_ajax_nopriv_display_projects_accor_location', array( &$this, 'display_projects_accor_location' ) );
       add_action( 'wp_ajax_display_projects_accor_location', array( &$this, 'display_projects_accor_location' ) );

       add_action( 'wp_ajax_nopriv_display_projects_accor_date', array( &$this, 'display_projects_accor_date' ) );
       add_action( 'wp_ajax_display_projects_accor_date', array( &$this, 'display_projects_accor_date' ) );
       add_action( 'wp_ajax_display_follow_projects', array( &$this, 'display_follow_projects' ) );
       add_action( 'wp_ajax_display_my_wishlist', array( &$this, 'display_my_wishlist' ) );


       add_action( 'wp_ajax_nopriv_display_projects_accor_country', array( &$this, 'display_projects_accor_country' ) );
       add_action( 'wp_ajax_display_projects_accor_country', array( &$this, 'display_projects_accor_country' ) );

       add_action( 'wp_ajax_nopriv_display_projects_by_category', array( &$this, 'display_projects_by_category' ) );
       add_action( 'wp_ajax_display_projects_by_category', array( &$this, 'display_projects_by_category' ) );

       add_action( 'wp_ajax_nopriv_display_projects_images', array( &$this, 'display_projects_images' ) );
       add_action( 'wp_ajax_display_projects_images', array( &$this, 'display_projects_images' ) );


       add_action( 'wp_ajax_nopriv_get_post_images_by_tag', array( &$this, 'get_post_images_by_tag' ) );
       add_action( 'wp_ajax_get_post_images_by_tag', array( &$this, 'get_post_images_by_tag' ) );


       add_action( 'wp_ajax_nopriv_add_to_my_wishlist', array( &$this, 'login_for_wishlist' ) );
       add_action( 'wp_ajax_add_to_my_wishlist', array( &$this, 'add_to_my_wishlist' ) );

       add_action( 'wp_ajax_nopriv_add_to_my_wishlist_lb', array( &$this, 'add_to_my_wishlist_lb' ) );
       add_action( 'wp_ajax_add_to_my_wishlist_lb', array( &$this, 'add_to_my_wishlist_lb' ) );

       add_action( 'wp_ajax_nopriv_delete_from_my_wishlist', array( &$this, 'login_for_wishlist' ) );
       add_action( 'wp_ajax_delete_from_my_wishlist', array( &$this, 'delete_from_my_wishlist' ) );

  }


    public function display_projects_accor_location() {
    global $wpdb;
    $post_per_page = 12;
    $paged = ( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;
    $latitude=$_POST['latitude'];
    $longitude=$_POST['longitude'];
    $offset = ($paged - 1)*$post_per_page;
    $offset = ($offset <= 0) ? 0 : $offset;


if( $latitude !='' &&  $longitude !='')
{
/*$sql_query=" SELECT p.ID, get_distance_in_miles_between_geo_locations(".$latitude." , ".$longitude.", p.latitude, p.longitude) as distance_from_input FROM ".$wpdb->prefix."posts p JOIN ".$wpdb->prefix."icl_translations t ON p.ID = t.element_id AND t.element_type = 'post_projects' JOIN ".$wpdb->prefix."icl_languages l ON t.language_code=l.code AND l.active=1 WHERE  p.post_type = 'projects' AND (p.post_status = 'publish') AND t.language_code='".ICL_LANGUAGE_CODE."' ORDER BY distance_from_input ASC LIMIT ".$offset." ,".$post_per_page;*/

//$sql_query=" SELECT ID, get_distance_in_miles_between_geo_locations(".$latitude." , ".$longitude.", latitude, longitude) as distance_from_input FROM ".$wpdb->prefix."posts WHERE  post_type = 'projects' AND (post_status = 'publish') ORDER BY distance_from_input ASC LIMIT ".$offset." ,".$post_per_page;

$sql_query = "SELECT p.*, (((acos(sin(($latitude*pi()/180)) * sin((`latitude`*pi()/180))+cos(($latitude*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($longitude - `longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) AS `distance` FROM ".$wpdb->prefix."posts p  WHERE  p.post_type = 'projects' AND (p.post_status = 'publish') ORDER BY distance ASC,p.ID DESC LIMIT ".$offset." ,".$post_per_page;
}
else
{
$sql_query=" SELECT ID, latitude, longitude FROM ".$wpdb->prefix."posts WHERE  post_type = 'projects' AND (post_status = 'publish') ORDER BY ID DESC LIMIT ".$offset." ,".$post_per_page;
}


  $resaults=$wpdb->get_results($sql_query);

  if(count($resaults)<$post_per_page)
        $last_page=1;
  else
        $last_page=2;

  ob_start();
  foreach($resaults as $projects){


           $GLOBALS['project']=$projects;

           get_template_part("content", "project");

  }
$data['sql_query'] = $sql_query;
  $data['content'] = ob_get_contents();
  $data['paged']=$paged;
  $data['lpage']=$last_page;
  $data['qq']=$sql_query;
  $data['cc']=count($resaults);

  ob_end_clean();



  print json_encode($data);


    die();


    }



    public function display_projects_accor_date() {
    global $wpdb;
    $post_per_page = 12;
    $paged = ( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;
    $offset = ($paged - 1)*$post_per_page;
    $offset = ($offset <= 0) ? 0 : $offset;

    /*$sql_query="SELECT  p.ID FROM ".$wpdb->prefix."posts p JOIN ".$wpdb->prefix."icl_translations t ON p.ID = t.element_id AND t.element_type = 'post_projects' JOIN ".$wpdb->prefix."icl_languages l ON t.language_code=l.code AND l.active=1 WHERE  p.post_type = 'projects' AND (p.post_status = 'publish') AND t.language_code='".ICL_LANGUAGE_CODE."'  ORDER BY p.post_date DESC LIMIT ".$offset.", ".$post_per_page;*/

    $sql_query="SELECT  ID FROM ".$wpdb->prefix."posts WHERE  post_type = 'projects' AND (post_status = 'publish') ORDER BY post_date,ID DESC LIMIT ".$offset.", ".$post_per_page;

    $resaults=$wpdb->get_results($sql_query);

    if(count($resaults)<$post_per_page)
        $last_page=1;
    else
        $last_page=2;

    ob_start();
    foreach($resaults as $projects){


             $GLOBALS['project']=$projects;
             get_template_part("content", "project");

    }

  $data['content'] = ob_get_contents();
  $data['paged']=$paged;
  $data['lpage']=$last_page;
   $data['qq']=$sql_query;
  $data['cc']=count($resaults);

  ob_end_clean();



  print json_encode($data);


     die();

    }

    function display_follow_projects(){

    global $wpdb;
    $post_per_page = 12;
    $paged = ( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;
    $offset = ($paged - 1)*$post_per_page;
    $offset = ($offset <= 0) ? 0 : $offset;


        $user_id = get_current_user_id();
          if(empty($user_id)){
              echo '{"status":"error"}';
              exit;
          }

    $resaults = $wpdb->get_results($wpdb->prepare("select p.ID from " . $wpdb->prefix . "followers fp, ".$wpdb->prefix."posts p where fp.post_id=p.ID and user_id =%d ORDER BY p.post_date,p.ID DESC LIMIT %d, %d", $user_id,$offset,$post_per_page));


    if(count($resaults)<$post_per_page)
        $last_page=1;
    else
        $last_page=2;

    ob_start();
    foreach($resaults as $projects){


             $GLOBALS['project']=$projects;
             get_template_part("content", "project");

    }

  $data['content'] = ob_get_contents();
  $data['paged']=$paged;
  $data['lpage']=$last_page;
   //$data['qq']=$sql_query;
  //$data['cc']=count($resaults);

  ob_end_clean();


          print json_encode($data);


     die();
    }


   public function display_projects_by_category() {
    global $wpdb;
    $post_per_page = 3;
    $paged = ( $_REQUEST['page'] ) ? $_REQUEST['page'] : 4;
    $offset = ($paged - 1)*$post_per_page;
    $offset = ($offset <= 0) ? 0 : $offset;
    $cur_cat_id=$_REQUEST['category_id'];

     //$sql_query=$wpdb->prepare("SELECT  p.ID FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta pm WHERE p.ID=pm.post_id AND pm.meta_key='countryCode' AND pm.meta_value=%s AND p.post_type = 'projects' AND (p.post_status = 'publish')  ORDER BY p.post_date DESC LIMIT %d, %d", $country_code, $offset, $post_per_page);

    //$resaults=$wpdb->get_results($sql_query);

    $publish_project=array( 'post_type' => 'projects', 'post_status'=>'publish','category'=> $cur_cat_id,'posts_per_page'=> $post_per_page,'offset'=> $offset);
    $resaults =  get_posts($publish_project);

    if(count($resaults)<$post_per_page)
        $last_page=1;
    else
        $last_page=2;

    ob_start();
    foreach($resaults as $projects){


             $GLOBALS['project']=$projects;
             get_template_part("content", "project");

    }

  $data['content'] = ob_get_contents();
  $data['paged']=$paged;
  $data['lpage']=$last_page;
  // $data['qq']=$sql_query;
  $data['cc']=count($resaults);

  ob_end_clean();



  print json_encode($data);


     die();

    }


   public function display_projects_accor_country() {
    global $wpdb;
    $post_per_page = 3;
    $paged = ( $_REQUEST['page'] ) ? $_REQUEST['page'] : 4;
    $offset = ($paged - 1)*$post_per_page;
    $offset = ($offset <= 0) ? 0 : $offset;
    $country_code=$_REQUEST['countryCode'];

     $sql_query=$wpdb->prepare("SELECT  p.ID FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta pm WHERE p.ID=pm.post_id AND pm.meta_key='countryCode' AND pm.meta_value=%s AND p.post_type = 'projects' AND (p.post_status = 'publish')  ORDER BY p.post_date,p.ID DESC LIMIT %d, %d", $country_code, $offset, $post_per_page);

    $resaults=$wpdb->get_results($sql_query);



    if(count($resaults)<$post_per_page)
        $last_page=1;
    else
        $last_page=2;

    ob_start();
    foreach($resaults as $projects){


             $GLOBALS['project']=$projects;
             get_template_part("content", "project");

    }

  $data['content'] = ob_get_contents();
  $data['paged']=$paged;
  $data['lpage']=$last_page;
   $data['qq']=$sql_query;
  $data['cc']=count($resaults);

  ob_end_clean();



  print json_encode($data);


     die();

    }


  /**
   * This function displays the products on projetc's/store's details page
   */
  public function display_projects_images() {
    global $wpdb;

    $post_per_page = 9;
    $paged = ( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;
    $offset = ($paged - 1)*$post_per_page;
    $offset = ($offset <= 0) ? 0 : $offset;
    $project_id = $_REQUEST['project_id'];
    $terms_name = $_REQUEST['terms_name'];

    if($terms_name != ""){
      $wp_prepare=$wpdb->prepare(
      "SELECT pi.* FROM ".$wpdb->prefix."portfolio_images pi, ".$wpdb->prefix."posts p, ".$wpdb->prefix."terms t, ".$wpdb->prefix."term_relationships tr
       WHERE t.name=%s AND tr.term_taxonomy_id=t.term_id AND tr.object_id=pi.image_id AND p.ID=pi.image_id AND pi.portfolio_id=%d ORDER BY pi.order_pos ASC
      ",$terms_name,$project_id);
    } else {

     $wp_prepare=$wpdb->prepare("SELECT pi.* FROM ".$wpdb->prefix."portfolio_images pi, ".$wpdb->prefix."posts p
       WHERE p.ID=pi.image_id AND portfolio_id=%d ORDER BY pi.order_pos ASC
      ",$project_id );
    }

    $resault= $wpdb->get_results($wp_prepare);

    if(count($resault)){
      //$image_content="";
      $image_ids  = array();
      foreach($resault as $res) {
        $image_ids[]=$res->image_id;
        $image_content.='<div class="project-cover"><div class="cover-img" style="height:290px">';

        if($res->img_status == 1)
          $vvv = "<div class='potfolio_area nailthumb-container'>New</div>";
        elseif($res->img_status == 2)
          $vvv = "<div class='potfolio_area'>Featured</div>";
        else
          $vvv = "";

        $image_content .= $vvv;
        $caption = get_post_field('post_excerpt', $res->image_id);
        $img_caption=($caption!="")?$caption:"Untitle Caption";
        $src = wp_get_attachment_image_src($res->image_id , 'full');

        $buy_now_link = '';
        $buy_now_price = 0;
        if($res->enable_buy_now == 1)
          $buy_now_link = $res->buy_now_option;
        if($res->buy_now_price != '') {
          $buy_now_price = $res->buy_now_price;
        }

        $image_content.= '<a class="cover-img-link socialGallery" href="'.$src[0].'" image_id="'.$res->image_id.'" project_id="'.$project_id.'"
                         buy_now_link="'.$buy_now_link.'" buy_now_price="'.$buy_now_price.'">';
        //$image_content.=remove_width_and_height_attribute(wp_get_attachment_image( $res->image_id, 'thumbnail' ));
        //$image_content.= '<a class="inline" href="#inline_content'.$res->image_id.'">';

        if($src[2] > 270)
	        $height = '270';
        else
          $height = $src[2];
        if($src[1] > 180)
	        $width = '180';
        else
          $width = $src[1];
        $image_content.='<img src="'.$src[0].'" width="'.$width.'" height="'.$height.'" class="" title="'.htmlspecialchars($img_caption).'"/>';
        $image_content.='</a></div>';
        $image_content.='<div class="cover-info-stats"><div class="cover-info">';
        $image_content.='<div class="portfolio_name">';

        if(get_post_field('post_excerpt', $res->image_id)!="") {
          $pex = get_post_field('post_excerpt', $res->image_id);
          $excerpt = strip_tags($pex);
          //$image_content.= strlen($excerpt);
          //$final_excerpt = substr($excerpt, 0, 90);
          if(strlen($excerpt) > 90) {
            $final_excerpt = display_limited_words_with_formatting($pex, 90);
            $image_content.= $final_excerpt . '..<a href="'.$src[0].'" class="socialGallery" image_id="'.$res->image_id.'" project_id="'.$project_id.'"
                             buy_now_link="'.$buy_now_link.'" buy_now_price="'.$buy_now_price.'">more</a>';
          } else {
            $final_excerpt = display_limited_words_with_formatting($pex, 0);
            $image_content.= '<a href="" class="projectName portfolio_name-link">'.$final_excerpt.'</a>';
          }
          //$image_content.='<a class="projectName portfolio_name-link" >'.get_post_field('post_excerpt', $res->image_id) .'</a> ';
        }

        $image_content.='</div>';
        $image_content.='</div>';
        $image_content.='<div class="cover-stat-fields-wrap">';
        $image_content.='</div>';
        $image_content.='</div>';
        // if($res->enable_buy_now==1){
        $image_content.='<div class="buy_now_stats"><div class="buy_now_btn add_to_my_wishlist">';
        $image_content.='<a class="pop_add_wisl" href="#" image_id="'.$res->image_id.'" project_id="'.$project_id.'" id="add_wishlist_bbn'.$res->image_id.'">Add To Wishlist</a>';
        $image_content.='</div>';

	      if($res->enable_buy_now == 1){
          $image_content.= '<div class="buy_now_btn"><a href="javascript:void(0)"  onclick="window.open(\'http://'.$res->buy_now_option.'\',\'_blank\',\'toolbar=yes, scrollbars=yes, resizable=yes, top=200, left=200, width=1000, height=550\');">';
	        /* Temporarily of the price display - start */
	        /*
	        if($res->buy_now_price != '')
	        {
            $image_content.= '<span class="buy_now_price_span">';
            $image_content.= $res->buy_now_price;
            $image_content.= '</span>';
	        } else {
            $image_content.= '<span class="buy_now_price_span">0</span>';
	        } */
	        /* Temporarily of the price display - end */
          $image_content.= 'Buy Now</a></div>';
	      }
        $image_content.= '</div>';
        $image_content.= '</div>';

      }
      $data['img_content']=$image_content;
      //$data['tag_content']=$tag_string;
      $data['paged']=$paged;
      $data['lpage']=$last_page;
    }

    print json_encode($data);die();
  }


  public function get_post_images_by_tag(){
    global $wpdb;
    $terms_name=$_REQUEST['terms_name'];
    $project_id=$_REQUEST['project_id'];
    $resault= $wpdb->get_results(
    $wpdb->prepare(
      "
      SELECT pi.* FROM ".$wpdb->prefix."portfolio_images pi, ".$wpdb->prefix."posts p, ".$wpdb->prefix."terms t, ".$wpdb->prefix."term_relationships tr
       WHERE t.name=%s AND tr.term_taxonomy_id=t.term_id AND tr.object_id=pi.image_id AND p.ID=pi.image_id AND pi.portfolio_id=%d ORDER BY pi.order_pos ASC
      ",$terms_name,$project_id));


    /*$data['sql']="SELECT pi.* FROM ".$wpdb->prefix."portfolio_images pi, ".$wpdb->prefix."posts p, ".$wpdb->prefix."terms t, ".$wpdb->prefix."term_relationships tr
       WHERE t.name='".$terms_name."' AND tr.term_taxonomy_id=t.term_id AND tr.object_id=pi.image_id AND p.ID=pi.image_id AND portfolio_id='".$project_id."' ORDER BY pi.order_pos ASC";*/

    print json_encode($data);
    die();
  }


  /**
   * Function that adds to wishlist
   */
  public function add_to_my_wishlist(){
    global $wpdb;
    $image_id = $_POST['image_id'];
    $project_id = $_POST['project_id'];

    $user_id = get_current_user_id();
    if(empty($user_id)){
      echo '{"status":"error"}';
      exit;
    }

    if(!empty($image_id) && !empty($project_id)){

      $aa = $wpdb->prepare("SELECT image_id FROM ".$wpdb->prefix."wishlist
                      WHERE image_id=%d AND user_id=%d AND project_id=%d",
                       $image_id, $user_id, $project_id
            );
      $resault= $wpdb->get_results($aa);
      if(count($resault) == 0){

        $ins_q = $wpdb->prepare("INSERT INTO ".$wpdb->prefix."wishlist
                      SET image_id=%d , user_id=%d , project_id=%d",
                       $image_id, $user_id, $project_id
            );
        $wpdb->query($ins_q);

        //send email to author
        $author_id=get_post_field('post_author', $project_id);
        $project_title=get_post_field('post_title', $project_id);
        $to_email=get_the_author_meta( 'user_login', $author_id);
        $to_name=get_the_author_meta( 'display_name', $author_id);
        $from_name=get_the_author_meta( 'display_name', $user_id);
        $from_email=get_the_author_meta( 'user_login', $user_id);
        $subject=$from_name ." Added ".$project_title." into his/her wishlist";
        $message="Hello ".$to_name. "<br/>";
        $message.=$from_name. " Added ".$project_title." into his/her wishlist";
        $headers = 'From: '.$from_name.' <'.$from_email.'>' . '\r\n';
        add_filter( 'wp_mail_content_type', 'set_html_content_type' );
        wp_mail($to_email, $subject, $message, $headers);
      }
      $data['image_id']=$image_id;
      $data['msg']="Added To Wishlist";
    }
    print json_encode($data);
    die();
  }


  /**
   * Add to my wishlist for lightbox
   */
  public function add_to_my_wishlist_lb() {
    global $wpdb;
    $image_id = $_POST['image_id'];
    $project_id = $_POST['project_id'];

    $user_id = get_current_user_id();
    if(empty($user_id)){
      echo '{"status":"error"}';
      exit;
    }

    if(!empty($image_id) && !empty($project_id)){

      $aa = $wpdb->prepare("SELECT image_id FROM ".$wpdb->prefix."wishlist
                      WHERE image_id=%d AND user_id=%d AND project_id=%d",
                       $image_id, $user_id, $project_id
            );
      $resault= $wpdb->get_results($aa);
      if(count($resault) == 0){

        $ins_q = $wpdb->prepare("INSERT INTO ".$wpdb->prefix."wishlist
                      SET image_id=%d , user_id=%d , project_id=%d",
                       $image_id, $user_id, $project_id
            );
        $wpdb->query($ins_q);

        //send email to author
        $author_id=get_post_field('post_author', $project_id);
        $project_title=get_post_field('post_title', $project_id);
        $to_email=get_the_author_meta( 'user_login', $author_id);
        $to_name=get_the_author_meta( 'display_name', $author_id);
        $from_name=get_the_author_meta( 'display_name', $user_id);
        $from_email=get_the_author_meta( 'user_login', $user_id);
        $subject=$from_name ." Added ".$project_title." into his/her wishlist";
        $message="Hello ".$to_name. "<br/>";
        $message.=$from_name. " Added ".$project_title." into his/her wishlist";
        $headers = 'From: '.$from_name.' <'.$from_email.'>' . '\r\n';
        add_filter( 'wp_mail_content_type', 'set_html_content_type' );
        wp_mail($to_email, $subject, $message, $headers);
      }
      $data['image_id']=$image_id;
      $data['msg']="Added To Wishlist";
    }
    print json_encode($data);
    die();
  }


  /**
   * delete from wishlist
   */
  public function delete_from_my_wishlist() {
    global $wpdb;
    $user_id = get_current_user_id();
    $image_id = $_POST['image_id'];
    $project_id = $_POST['project_id'];
    $data = array();

    $sql = "DELETE FROM {$wpdb->prefix}wishlist WHERE user_id=$user_id AND image_id=$image_id AND project_id=$project_id";
    $query = $wpdb->query($sql);
    if($query)
      $data['msg'] = 'ok';
    else
      $data['msg'] = 'error';

    print json_encode($data);exit;
  }


  public function login_for_wishlist(){
    $data['msg'] = "login";
    $project_id = $_POST['project_id'];
    $data['llink'] = get_permalink($project_id)."";
    print json_encode($data);
    die();
  }


  /**
   * Display my wishlist
   */
  public function display_my_wishlist(){
    global $wpdb;
    $user_id = get_current_user_id();
    if(empty($user_id)){
      echo '{"status":"error"}';
      exit;
    }

    $resaults = $wpdb->get_results($wpdb->prepare("select pi.*, wl.project_id from " . $wpdb->prefix . "wishlist wl, ".$wpdb->prefix."posts p, ".$wpdb->prefix."portfolio_images pi where pi.image_id=wl.image_id AND wl.image_id=p.ID and wl.user_id =%d ORDER BY p.post_date DESC ", $user_id));

    $image_content = "";
    //ob_start();

    foreach($resaults as $res){
      $image_content.='<div class="project-cover wishlist-product"><div class="cover-img" style="height:290px">';

      if($res->img_status == 1)
        $vvv = "<div class='potfolio_area'>New</div>";
      elseif($res->img_status==2)
        $vvv = "<div class='potfolio_area'>Featured</div>";
      else
        $vvv ="";
      $image_content .= $vvv;
      $caption = get_post_field('post_excerpt', $res->image_id);

      $img_caption = ($caption!="")? $caption : "Untitle Caption";

      //$src = wp_get_attachment_image_src($res->image_id , 'full');

      $image_content.='<a class="cover-img-link group1" href="'.get_permalink($res->project_id).'"  title="" >';
      //$image_content.=remove_width_and_height_attribute(wp_get_attachment_image( $res->image_id, 'thumbnail' ));

      $src = wp_get_attachment_image_src($res->image_id , 'full');
      if($src[2] > 270)
  	    $height = '270';
      else
        $height = $src[2];
      if($src[1] > 180)
  	    $width = '180';
      else
        $width = $src[1];
      $image_content.='<img src="'.$src[0].'" width="'.$width.'" height="'.$height.'" class="" title="'.htmlspecialchars($img_caption).'"/>';

      $image_content.='</a></div>';
      $image_content.='<div class="cover-info-stats"><div class="cover-info">';
      $image_content.='<div class="portfolio_name">';


      if(get_post_field('post_excerpt', $res->image_id)!="") {
        $pex = get_post_field('post_excerpt', $res->image_id);
        $excerpt = strip_tags($pex);
        if(strlen($excerpt) > 90) {
          $final_excerpt = display_limited_words_with_formatting($pex, 90);
          $image_content.= $final_excerpt . '..<a href="'.$src[0].'" class="socialGallery" image_id="'.$res->image_id.'" project_id="'.$project_id.'" buy_now_link="'.$buy_now_link.'">more</a>';
        } else {
          $final_excerpt = display_limited_words_with_formatting($pex, 0);
          $image_content.= '<a href="" class="projectName portfolio_name-link">'.$final_excerpt.'</a>';
        }

      }

      $image_content.='</div>';
      $image_content.='</div>';

      $image_content.='<div class="cover-stat-fields-wrap">';

      $image_content.='</div>';
      $image_content.='</div>';

      /* if($res->enable_buy_now==1){
        $image_content.='<div class="buy_now_stats">';
        $image_content.='<div class="buy_now_btn add_to_my_wishlist"><a href="#" image_id="'.$res->image_id.'" project_id="'.$project_id.'" id="add_wishlist_bbn'.$res->image_id.'">Remove from Wishlist<a>';
        $image_content.='</div>';
        $image_content.='<div class="buy_now_btn"><a href="javascript:void(0)"  onclick="window.open(\''.$res->buy_now_option.'\',\'_blank\',\'toolbar=yes, scrollbars=yes, resizable=yes, top=200, left=200, width=1000, height=550\');">';
  	    if($res->buy_now_price!='') {
          $image_content.='<span class="buy_now_price_span">';
          $image_content.=$res->buy_now_price;
          $image_content.='</span>';
  	    } else {
          $image_content.='<span class="buy_now_price_span">0</span>';
  	    }
          $image_content.='Buy Now</a></div>';
          $image_content.='</div>';
      } */

      // add "remove from wishlist button"
      $image_content.= '<div class="buy_now_stats">';
      $image_content.= '<div class="buy_now_btn add_to_my_wishlist">';
      $image_content.= '<a class="remove_from_wl" image_id="' . $res->image_id . '" project_id="' . $res->project_id . '" id="add_wishlist_bbn' . $res->image_id . '">';
      $image_content.= 'Remove from Wishlist</a>';
      $image_content.= '</div>';

      // add buy now button if buy now option is there
      if($res->buy_now_price!='') {
        if($res->buy_now_price!='')
          $bn_price = $res->buy_now_price;
        else
          $bn_price = 0;
        $image_content.= '<div class="buy_now_btn">';
        $image_content.= '<a href="javascript:void(0)" onclick="">';
        $image_content.= '<span class="buy_now_price_span">' . $bn_price . '</span>Buy Now';
        $image_content.= '</a>';
        $image_content.= '</div>';
      }

      $image_content.= '</div>';

      $image_content.='</div>';
    }

    $data['content'] = $image_content;
    //ob_end_clean();
    print json_encode($data);
    die();
  }


}
new DisplayProjects;

function set_html_content_type() {

  return 'text/html';
}
?>
