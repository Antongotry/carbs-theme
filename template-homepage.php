<?php
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Homepage
 *
 * @package storefront
 */

get_header(); 

// Функция вызова категории в табах
function render_product_slider($category_slug, $tab_id, $gallery_class, $prev_btn_class, $next_btn_class) {
    $exclude_slugs = array('bez-katehorii', 'na-vydalennia', 'novi-pozytsii');

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 10,
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category_slug,
            ),
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $exclude_slugs,
                'operator' => 'NOT IN',
            ),
        ),
    );

    // WP Query
    $loop = new WP_Query($args);

    if ($loop->have_posts()) :
?>
<article class="new-slider" id="<?php echo esc_attr($tab_id); ?>">
	<div class="new-slider__gallary <?php echo esc_attr($gallery_class); ?>">
		<div class="new-slider__wrapper swiper-wrapper">
			<?php
	// Loop through products
	while ($loop->have_posts()) : $loop->the_post();
	global $product;

	// Get product data
	$product_id = $product->get_id();
	$product_title = $product->get_title();
	$product_permalink = get_permalink($product_id);
	$product_thumbnail = get_the_post_thumbnail_url($product_id, 'full');
	$product_price = $product->get_price_html();
	$regular_price = $product->get_regular_price();
	$sale_price = $product->get_sale_price();
	$classAv = $product->is_in_stock() ? 'tag-available' : 'tag-order';
	$textAv = $product->is_in_stock() ? 'В наявності' : 'Під замовлення';
			?>
			<section class="swiper-slide">
				<a href="<?php echo esc_url($product_permalink); ?>" class="new-slider__image">
					<img src="<?php echo esc_url($product_thumbnail); ?>" alt="<?php echo esc_attr($product_title); ?>" />
					<div class="new-slider__icons">
						<div class="new-slider__icon-right">
							<?php if ($product->is_on_sale()) : ?>
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/percentage.png" alt="percentage" loading="lazy" />
							<?php endif; ?>
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/paw.png" alt="paw" loading="lazy" />
						</div>
					</div>
				</a>
				<div class="new-slider__footer-card">
					<div class="new-slider__tag <?php echo esc_attr($classAv); ?>">
						<span><?php echo esc_html($textAv); ?></span>
					</div>
					<div class="new-slider__mid">
						<h3 class="new-slider__title">
							<a href="<?php echo esc_url($product_permalink); ?>"><?php echo esc_html($product_title); ?></a>
						</h3>
						<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="btn-icon-style add_to_cart_button ajax_add_to_cart new-slider__bag" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="<?php echo esc_attr( $product->add_to_cart_text() ); ?>" rel="nofollow">
                            <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</a>
					</div>
					<div class="new-slider__bottom-card">
						<div class="new-slider__prices">
							<?php if ( $product->is_type( 'variable' ) ) {
				// Get the available variations
				$available_variations = $product->get_available_variations();
				$variation_prices = array();

				foreach ( $available_variations as $variation ) {
					$variation_obj = new WC_Product_Variation( $variation['variation_id'] );
					$variation_prices[] = $variation_obj->get_price();
				}

				// Get the minimum and maximum prices from the variations
				if ( !empty( $variation_prices ) ) {
					$min_price = min( $variation_prices );
					$max_price = max( $variation_prices );
				}

				// Get the minimum and maximum regular prices from the variations
				$variation_regular_prices = array_map( function( $variation ) {
					$variation_obj = new WC_Product_Variation( $variation['variation_id'] );
					return $variation_obj->get_regular_price();
				}, $available_variations );

				if ( !empty( $variation_regular_prices ) ) {
					$min_regular_price = min( $variation_regular_prices );
					$max_regular_price = max( $variation_regular_prices );
				}

				if ( isset( $min_price ) && $min_price !== $min_regular_price ) {
					// Show sale price range
							?>
							<div class="new-slider__current-price"><?php echo wc_price( $min_price ); ?></div>
							<div class="new-slider__old-price"><?php echo wc_price( $min_regular_price ); ?> </div>
							<?php
				} elseif ( isset( $min_price ) ) {
					// Show regular price range
							?>
							<div class="new-slider__current-price"><?php echo wc_price( $min_price ); ?> - <?php echo wc_price( $max_price ); ?></div>
							<?php
				}
			} else {
				// For simple products
				if ( $product->get_sale_price() ) {
							?>
							<div class="new-slider__current-price"><?php echo wc_price( $product->get_sale_price() ); ?></div>
							<div class="new-slider__old-price"><?php echo wc_price( $product->get_regular_price() ); ?></div>
							<?php
				} else {
							?>
							<div class="new-slider__current-price"><?php echo wc_price( $product->get_price() ); ?></div>
							<?php
				}
			} ?>
						</div>
<!--						<a href="--><?php //echo esc_url( $product->add_to_cart_url() ); ?><!--" class="btn-black add_to_cart_button ajax_add_to_cart" data-product_id="--><?php //echo esc_attr( $product->get_id() ); ?><!--" data-product_sku="--><?php //echo esc_attr( $product->get_sku() ); ?><!--" aria-label="--><?php //echo esc_attr( $product->add_to_cart_text() ); ?><!--" rel="nofollow">-->
<!--                            <svg class="bag"><use xlink:href="#bag"></use></svg>-->
<!--                        </a>-->
					</div>
				</div>
			</section>
			<?php endwhile; ?>
		</div>
	</div>
	<div class="new-slider__buttons">
		<div class="<?php echo esc_attr($prev_btn_class); ?> btn-prev btn-swiper">
			<svg class="arrow"><use xlink:href="#arrow-prev"></use></svg>
			<svg class="arrow-mob">
				<use xlink:href="#arrow-prev-mob"></use>
			</svg>
		</div>
		<div class="<?php echo esc_attr($next_btn_class); ?> btn-next btn-swiper">
			<svg class="arrow"><use xlink:href="#arrow-next"></use></svg>
			<svg class="arrow-mob">
				<use xlink:href="#arrow-next-mob"></use>
			</svg>
		</div>
	</div>
</article>
<?php
	endif;

	wp_reset_query();
}



?>
<!-- category -->
<div class="category container">
	<div class="category__top">
		<div class="category__title">
			<h2><?php _e('Категорії', 'crabs_project') ?></h2>
		</div>
		<div class="category__info">
			<div class="category__text">
				<span>
					<?php the_field('black_text') ?>
				</span>
			</div>
			<div class="category__all">
				<a href="https://www.crabs.ua/shop/">
					<span><?php _e('Всі категорії', 'crubs_project') ?></span>
					<svg
						 width="18"
						 height="18"
						 viewBox="0 0 18 18"
						 fill="none"
						 xmlns="http://www.w3.org/2000/svg"
						 >
						<path
							  d="M1 1H17M17 1V17M17 1L1 17"
							  stroke="#E93A53"
							  stroke-width="2"
							  stroke-linecap="round"
							  stroke-linejoin="round"
							  />
					</svg>
				</a>
			</div>
		</div>
	</div>

</div>

<div class="categories-horizontal-scroll-wrapper">
    <?php
    if (function_exists('my_theme_render_categories')) {
        my_theme_render_categories('homepage');
    }
    ?>
</div>
<!-- category -->

