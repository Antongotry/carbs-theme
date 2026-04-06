<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="orders__list">

		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

			$_product = $cart_item['data'];
			$product_id = $cart_item['product_id'];
			$product_name = $_product->get_name();
			$product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
			$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'product__item', $cart_item, $cart_item_key ) ); ?>">

					
					
					<!-- Product Thumbnail -->
					<div class="product__image">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

						if ( ! $product_permalink ) {
							echo $thumbnail; // PHPCS: XSS ok.
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
						}
						?>
					</div>

					<!-- Product Title And Price -->
					<div class="product__info">

						<div class="product__details">

							<div class="product__name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
								<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
							</div>

							<!-- Block Product Quantity -->
							<!-- <div class="product-quantity" data-title="<?php //esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
								<p><?php //echo esc_html( $cart_item['quantity'] ); esc_attr_e( ' шт', 'woocommerce' ); ?></p>
							</div> -->

							<!-- Remove Button -->
							<div class="product__delete">
								<span>Видалити</span>


								<?php
									
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s"  aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg width="19" height="23" viewBox="0 0 19 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12.875 22.4688H6.125C5.00612 22.4688 3.93306 22.0243 3.14189 21.2331C2.35072 20.4419 1.90625 19.3689 1.90625 18.25V8.125C1.90625 7.90122 1.99515 7.68661 2.15338 7.52838C2.31161 7.37015 2.52622 7.28125 2.75 7.28125C2.97378 7.28125 3.18839 7.37015 3.34662 7.52838C3.50486 7.68661 3.59375 7.90122 3.59375 8.125V18.25C3.59375 18.9213 3.86043 19.5652 4.33514 20.0399C4.80984 20.5146 5.45367 20.7812 6.125 20.7812H12.875C13.5463 20.7812 14.1902 20.5146 14.6649 20.0399C15.1396 19.5652 15.4062 18.9213 15.4062 18.25V8.125C15.4062 7.90122 15.4951 7.68661 15.6534 7.52838C15.8116 7.37015 16.0262 7.28125 16.25 7.28125C16.4738 7.28125 16.6884 7.37015 16.8466 7.52838C17.0049 7.68661 17.0938 7.90122 17.0938 8.125V18.25C17.0938 19.3689 16.6493 20.4419 15.8581 21.2331C15.0669 22.0243 13.9939 22.4688 12.875 22.4688Z" fill="#E93A53"></path>
                                                        <path d="M17.9375 5.59375H1.0625C0.838724 5.59375 0.624113 5.50486 0.465879 5.34662C0.307645 5.18839 0.21875 4.97378 0.21875 4.75C0.21875 4.52622 0.307645 4.31161 0.465879 4.15338C0.624113 3.99515 0.838724 3.90625 1.0625 3.90625H17.9375C18.1613 3.90625 18.3759 3.99515 18.5341 4.15338C18.6924 4.31161 18.7812 4.52622 18.7812 4.75C18.7812 4.97378 18.6924 5.18839 18.5341 5.34662C18.3759 5.50486 18.1613 5.59375 17.9375 5.59375Z" fill="#E93A53"></path>
                                                        <path d="M12.875 5.59375H6.125C5.90122 5.59375 5.68661 5.50486 5.52838 5.34662C5.37015 5.18839 5.28125 4.97378 5.28125 4.75V3.0625C5.28125 2.39117 5.54793 1.74734 6.02264 1.27264C6.49734 0.797935 7.14117 0.53125 7.8125 0.53125H11.1875C11.8588 0.53125 12.5027 0.797935 12.9774 1.27264C13.4521 1.74734 13.7188 2.39117 13.7188 3.0625V4.75C13.7188 4.97378 13.6299 5.18839 13.4716 5.34662C13.3134 5.50486 13.0988 5.59375 12.875 5.59375ZM6.96875 3.90625H12.0312V3.0625C12.0312 2.83872 11.9424 2.62411 11.7841 2.46588C11.6259 2.30764 11.4113 2.21875 11.1875 2.21875H7.8125C7.58872 2.21875 7.37411 2.30764 7.21588 2.46588C7.05764 2.62411 6.96875 2.83872 6.96875 3.0625V3.90625Z" fill="#E93A53"></path>
                                                        <path d="M7.8125 17.4062C7.58872 17.4062 7.37411 17.3174 7.21588 17.1591C7.05765 17.0009 6.96875 16.7863 6.96875 16.5625V10.6562C6.96875 10.4325 7.05765 10.2179 7.21588 10.0596C7.37411 9.9014 7.58872 9.8125 7.8125 9.8125C8.03628 9.8125 8.25089 9.9014 8.40912 10.0596C8.56736 10.2179 8.65625 10.4325 8.65625 10.6562V16.5625C8.65625 16.7863 8.56736 17.0009 8.40912 17.1591C8.25089 17.3174 8.03628 17.4062 7.8125 17.4062Z" fill="#E93A53"></path>
                                                        <path d="M11.1875 17.4062C10.9637 17.4062 10.7491 17.3174 10.5909 17.1591C10.4326 17.0009 10.3438 16.7863 10.3438 16.5625V10.6562C10.3438 10.4325 10.4326 10.2179 10.5909 10.0596C10.7491 9.9014 10.9637 9.8125 11.1875 9.8125C11.4113 9.8125 11.6259 9.9014 11.7841 10.0596C11.9424 10.2179 12.0312 10.4325 12.0312 10.6562V16.5625C12.0312 16.7863 11.9424 17.0009 11.7841 17.1591C11.6259 17.3174 11.4113 17.4062 11.1875 17.4062Z" fill="#E93A53"></path>
                                                    </svg></a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											/* translators: %s is the product name */
											esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() )
										),
										$cart_item_key
									);
								?>
							</div>

						</div>

						<div class="package__prices" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
							
							<?php
								$regular_price = $_product->get_regular_price();
								$sale_price = $_product->get_sale_price();
								$quantity = $cart_item['quantity'];
						
								// Вычисление суммы скидки
								$discount = 0;
								if ( $sale_price && $regular_price > $sale_price ) {
									$discount = $regular_price * $quantity;
								} ?>
						
								
								<span class="current-price">
								<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.  ?>

								</span>


								<?php // Вывод общей цены и суммы скидки
								if ( $discount > 0 ) {
									echo '<span class="old-price active">' . wc_price( $discount )  . '</span> ';
								}
							?>
							
						</div>



					</div>

				</div>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>

		
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<p><?php wc_cart_totals_coupon_label( $coupon ); ?></p>
				<p><?php wc_cart_totals_coupon_html( $coupon ); ?></p>
			</div>
		<?php endforeach; ?>

		

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="fee">
				<p><?php echo esc_html( $fee->name ); ?></p>
				<p><?php wc_cart_totals_fee_html( $fee ); ?></p>
			</div>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<p><?php echo esc_html( $tax->label ); ?></p>
						<p><?php echo wp_kses_post( $tax->formatted_amount ); ?></p>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="tax-total">
					<p><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></p>
					<p><?php wc_cart_totals_taxes_total_html(); ?></p>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		
		

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

</section>

<div class="orders__bottom">
    <div class="orders__sum">
		<div class="orders__bottom-block">
			<h4 class="orders__bottom-title">Сума:</h4>
			<p class="orders__bottom_price"><?php wc_cart_totals_order_total_html(); ?></p>
		</div>
		<div class="orders__bottom-block">
			<h4 class="orders__bottom-title orders__bottom-title--delivery"><?php esc_html_e( 'Доставка:', 'woocommerce' ); ?></h4>
			<p class="orders__bottom-delivery"><?php esc_html_e( '1–2 дні. Згідно тарифів перевізника', 'woocommerce' ); ?></p>
		</div>

	</div>

	<div class="orders__total">
		<div class="orders__bottom-block">
			
				<h4 class="orders__bottom-title"><?php esc_html_e( 'Загальна вартість:', 'woocommerce' ); ?></h4>

				<div class="orders__bottom-option">
				<p class="orders__bottom-option"><?php wc_cart_totals_order_total_html(); ?></p>
				<span class="order-details__bonus">+отримав крабів</span>
				</div>
			
		</div>
	</div>
</div>


