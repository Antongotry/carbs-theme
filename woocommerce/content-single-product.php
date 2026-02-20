<?php

/**
	 * The template for displaying product content in the single-product.php template
	 *
	 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
	 *
	 * HOWEVER, on occasion WooCommerce will need to update template files and you
	 * (the theme developer) will need to copy the new files to your theme to
	 * maintain compatibility. We try to do this as little as possible, but it does
	 * happen. When this occurs the version of the template file will be bumped and
	 * the readme will list any important changes.
	 *
	 * @see     https://woocommerce.com/document/template-structure/
	 * @package WooCommerce\Templates
	 * @version 3.6.0
	 */

defined('ABSPATH') || exit;

global $product;

/**
	 * Hook: woocommerce_before_single_product.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

$product_id = $product->get_id();
$colors = wc_get_product_terms($product_id, 'pa_kolory-v-naiavnosti', array('fields' => 'all'));
$price = $product->get_price_html();
$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();
$sku = $product->get_sku();
$collections = wc_get_product_terms($product->get_id(), 'pa_kolektsiia', array('fields' => 'all'));

$count = 0; // значення за замовчуванням
$count = $product->get_stock_quantity() ? $product->get_stock_quantity() : 0;

$sku = $product->get_sku();

$xml_url = 'https://server-v2.servep2p.com:8085/PricePromOdegda.xml';

// Пробуємо отримати XML (з кешем, щоб не вантажити постійно)
$transient_key = 'xml_priceprom_cache';
$xml_data = get_transient($transient_key);

if ($xml_data === false) {
    $response = wp_remote_get($xml_url, [
        'timeout' => 15,
        'sslverify' => false,
    ]);
    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $xml = @simplexml_load_string($body);
        if ($xml) {
            $xml_data = json_encode($xml);
            set_transient($transient_key, $xml_data, HOUR_IN_SECONDS);
        }
    }
}

if ($xml_data) {
    $xml = json_decode($xml_data);
    $offers = [];

    if (isset($xml->shop->offers->offer)) {
        $offers = $xml->shop->offers->offer;
    } elseif (isset($xml->offer)) {
        $offers = $xml->offer;
    }

    foreach ($offers as $offer) {
        $vendor = (string) $offer->vendorCode;
        if ($vendor === $sku) {
            $count = (int) $offer->quantity_in_stock;
            break;
        }
    }
}



if ($product->is_in_stock()) {
    if ($count <= 1 && $count !== null) {
        $textAv = "Закінчується";
        $classAv = "tag-ends";
    } else {
        $textAv = "В наявності";
        $classAv = "tag-available";
    }
} else {
    $textAv = "Під замовлення";
    $classAv = "tag-order";
}
?>

<link rel="stylesheet" href="/wp-content/themes/carbs-theme/css/product-page.css?v=<?php echo get_dynamic_version(); ?>">

<svg display="none" xmlns="http://www.w3.org/2000/svg">
	<symbol id="star" viewBox="0 0 20 19">
		<path d="M10 0L13.4092 5.3077L19.5106 6.90983L15.5161 11.7923L15.8779 18.0902L10 15.8L4.12215 18.0902L4.48387 11.7923L0.489435 6.90983L6.59085 5.3077L10 0Z" fill="#F4A804" />
	</symbol>
	<symbol id="bag" viewBox="0 0 41 41">
		<rect width="41" height="41" rx="5" fill="#242424" />
		<path d="M12.8402 18.8104C12.9467 17.7839 13.8862 17 15.0097 17H25.9903C27.1138 17 28.0533 17.7839 28.1598 18.8104L28.99 26.8104C29.1118 27.9846 28.1057 29 26.8205 29H14.1795C12.8943 29 11.8882 27.9846 12.01 26.8104L12.8402 18.8104Z" fill="#242424" />
		<path d="M16.1414 20V16C16.1414 13.7909 18.0928 12 20.5 12C22.9072 12 24.8586 13.7909 24.8586 16V20M14.1795 29H26.8205C28.1057 29 29.1118 27.9845 28.99 26.8104L28.1598 18.8104C28.0533 17.7839 27.1139 17 25.9903 17H15.0097C13.8862 17 12.9467 17.7839 12.8402 18.8104L12.01 26.8104C11.8882 27.9846 12.8943 29 14.1795 29Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
	</symbol>
	<symbol id="arrow-prev" viewBox="0 0 50 50" width="50" height="50" fill="none">
		<rect width="50" height="50" rx="5" fill="#E93A53" />
		<path d="M26.5547 30L21.2513 24.6967L26.5547 19.3933" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
	</symbol>
	<symbol id="arrow-next" viewBox="0 0 50 50" width="50" height="50" fill="none">
		<rect width="50" height="50" rx="5" fill="#E93A53" />
		<path d="M23.75 20L29.0533 25.3033L23.75 30.6067" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
	</symbol>
	<symbol id="arrow-prev_40" width="40" height="40" viewBox="0 0 40 40" fill="none">
		<rect x="0.5" y="0.5" width="39" height="39" rx="4.5" stroke="#242424" />
		<path d="M21.2427 24L17 19.7573L21.2427 15.5146" stroke="#242424" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
	</symbol>
	<symbol id="arrow-next_40" width="40" height="40" viewBox="0 0 40 40" fill="none">
		<rect x="0.5" y="0.5" width="39" height="39" rx="4.5" stroke="#242424" />
		<path d="M19 16L23.2427 20.2427L19 24.4854" stroke="#242424" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
	</symbol>
	<symbol id="arrow-prev-mob" width="55" height="55" viewBox="0 0 55 55" fill="none">
		<rect width="55" height="55" rx="5" fill="#E93A53" />
		<path d="M31 18L22 27.5L31 37" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
	</symbol>
	<symbol id="arrow-next-mob" width="55" height="55" viewBox="0 0 55 55" fill="none">
		<rect x="55" y="55.002" width="55" height="55" rx="5" transform="rotate(180 55 55.002)" fill="#E93A53" />
		<path d="M24 37.002L33 27.502L24 18.002" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
	</symbol>
	<symbol id="arrow-top" width="22" height="22" viewBox="0 0 22 22" fill="none">
		<rect x="22" width="22" height="22" rx="3" transform="rotate(90 22 0)" fill="#E93A53" />
		<path d="M8.7998 11.6836L11.1333 9.35012L13.4667 11.6836" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
	</symbol>
	<symbol id="arrow-top-mob" width="30" height="30" viewBox="0 0 30 30" fill="none">
		<rect x="30" width="30" height="30" rx="3" transform="rotate(90 30 0)" fill="#E93A53" />
		<path d="M12 15.9316L15.182 12.7496L18.364 15.9316" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
	</symbol>
</svg>

<?php
global $product;
$attributes = $product->get_attributes();
$counter = 0;
$has_visible_attributes = false;

foreach ($attributes as $attribute) {
    if($attribute->get_visible())
    {
        $has_visible_attributes = true;
        break;
    }
}
?>

<div class="mobile-product-navigation mob">
	<a data-to-scroll="navigation-scroll-desc" href="#description">Опис</a>
    <?php if ($has_visible_attributes) :?>
	<a data-to-scroll="navigation-scroll-char" href="#characteristics">Характеристики</a>
    <?php endif;?>
	<a data-to-scroll="navigation-scroll-booking" href="#booking-mob">Бронювання до пологів</a>
	<a data-to-scroll="navigation-scroll-delivery" href="#delivery-mob">Оплата і доставка</a>
	<a data-to-scroll="navigation-scroll-reviews" href="#reviews-mob">Відгуки</a>
</div>




<div class="stock-info-modal-overlay">
    <div class="stock-info-modal">
        <button class="modal__close" type="button" aria-label="Закрити" data-close-modal>
          <svg width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 1L26 26M26 1L1 26" stroke="#E93A53" stroke-width="2"/>
        </svg>
        </button>
        <div class="modal__icon" aria-hidden="true">
        <img src="/wp-content/uploads/2025/10/Vector-291_red-1.svg" alt="icon" width="100" height="100">
        </div>
        <h2 class="modal__title" id="lowstock-title">Товар закінчується на складі</h2>
        <span class="modal__text">Кількість товару, що залишилась: <?php echo $count; ?>шт</span>
    </div>
</div>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
	<div class="card-main container">
		<!-- Left Col -->
		<div class="card-main__column">
			<div class="card-main__icons">
				<div class="card-main__icon-right mob">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/percentage.png" alt="percentage"  width="35" height="35">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/paw.png" alt="paw" width="35" height="35">
				</div>
			</div>

			<div class="product-gallery">
				<div class="swiper gallery-thumbs">
					<div class="swiper-wrapper">
						<?php
						$attachment_ids = $product->get_gallery_image_ids();
						$placeholder_image = '/wp-content/uploads/woocommerce-placeholder.png';

						if (empty($attachment_ids) && empty($variation_images) && !$product->get_image_id()) {
							// Если нет изображений, выводим заполнитель
							echo '<div class="swiper-slide">';
							echo '<img src="' . esc_url($placeholder_image) . '" class="swiper-lazy" alt="Product placeholder">';
							echo '</div>';
						} else {
							// Добавляем основное изображение товара или заполнитель, если его нет
							echo '<div class="swiper-slide">';
							if ($product->get_image_id()) {
								echo wp_get_attachment_image($product->get_image_id(), 'thumbnail', false, array('class' => 'swiper-lazy'));
							} else {
								echo '<img src="' . esc_url($placeholder_image) . '" class="swiper-lazy" alt="Product placeholder">';
							}
							echo '</div>';

							// Добавляем остальные изображения из галереи
							foreach ($attachment_ids as $attachment_id) {
								echo '<div class="swiper-slide">';
								echo wp_get_attachment_image($attachment_id, 'thumbnail', false, array('class' => 'swiper-lazy'));
								echo '</div>';
							}

						}
						?>
					</div>
				</div>
				<div class="swiper gallery-top">
					<div class="swiper-wrapper">
						<?php
						if (empty($attachment_ids) && empty($variation_images) && !$product->get_image_id()) {
							// Если нет изображений, выводим заполнитель
							echo '<div class="swiper-slide">';
							echo '<img src="' . esc_url($placeholder_image) . '" class="swiper-lazy" alt="Product placeholder">';
							echo '</div>';
						} else {
							// Добавляем основное изображение товара или заполнитель, если его нет
							echo '<div class="swiper-slide">';
							if ($product->get_image_id()) {
								echo wp_get_attachment_image($product->get_image_id(), 'full', false, array('class' => 'swiper-lazy'));
							} else {
								echo '<img src="' . esc_url($placeholder_image) . '" class="swiper-lazy" alt="Product placeholder">';
							}
							echo '</div>';

							// Добавляем остальные изображения из галереи
							foreach ($attachment_ids as $attachment_id) {
								echo '<div class="swiper-slide">';
								echo wp_get_attachment_image($attachment_id, 'full', false, array('class' => 'swiper-lazy'));
								echo '</div>';
							}
						}
						?>
					</div>
					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>
				</div>
			</div>


			<?php do_action('woocommerce_before_single_product_summary'); ?>

		</div> 
		<!-- Left Col -->

		<!-- Right Col -->
		<div class="card-main__column">

			<!-- Short Story -->
			<div class="shortstory">
				<div class="shortstory__header">
					<div class="data-line">
						<div class="shortstory__code"><?php echo __('Код:', 'crabs_project') . ' ' . esc_html($sku); ?></div>
						<div class="card-main__tag  <?php echo esc_attr($classAv); ?>">
							<img loading="lazy" src="/wp-content/themes/carbs-theme/img/tag-available.svg" class="available" style="display: none;">
							<img loading="lazy" src="/wp-content/themes/carbs-theme/img/tag-order.svg" class="order" style="display: none;">
							<img loading="lazy" src="/wp-content/uploads/2025/10/Vector-291_red-1.svg" class="ends" style="display: none;">
							<span><?php echo esc_html($textAv); ?></span>
							<?php if ($count <= 1 && $count > 0 && $count !== null) { ?>
							    <button type="button" class="stock-info-btn" aria-label="Що означає статус">i</button>
							<?php } ?>
						</div>
					</div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const button = document.querySelector('.stock-info-btn');
                            const overlay = document.querySelector('.stock-info-modal-overlay');
                            const modal = document.querySelector('.stock-info-modal');
                            
                            if (!button || !overlay || !modal) {
                                return;
                            }
                            
                            button.addEventListener("click", function() {
                                overlay.classList.toggle('shown');
                            });
                            
                            overlay.addEventListener("click", function() {
                                overlay.classList.remove('shown');
                            });
                        });
                    </script>

					<h1><?php the_title(); ?></h1>
					<?php echo apply_filters('woocommerce_short_description', $post->post_excerpt); ?>
                    <div class="shortstory__right">
                        <div class="shortstory__rating">
                            <div class="shortstory__stars">
                                <?php
                                $average_rating = get_custom_product_average_rating($post->ID);
                                $ratings_count = get_ratings_count($post->ID);
                                ?>
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <svg class="star" width="36" height="33" viewBox="0 0 36 33" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="<?php echo ($i <= $average_rating) ? '#F4A804' : 'grey'; ?>" d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z" />
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <span><?php echo number_format($average_rating, 1); ?></span>
                        </div>

                        <!-- <div class="wishlist-icon <?php //echo wooeshop_in_wishlist2( $product->get_id() ) ? 'in-wishlist' : ''
                        ?>" data-id="<?php //echo $product->get_id();
                        ?>">
<i class="fa-solid fa-heart"></i>
</div> -->
                        <div class="ajax-loader">
                            <!-- <img src="<?php //echo get_stylesheet_directory_uri();
                            ?>/img/ripple.svg" alt=""> -->
                        </div>



                        <button class="wishlist-icon <?php echo wooeshop_in_wishlist($product->get_id()) ? 'in-wishlist' : ''; ?>" data-id="<?php echo $product->get_id(); ?>">
                            <svg width="40" height="40" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="26" height="26" rx="4" fill="white"></rect>
                                <path d="M7.01792 13.0342L12.881 18.6673L18.744 13.0342C19.3957 12.408 19.7619 11.5587 19.7619 10.6731C19.7619 8.82896 18.2059 7.33398 16.2865 7.33398C15.3648 7.33398 14.4808 7.68578 13.829 8.31199L12.881 9.22287L11.9329 8.31199C11.2811 7.68578 10.3971 7.33398 9.47541 7.33398C7.55599 7.33398 6 8.82896 6 10.6731C6 11.5587 6.36616 12.408 7.01792 13.0342Z" stroke="#E93A53" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>

                    </div>


                    <?php
                        global $product;
                        $alternativesIds = get_field('alternative_products', $product->get_id());
                    ?>
                    <?php if ( $product && !empty($alternativesIds)) : ?>
                        <div class="alternative-head">Кольори в наявності</div>

                        <?php echo do_shortcode('[op_alternative_products]'); ?>
                    <?php endif; ?>

				</div>
				<div class="shortstory__instalments">
					<div class="shortstory__icons">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/percentage.png" alt="percentage"  width="35" height="35">
											<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/paw.png" alt="paw"  width="35" height="35">
					</div>
					<span><?php _e('Оплачуйте частинами від 5000 грн в місяць без вістотків', 'crabs_project'); ?></span>
					<?php
					$product_id = $product->get_id();

					?>
					<a href="/checkout/?add-to-cart=<?php echo $product_id; ?>" class="btn-white"><?php _e('Взяти в розстрочку', 'crabs_project'); ?></a>
				</div>
				
				<!--<div class="show-room_block">-->
				<!--<a href="##" class="shortstory__check open-popup">-->
    <!--						<span>Наявність на складі</span>-->
    <!--						<svg width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg">-->
    <!--							<path d="M1 1L5.24268 5.24268L1 9.48535" stroke="#E93A53" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />-->
    <!--						</svg>-->
    <!--					</a>	-->
				<!--</div>-->
				
				
				<div class="show-room_block">
                  <a href="##" 
                     class="shortstory__check open-popup"
                     data-sku="<?php echo esc_attr( get_post_meta( get_the_ID(), '_sku', true ) ); ?>">
                     
                    <span>Наявність на складі</span>
                    <svg width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M1 1L5.24268 5.24268L1 9.48535"
                            stroke="#E93A53"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    
                  </a>
                </div>

				
				
				
    
				<div class="price-line">

					<div class="shortstory__prices">
					    <?php if ( crabs_should_show_price( $product ) ) : ?>
    						<div class="shortstory__price-left">
    							<?php if ($product->is_type('variable')) {
                                    	// Get the available variations
                                    	$available_variations = $product->get_available_variations();
                                    	$variation_prices = array();
                                    
                                    	foreach ($available_variations as $variation) {
                                    		$variation_obj = new WC_Product_Variation($variation['variation_id']);
                                    		$price = $variation_obj->get_price();
                                    
                                    		if ($price !== '' && $price !== null) {
                                    			$variation_prices[] = $price;
                                    		}
                                    	}
                                    
                                    	if (!empty($variation_prices)) {
                                    		// Get the minimum and maximum prices from the variations
                                    		$min_price = min($variation_prices);
                                    		$max_price = max($variation_prices);
                                    
                                    		// Get the minimum and maximum regular prices from the variations
                                    		$variation_regular_prices = array_map(function ($variation) {
                                    			$variation_obj = new WC_Product_Variation($variation['variation_id']);
                                    			$regular_price = $variation_obj->get_regular_price();
                                    			return $regular_price !== '' && $regular_price !== null ? $regular_price : null;
                                    		}, $available_variations);
                                    
                                    		$variation_regular_prices = array_filter($variation_regular_prices);
                                    
                                    		if (!empty($variation_regular_prices)) {
                                    			$min_regular_price = min($variation_regular_prices);
                                    			$max_regular_price = max($variation_regular_prices);
                                    
                                    			if ($min_price !== $min_regular_price) {
                                    				// Show sale price range
                                    							?>
                                    							<div class="shortstory__current-pirce"><?php echo wc_price($min_price); ?></div>
                                    							<div class="shortstory__old-pirce"><?php echo wc_price($min_regular_price); ?> </div>
                                    							<?php
                                    			} else {
                                    				// Show regular price range
                                    							?>
                                    							<div class="shortstory__current-pirce"><?php echo wc_price($min_price); ?> - <?php echo wc_price($max_price); ?></div>
                                    							<?php
                                    			}
                                    		}
                                    	}
                                    } else {
                                    	// For simple products
                                    	if ($product->get_sale_price()) {
                                    							?>
                                    							<div class="shortstory__current-pirce"><?php echo wc_price($product->get_sale_price()); ?></div>
                                    							<div class="shortstory__old-pirce"><?php echo wc_price($product->get_regular_price()); ?></div>
                                    							<?php
                                    	} elseif ($product->get_price() !== '' && $product->get_price() !== null) {
                                    							?>
                                    							<div class="shortstory__current-pirce"><?php echo wc_price($product->get_price()); ?></div>
                                    							<?php
                                    	}
                                    }
    							?>
    						</div>
						<?php endif; ?>
						<div class="shortstory__price-right">
							<svg width="31" height="20" viewBox="0 0 31 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M3.71429 1H19C19.5523 1 20 1.44772 20 2V2.69643M27.4643 8.46429H21C20.4477 8.46429 20 8.01657 20 7.46428V2.69643M27.4643 8.46429L28.9289 10.2219C29.0787 10.4016 29.1607 10.6281 29.1607 10.862V15.6071C29.1607 16.1594 28.713 16.6071 28.1607 16.6071H26.1071M27.4643 8.46429L24.6924 3.22854C24.5191 2.90118 24.179 2.69643 23.8086 2.69643H20M19.6607 16.6071H13.8929M7.44643 16.6071H3.71429M8.125 12.1964H2.69643M10.5 8.46429H1M11.8571 4.73214H2.69643" stroke="#E93A53" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
								<circle cx="10.5003" cy="16.6077" r="2.39286" stroke="#E93A53" stroke-width="2" />
								<circle cx="22.7141" cy="16.6077" r="2.39286" stroke="#E93A53" stroke-width="2" />
							</svg>
							<span><?php _e('Під замовлення', 'crabs_project'); ?></span>
						</div>
					</div>
					
					
					
				

					
					
<!-- sd  -->
                        <?php if($product->is_in_stock()) :?>
                        <div class="pay-line">
                           
                            <!--<a class="shortstory__btn-black go-to-cart-mirror">
                                <span>Додати в кошик</span>
                                <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </a> -->
                            
                            <?php
                                $id  = $product->get_id();
                                $sku = $product->get_sku();
                                ?>
                                <a href="<?php echo esc_url( add_query_arg( 'add-to-cart', $id ) ); ?>"
                                   class="shortstory__btn-black add_to_cart_button ajax_add_to_cart go-to-cart-mirror"
                                   data-product_id="<?php echo esc_attr( $id ); ?>"
                                   data-product_sku="<?php echo esc_attr( $sku ); ?>"
                                   data-quantity="1">
                                  <span>Додати в кошик</span>
                                <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </a>
                                
                                 <div style="display: FLEX;" class="shortstory__buttons <?php echo esc_attr($classAv); ?>">
        				    	<a style="display: FLEX;" class="shortstory__btn-red one-click_btn"><span>Купити в 1 клік</span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.5744 19.1999L12.6361 15.2616L11.4334 16.4643C10.2022 17.6955 9.58656 18.3111 8.92489 18.1658C8.26322 18.0204 7.96225 17.2035 7.3603 15.5696L5.3527 10.1205C4.15187 6.86106 3.55146 5.23136 4.39141 4.39141C5.23136 3.55146 6.86106 4.15187 10.1205 5.35271L15.5696 7.3603C17.2035 7.96225 18.0204 8.26322 18.1658 8.92489C18.3111 9.58656 17.6955 10.2022 16.4643 11.4334L15.2616 12.6361L19.1999 16.5744C19.6077 16.9821 19.8116 17.186 19.9058 17.4135C20.0314 17.7168 20.0314 18.0575 19.9058 18.3608C19.8116 18.5882 19.6077 18.7921 19.1999 19.1999C18.7921 19.6077 18.5882 19.8116 18.3608 19.9058C18.0575 20.0314 17.7168 20.0314 17.4135 19.9058C17.186 19.8116 16.9821 19.6077 16.5744 19.1999Z" stroke="#3D3D3D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
        						</a>
            					<!--<a style="display: NONE;"href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="shortstory__btn-black add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr($product->get_id()); ?>" data-product_sku="<?php echo esc_attr($product->get_sku()); ?>" aria-label="<?php echo esc_attr($product->add_to_cart_text()); ?>" rel="nofollow">
            						<span><?php _e('Додати в кошик', 'crabs_project'); ?></span>
            						<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
            							<path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            						</svg> 
            					</a>
            
            					<a style="display: NONE;" class="shortstory__btn-red" href="<?php echo esc_url(add_query_arg('add-to-cart', get_the_ID(), wc_get_checkout_url())); ?>" class="shortstory__btn-red"><?php _e('Швидка оплата', 'crabs_project'); ?>
            
            						<svg width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            							<path fill-rule="evenodd" clip-rule="evenodd" d="M8.94358 0.25H13.0564C14.8942 0.249984 16.3498 0.249972 17.489 0.403135C18.6614 0.560763 19.6104 0.89288 20.3588 1.64124C21.1071 2.38961 21.4392 3.33856 21.5969 4.51098C21.6873 5.18385 21.7244 5.9671 21.7395 6.87428C21.7464 6.91516 21.75 6.95716 21.75 7C21.75 7.03525 21.7476 7.06994 21.7429 7.1039C21.75 7.66962 21.75 8.28183 21.75 8.94359V9.05641C21.75 10.8942 21.75 12.3498 21.5969 13.489C21.4392 14.6614 21.1071 15.6104 20.3588 16.3588C19.6104 17.1071 18.6614 17.4392 17.489 17.5969C16.3498 17.75 14.8942 17.75 13.0564 17.75H8.94359C7.10583 17.75 5.65019 17.75 4.51098 17.5969C3.33856 17.4392 2.38961 17.1071 1.64124 16.3588C0.89288 15.6104 0.560763 14.6614 0.403135 13.489C0.249972 12.3498 0.249984 10.8942 0.25 9.05642V8.94358C0.249995 8.28182 0.249989 7.66961 0.257136 7.10388C0.252432 7.06993 0.250001 7.03525 0.250001 7C0.250001 6.95716 0.253592 6.91517 0.260489 6.87429C0.275642 5.96711 0.31267 5.18385 0.403135 4.51098C0.560763 3.33856 0.89288 2.38961 1.64124 1.64124C2.38961 0.89288 3.33856 0.560763 4.51098 0.403135C5.65019 0.249972 7.10582 0.249984 8.94358 0.25ZM1.75199 7.75C1.75009 8.13839 1.75 8.5541 1.75 9C1.75 10.9068 1.75159 12.2615 1.88976 13.2892C2.02502 14.2952 2.27869 14.8749 2.7019 15.2981C3.12511 15.7213 3.70476 15.975 4.71085 16.1102C5.73851 16.2484 7.09318 16.25 9 16.25H13C14.9068 16.25 16.2615 16.2484 17.2892 16.1102C18.2952 15.975 18.8749 15.7213 19.2981 15.2981C19.7213 14.8749 19.975 14.2952 20.1102 13.2892C20.2484 12.2615 20.25 10.9068 20.25 9C20.25 8.5541 20.2499 8.13839 20.248 7.75H1.75199ZM20.2239 6.25H1.77607C1.79564 5.66327 1.82987 5.15634 1.88976 4.71085C2.02502 3.70476 2.27869 3.12511 2.7019 2.7019C3.12511 2.27869 3.70476 2.02502 4.71085 1.88976C5.73851 1.75159 7.09318 1.75 9 1.75H13C14.9068 1.75 16.2615 1.75159 17.2892 1.88976C18.2952 2.02502 18.8749 2.27869 19.2981 2.7019C19.7213 3.12511 19.975 3.70476 20.1102 4.71085C20.1701 5.15634 20.2044 5.66327 20.2239 6.25ZM14.9553 9.25H15.0447C15.4776 9.24995 15.8744 9.2499 16.1972 9.2933C16.5527 9.34109 16.9284 9.45354 17.2374 9.76256C17.5465 10.0716 17.6589 10.4473 17.7067 10.8028C17.7501 11.1256 17.7501 11.5224 17.75 11.9553V12.0447C17.7501 12.4776 17.7501 12.8744 17.7067 13.1972C17.6589 13.5527 17.5465 13.9284 17.2374 14.2374C16.9284 14.5465 16.5527 14.6589 16.1972 14.7067C15.8744 14.7501 15.4776 14.7501 15.0447 14.75H14.9553C14.5224 14.7501 14.1256 14.7501 13.8028 14.7067C13.4473 14.6589 13.0716 14.5465 12.7626 14.2374C12.4535 13.9284 12.3411 13.5527 12.2933 13.1972C12.2499 12.8744 12.2499 12.4776 12.25 12.0447V11.9553C12.2499 11.5224 12.2499 11.1256 12.2933 10.8028C12.3411 10.4473 12.4535 10.0716 12.7626 9.76256C13.0716 9.45354 13.4473 9.34109 13.8028 9.2933C14.1256 9.2499 14.5224 9.24995 14.9553 9.25ZM13.8257 10.8219L13.8232 10.8232L13.8219 10.8257C13.8209 10.8276 13.8192 10.8309 13.8172 10.836C13.8082 10.8577 13.7929 10.9061 13.7799 11.0027C13.7516 11.2134 13.75 11.5074 13.75 12C13.75 12.4926 13.7516 12.7866 13.7799 12.9973C13.7929 13.0939 13.8082 13.1423 13.8172 13.164C13.818 13.1661 13.8188 13.1679 13.8195 13.1694C13.8205 13.1716 13.8213 13.1732 13.8219 13.1743L13.8232 13.1768L13.8257 13.1781C13.8276 13.1791 13.8309 13.1808 13.836 13.1828C13.8577 13.1918 13.9061 13.2071 14.0027 13.2201C14.2134 13.2484 14.5074 13.25 15 13.25C15.4926 13.25 15.7866 13.2484 15.9973 13.2201C16.0939 13.2071 16.1423 13.1918 16.164 13.1828C16.1691 13.1808 16.1724 13.1791 16.1743 13.1781L16.1768 13.1768L16.1781 13.1743C16.1791 13.1724 16.1808 13.1691 16.1828 13.164C16.1918 13.1423 16.2071 13.0939 16.2201 12.9973C16.2484 12.7866 16.25 12.4926 16.25 12C16.25 11.5074 16.2484 11.2134 16.2201 11.0027C16.2071 10.9061 16.1918 10.8577 16.1828 10.836C16.1816 10.833 16.1806 10.8307 16.1797 10.8288C16.1791 10.8275 16.1786 10.8265 16.1781 10.8257L16.1768 10.8232L16.1743 10.8219C16.1724 10.8209 16.1691 10.8192 16.164 10.8172C16.1423 10.8082 16.0939 10.7929 15.9973 10.7799C15.7866 10.7516 15.4926 10.75 15 10.75C14.5074 10.75 14.2134 10.7516 14.0027 10.7799C13.9061 10.7929 13.8577 10.8082 13.836 10.8172C13.8309 10.8192 13.8276 10.8209 13.8257 10.8219ZM4.25 10.5C4.25 10.0858 4.58579 9.75 5 9.75H7C7.41421 9.75 7.75 10.0858 7.75 10.5C7.75 10.9142 7.41421 11.25 7 11.25H5C4.58579 11.25 4.25 10.9142 4.25 10.5ZM13.8232 13.1768C13.8228 13.1764 13.823 13.1764 13.8232 13.1768C13.8236 13.177 13.8237 13.1772 13.8232 13.1768ZM16.1768 13.1768C16.1766 13.177 16.1767 13.1768 16.1768 13.1768V13.1768ZM4.25 13.5C4.25 13.0858 4.58579 12.75 5 12.75H9C9.41421 12.75 9.75 13.0858 9.75 13.5C9.75 13.9142 9.41421 14.25 9 14.25H5C4.58579 14.25 4.25 13.9142 4.25 13.5Z" fill="white" />
            						</svg>
            					</a> -->
    
    			        	</div>
                        </div>
                        <a class="mono-btn">
                            <span>Mono checkout</span>
                            <div class="cat-background"></div>
                        </a>
                        <?php else: ?>
                            <a class="shortstory__btn-black inform-when-available-btn">
                                <span>Повідомити про наявність</span>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.9993 5.08472C16.0828 5.19129 16.8121 5.43213 17.3564 5.97645C18.3327 6.95276 18.3327 8.52411 18.3327 11.6668C18.3327 14.8095 18.3327 16.3809 17.3564 17.3572C16.3801 18.3335 14.8087 18.3335 11.666 18.3335H8.33268C5.18999 18.3335 3.61864 18.3335 2.64233 17.3572C1.66602 16.3809 1.66602 14.8095 1.66602 11.6668C1.66602 8.52411 1.66602 6.95276 2.64233 5.97645C3.18664 5.43213 3.91592 5.19129 4.99935 5.08472" stroke="#F8F7F7" stroke-width="1.25"/>
                                    <path d="M8.33398 5H11.6673" stroke="#F8F7F7" stroke-width="1.25" stroke-linecap="round"/>
                                    <path d="M9.16602 7.5H10.8327" stroke="#F8F7F7" stroke-width="1.25" stroke-linecap="round"/>
                                    <path d="M6.79908 9.83259L6.19939 9.33284C5.6096 8.84135 5.3147 8.5956 5.15735 8.25965C5 7.9237 5 7.53984 5 6.7721V5.83335C5 3.86917 5 2.88708 5.61019 2.27688C6.22039 1.66669 7.20248 1.66669 9.16667 1.66669H10.8333C12.7975 1.66669 13.7796 1.66669 14.3898 2.27688C15 2.88708 15 3.86917 15 5.83335V6.7721C15 7.53984 15 7.9237 14.8426 8.25965C14.6853 8.5956 14.3904 8.84135 13.8006 9.33284L13.2009 9.83259C11.6704 11.108 10.9051 11.7457 10 11.7457C9.09488 11.7457 8.32961 11.108 6.79908 9.83259Z" stroke="#F8F7F7" stroke-width="1.25" stroke-linecap="round"/>
                                    <path d="M5 8.33331L6.79908 9.83255C8.32961 11.108 9.09488 11.7457 10 11.7457C10.9051 11.7457 11.6704 11.108 13.2009 9.83255L15 8.33331" stroke="#F8F7F7" stroke-width="1.25" stroke-linecap="round"/>
                                </svg>
                            </a>

                            <div class="availability-popup-overlay">
                                <div class="availability-popup">
                                    <button class="availability-popup-close" aria-label="Закрити">×</button>
                                    <h3>Повідомити про наявність</h3>
                                    <p class="description-text">Заповніть поле номеру телефону для щоб ми могли повідомити вам про наявність товару</p>

                                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                                        <input type="hidden" name="action" value="notify_about_availability">
                                        <input type="hidden" name="product_title" value="<?php echo get_the_title(); ?>">
                                        <input type="hidden" name="product_url" value="<?php echo get_permalink(); ?>">
                                        <input type="hidden" name="product_sku" value="<?php echo $product->get_sku(); ?>">

                                        <div class="form-group">
                                            <label for="name_field">Ваше ім'я<span class="required-asterisk">*</span></label>
                                            <input type="text" id="name_field" name="name" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email_field">Ваш e-mail<span class="required-asterisk">*</span></label>
                                            <input type="email" id="email_field" name="email" required>
                                        </div>

                                        <div class="form-group">
                                            <div class="phone-input-line">
                                                <span class="flag-icon" role="img" aria-label=" прапор України">🇺🇦</span>
                                                <span class="dropdown-arrow">▼</span>
                                                <span class="country-code">+38</span>
                                                <input type="tel" id="phone_field" name="phone" placeholder="( _ ) _ _ _" aria-label="Номер телефону" required class="phone-number-input">
                                                <span class="phone-line-asterisk">*</span>
                                            </div>
                                        </div>

                                        <div class="form-group checkbox-group">
                                            <input type="checkbox" id="privacy_policy" name="privacy_policy" required>
                                            <label for="privacy_policy">Я погоджуюсь з політикою конфіденційності</label>
                                        </div>
                                        

                                        <button class="submit-btn contact-me-form-submit-btn" type="submit">Відправити</button>
                                    </form>
                                </div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const trigger = document.querySelector('.inform-when-available-btn');
                                    const popup = document.querySelector('.availability-popup-overlay');
                                    const closeBtn = document.querySelector('.availability-popup-close');

                                    if (trigger && popup && closeBtn) {
                                        trigger.addEventListener('click', function (e) {
                                            e.preventDefault();
                                            popup.style.display = 'flex';
                                        });

                                        closeBtn.addEventListener('click', function () {
                                            popup.style.display = 'none';
                                        });
                                    }
                                });
                                
                            </script>
                        <?php endif; ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const url = new URL(window.location.href);
                            
                                if (url.searchParams.get('notify') === 'success') {
                                    const box = document.createElement('div');
                                    box.className = 'availability-success-box';
                                    box.innerText = 'Дякуємо, що залишили заявку! Ми скоро зв\'яжемось з вами!';
                                    document.body.appendChild(box);
                            
                                    setTimeout(() => {
                                        box.classList.add('hide');
                                    }, 3000);
                            
                                    setTimeout(() => {
                                        box.remove();
                                    }, 4500);
                                }
                            });
                        </script>
                            <style>
                                .availability-popup-overlay {
                                    position: fixed;
                                    top: 0;
                                    left: 0;
                                    right: 0;
                                    bottom: 0;
                                    background: rgba(0, 0, 0, 0.5);
                                    display: none;
                                    justify-content: center;
                                    align-items: center;
                                    z-index: 9999;
                                }

                                .availability-popup {
                                    background: white;
                                    padding: 20px;
                                    max-width: 400px;
                                    width: 100%;
                                    border-radius: 8px;
                                    position: relative;
                                }

                                .availability-popup-close {
                                    position: absolute;
                                    right: 10px;
                                    top: 10px;
                                    background: none;
                                    border: none;
                                    font-size: 24px;
                                    cursor: pointer;
                                }
                            </style>
				</div>

				<?php if (!empty($colors) && !empty($collections)) : ?>
				<!-- shortstory-attrs -->
				<div class="shortstory-attrs">
					<?php if (!empty($colors)) { ?>
					<div id="color-attributes" class="shortstory__colection">
						<h3><?php _e('Кольори в наявності', 'crabs_project'); ?></h3>
						<div class="shortstory__row">
							<?php foreach ($colors as $color) : ?>
							<a href="##" class="shortstory__card" data-attribute-slug="<?php echo esc_attr($color->slug); ?>">
								<div class="shortstory__image">
									<?php
												$thumbnail_id = get_term_meta($color->term_id, 'attribute_image_id', true);
												if ($thumbnail_id) {
													echo wp_get_attachment_image($thumbnail_id, 'full');
												} else {
													echo '<img src="' . get_stylesheet_directory_uri() . '/img/default-attribute.jpg" alt="default image" />';
												}
									?>
									<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
										<circle cx="11" cy="11" r="11" fill="#E93A53" />
										<path d="M17 7L9.43756 15L6 11.3637" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
									</svg>
								</div>
								<h4><?php echo esc_html($color->name); ?></h4>
							</a>
							<?php endforeach; ?>
						</div>
					</div>
					<?php } ?>
					<?php if (!empty($collections)) { ?>
					<div id="collection-attributes" class="shortstory__colection">
						<h3><?php _e('Колекція', 'crabs_project'); ?></h3>
						<div class="shortstory__row">
							<?php foreach ($collections as $collection) : ?>
							<a href="##" class="shortstory__card" data-attribute-slug="<?php echo esc_attr($collection->slug); ?>">
								<div class="shortstory__image">
									<?php
													 $thumbnail_id = get_term_meta($collection->term_id, 'attribute_image_id', true);
													 if ($thumbnail_id) {
														 echo wp_get_attachment_image($thumbnail_id, 'full');
													 } else {
														 echo '<img src="' . get_stylesheet_directory_uri() . '/img/default-attribute.jpg" alt="default image" />';
													 }
									?>
									<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
										<circle cx="11" cy="11" r="11" fill="#E93A53" />
										<path d="M17 7L9.43756 15L6 11.3637" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
									</svg>
								</div>
								<h4><?php echo esc_html($collection->name); ?></h4>
							</a>
							<?php endforeach; ?>
						</div>
					</div>
					<?php } ?>

					<div id="variation-area">

						<?php global $product;
						if ($product && $product->is_type('variable')) {
							// Выводим стандартную форму вариаций WooCommerce
							woocommerce_variable_add_to_cart();
						} ?>
					</div>



					<!-- shortstory-attrs - 1 -->

					<div style="display: none;" class="shortstory__buttons <?php echo esc_attr($classAv); ?>">
						<a class="btn-black call-back-call"><span>Швидке замовлення</span></a>

						<a href="#" class="shortstory__btn-black attrs" data-product_id="<?php echo esc_attr($product->get_id()); ?>" data-product_sku="<?php echo esc_attr($product->get_sku()); ?>" data-url="<?php echo esc_url($product->add_to_cart_url()); ?>" data-add-classes="add_to_cart_button ajax_add_to_cart" aria-label="<?php echo esc_attr($product->add_to_cart_text()); ?>" rel="nofollow" data-color="" data-collection="">
							<span><?php _e('Додати в кошик', 'crabs_project'); ?></span>
							<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</a>

						<a class="shortstory__btn-red" href="<?php echo esc_url(add_query_arg('add-to-cart', get_the_ID(), wc_get_checkout_url())); ?>" class="shortstory__btn-red"><?php _e('Швидка оплата', 'crabs_project'); ?>

							<svg width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M8.94358 0.25H13.0564C14.8942 0.249984 16.3498 0.249972 17.489 0.403135C18.6614 0.560763 19.6104 0.89288 20.3588 1.64124C21.1071 2.38961 21.4392 3.33856 21.5969 4.51098C21.6873 5.18385 21.7244 5.9671 21.7395 6.87428C21.7464 6.91516 21.75 6.95716 21.75 7C21.75 7.03525 21.7476 7.06994 21.7429 7.1039C21.75 7.66962 21.75 8.28183 21.75 8.94359V9.05641C21.75 10.8942 21.75 12.3498 21.5969 13.489C21.4392 14.6614 21.1071 15.6104 20.3588 16.3588C19.6104 17.1071 18.6614 17.4392 17.489 17.5969C16.3498 17.75 14.8942 17.75 13.0564 17.75H8.94359C7.10583 17.75 5.65019 17.75 4.51098 17.5969C3.33856 17.4392 2.38961 17.1071 1.64124 16.3588C0.89288 15.6104 0.560763 14.6614 0.403135 13.489C0.249972 12.3498 0.249984 10.8942 0.25 9.05642V8.94358C0.249995 8.28182 0.249989 7.66961 0.257136 7.10388C0.252432 7.06993 0.250001 7.03525 0.250001 7C0.250001 6.95716 0.253592 6.91517 0.260489 6.87429C0.275642 5.96711 0.31267 5.18385 0.403135 4.51098C0.560763 3.33856 0.89288 2.38961 1.64124 1.64124C2.38961 0.89288 3.33856 0.560763 4.51098 0.403135C5.65019 0.249972 7.10582 0.249984 8.94358 0.25ZM1.75199 7.75C1.75009 8.13839 1.75 8.5541 1.75 9C1.75 10.9068 1.75159 12.2615 1.88976 13.2892C2.02502 14.2952 2.27869 14.8749 2.7019 15.2981C3.12511 15.7213 3.70476 15.975 4.71085 16.1102C5.73851 16.2484 7.09318 16.25 9 16.25H13C14.9068 16.25 16.2615 16.2484 17.2892 16.1102C18.2952 15.975 18.8749 15.7213 19.2981 15.2981C19.7213 14.8749 19.975 14.2952 20.1102 13.2892C20.2484 12.2615 20.25 10.9068 20.25 9C20.25 8.5541 20.2499 8.13839 20.248 7.75H1.75199ZM20.2239 6.25H1.77607C1.79564 5.66327 1.82987 5.15634 1.88976 4.71085C2.02502 3.70476 2.27869 3.12511 2.7019 2.7019C3.12511 2.27869 3.70476 2.02502 4.71085 1.88976C5.73851 1.75159 7.09318 1.75 9 1.75H13C14.9068 1.75 16.2615 1.75159 17.2892 1.88976C18.2952 2.02502 18.8749 2.27869 19.2981 2.7019C19.7213 3.12511 19.975 3.70476 20.1102 4.71085C20.1701 5.15634 20.2044 5.66327 20.2239 6.25ZM14.9553 9.25H15.0447C15.4776 9.24995 15.8744 9.2499 16.1972 9.2933C16.5527 9.34109 16.9284 9.45354 17.2374 9.76256C17.5465 10.0716 17.6589 10.4473 17.7067 10.8028C17.7501 11.1256 17.7501 11.5224 17.75 11.9553V12.0447C17.7501 12.4776 17.7501 12.8744 17.7067 13.1972C17.6589 13.5527 17.5465 13.9284 17.2374 14.2374C16.9284 14.5465 16.5527 14.6589 16.1972 14.7067C15.8744 14.7501 15.4776 14.7501 15.0447 14.75H14.9553C14.5224 14.7501 14.1256 14.7501 13.8028 14.7067C13.4473 14.6589 13.0716 14.5465 12.7626 14.2374C12.4535 13.9284 12.3411 13.5527 12.2933 13.1972C12.2499 12.8744 12.2499 12.4776 12.25 12.0447V11.9553C12.2499 11.5224 12.2499 11.1256 12.2933 10.8028C12.3411 10.4473 12.4535 10.0716 12.7626 9.76256C13.0716 9.45354 13.4473 9.34109 13.8028 9.2933C14.1256 9.2499 14.5224 9.24995 14.9553 9.25ZM13.8257 10.8219L13.8232 10.8232L13.8219 10.8257C13.8209 10.8276 13.8192 10.8309 13.8172 10.836C13.8082 10.8577 13.7929 10.9061 13.7799 11.0027C13.7516 11.2134 13.75 11.5074 13.75 12C13.75 12.4926 13.7516 12.7866 13.7799 12.9973C13.7929 13.0939 13.8082 13.1423 13.8172 13.164C13.818 13.1661 13.8188 13.1679 13.8195 13.1694C13.8205 13.1716 13.8213 13.1732 13.8219 13.1743L13.8232 13.1768L13.8257 13.1781C13.8276 13.1791 13.8309 13.1808 13.836 13.1828C13.8577 13.1918 13.9061 13.2071 14.0027 13.2201C14.2134 13.2484 14.5074 13.25 15 13.25C15.4926 13.25 15.7866 13.2484 15.9973 13.2201C16.0939 13.2071 16.1423 13.1918 16.164 13.1828C16.1691 13.1808 16.1724 13.1791 16.1743 13.1781L16.1768 13.1768L16.1781 13.1743C16.1791 13.1724 16.1808 13.1691 16.1828 13.164C16.1918 13.1423 16.2071 13.0939 16.2201 12.9973C16.2484 12.7866 16.25 12.4926 16.25 12C16.25 11.5074 16.2484 11.2134 16.2201 11.0027C16.2071 10.9061 16.1918 10.8577 16.1828 10.836C16.1816 10.833 16.1806 10.8307 16.1797 10.8288C16.1791 10.8275 16.1786 10.8265 16.1781 10.8257L16.1768 10.8232L16.1743 10.8219C16.1724 10.8209 16.1691 10.8192 16.164 10.8172C16.1423 10.8082 16.0939 10.7929 15.9973 10.7799C15.7866 10.7516 15.4926 10.75 15 10.75C14.5074 10.75 14.2134 10.7516 14.0027 10.7799C13.9061 10.7929 13.8577 10.8082 13.836 10.8172C13.8309 10.8192 13.8276 10.8209 13.8257 10.8219ZM4.25 10.5C4.25 10.0858 4.58579 9.75 5 9.75H7C7.41421 9.75 7.75 10.0858 7.75 10.5C7.75 10.9142 7.41421 11.25 7 11.25H5C4.58579 11.25 4.25 10.9142 4.25 10.5ZM13.8232 13.1768C13.8228 13.1764 13.823 13.1764 13.8232 13.1768C13.8236 13.177 13.8237 13.1772 13.8232 13.1768ZM16.1768 13.1768C16.1766 13.177 16.1767 13.1768 16.1768 13.1768V13.1768ZM4.25 13.5C4.25 13.0858 4.58579 12.75 5 12.75H9C9.41421 12.75 9.75 13.0858 9.75 13.5C9.75 13.9142 9.41421 14.25 9 14.25H5C4.58579 14.25 4.25 13.9142 4.25 13.5Z" fill="white" />
							</svg>
						</a>
					</div>
					<!-- shortstory-attrs - 1 -->


				</div>


				<?php else: ?>

				<!-- without atrrs - 2 -->
			 <!-- without atrrs - 2 -->

				<?php endif; ?>
				
				<?php
                    // ... в середині price.php після відображення ціни:
                    global $product;
                    $price = wc_get_price_to_display( $product );
                    if ( $price > 0 ) :
                        $bonus = (int) round( $price * 0.01 );
                    ?>
                <div class="crabs-bonus" id="crabs-bonus" data-base-price="<?php echo esc_attr( $price ); ?>">
                     <span class="crabs-bonus__icon" aria-hidden="true">
                    <img loading="lazy" src="/wp-content/uploads/2025/10/Krab.webp" alt="краби" width="56" height="41">
                     </span>
                   <span class="crabs-bonus__text">
                     + <b class="crabs-bonus__value"><?php echo esc_html( $bonus ); ?></b> крабів за покупку
                     </span>
                    <button type="button" class="crabs-bonus__hint" aria-label="Що це?">i</button>
                    <div class="crabs-bonus_popup" aria-hidden="true">
                          <div class="crabs-bonus_popup__inner">
                              <div class="crabs-bonus_popup__icon">
                                  <img src="/wp-content/uploads/2025/10/Krab.webp" alt="Crabs bonus icon">
                            </div>
                            <p>Як ви можете використати бонуси?<br><strong>1 краб = 1 грн</strong></p>
                          </div>
                    </div>
                </div>
                <?php endif; ?>

				<div class="advantage">
    					<div class="showroom-group">
                         <div class="delivery-later">
    						<div class="later-images">
    							<img loading="lazy" src="/wp-content/uploads/2025/10/Group-860.webp" alt="доставка" width="40" height="27">
    						</div>
    						 	<span class="delivery-later-text">Безкоштовна доставка</span>
    					</div>
                        
                        
                        <div class="baby-later">
    						<div class="later-images">
    							<img loading="lazy" src="/wp-content/uploads/2025/10/Group-863.webp" alt="пакунок малюка" width="29" height="27">
    						</div>
    						 	<span class="baby-later-text">Оплата пакунком малюка</span>
    					</div>
                        
                        
                        <div class="mama-later">
    				    
                            <div class="later-images">
    							<img loading="lazy" src="/wp-content/uploads/2025/10/Vector-317.webp" alt="бронювання" width="29" height="34">
    						</div>
    						<a href="##" class="mama-later-text shortstory__check before-birth open-before-birth-popup">Бронювання до пологів </a>
                            
                       
                        </div>
    
    					
    
                        <a href="##" class="shortstory__check before-birth-web-screen open-before-birth-popup">
                            <svg width="19" height="23" viewBox="0 0 19 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 9.51782V3.48C1 3.48 2.85455 3.55891 5.25 2.86C7.64545 2.16109 9.5 1 9.5 1C9.5 1 11.0938 2.085 13.75 2.86C16.4062 3.635 18 3.48 18 3.48V10.1189C18 12.7128 17.751 15.3879 16.1266 17.4102C15.7298 17.9042 15.2519 18.4322 14.6773 18.98C12.2385 21.305 9.5 22.08 9.5 22.08C9.5 22.08 6.56364 21.1164 4.32273 18.98C3.92664 18.6024 3.57584 18.2312 3.26549 17.8722C1.3092 15.6097 1 12.5088 1 9.51782Z" stroke="#E93A53" stroke-width="1.5"/>
                                <path d="M3.71973 9.12831V6.08002C3.71973 6.08002 4.98082 6.13348 6.60973 5.66002C8.23864 5.18657 9.49973 4.40002 9.49973 4.40002V18.68C9.49973 18.68 7.503 18.0272 5.97918 16.58C4.00819 14.7081 3.71973 11.8465 3.71973 9.12831Z" stroke="#E93A53" stroke-width="1.5"/>
                            </svg>
                            <span>Забронювати до пологів</span>
                        </a>
    				</div>
    				<div class="viza-group">
    				    <div class="viza-image-block">
    							<img loading="lazy" src="/wp-content/uploads/2025/09/Visa_Logo.webp" alt="карта оплати">
    					</div>
    					<div class="viza-image-block">
    							<img loading="lazy" src="/wp-content/uploads/2025/09/732ee0f50e080402fa77a45888cf5466.webp" alt="карта оплати">
    					</div>
    					<div class="viza-image-block">
    							<img loading="lazy" src="/wp-content/uploads/2025/09/MasterCard_Logo.svg.webp" alt="карта оплати">
    					</div>
    					 <div class="viza-image-block">
    							<img loading="lazy" src="/wp-content/uploads/2025/10/Plata-by-mono.webp" alt="карта оплати">
    					</div>
    					<!--<div class="viza-image-block">-->
    					<!--		<img loading="lazy" src="/wp-content/uploads/2025/10/Apple_Pay.webp" alt="карта оплати">-->
    					<!--</div>-->
    					
    					<div class="viza-image-block">
    							<img loading="lazy" src="/wp-content/uploads/2025/10/Lapka-ta-dfihrama.webp" alt="карта оплати">
    					</div>
    					
    				</div>
			    </div>
			    
			    
			    <!-- dd -->

			</div>

            <div class="before-birth-popup-overlay">
                <div class="before-birth-popup">
                    <button class="before-birth-popup-close" aria-label="Закрити">×</button>
                    <h3 class="before-birth-popup-header-text"><strong>Що таке бронювання товару до пологів?</strong></h3>
                    <p class="before-birth-popup-description-text">
                        Це зручна послуга, яка дозволяє вам заздалегідь зарезервувати необхідні – наприклад, дитячий візочок, ліжечко чи автокрісло – ще до народження малюка.
                    </p>
                    <p class="before-birth-popup-description-text">
                        Ви сплачуєте лише 10% від вартості замовлення, а решту суми зможете доплатити протягом 2 місяців. Ми гарантуємо наявність товару та зберігаємо його для вас, щоб ви могли підготуватися до появи дитини без зайвого стресу й поспіху.
                    </p>
                </div>
            </div>
			<!-- Short Story -->
            <!-- consultation -->
            <div class="consultation">
                <h2>Потрібна допомога експерта?</h2>
                <div class="consultation-actions">
                    <a class="call-back-call" href="##">Замовити консультацію</a>
                    <span class="or-text">або</span>
                    <div class="social-icons">
                        <a href="##" aria-label="Зв'язатися через Viber">
                            <svg width="17" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.2834 3.81139C15.0025 2.80956 14.447 2.01647 13.6319 1.45547C12.6033 0.746843 11.4202 0.495527 10.3985 0.343089C8.98466 0.132286 7.70404 0.102759 6.48454 0.251764C5.34057 0.391842 4.47951 0.615692 3.69328 0.976873C2.15311 1.68413 1.22888 2.82947 0.94666 4.37994C0.809328 5.13252 0.717317 5.81299 0.663758 6.46188C0.540159 7.96223 0.652084 9.29022 1.0064 10.5207C1.3511 11.7203 1.95398 12.5779 2.84938 13.141C3.07804 13.2845 3.36918 13.3882 3.65209 13.4884C3.79354 13.5386 3.92949 13.588 4.04829 13.6402V16.0641C4.04829 16.3813 4.3051 16.6381 4.62233 16.6381C4.77202 16.6381 4.91553 16.5798 5.02265 16.4754L7.25909 14.3001C7.3566 14.1888 7.3566 14.1888 7.45685 14.1868C8.2211 14.1717 9.00183 14.1421 9.77775 14.1002C10.7178 14.0487 11.8068 13.9581 12.8327 13.5303C13.7713 13.1382 14.4566 12.5161 14.8679 11.6818C15.2971 10.8112 15.5525 9.8677 15.648 8.79857C15.8155 6.91851 15.696 5.28701 15.2834 3.81139ZM11.3893 10.9663C11.1648 11.3337 10.8283 11.5878 10.4328 11.7526C10.1437 11.8734 9.84847 11.848 9.56351 11.7272C7.1712 10.715 5.29594 9.11924 4.05584 6.8265C3.8004 6.35408 3.62256 5.83977 3.41862 5.34057C3.37674 5.23758 3.38017 5.11741 3.36163 5.00549C3.37948 4.19866 3.99816 3.74478 4.62302 3.60745C4.86197 3.55458 5.07346 3.63904 5.25062 3.80864C5.74089 4.27694 6.12886 4.8194 6.42068 5.42778C6.5484 5.69489 6.49072 5.9311 6.27305 6.12954C6.22842 6.17074 6.18104 6.20919 6.13229 6.24559C5.63584 6.61913 5.56305 6.90134 5.82741 7.46371C6.27786 8.42023 7.02563 9.06225 7.99313 9.46051C8.24788 9.56557 8.48821 9.51338 8.68322 9.3067C8.70931 9.27923 8.73884 9.25245 8.75806 9.22087C9.13984 8.58434 9.6926 8.64751 10.2035 9.01075C10.5392 9.24902 10.8647 9.50034 11.1971 9.74341C11.7011 10.1149 11.697 10.4637 11.3893 10.9663ZM8.51911 4.37651C8.3907 4.37651 8.26161 4.38681 8.13526 4.40809C7.92103 4.44449 7.71915 4.2996 7.68344 4.08537C7.64774 3.87182 7.79194 3.66925 8.00617 3.63355C8.1744 3.60608 8.34744 3.59166 8.51911 3.59166C10.2186 3.59166 11.6015 4.97459 11.6015 6.67406C11.6015 6.84641 11.5871 7.01945 11.5589 7.18768C11.5267 7.37926 11.3605 7.51521 11.1723 7.51521C11.1511 7.51521 11.1291 7.51384 11.1071 7.50972C10.8936 7.47333 10.7494 7.27145 10.7851 7.0579C10.8064 6.93293 10.8167 6.80384 10.8167 6.67475C10.8167 5.40718 9.78599 4.37651 8.51911 4.37651ZM10.2282 6.92675C10.2282 7.14305 10.0524 7.31883 9.83611 7.31883C9.61982 7.31883 9.44403 7.14305 9.44403 6.92675C9.44403 6.27786 8.91599 5.74982 8.2671 5.74982C8.05081 5.74982 7.87502 5.57404 7.87502 5.35774C7.87502 5.14144 8.05081 4.96566 8.2671 4.96566C9.3479 4.96497 10.2282 5.84527 10.2282 6.92675ZM12.8691 7.40535C12.8279 7.588 12.6665 7.71091 12.4866 7.71091C12.4578 7.71091 12.4282 7.70748 12.3994 7.7013C12.1879 7.65323 12.0554 7.44312 12.1035 7.23231C12.1604 6.98168 12.1893 6.72281 12.1893 6.46326C12.1893 4.55573 10.6374 3.0032 8.72922 3.0032C8.46898 3.0032 8.2108 3.03204 7.96017 3.08903C7.75005 3.13778 7.53856 3.00457 7.49118 2.79308C7.44312 2.58159 7.57564 2.37147 7.78713 2.32409C8.09475 2.25337 8.41199 2.21835 8.7306 2.21835C11.0707 2.21835 12.9748 4.12245 12.9748 6.46257C12.9748 6.78118 12.9391 7.09841 12.8691 7.40535Z" fill="white"/>
                            </svg>
                        </a>
                        <a href="##" aria-label="Зв'язатися через Telegram">
                            <svg width="16" height="16" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.54572 11.9793L6.77747 8.47818L13.1341 2.75057C13.4155 2.49399 13.0762 2.36983 12.7037 2.59331L4.85723 7.55117L1.46371 6.47517C0.735338 6.26825 0.727061 5.76336 1.62924 5.39918L14.8474 0.30061C15.4517 0.0274728 16.031 0.449595 15.7993 1.37661L13.548 11.9793C13.3907 12.7325 12.9355 12.9146 12.3064 12.567L8.8798 10.0342L7.2327 11.6317C7.04233 11.822 6.88507 11.9793 6.54572 11.9793Z" fill="white"/>
                            </svg>

                        </a>
                    </div>
                </div>
            </div>
            <!-- consultation -->

			<!-- Cross Sell Products -->
			<?php


			$upsell_ids = $product->get_upsell_ids();

			if (!empty($upsell_ids)) {
				$args = array(
					'post_type' => 'product',
					'post__in' => $upsell_ids,
					'posts_per_page' => -1,
                    'meta_query'     => array(
                        array(
                            'key'     => '_stock_status',
                            'value'   => 'instock',
                            'compare' => '='
                        )
                    )
				);

				$cross_sell_products = new WP_Query($args);


				if ($cross_sell_products->have_posts()) { ?>

<!--			<div class="relate">-->
<!--				<h2>Разом з товаром купують</h2>-->
<!--				<div class="relate-block">-->
<!--					<div class="relate-slider__wrapper">-->
<!--						--><?php //while ($cross_sell_products->have_posts()) : $cross_sell_products->the_post();
//														 $product = wc_get_product(get_the_ID()); ?>
<!--						<section class="related-slide">-->
<!--							<div class="relate-slider__image">-->
<!--								<a href="--><?php //the_permalink(); ?><!--">--><?php //the_post_thumbnail('full'); ?><!--</a>-->
<!---->
<!--								<div class="relate-slider__icons">-->
<!---->
<!--									--><?php
//														 $classAv = $cross_sell_products->is_in_stock() ? 'tag-available' : 'tag-order';
//														 $textAv = $cross_sell_products->is_in_stock() ? 'В наявності' : 'Під замовлення';
//									?>
<!---->
<!--									<div class="relate-slider__tag tag-order --><?php //echo esc_attr($classAv); ?><!--">-->
<!--										<span>--><?php //echo esc_html($textAv); ?><!--</span>-->
<!--									</div>-->
<!---->
<!--									<div class="relate-slider__icon-right">-->
<!--										<img src="--><?php //echo get_stylesheet_directory_uri(); ?><!--/img/percentage.png" alt="percentage"  width="35" height="35">-->
<!--										<img src="--><?php //echo get_stylesheet_directory_uri(); ?><!--/img/paw.png" alt="paw"  width="35" height="35">-->
<!--									</div>-->
<!--									<button href="#" class="relate-slider__icon-heart wishlist-icon --><?php //echo wooeshop_in_wishlist2($product->get_id()) ? 'in-wishlist' : '' ?><!--" data-id="--><?php //echo $product->get_id(); ?><!--">-->
<!--										<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--											<rect width="26" height="26" rx="4" fill="white" />-->
<!--											<path d="M7.01792 13.0342L12.881 18.6673L18.744 13.0342C19.3957 12.408 19.7619 11.5587 19.7619 10.6731C19.7619 8.82896 18.2059 7.33398 16.2865 7.33398C15.3648 7.33398 14.4808 7.68578 13.829 8.31199L12.881 9.22287L11.9329 8.31199C11.2811 7.68578 10.3971 7.33398 9.47541 7.33398C7.55599 7.33398 6 8.82896 6 10.6731C6 11.5587 6.36616 12.408 7.01792 13.0342Z" stroke="#E93A53" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />-->
<!--										</svg>-->
<!--									</button>-->
<!--								</div>-->
<!--							</div>-->
<!---->
<!--							<div class="relate-slider__footer-card">-->
<!---->
<!--								<div class="relate-slider__rating">-->
<!--									<div class="relate-slider__stars">-->
<!--										--><?php
//														 $average_rating = get_custom_product_average_rating($post->ID);
//														 $ratings_count = get_ratings_count($post->ID);
//										?>
<!--										--><?php //for ($i = 1; $i <= 5; $i++) : ?>
<!--										<svg class="star" width="36" height="33" viewBox="0 0 36 33" xmlns="http://www.w3.org/2000/svg">-->
<!--											<path fill="--><?php //echo ($i <= $average_rating) ? '#F4A804' : 'grey'; ?><!--" d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z" />-->
<!--										</svg>-->
<!--										--><?php //endfor; ?>
<!--									</div>-->
<!--									<span>--><?php //echo number_format($average_rating, 1); ?><!--</span>-->
<!--								</div>-->
<!---->
<!--								<div class="relate-slider__bottom-card">-->
<!--									<h3 class="relate-slider__title">--><?php //the_title(); ?><!--</h3>-->
<!--									<div class="relate-slider__prices">-->
<!--										--><?php //if ($product->is_type('variable')) {
//											// Get the available variations
//											$available_variations = $product->get_available_variations();
//											$variation_prices = array();
//
//											foreach ($available_variations as $variation) {
//												$variation_obj = new WC_Product_Variation($variation['variation_id']);
//												$variation_prices[] = $variation_obj->get_price();
//											}
//
//											// Get the minimum and maximum prices from the variations
//											if (!empty($variation_prices)) {
//												$min_price = min($variation_prices);
//												$max_price = max($variation_prices);
//											}
//
//											// Get the minimum and maximum regular prices from the variations
//											$variation_regular_prices = array_map(function ($variation) {
//												$variation_obj = new WC_Product_Variation($variation['variation_id']);
//												return $variation_obj->get_regular_price();
//											}, $available_variations);
//
//											if (!empty($variation_regular_prices)) {
//												$min_regular_price = min($variation_regular_prices);
//												$max_regular_price = max($variation_regular_prices);
//											}
//
//											if ($min_price !== $min_regular_price) {
//												// Show sale price range
//										?>
<!--										<div class="relate-slider__current-pirce">--><?php //echo wc_price($min_price); ?><!--</div>-->
<!--										<div class="relate-slider__old-pirce">--><?php //echo wc_price($min_regular_price); ?><!-- </div>-->
<!--										--><?php
//											} else {
//												// Show regular price range
//										?>
<!--										<div class="relate-slider__current-pirce">--><?php //echo wc_price($min_price); ?><!-- - --><?php //echo wc_price($max_price); ?><!--</div>-->
<!--										--><?php
//											}
//										} else {
//											// For simple products
//											if ($product->get_sale_price()) {
//										?>
<!--										<div class="relate-slider__current-pirce">--><?php //echo wc_price($product->get_sale_price()); ?><!--</div>-->
<!--										<div class="relate-slider__old-pirce">--><?php //echo wc_price($product->get_regular_price()); ?><!--</div>-->
<!--										--><?php
//											} else {
//										?>
<!--										<div class="relate-slider__current-pirce">--><?php //echo wc_price($product->get_price()); ?><!--</div>-->
<!--										--><?php
//											}
//										}
//										?>
<!---->
<!--									</div>-->
<!---->
<!--								</div>-->
<!--								<a href="--><?php //echo esc_url($product->add_to_cart_url()); ?><!--" class="btn-red add_to_cart_button ajax_add_to_cart" data-product_id="--><?php //echo esc_attr($product->get_id()); ?><!--" data-product_sku="--><?php //echo esc_attr($product->get_sku()); ?><!--" aria-label="--><?php //echo esc_attr($product->add_to_cart_text()); ?><!--" rel="nofollow">-->
<!--									<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--										<path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />-->
<!--									</svg>-->
<!--								</a>-->
<!---->
<!--							</div>-->
<!---->
<!--						</section>-->
<!--						--><?php //endwhile; ?>
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
			<?php
														 wp_reset_postdata();
														}
			}
			?>

		</div> <!-- card-main__column -->
		<!-- Right Col -->

	</div> <!-- card-main container -->

	<?php
	$payment_content = '<strong>Оплата при отриманні</strong><br>'
		. 'Ви можете оплатити замовлення у відділенні Нової Пошти після огляду товару.<br><br>'
		. '<strong>Онлайн-оплата</strong><br>'
		. 'Оплатити замовлення можна онлайн через:'
		. '<ul>'
		. '<li>Monobank (Visa, Mastercard)</li>'
		. '<li>Apple Pay</li>'
		. '<li>Google Pay</li>'
		. '<li>Оплата частинами ПриватБанк</li>'
		. '<li>Оплата частинами Monobank</li>'
		. '<li>Оплата частинами від А-Банку</li>'
		. '<li>Оплата частинами від Глобус-Банку</li>'
		. '</ul>'
		. 'Оплата проходить швидко та безпечно, без додаткових комісій.<br><br>'
		. '<strong>Оплата на рахунок ФОП</strong><br>'
		. 'За потреби ви можете оплатити замовлення за реквізитами. Усі дані ми надаємо після оформлення замовлення.<br><br>'
		. '<strong>Готівкою</strong><br>'
		. 'Готівковий розрахунок доступний при самовивезенні в Івано-Франківську.';

	$shipping_content = '<strong>Нова Пошта</strong><br>'
		. 'Доставляємо замовлення по всій Україні службою Нова Пошта.'
		. '<ul>'
		. '<li>Вартість доставки розраховується за тарифами перевізника та залежить від ваги посилки, габаритів і відстані доставки.</li>'
		. '<li>У межах населеного пункту — від 60 ₴.</li>'
		. '<li>По Україні — від 70 ₴.</li>'
		. '<li>Доплата за отримання у поштоматі — +10 ₴.</li>'
		. '<li>Доплата за габарити: понад 120 см або відсутність коробки — +100 ₴.</li>'
		. '<li>Доплата за габарити понад 30 кг — +1 ₴ / кг.</li>'
		. '<li>При замовленні від 5000 грн доставка безкоштовна.</li>'
		. '<li>Термін доставки 1-2 дні.</li>'
		. '</ul>'
		. '<strong>Курʼєрська доставка</strong>'
		. '<ul>'
		. '<li>За потреби можемо доставити замовлення курʼєром Нової Пошти прямо до дверей.</li>'
		. '<li>Доплата за доставку чи забір курʼєром посилок і документів становитиме — +50 ₴.</li>'
		. '<li>Курʼєрська доставка також безкоштовна при замовленні від 5000 грн.</li>'
		. '</ul>'
		. '<strong>Самовивіз</strong><br>'
		. 'Якщо вам зручно забрати покупку особисто, ви можете скористатися самовивозом з нашого шоуруму: '
		. 'м. Івано-Франківськ, вул. Вовчинецька, 227.<br><br>'
		. '<strong>Термін обробки замовлень</strong><br>'
		. 'Заявки опрацьовуються у робочий час:<br>'
		. 'ПН-ПТ з 10:00 до 19:00<br>'
		. 'СБ з 11:00 до 18:00<br>'
		. 'Неділя - вихідний';
	?>

	<div class="product-footer container">
		<div class="product-tabs">
			<div class="tab-headers">
				<div class="tab active" data-tab="description"><?php _e('Опис', 'crabs_project') ?></div>
				<div class="tab" data-tab="booking">Бронювання до пологів</div>
				<div class="tab" data-tab="reviews">Відгуки</div>
				<div class="tab" data-tab="payment">Оплата і доставка</div>
                <?php if ( $has_visible_attributes ) : ?>
                    <div class="tab" data-tab="characteristics">Характеристики</div>
                <?php endif; ?>
			</div>

			<div class="tab-content navigation-scroll-desc active" id="description">
				<div class="description-card-body">
					<div class="prod-info-head description-head  mob">Опис</div>
					<?php the_content(); ?>
				</div>
			</div>

            <?php if($has_visible_attributes) : ?>
			<div class="tab-content navigation-scroll-char" id="characteristics">
				<div class="prod-info-head characteristics-head  mob">Характериистики</div>
				<div class="characteristics__body">
					<?php
					global $product;
					$attributes = $product->get_attributes();
					$counter = 0;

					foreach ($attributes as $attribute) {
						if ($attribute->get_visible()) {
							$name = wc_attribute_label($attribute->get_name());
							$values = array();

							if ($attribute->is_taxonomy()) {
								$attribute_values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'all'));
								foreach ($attribute_values as $attribute_value) {
									$values[] = esc_html($attribute_value->name);
								}
							} else {
								$values = $attribute->get_options();
							}

							$value = join(', ', $values);
							$card_class = (floor($counter / 2) % 2 == 0) ? 'characteristics__card card-wh' : 'characteristics__card';
					?>
					<div class="<?php echo $card_class; ?>">
						<h3><?php echo esc_html($name); ?></h3>
						<span><?php echo $value ? esc_html($value) : 'Немає даних'; ?></span>
					</div>
					<?php
							$counter++;
						}
					}
					?>
				</div>
			</div>
            <?php endif; ?>




			<div class="tab-content pc" id="reviews">
				<!-- reviews -->
				<div class="reviews">
					<div class="card__header">
						<h2>Відгуки</h2>
						<a href="/vidhuky/" class="btn-black">Всі відгуки</a>
					</div>

					<article class="reviews-slider">
						<div class="reviews-slider__gallary">
							<div class="reviews-slider__wrapper swiper-wrapper">

								<?php echo do_shortcode('[product_reviews]'); ?>

							</div>
						</div>
						<div class="reviews-slider__buttons">
							<div class="reviews-btn-prev btn-swiper">
								<svg
									 width="33"
									 height="33"
									 viewBox="0 0 33 33"
									 fill="none"
									 xmlns="http://www.w3.org/2000/svg">
									<rect
										  x="0.75"
										  y="0.75"
										  width="30.5"
										  height="30.5"
										  rx="4.25"
										  stroke="#242424"
										  stroke-width="1.5" />
									<path
										  d="M16.9941 19.2012L13.6 15.807L16.9941 12.4129"
										  stroke="#242424"
										  stroke-width="1.5"
										  stroke-linecap="round"
										  stroke-linejoin="round" />
								</svg>
							</div>
							<div class="reviews-btn-next btn-swiper">
								<svg
									 width="33"
									 height="33"
									 viewBox="0 0 33 33"
									 fill="none"
									 xmlns="http://www.w3.org/2000/svg">
									<rect
										  x="31.25"
										  y="31.25"
										  width="30.5"
										  height="30.5"
										  rx="4.25"
										  transform="rotate(180 31.25 31.25)"
										  stroke="#242424"
										  stroke-width="1.5" />
									<path
										  d="M15.0059 12.7988L18.4 16.193L15.0059 19.5871"
										  stroke="#242424"
										  stroke-width="1.5"
										  stroke-linecap="round"
										  stroke-linejoin="round" />
								</svg>
							</div>
						</div>
					</article>



					<?php
					$average_rating = get_custom_product_average_rating($post->ID);
					$ratings_count = get_ratings_count($post->ID);
					?>
					<div class="reviews__footer">

						<div class="reviews__rating">
							<div class="reviews__rating-left">
								<div class="reviews__count"><?php echo number_format($average_rating, 1); ?></div>
								<div class="reviews__rating-item">
									<div class="reviews-slider__stars">
										<?php for ($i = 1; $i <= 5; $i++) : ?>
										<svg class="star" width="36" height="33" viewBox="0 0 36 33" xmlns="http://www.w3.org/2000/svg">
											<path fill="<?php echo ($i <= $average_rating) ? '#F4A804' : 'grey'; ?>" d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z" />
										</svg>
										<?php endfor; ?>
									</div>
									<span>Рейтинг товару</span>
								</div>
							</div>

							<?php
							$ratings_count = get_ratings_count($post->ID);
							$reviews_exist = array_sum($ratings_count) > 0;
							?>

							<div class="rating-table reviews__rating-right">
								<?php if ($reviews_exist) : ?>
								<?php for ($i = 5; $i >= 1; $i--) : ?>
								<div class="rating-row">
									<div class="rating-bar">
										<div class="rating-fill" style="width: <?php echo ($ratings_count[$i] / array_sum($ratings_count)) * 100; ?>%;"></div>
									</div>
								</div>
								<?php endfor; ?>
								<?php else : ?>
								<p>Немає відгуків.</p>
								<?php endif; ?>
							</div>




						</div>

						<div class="asdas">
							<?php echo do_shortcode('[review_form]'); ?>
						</div>

					</div>



				</div>
				<!-- reviews -->
			</div>


			<div class="tab-content" id="payment">
				<div class="delivery__body pc">
					<h3>Оплата</h3>
					<?php echo wp_kses_post($payment_content); ?>

					<h3>Доставка</h3>
					<?php echo wp_kses_post($shipping_content); ?>
				</div>
			</div>

			<div class="tab-content navigation-scroll-booking" id="booking">
				<div class="booking-card__body">

					<div class="prod-info-head booking-head  mob">Бронювання до пологів</div>
					<p><?php echo wp_kses_post(get_theme_mod('product_page_booking', '')); ?></p>

				</div>
			</div>

			<div class="faq-block mob">
				<div class="item booking-item navigation-scroll-booking" id="booking-mob">
					<div class="question"><span>Бронювання до пологів</span>
						<div class="switcher"><img src="/wp-content/themes/carbs-theme/img/icons/arrow-faq.svg" alt="Arrow"></div>
					</div>
					<div class="answer">
						<div class="answer-content">
							<div class="booking-card__body">
								<p><?php echo wp_kses_post(get_theme_mod('product_page_booking', '')); ?></p>
							</div>
						</div>
					</div>
				</div>
				
				
				<div class="item delivery-item navigation-scroll-delivery" id="delivery-mob">
					<div class="question"><span>Оплата і доставка</span>
						<div class="switcher"><img src="/wp-content/themes/carbs-theme/img/icons/arrow-faq.svg" alt="Arrow"></div>
					</div>
					<div class="answer">
						<div class="answer-content">
							<div class="delivery__body">
								<h3>Оплата</h3>
								<?php echo wp_kses_post($payment_content); ?>

								<h3>Доставка</h3>
								<?php echo wp_kses_post($shipping_content); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="item reviews-item navigation-scroll-reviews" id="reviews-mob">
					<div class="question"><span>Відгуки</span>
						<div class="switcher"><img src="/wp-content/themes/carbs-theme/img/icons/arrow-faq.svg" alt="Arrow"></div>
					</div>
					<div class="answer">
						<div class="answer-content">
							<div class="reviews"> 
							    <div class="card__header"> 
							        <h2>Відгуки</h2> 
							        <a href="/vidhuky/" class="btn-black">Всі відгуки</a> 
						        </div>  
						        <article class="reviews-slider"> 
						            <div class="reviews-slider__gallary"> 
        					            <div class="reviews-slider__wrapper swiper-wrapper">  
        					                <?php echo do_shortcode('[product_reviews]'); ?>  
        					            </div> 
    					            </div> 
    					            <div class="reviews-slider__buttons"> 
    					                <div class="reviews-btn-prev btn-swiper"> 
    					                    <svg  width="33"  height="33"  viewBox="0 0 33 33"  fill="none"  xmlns="http://www.w3.org/2000/svg"> <rect x="0.75" y="0.75" width="30.5" height="30.5" rx="4.25" stroke="#242424" stroke-width="1.5" /> <path d="M16.9941 19.2012L13.6 15.807L16.9941 12.4129" stroke="#242424" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg> 
				                        </div> 
				                        <div class="reviews-btn-next btn-swiper"> 
				                            <svg  width="33"  height="33"  viewBox="0 0 33 33"  fill="none"  xmlns="http://www.w3.org/2000/svg"> <rect x="31.25" y="31.25" width="30.5" height="30.5" rx="4.25" transform="rotate(180 31.25 31.25)" stroke="#242424" stroke-width="1.5" /> <path d="M15.0059 12.7988L18.4 16.193L15.0059 19.5871" stroke="#242424" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg> 
			                            </div> 
		                            </div> 
	                            </article>  
	                            <?php $average_rating = get_custom_product_average_rating($post->ID); $ratings_count = get_ratings_count($post->ID); ?> 
	                            <div class="reviews__footer">  
	                                <div class="reviews__rating"> 
	                                    <div class="reviews__rating-left"> 
	                                        <div class="reviews__count"><?php echo number_format($average_rating, 1); ?></div> 
                                            <div class="reviews__rating-item"> 
                                                <div class="reviews-slider__stars"> 
                                                <?php for ($i = 1; $i <= 5; $i++) : ?> 
                                                    <svg class="star" width="36" height="33" viewBox="0 0 36 33" xmlns="http://www.w3.org/2000/svg"> <path fill="<?php echo ($i <= $average_rating) ? '#F4A804' : 'grey'; ?>" d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z" /> </svg> 
                                                <?php endfor; ?> 
                                            </div> 
                                            <span>Рейтинг товару</span> 
                                        </div> 
                                    </div>  
                                    <?php 
                                        $ratings_count = get_ratings_count($post->ID); 
                                        $reviews_exist = array_sum($ratings_count) > 0; 
                                    ?>  
                                    <div class="rating-table reviews__rating-right"> 
                                        <?php if ($reviews_exist) : ?> 
                                            <?php for ($i = 5; $i >= 1; $i--) : ?> 
                                                <div class="rating-row"> 
                                                    <div class="rating-bar"> 
                                                        <div class="rating-fill" style="width: <?php echo ($ratings_count[$i] / array_sum($ratings_count)) * 100; ?>%;"></div> 
                                                    </div> 
                                                </div> 
                                            <?php endfor; ?> 
                                        <?php else : ?> 
                                            <p>Немає відгуків.</p> 
                                        <?php endif; ?> 
                                    </div> 
                                </div>  
                                <div class="asdas"> <?php echo do_shortcode('[review_form]'); ?> </div>  
                            </div>  
                        </div>
						</div>
					</div>
				</div>
			</div>
		</div>



		<div class="block-sticky">
			<div class="cur-product">
				<div class="cur-product__image">

					<div class="card-main__tag  <?php echo esc_attr($classAv); ?>">
						<img loading="lazy" src="/wp-content/themes/carbs-theme/img/tag-available.svg" class="available" style="display: none;">
						<img loading="lazy" src="/wp-content/themes/carbs-theme/img/tag-order.svg" class="order" style="display: none;">
						<span><?php echo esc_html($textAv); ?></span>
					</div>
					<button class="wishlist-icon <?php echo wooeshop_in_wishlist($product->get_id()) ? 'in-wishlist' : ''; ?>" data-id="<?php echo $product->get_id(); ?>">
						<svg width="40" height="40" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
							<rect width="26" height="26" rx="4" fill="white"></rect>
							<path d="M7.01792 13.0342L12.881 18.6673L18.744 13.0342C19.3957 12.408 19.7619 11.5587 19.7619 10.6731C19.7619 8.82896 18.2059 7.33398 16.2865 7.33398C15.3648 7.33398 14.4808 7.68578 13.829 8.31199L12.881 9.22287L11.9329 8.31199C11.2811 7.68578 10.3971 7.33398 9.47541 7.33398C7.55599 7.33398 6 8.82896 6 10.6731C6 11.5587 6.36616 12.408 7.01792 13.0342Z" stroke="#E93A53" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
						</svg>
					</button>
					<?php
					if ( $product->get_image_id() ) {
						echo wp_get_attachment_image($product->get_image_id(), 'thumbnail');
					}
					?>
				</div>

				<div class="cur-product__info">

					<div class="cur-product__info_head">
						<h4><?php echo esc_html($product->get_name()); ?></h4>

						<a class="btn-black go-to-cart-sticky">
							<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg>
						</a>
					</div>

					<div class="cur-product__price">
						<?php
						if ($product->is_on_sale()) {
							echo '<span class="price-sale">' . wc_price($product->get_sale_price()) . '</span>';
							echo '<span class="price-regular">' . wc_price($product->get_regular_price()) . '</span>';
						} else {
							echo '<span class="price-regular">' . wc_price($product->get_price()) . '</span>';
						}
						?>
					</div>
				</div>
			</div>
            <div class="consultation">
                <h2>Потрібна допомога експерта?</h2>
                <div class="consultation-actions">
                    <a class="call-back-call" href="##">Замовити консультацію</a>
                    <span class="or-text">або</span>
                    <div class="social-icons">
                        <a href="viber://chat?number=%2B380990084117" aria-label="Зв'язатися через Viber">
                            <svg width="17" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.2834 3.81139C15.0025 2.80956 14.447 2.01647 13.6319 1.45547C12.6033 0.746843 11.4202 0.495527 10.3985 0.343089C8.98466 0.132286 7.70404 0.102759 6.48454 0.251764C5.34057 0.391842 4.47951 0.615692 3.69328 0.976873C2.15311 1.68413 1.22888 2.82947 0.94666 4.37994C0.809328 5.13252 0.717317 5.81299 0.663758 6.46188C0.540159 7.96223 0.652084 9.29022 1.0064 10.5207C1.3511 11.7203 1.95398 12.5779 2.84938 13.141C3.07804 13.2845 3.36918 13.3882 3.65209 13.4884C3.79354 13.5386 3.92949 13.588 4.04829 13.6402V16.0641C4.04829 16.3813 4.3051 16.6381 4.62233 16.6381C4.77202 16.6381 4.91553 16.5798 5.02265 16.4754L7.25909 14.3001C7.3566 14.1888 7.3566 14.1888 7.45685 14.1868C8.2211 14.1717 9.00183 14.1421 9.77775 14.1002C10.7178 14.0487 11.8068 13.9581 12.8327 13.5303C13.7713 13.1382 14.4566 12.5161 14.8679 11.6818C15.2971 10.8112 15.5525 9.8677 15.648 8.79857C15.8155 6.91851 15.696 5.28701 15.2834 3.81139ZM11.3893 10.9663C11.1648 11.3337 10.8283 11.5878 10.4328 11.7526C10.1437 11.8734 9.84847 11.848 9.56351 11.7272C7.1712 10.715 5.29594 9.11924 4.05584 6.8265C3.8004 6.35408 3.62256 5.83977 3.41862 5.34057C3.37674 5.23758 3.38017 5.11741 3.36163 5.00549C3.37948 4.19866 3.99816 3.74478 4.62302 3.60745C4.86197 3.55458 5.07346 3.63904 5.25062 3.80864C5.74089 4.27694 6.12886 4.8194 6.42068 5.42778C6.5484 5.69489 6.49072 5.9311 6.27305 6.12954C6.22842 6.17074 6.18104 6.20919 6.13229 6.24559C5.63584 6.61913 5.56305 6.90134 5.82741 7.46371C6.27786 8.42023 7.02563 9.06225 7.99313 9.46051C8.24788 9.56557 8.48821 9.51338 8.68322 9.3067C8.70931 9.27923 8.73884 9.25245 8.75806 9.22087C9.13984 8.58434 9.6926 8.64751 10.2035 9.01075C10.5392 9.24902 10.8647 9.50034 11.1971 9.74341C11.7011 10.1149 11.697 10.4637 11.3893 10.9663ZM8.51911 4.37651C8.3907 4.37651 8.26161 4.38681 8.13526 4.40809C7.92103 4.44449 7.71915 4.2996 7.68344 4.08537C7.64774 3.87182 7.79194 3.66925 8.00617 3.63355C8.1744 3.60608 8.34744 3.59166 8.51911 3.59166C10.2186 3.59166 11.6015 4.97459 11.6015 6.67406C11.6015 6.84641 11.5871 7.01945 11.5589 7.18768C11.5267 7.37926 11.3605 7.51521 11.1723 7.51521C11.1511 7.51521 11.1291 7.51384 11.1071 7.50972C10.8936 7.47333 10.7494 7.27145 10.7851 7.0579C10.8064 6.93293 10.8167 6.80384 10.8167 6.67475C10.8167 5.40718 9.78599 4.37651 8.51911 4.37651ZM10.2282 6.92675C10.2282 7.14305 10.0524 7.31883 9.83611 7.31883C9.61982 7.31883 9.44403 7.14305 9.44403 6.92675C9.44403 6.27786 8.91599 5.74982 8.2671 5.74982C8.05081 5.74982 7.87502 5.57404 7.87502 5.35774C7.87502 5.14144 8.05081 4.96566 8.2671 4.96566C9.3479 4.96497 10.2282 5.84527 10.2282 6.92675ZM12.8691 7.40535C12.8279 7.588 12.6665 7.71091 12.4866 7.71091C12.4578 7.71091 12.4282 7.70748 12.3994 7.7013C12.1879 7.65323 12.0554 7.44312 12.1035 7.23231C12.1604 6.98168 12.1893 6.72281 12.1893 6.46326C12.1893 4.55573 10.6374 3.0032 8.72922 3.0032C8.46898 3.0032 8.2108 3.03204 7.96017 3.08903C7.75005 3.13778 7.53856 3.00457 7.49118 2.79308C7.44312 2.58159 7.57564 2.37147 7.78713 2.32409C8.09475 2.25337 8.41199 2.21835 8.7306 2.21835C11.0707 2.21835 12.9748 4.12245 12.9748 6.46257C12.9748 6.78118 12.9391 7.09841 12.8691 7.40535Z" fill="white"/>
                            </svg>
                        </a>
                        <a href="https://t.me/crabsua" aria-label="Зв'язатися через Telegram">
                            <svg width="16" height="16" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.54572 11.9793L6.77747 8.47818L13.1341 2.75057C13.4155 2.49399 13.0762 2.36983 12.7037 2.59331L4.85723 7.55117L1.46371 6.47517C0.735338 6.26825 0.727061 5.76336 1.62924 5.39918L14.8474 0.30061C15.4517 0.0274728 16.031 0.449595 15.7993 1.37661L13.548 11.9793C13.3907 12.7325 12.9355 12.9146 12.3064 12.567L8.8798 10.0342L7.2327 11.6317C7.04233 11.822 6.88507 11.9793 6.54572 11.9793Z" fill="white"/>
                            </svg>

                        </a>
                    </div>
                </div>
            </div>
		</div>
	</div>
	<div class="cybex-action container">
		<h2>Cybex в дії</h2>

		<div class="cybex-action-nav">
			<div class="swiper-button-prev-2">
				<img src="/wp-content/themes/carbs-theme/img/icons/n-arrow-left.svg" alt="Prev">
			</div>
			<div class="swiper-button-next-2">
				<img src="/wp-content/themes/carbs-theme/img/icons/n-arrow-right.svg" alt="Next">
			</div>
		</div>
		<div class="swiper cybex-action-gallery">
			<div class="swiper-wrapper">
				<div class="swiper-slide">
					<img width="253" height="345" src="/wp-content/themes/carbs-theme/img/action/cybex-action-1.webp" class="swiper-lazy" alt="Cybex Action" decoding="async" loading="lazy">
				</div>
				<div class="swiper-slide">
					<img width="253" height="345" src="/wp-content/themes/carbs-theme/img/action/cybex-action-2.webp" class="swiper-lazy" alt="Cybex Actio" decoding="async" loading="lazy">
				</div>
				<div class="swiper-slide">
					<img width="253" height="345" src="/wp-content/themes/carbs-theme/img/action/cybex-action-3.webp" class="swiper-lazy" alt="Cybex Actio" decoding="async" loading="lazy">
				</div>
				<div class="swiper-slide">
					<img width="253" height="345" src="/wp-content/themes/carbs-theme/img/action/cybex-action-4.webp" class="swiper-lazy" alt="Cybex Actio" decoding="async" loading="lazy">
				</div>
				<div class="swiper-slide">
					<img width="253" height="345" src="/wp-content/themes/carbs-theme/img/action/cybex-action-5.webp" class="swiper-lazy" alt="Cybex Actio" decoding="async" loading="lazy">
				</div>
				<div class="swiper-slide">
					<img width="253" height="345" src="/wp-content/themes/carbs-theme/img/action/cybex-action-6.webp" class="swiper-lazy" alt="Cybex Actio" decoding="async" loading="lazy">
				</div>
				<div class="swiper-slide">
					<img width="253" height="345" src="/wp-content/themes/carbs-theme/img/action/cybex-action-7.webp" class="swiper-lazy" alt="Cybex Actio" decoding="async" loading="lazy">
				</div>
				<div class="swiper-slide">
					<img width="253" height="345" src="/wp-content/themes/carbs-theme/img/action/cybex-action-1.webp" class="swiper-lazy" alt="Cybex Action" decoding="async" loading="lazy">
				</div>
				<div class="swiper-slide">
					<img width="253" height="345" src="/wp-content/themes/carbs-theme/img/action/cybex-action-2.webp" class="swiper-lazy" alt="Cybex Action" decoding="async" loading="lazy">
				</div>
				<div class="swiper-slide">
					<img width="253" height="345" src="/wp-content/themes/carbs-theme/img/action/cybex-action-3.webp" class="swiper-lazy" alt="Cybex Action" decoding="async" loading="lazy">
				</div>
				<div class="swiper-slide">
					<img width="253" height="345" src="/wp-content/themes/carbs-theme/img/action/cybex-action-4.webp" class="swiper-lazy" alt="Cybex Action" decoding="async" loading="lazy">
				</div>
			</div>

		</div>






















	</div>
	<!-- Card Footer Container -->
	<div class="card-footer container">

		<?php
	$cross_sells = $product->get_cross_sell_ids();
					global $product;
					if (!$cross_sells) { ?>

		<!-- Аксесуари -->
		<div class="card-swiper accessories">
			<h2><?php _e('Аксесуари', 'crabs_project') ?> </h2>
			<?php


						$args = array(
						'post_type'      => 'product',
						'posts_per_page' => 10,
						'post__in'       => $cross_sells,
						'orderby'        => 'post__in',
					);

										$cross_sell_query = new WP_Query($args);

										if ($cross_sell_query->have_posts()) : ?>
			<article class="card-swiper-slider">
				<div class="card-swiper-slider__gallary accessuari">
					<div class="card-swiper-slider__wrapper swiper-wrapper">
						<?php while ($cross_sell_query->have_posts()) : $cross_sell_query->the_post();
										$current_product = wc_get_product(get_the_ID()); ?>
						<section class="swiper-slide">
							<div>
								<?php if (has_post_thumbnail()) : ?>
								<a class="card-swiper-slider__image" href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail('full', array('alt' => get_the_title())); ?>
								</a>
								<?php else : ?>
								<a class="card-swiper-slider__image" href="<?php the_permalink(); ?>">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/card.jpg" alt="slide-photo" />
								</a>
								<?php endif; ?>
								<div class="card-swiper-slider__icons">
									<?php
										$classAv = $current_product->is_in_stock() ? 'tag-available' : 'tag-order';
										$textAv = $current_product->is_in_stock() ? 'В наявності' : 'Під замовлення';
									?>

									<div class="card-swiper-slider__icons">
										<div class="card-swiper-slider__icon-right">
											<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/percentage.png" alt="percentage"  width="35" height="35">
											<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/paw.png" alt="paw"  width="35" height="35">
										</div>
										<button class="card-swiper-slider__icon-heart wishlist-icon <?php echo wooeshop_in_wishlist2($current_product->get_id()) ? 'in-wishlist' : '' ?>" data-id="<?php echo $current_product->get_id(); ?>">

											<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="26" height="26" rx="4" fill="white" />
												<path d="M7.01792 13.0342L12.881 18.6673L18.744 13.0342C19.3957 12.408 19.7619 11.5587 19.7619 10.6731C19.7619 8.82896 18.2059 7.33398 16.2865 7.33398C15.3648 7.33398 14.4808 7.68578 13.829 8.31199L12.881 9.22287L11.9329 8.31199C11.2811 7.68578 10.3971 7.33398 9.47541 7.33398C7.55599 7.33398 6 8.82896 6 10.6731C6 11.5587 6.36616 12.408 7.01792 13.0342Z" stroke="#E93A53" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
											</svg>

										</button>
									</div>
								</div>
								<div class="card-swiper-slider__footer-card">
									<div class="card-swiper-slider__tag <?php echo esc_attr($classAv); ?>">
										<img src="/wp-content/themes/carbs-theme/img/tag-available.svg" alt="percentage"  width="14" height="14">
										<span><?php echo esc_html($textAv); ?></span>
									</div>
									<div class="card-swiper-slider__rating">
										<div class="card-swiper-slider__stars">
											<?php
										$average_rating = get_custom_product_average_rating($post->ID);
										$ratings_count = get_ratings_count($post->ID);
											?>
											<?php for ($i = 1; $i <= 5; $i++) : ?>
											<svg class="star" width="36" height="33" viewBox="0 0 36 33" xmlns="http://www.w3.org/2000/svg">
												<path fill="<?php echo ($i <= $average_rating) ? '#F4A804' : 'grey'; ?>" d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z" />
											</svg>
											<?php endfor; ?>
										</div>
										<span><?php echo number_format($average_rating, 1); ?></span>
									</div>
									<div class="card-swiper-slider__mid">
										<h3 class="card-swiper-slider__title"><?php the_title(); ?></h3>

										<a href="<?php echo esc_url($current_product->add_to_cart_url()); ?>" class=" add_to_cart_button ajax_add_to_cart card-swiper-slider__bag" data-product_id="<?php echo esc_attr($current_product->get_id()); ?>" data-product_sku="<?php echo esc_attr($current_product->get_sku()); ?>" aria-label="<?php echo esc_attr($current_product->add_to_cart_text()); ?>" rel="nofollow">

											<svg class="bag">
												<use xlink:href="#bag"></use>
											</svg>
										</a>

									</div>

									<div class="card-swiper-slider__bottom-card">
										<div class="card-swiper-slider__prices">
											<?php if ($current_product->is_type('variable')) {
												// Get the available variations
												$available_variations = $current_product->get_available_variations();
												$variation_prices = array();

												foreach ($available_variations as $variation) {
													$variation_obj = new WC_Product_Variation($variation['variation_id']);
													$price = $variation_obj->get_price();

													if ($price !== '' && $price !== null) {
														$variation_prices[] = $price;
													}
												}

												if (!empty($variation_prices)) {
													// Get the minimum and maximum prices from the variations
													$min_price = min($variation_prices);
													$max_price = max($variation_prices);

													// Get the minimum and maximum regular prices from the variations
													$variation_regular_prices = array_map(function ($variation) {
														$variation_obj = new WC_Product_Variation($variation['variation_id']);
														$regular_price = $variation_obj->get_regular_price();
														return $regular_price !== '' && $regular_price !== null ? $regular_price : null;
													}, $available_variations);

													$variation_regular_prices = array_filter($variation_regular_prices);

													if (!empty($variation_regular_prices)) {
														$min_regular_price = min($variation_regular_prices);
														$max_regular_price = max($variation_regular_prices);

														if ($min_price !== $min_regular_price) {
															// Show sale price range
											?>
											<div class="card-swiper-slider__current-pirce"><?php echo wc_price($min_price); ?></div>
											<div class="card-swiper-slider__old-pirce"><?php echo wc_price($min_regular_price); ?> </div>
											<?php
														} else {
															// Show regular price range
											?>
											<div class="card-swiper-slider__current-pirce"><?php echo wc_price($min_price); ?> - <?php echo wc_price($max_price); ?></div>
											<?php
														}
													}
												}
											} else {
												// For simple products
												if ($current_product->get_sale_price()) {
											?>
											<div class="card-swiper-slider__current-pirce"><?php echo wc_price($current_product->get_sale_price()); ?></div>
											<div class="card-swiper-slider__old-pirce"><?php echo wc_price($current_product->get_regular_price()); ?></div>
											<?php
												} elseif ($current_product->get_price() !== '' && $current_product->get_price() !== null) {
											?>
											<div class="card-swiper-slider__current-pirce"><?php echo wc_price($current_product->get_price()); ?></div>
											<?php
												}
											}
											?>

										</div>



										<a href="<?php echo esc_url($current_product->add_to_cart_url()); ?>" class=" add_to_cart_button ajax_add_to_cart btn-black" data-product_id="<?php echo esc_attr($current_product->get_id()); ?>" data-product_sku="<?php echo esc_attr($current_product->get_sku()); ?>" aria-label="<?php echo esc_attr($current_product->add_to_cart_text()); ?>" rel="nofollow">В кошик

											<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M4.62877 7.72073V4.17384C4.62877 2.21495 6.14744 0.626953 8.02082 0.626953C9.8942 0.626953 11.4129 2.21495 11.4129 4.17384V7.72073M3.10194 15.7012H12.9397C13.9399 15.7012 14.7229 14.8008 14.6281 13.7596L13.982 6.66587C13.8991 5.75567 13.168 5.06056 12.2936 5.06056H3.74805C2.87365 5.06056 2.14256 5.75568 2.05966 6.66587L1.41355 13.7596C1.31872 14.8008 2.10172 15.7012 3.10194 15.7012Z" stroke="white" stroke-width="1.16736" stroke-linecap="round" stroke-linejoin="round" />
											</svg>
										</a>
									</div>


								</div>
								</section>
							<?php endwhile; ?>
							</div>
					</div>
					<div class="card-swiper-slider__buttons">
						<div class="accessuari-btn-prev btn-swiper">
							<svg class="arrow_40">
								<use xlink:href="#arrow-prev_40"></use>
							</svg>
						</div>
						<div class="accessuari-btn-next btn-swiper">
							<svg class="arrow_40">
								<use xlink:href="#arrow-next_40"></use>
							</svg>
						</div>
					</div>
					</article>
				<?php wp_reset_postdata(); ?>
				<?php endif; ?>


				</div>
			<!-- Аксесуари -->

			<?php }

            $upsell_ids = $product->get_upsell_ids();

            if (!empty($upsell_ids)) {
                $args = array(
                    'post_type' => 'product',
                    'post__in' => $upsell_ids,
                    'posts_per_page' => -1,
                    'meta_query'     => array(
                        array(
                            'key'     => '_stock_status',
                            'value'   => 'instock',
                            'compare' => '='
                        )
                    )
                );

                $cross_sell_products = new WP_Query($args);

					if ($cross_sell_products) { ?>

			<!-- Може зацікавити -->
			<div class="card-swiper">
				<h2>Разом з цим товаром купують</h2>
				<?php

                if ($cross_sell_products->have_posts()) : ?>
				<article class="card-swiper-slider">
					<div class="card-swiper-slider__gallary related">
						<div class="card-swiper-slider__wrapper swiper-wrapper">
							<?php while ($cross_sell_products->have_posts()) : $cross_sell_products->the_post();
						$current_product = wc_get_product(get_the_ID()); ?>
							<section class="swiper-slide">
								<div>
									<?php if (has_post_thumbnail()) : ?>
									<a class="card-swiper-slider__image" href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail('full', array('alt' => get_the_title())); ?>
									</a>
									<?php else : ?>
									<a class="card-swiper-slider__image" href="<?php the_permalink(); ?>">
										<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/card.jpg" alt="slide-photo" />
									</a>
									<?php endif; ?>
									<div class="card-swiper-slider__icons">
										<?php
						$classAv = $current_product->is_in_stock() ? 'tag-available' : 'tag-order';
						$textAv = $current_product->is_in_stock() ? 'В наявності' : 'Під замовлення';
										?>

										<div class="card-swiper-slider__icons">
                                            <div class="catalog-card__icon-right">
                                                <div class="catalog-card__icon-group">
                                                <img  src="<?php echo esc_url( content_url( '/uploads/2025/07/Lapka-ta-vidsotok-e1751405070826.webp' ) ); ?>"  alt="percentage"  loading="lazy">

                                                    
                                                </div>
                                                <div class="catalog-card__icon-single">
                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/pregnant-woman.png" alt="reserve before childbirth">
                                                </div>
                                            </div>
											<button class="card-swiper-slider__icon-heart wishlist-icon <?php echo wooeshop_in_wishlist2($current_product->get_id()) ? 'in-wishlist' : '' ?>" data-id="<?php echo $current_product->get_id(); ?>">

												<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect width="26" height="26" rx="4" fill="white" />
													<path d="M7.01792 13.0342L12.881 18.6673L18.744 13.0342C19.3957 12.408 19.7619 11.5587 19.7619 10.6731C19.7619 8.82896 18.2059 7.33398 16.2865 7.33398C15.3648 7.33398 14.4808 7.68578 13.829 8.31199L12.881 9.22287L11.9329 8.31199C11.2811 7.68578 10.3971 7.33398 9.47541 7.33398C7.55599 7.33398 6 8.82896 6 10.6731C6 11.5587 6.36616 12.408 7.01792 13.0342Z" stroke="#E93A53" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
												</svg>

											</button>
										</div>
									</div>
									<div class="card-swiper-slider__footer-card">
										<div class="card-swiper-slider__tag <?php echo esc_attr($classAv); ?>">
											<img src="/wp-content/themes/carbs-theme/img/tag-available.svg" alt="percentage"  width="14" height="14">
											<span><?php echo esc_html($textAv); ?></span>
										</div>
										<div class="card-swiper-slider__rating">
											<div class="card-swiper-slider__stars">
												<?php
						$average_rating = get_custom_product_average_rating($post->ID);
						$ratings_count = get_ratings_count($post->ID);
												?>
												<?php for ($i = 1; $i <= 5; $i++) : ?>
												<svg class="star" width="36" height="33" viewBox="0 0 36 33" xmlns="http://www.w3.org/2000/svg">
													<path fill="<?php echo ($i <= $average_rating) ? '#F4A804' : 'grey'; ?>" d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z" />
												</svg>
												<?php endfor; ?>
											</div>
											<span><?php echo number_format($average_rating, 1); ?></span>
										</div>
										<div class="card-swiper-slider__mid">
											<h3 class="card-swiper-slider__title"><?php the_title(); ?></h3>

											<a href="<?php echo esc_url($current_product->add_to_cart_url()); ?>" class=" add_to_cart_button ajax_add_to_cart card-swiper-slider__bag" data-product_id="<?php echo esc_attr($current_product->get_id()); ?>" data-product_sku="<?php echo esc_attr($current_product->get_sku()); ?>" aria-label="<?php echo esc_attr($current_product->add_to_cart_text()); ?>" rel="nofollow">

												<svg class="bag">
													<use xlink:href="#bag"></use>
												</svg>
											</a>

										</div>

										<div class="card-swiper-slider__bottom-card">
											<div class="card-swiper-slider__prices">
												<?php if ($current_product->is_type('variable')) {
													// Get the available variations
													$available_variations = $current_product->get_available_variations();
													$variation_prices = array();

													foreach ($available_variations as $variation) {
														$variation_obj = new WC_Product_Variation($variation['variation_id']);
														$price = $variation_obj->get_price();

														if ($price !== '' && $price !== null) {
															$variation_prices[] = $price;
														}
													}

													if (!empty($variation_prices)) {
														// Get the minimum and maximum prices from the variations
														$min_price = min($variation_prices);
														$max_price = max($variation_prices);

														// Get the minimum and maximum regular prices from the variations
														$variation_regular_prices = array_map(function ($variation) {
															$variation_obj = new WC_Product_Variation($variation['variation_id']);
															$regular_price = $variation_obj->get_regular_price();
															return $regular_price !== '' && $regular_price !== null ? $regular_price : null;
														}, $available_variations);

														$variation_regular_prices = array_filter($variation_regular_prices);

														if (!empty($variation_regular_prices)) {
															$min_regular_price = min($variation_regular_prices);
															$max_regular_price = max($variation_regular_prices);

															if ($min_price !== $min_regular_price) {
																// Show sale price range
												?>
												<div class="card-swiper-slider__current-pirce"><?php echo wc_price($min_price); ?></div>
												<div class="card-swiper-slider__old-pirce"><?php echo wc_price($min_regular_price); ?> </div>
												<?php
															} else {
																// Show regular price range
												?>
												<div class="card-swiper-slider__current-pirce"><?php echo wc_price($min_price); ?> - <?php echo wc_price($max_price); ?></div>
												<?php
															}
														}
													}
												} else {
													// For simple products
													if ($current_product->get_sale_price()) {
												?>
												<div class="card-swiper-slider__current-pirce"><?php echo wc_price($current_product->get_sale_price()); ?></div>
												<div class="card-swiper-slider__old-pirce"><?php echo wc_price($current_product->get_regular_price()); ?></div>
												<?php
													} elseif ($current_product->get_price() !== '' && $current_product->get_price() !== null) {
												?>
												<div class="card-swiper-slider__current-pirce"><?php echo wc_price($current_product->get_price()); ?></div>
												<?php
													}
												}
												?>

											</div>



											<a href="<?php echo esc_url($current_product->add_to_cart_url()); ?>" class=" add_to_cart_button ajax_add_to_cart btn-black" data-product_id="<?php echo esc_attr($current_product->get_id()); ?>" data-product_sku="<?php echo esc_attr($current_product->get_sku()); ?>" aria-label="<?php echo esc_attr($current_product->add_to_cart_text()); ?>" rel="nofollow">В кошик

												<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M4.62877 7.72073V4.17384C4.62877 2.21495 6.14744 0.626953 8.02082 0.626953C9.8942 0.626953 11.4129 2.21495 11.4129 4.17384V7.72073M3.10194 15.7012H12.9397C13.9399 15.7012 14.7229 14.8008 14.6281 13.7596L13.982 6.66587C13.8991 5.75567 13.168 5.06056 12.2936 5.06056H3.74805C2.87365 5.06056 2.14256 5.75568 2.05966 6.66587L1.41355 13.7596C1.31872 14.8008 2.10172 15.7012 3.10194 15.7012Z" stroke="white" stroke-width="1.16736" stroke-linecap="round" stroke-linejoin="round" />
												</svg>
											</a>
										</div>


									</div>
									</section>
								<?php endwhile; ?>
								</div>
						</div>
						<div class="card-swiper-slider__buttons">
							<div class="related-btn-prev btn-swiper">
								<svg class="arrow_40">
									<use xlink:href="#arrow-prev_40"></use>
								</svg>
							</div>
							<div class="related-btn-next btn-swiper">
								<svg class="arrow_40">
									<use xlink:href="#arrow-next_40"></use>
								</svg>
							</div>
						</div>
						</article>
					<?php wp_reset_postdata(); ?>
					<?php endif; ?>
                    <?php } ?>

					</div>
				<!-- Може зацікавити -->

				<?php } ?>

				<?php // Get the recently viewed product IDs
				$viewed_products = ! empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();
				$viewed_products = array_filter(array_map('absint', $viewed_products));


				if ($viewed_products) { ?>
				<!-- Ви переглядали -->
                    <div class="card-swiper">
                        <h2>Ви переглядали</h2>
                        <?php
                        $args = array(
                            'post_type'      => 'product',
                            'posts_per_page' => 10,
                            'post__in'       => $viewed_products,
                            'orderby'        => 'post__in',
                        );

                        $viewed_query = new WP_Query($args);

                        if ($viewed_query->have_posts()) : ?>
                            <article class="card-swiper-slider">
                                <div class="card-swiper-slider__gallary look">
                                    <div class="card-swiper-slider__wrapper swiper-wrapper">
                                        <?php while ($viewed_query->have_posts()) : $viewed_query->the_post();
                                            $current_product = wc_get_product(get_the_ID()); ?>
                                            <section class="swiper-slide">
                                                <div>
                                                    <?php if (has_post_thumbnail()) : ?>
                                                        <a class="card-swiper-slider__image" href="<?php the_permalink(); ?>">
                                                            <?php the_post_thumbnail('full', array('alt' => get_the_title())); ?>
                                                        </a>
                                                    <?php else : ?>
                                                        <a class="card-swiper-slider__image" href="<?php the_permalink(); ?>">
                                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/card.jpg" alt="slide-photo" />
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php
                                                    $classAv = $current_product->is_in_stock() ? 'tag-available' : 'tag-order';
                                                    $textAv = $current_product->is_in_stock() ? 'В наявності' : 'Під замовлення';
                                                    ?>

                                                    <div class="card-swiper-slider__icons">
                                                        <div class="card-swiper-slider__icon-right">
                                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/percentage.png" alt="percentage"  width="35" height="35">
                                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/paw.png" alt="paw"  width="35" height="35">
                                                        </div>
                                                        <button class="card-swiper-slider__icon-heart wishlist-icon <?php echo wooeshop_in_wishlist2($current_product->get_id()) ? 'in-wishlist' : '' ?>" data-id="<?php echo $current_product->get_id(); ?>">
                                                            <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect width="26" height="26" rx="4" fill="white" />
                                                                <path d="M7.01792 13.0342L12.881 18.6673L18.744 13.0342C19.3957 12.408 19.7619 11.5587 19.7619 10.6731C19.7619 8.82896 18.2059 7.33398 16.2865 7.33398C15.3648 7.33398 14.4808 7.68578 13.829 8.31199L12.881 9.22287L11.9329 8.31199C11.2811 7.68578 10.3971 7.33398 9.47541 7.33398C7.55599 7.33398 6 8.82896 6 10.6731C6 11.5587 6.36616 12.408 7.01792 13.0342Z" stroke="#E93A53" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-swiper-slider__footer-card">
                                                    <div class="card-swiper-slider__tag <?php echo esc_attr($classAv); ?>">
                                                        <img src="/wp-content/themes/carbs-theme/img/tag-available.svg" alt="percentage"  width="14" height="14">
                                                        <span><?php echo esc_html($textAv); ?></span>
                                                    </div>
                                                    <div class="card-swiper-slider__rating">
                                                        <div class="card-swiper-slider__stars">
                                                            <?php
                                                            $average_rating = get_custom_product_average_rating($post->ID);
                                                            $ratings_count = get_ratings_count($post->ID);
                                                            ?>
                                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                                <svg class="star" width="36" height="33" viewBox="0 0 36 33" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill="<?php echo ($i <= $average_rating) ? '#F4A804' : 'grey'; ?>" d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z" />
                                                                </svg>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <span><?php echo number_format($average_rating, 1); ?></span>
                                                    </div>
                                                    <div class="card-swiper-slider__mid">
                                                        <h3 class="card-swiper-slider__title"><?php the_title(); ?></h3>
                                                        <a href="<?php echo esc_url($current_product->add_to_cart_url()); ?>" class=" add_to_cart_button ajax_add_to_cart card-swiper-slider__bag" data-product_id="<?php echo esc_attr($current_product->get_id()); ?>" data-product_sku="<?php echo esc_attr($current_product->get_sku()); ?>" aria-label="<?php echo esc_attr($current_product->add_to_cart_text()); ?>" rel="nofollow">
                                                            <svg class="bag">
                                                                <use xlink:href="#bag"></use>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <div class="card-swiper-slider__bottom-card">
                                                        <div class="card-swiper-slider__prices">
                                                            <?php if ($current_product->is_type('variable')) {
                                                                $available_variations = $current_product->get_available_variations();
                                                                $variation_prices = array();
                                                                foreach ($available_variations as $variation) {
                                                                    $variation_obj = new WC_Product_Variation($variation['variation_id']);
                                                                    $price = $variation_obj->get_price();
                                                                    if ($price !== '' && $price !== null) {
                                                                        $variation_prices[] = $price;
                                                                    }
                                                                }
                                                                if (!empty($variation_prices)) {
                                                                    $min_price = min($variation_prices);
                                                                    $max_price = max($variation_prices);
                                                                    $variation_regular_prices = array_map(function ($variation) {
                                                                        $variation_obj = new WC_Product_Variation($variation['variation_id']);
                                                                        $regular_price = $variation_obj->get_regular_price();
                                                                        return $regular_price !== '' && $regular_price !== null ? $regular_price : null;
                                                                    }, $available_variations);
                                                                    $variation_regular_prices = array_filter($variation_regular_prices);
                                                                    if (!empty($variation_regular_prices)) {
                                                                        $min_regular_price = min($variation_regular_prices);
                                                                        $max_regular_price = max($variation_regular_prices);
                                                                        if ($min_price !== $min_regular_price) {
                                                                            ?>
                                                                            <div class="card-swiper-slider__current-pirce"><?php echo wc_price($min_price); ?></div>
                                                                            <div class="card-swiper-slider__old-pirce"><?php echo wc_price($min_regular_price); ?> </div>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <div class="card-swiper-slider__current-pirce"><?php echo wc_price($min_price); ?> - <?php echo wc_price($max_price); ?></div>
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                if ($current_product->get_sale_price()) {
                                                                    ?>
                                                                    <div class="card-swiper-slider__current-pirce"><?php echo wc_price($current_product->get_sale_price()); ?></div>
                                                                    <div class="card-swiper-slider__old-pirce"><?php echo wc_price($current_product->get_regular_price()); ?></div>
                                                                    <?php
                                                                } elseif ($current_product->get_price() !== '' && $current_product->get_price() !== null) {
                                                                    ?>
                                                                    <div class="card-swiper-slider__current-pirce"><?php echo wc_price($current_product->get_price()); ?></div>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <a href="<?php echo esc_url($current_product->add_to_cart_url()); ?>" class=" add_to_cart_button ajax_add_to_cart btn-black" data-product_id="<?php echo esc_attr($current_product->get_id()); ?>" data-product_sku="<?php echo esc_attr($current_product->get_sku()); ?>" aria-label="<?php echo esc_attr($current_product->add_to_cart_text()); ?>" rel="nofollow">В кошик
                                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M4.62877 7.72073V4.17384C4.62877 2.21495 6.14744 0.626953 8.02082 0.626953C9.8942 0.626953 11.4129 2.21495 11.4129 4.17384V7.72073M3.10194 15.7012H12.9397C13.9399 15.7012 14.7229 14.8008 14.6281 13.7596L13.982 6.66587C13.8991 5.75567 13.168 5.06056 12.2936 5.06056H3.74805C2.87365 5.06056 2.14256 5.75568 2.05966 6.66587L1.41355 13.7596C1.31872 14.8008 2.10172 15.7012 3.10194 15.7012Z" stroke="white" stroke-width="1.16736" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </section>
                                        <?php endwhile; ?>
                                    </div>
                                    <div class="swiper-scrollbar"></div>
                                </div>
                            </article>
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </div>
				<!-- Ви переглядали -->

				<?php } ?>

			</div>
			<!-- Cart Footer Container -->


		</div> <!-- product -->



		<?php do_action('woocommerce_after_single_product'); ?>
		
		
		
	
