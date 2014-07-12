<?php

global $post;
global $more;

/* WP query loop for swiper slider */

$args = array(
	'post_type' => 'swiper',
	'posts_per_page' => $number,
	'category_name' => $category
);
$swiper_query = new WP_Query( $args );

// The Loop
if ( $swiper_query->have_posts() ) : while ( $swiper_query->have_posts() ) : $swiper_query->the_post();

$more = 0;

$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'phone' );
$full_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'tablet' );

?>

<div class="swiper-slide">

	<?php if ( has_post_thumbnail() ) : ?>
		<span data-picture data-alt="Slider image">
		    <span data-src="<?php echo $thumb_url[0]; ?>"></span>
		    <span data-src="<?php echo $full_url[0]; ?>" data-media="(min-width: 500px)"></span>
		    <noscript>
		        <img src="<?php echo $thumb_url[0]; ?>" alt="<?php esc_attr_e('Slider image', 'apppresser-swipers'); ?>">
		    </noscript>
		</span>
	<?php endif; ?>

	<?php if ( get_the_title() || the_content() ) : ?>
	<div class="swiper-slide-content">
	<?php if ( get_the_title() ) : ?><a href="<?php the_permalink(); ?>"><?php the_title('<h2>','</h2>'); ?></a><?php endif; ?>

	<?php the_content(''); ?>

	<?php // This stuff should all be hooked in, so someone can add their own loop content ?>
	</div>
	<?php endif; ?>
</div>

<?php endwhile; ?>
<?php wp_reset_postdata(); ?>
<?php endif; ?>

</div><!-- /.swiper-wrapper -->