<!-- fs-slider -->
<!-- fs-slider -->
<div class="container fs-slider">
  <div class="main-slider-wrapper">
    <div class="swiper main-slider-v1">
      <div class="swiper-wrapper">
        <?php
        $page_id = 22453;

        for ($i = 1; $i <= 6; $i++):
          $slide_v1_link = get_field("slide_{$i}_link", $page_id);
          $slide_v1_background_image = get_field("slide_{$i}_background_image", $page_id);
          $slide_v1_background_image_mob = get_field("slide_{$i}_background_image_mob", $page_id);
          $slide_v1_background_image_note = get_field("slide_{$i}_background_image_note", $page_id);
          $slide_v1_content = get_field("slide_{$i}_content", $page_id);
          $slide_numb = "slide-n-{$i}";

          $slide_link = $slide_v1_link ? $slide_v1_link : get_permalink( wc_get_page_id('shop') );
        ?>

        <?php if ($slide_v1_background_image): ?>
          <div class="swiper-slide <?php echo esc_attr($slide_numb); ?>"
            data-note="<?php echo esc_url($slide_v1_background_image_note); ?>"
            data-mob="<?php echo esc_url($slide_v1_background_image_mob); ?>">

            <a href="<?php echo esc_url($slide_v1_link ?: '/shop'); ?>" 
               class="slide-link" 
               aria-label="Переглянути каталог">
            
              <picture>
                <?php if ($slide_v1_background_image_mob) : ?>
                  <source 
                    media="(max-width: 600px)" 
                    srcset="<?php echo esc_url($slide_v1_background_image_mob); ?>">
                <?php endif; ?>
            
                <?php if ($slide_v1_background_image_note) : ?>
                  <source 
                    media="(max-width: 1024px)" 
                    srcset="<?php echo esc_url($slide_v1_background_image_note); ?>">
                <?php endif; ?>
            
                <img 
                  class="slide-bg-img"
                  src="<?php echo esc_url($slide_v1_background_image); ?>" 
                  alt="<?php echo esc_attr(strip_tags($slide_v1_content)); ?>"
                  data-no-lazy="1"
                >
              </picture>
            
              <div class="content-html">
                <?php echo wp_kses_post($slide_v1_content); ?>
              </div>
            </a>



          </div>
        <?php endif; ?>

        <?php endfor; ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
</div>
<!-- fs-slider -->

<!-- fs-slider -->



<!-- submain-picture -->
<!--<div class="submain-picture">-->
<!--	<picture>-->
<!--		<source-->
<!--				srcset="<?php echo esc_url(get_field('banner1_phone')); ?>"-->
<!--				media="(max-width: 774.98px)"-->
<!--				/>-->
<!--		<img src="<?php echo esc_url(get_field('banner1_desctop')); ?>" alt="background" loading="lazy" />-->
<!--	</picture>-->
<!--</div>-->
<!-- submain-picture -->

<!-- Popular -->
<div class="popular">
	<div class="container">
		<h2>Популярні товари</h2>

		<div class="popular__body">
			<div class="popular__content">
				<div class="popular__card">
					<a href="<?php the_field('link1') ?>"><h3><?php the_field('slide_title1') ?></h3></a>
					<div class="popular__price"><?php the_field('price1') ?></div>
					<a href="<?php the_field('link1') ?>" class="btn-black">Купити зараз</a>
					<div class="popular__rating">
						<div class="popular__stars">
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
						</div>
						<span>5.0</span>
					</div>
				</div>
			</div>
			<div class="popular__content">
				<div class="popular__card">
					<a href="<?php the_field('link2') ?>"><h3><?php the_field('slide_title2') ?></h3></a>
					<div class="popular__price"><?php the_field('price2') ?></div>
					<a href="<?php the_field('link2') ?>" class="btn-black">Купити зараз</a>
					<div class="popular__rating">
						<div class="popular__stars">
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
						</div>
						<span>5.0</span>
					</div>
				</div>
			</div>
			<div class="popular__content">
				<div class="popular__card">
					<a href="<?php the_field('link3') ?>"><h3><?php the_field('slide_title3') ?></h3></a>
					<div class="popular__price"><?php the_field('price3') ?></div>
					<a href="<?php the_field('link3') ?>" class="btn-black">Купити зараз</a>
					<div class="popular__rating">
						<div class="popular__stars">
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
						</div>
						<span>5.0</span>
					</div>
				</div>
			</div>
			<div class="popular__content">
				<div class="popular__card">
					<a href="<?php the_field('link4') ?>"><h3><?php the_field('slide_title4') ?></h3></a>
					<div class="popular__price"><?php the_field('price4') ?></div>
					<a href="<?php the_field('link4') ?>" class="btn-black">Купити зараз</a>
					<div class="popular__rating">
						<div class="popular__stars">
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
						</div>
						<span>5.0</span>
					</div>
				</div>
			</div>
			<div class="popular__content">
				<div class="popular__card">
					<a href="<?php the_field('link5') ?>"><h3><?php the_field('slide_title5') ?></h3></a>
					<div class="popular__price"><?php the_field('price5') ?></div>
					<a href="<?php the_field('link5') ?>" class="btn-black">Купити зараз</a>
					<div class="popular__rating">
						<div class="popular__stars">
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
						</div>
						<span>5.0</span>
					</div>
				</div>
			</div>
			<div class="popular__content">
				<div class="popular__card">
					<a href="<?php the_field('link6') ?>"><h3><?php the_field('slide_title6') ?></h3></a>
					<div class="popular__price"><?php the_field('price6') ?></div>
					<a href="<?php the_field('link6') ?>" class="btn-black">Купити зараз</a>
					<div class="popular__rating">
						<div class="popular__stars">
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
							<svg class="star"><use xlink:href="#star"></use></svg>
						</div>
						<span>5.0</span>
					</div>
				</div>
			</div>
		</div>

		<article class="popular-slider">
			<div class="popular-slider__gallary">
				<div class="popular-slider__wrapper swiper-wrapper">
					<section class="swiper-slide">
						<a href="<?php the_field('link1') ?>"><img src="<?php echo esc_url( get_field('img_slide1') ); ?>" alt="slide-photo"/></a>
					</section>
					<section class="swiper-slide">
						<a href="<?php the_field('link2') ?>"><img src="<?php echo esc_url( get_field('img_slide2') ); ?>" alt="slide-photo"/></a>
					</section>
					<section class="swiper-slide">
						<a href="<?php the_field('link3') ?>"><img src="<?php echo esc_url( get_field('img_slide3') ); ?>" alt="slide-photo"/></a>
					</section>
					<section class="swiper-slide">
						<a href="<?php the_field('link4') ?>"><img src="<?php echo esc_url( get_field('img_slide4') ); ?>" alt="slide-photo"/></a>
					</section>
					<section class="swiper-slide">
						<a href="<?php the_field('link5') ?>"><img src="<?php echo esc_url( get_field('img_slide5') ); ?>" alt="slide-photo"/></a>
					</section>
					<section class="swiper-slide">
						<a href="<?php the_field('link6') ?>"><img src="<?php echo esc_url( get_field('img_slide6') ); ?>" alt="slide-photo"/></a>
					</section>
				</div>
			</div>
			<div class="popular-slider__buttons">
				<div class="popular-btn-prev btn-swiper">
					<picture>
						<source
								srcset="<?php echo get_stylesheet_directory_uri(); ?>/img/popular-arrow-prev.svg"
								media="(max-width: 1023.98px)"
								/>
						<source
								srcset="<?php echo get_stylesheet_directory_uri(); ?>/img/popular-arrow-prev_x2.svg"
								media="(max-width: 1439.98px)"
								/>
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/popular-arrow-prev_x3.svg" alt="main-picture" loading="lazy"/>
					</picture>
				</div>
				<div class="popular-btn-next btn-swiper">
					<picture>
						<source
								srcset="<?php echo get_stylesheet_directory_uri(); ?>/img/popular-arrow-next.svg"
								media="(max-width: 1023.98px)"
								/>
						<source
								srcset="<?php echo get_stylesheet_directory_uri(); ?>/img/popular-arrow-next_x2.svg"
								media="(max-width: 1439.98px)"
								/>
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/popular-arrow-next_x3.svg" alt="main-picture" loading="lazy"/>
					</picture>
				</div>
			</div>
		</article>

	</div>
</div>
<!-- Popular -->

