<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

</main>
<footer id="colophon" class="site-footer footer" role="contentinfo">
	<div class="footer__content container">
		<div class="footer__top">
			<div class="footer__left">
				<a href="/" class="footer__logo">
					<img src="/wp-content/uploads/2024/06/logo_footer.png" alt="logo" />
				</a>
				<div class="footer__text">
					<?php echo nl2br(esc_html(get_theme_mod('footer_text_under_logo'))); ?>
				</div>
			</div>
			<div class="footer__right">
				<div class="footer__column">
					<div class="footer__title" tabindex="-1">
						<span><?php _e('Категорії', 'crabs_project') ?></span>
					</div>
					<div>
						<?php
                    	wp_nav_menu( array(
                    		'theme_location' => 'primary', // Replace with the appropriate theme location if different
                    		'menu' => 'Категорії Товарів', // Replace with the appropriate menu name if different
                    		'container' => false,
                    		'menu_class' => 'footer__list', // Class for the <ul> element
                    		'depth' => 1,
                    	) );

						?>
					</div>
				</div>
				<div class="footer__column">
					<div class="footer__title" tabindex="-1">
						<span><?php _e('Навігація', 'crabs_project') ?></span>
					</div>
					<div>
						<?php
                    	wp_nav_menu( array(
                    		'theme_location' => 'primary', // Replace with the appropriate theme location if different
                    		'menu' => 'Навігація', // Replace with the appropriate menu name if different
                    		'container' => false,
                    		'menu_class' => 'footer__list', // Class for the <ul> element
                    		'depth' => 1,
                    	) );

						?>
					</div>
				</div>
				<div class="footer__column">
					<div class="footer__title" tabindex="-1">
						<span><?php _e('Клієнтам', 'crabs_project') ?></span>
					</div>
					<div>
						<?php
                        	wp_nav_menu( array(
                        		'theme_location' => 'primary', // Replace with the appropriate theme location if different
                        		'menu' => 'Клієнтам', // Replace with the appropriate menu name if different
                        		'container' => false,
                        		'menu_class' => 'footer__list', // Class for the <ul> element
                        		'depth' => 1,
                        	));
						?>
					</div>
				</div>
				<div class="footer__column">
					<div class="footer__title" tabindex="-1">
						<span><?php _e('Контакти', 'crabs_project') ?></span>
					</div>
					<div>
						<div class="footer__contacts">
							<div class="footer__contact">
								<div class="footer__icon">
									<svg
										 width="14"
										 height="16"
										 viewBox="0 0 14 16"
										 fill="none"
										 xmlns="http://www.w3.org/2000/svg"
										 >
										<path
											  fill-rule="evenodd"
											  clip-rule="evenodd"
											  d="M13 7.22222C13 10.6587 10.3137 13.4444 7 15C3.68629 13.4444 1 10.6587 1 7.22222C1 3.78578 3.68629 1 7 1C10.3137 1 13 3.78578 13 7.22222ZM7 9.55556C8.24264 9.55556 9.25 8.51089 9.25 7.22222C9.25 5.93356 8.24264 4.88889 7 4.88889C5.75736 4.88889 4.75 5.93356 4.75 7.22222C4.75 8.51089 5.75736 9.55556 7 9.55556Z"
											  stroke="#3D3D3D"
											  stroke-linecap="round"
											  stroke-linejoin="round"
											  />
									</svg>
								</div>
								<div class="footer__info">
									<a href="https://www.google.com/maps/place/%D0%B2%D1%83%D0%BB%D0%B8%D1%86%D1%8F+%D0%92%D0%BE%D0%B2%D1%87%D0%B8%D0%BD%D0%B5%D1%86%D1%8C%D0%BA%D0%B0,+227,+%D0%86%D0%B2%D0%B0%D0%BD%D0%BE-%D0%A4%D1%80%D0%B0%D0%BD%D0%BA%D1%96%D0%B2%D1%81%D1%8C%D0%BA,+%D0%86%D0%B2%D0%B0%D0%BD%D0%BE-%D0%A4%D1%80%D0%B0%D0%BD%D0%BA%D1%96%D0%B2%D1%81%D1%8C%D0%BA%D0%B0+%D0%BE%D0%B1%D0%BB%D0%B0%D1%81%D1%82%D1%8C,+76000/data=!4m2!3m1!1s0x4730c190857098c3:0x3e8710ade89b870?sa=X&ved=1t:242&ictx=111"
									   ><?php echo nl2br(esc_html(get_theme_mod('footer_address'))); ?></a>
								</div>
							</div>
							<div class="footer__contact">
								<div class="footer__icon">
									<svg
										 width="16"
										 height="13"
										 viewBox="0 0 16 13"
										 fill="none"
										 xmlns="http://www.w3.org/2000/svg"
										 >
										<path
											  d="M1.38889 1.39286L6.90006 6.96026C7.50754 7.57394 8.49246 7.57394 9.09994 6.96026L14.6111 1.39286M1.77778 12H14.2222C14.6518 12 15 11.6482 15 11.2143V1.78571C15 1.35178 14.6518 1 14.2222 1H1.77778C1.34822 1 1 1.35178 1 1.78571V11.2143C1 11.6482 1.34822 12 1.77778 12Z"
											  stroke="#3D3D3D"
											  stroke-linecap="round"
											  stroke-linejoin="round"
											  />
									</svg>
								</div>
								<div class="footer__info">
									<a href="mailto:<?php echo esc_html(get_theme_mod('footer_email')); ?>"><?php echo esc_html(get_theme_mod('footer_email')); ?></a>
									<span><?php _e('Ваші пропозиції та питання', 'crabs_project') ?></span>
								</div>
							</div>
							<div class="footer__contact footer__contact-phone">
								<div class="footer__icon">
									<svg
										 width="16"
										 height="16"
										 viewBox="0 0 16 16"
										 fill="none"
										 xmlns="http://www.w3.org/2000/svg"
										 >
										<path
											  d="M14.9996 11.4819V13.5894C15.0028 14.3951 14.2784 15.0679 13.465 14.9945C6.44446 15 1 9.50499 1.00549 2.53159C0.932236 1.72251 1.60167 1.00082 2.40631 1.00007H4.51809C4.85971 0.996713 5.1909 1.11745 5.44992 1.33977C6.18634 1.97183 6.66001 4.11716 6.47776 4.96999C6.33547 5.63584 5.66427 6.10167 5.20794 6.5571C6.21002 8.31592 7.66919 9.7722 9.4315 10.7723C9.88783 10.3169 10.3546 9.647 11.0218 9.50499C11.8776 9.32283 14.0367 9.79717 14.6663 10.5387C14.8895 10.8016 15.0081 11.1373 14.9996 11.4819Z"
											  stroke="#3D3D3D"
											  stroke-linecap="round"
											  stroke-linejoin="round"
											  />
									</svg>
								</div>
								<div class="footer__info">
									<a href="tel:<?php echo esc_html(get_theme_mod('footer_phone_1')); ?>"><?php echo esc_html(get_theme_mod('footer_phone_1')); ?></a>
								</div>
							</div>
							<div class="footer__contact footer__contact-phone">
								<div class="footer__icon">
									<svg
										 width="16"
										 height="16"
										 viewBox="0 0 16 16"
										 fill="none"
										 xmlns="http://www.w3.org/2000/svg"
										 >
										<path
											  d="M14.9996 11.4819V13.5894C15.0028 14.3951 14.2784 15.0679 13.465 14.9945C6.44446 15 1 9.50499 1.00549 2.53159C0.932236 1.72251 1.60167 1.00082 2.40631 1.00007H4.51809C4.85971 0.996713 5.1909 1.11745 5.44992 1.33977C6.18634 1.97183 6.66001 4.11716 6.47776 4.96999C6.33547 5.63584 5.66427 6.10167 5.20794 6.5571C6.21002 8.31592 7.66919 9.7722 9.4315 10.7723C9.88783 10.3169 10.3546 9.647 11.0218 9.50499C11.8776 9.32283 14.0367 9.79717 14.6663 10.5387C14.8895 10.8016 15.0081 11.1373 14.9996 11.4819Z"
											  stroke="#3D3D3D"
											  stroke-linecap="round"
											  stroke-linejoin="round"
											  />
									</svg>
								</div>
								<div class="footer__info">
									<a href="tel:<?php echo esc_html(get_theme_mod('footer_phone_2')); ?>"><?php echo esc_html(get_theme_mod('footer_phone_2')); ?></a>
								</div>
							</div>
						</div>
						<div class="footer__social">
							<a href="<?php echo esc_url(get_theme_mod('footer_social_instagram')); ?>"><i class="fab fa-instagram"></i> Instagram</a>
							<a href="<?php echo esc_url(get_theme_mod('footer_social_telegram')); ?>"><i class="fab fa-telegram"></i> Telegram</a>
							<a href="viber://chat?number=380990084117"><i class="fab fa-viber"></i> Viber</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer__bottom">
			<div class="footer__copy">&copy; <?php echo date('Y'); ?>. Crabs
			</div>
			<div class="footer__credits">
				<div class="footer__credit">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/privat.svg" alt="privar" />
				</div>
				<div class="footer__credit">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/visa.svg" alt="privar" />
				</div>
				<div class="footer__credit">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/mastercard.svg" alt="privar" />
				</div>
			</div>
			<div class="footer__studio">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/prime.png" alt="prime" />
				<span> <?php _e('Розробка Prime Studio', 'crabs_project') ?> </span>
			</div>
		</div>
	</div>
