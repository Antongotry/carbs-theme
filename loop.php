<?php
/**
 * The loop template file.
 *
 * Included on pages like index.php, archive.php and search.php to display a loop of posts
 * Learn more: https://codex.wordpress.org/The_Loop
 *
 * @package storefront
 */

do_action( 'storefront_loop_before' ); ?>

<section class="clause-main">
    <div class="container">
        <h2 class="title">Корисні статті про батьківство</h2>

        <ul class="clause-main__list">

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<li class="clause-main__item clause-card">
					<a href="<?php the_permalink(); ?>" class="clause-card__image">
						<picture>
							<?php
							$image_id = get_post_thumbnail_id();
							$image_srcset = wp_get_attachment_image_srcset( $image_id, 'large' );
							?>
							<source media="(min-width: 1024px)" srcset="<?php echo $image_srcset; ?>" />
							<?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title(), 'width' => 355, 'height' => 355 ) ); ?>
						</picture>
					</a>
					<div class="clause-card__text">
						<time datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
						<a href="<?php the_permalink(); ?>">
							<h3 class="subtitle"><?php the_title(); ?></h3>
						</a>
						<div class="clause-card__category">
							<?php
							$categories = get_the_category();
							if ( ! empty( $categories ) ) {
								echo esc_html( $categories[0]->name );
							}
							?>
						</div>
					</div>
				</li>
			<?php endwhile; else: ?>
				<p><?php esc_html_e( 'No posts found.', 'textdomain' ); ?></p>
			<?php endif; ?>
					
		</ul>
		
    </div>
</section>