<!-- New -->
<div class="new container">
	<div class="new__title">Новинки в асортименті</div>

	<nav class="new__nav">
		<div class="new__tabs">
			<div class="select-body">
				<div class="select">
					<span class="select-current">Коляски Cybex</span>
					<svg
						 width="13"
						 height="11"
						 viewBox="0 0 13 11"
						 fill="none"
						 xmlns="http://www.w3.org/2000/svg"
						 >
						<path
							  d="M12.624 0.0621777L6.56185 10.5622L0.499668 0.062178L12.624 0.0621777Z"
							  fill="#E93A53"
							  />
					</svg>
				</div>
				<div class="option-body">
					<a href="#newTab1" class="option">Коляски Cybex</a>
					<a href="#newTab2" class="option">Автокрісла Cybex</a>
					<a href="#newTab3" class="option">Аксесуари Cybex</a>
					<a href="#newTab4" class="option">Люльки Cybex</a>
				</div>


			</div>
		</div>


		<a href="/product-category/novi-pozytsii/" class="btn-black">Переглянути всі новинки</a>


	</nav>

	<!-- Tab 1 Container -->
	<?php render_product_slider('koliasky-cybex', 'newTab1', 'gallary-t1', 'btn-t1-prev', 'btn-t1-next'); ?>
	<!-- Tab 1 Container -->

	<!-- Tab 2 Container -->
	<?php render_product_slider('avtokrisla-cybex', 'newTab2', 'gallary-t2', 'btn-t2-prev', 'btn-t2-next'); ?>
	<!-- Tab 2 Container -->

	<!-- Tab 3 Container -->
	<?php render_product_slider('aksesuary-cybex', 'newTab3', 'gallary-t3', 'btn-t3-prev', 'btn-t3-next'); ?>
	<!-- Tab 3 Container -->

	<!-- Tab 4 Container -->
	<?php render_product_slider('liulky-cybex', 'newTab4', 'gallary-t4', 'btn-t4-prev', 'btn-t4-next'); ?>
	<!-- Tab 4 Container -->

	<div class="new__btn">
		<a href="/product-category/novi-pozytsii/" class="btn-black">Переглянути всі новинки</a>
	</div>

</div>
<!-- New -->

<!-- container -->
<div class="container">
	<div class="set">
		<div class="set__column">
			<h2 class="set__title"><?php the_field('title_section6') ?></h2>
			<p class="set__subtitle"><?php the_field('subtitle_section6') ?></p>
			<a href="/konstruktor/"><?php _e('Зібрати комплект', 'crubs_project') ?></a>
		</div>
		<div class="set__image">
			<picture>
				<source
						srcset="<?php echo esc_url(get_field('image_phone_section6')); ?>"
						media="(max-width: 1023.98px)"
						/>
				<img src="<?php echo esc_url(get_field('image_desk_section6')); ?>" alt="main-picture" loading="lazy"/>
			</picture>
		</div>

	</div>
</div>
<!-- container -->

<!-- reservation -->
<div class="reservation container">
	<div class="reservation__top">
		<h2><?php the_field('title_section7') ?></h2>
		<div class="reservation__info">
			<div class="reservation__text">
				<?php the_field('subtitle_section7') ?>
			</div>
			<div class="reservation__all">
				<a href="https://www.crabs.ua/shop/">
					<span><?php _e('Всі товари', 'crubs_project') ?></span>
					<svg
						 width="18"
						 height="18"
						 viewBox="0 0 18 18"
						 fill="none"
						 xmlns="http://www.w3.org/2000/svg"
						 >
						<path
							  d="M1 1H17M17 1V17M17 1L1 17"
							  stroke="#E93A53"
							  stroke-width="2"
							  stroke-linecap="round"
							  stroke-linejoin="round"
							  />
					</svg>
				</a>
			</div>
		</div>
	</div>
	<article class="reservation-slider">
		<div class="reservation-slider__gallary">
			<div class="reservation-slider__wrapper swiper-wrapper">
				<?php
	$args = array(
	'post_type' => 'product',
	'posts_per_page' => 5,
	'orderby' => 'date',
	'order' => 'DESC',
	'meta_query' => array(
		array(
			'key' => '_stock_status',
			'value' => 'instock',
			'compare' => '='
		)
	)
);

			$loop = new WP_Query($args);

			while ($loop->have_posts()) : $loop->the_post();
			global $product;

			$product_id = $product->get_id();
			$product_title = $product->get_title();
			$product_permalink = get_permalink($product_id);
			$product_thumbnail = get_the_post_thumbnail_url($product_id, 'full');
			$product_price = $product->get_price_html();
			$regular_price = $product->get_regular_price();
			$sale_price = $product->get_sale_price();
			$classAv = $product->is_in_stock() ? 'tag-available' : 'tag-order';
			$textAv = $product->is_in_stock() ? 'В наявності' : 'Під замовлення';
				?>
				<section class="swiper-slide">
					<a href="<?php echo esc_url($product_permalink); ?>" class="new-slider__image">
						<img src="<?php echo esc_url($product_thumbnail); ?>" alt="<?php echo esc_attr($product_title); ?>" loading="lazy"/>
						<div class="new-slider__icons">
							<div class="new-slider__icon-right">
								<?php if ($product->is_on_sale()) : ?>
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/percentage.png" alt="percentage" loading="lazy" />
								<?php endif; ?>
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/percentage.png" alt="percentage" loading="lazy"/>
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/paw.png" alt="paw" loading="lazy"/>
							</div>
						</div>
					</a>
					<div class="new-slider__footer-card">

						<div class="new-slider__tag <?php echo esc_attr($classAv); ?>">
							<span><?php echo esc_html($textAv); ?></span>
						</div>

						<div class="new-slider__mid">
							<h3 class="new-slider__title">
								<a href="<?php echo esc_url($product_permalink); ?>"><?php echo esc_html($product_title); ?></a>
							</h3>

							<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="btn-icon-style add_to_cart_button ajax_add_to_cart new-slider__bag" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="<?php echo esc_attr( $product->add_to_cart_text() ); ?>" rel="nofollow">

                                <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
							</a>



						</div>
						<div class="new-slider__bottom-card">
							<div class="new-slider__prices">
								<?php if ( $product->is_type( 'variable' ) ) {
	// Get the available variations
	$available_variations = $product->get_available_variations();
	$variation_prices = array();

	foreach ( $available_variations as $variation ) {
		$variation_obj = new WC_Product_Variation( $variation['variation_id'] );
		$variation_prices[] = $variation_obj->get_price();
	}

	// Get the minimum and maximum prices from the variations
	$min_price = min( $variation_prices );
	$max_price = max( $variation_prices );

	// Get the minimum and maximum regular prices from the variations
	$variation_regular_prices = array_map( function( $variation ) {
		$variation_obj = new WC_Product_Variation( $variation['variation_id'] );
		return $variation_obj->get_regular_price();
	}, $available_variations );

	$min_regular_price = min( $variation_regular_prices );
	$max_regular_price = max( $variation_regular_prices );

	if ( $min_price !== $min_regular_price ) {
		// Show sale price range
								?>
								<div class="new-slider__current-price"><?php echo wc_price( $min_price ); ?></div>
								<div class="new-slider__old-price"><?php echo wc_price( $min_regular_price ); ?> </div>
								<?php
	} else {
		// Show regular price range
								?>
								<div class="new-slider__current-price"><?php echo wc_price( $min_price ); ?> - <?php echo wc_price( $max_price ); ?></div>
								<?php
	}
} else {
	// For simple products
	if ( $product->get_sale_price() ) {
								?>
								<div class="new-slider__current-price"><?php echo wc_price( $product->get_sale_price() ); ?></div>
								<div class="new-slider__old-price"><?php echo wc_price( $product->get_regular_price() ); ?></div>
								<?php
	} else {
								?>
								<div class="new-slider__current-price"><?php echo wc_price( $product->get_price() ); ?></div>
								<?php
	}
}
								?>
							</div>

