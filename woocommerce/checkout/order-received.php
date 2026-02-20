<?php
/**
 * "Order received" message.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/order-received.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.8.0
 *
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="outcome outcome--success">
	<div class="container">
		<img
			src="<?php echo get_stylesheet_directory_uri(); ?>/img/icons/success.svg"
			alt="success"
			class="outcome__img"
		/>
		<h2 class="title outcome__title">
			Ваше замовлення успішно сформоване та очікує
			підтвердження
		</h2>
		<p class="outcome__text">
			Ми вдячні, що ви обрали саме Нас. Наш спеціаліст
			звʼяжеться з вами для підтвердження замовлення.
		</p>
		<div class="outcome__btns">
			<a href="/shop/" class="btn outcome__btn"
				>Продовжити покупки</a
			>
			<a
				href="/"
				class="btn btn--light outcome__btn"
				>Повернутися на головну</a
			>
		</div>
	</div>
</section>
