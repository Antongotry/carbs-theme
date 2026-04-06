<?php
/**
* Template Name: Забронювати до пологів
*
* @package WordPress
* @subpackage Crabs Theme
* @since Crabs Theme 1.0
*/

get_header(); 



?>

<section class="booking">
    <div class="container">
        <h2 class="title orders__title booking__title">
            <?php the_title(); ?>
        </h2>
        <div class="orders__buttons">
            <a href="#orederTab1" class="btn btn--light">Я новий покупець</a>
            <a href="#orederTab2" class="btn btn--light">Я діючий клієнт</a>
        </div>

        <div class="orders__content">
            <div class="orders__registration registration-form" id="orederTab1">

                <?php echo do_shortcode('[contact-form-7 id="2d91967" title="Контактна форма 1"]') ?>
                
            </div>
            
            <form class="orders__registration registration-form" id="orederTab2">
                <div class="registration-form__top">
                    <h3
                        class="registration-form__title booking__subtitle"
                    >
                        Оформлення бронювання2
                    </h3>
                    <p class="booking__text">
                        Lorem ipsum dolor sit amet, consectetur
                        adipiscing elit, sed do eiusmod tempor
                        incididunt ut labore et dolore magna aliqua.
                        Ut enim ad minim veniam, quis nostrud
                        exercitation ullamco laboris nisi ut aliquip
                        ex ea commodo consequat.
                    </p>
                </div>

                <?php if ( ! is_user_logged_in() ) : ?>
                <div class="registration-form__block">

                    
                                                
                    
                        

                            <h4 class="registration-form__heading registration-form__heading--first">Дані для входу</h4>

                        <div id="custom-login-container">
                            <div class="login-form">
                                <div class="rregistration-form__details registration-form__details--contact loginForm">
                                    <input type="email" id="custom_email" class="registration-form__input registration-form__input--nam" placeholder="Email" autocomplete="username" required />
                     
                                    <input type="password" id="custom_password" class="registration-form__input registration-form__input--nam" placeholder="Пароль" autocomplete="current-password" required />
                                </div>

                                <div id="login-errors" style="color: red;"></div> 

                                <button id="custom-login-submit" class="btn">Увійти</button>

                                
                            </div>
                        </div>

                        <?php echo do_shortcode('[nextend_social_login]'); ?>

                        

                        
                    
                </div>
                <?php else : ?>

                        <!-- Если пользователь зарегистрирован и вошел в систему -->
                        <button id="custom-login-submit" class="btn">Забронювати</button>

                        <?php endif; ?>


            </form>




        
            <div class="orders__info">
                <h3 class="registration-form__title">Ваше замовлення</h3>

                <?php if ( ! WC()->cart->is_empty() ) : ?>

                <ul class="orders__list">
                    <?php
                    do_action( 'woocommerce_before_mini_cart_contents' );

                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                            $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                            $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                            $product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                            ?>
                            <li class="product__item woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
                                
                                <?php if ( empty( $product_permalink ) ) : ?>
                                    <div class="product__image">
                                        <?php echo $thumbnail; ?>
                                    </div>
                                    
                                <?php else : ?>

                                    <div class="product__image">
                                        <a href="<?php echo esc_url( $product_permalink ); ?>">
                                            <?php echo $thumbnail; ?>
                                        </a>
                                    </div>
                                    <div class="product__info">
                                        <div class="product__details">
                                            <p class="product__name">
                                                <?php echo wp_kses_post( $product_name ); ?>
                                            </p>
                                            
                                            <?php
                                                echo apply_filters(
                                                    'woocommerce_cart_item_remove_link',
                                                    sprintf(
                                                        '<a href="%s" class="product__delete remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"><span>Видалити</span><svg width="19" height="23" viewBox="0 0 19 23" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.875 22.4688H6.125C5.00612 22.4688 3.93306 22.0243 3.14189 21.2331C2.35072 20.4419 1.90625 19.3689 1.90625 18.25V8.125C1.90625 7.90122 1.99515 7.68661 2.15338 7.52838C2.31161 7.37015 2.52622 7.28125 2.75 7.28125C2.97378 7.28125 3.18839 7.37015 3.34662 7.52838C3.50486 7.68661 3.59375 7.90122 3.59375 8.125V18.25C3.59375 18.9213 3.86043 19.5652 4.33514 20.0399C4.80984 20.5146 5.45367 20.7812 6.125 20.7812H12.875C13.5463 20.7812 14.1902 20.5146 14.6649 20.0399C15.1396 19.5652 15.4062 18.9213 15.4062 18.25V8.125C15.4062 7.90122 15.4951 7.68661 15.6534 7.52838C15.8116 7.37015 16.0262 7.28125 16.25 7.28125C16.4738 7.28125 16.6884 7.37015 16.8466 7.52838C17.0049 7.68661 17.0938 7.90122 17.0938 8.125V18.25C17.0938 19.3689 16.6493 20.4419 15.8581 21.2331C15.0669 22.0243 13.9939 22.4688 12.875 22.4688Z" fill="#E93A53"/><path d="M17.9375 5.59375H1.0625C0.838724 5.59375 0.624113 5.50486 0.465879 5.34662C0.307645 5.18839 0.21875 4.97378 0.21875 4.75C0.21875 4.52622 0.307645 4.31161 0.465879 4.15338C0.624113 3.99515 0.838724 3.90625 1.0625 3.90625H17.9375C18.1613 3.90625 18.3759 3.99515 18.5341 4.15338C18.6924 4.31161 18.7812 4.52622 18.7812 4.75C18.7812 4.97378 18.6924 5.18839 18.5341 5.34662C18.3759 5.50486 18.1613 5.59375 17.9375 5.59375Z" fill="#E93A53"/><path d="M12.875 5.59375H6.125C5.90122 5.59375 5.68661 5.50486 5.52838 5.34662C5.37015 5.18839 5.28125 4.97378 5.28125 4.75V3.0625C5.28125 2.39117 5.54793 1.74734 6.02264 1.27264C6.49734 0.797935 7.14117 0.53125 7.8125 0.53125H11.1875C11.8588 0.53125 12.5027 0.797935 12.9774 1.27264C13.4521 1.74734 13.7188 2.39117 13.7188 3.0625V4.75C13.7188 4.97378 13.6299 5.18839 13.4716 5.34662C13.3134 5.50486 13.0988 5.59375 12.875 5.59375ZM6.96875 3.90625H12.0312V3.0625C12.0312 2.83872 11.9424 2.62411 11.7841 2.46588C11.6259 2.30764 11.4113 2.21875 11.1875 2.21875H7.8125C7.58872 2.21875 7.37411 2.30764 7.21588 2.46588C7.05764 2.62411 6.96875 2.83872 6.96875 3.0625V3.90625Z" fill="#E93A53"/><path d="M7.8125 17.4062C7.58872 17.4062 7.37411 17.3174 7.21588 17.1591C7.05765 17.0009 6.96875 16.7863 6.96875 16.5625V10.6562C6.96875 10.4325 7.05765 10.2179 7.21588 10.0596C7.37411 9.9014 7.58872 9.8125 7.8125 9.8125C8.03628 9.8125 8.25089 9.9014 8.40912 10.0596C8.56736 10.2179 8.65625 10.4325 8.65625 10.6562V16.5625C8.65625 16.7863 8.56736 17.0009 8.40912 17.1591C8.25089 17.3174 8.03628 17.4062 7.8125 17.4062Z" fill="#E93A53"/><path d="M11.1875 17.4062C10.9637 17.4062 10.7491 17.3174 10.5909 17.1591C10.4326 17.0009 10.3438 16.7863 10.3438 16.5625V10.6562C10.3438 10.4325 10.4326 10.2179 10.5909 10.0596C10.7491 9.9014 10.9637 9.8125 11.1875 9.8125C11.4113 9.8125 11.6259 9.9014 11.7841 10.0596C11.9424 10.2179 12.0312 10.4325 12.0312 10.6562V16.5625C12.0312 16.7863 11.9424 17.0009 11.7841 17.1591C11.6259 17.3174 11.4113 17.4062 11.1875 17.4062Z" fill="#E93A53"/></svg></a>',
                                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                        esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                                                        esc_attr( $product_id ),
                                                        esc_attr( $cart_item_key ),
                                                        esc_attr( $_product->get_sku() )
                                                    ),
                                                    $cart_item_key
                                                );
                                                ?>
                                        </div>
                                        <p class="product__price">
                                            <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="product-price">' . sprintf( '%s', $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                            </li>

                            <?php
                        }
                    }

                    do_action( 'woocommerce_mini_cart_contents' );
                    ?>
                </ul>

            <div class="orders__bottom">
                <div class="orders__sum">
                    <div class="orders__bottom-block">
                        <h4 class="orders__bottom-title">
                            Сума замовлення:
                        </h4>
                        <p class="orders__bottom_price">
                            <?php echo WC()->cart->get_cart_total(); ?>
                        </p>
                    </div>
                    <div class="orders__bottom-block">
                        <h4 class="orders__bottom-title orders__bottom-title--delivery">
                            Термін бронювання:
                        </h4>
                        <p class="orders__bottom-delivery">
                            2 місяці
                        </p>
                    </div>
                </div>
                <div class="orders__total">
                    <div class="orders__bottom-block">
                        <h4 class="orders__bottom-title">
                            Вартість бронювання:
                        </h4>
                        <div class="orders__bottom-option">
                            <p class="o_price">
                                <?php echo WC()->cart->get_cart_total(); ?>
                            </p>

                        </div>
                    </div>
                </div>
            </div>

            <?php else : ?>

            <p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'Немає продуктів для .', 'woocommerce' ); ?></p>

                <?php endif; ?>

            </div>
        </div>
    </div>