<!--							<a href="--><?php //echo esc_url( $product->add_to_cart_url() ); ?><!--" class="btn-black add_to_cart_button ajax_add_to_cart" data-product_id="--><?php //echo esc_attr( $product->get_id() ); ?><!--" data-product_sku="--><?php //echo esc_attr( $product->get_sku() ); ?><!--" aria-label="--><?php //echo esc_attr( $product->add_to_cart_text() ); ?><!--" rel="nofollow">Додати в кошик</a>-->
						</div>
					</div>
				</section>
				<?php endwhile; wp_reset_query(); ?>
			</div>
		</div>
		<div class="new-slider__buttons">
			<div class="reservation-btn-prev btn-swiper">
				<svg class="arrow"><use xlink:href="#arrow-prev"></use></svg>
				<svg class="arrow-mob"><use xlink:href="#arrow-prev-mob"></use></svg>
			</div>
			<div class="reservation-btn-next btn-swiper">
				<svg class="arrow"><use xlink:href="#arrow-next"></use></svg>
				<svg class="arrow-mob"><use xlink:href="#arrow-next-mob"></use></svg>
			</div>
		</div>
	</article>

</div>
<!-- reservation -->

<!-- advert -->
<div class="advert container">
	<div class="advert__column">
		<div class="advert__content">
			<h2 class="advert__title"><?php the_field('title_section8_left_col') ?></h2>
			<p class="advert__subtitle">
				<?php the_field('subtitle_section8_left_col') ?>
			</p>
			<a href="https://www.crabs.ua/product-category/liulky-cybex/" class="btn-black">Вибрати люльку</a>
		</div>
		<picture>
			<source srcset="<?php echo esc_url(get_field('section8_left_col_img_phone')); ?>" media="(max-width: 774.98px)" />
			<source srcset="<?php echo esc_url(get_field('section8_left_col_img_tab')); ?>" media="(max-width: 1439.98px)" />
			<img src="<?php echo esc_url(get_field('section8_left_col_img_desktop')); ?>" alt="advert" loading="lazy"/>
		</picture>
	</div>
	<div class="advert__column">
		<div class="advert__content">
			<h2 class="advert__title wh">
				<?php the_field('title_section8_right_col') ?>
			</h2>
			<p class="advert__subtitle wh">
				<?php the_field('subtitle_section8_right_col') ?>
			</p>
			<a href="https://www.crabs.ua/shop/" class="btn-white">Детальніше</a>
		</div>
		<picture>
			<source srcset="<?php echo esc_url(get_field('section8_right_col_img_phone')); ?>" media="(max-width: 774.98px)" />
			<img src="<?php echo esc_url(get_field('section8_right_col_img_desktop')); ?>" alt="advert" loading="lazy"/>
		</picture>
	</div>
</div>
<!-- advert -->

<!-- submain-picture -->								
<div class="submain-picture">
	<picture>
		<source srcset="<?php echo esc_url(get_field('section9_img_phone')); ?>" media="(max-width: 774.98px)" />
		<img src="<?php echo esc_url(get_field('section9_img_desktop')); ?>" alt="background" loading="lazy"/>
	</picture>
</div>
<!-- submain-picture -->	

