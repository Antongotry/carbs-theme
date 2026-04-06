<?php
/**
* Template Name: Заброньовані замовлення
* @package WordPress
* @subpackage Crabs Theme
* @since Crabs Theme 1.0
*/

// Начало буферизации вывода
ob_start();

get_header(); 

// Проверка авторизации
if (!is_user_logged_in()) {
    echo '<p>Вы не авторизованы. Пожалуйста, войдите, чтобы просмотреть свои заказы.</p>';
    get_footer();
    ob_end_flush(); // Очищаем буфер после завершения
    exit;
}

global $wpdb;
$user_id = get_current_user_id();

// Включение вывода ошибок
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Установка локали для украинского языка
setlocale(LC_TIME, 'uk_UA.UTF-8');

// Проверяем, была ли отправлена форма на удаление бронирования
if (isset($_POST['cancel_booking']) && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);
    
    // Логируем попытку удаления бронирования
    error_log('Попытка удаления бронирования с ID: ' . $booking_id);

    // Удаляем запись из базы данных
    $deleted = $wpdb->delete('wp_user_bookings', ['id' => $booking_id, 'user_id' => $user_id]);

    // Проверяем, успешно ли удалена запись
    if ($deleted) {
        error_log('Бронирование успешно удалено: ID ' . $booking_id);
    } else {
        error_log('Ошибка при удалении бронирования: ' . $wpdb->last_error);
        echo '<p>Ошибка при удалении бронирования. Пожалуйста, попробуйте снова.</p>';
    }

    // Очистка буфера и перенаправление
    ob_clean();
    wp_safe_redirect(get_permalink());
    exit();
}

// Получаем забронированные товары для текущего пользователя
$bookings = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM wp_user_bookings WHERE user_id = %d AND expiration_date > NOW()",
        $user_id
    )
);

// Получаем дату истечения последнего бронирования
$last_booking_expiration_date = !empty($bookings) ? $bookings[0]->expiration_date : null;

?>

<section class="reserved-orders">
    <div class="container">
        <div class="reserved-orders__block">
            <div class="reserved-orders__header">
                <div class="reserved-orders__content">
                    <h2 class="title reserved-orders__title">
                        Заброньовані замовлення
                    </h2>
                    <p class="reserved-orders__text">
                        У цьому розділі відображаються ваші замовлення, які були заброньовані до пологів.
						У вас є можливість відслідковувати, до коли дійсне бронювання, щоб вчасно завершити
						його повну оплату. Якщо у вас будуть питання стосовно заброньованих товарів,
						звертайтесь в наш відділ турботи. 
                    </p>
                </div>

                <!-- Блок с датой истечения бронирования -->
                <?php if ($last_booking_expiration_date) : ?>
                    <span class="btn btn--reminder reserved-orders__reminder">
                        Ваше бронювання дійсне до: <?php echo date_i18n('d F Y', strtotime($last_booking_expiration_date)); ?> року
                    </span>
                <?php endif; ?>
            </div>
            <div class="reserved-orders__body">
                <h4 class="order-details__title">
                    Ваші замовлення
                </h4>

                <div class="reserved-orders__list">
                    <?php if (!empty($bookings)) : ?>
                        <?php foreach ($bookings as $booking) : ?>
                            <div class="reserved-orders__item">
                                <!-- Название товара и картинка -->
                                <div class="reserved-orders__option">
                                    <span class="reserved-orders__label">Назва</span>
                                    <div class="reserved-orders__details">
                                        <div class="reserved-orders__image">
                                            <img src="<?php echo esc_url($booking->image_url); ?>" alt="<?php echo esc_attr($booking->product_name); ?>" />
                                        </div>
                                        <div class="reserved-orders__info">
                                            <p class="reserved-orders__name">
                                                <?php echo esc_html($booking->product_name); ?>
                                            </p>
                                            <p class="reserved-orders__price">
                                                <?php echo number_format((float)$booking->price, 0, '', ' ') . ' ₴'; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Дата бронирования -->
                                <div class="reserved-orders__option">
                                    <span class="reserved-orders__label">Дата бронювання:</span>
                                    <div class="reserved-orders__group">
                                        <p class="reserved-orders__date">
                                            <?php echo date_i18n('d.m.Y', strtotime($booking->booking_date)); ?> - <?php echo date_i18n('d.m.Y', strtotime($booking->expiration_date)); ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Количество -->
                                <div class="reserver-orders__option">
                                    <span class="reserved-orders__label">Кількість:</span>
                                    <div class="reserved-orders__group">
                                        <p><?php echo esc_html($booking->quantity); ?> шт</p>
                                    </div>
                                </div>

                                <!-- Сумма брони -->
                                <div class="reserved-orders__option">
                                    <span class="reserved-orders__label reserved-orders__label--price">Сума броні:</span>
                                    <p class="reserved-orders__reservation-price">
                                        <?php echo number_format((float)$booking->reservation_price, 0, '', ' ') . ' ₴'; ?>
                                    </p>
                                </div>

                                <!-- Кнопка отмены бронирования -->
                                <div class="reserved-orders__option reserved-orders__option--btn">
                                    <form method="post" action="">
                                        <input type="hidden" name="booking_id" value="<?php echo esc_attr($booking->id); ?>">
                                        <button type="submit" name="cancel_booking" class="btn btn--order">Скасувати бронювання</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>У вас немає заброньованих товарів.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
get_footer(); 

// Завершаем буферизацию
ob_end_flush();
