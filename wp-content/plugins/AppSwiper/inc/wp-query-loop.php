<?php

/* Normal query loop for carousel */

	?>
	<div class="swiper-wrapper">

	<?php

	$args = array(
		'post_type' => $type,
		'posts_per_page' => $number,
		'category_name' => $category
	);
	$carousel_query = new WP_Query( $args );

	// The Loop
	if ( $carousel_query->have_posts() ) : while ( $carousel_query->have_posts() ) : $carousel_query->the_post();

	?>

	<div class="swiper-slide">

	<?php if ( has_post_thumbnail() ) : ?><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
	<?php endif; ?>

	<div class="swiper-slide-content">

	<a href="<?php the_permalink(); ?>"><?php the_title('<h2>','</h2>'); ?></a>

	<?php the_excerpt(); ?>

	<!-- This stuff should all be hooked in, so someone can add their own loop content -->

	</div>
	</div>

	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
	<?php endif; ?>

	</div><!-- /.swiper-wrapper -->