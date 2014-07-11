<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package AppPresser Theme
 */
?>

	</div><!-- #main -->
	<?php //if ( has_nav_menu( 'footer-menu' ) ) : ?>
	<footer id="colophon" class="site-footer" role="contentinfo">

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

	</footer><!-- #colophon -->
	<?php //endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
</div><!-- #body-container -->
</body>
</html>