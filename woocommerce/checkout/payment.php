<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
	<div id="payment" class="woocommerce-checkout-payment">
		<div class="form-row place-order">
			<noscript>
				<?php
				/* translators: $1 and $2 opening and closing emphasis tags respectively */
				printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
				?>
				<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
			</noscript>

			<?php wc_get_template( 'checkout/terms.php' ); ?>

			<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

				<div class="registration-form__bottom">
					<div class="registration-form__submit">

					<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button t554 alt registration-form__btn btn" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr('Оформити замовлення') . '" data-value="' . esc_attr( 'Оформити замовлення' ) . '">' . esc_html( 'Оформити замовлення' ) . '</button>' ); // @codingStandardsIgnoreLine ?>

					</div>

					<label for="privacy-policy" class="registration-form__label registration-form__label--last">
						<input type="radio" name="privacy-policy" id="privacy-policy" value="privacy-policy" checked="">
						<span class="registration-form__custom-checkbox"></span>
						<a href="/privacy-policy/">Я погоджуюсь з політикою
						конфіденційності</a>
					</label>
				</div>

			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

			<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
		</div>
	</div>
<?php
if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}