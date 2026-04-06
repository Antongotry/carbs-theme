<?php
/**
 * Single payment method
 *
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

$extra_gateway_class = $gateway->id === 'liqpay' ? ' payment_method_mono_gateway' : '';
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?><?php echo esc_attr( $extra_gateway_class ); ?>">
    <input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

    <label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
        <?php echo $gateway->get_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php echo $gateway->get_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </label>

    <?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
        <div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
            <?php $gateway->payment_fields(); ?>
        </div>
    <?php endif; ?>
</li>