</section>
               

<script>
jQuery(document).ready(function($) {
    // Читаем номер бронирования из cookies, если он уже существует
    var bookingNumber = $.cookie('booking_number');

    // Логируем состояние cookies при загрузке страницы
    console.log("Booking number from cookies at start: " + bookingNumber);

    // Если номер бронирования не существует, генерируем новый
    if (!bookingNumber) {
        bookingNumber = 'BOOKING-' + Math.floor(Math.random() * 1000000); // Генерация случайного номера бронирования
        $.cookie('booking_number', bookingNumber, { path: '/', expires: 7 }); // Сохраняем в cookies на 7 дней

        // Логируем сохранение cookie
        console.log("Generated new booking number: " + bookingNumber);
        console.log("Booking number saved in cookies: " + $.cookie('booking_number')); // Логируем после сохранения в cookies
    }

    // Заполняем скрытое поле "booking-number" при загрузке страницы
    $('input[name="booking-number"]').val(bookingNumber); // Заполняем скрытое поле номером бронирования
    console.log('Hidden field booking-number value on page load: ' + $('input[name="booking-number"]').val());

    // === Логика для передачи данных корзины ===
    var cartData = [];

    // Проходим по каждому товару в корзине и сохраняем его данные
    $('.product__item').each(function() {
        var product = {
            name: $(this).find('.product__name').text(),  // Название продукта
            price: $(this).find('.product__price').text(), // Цена продукта
            quantity: $(this).find('.product__quantity .product__value').length
                ? $(this).find('.product__quantity .product__value').text()
                : '1', // Если количество не указано, предполагаем, что 1
        };
        cartData.push(product);
    });

    // Формируем текст для каждого товара с количеством на новой строке
    var productTitles = cartData.map(function(product) {
        return product.name + " — " + product.quantity + " шт.";
    }).join('\n');  // Используем перенос строки для каждого товара

    // Заполняем скрытое поле "product_titles" названиями продуктов
    $('input[name="product_titles"]').val(productTitles);
    console.log('Hidden field product_titles value on page load: ' + $('input[name="product_titles"]').val());

    // Отправка данных корзины в куки перед отправкой формы
    document.addEventListener('wpcf7mailsent', function(event) {
        var cartData = [];

        // Проходим по каждому товару в корзине и сохраняем его данные
        $('.product__item').each(function() {
            var product = {
                name: $(this).find('.product__name').text(),
                price: $(this).find('.product__price').text(),
                quantity: $(this).find('.product__quantity .product__value').length
                    ? $(this).find('.product__quantity .product__value').text()
                    : '1', // Если количество не указано, предполагаем, что 1
                image: $(this).find('.product__image img').attr('src') // Сохраняем URL изображения
            };
            cartData.push(product);
        });

        // Логируем данные корзины для проверки
        console.log("Cart data to save in cookies: ", cartData);

        // Сохраняем данные корзины в куки (действуют 7 дней)
        $.cookie('cart_data', JSON.stringify(cartData), { path: '/', expires: 7 });

        // Сохраняем номер заказа и сумму в куки
        var orderTotal = $('.orders__bottom_price').text();
        var orderNumber = 'ORDER-' + Math.floor(Math.random() * 1000000); // Генерация случайного номера заказа

        console.log("Order total: " + orderTotal);
        console.log("Generated order number: " + orderNumber);

        $.cookie('order_total', orderTotal, { path: '/', expires: 7 });
        $.cookie('order_number', orderNumber, { path: '/', expires: 7 });

        // Перенаправляем на страницу благодарности
        window.location.href = "/thank-you/";
    }, false);

    // Заполнение скрытого поля с номером бронирования перед отправкой формы CF7
    document.addEventListener('wpcf7beforesubmit', function(event) {
        // Берем номер бронирования из cookies
        var bookingNumberFromCookie = $.cookie('booking_number');

        // Логируем значение из cookies
        console.log("Booking number from cookies before form submit: " + bookingNumberFromCookie);

        // Если куки с номером бронирования есть, заполняем скрытое поле
        if (bookingNumberFromCookie) {
            $('input[name="booking-number"]').val(bookingNumberFromCookie);
            console.log('Hidden field booking-number value before submit: ' + $('input[name="booking-number"]').val());
        } else {
            console.log("Booking number not found in cookies.");
        }
    }, false);
});


// Логика для залогиненных пользователей

jQuery(document).ready(function($){
    $('#custom-login-submit').on('click', function(e) {
        e.preventDefault();
        
        // Получаем данные из корзины
        var cartData = [];
        $('.woocommerce-mini-cart-item').each(function() {
            var productTitle = $(this).find('.product__name').text();
            var productPrice = $(this).find('.product__price').text();
            var productId = $(this).find('.remove_from_cart_button').data('product_id'); // Получаем ID продукта
            
            cartData.push({
                id: productId,
                title: productTitle,
                price: productPrice
            });
        });

        console.log(cartData); // Проверяем данные корзины

        // Отправляем AJAX запрос для сохранения данных в базу
        $.ajax({
            url: review_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'save_cart_to_bookings',
                cart_items: cartData,
                nonce: review_ajax_obj.nonce
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    window.location.href = '/thank-you/';
                } else {
                    console.log('Ошибка бронирования');
                }
            }
        });
    });
});





</script>
    

<?php
get_footer();
?>
