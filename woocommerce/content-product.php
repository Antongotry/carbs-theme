<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
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

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<section <?php wc_product_class( 'catalog-card', $product ); ?>>
    <div class="catalog-card__icon-right">
        <div class="catalog-card__icon-group">
            <img src="/wp-content/uploads/2025/07/Lapka-ta-vidsotok-e1751405070826.webp" alt="percentage">
        </div>
        <?php if ( has_term( 'Забронювати до пологів', 'product_tag', $product->get_id() ) ) : ?>
            <div class="catalog-card__icon-single">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/pregnant-woman.png" alt="reserve before childbirth">
            </div>
        <?php endif; ?>

    </div>

	<a href="<?php the_permalink(); ?>" class="catalog-card__image">
		<?php if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail(); ?>
		<?php else : ?>
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/catalog-card.png" alt="<?php the_title(); ?>" />
		<?php endif; ?>
	</a>

<?php if ( function_exists('crabs_render_stock_badge') ) {
    crabs_render_stock_badge( $product, 'single' ); // параметр не обов’язковий, але зручний для таргет-стилів
} ?>

	
 <?php 
	$classAv = $product->is_in_stock() ? 'tag-available' : 'tag-order';
	$textAv = $product->is_in_stock() ? 'В наявності' : 'Під замовлення';
	?>

	<!--<div class="catalog-card__tag catalog-card__tag--mobile tag-order <?php echo esc_attr($classAv); ?>">-->
	<!--	<span><?php echo esc_html($textAv); ?></span>-->
	<!--</div>-->

	<div class="catalog-card__content">
		<div class="catalog-card__icon-left wishlist-icon <?php echo wooeshop_in_wishlist($product->get_id()) ? 'in-wishlist' : ''; ?>" data-id="<?php echo esc_attr( $product->get_id() ); ?>">
			<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="26" height="26" rx="4" fill="white"></rect><path d="M7.01792 13.0342L12.881 18.6673L18.744 13.0342C19.3957 12.408 19.7619 11.5587 19.7619 10.6731C19.7619 8.82896 18.2059 7.33398 16.2865 7.33398C15.3648 7.33398 14.4808 7.68578 13.829 8.31199L12.881 9.22287L11.9329 8.31199C11.2811 7.68578 10.3971 7.33398 9.47541 7.33398C7.55599 7.33398 6 8.82896 6 10.6731C6 11.5587 6.36616 12.408 7.01792 13.0342Z" stroke="#E93A53" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
		</div>
		<div class="catalog-card__icons">
			<!--<div class="catalog-card__tag catalog-card__tag--desktop tag-order <?php echo esc_attr($classAv); ?>">-->
			<!--	<span><?php echo esc_html($textAv); ?></span>-->
			<!--</div>-->

			<button class="remove-wishlist" data-product-id="<?php echo get_the_ID(); ?>">
				<svg class="delete-icon">
					<use xlink:href="#delete"></use>
				</svg>
				<span>Видалити</span>
			</button>

		</div>
        <div class="catalog-card__footer">
            <div class="catalog-card__header-row">
                <a href="<?php the_permalink(); ?>" class="catalog-card__title-link">
                    <h3 class="catalog-card__title"><?php the_title(); ?></h3>
                </a>
                <div class="catalog-card__action-button">
                    <?php
                    global $product;
                    if ( ! is_a( $product, 'WC_Product' ) ) {
                        $product = wc_get_product( get_the_ID() );
                    }

                    if ( $product && $product->is_in_stock() ) :
                        $button_classes = 'btn-icon-style add_to_cart_button';
                        if ( ! $product->is_type( 'variable' ) ) {
                            $button_classes .= ' ajax_add_to_cart';
                        }

                        $default_attributes = array(
                            'aria-label' => $product->add_to_cart_text(),
                            'rel'        => 'nofollow',
                        );
                        if ( ! $product->is_type( 'variable' ) ) {
                            $default_attributes['data-product_id'] = esc_attr( $product->get_id() );
                            $default_attributes['data-product_sku'] = esc_attr( $product->get_sku() );
                        }

                        echo apply_filters(
                            'woocommerce_loop_add_to_cart_link',
                            sprintf(
                                '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                esc_url( $product->add_to_cart_url() ),
                                esc_attr( isset( $quantity ) ? $quantity : 1 ),
                                esc_attr( $button_classes ),
                                wc_implode_html_attributes( $default_attributes ),
                                '<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                            ),
                            $product,
                            array()
                        );
                    endif;
                    ?>
                </div>
            </div>

            <div class="catalog-card__prices">
                <?php if ( crabs_should_show_price( $product ) ) : ?>
                <?php
                if ( $product ) :
                    if ( $product->is_type( 'variable' ) ) {
                        $available_variations = $product->get_available_variations();
                        $variation_prices = array();
                        foreach ( $available_variations as $variation ) {
                            $variation_obj = new WC_Product_Variation( $variation['variation_id'] );
                            $price = $variation_obj->get_price();
                            if ( $price !== '' && $price !== null ) {
                                $variation_prices[] = $price;
                            }
                        }
                        if ( !empty( $variation_prices ) ) {
                            $min_price = min( $variation_prices );
                            $max_price = max( $variation_prices );
                            $variation_regular_prices = array_map( function( $variation ) {
                                $variation_obj = new WC_Product_Variation( $variation['variation_id'] );
                                $regular_price = $variation_obj->get_regular_price();
                                return $regular_price !== '' && $regular_price !== null ? $regular_price : null;
                            }, $available_variations );
                            $variation_regular_prices = array_filter( $variation_regular_prices );
                            if ( !empty( $variation_regular_prices ) ) {
                                $min_regular_price = min( $variation_regular_prices );
                                if ( $min_price != $min_regular_price ) {
                                    ?>
                                    <div class="catalog-card__current-pirce"><?php echo wc_price( $min_price ); ?></div>
                                    <div class="catalog-card__old-pirce"><?php echo wc_price( $min_regular_price ); ?> </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="catalog-card__current-pirce"><?php echo wc_price( $min_price ) . ( $min_price != $max_price ? ' - ' . wc_price( $max_price ) : '' ); ?></div>
                                    <?php
                                }
                            } elseif ($min_price) {
                                ?>
                                <div class="catalog-card__current-pirce"><?php echo wc_price( $min_price ) . ( $min_price != $max_price ? ' - ' . wc_price( $max_price ) : '' ); ?></div>
                                <?php
                            }
                        }
                    } else {
                        if ( $product->get_sale_price() !== '' && $product->get_sale_price() !== null ) {
                            ?>
                            <div class="catalog-card__current-pirce"><?php echo wc_price( $product->get_sale_price() ); ?></div>
                            <div class="catalog-card__old-pirce"><?php echo wc_price( $product->get_regular_price() ); ?></div>
                            <?php
                        } elseif ( $product->get_price() !== '' && $product->get_price() !== null ) {
                            ?>
                            <div class="catalog-card__current-pirce"><?php echo wc_price( $product->get_price() ); ?></div>
                            <?php
                        }
                    }
                endif;
                ?>
                <?php endif; ?>
            </div>
        </div>
	</div>
</section>


