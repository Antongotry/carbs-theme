<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WL3XH6DZ');</script>
<!-- End Google Tag Manager -->
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">


<link rel="icon" href="https://crabs.ua/wp-content/themes/carbs-theme/img/favicon.ico" sizes="any">
<link rel="icon" type="image/png" href="https://crabs.ua/wp-content/themes/carbs-theme/img/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="https://crabs.ua/wp-content/themes/carbs-theme/img/favicon-48x48.png" sizes="48x48">
<link rel="apple-touch-icon" href="https://crabs.ua/wp-content/themes/carbs-theme/img/favicon-180x180.png" sizes="180x180">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WL3XH6DZ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<div class="wrapper">

<?php wp_body_open(); ?>

<?php do_action( 'storefront_before_site' ); ?>

	<?php do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header header" role="banner" style="<?php storefront_header_styles(); ?>">


		<div class="header__content container">
			<div class="header__column">
			<a class="header__catalogue">
				<svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect width="5" height="5" rx="1" fill="#E93A53" />
				<rect y="10" width="5" height="5" rx="1" fill="#E93A53" />
				<rect x="10" y="10" width="5" height="5" rx="1" fill="#E93A53" />
				<rect x="10" width="5" height="5" rx="1" fill="#E93A53" />
				</svg>
				<span><?php _e('Каталог', 'crabs_project'); ?></span>
			</a>
			<nav>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary', // Replace with the appropriate theme location if different
					'menu' => 'Головне меню', // Replace with the appropriate menu name if different
					'container' => false,
					'menu_class' => 'header__nav', // Class for the <ul> element
					'depth' => 1,
				) );
				?>
			</nav>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header__logo">
				<?php if ( get_theme_mod( 'crabs_project_logo' ) ) : ?>
					<img src="<?php echo esc_url( get_theme_mod( 'crabs_project_logo' ) ); ?>" alt="logo" />
				<?php else : ?>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo.png" alt="logo" />
				<?php endif; ?>
			</a>

			</div>
			<div class="header__column">

        <a href="/konstruktor/model-cybex-priam" class="header__constructor"><?php _e('Збери свій Cybex', 'crabs_project'); ?></a>

        <div class="search-form">
          <a href="##" class="header__search">
            <svg
            width="19"
            height="19"
            viewBox="0 0 19 19"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            >
            <path
              d="M17.4697 18.5303C17.7626 18.8232 18.2374 18.8232 18.5303 18.5303C18.8232 18.2374 18.8232 17.7626 18.5303 17.4697L17.4697 18.5303ZM15.25 8.5C15.25 12.2279 12.2279 15.25 8.5 15.25V16.75C13.0563 16.75 16.75 13.0563 16.75 8.5H15.25ZM8.5 15.25C4.77208 15.25 1.75 12.2279 1.75 8.5H0.25C0.25 13.0563 3.94365 16.75 8.5 16.75V15.25ZM1.75 8.5C1.75 4.77208 4.77208 1.75 8.5 1.75V0.25C3.94365 0.25 0.25 3.94365 0.25 8.5H1.75ZM8.5 1.75C12.2279 1.75 15.25 4.77208 15.25 8.5H16.75C16.75 3.94365 13.0563 0.25 8.5 0.25V1.75ZM18.5303 17.4697L14.3428 13.2821L13.2821 14.3428L17.4697 18.5303L18.5303 17.4697Z"
              fill="#242424"
            />
            </svg>
          </a>
          <form role="search" method="get" class="search-form__item" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <button class="search-form__btn" type="submit"></button>
            <input type="search" class="search-form__input" placeholder="Пошук" value="<?php echo get_search_query(); ?>" name="s" />
            <input type="hidden" name="post_type" value="product" />
        </form>
        </div>

        <div class="header__buttons">
          <?php if (is_user_logged_in()) : ?>
          <!-- Если пользователь залогинен, то перенаправляем на страницу забронированных заказов --> 
          <a href="https://www.crabs.ua/zabronovani-zamovlennia/" class="header__btn">
              <svg
                  width="22"
                  height="24"
                  viewBox="0 0 22 24"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
              >
                  <path
                      d="M20.4876 23.2001C20.2279 18.615 18.7827 15.2715 10.7438 15.2715C2.70491 15.2715 1.25978 18.615 1 23.2001"
                      stroke="#242424"
                      stroke-width="1.5"
                      stroke-linecap="round"
                  />
                  <ellipse
                      cx="10.7445"
                      cy="5.75694"
                      rx="4.89685"
                      ry="4.75694"
                      stroke="#242424"
                      stroke-width="1.5"
                  />
              </svg>
          </a>
      <?php else : ?>
          <!-- Если пользователь не залогинен, то открываем попап -->
          <a href="#login" class="header__btn open-popup">
              <svg
                  width="22"
                  height="24"
                  viewBox="0 0 22 24"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
              >
                  <path
                      d="M20.4876 23.2001C20.2279 18.615 18.7827 15.2715 10.7438 15.2715C2.70491 15.2715 1.25978 18.615 1 23.2001"
                      stroke="#242424"
                      stroke-width="1.5"
                      stroke-linecap="round"
                  />
                  <ellipse
                      cx="10.7445"
                      cy="5.75694"
                      rx="4.89685"
                      ry="4.75694"
                      stroke="#242424"
                      stroke-width="1.5"
                  />
              </svg>
          </a>
      <?php endif; ?>


          <a href="/wishlist" class="header__btn header_favorite">
              <svg width="23" height="19" viewBox="0 0 23 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M2.52688 9.55034L11.3214 18L20.116 9.55034C21.0936 8.61103 21.6429 7.33706 21.6429 6.00867C21.6429 3.24246 19.3089 1 16.4297 1C15.0471 1 13.7212 1.5277 12.7435 2.46701L11.3214 3.83333L9.89933 2.46701C8.92169 1.5277 7.59571 1 6.21311 1C3.33399 1 1 3.24246 1 6.00867C1 7.33706 1.54923 8.61103 2.52688 9.55034Z" stroke="#242424" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <span id="wishlist-count"><?php echo count( wooeshop_get_wishlist() ); ?></span>
          </a>


          <a href="##" class="header__btn" id="btnCartHeader">
            <svg
              width="19"
              height="19"
              viewBox="0 0 19 19"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
              d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z"
              stroke="#242424"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
              />
            </svg>
            <span class="cart-count" id="cart-count">0</span>
          </a>


        </div>

			</div>
      
		</div>

	</header><!-- #masthead -->


	<main>
    <svg display="none" xmlns="http://www.w3.org/2000/svg">
          <symbol id="star" viewBox="0 0 20 19">
            <path
              d="M10 0L13.4092 5.3077L19.5106 6.90983L15.5161 11.7923L15.8779 18.0902L10 15.8L4.12215 18.0902L4.48387 11.7923L0.489435 6.90983L6.59085 5.3077L10 0Z"
              fill="#F4A804"
            />
          </symbol>
          <symbol id="bag" viewBox="0 0 41 41">
            <rect width="41" height="41" rx="5" fill="#242424" />
            <path
              d="M12.8402 18.8104C12.9467 17.7839 13.8862 17 15.0097 17H25.9903C27.1138 17 28.0533 17.7839 28.1598 18.8104L28.99 26.8104C29.1118 27.9846 28.1057 29 26.8205 29H14.1795C12.8943 29 11.8882 27.9846 12.01 26.8104L12.8402 18.8104Z"
              fill="#242424"
            />
            <path
              d="M16.1414 20V16C16.1414 13.7909 18.0928 12 20.5 12C22.9072 12 24.8586 13.7909 24.8586 16V20M14.1795 29H26.8205C28.1057 29 29.1118 27.9845 28.99 26.8104L28.1598 18.8104C28.0533 17.7839 27.1139 17 25.9903 17H15.0097C13.8862 17 12.9467 17.7839 12.8402 18.8104L12.01 26.8104C11.8882 27.9846 12.8943 29 14.1795 29Z"
              stroke="white"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </symbol>
        <symbol id="red-bag" viewBox="0 0 54 66">
            <rect width="54" height="66" rx="5" fill="#E93A53" />
            <g id="bag-icon-paths" transform="translate(7, 10)">
            <path
                    d="M12.8402 18.8104C12.9467 17.7839 13.8862 17 15.0097 17H25.9903C27.1138 17 28.0533 17.7839 28.1598 18.8104L28.99 26.8104C29.1118 27.9846 28.1057 29 26.8205 29H14.1795C12.8943 29 11.8882 27.9846 12.01 26.8104L12.8402 18.8104Z"
                    fill="#E93A53"
            />
            <path
                    d="M16.1414 20V16C16.1414 13.7909 18.0928 12 20.5 12C22.9072 12 24.8586 13.7909 24.8586 16V20M14.1795 29H26.8205C28.1057 29 29.1118 27.9845 28.99 26.8104L28.1598 18.8104C28.0533 17.7839 27.1139 17 25.9903 17H15.0097C13.8862 17 12.9467 17.7839 12.8402 18.8104L12.01 26.8104C11.8882 27.9846 12.8943 29 14.1795 29Z"
                    stroke="white"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    fill="none"
            />
            </g>
        </symbol>
        <symbol
            id="arrow-prev"
            viewBox="0 0 50 50"
            width="50"
            height="50"
            fill="none"
          >
            <rect
              x="0.75"
              y="0.75"
              width="48.5"
              height="48.5"
              rx="4.25"
              stroke="#242424"
              stroke-width="1.5"
            />
            <path
              d="M26.5547 30L21.2513 24.6967L26.5547 19.3933"
              stroke="#242424"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </symbol>
          <symbol
            id="arrow-next"
            viewBox="0 0 50 50"
            width="50"
            height="50"
            fill="none"
          >
            <rect
              x="0.75"
              y="0.75"
              width="48.5"
              height="48.5"
              rx="4.25"
              stroke="#242424"
              stroke-width="1.5"
            />
            <path
              d="M23.75 20L29.0533 25.3033L23.75 30.6067"
              stroke="#242424"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </symbol>
          <symbol
            id="arrow-prev_40"
            width="40"
            height="40"
            viewBox="0 0 40 40"
            fill="none"
          >
            <rect
              x="0.5"
              y="0.5"
              width="39"
              height="39"
              rx="4.5"
              stroke="#242424"
            />
            <path
              d="M21.2427 24L17 19.7573L21.2427 15.5146"
              stroke="#242424"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </symbol>
          <symbol
            id="arrow-next_40"
            width="40"
            height="40"
            viewBox="0 0 40 40"
            fill="none"
          >
            <rect
              x="0.5"
              y="0.5"
              width="39"
              height="39"
              rx="4.5"
              stroke="#242424"
            />
            <path
              d="M19 16L23.2427 20.2427L19 24.4854"
              stroke="#242424"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </symbol>

        <symbol
                id="arrow-prev_40_cart"
                width="40"
                height="40"
                viewBox="0 0 40 40"
                fill="#242424"
        >
            <rect
                    x="0.5"
                    y="0.5"
                    width="39"
                    height="39"
                    rx="4.5"
                    stroke="#ffffff"
            />
            <path
                    d="M21.2427 24L17 19.7573L21.2427 15.5146"
                    stroke="#ffffff"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
            />
        </symbol>
        <symbol
                id="arrow-next_40_cart"
                width="40"
                height="40"
                viewBox="0 0 40 40"
                fill="#242424"
        >
            <rect
                    x="0.5"
                    y="0.5"
                    width="39"
                    height="39"
                    rx="4.5"
                    stroke="#ffffff"
            />
            <path
                    d="M19 16L23.2427 20.2427L19 24.4854"
                    stroke="#ffffff"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
            />
        </symbol>
        <symbol id="icon-refresh-cycle" viewBox="0 0 23 19">
            <path d="M19.7385 9.5C19.7385 14.1944 15.9777 18 11.3385 18C7.25182 18 3.84678 15.047 3.09364 11.1346M19.7385 9.5L16.8308 11.4615M19.7385 9.5L22 11.7885M2.93846 9.5C2.93846 4.80558 6.69927 1 11.3385 1C15.3552 1 18.7135 3.85291 19.5423 7.66553M2.93846 9.5L1 6.88462M2.93846 9.5L5.52308 7.86538" stroke="#E93A53" fill="none"/>
        </symbol>

          <symbol
            id="arrow-prev-mob"
            width="55"
            height="55"
            viewBox="0 0 55 55"
            fill="none"
          >
            <rect x="0.5" y="0.5" width="54" height="54" rx="4.5" stroke="#242424"/>
            <path d="M31 18L22 27.5L31 37" stroke="#242424" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </symbol>
          <symbol
            id="arrow-next-mob"
            width="55"
            height="55"
            viewBox="0 0 55 55"
            fill="none"
          >
            <rect x="54.5" y="54.502" width="54" height="54" rx="4.5" transform="rotate(180 54.5 54.502)" stroke="#242424"/>
            <path d="M24 37.002L33 27.502L24 18.002" stroke="#242424" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </symbol>
          <symbol
            id="arrow-top"
            width="22"
            height="22"
            viewBox="0 0 22 22"
            fill="none"
          >
            <rect
              x="22"
              width="22"
              height="22"
              rx="3"
              transform="rotate(90 22 0)"
              fill="#E93A53"
            />
            <path
              d="M8.7998 11.6836L11.1333 9.35012L13.4667 11.6836"
              stroke="white"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </symbol>
          <symbol
            id="arrow-top-mob"
            width="30"
            height="30"
            viewBox="0 0 30 30"
            fill="none"
          >
            <rect
              x="30"
              width="30"
              height="30"
              rx="3"
              transform="rotate(90 30 0)"
              fill="#E93A53"
            />
            <path
              d="M12 15.9316L15.182 12.7496L18.364 15.9316"
              stroke="white"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </symbol>
          <symbol id="delete" viewBox="0 0 16 18" fill="none">
            <path d="M10.75 17.9375H5.25C4.33832 17.9375 3.46398 17.5753 2.81932 16.9307C2.17466 16.286 1.8125 15.4117 1.8125 14.5V6.25C1.8125 6.06766 1.88493 5.8928 2.01386 5.76386C2.1428 5.63493 2.31766 5.5625 2.5 5.5625C2.68234 5.5625 2.8572 5.63493 2.98614 5.76386C3.11507 5.8928 3.1875 6.06766 3.1875 6.25V14.5C3.1875 15.047 3.4048 15.5716 3.79159 15.9584C4.17839 16.3452 4.70299 16.5625 5.25 16.5625H10.75C11.297 16.5625 11.8216 16.3452 12.2084 15.9584C12.5952 15.5716 12.8125 15.047 12.8125 14.5V6.25C12.8125 6.06766 12.8849 5.8928 13.0139 5.76386C13.1428 5.63493 13.3177 5.5625 13.5 5.5625C13.6823 5.5625 13.8572 5.63493 13.9861 5.76386C14.1151 5.8928 14.1875 6.06766 14.1875 6.25V14.5C14.1875 15.4117 13.8253 16.286 13.1807 16.9307C12.536 17.5753 11.6617 17.9375 10.75 17.9375Z"/>
            <path d="M14.875 4.1875H1.125C0.942664 4.1875 0.767795 4.11507 0.638864 3.98614C0.509933 3.8572 0.4375 3.68234 0.4375 3.5C0.4375 3.31766 0.509933 3.1428 0.638864 3.01386C0.767795 2.88493 0.942664 2.8125 1.125 2.8125H14.875C15.0573 2.8125 15.2322 2.88493 15.3611 3.01386C15.4901 3.1428 15.5625 3.31766 15.5625 3.5C15.5625 3.68234 15.4901 3.8572 15.3611 3.98614C15.2322 4.11507 15.0573 4.1875 14.875 4.1875Z"/>
            <path d="M10.75 4.1875H5.25C5.06766 4.1875 4.8928 4.11507 4.76386 3.98614C4.63493 3.8572 4.5625 3.68234 4.5625 3.5V2.125C4.5625 1.57799 4.7798 1.05339 5.16659 0.666592C5.55339 0.279799 6.07799 0.0625 6.625 0.0625H9.375C9.92201 0.0625 10.4466 0.279799 10.8334 0.666592C11.2202 1.05339 11.4375 1.57799 11.4375 2.125V3.5C11.4375 3.68234 11.3651 3.8572 11.2361 3.98614C11.1072 4.11507 10.9323 4.1875 10.75 4.1875ZM5.9375 2.8125H10.0625V2.125C10.0625 1.94266 9.99007 1.7678 9.86114 1.63886C9.73221 1.50993 9.55734 1.4375 9.375 1.4375H6.625C6.44266 1.4375 6.2678 1.50993 6.13886 1.63886C6.00993 1.7678 5.9375 1.94266 5.9375 2.125V2.8125Z"/>
            <path d="M6.625 13.8125C6.44266 13.8125 6.2678 13.7401 6.13886 13.6111C6.00993 13.4822 5.9375 13.3073 5.9375 13.125V8.3125C5.9375 8.13016 6.00993 7.9553 6.13886 7.82636C6.2678 7.69743 6.44266 7.625 6.625 7.625C6.80734 7.625 6.9822 7.69743 7.11114 7.82636C7.24007 7.9553 7.3125 8.13016 7.3125 8.3125V13.125C7.3125 13.3073 7.24007 13.4822 7.11114 13.6111C6.9822 13.7401 6.80734 13.8125 6.625 13.8125Z"/>
            <path d="M9.375 13.8125C9.19266 13.8125 9.01779 13.7401 8.88886 13.6111C8.75993 13.4822 8.6875 13.3073 8.6875 13.125V8.3125C8.6875 8.13016 8.75993 7.9553 8.88886 7.82636C9.01779 7.69743 9.19266 7.625 9.375 7.625C9.55734 7.625 9.73221 7.69743 9.86114 7.82636C9.99007 7.9553 10.0625 8.13016 10.0625 8.3125V13.125C10.0625 13.3073 9.99007 13.4822 9.86114 13.6111C9.73221 13.7401 9.55734 13.8125 9.375 13.8125Z"/>
          </symbol>
          <symbol id="checkpoint-icon" viewBox="0 0 23 23" fill="none">
            <circle cx="11.5" cy="11.5" r="11.5" fill="#E93A53"/>
            <path d="M6 12.5L10 16L17 7" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
          </symbol>
          <symbol id="heart-icon" viewBox="0 0 15 13" fill="none">
              <path d="M1.7483 6.53177L7.38583 11.9482L13.0234 6.53177C13.6501 5.92965 14.0021 5.113 14.0021 4.26147C14.0021 2.48826 12.506 1.05078 10.6604 1.05078C9.77411 1.05078 8.92413 1.38905 8.29743 1.99117L7.38583 2.86702L6.47423 1.99117C5.84753 1.38905 4.99755 1.05078 4.11127 1.05078C2.26568 1.05078 0.769531 2.48826 0.769531 4.26147C0.769531 5.113 1.1216 5.92965 1.7483 6.53177Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>                 
          </symbol>
        </svg>

		<?php
		// Вставляем хлебные крошки после хедера
		custom_breadcrumb_output(array(
			'delimiter'   => ' / ',
			'wrap_before' => '<div class="breadcrambs"><div class="container">',
			'wrap_after'  => '</div></div>',
			'before'      => '<span>',
			'after'       => '</span>',
			'home'        => _x( 'Повернутися назад', 'breadcrumb', 'woocommerce' ),
		));
		?>



