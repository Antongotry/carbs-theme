<?php
// ==== Дані поточного товару для форми швидкого замовлення ====
global $product;

$product_id    = 0;
$product_title = '';
$product_img   = '';
$product_url   = '';
$product_sku   = '';
$product_price = '';

if ( function_exists( 'is_product' ) && is_product() ) {

    // На сторінці товару $product зазвичай присутній, але підстрахуємося.
    if ( $product instanceof WC_Product ) {
        $product_id = $product->get_id();
    } else {
        $maybe_id   = get_the_ID();
        $product_id = is_numeric( $maybe_id ) ? (int) $maybe_id : 0;
        $product    = $product_id ? wc_get_product( $product_id ) : null;
    }

    if ( $product_id ) {
        $product_title = get_the_title( $product_id );
        $product_img   = get_the_post_thumbnail_url( $product_id, 'woocommerce_thumbnail' );

        // Фолбек на placeholder, якщо у товару немає зображення
        if ( ! $product_img ) {
            $product_img = wc_placeholder_img_src( 'woocommerce_thumbnail' );
        }

        $product_url = get_permalink( $product_id );
        
        $product_sku = $product->get_sku();
        
        $product_price = $product->get_price();
    }
}
?>

<div class="form-lightbox-wrapper one-click">
  <div class="lightbox-background"></div>
 
  <div class="lightbox-section">
    
    <div class="form-head">
        <h2>Швидка покупка в 1 клік</h2>
      </div>
    <!-- Відображення товару в «шапці» модалки -->
    <?php if ( $product_id ) : ?>
      <div class="quick-order-product">
        <img class="quick-order-product__img"
             src="<?php echo esc_url( $product_img ); ?>"
             alt="<?php echo esc_attr( $product_title ); ?>">
        <div class="quick-order-product__title">
          <?php echo esc_html( $product_title ); ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="lightbox-content">
      <div class="close-lightbox">
        <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/close.svg' ); ?>"
             loading="lazy" width="14" height="14" alt="Закрити">
      </div>

     

      <div class="form-description">
        Заповніть поле номеру телефону для швидкого придбання товару
      </div>

      <form class="form-fields call-back-form"
            action="<?php echo esc_url( get_stylesheet_directory_uri() . '/php/call-back-form.php' ); ?>"
            method="post">

        <div class="form-data-group">
          <input type="hidden" name="form_type" value="one_click">

          <input class="form-input" type="text" name="name"
                 placeholder="Ім’я*" required>

          <input class="form-input" type="tel" name="phone"
                 placeholder="+38(0__)___-__-__" required>
        </div>

        <!-- Приховані поля з даними товару -->
        <input type="hidden" name="product_id"    value="<?php echo esc_attr( $product_id ); ?>">
        <input type="hidden" name="product_title" value="<?php echo esc_attr( $product_title ); ?>">
        <input type="hidden" name="product_url"   value="<?php echo esc_url( $product_url ); ?>">
        <input type="hidden" name="product_image" value="<?php echo esc_url( $product_img ); ?>">
        <input type="hidden" name="product_sku" value="<?php echo esc_attr( $product_sku ); ?>">
        <input type="hidden" name="product_price" value="<?php echo esc_attr( $product_price ); ?>">

        
                        <?php
                  // URL сторінки політики (візьме з Налаштування → Конфіденційність, якщо задано)
                  $privacy_url = function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : '#';
                ?>
                <div class="form-row form-agree">
                  <label class="cr-checkbox">
                    <input type="checkbox" name="agree_privacy" value="1" required>
                    <span class="cr-checkbox__box" aria-hidden="true"></span>
                    <span class="cr-checkbox__text">
                      Я погоджуюсь з
                      <a href="<?php echo esc_url( $privacy_url ); ?>" target="_blank" rel="noopener">
                        політикою конфіденційності
                      </a>
                    </span>
                  </label>
                </div>

        
        
        <button type="submit" name="submit_one_click_order" class="btn btn-primary">
          <span>Відправити</span>
        </button>
      </form>
    </div>
  </div>
</div>

<div class="form-lightbox-wrapper call-back">
  <div class="lightbox-background"></div>
 
  <div class="lightbox-section">
    
    <div class="form-head">
        <h2>Замовити консультацію</h2>
      </div>
    <!-- Відображення товару в «шапці» модалки -->
    <?php if ( $product_id ) : ?>
      <div class="quick-order-product">
        <img class="quick-order-product__img"
             src="<?php echo esc_url( $product_img ); ?>"
             alt="<?php echo esc_attr( $product_title ); ?>">
        <div class="quick-order-product__title">
          <?php echo esc_html( $product_title ); ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="lightbox-content">
      <div class="close-lightbox">
        <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/close.svg' ); ?>"
             loading="lazy" width="14" height="14" alt="Закрити">
      </div>

     

      <div class="form-description">
        Заповніть поле номеру телефону для швидкого придбання товару
      </div>

      <form class="form-fields call-back-form"
            action="<?php echo esc_url( get_stylesheet_directory_uri() . '/php/call-back-form.php' ); ?>"
            method="post">

        <div class="form-data-group">
          <input type="hidden" name="form_type" value="call_back">

          <input class="form-input" type="text" name="name"
                 placeholder="Ім’я*" required>

          <input class="form-input" type="tel" name="phone"
                 placeholder="+38(0__)___-__-__" required>
        </div>

        <!-- Приховані поля з даними товару -->
        <input type="hidden" name="product_id"    value="<?php echo esc_attr( $product_id ); ?>">
        <input type="hidden" name="product_title" value="<?php echo esc_attr( $product_title ); ?>">
        <input type="hidden" name="product_url"   value="<?php echo esc_url( $product_url ); ?>">
        <input type="hidden" name="product_image" value="<?php echo esc_url( $product_img ); ?>">
        <input type="hidden" name="product_sku" value="<?php echo esc_attr( $product_sku ); ?>">

        
                        <?php
                  // URL сторінки політики (візьме з Налаштування → Конфіденційність, якщо задано)
                  $privacy_url = function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : '#';
                ?>
                <div class="form-row form-agree">
                  <label class="cr-checkbox">
                    <input type="checkbox" name="agree_privacy" value="1" required>
                    <span class="cr-checkbox__box" aria-hidden="true"></span>
                    <span class="cr-checkbox__text">
                      Я погоджуюсь з
                      <a href="<?php echo esc_url( $privacy_url ); ?>" target="_blank" rel="noopener">
                        політикою конфіденційності
                      </a>
                    </span>
                  </label>
                </div>

        
        
        <button type="submit" name="submit_one_click_order" class="btn btn-primary">
          <span>Відправити</span>
        </button>
      </form>
    </div>
  </div>
</div>