</footer><!-- #colophon -->

<?php do_action( 'storefront_after_footer' ); ?>

<div class="menu-header-wrapper">
	<div class="menu-header-background"></div>
	<div class="menu-header">
		<div class="menu-block-top">
			<div class="side-menu-close">
				<img src="/wp-content/uploads/2024/10/cr-close.svg" loading="eager" alt="Close">
			</div>
		</div>
		<div class="menu-block-middle">
			<?php if (is_user_logged_in()) : ?>
			<a href="/cabinet" class="cabinet-line mob">
				<img src="/wp-content/uploads/2024/10/cabinet.svg" loading="eager" alt="Cabinet icon">
				<p>Відкрити кабінет</p>
			</a>
			<?php else : ?>
			<a class="cabinet-line mob open-login-pop-up">
				<img src="/wp-content/uploads/2024/10/cabinet.svg" loading="eager" alt="Cabinet icon">
				<p>Увійти в кабінет</p>
			</a>
			<?php endif; ?>
			
				<a class="btn btn-gray mob" href="/konstruktor/model-cybex-priam"><span>Збери свій Cybex</span></a>
			
			<div class="side-menu category-list">
				<div class="h3">Категорії</div>
				<?php
				// Функция для подсчета товаров в категории и её потомках
				function get_total_product_count($category_id) {
					// Получаем количество товаров в текущей категории
					$current_category = get_term($category_id, 'product_cat');
					$total_count = $current_category->count;

					// Получаем дочерние категории
					$child_categories = get_categories(array(
						'taxonomy' => 'product_cat',
						'parent'   => $category_id,
						'hide_empty' => 0
					));

					// Рекурсивно подсчитываем товары во всех дочерних категориях
					foreach ($child_categories as $child) {
						$total_count += get_total_product_count($child->term_id);
					}

					return $total_count;
				}
				
				function display_category_tree($parent_id = 0, $level = 0) {
					$args = array(
						'taxonomy'     => 'product_cat',
						'parent'       => $parent_id,
						'hide_empty'   => 0
					);
					$categories = get_categories($args);

					// Сортировка категорий на основе кастомного поля ACF
					usort($categories, function($a, $b) {
						$order_a = get_field('custom_category_order', 'product_cat_' . $a->term_id);
						$order_b = get_field('custom_category_order', 'product_cat_' . $b->term_id);

						// Если поле не заполнено, присваиваем значение 0
						$order_a = ($order_a !== '' && $order_a !== null) ? intval($order_a) : 9999;
						$order_b = ($order_b !== '' && $order_b !== null) ? intval($order_b) : 9999;

						// Сортировка по возрастанию
						if ($order_a === $order_b) {
							// Если значения одинаковые, сортируем по имени
							return strcasecmp($a->name, $b->name);
						}
						return $order_a - $order_b;
					});

					foreach ($categories as $cat) {
						if ($cat->slug !== 'bez-katehorii' && $cat->slug !== 'na-vydalennia' && $cat->slug !== 'novi-pozytsii') {
							$category_id = $cat->term_id;
							$thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);
							$image = wp_get_attachment_image_src($thumbnail_id, 'thumbnail');
							$icon = get_field('prod_cat_icon', 'product_cat_' . $category_id);
							// Получаем количество товаров в категории
							$product_count = get_total_product_count($category_id);
				?>
				<div class="category-item level-<?php echo $level; ?> products-count-<?php echo $product_count; ?>">
					<div class="category-header">
						<?php if ($image): ?>
						<img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr($cat->name); ?>" class="category-icon">
						<?php endif; ?>
						<span class="category-name"><?php echo $cat->name; ?></span>
						<?php 
							$child_categories = get_categories(array(
								'taxonomy' => 'product_cat',
								'parent'   => $category_id,
								'hide_empty' => 0
							));
							if (!empty($child_categories)): 
						?>
						<img src="/wp-content/uploads/2024/10/cr-arrow-right.svg" alt="Open" class="category-arrow">
						<?php endif; ?>
					</div>
					<?php if (!empty($child_categories)): ?>
					<ul class="subcategory-list">
                        <li><a href="<?php echo get_term_link($cat->slug, 'product_cat'); ?>" class="view-all">Всі продукти категорії</a></li>
                        <?php display_category_tree($category_id, $level + 1); ?>
					</ul>
					<?php else : ?>
					<a href="<?php echo get_term_link($cat->slug, 'product_cat'); ?>" class="view-this"></a>
					
					<?php endif; ?>
				</div>
				<?php
						}
					}
				}

				display_category_tree();
				?>
			</div>
			<div class="side-menu side-menu-1 mob">
				<div class="h3">Навігація</div>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary', // Replace with the appropriate theme location if different
					'menu' => 'Навігація', // Replace with the appropriate menu name if different
					'container' => false,
					'menu_class' => 'menu', // Class for the <ul> element
					'depth' => 1,
				) );

				?>
			</div>
			<div class="side-menu side-menu-2 mob">
				<div class="h3">Інформація</div>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary', // Replace with the appropriate theme location if different
					'menu' => 'Клієнтам', // Replace with the appropriate menu name if different
					'container' => false,
					'menu_class' => 'menu', // Class for the <ul> element
					'depth' => 1,
				) );

				?>
			</div>
		</div>
		<div class="menu-block-bottom">
			<div class="h3">Контакти</div>
			<div class="contact-item">
				<div class="contact-icon"><img src="/wp-content/uploads/2024/10/cr-location.svg" loading="lazy" alt="Location icon"></div>
				<a href="https://www.google.com/maps/place/%D0%B2%D1%83%D0%BB%D0%B8%D1%86%D1%8F+%D0%92%D0%BE%D0%B2%D1%87%D0%B8%D0%BD%D0%B5%D1%86%D1%8C%D0%BA%D0%B0,+227,+%D0%86%D0%B2%D0%B0%D0%BD%D0%BE-%D0%A4%D1%80%D0%B0%D0%BD%D0%BA%D1%96%D0%B2%D1%81%D1%8C%D0%BA,+%D0%86%D0%B2%D0%B0%D0%BD%D0%BE-%D0%A4%D1%80%D0%B0%D0%BD%D0%BA%D1%96%D0%B2%D1%81%D1%8C%D0%BA%D0%B0+%D0%BE%D0%B1%D0%BB%D0%B0%D1%81%D1%82%D1%8C,+76000/data=!4m2!3m1!1s0x4730c190857098c3:0x3e8710ade89b870?sa=X&ved=1t:242&ictx=111" target="_blank" class="contact-link">
					<p>вул. Вовчинецька 227/2, Івано-Франківськ, Україна</p>
				</a>
			</div>
			<div class="contact-item">
				<div class="contact-icon"><img src="/wp-content/uploads/2024/10/cr-mail.svg" loading="lazy" alt="Mail icon"></div>
				<a href="mailto:onesevenpro@gmail.com" target="_blank" class="contact-link">
					<p>onesevenpro@gmail.com<br><small>Ваші пропозиції та запитання</small></p>
				</a>
			</div>
			<div class="contact-item">
				<div class="contact-icon"><img src="/wp-content/uploads/2024/10/cr-phone.svg" loading="lazy" alt="Phone icon"></div>
				<a href="tel:0990084117" target="_blank" class="contact-link">
					<p>+380 (99) 008-41-17</p>
				</a>
			</div>
			<div class="contact-item">
				<div class="contact-icon"><img src="/wp-content/uploads/2024/10/cr-phone.svg" loading="lazy" alt="Phone icon"></div>
				<a href="tel:0683302030" target="_blank" class="contact-link">
					<p>+380 (68) 330-20-30</p>
				</a>
			</div>
			<div class="contact-item block-with-cols soc-block">
				<a href="https://www.instagram.com/crabs.ua/" target="_blank">
					<div class="contact-icon"><span>Instagram</span></div>
				</a>
				<a href="https://t.me/crabsua" target="_blank">
					<div class="contact-icon"><span>Telegram</span></div>
				</a>
				<a href="viber://chat?number=380990084117" target="_blank">
					<div class="contact-icon"><span>Viber</span></div>
				</a>
			</div>
		</div>
	</div>
