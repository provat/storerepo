<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

  <div class="mid_leftarea">
        	<div class="box_mid">
            	<div class="box_left"></div>
                <div class="box_right"></div>
                <div class="category_list">
                	<h3>categories</h3>
                    <ul class="category_list">
                        <li><a href="#">Lorem Ipsum is simply</a></li>
                        <li><a href="#">Lorem Ipsum is  dummy</a></li>
                        <li><a href="#">Lorem Ipsum is simply Lorem Ipsum is simply</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mid_rightarea">
        	<div class="box_mid">
            	<div class="box_left"></div>
                <div class="box_right"></div>
                <div>
                	<?php while ( have_posts() ) : the_post();  ?>   
                        <?php the_content(); ?>
                        <?php endwhile; ?>
                <div class="clear"></div>
                </div>
            </div>
        </div>

<?php get_footer(); ?>