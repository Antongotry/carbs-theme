<?php 

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}


$product_id = $product->get_id();
?>

<section <?php wc_product_class( 'catalog-card', $product ); ?>>
    
    <a href="<?php the_permalink(); ?>" class="catalog-card__image">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail(); ?>
        <?php else : ?>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/catalog-card.png" alt="<?php the_title(); ?>" />
        <?php endif; ?>
    </a>
    <div class="catalog-card__content">

        <div class="catalog-card__icons">

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
            <button class="remove-wishlist" data-product-id="<?php echo get_the_ID(); ?>">
                <svg class="delete-icon">
                <use xlink:href="#delete"></use>
                </svg>
                <span>Видалити</span>
            </button>
        </div>

        <div class="catalog-card__footer">
			<?php if ( $product->is_in_stock() ) : ?>
                <div class="catalog-card__tag">
                    <svg width="15" height="11" viewBox="0 0 15 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 1L5.06257 10L1 5.90911" stroke="#5EB04C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>В наявності</span>
                </div>
            <?php endif; ?>
            <?php if ( $product->is_on_backorder() ) : ?>
                <div class="catalog-card__tag">
                    <svg width="17" height="12" viewBox="0 0 17 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.44578 1H10.2169C10.7159 1 11.1205 1.40456 11.1205 1.90361V1.90361M15.0964 4.9759H12.1205C11.5682 4.9759 11.1205 4.52819 11.1205 3.9759V1.90361M15.0964 4.9759L15.7682 5.78211C15.918 5.96182 16 6.18835 16 6.42229V8.31325C16 8.86554 15.5523 9.31325 15 9.31325H14.3735M15.0964 4.9759L13.7516 2.43573C13.5783 2.10836 13.2382 1.90361 12.8678 1.90361H11.1205M10.9398 9.31325H7.86747M4.43373 9.31325H2.44578M4.79518 6.96385H1.90361M6.06024 4.9759H1M6.78313 2.98795H1.90361" stroke="#E93A53" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="6.05918" cy="9.31309" r="1.30723" stroke="#E93A53"/>
                        <circle cx="12.567" cy="9.31309" r="1.30723" stroke="#E93A53"/>
                    </svg>

                    <span>Під замовлення</span>
                </div>
            <?php endif; ?>
            <div class="catalog-card__mid">
                <a href="<?php the_permalink(); ?>">
                    <h3 class="catalog-card__title"><?php the_title(); ?></h3>
                </a>
                <a href="#" class="catalog-card__bag add-to-cart-button" data-product_id="<?php echo $product_id; ?>">
                    <svg class="bag"><use xlink:href="#bag"></use></svg>
                </a>
            </div>
            <div class="catalog-card__prices">
                

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
                        <div class="catalog-card__current-pirce"><?php echo wc_price( $min_price ); ?></div>
                        <div class="catalog-card__old-pirce"><?php echo wc_price( $min_regular_price ); ?> </div>
                        <?php
                    } else {
                        // Show regular price range
                        ?>
                        <div class="catalog-card__current-pirce"><?php echo wc_price( $min_price ); ?> - <?php echo wc_price( $max_price ); ?></div>
                        <?php
                    }
                } else {
                    // For simple products
                    if ( $product->get_sale_price() ) {
                        ?>
                        <div class="catalog-card__current-pirce"><?php echo wc_price( $product->get_sale_price() ); ?></div>
                        <div class="catalog-card__old-pirce"><?php echo wc_price( $product->get_regular_price() ); ?></div>
                        <?php
                    } else {
                        ?>
                        <div class="catalog-card__current-pirce"><?php echo wc_price( $product->get_price() ); ?></div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="catalog-card__buttons">
                <?php if ( $product->is_in_stock() ) : ?>
                    <?php if ( $product->is_type( 'variable' ) ) {

                    $add_to_cart_button = apply_filters('woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
                        sprintf(
                            '<a href="%s" data-quantity="%s" class="%s" %s>%s 
                            
                            <svg
                            viewBox="0 0 19 19"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            >
                            <path
                                d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z"
                                stroke="white"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            </svg></a>',
                            esc_url( $product->add_to_cart_url() ),
                            esc_attr( isset( $quantity ) ? $quantity : 1 ),
                            esc_attr( isset( $class ) ? $class : 'btn-black' ),
                            isset( $attributes ) ? wc_implode_html_attributes( $attributes ) : '',
                            esc_html( $product->add_to_cart_text() )
                        ),
                        $product, $args );
                    echo $add_to_cart_button;

                    } else { ?>
                        
                        <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="btn-black add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="<?php echo esc_attr( $product->add_to_cart_text() ); ?>" rel="nofollow">
                            <span><?php _e('Додати в кошик', 'crabs_project'); ?></span>
                            <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>

                    <?php } ?>

                <?php endif; ?>
                <a href="<?php the_permalink(); ?>" class="btn-white">Детальніше</a>
            </div>

        </div>
    </div>
</section>