<section class="swiper-slide">
    <div>
        <a class="relate-slider__image" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('full'); ?></a>
        
        <div class="relate-slider__icons">
            <div class="relate-slider__tag">
                <svg width="15" height="11" viewBox="0 0 15 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 1L5.06257 10L1 5.90911" stroke="#5EB04C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span><?php echo ($product->is_in_stock() ? 'В наявності' : 'Немає в наявності'); ?></span>
            </div>
            <div class="relate-slider__icon-right">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/percentage.png" alt="percentage"  width="35" height="35">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/paw.png" alt="paw"  width="35" height="35">
            </div>
            <a href="#" class="relate-slider__icon-heart">
                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="26" height="26" rx="4" fill="white"/>
                    <path d="M7.01792 13.0342L12.881 18.6673L18.744 13.0342C19.3957 12.408 19.7619 11.5587 19.7619 10.6731C19.7619 8.82896 18.2059 7.33398 16.2865 7.33398C15.3648 7.33398 14.4808 7.68578 13.829 8.31199L12.881 9.22287L11.9329 8.31199C11.2811 7.68578 10.3971 7.33398 9.47541 7.33398C7.55599 7.33398 6 8.82896 6 10.6731C6 11.5587 6.36616 12.408 7.01792 13.0342Z" stroke="#E93A53" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
    </div>
    <div class="relate-slider__footer-card">
        <div class="relate-slider__rating">
            <div class="relate-slider__stars">
                <svg class="star">
                    <use xlink:href="#star"></use>
                </svg>
                <svg class="star">
                    <use xlink:href="#star"></use>
                </svg>
                <svg class="star">
                    <use xlink:href="#star"></use>
                </svg>
                <svg class="star">
                    <use xlink:href="#star"></use>
                </svg>
                <svg class="star">
                    <use xlink:href="#star"></use>
                </svg>
            </div>
            <span>5.0 (9)</span>
        </div>
        
        <h3 class="relate-slider__title"><?php the_title(); ?></h3>

        <div class="relate-slider__prices">
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
                <div class="relate-slider__current-pirce"><?php echo wc_price( $min_price ); ?></div>
                <div class="relate-slider__old-pirce"><?php echo wc_price( $min_regular_price ); ?> </div>
                <?php
            } else {
                // Show regular price range
                ?>
                <div class="relate-slider__current-pirce"><?php echo wc_price( $min_price ); ?> - <?php echo wc_price( $max_price ); ?></div>
                <?php
            }
        } else {
            // For simple products
            if ( $product->get_sale_price() ) {
                ?>
                <div class="relate-slider__current-pirce"><?php echo wc_price( $product->get_sale_price() ); ?></div>
                <div class="relate-slider__old-pirce"><?php echo wc_price( $product->get_regular_price() ); ?></div>
                <?php
            } else {
                ?>
                <div class="relate-slider__current-pirce"><?php echo wc_price( $product->get_price() ); ?></div>
                <?php
            }
        }
        ?>

        </div>

        <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="btn-black add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="<?php echo esc_attr( $product->add_to_cart_text() ); ?>" rel="nofollow">
            <span class="span1"><?php _e('В кошик', 'crabs_project'); ?></span>
            <span class="span2"><?php _e('Додати в кошик', 'crabs_project'); ?></span>
            <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>

    </div>
</section>