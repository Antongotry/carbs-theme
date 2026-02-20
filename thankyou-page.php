<?php
/**
* Template Name: Сторінка вдячності
* @package WordPress
* @subpackage Crabs Theme
* @since Crabs Theme 1.0
*/

get_header(); ?>

<div class="success">

<section class="outcome outcome--success">
    <div class="container">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/icons/success.svg" alt="success" class="outcome__img"/>
        <h2 class="title outcome__title">
            Ваше замовлення успішно сформоване та очікує підтвердження
        </h2>
        <p class="outcome__text">
            Ми вдячні, що ви обрали саме Нас. Наш спеціаліст звʼяжеться з вами для підтвердження замовлення.
        </p>
        <div class="outcome__btns">
            <a href="/shop" class="btn outcome__btn">Продовжити покупки</a>
            <a href="/" class="btn btn--light outcome__btn">Повернутися на головну</a>
        </div>
    </div>
</section>




<?php 

if (!is_user_logged_in()) { ?>

<!--<section class="order-details">-->
<!--    <div class="container">-->
<!--        <div class="order-details__body">-->
<!--            <h4 class="order-details__title">Деталі замовлення</h4>-->
<!--            <div class="order-details__top">-->
<!--                <div class="order-details__option">-->
<!--                    <span class="order-details__label">Номер бронювання:</span>-->
<!--                    <span class="order-details__value" id="booking-number">---</span> <!-- Номер бронирования -->
<!--                </div>-->
<!--                <div class="order-details__option">-->
<!--                    <span class="order-details__label">Дата:</span>-->
<!--                    <span class="order-details__value"><?php echo date('d.m.Y'); ?></span>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="order-details__bottom">-->
<!--                <div class="order-details__option">-->
<!--                    <span class="order-details__label">Сума:</span>-->
<!--                    <span class="order-details__value" id="order-total">---</span>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->

<!--<section class="product">-->
<!--    <div class="container">-->
<!--        <div class="product__list" id="product-list">-->
<!--            <h4 class="order-details__title product__title">Товар</h4>-->
            <!-- Данные о товарах будут динамически вставлены здесь -->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->

<?php } else{ 

echo do_shortcode('[user_bookings]'); 

} ?>


</div>

<script>
jQuery(document).ready(function($) {
    // Чтение данных из куки
    var cartData = $.cookie('cart_data');
    var orderNumber = $.cookie('order_number');
    var orderTotal = $.cookie('order_total');
    var bookingNumber = $.cookie('booking_number'); // Чтение номера бронирования из cookies

    // Проверяем, есть ли данные о заказе
    if (cartData && orderNumber && orderTotal && bookingNumber) {
        cartData = JSON.parse(cartData);

        // Вставляем номер заказа, номер бронирования и сумму
        $('#order-number').text(orderNumber);
        $('#booking-number').text(bookingNumber); // Вставляем номер бронирования
        $('#order-total').text(orderTotal);

        // Вставляем информацию о товарах с нужной структурой
        $.each(cartData, function(index, product) {
            $('#product-list').append(
                '<div class="product__item">' +
                    '<div class="product__image">' +
                        '<img src="' + product.image + '" alt="' + product.name + '">' + // Используем URL изображения из cookies
                    '</div>' +
                    '<div class="product__info">' +
                        '<div class="product__details">' +
                            '<p class="product__name">' + product.name + '</p>' +
                            '<p class="product__price">' + product.price + '</p>' +
                        '</div>' +
                        '<div class="product__quantity">' +
                            '<span class="product__label">К-сть штук:</span>' +
                            '<span class="product__value">' + product.quantity + ' шт</span>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );
        });

        // Очищаем куки после использования
        $.removeCookie('cart_data', { path: '/' });
        $.removeCookie('order_number', { path: '/' });
        $.removeCookie('order_total', { path: '/' });
        $.removeCookie('booking_number', { path: '/' }); // Очищаем номер бронирования
    }
});
</script>

<?php get_footer(); ?>