</div>

<?php include(get_stylesheet_directory() . '/php/form-lightbox.php'); ?>

<?php wp_footer(); ?>


<!-- логин форма -->
<div id="login" class="login">
	<div class="login__content area">
		<h2><?php _e('Вхід', 'crabs_project'); ?></h2>

		<?php if ( is_user_logged_in() ) : ?>
		<p><?php _e('Ви вже увійшли.', 'crabs_project'); ?></p>
		<?php else : ?>
		<form method="post" class="woocommerce-form woocommerce-form-login">
			<?php do_action( 'woocommerce_login_form_start' ); ?>
			<div class="login__form">
				<div class="login__input">
					<input type="text" name="username" id="username" autocomplete="username" />
					<label for="username"><?php _e('Телефон або ел. пошта', 'crabs_project'); ?></label>
				</div>

				<div class="login__input">
					<input type="password" name="password" id="password" autocomplete="current-password" autocomplete="current-password" />
					<label for="password"><?php _e('Пароль', 'crabs_project'); ?></label>
				</div>
				<?php echo do_shortcode('[nextend_social_login]'); ?>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<button type="submit" class="btn-red" name="login" value="<?php esc_attr_e( 'Увійти', 'crabs_project' ); ?>"><?php _e( 'Увійти', 'crabs_project' ); ?></button>

				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

				<?php do_action( 'woocommerce_login_form_end' ); ?>
			</div>
		</form>
		<?php endif; ?>
		<!-- <a href="<?php //echo esc_url( wp_lostpassword_url() ); ?>" class="lost-pass"><?php //_e('Забули пароль?', 'crabs_project'); ?></a> -->
		<div id="closeLogin" class="close-popup" data-close="login">
			<svg viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M32 1L1 32M1 1L32 32" stroke="#E93A53" stroke-width="2" />
			</svg>
		</div>
	</div>