<!-- gray-bg -->	
<div class="gray-bg" id="about-us">
	<div class="about-us container">
		<div class="about-us__top">
			<div class="about-us__title">
				<h2><?php the_field('about_us_text1'); ?></h2>
			</div>
			<div class="about-us__text"><?php the_field('about_us_text2'); ?></div>
		</div>
		<div class="about-us__cards">
			<div class="about-us__subtitle card-body">
				<p><?php the_field('about_us_text3'); ?></p>
			</div>
			<div class="about-us__card card-body">
				<svg
					 width="44"
					 height="44"
					 viewBox="0 0 44 44"
					 fill="none"
					 xmlns="http://www.w3.org/2000/svg"
					 >
					<rect width="44" height="44" rx="4" fill="#242424" />
					<path
						  d="M15.5 7.5H11C10.4477 7.5 10 7.94772 10 8.5V36.5C10 37.0523 10.4477 37.5 11 37.5H33C33.5523 37.5 34 37.0523 34 36.5V8.5C34 7.94772 33.5523 7.5 33 7.5H28.5M15.5 7.5V7.5C15.5 6.39543 16.3954 5.5 17.5 5.5H26.5C27.6046 5.5 28.5 6.39543 28.5 7.5V7.5M15.5 7.5V7.5C15.5 8.60457 16.3954 9.5 17.5 9.5H26.5C27.6046 9.5 28.5 8.60457 28.5 7.5V7.5M22.5 14H31M22.5 18H27.5M22.5 21.5H31M22.5 25.5H27.5M22.5 29H31M22.5 33H27.5M13 15.5L15.5 18L19.5 14M13 23L15.5 25.5L19.5 21.5M13 30.5L15.5 33L19.5 29"
						  stroke="#EEEDEA"
						  stroke-linecap="round"
						  />
				</svg>
				<h3><?php the_field('about_us_text4'); ?></h3>
				<p><?php the_field('about_us_text5'); ?></p>
			</div>
			<div class="about-us__card card-body">
				<svg
					 width="44"
					 height="44"
					 viewBox="0 0 44 44"
					 fill="none"
					 xmlns="http://www.w3.org/2000/svg"
					 >
					<rect width="44" height="44" rx="4" fill="#242424" />
					<path
						  d="M7.5 22.25V11C7.5 9.89543 8.39543 9 9.5 9H35C36.1046 9 37 9.89543 37 11V30.5M7.5 22.25V33.5C7.5 34.6046 8.39543 35.5 9.5 35.5H31.5M7.5 22.25L12.0144 17.1713C12.8067 16.2799 14.1981 16.2756 14.9958 17.1621L25.65 29M31.5 35.5H35C36.1046 35.5 37 34.6046 37 33.5V30.5M31.5 35.5L25.65 29M25.65 29L29.0382 25.1577C29.8209 24.2702 31.1987 24.2525 32.0039 25.1196L37 30.5"
						  stroke="#EEEDEA"
						  stroke-linecap="round"
						  stroke-linejoin="round"
						  />
					<circle cx="28" cy="16" r="3.5" stroke="#EEEDEA" />
				</svg>
				<h3><?php the_field('about_us_text6'); ?></h3>
				<p><?php the_field('about_us_text7'); ?></p>
			</div>
			<div class="about-us__card card-body">
				<svg
					 width="44"
					 height="44"
					 viewBox="0 0 44 44"
					 fill="none"
					 xmlns="http://www.w3.org/2000/svg"
					 >
					<rect width="44" height="44" rx="4" fill="#242424" />
					<path
						  d="M22.0625 22.9995L20.5625 24.4995L21.5625 25.4995M22.0625 22.9995L24.0625 20.9995M22.0625 22.9995L19.0625 19.9995M24.0625 20.9995L25.5625 19.4995L26.5625 20.4995M24.0625 20.9995L21.0625 17.9995M19.0625 19.9995L14.0625 14.9995L12.0625 14.4995L9.56245 10.4995L11.5625 8.49948L15.5625 10.9995L16.0625 12.9995L21.0625 17.9995M19.0625 19.9995L10.0625 28.9995C7.0625 32 11.0625 36 14.0625 32.9995L21.5625 25.4995M21.5625 25.4995L22.0625 25.9995L22.5625 25.4995L24.0625 26.9995L23.5625 27.4995L28.5625 32.4995C31.5625 34.9995 36.5625 31.4995 33.5625 27.4995L28.5625 22.4995L28.0625 22.9995L26.5625 21.4995L27.0625 20.9995L26.5625 20.4995M21.0625 17.9995L22.5625 16.4995C22.5625 16.4995 20.8009 12.761 23.5625 9.99998C26.5625 7.00063 30.0625 7.99948 30.0625 7.99948L26.5625 11.4995L27.5625 15.4995L31.5625 16.4995L35.0625 12.9995C35.0625 12.9995 35.5625 17.4993 32.5625 19.4995C29.5625 21.4997 26.5625 20.4995 26.5625 20.4995"
						  stroke="#EEEDEA"
						  stroke-linecap="round"
						  />
				</svg>
				<h3><?php the_field('about_us_text8'); ?></h3>
				<p><?php the_field('about_us_text9'); ?></p>
			</div>
			<div class="about-us__card card-body">
				<svg
					 width="44"
					 height="44"
					 viewBox="0 0 44 44"
					 fill="none"
					 xmlns="http://www.w3.org/2000/svg"
					 >
					<rect width="44" height="44" rx="4" fill="#242424" />
					<path
						  d="M7 17L11.5 9H32.5L37 17M7 17H14.5M7 17C7 17 7 20.1963 9.5 20.8745M37 17H29.5M37 17C37 17 37 20.1963 34.5 20.8745M14.5 17C14.5 17 14 21 10.5 21C10.1287 21 9.79672 20.955 9.5 20.8745M14.5 17C14.5 17 14.5 21 18 21C21.5 21 22 17 22 17M14.5 17H22M22 17C22 17 22.5 21 26 21C29.5 21 29.5 17 29.5 17M22 17H29.5M29.5 17C29.5 17 30 21 33.5 21C33.8713 21 34.2033 20.955 34.5 20.8745M9.5 20.8745V35H14M34.5 20.8745V35H20M14 35V25.5C14 24.9477 14.4477 24.5 15 24.5H19C19.5523 24.5 20 24.9477 20 25.5V35M14 35H20M30 25.5V29C30 29.5523 29.5523 30 29 30H24C23.4477 30 23 29.5523 23 29V25.5C23 24.9477 23.4477 24.5 24 24.5H29C29.5523 24.5 30 24.9477 30 25.5Z"
						  stroke="#EEEDEA"
						  stroke-linecap="round"
						  stroke-linejoin="round"
						  />
				</svg>

				<h3><?php the_field('about_us_text10'); ?></h3>
				<p><?php the_field('about_us_text11'); ?></p>
			</div>
			<div class="about-us__card card-body">
				<svg
					 width="44"
					 height="44"
					 viewBox="0 0 44 44"
					 fill="none"
					 xmlns="http://www.w3.org/2000/svg"
					 >
					<rect width="44" height="44" rx="4" fill="#242424" />
					<circle cx="22" cy="28" r="9.5" stroke="#EEEDEA" />
					<path
						  d="M15 21.5V13.3143C15 9.27472 18.134 6 22 6C25.866 6 29 9.27472 29 13.3143V21.5"
						  stroke="#EEEDEA"
						  />
					<path
						  d="M22 27V32"
						  stroke="#EEEDEA"
						  stroke-width="2"
						  stroke-linecap="round"
						  stroke-linejoin="round"
						  />
				</svg>
				<h3>
					<?php the_field('about_us_text12'); ?>
				</h3>
				<p><?php the_field('about_us_text13'); ?></p>
			</div>
			<div class="card-body">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/bg4.png" alt="background" loading="lazy"/>
			</div>
			<div class="about-us__card card-body">
				<svg
					 width="44"
					 height="44"
					 viewBox="0 0 44 44"
					 fill="none"
					 xmlns="http://www.w3.org/2000/svg"
					 >
					<rect width="44" height="44" rx="4" fill="#242424" />
					<path
						  d="M29.6679 18.8156C29.6723 20.1845 29.2839 21.5348 28.5345 22.7567C27.6459 24.2208 26.2799 25.4523 24.5895 26.3133C22.899 27.1742 20.9509 27.6305 18.9633 27.6312C17.3011 27.6347 15.6614 27.3149 14.1777 26.6978L6.99933 28.6683L9.39213 22.7567C8.64272 21.5348 8.25437 20.1845 8.2587 18.8156C8.25947 17.1788 8.81359 15.5744 9.859 14.1823C10.9044 12.7902 12.3998 11.6652 14.1777 10.9334C15.6614 10.3163 17.3011 9.99646 18.9633 10H19.593C22.218 10.1193 24.6973 11.0317 26.5562 12.5626C28.4152 14.0935 29.5231 16.1353 29.6679 18.297V18.8156Z"
						  stroke="#EEEDEA"
						  stroke-linecap="round"
						  stroke-linejoin="round"
						  />
					<path
						  d="M19 31.6154C20.6659 32.4871 22.5858 32.9492 24.5445 32.9498C26.1826 32.9534 27.7985 32.6296 29.2606 32.0047L36.3348 34L33.9768 28.0141C34.7153 26.7769 35.098 25.4096 35.0937 24.0235C35.093 22.3661 34.5469 20.7417 33.5167 19.332"
						  stroke="#EEEDEA"
						  stroke-linecap="round"
						  stroke-linejoin="round"
						  />
				</svg>
				<h3><?php the_field('about_us_text14'); ?></h3>
				<p><?php the_field('about_us_text15'); ?></p>
			</div>
			<div class="card-body">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/bg5.jpg" alt="background" loading="lazy"/>
			</div>
			<div class="card-body">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/bg6.jpg" alt="background" loading="lazy"/>
			</div>
		</div>
	</div>
	<div class="trust">
		<div class="trust__top container">
			<h2>
				<?php the_field('about_us_text16'); ?>
			</h2>
			<div class="trust__text">
				<p>
					<?php the_field('about_us_text17'); ?>
				</p>
				<p>
					<?php the_field('about_us_text18'); ?>
				</p>
			</div>
		</div>
		<article class="trust-slider container">
			<div class="trust-slider__gallary">
				<div class="trust-slider__wrapper swiper-wrapper">
					<?php
					$images = acf_photo_gallery('carousel_about_section', $post->ID);
					
					if( count($images) ):
					
					$images = array_slice($images, 0, 15);
					
					
					foreach($images as $image):
					$full_image_url = $image['full_image_url'];
					$title = $image['title'];
					$alt = get_field('photo_gallery_alt', $image['id']);
					?>
					<section class="swiper-slide">
						<img src="<?php echo esc_url($full_image_url); ?>" alt="<?php echo esc_attr($alt); ?>" title="<?php echo esc_attr($title); ?>" loading="lazy"/>
					</section>
					<?php endforeach; endif; ?>
				</div>
			</div>

			<div class="trust-slider__buttons">
				<div class="trust-btn-prev btn-swiper">
					<svg class="arrow"><use xlink:href="#arrow-prev"></use></svg>
					<svg class="arrow-mob">
						<use xlink:href="#arrow-prev-mob"></use>
					</svg>
				</div>
				<div class="trust-btn-next btn-swiper">
					<svg class="arrow"><use xlink:href="#arrow-next"></use></svg>
					<svg class="arrow-mob">
						<use xlink:href="#arrow-next-mob"></use>
					</svg>
				</div>
			</div>
		</article>
		<div class="trust__top container">
			<h2>
				<?php the_field('about_us_text19'); ?>
			</h2>
			<div class="trust__text">
				<p>
					<?php the_field('about_us_text20'); ?>
				</p>
				<p>
					<?php the_field('about_us_text21'); ?>
				</p>
			</div>

		</div>
	</div>
</div>
<!-- gray-bg -->	

