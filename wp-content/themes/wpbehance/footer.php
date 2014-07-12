<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

		<div class="clear"></div>
    </div>
</div>

<!--========================Middle End=====================-->
<!--========================Footer Start=====================-->
    <div class="footer_wrapper">
    <div class="footer_border"></div>
    <div class="footer_bg">
        <div class="main_wrap">
            <div class="footerLink">
                <?php
                $menu_args = array(
                  'menu'            => 'Footer Menu',
                  'container'       => '',
                  'menu_class'      => 'footer-pop',
                  'menu_id'         => '',
                  'echo'            => true,
                  'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                  'depth'           => 0,
                  'walker'          => ''
                );
                wp_nav_menu( $menu_args );
                ?>
            </div>
            <div class="copyrightText">
              <p align="right" class="copy_text"> &nbsp;&nbsp; &copy; 2014 Copyright Everystore</p>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    </div>
   
<!--========================Footer End=====================-->

	<?php wp_footer(); ?>
</body>
</html>
