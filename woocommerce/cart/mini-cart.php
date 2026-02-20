<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<div class="cart__main">

<?php if ( ! WC()->cart->is_empty() ) : ?>

	<article class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				/**
				 * This filter is documented in woocommerce/templates/cart/cart.php.
				 *
				 * @since 2.1.0
				 */
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<section class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
					
					<?php if ( empty( $product_permalink ) ) : ?>
						<?php echo $thumbnail; ?>
					<?php else : ?>
					<a class="cart__image" href="<?php echo esc_url( $product_permalink ); ?>">
						<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>

						
					<?php endif; ?>

					<div class="cart__section">
						<div class="cart__section-content">
							<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $product_name ); ?></a>

                            <div class="cart__prices product-price">
                                <?php
                                // Получаем объект продукта
                                $_product = wc_get_product( $cart_item['variation_id'] ? $cart_item['variation_id'] : $cart_item['product_id'] );

                                // Проверяем наличие продукта
                                if ( $_product ) {
                                    // Получаем цену товара
                                    $regular_price = $_product->get_regular_price();
                                    $sale_price = $_product->get_sale_price();
                                    $current_price = $_product->get_price(); // Текущая цена товара

                                    // Если у товара есть скидка, выводим цену с учётом скидки
                                    if ( $sale_price && $sale_price < $regular_price ) {
                                        $product_price = '<del>' . wc_price( $regular_price ) . '</del> <ins>' . wc_price( $sale_price ) . '</ins>';
                                    } else {
                                        $product_price = wc_price( $current_price );
                                    }

                                    // Умножаем цену на количество товара в корзине
                                    $total_price = $current_price * $cart_item['quantity'];
                                    // Форматируем цену
                                    $formatted_price = wc_price( $total_price );
                                } else {
                                    $formatted_price = '';
                                }

                                // Выводим цену товара с учётом количества и скидок
                                echo apply_filters( 'woocommerce_widget_cart_item_quantity', sprintf( '%s', $product_price ) , $cart_item, $cart_item_key );
                                ?>
                            </div>
						</div>
						<?php
                            $price = wc_get_price_to_display( $_product );
                            if ( $price > 0 ) $bonus = (int) round( $price * 0.01 );
                            $item_quantity = $cart_item['quantity'];
                            $bonus = $bonus * $item_quantity;
                            ?>
                            <div class="cart_bottom">
                              <div class="cart-bonus">
                                <span class="cart-bonus__plus">+</span>
                                <span class="cart-bonus__value"><?php echo esc_html( $bonus ); ?></span>
                                <span class="cart-bonus__text">крабів</span>
                              </div>
                         </div>

                        
                        <div class="quantity-controls">
                            <button class="minus" data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>">-</button>
                            <input type="text" readonly value="<?php echo $cart_item['quantity']; ?>">
                            <button class="plus" data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>">+</button>
                        </div>


						<?php
						echo apply_filters(
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<a href="%s" class="remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">
									<span class="delete-text">%s</span>
									<svg class="delete-icon"><use xlink:href="#delete"></use></svg>
								</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),  // Экранируем URL для удаления
								esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),  // Экранируем aria-label
								esc_attr( $product_id ),  // Экранируем ID продукта
								esc_attr( $cart_item_key ),  // Экранируем ключ товара в корзине
								esc_attr( $_product->get_sku() ),  // Экранируем SKU продукта
								esc_html__( 'Видалити', 'your-text-domain' )  // Используем gettext для текста кнопки удаления
							),
							$cart_item_key
						);
						?>




					</div>

					<!-- Constructor -->
					<div class="package__section-content">
						<div class="package__section-top">
							
							<a class="package__section-title" href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $product_name ); ?></a>
							<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove_from_cart_button package__delete" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">
										<svg class="delete-icon">
									<use xlink:href="#delete"></use>
									</svg>
								<span>Видалити</span></a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										/* translators: %s is the product name */
										esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
										esc_attr( $product_id ),
										esc_attr( $cart_item_key ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
							?>
							
						</div>
						<div class="package__prices">

						<?php 
							// Получаем объект продукта
							$_product = wc_get_product( $cart_item['product_id'] );

							// Проверяем наличие продукта
							if ( $_product ) {
								// Получаем цену товара с учетом скидок
								if ( $_product->is_type( 'variable' ) ) {
									// Для вариативных товаров получаем минимальную цену со скидкой
									$regular_price = $_product->get_variation_regular_price( 'min', true );
									$sale_price = $_product->get_variation_sale_price( 'min', true );

									if ( $sale_price && $sale_price < $regular_price ) {
										$product_price = '<del>' . wc_price( $regular_price ) . '</del> <ins>' . wc_price( $sale_price ) . '</ins>';
									} else {
										$product_price = wc_price( $regular_price );
									}
								} else {
									// Для простых товаров
									$regular_price = $_product->get_regular_price();
									$sale_price = $_product->get_sale_price();

									if ( $sale_price && $sale_price < $regular_price ) {
										$product_price = '<del>' . wc_price( $regular_price ) . '</del> <ins>' . wc_price( $sale_price ) . '</ins>';
									} else {
										$product_price = wc_price( $regular_price );
									}
								}

								// Умножаем цену на количество товара в корзине
								$total_price = $_product->get_price() * $cart_item['quantity'];
								// Форматируем цену
								$formatted_price = wc_price( $total_price );
							} else {
								$formatted_price = '';
							}

							// Выводим цену товара с учетом количества и скидок
							echo apply_filters( 'woocommerce_widget_cart_item_quantity', sprintf( '%s', $product_price ), $cart_item, $cart_item_key );
 
							?>
						</div>
					</div>
					<!-- Constructor -->
					
					<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</section>

				<?php
			}
		}

		do_action( 'woocommerce_mini_cart_contents' );
		?>
	</article>
	

	<div class="cart__total">
		<div class="package__totals-item modal">
		<?php
		/** 
		 * Hook: woocommerce_widget_shopping_cart_total.
		 *
		 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
		 */
		do_action( 'woocommerce_widget_shopping_cart_total' );
		?>
		</div>

		<div class="package__totals-item">
			<span class="package__count-title">Кількість товарів:</span>
			<p><span class="cart-count">0</span> шт</p>

		</div>

        <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

        <div class="cart__buttons">

            <a href="/checkout" class="btn-black">Оформити замовлення</a>
            <!--		<a href="/broniuvannia-do-polohiv/" class="btn-red book-product">Забронювати до пологів</a>-->
            <!--<a href="#" class="btn-white back-to-shop">Повернутись до покупок</a>-->
            <!-- <a class="constructor-btn btn-white" href="#">Оформити розстрочку</a> -->
             
             <!--<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" 
                    class="mono-checkout"
                    aria-label="Оформити через mono checkout">
                    <span class="mono-checkout__text">Оформити через</span>
                    <img src="https://www.crabs.ua/wp-content/uploads/2025/09/image-17.webp" alt="logo" />
                    
             </a> -->
                <!--<a href="#"-->
                <!--     class="btn-mono-custom"-->
                <!--     data-mono-action="buy_cart"-->
                <!--     aria-label="Оформити через mono checkout">-->
                <!--      Оформити через -->
                <!--      <img src="https://www.crabs.ua/wp-content/uploads/2025/09/monocheck2.webp"-->
                <!--        alt="mono checkout" class="btn-mono-custom__logo">-->
                <!--</a>-->
            
            
            
            <?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
            
            
        </div>
	</div>


	
	
	


<?php else : ?>

	<div class="empty-cart-content">
	<div class="middle-cart-box">
		<img src="/wp-content/themes/carbs-theme/img/empty-cart.svg" alt="Empty Cart">
		<p>Ваш кошик пустий, будь ласка додайте товари</p>
	</div>
	<div class="bottom-cart-box">
		<p>Всього: <span>0 ₴</span></p>
	</div>
	<a href="/shop/" class="btn-black"><span>До каталогу</span></a>
</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
</div>

<!--<?php echo do_shortcode('[first_added_upsell_products]'); ?>-->