<!-- contacts -->	
<div class="contacts container" id="contacts">
	<h2><?php _e('Контакти', 'crabs_project') ?></h2>
	<div class="contacts__block">
		<div class="contacts__card">
			<ul>
				<li>
					<div class="contacts__icon">
						<svg
							 width="22"
							 height="25"
							 viewBox="0 0 22 25"
							 fill="none"
							 xmlns="http://www.w3.org/2000/svg"
							 >
							<path
								  fill-rule="evenodd"
								  clip-rule="evenodd"
								  d="M21 11.2222C21 16.8678 16.5228 21.4444 11 24C5.47715 21.4444 1 16.8678 1 11.2222C1 5.57664 5.47715 1 11 1C16.5228 1 21 5.57664 21 11.2222ZM11 15.0556C13.0711 15.0556 14.75 13.3393 14.75 11.2222C14.75 9.10513 13.0711 7.38889 11 7.38889C8.92893 7.38889 7.25 9.10513 7.25 11.2222C7.25 13.3393 8.92893 15.0556 11 15.0556Z"
								  stroke="#242424"
								  stroke-width="2"
								  stroke-linecap="round"
								  stroke-linejoin="round"
								  />
						</svg>
					</div>
					<div class="contacts__info">
						<a href="https://www.google.com/maps/place/%D0%B2%D1%83%D0%BB%D0%B8%D1%86%D1%8F+%D0%92%D0%BE%D0%B2%D1%87%D0%B8%D0%BD%D0%B5%D1%86%D1%8C%D0%BA%D0%B0,+227,+%D0%86%D0%B2%D0%B0%D0%BD%D0%BE-%D0%A4%D1%80%D0%B0%D0%BD%D0%BA%D1%96%D0%B2%D1%81%D1%8C%D0%BA,+%D0%86%D0%B2%D0%B0%D0%BD%D0%BE-%D0%A4%D1%80%D0%B0%D0%BD%D0%BA%D1%96%D0%B2%D1%81%D1%8C%D0%BA%D0%B0+%D0%BE%D0%B1%D0%BB%D0%B0%D1%81%D1%82%D1%8C,+76000/data=!4m2!3m1!1s0x4730c190857098c3:0x3e8710ade89b870?sa=X&ved=1t:242&ictx=111"><?php the_field('contacts_address') ?></a>
					</div>
				</li>
				<li>
					<div class="contacts__icon">
						<svg
							 width="27"
							 height="22"
							 viewBox="0 0 27 22"
							 fill="none"
							 xmlns="http://www.w3.org/2000/svg"
							 >
							<path
								  d="M1.69444 1.71429L11.5358 11.8368C12.6206 12.9526 14.3794 12.9526 15.4642 11.8368L25.3056 1.71429M2.38889 21H24.6111C25.3782 21 26 20.3604 26 19.5714V2.42857C26 1.63959 25.3782 1 24.6111 1H2.38889C1.62183 1 1 1.63959 1 2.42857V19.5714C1 20.3604 1.62183 21 2.38889 21Z"
								  stroke="#242424"
								  stroke-width="2"
								  stroke-linecap="round"
								  stroke-linejoin="round"
								  />
						</svg>
					</div>
					<div class="contacts__info">
						<a href="mailto:<?php the_field('contacts_email') ?>"><?php the_field('contacts_email') ?></a>
						<span><?php the_field('contacts_email_desc') ?></span>
					</div>
				</li>
				<li>
					<div class="contacts__icon">
						<svg
							 width="23"
							 height="23"
							 viewBox="0 0 23 23"
							 fill="none"
							 xmlns="http://www.w3.org/2000/svg"
							 >
							<path
								  d="M21.9993 16.7228V19.8842C22.0043 21.0927 20.9176 22.1018 19.6975 21.9918C9.16669 22 1 13.7575 1.00824 3.29738C0.898354 2.08377 1.90251 1.00124 3.10947 1.0001H6.27714C6.78957 0.995069 7.28635 1.17617 7.67489 1.50965C8.77951 2.45774 9.49001 5.67573 9.21664 6.95499C9.00321 7.95376 7.99641 8.65251 7.31191 9.33565C8.81503 11.9739 11.0038 14.1583 13.6473 15.6584C14.3318 14.9753 15.0319 13.9705 16.0326 13.7575C17.3164 13.4842 20.5551 14.1958 21.4994 15.308C21.8343 15.7025 22.0122 16.206 21.9993 16.7228Z"
								  stroke="#242424"
								  stroke-width="2"
								  stroke-linecap="round"
								  stroke-linejoin="round"
								  />
						</svg>
					</div>
					<div class="contacts__info">
						<a href="tel:<?php the_field('contacts_phone1') ?>"><?php the_field('contacts_phone1') ?></a>
					</div>
				</li>
				<li>
					<div class="contacts__icon">
						<svg
							 width="23"
							 height="23"
							 viewBox="0 0 23 23"
							 fill="none"
							 xmlns="http://www.w3.org/2000/svg"
							 >
							<path
								  d="M21.9993 16.7228V19.8842C22.0043 21.0927 20.9176 22.1018 19.6975 21.9918C9.16669 22 1 13.7575 1.00824 3.29738C0.898354 2.08377 1.90251 1.00124 3.10947 1.0001H6.27714C6.78957 0.995069 7.28635 1.17617 7.67489 1.50965C8.77951 2.45774 9.49001 5.67573 9.21664 6.95499C9.00321 7.95376 7.99641 8.65251 7.31191 9.33565C8.81503 11.9739 11.0038 14.1583 13.6473 15.6584C14.3318 14.9753 15.0319 13.9705 16.0326 13.7575C17.3164 13.4842 20.5551 14.1958 21.4994 15.308C21.8343 15.7025 22.0122 16.206 21.9993 16.7228Z"
								  stroke="#242424"
								  stroke-width="2"
								  stroke-linecap="round"
								  stroke-linejoin="round"
								  />
						</svg>
					</div>
					<div class="contacts__info">
						<a href="tel:<?php the_field('contacts_phone2') ?>"><?php the_field('contacts_phone2') ?></a>
					</div>
				</li>
			</ul>
			<div class="contacts__social">
				<a href="<?php the_field('contacts_instagram') ?>" class="btn-black">Instagram</a>
				<a href="<?php the_field('contacts_telegram') ?>" class="btn-black">Telegram</a>
				<a href="viber://chat?number=380990084117" class="btn-black">Viber</a>
			</div>
		</div>
		<div class="contacts__picture">
			<iframe src="" frameborder="0"></iframe>
			<picture>
				<source
						srcset="<?php echo get_stylesheet_directory_uri(); ?>/img/contacts-photo.jpg"
						media="(max-width: 774.98px)"
						/>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/contacts-photo.jpg" alt="contacts-picture" loading="lazy"/>
			</picture>
		</div>
		<div class="contacts__map">
			<iframe
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3706.1087355618347!2d24.737438112939362!3d48.94178168733952!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4730c190857098c3%3A0x3e8710ade89b870!2z0YPQuy4g0JLQvtC70YfQuNC90LXRhtC60LDRjywgMjI3LCDQmNCy0LDQvdC-LdCk0YDQsNC90LrQvtCy0YHQuiwg0JjQstCw0L3Qvi3QpNGA0LDQvdC60L7QstGB0LrQsNGPINC-0LHQu9Cw0YHRgtGMLCA3NjAwMA!5e0!3m2!1suk!2sua!4v1719213314732!5m2!1suk!2sua"
					width="100%"
					height="100%"
					style="border: 0"
					allowfullscreen=""
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					></iframe>

		</div>
	</div>
</div>
<!-- contacts -->

<!-- gray-bg -->	
<div class="gray-bg-mob">
	<div class="writings container">
		<div class="writings__top">
			<div class="writings__title">
				<h2><?php _e('Статті', 'crabs_project') ?></h2>
			</div>
			<div class="writings__text"><?php _e('Корисні статті про батьківство', 'crabs_project') ?></div>
		</div>
		<article class="writings-slider">
			<div class="writings-slider__gallary">
				<div class="writings-slider__wrapper swiper-wrapper">
					<?php
	$recent_posts = new WP_Query(array(
		'post_type' => 'post',
		'posts_per_page' => 4,
		'orderby' => 'date',
		'order' => 'DESC'
	));

					if ($recent_posts->have_posts()) :
					while ($recent_posts->have_posts()) : $recent_posts->the_post();
					$categories = get_the_category();
					$category_name = $categories ? $categories[0]->name : 'Без категории';
					$post_date = get_the_date('d.m.Y');
					$post_title = get_the_title();
					$post_permalink = get_permalink();
					$post_thumbnail = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : get_stylesheet_directory_uri() . '/img/default.jpg';
					?>
					<section class="swiper-slide">
						<a class="writings-slider__image" href="<?php echo esc_url($post_permalink); ?>"><img src="<?php echo esc_url($post_thumbnail); ?>" alt="<?php echo esc_attr($post_title); ?>" loading="lazy"/></a>

						<div class="writings-slider__info">
							<div class="writings-slider__tag"><?php echo esc_html($category_name); ?></div>
							<span><?php echo esc_html($post_date); ?></span>
							<h3>
								<a href="<?php echo esc_url($post_permalink); ?>">
									<?php echo esc_html($post_title); ?>
								</a>
							</h3>
						</div>
					</section>
					<?php
					endwhile;
					wp_reset_postdata();
					endif;
					?>
				</div>
			</div>
			<div class="writings-slider__buttons">
				<div class="writings-btn-prev btn-swiper">
					<svg class="arrow"><use xlink:href="#arrow-prev"></use></svg>
					<svg class="arrow-mob">
						<use xlink:href="#arrow-prev-mob"></use>
					</svg>
				</div>
				<div class="writings-btn-next btn-swiper">
					<svg class="arrow"><use xlink:href="#arrow-next"></use></svg>
					<svg class="arrow-mob">
						<use xlink:href="#arrow-next-mob"></use>
					</svg>
				</div>
			</div>
		</article>

		<div class="writings__btn">
			<a href="/bloh/" class="btn-black"><?php _e('Всі статті', 'crabs_project') ?></a>
		</div>
	</div>