</div>

<!-- Попап корзины -->
<div id="cart-wrapper" class="popup-cart cart">
	<div class="cart__content area">
		<h2>Ваш кошик:</h2>
		<div id="popup-cart" >
			<div id="cart-content">
				<?php echo do_shortcode('[popup_cart]'); ?>
			</div>

            <div id="upsells-in-cart-container">

            </div>



        </div>
		<div id="mini-cart-spinner" style="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/spinner_def.gif" /></div>
		<div id="closeCart" class="close-popup" data-close="login">
			<svg viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M32 1L1 32M1 1L32 32" stroke="#E93A53" stroke-width="2"></path>
			</svg>
		</div>
	</div>

</div>

<!-- Попап быстрого просмотра -->

<div id="quickView" class="quick-view">
	<div id="popup-qv-content" class="quick-view__content area"></div>
</div>

<?php if (is_product()){ ?>
<div id="showroom" class="showroom">
	<div class="showroom__content area">
		<h2>Наявність на складі</h2>
		<div class="showroom__main">
			<div class="showroom__product">
				<a href="##" class="showroom__image">
					<?php
	$product_id = get_the_ID(); // Получаем ID текущего продукта
	$showroom = !empty($custom_fields['_instok_showroom'][0]) ? esc_html($custom_fields['_instok_showroom'][0]) : '';
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' ); 



	$showroom = get_post_meta( $post->ID, '_instok_showroom', true );

	$availability_class = $showroom === 'yes' ? 'available' : 'not-available';



	if ( $image ) : ?>
					<img src="<?php echo esc_url( $image[0] ); ?>" data-id="<?php echo esc_attr( $product_id ); ?>">
					<?php endif; ?>
				</a>
				<a href="##" class="showroom__subtitle"
				   ><?php the_title(); ?></a
					>
			</div>
			<!--<div class="showroom__info">-->
			<!--	<div class="showroom__address">-->
			<!--		<span class="showroom__city">Івано-Франківськ, Україна</span>-->
			<!--		<span class="showroom__street"-->
			<!--			  >вул.Вовчинецька 227/2</span-->
			<!--			>-->
			<!--	</div>-->
			<!--	
			<!--</div>-->
			<!--<div class="showroom_availability">-->
   <!--           <span class="stock-status"></span>-->
   <!--         </div>-->
            
            <div class="showroom_availability">Перевіряємо...</div>


			<div class="showroom__bottom">
				<span class="showroom__time">Працюємо: 9:00 - 19:00</span>
				<span class="showroom__phone"><?php echo esc_html(get_theme_mod('footer_phone_1')); ?></span>
			</div>
		</div>
		<div id="closeShowroom" class="close-popup">
			<svg
				 viewBox="0 0 33 33"
				 fill="none"
				 xmlns="http://www.w3.org/2000/svg"
				 >
				<path
					  d="M32 1L1 32M1 1L32 32"
					  stroke="#E93A53"
					  stroke-width="2"
					  />
			</svg>
		</div>
	</div>
</div>
    <div id="how-to-pay-later" class="showroom">
        <div class="showroom__content area">
            <h2>Як працює оплата частинами?</h2>
            <div class="showroom__main">
                <p>Сюди потрібний текст</p>
            </div>
            <div id="close-how-to-pay-later-popup" class="close-popup">
                <svg
                        viewBox="0 0 33 33"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                            d="M32 1L1 32M1 1L32 32"
                            stroke="#E93A53"
                            stroke-width="2"
                    />
                </svg>
            </div>
        </div>
    </div>
<?php	} ?>

    <div id="floatingContactButton" class="contact-button visible">
    	<div class="contact-button-icon">
    		<svg
				 width="16"
				 height="16"
				 viewBox="0 0 16 16"
				 fill="none"
				 xmlns="http://www.w3.org/2000/svg"
				 >
				<path
					  d="M14.9996 11.4819V13.5894C15.0028 14.3951 14.2784 15.0679 13.465 14.9945C6.44446 15 1 9.50499 1.00549 2.53159C0.932236 1.72251 1.60167 1.00082 2.40631 1.00007H4.51809C4.85971 0.996713 5.1909 1.11745 5.44992 1.33977C6.18634 1.97183 6.66001 4.11716 6.47776 4.96999C6.33547 5.63584 5.66427 6.10167 5.20794 6.5571C6.21002 8.31592 7.66919 9.7722 9.4315 10.7723C9.88783 10.3169 10.3546 9.647 11.0218 9.50499C11.8776 9.32283 14.0367 9.79717 14.6663 10.5387C14.8895 10.8016 15.0081 11.1373 14.9996 11.4819Z"
					  stroke="white"
					  stroke-linecap="round"
					  stroke-linejoin="round"
				  />
			</svg>
    	</div>
    	<div class="social-icons">
    		<a href="tel:+380990084117" target="_blank" rel="nofollow">
    			<div class="floating-contact-icon contact-icon-phone">
    			    <svg
        				 width="16"
        				 height="16"
        				 viewBox="0 0 16 16"
        				 fill="none"
        				 xmlns="http://www.w3.org/2000/svg"
        				 >
        				<path
        					  d="M14.9996 11.4819V13.5894C15.0028 14.3951 14.2784 15.0679 13.465 14.9945C6.44446 15 1 9.50499 1.00549 2.53159C0.932236 1.72251 1.60167 1.00082 2.40631 1.00007H4.51809C4.85971 0.996713 5.1909 1.11745 5.44992 1.33977C6.18634 1.97183 6.66001 4.11716 6.47776 4.96999C6.33547 5.63584 5.66427 6.10167 5.20794 6.5571C6.21002 8.31592 7.66919 9.7722 9.4315 10.7723C9.88783 10.3169 10.3546 9.647 11.0218 9.50499C11.8776 9.32283 14.0367 9.79717 14.6663 10.5387C14.8895 10.8016 15.0081 11.1373 14.9996 11.4819Z"
        					  stroke="white"
        					  stroke-linecap="round"
        					  stroke-linejoin="round"
        				  />
        			</svg>
    			</div>
    		</a>
    		<a href="https://www.instagram.com/crabs.ua/" target="_blank" rel="nofollow">
    			<div class="floating-contact-icon contact-icon-instagram"><img src="/wp-content/themes/carbs-theme/img/icons/instagram-white.svg" alt="Instagram" width="32" height="32"></div>
    		</a>
    		<a href="viber://chat?number=380990084117" data-social="viber" rel="nofollow" target="_blank">
                <div class="floating-contact-icon contact-icon-viber"><img src="/wp-content/themes/carbs-theme/img/icons/viber-white.svg" width="32" height="32" loading="lazy" alt="Viber icon"></div>
            </a>
    		<a href="https://t.me/crabsua" rel="nofollow" target="_blank">
    			<div class="floating-contact-icon contact-icon-telegram"><img src="/wp-content/themes/carbs-theme/img/icons/telegram-white.svg" width="32" height="32" loading="lazy" alt="Telegram icon"></div>
    		</a>
    	</div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById("floatingContactButton");
        const mainButton = container.querySelector(".contact-button-icon");
        const social = container.querySelector(".social-icons");
    
        mainButton.addEventListener("click", function (e) {
            e.stopPropagation();
            social.style.display = (social.style.display === "flex") ? "none" : "flex";
        });
    
        container.querySelectorAll(".floating-contact-icon").forEach(icon => {
            icon.addEventListener("click", function () {
                social.style.display = "none";
            });
        });
    
        document.addEventListener("click", function (e) {
            if (!container.contains(e.target)) {
                social.style.display = "none";
            }
        });
    });
    </script>
    <script>
    (function () {
      var isMobile = /Android|iPhone|iPad|iPod|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
      if (!isMobile) return;
    
      document.querySelectorAll('a[href^="viber://chat?number="]').forEach(function (a) {
        var href = a.getAttribute('href');
    
        var m = href.match(/^viber:\/\/chat\?number=\+?(\d+)/i);
        if (!m) return;
    
        var num = m[1]; 
        a.setAttribute('href', 'viber://add?number=' + num);
      });
    })();
    </script>



</div> <!-- wrapper -->
<?php wp_footer(); ?>
</body>
</html>
