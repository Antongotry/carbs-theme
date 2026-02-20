<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="registration-form__top">
	<h3 class="registration-form__title">
		Оформлення замовлення
	</h3>
	<div
		class="order-details__option order-details__option--bonus registration-form__info-bonus"
	>
		<span class="order-details__label"
			>Бонуси:</span
		>
		<span class="order-details__bonus"
			>4 200 бонусів
		</span>
	</div>
</div>


<div class="registration-form__block">

    <h4 class="registration-form__heading registration-form__heading--first">Контактні дані</h4>
                                
	<div class="registration-form__details registration-form__details--contact">
		<?php
		$fields = $checkout->get_checkout_fields( 'billing' );

		foreach ( $fields as $key => $field ) {
			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		}
		?>
		<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	</div>
</div>

<div class="registration-form__block existing-form">

    <h4 class="registration-form__heading registration-form__heading--first">Дані для входу</h4>
                                
	
		<?php if ( ! is_user_logged_in() ) : ?>

		<div id="custom-login-container">
    		<div class="login-form">
		    	<div class="registration-form__details registration-form__details--contact loginForm">
			        <input type="email" id="custom_email" class="input-text" placeholder="Email" autocomplete="username" required />
	 
			        <input type="password" id="custom_password" class="input-text" placeholder="Пароль" autocomplete="current-password" required />
		        </div>

		        <div id="login-errors" style="color: red;"></div> 

		        <button id="custom-login-submit" class="btn">Увійти</button>

		        
		    </div>
		</div>

		<?php echo do_shortcode('[nextend_social_login]'); ?>

		<?php else : 


			if ( is_user_logged_in() ) {
			    $current_user = wp_get_current_user();
			    $first_name = get_user_meta($current_user->ID, 'billing_first_name', true);
			    $last_name = get_user_meta($current_user->ID, 'billing_last_name', true);
			    $phone = get_user_meta($current_user->ID, 'billing_phone', true);
			    $email = $current_user->user_email;
			} else {
			    $first_name = '';
			    $last_name = '';
			    $phone = '';
			    $email = '';
			}


		?>

	    <!-- Если пользователь зарегистрирован и вошел в систему -->
		<div class="registration-form__details registration-form__details--contact">
		    <p class="form-row form-row-first">
		        <input type="text" class="input-text validate-required" name="billing_first_name_lu" placeholder="Ім'я" value="<?php echo esc_attr( $first_name ); ?>">
		    </p>
		    <p class="form-row form-row-last">
		        <input type="text" class="input-text validate-required" name="billing_last_name_lu" placeholder="Прізвище" value="<?php echo esc_attr( $last_name ); ?>">
		    </p>
		    <p class="form-row form-row-first validate-required">
		        <input type="text" class="input-text" name="billing_phone_lu" placeholder="+38 (___) ___ ____*" value="<?php echo esc_attr( $phone ); ?>">
		    </p>
		    <p class="form-row form-row-last validate-required">
		        <input type="text" class="input-text" name="billing_email_lu" placeholder="E-mail" value="<?php echo esc_attr( $email ); ?>">
		    </p>		
		</div>

		<?php endif; ?>

		
	
</div>


<div class="registration-form__block">
	<h4 class="registration-form__heading">Доставка</h4>
	<div class="registration-form__details">
		

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

			<?php endif; ?>

			<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>

		
	</div>
</div>




<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields">
		<?php if ( ! $checkout->is_registration_required() ) : ?>

			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'woocommerce' ); ?></span>
				</label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

			<div class="create-account">
				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>