</div>
<!-- gray-bg -->	

<!-- gray-bg -->	
<div class="gray-bg">
	<div class="feedback container">
		<div class="feedback__top">
			<div class="feedback__title">
				<h2><?php _e('Відгуки', 'crabs_project') ?></h2>
			</div>
			<div class="feedback__right">
				<div class="feedback__text"><?php _e('Що говорять про нас клієнти', 'crabs_project') ?></div>
				<a href="https://search.google.com/local/reviews?placeid=ChIJqWJKzmbBMEcRI16fFhKzeH0" target="_blank" class="feedback__google-rating">
					<svg class="star"><use xlink:href="#star"></use></svg>
					<span>5.0 Наш рейтинг</span>
					<svg
						 width="75"
						 height="25"
						 viewBox="0 0 75 25"
						 fill="none"
						 xmlns="http://www.w3.org/2000/svg"
						 >
						<path
							  d="M53.1348 16.3703C54.6163 15.938 55.4846 14.6646 55.4846 12.9242C55.4846 11.8569 55.1941 11.0174 54.6075 10.3902C52.8975 8.56137 49.9928 9.35168 49.2686 11.8427C48.9719 12.8635 48.9724 12.9414 49.2821 14.0554C49.5594 15.0528 50.4356 16.086 51.1314 16.2362C51.3378 16.2808 51.6418 16.3754 51.8069 16.4465C52.2247 16.6263 52.2654 16.624 53.1348 16.3703ZM50.4473 24.4246C48.8908 23.9162 47.4838 22.7179 46.7464 21.2726C46.5348 20.8579 46.6415 20.7722 48.0348 20.237C49.0161 19.8601 49.33 19.8183 49.33 20.0645C49.33 20.4219 50.2636 21.382 50.9038 21.6829C52.1845 22.2849 53.8685 21.9239 54.6678 20.876C55.0365 20.3926 55.365 19.1068 55.305 18.3817L55.2731 17.9965L54.6658 18.4198C53.802 19.022 52.3736 19.2869 51.2064 19.0613C49.8101 18.7915 48.926 18.3265 47.9895 17.3694C47.0005 16.3587 46.5747 15.5399 46.2646 14.0526C45.6434 11.0734 47.5341 7.88576 50.4841 6.93866C51.9829 6.45747 54.0843 6.76731 54.9708 7.60019L55.3116 7.92034L55.3606 7.49212L55.4096 7.06392H56.7606H58.1116L58.1554 13.0684C58.2015 19.3769 58.1329 20.2393 57.4785 21.5811C56.8304 22.9096 55.5738 23.9558 54.0304 24.4518C53.0559 24.7649 51.4519 24.7528 50.4473 24.4246Z"
							  fill="#4286F5"
							  />
						<path
							  d="M27.4541 15.8937C28.6161 15.0884 29.1834 13.338 28.7734 11.8225C28.4318 10.5595 26.9785 9.39171 25.7485 9.39171C23.7644 9.39171 22.3211 10.8563 22.3133 12.8775C22.3071 14.4887 23.3045 15.9747 24.6195 16.3134C25.4723 16.533 26.8193 16.3336 27.4541 15.8937ZM24.4442 19.057C22.0668 18.6333 20.3421 17.0816 19.5296 14.6356C19.1544 13.5058 19.2794 11.5489 19.7977 10.4406C20.9135 8.0546 23.0357 6.68504 25.6041 6.6934C30.4981 6.70932 33.393 11.679 31.0935 16.1169C30.4359 17.386 28.5583 18.7392 27.0769 19.0117C25.7516 19.2554 25.5747 19.2585 24.4442 19.057Z"
							  fill="#EA4235"
							  />
						<path
							  d="M66.8927 12.3449C67.447 12.0982 67.2424 12.1652 67.3398 12.1626C67.4373 12.16 68.062 11.8908 68.5972 11.6431C69.1323 11.3955 69.6774 11.1928 69.8085 11.1928C69.9397 11.1928 69.1677 11.5252 69.1677 11.4598C69.1677 11.3943 70.4189 10.8283 70.8735 10.6738C71.6769 10.4007 71.6926 10.3847 71.4368 10.102C70.6806 9.26639 69.152 9.09982 68.1345 9.74211C67.3096 10.2628 66.6518 11.147 66.5247 11.9058C66.4107 12.5866 66.3559 12.5837 66.8927 12.3449ZM68.4708 19.0542C66.0974 18.4973 64.5415 16.9985 63.7537 14.5104C63.5402 13.836 63.5473 11.8779 63.7658 11.2035C64.6863 8.36163 66.7781 6.68945 69.4124 6.68945C70.9165 6.68945 72.1589 7.20022 73.238 8.26225C74.0096 9.02161 75.0006 10.6815 75.0006 11.2144C75.0006 11.5591 74.8209 11.6632 72.9741 12.3885C71.489 12.9717 70.7723 13.2655 69.5966 13.773C69.3902 13.8621 69.0862 13.9898 68.9211 14.0568C68.756 14.1238 68.3169 14.3087 67.9454 14.4676C67.5738 14.6266 67.1753 14.7856 67.0597 14.8211C66.6989 14.9318 67.9403 16.0911 68.6209 16.2792C69.9585 16.6487 71.1356 16.3945 72.0684 15.5346C72.4199 15.2107 72.7764 14.9456 72.8607 14.9456C73.1152 14.9456 74.9953 16.2094 74.998 16.3823C75.0032 16.7018 73.6854 17.9059 72.8144 18.3777C72.0155 18.8103 70.184 19.3251 69.5966 19.2821C69.4728 19.273 68.9661 19.1705 68.4708 19.0542Z"
							  fill="#EA4235"
							  />
						<path
							  d="M7.82492 18.9918C5.99281 18.699 4.22438 17.7292 2.67777 16.1692C1.59214 15.0742 0.878412 13.917 0.280242 12.2822C-0.100078 11.2428 -0.0912936 7.81654 0.294388 6.76497C1.51375 3.44039 3.84794 1.20964 7.14941 0.213718C8.19847 -0.10274 11.5341 -0.0583507 12.4784 0.284633C13.91 0.804626 15.6627 1.81722 16.1524 2.4072C16.3318 2.62336 16.2403 2.77042 15.3975 3.62076C14.87 4.15293 14.4028 4.58827 14.3591 4.58817C14.3154 4.58807 14.0833 4.41905 13.8432 4.21257C12.5995 3.14297 10.2081 2.57728 8.39662 2.92414C6.33371 3.31914 4.62277 4.62551 3.6616 6.5395C3.18055 7.49742 2.86818 7.55314 2.86818 9.54204C2.86818 11.4316 3.19893 11.6272 3.57805 12.4363C3.80168 12.9136 4.36966 13.7077 4.84023 14.2011C6.55741 16.0014 9.06734 16.7096 11.4685 16.0714C12.8156 15.7133 13.3927 15.4053 14.2335 14.5954C14.9688 13.8872 15.4479 13.0096 15.618 12.0595L15.7182 11.4997L12.6722 11.4591L9.62626 11.4184V10.0167V8.61504L13.9795 8.62752C16.3738 8.63438 18.3741 8.64031 18.4246 8.64068C18.4751 8.64116 18.5891 9.00325 18.678 9.44555C19.0123 11.1106 18.3987 13.6325 17.2212 15.4329C16.2513 16.9157 15.2131 17.716 13.0038 18.684C12.0874 19.0855 9.40763 19.2447 7.82492 18.9918Z"
							  fill="#4185F4"
							  />
						<path
							  d="M40.07 16.2738C40.7685 16.0989 41.7056 15.2191 42.0491 14.4157C42.1867 14.0939 42.4081 13.4744 42.3754 12.8555C42.3287 11.9718 42.1409 11.3436 41.9558 11.1193C41.1738 10.1721 40.7643 9.82489 40.1905 9.62247C38.9056 9.16911 37.7098 9.41712 36.7429 10.3375C34.8525 12.137 35.5777 15.4779 38.0398 16.3117C38.7453 16.5506 38.9828 16.5462 40.07 16.2738ZM37.4801 18.9951C35.8861 18.6587 34.221 17.391 33.4659 15.9386C32.2076 13.5187 32.5273 10.7125 34.2826 8.76956C36.7869 5.99762 41.2016 5.99887 43.7527 8.77223C45.9474 11.1583 45.7199 15.4896 43.2905 17.5692C42.1325 18.5605 41.0052 19.0069 39.487 19.0755C38.7506 19.1088 37.8475 19.0726 37.4801 18.9951Z"
							  fill="#FABC05"
							  />
						<path
							  d="M59.6885 9.69308V0.525391L61.1521 0.568357L62.6157 0.611316V9.69308V18.7748L61.1521 18.8178L59.6885 18.8608V9.69308Z"
							  fill="#34A853"
							  />
					</svg>
				</a>
			</div>
		</div>
		<div class="feedback-slider">
			<div class="feedback-slider__gallary">
                <div class="feedback-slider__wrapper swiper-wrapper">
                    <?php 
                    $args = array(
                        'post_type' => 'product_reviews',
                        'post_status' => 'publish',
                        'numberposts' => -1,
                        'orderby' => 'date',
                        'order' => 'DESC'
                    );
                
                    $reviews = get_posts($args);
                
                    if ($reviews && !empty($reviews)) {
                        $max_index = 0;
                        $max_len = 0;
                    
                        foreach ($reviews as $i => $r) {
                            $len = mb_strlen(wp_strip_all_tags($r->post_content), 'UTF-8');
                    
                            if ($len > $max_len) {
                                $max_len = $len;
                                $max_index = $i;
                            }
                        }
                    
                        if ($max_index !== 0) {
                            $longest = $reviews[$max_index];
                            array_splice($reviews, $max_index, 1);
                            array_unshift($reviews, $longest);
                        }
                        
                        foreach ($reviews as $review) {
                            $first_name = get_the_title($review->ID);
                            $rating = get_post_meta($review->ID, 'rating', true);
                            $review_text = apply_filters('the_content', $review->post_content);
                            $photo_urls = get_post_meta($review->ID, 'photos', true);
                            
                            if (!is_array($photo_urls)) {
                                $photo_urls = array();
                            }
                            
                            $avatar_url = !empty($photo_urls[0]) ? $photo_urls[0] : '';
                
                            // Вычисляем количество звезд
                            $stars_html = '';
                            $rating_value = floatval($rating);
                            
                            for ($i = 1; $i <= 5; $i++) {
                                $star_color = $i <= $rating_value ? '#F4A804' : '#CCCCCC';
                                $stars_html .= '<svg class="star" width="36" height="33" viewBox="0 0 36 33" fill="' . esc_attr($star_color) . '" xmlns="http://www.w3.org/2000/svg"><path d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z"/></svg>';
                            }
                    ?>
                    <div class="swiper-slide">
                        <div class="feedback-slider__top">
                            <div class="feedback-slider__autor">
                                <div class="feedback-slider__avatar">
                                    <img src="<?php echo esc_url($avatar_url ? $avatar_url : get_stylesheet_directory_uri() . '/img/Лого-4.png'); ?>" alt="<?php echo esc_attr($first_name); ?>" loading="lazy"/>
                                </div>
                                <h3><?php echo esc_html($first_name); ?></h3>
                            </div>
                            <div class="feedback-slider__rating">
                                <div class="feedback-slider__stars">
                                    <?php echo $stars_html; ?>
                                </div>
                                <span><?php echo esc_html(number_format($rating_value, 1)); ?></span>
                            </div>
                        </div>
                        <div class="feedback-slider__text">
                            <?php echo $review_text; ?>
                        </div>
                        <?php if (!empty($photo_urls)) : ?>
                        <div class="feedback-slider__product gallery-reviews">
                            <?php foreach ($photo_urls as $photo_url) : ?>
                            <div class="feedback-slider__image">
                                <a href="<?php echo esc_url($photo_url); ?>">
                                    <img src="<?php echo esc_url($photo_url); ?>" alt="product" loading="lazy"/>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php
                        }
                    } else {
                        echo '<div class="no-reviews"><p>Нет отзывов</p></div>';
                    }
                    ?>
                </div>
			</div>
			<div class="feedback-slider__buttons">
				<div class="feedback-btn-prev btn-swiper">
					<svg
						 width="40"
						 height="40"
						 viewBox="0 0 40 40"
						 fill="none"
						 xmlns="http://www.w3.org/2000/svg"
						 class="feedback-slider__btn"
						 >
						<rect width="40" height="40" rx="5" fill="#242424" />
						<path
							  d="M22.7451 26.4902L16.0001 19.7452L22.7451 13.0002"
							  stroke="white"
							  stroke-linecap="round"
							  stroke-linejoin="round"
							  />
					</svg>
					<svg
						 width="55"
						 height="55"
						 viewBox="0 0 55 55"
						 fill="none"
						 xmlns="http://www.w3.org/2000/svg"
						 class="feedback-slider__btn-mob"
						 >
						<rect width="55" height="55" rx="5" fill="#242424" />
						<path
							  d="M29.209 37L20.209 27.5L29.209 18"
							  stroke="white"
							  stroke-width="1.5"
							  stroke-linecap="round"
							  stroke-linejoin="round"
							  />
					</svg>
				</div>
				<div class="feedback-btn-next btn-swiper">
					<svg
						 width="40"
						 height="40"
						 viewBox="0 0 40 40"
						 fill="none"
						 xmlns="http://www.w3.org/2000/svg"
						 class="feedback-slider__btn"
						 >
						<rect width="40" height="40" rx="5" fill="#242424" />
						<path
							  d="M18 14L23.745 19.745L18 25.49"
							  stroke="white"
							  stroke-linecap="round"
							  stroke-linejoin="round"
							  />
					</svg>
					<svg
						 width="55"
						 height="55"
						 viewBox="0 0 55 55"
						 fill="none"
						 xmlns="http://www.w3.org/2000/svg"
						 class="feedback-slider__btn-mob"
						 >
						<rect width="55" height="55" rx="5" fill="#242424" />
						<path
							  d="M26.125 18L35.125 27.5L26.125 37"
							  stroke="white"
							  stroke-width="1.5"
							  stroke-linecap="round"
							  stroke-linejoin="round"
							  />
					</svg>
				</div>
			</div>
		</div>
		<div class="feedback__btn">
			<a href="/vidhuky/" class="btn-black">Всі відгуки</a>
		</div>
	</div>
</div>
<!-- gray-bg -->


<?php
get_footer();
