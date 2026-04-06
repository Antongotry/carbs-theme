<?php
/**
 * The template for displaying all single posts.
 *
 * @package storefront
 */

get_header(); ?>

	

<?php
while ( have_posts() ) :
	the_post(); ?>

	<section class="clause-page__top">
		<div class="container">
			<div class="clause-page__wrap">
			<p class="clause-page__date"><?php echo get_the_date('d.m.Y'); ?></p>

				<!--<div class="clause-page__socials">-->
				<!--	<a href="#" class="clause-page__social-link">-->
				<!--		<svg-->
				<!--			width="19"-->
				<!--			height="17"-->
				<!--			viewBox="0 0 19 17"-->
				<!--			fill="none"-->
				<!--			xmlns="http://www.w3.org/2000/svg"-->
				<!--		>-->
				<!--			<path-->
				<!--				fill-rule="evenodd"-->
				<!--				clip-rule="evenodd"-->
				<!--				d="M5.98742 0H0L7.09892 9.24481L0.454133 17H3.52422L8.54994 11.1345L13.0126 16.946H19L11.6948 7.43257L11.7077 7.44898L17.9976 0.107956H14.9275L10.2565 5.55954L5.98742 0ZM3.30496 1.61906H5.16893L15.695 15.327H13.8311L3.30496 1.61906Z"-->
				<!--				fill="white"-->
				<!--			/>-->
				<!--		</svg>-->
				<!--	</a>-->
				<!--	<a href="#" class="clause-page__social-link">-->
				<!--		<svg-->
				<!--			width="19"-->
				<!--			height="19"-->
				<!--			viewBox="0 0 19 19"-->
				<!--			fill="none"-->
				<!--			xmlns="http://www.w3.org/2000/svg"-->
				<!--		>-->
				<!--			<path-->
				<!--				d="M9.5 0C4.25315 0 0 4.27517 0 9.54918C0 14.3362 3.50835 18.2895 8.07975 18.9799V12.0797H5.72945V9.56923H8.07975V7.89908C8.07975 5.13364 9.4202 3.91994 11.7069 3.91994C12.8022 3.91994 13.3808 4.00111 13.6553 4.03835V6.22893H12.0954C11.1245 6.22893 10.7853 7.15424 10.7853 8.19701V9.56923H13.6306L13.2449 12.0797H10.7863V19C15.4233 18.3688 19 14.383 19 9.54918C19 4.27517 14.7469 0 9.5 0Z"-->
				<!--				fill="white"-->
				<!--			/>-->
				<!--		</svg>-->
				<!--	</a>-->
				<!--</div>-->
			</div>
			<h2 class="clause-page__title">
				<?php the_title(); ?>
			</h2>
			<div class="clause-page__content container">
				<!--<p><?php echo get_the_excerpt(); ?></p>-->

			</div>
		</div>
	</section>

	<section class="clause-page__content container">
		<?php  the_content(); ?>
	</section>

    

	<section class="clause-page__articles">
        <div class="container">
            <h2 class="title">Читайте також</h2>
            <!-- Swiper -->
            <div class="swiper clause-page__slider">
                <div class="swiper-wrapper">
                    <?php
                    $recent_posts = new WP_Query(array(
                        'posts_per_page' => 4,
                        'post_status' => 'publish'
                    ));
                    if ($recent_posts->have_posts()) :
                        while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                            <div class="swiper-slide">
                                <div class="clause__item clause-card">
                                    <a href="<?php the_permalink(); ?>" class="blog-card__image">
                                        <picture>
                                            <source media="(min-width: 1024px)" srcset="<?php the_post_thumbnail_url('large'); ?>" />
                                            <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title_attribute(); ?>" width="355" height="355" />
                                        </picture>
                                    </a>
                                    <div class="clause-card__text">
                                        <time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date('d.m.Y'); ?></time>
                                        <a href="<?php the_permalink(); ?>">
                                            <h3 class="subtitle"><?php the_title(); ?></h3>
                                        </a>
                                        <div class="clause-card__category">
                                            <?php the_category(', '); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata();
                    endif; ?>
                </div>
                <!-- If we need navigation buttons -->
            </div>
            <div class="clause-page__button clause-page__button--prev">
                <svg width="11" height="21" viewBox="0 0 11 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 1L1 10.5L10 20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="clause-page__button clause-page__button--next">
                <svg width="11" height="21" viewBox="0 0 11 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 20.002L10 10.502L1 1.00195" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>
    </section>




<?php endwhile; // End of the loop. ?>

		

<?php
do_action( 'storefront_sidebar' );
get_footer();
