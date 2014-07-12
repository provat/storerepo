<?php
class myproject_list{
    
    public function __construct(){
        add_action( 'init', array( &$this, 'init' ) );
       

       
    }
    
    
     public function init(){
       
        add_shortcode( 'my_project_list', array( &$this,'get_my_project_list') );  
        //add_action("wp_ajax_add_media_image_for_portfolio", array( &$this,'add_media_image_for_portfolio'));
        
    }
    
    public function get_my_project_list(){
        $publish_project="";
        if($publish_project=="")
            $this->show_no_project();
      }
      
      
      public function show_no_project(){
         ?>
<div class="portfolio">
          <h4>Add Projects to Your Portfolio</h4>

          <p>Projects added to your portfolio (and made public) are submitted to the gallery and eligible to be "featured".</p>
          
          <a href="<?php bloginfo('url') ?>/portfolio/editor" class="btn_bg">Upload My First Project</a>
</div>
       <?php   
      }
    
}
new myproject_list;