<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package AppPresser Theme
 */

get_header();
appp_title_header(); ?>

<div id="content" class="site-content" role="main">

<<<<<<< HEAD
	<?php while ( have_posts() ) : the_post(); ?>
=======
	<?php //while ( have_posts() ) : the_post(); ?>
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20

		<?php //get_template_part( 'content', 'page' ); ?>

		<?php
			// If comments are open or we have at least one comment, load up the comment template
			//if ( comments_open() || '0' != get_comments_number() )
				//comments_template();
		?>
<<<<<<< HEAD
		<?php
		if(is_home() || is_front_page()) {
		?>
		<input type="hidden" name="limit" id="page_no" value="2" />
    <input type="hidden" name="last_page" id="last_page" value="" />
		<div class="container">
        <div class="scroll-content"></div>
        <div class="ajax-spinner" style="display: none;"><i class="fa fa-spinner fa-spin fa-3x"></i></div>
    </div>
    <?php }?>

	<?php endwhile; // end of the loop. ?>
=======

	<?php //endwhile; // end of the loop. ?>
>>>>>>> 82cd3fc7d9304a6e84e7c4e399d355ffda47cd20

</div><!-- #content -->

<?php get_footer(); ?>

