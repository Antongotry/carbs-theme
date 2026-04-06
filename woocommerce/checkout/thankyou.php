<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="success">

<?php if ( $order ) : ?>

<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

<?php if ( $order->has_status( 'failed' ) ) : ?>

	<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
		<?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?>
	</p>

	<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
		<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay">
			<?php esc_html_e( 'Pay', 'woocommerce' ); ?>
		</a>
		<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay">
				<?php esc_html_e( 'My account', 'woocommerce' ); ?>
			</a>
		<?php endif; ?>
	</p>

<?php else : ?>

	<?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>


<section class="order-details">
    <div class="container">
        <div class="order-details__body">
            <h4 class="order-details__title">Деталі замовлення</h4>

            

                    <div class="order-details__top">
                        <div class="order-details__option">
                            <span class="order-details__label">Номер замовлення:</span>
                            <span class="order-details__value">№<?php echo $order->get_order_number(); ?></span>
                        </div>
                        <div class="order-details__option">
                            <span class="order-details__label">Дата:</span>
                            <span class="order-details__value"><?php echo wc_format_datetime( $order->get_date_created() ); ?></span>
                        </div>
                        <?php if ( $order->get_payment_method_title() ) : ?>
                            <div class="order-details__option">
                                <span class="order-details__label">Спосіб оплати:</span>
                                <span class="order-details__value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="order-details__option">
                            <span class="order-details__label">Адреса доставки:</span>
                            <span class="order-details__value"><?php echo $order->billing_address_1;; ?></span>
                        </div>
                    </div>
                    <div class="order-details__bottom">
                        <div class="order-details__option">
                            <span class="order-details__label">Сума:</span>
                            <span class="order-details__value"><?php echo $order->get_formatted_order_total(); ?></span>
                        </div>
                        <div class="order-details__option">
                            <span class="order-details__label">Доставка:</span>
                            <span class="order-details__value">Згідно тарифів перевізника</span>
                        </div>
                        <div class="order-details__option order-details__option--bonus">
                            <span class="order-details__label">Бонуси:</span>
                            <span class="order-details__bonus">+4 200 бонусів</span>
                        </div>
                    </div>

                <?php endif; ?>




            
        </div>
    </div>
</section>


<section class="product">
	<div class="container">
		<div class="product__list">
			<h4 class="order-details__title product__title">Товар</h4>
			<?php
			$items = $order->get_items();
			foreach ( $items as $item_id => $item ) {
				$product = $item->get_product();
				$product_name = $item->get_name();
				$product_quantity = $item->get_quantity();
				$product_total = $item->get_total();
				$product_image = wp_get_attachment_image_src( $product->get_image_id(), 'thumbnail' );
				?>
				<div class="product__item">
					<div class="product__image">
						<img src="<?php echo esc_url($product_image[0]); ?>" alt="<?php echo esc_attr($product_name); ?>" />
					</div>
					<div class="product__info">
						<div class="product__details">
							<p class="product__name">
								<?php echo esc_html($product_name); ?>
							</p>
							<p class="product__price">
								<?php echo wc_price($product_total); ?>
							</p>
						</div>
						<div class="product__quantity">
							<span class="product__label">К-сть штук:</span>
							<span class="product__value"><?php echo esc_html($product_quantity); ?> шт</span>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</section>


<?php else : ?>

<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

<?php endif; ?>

</div>