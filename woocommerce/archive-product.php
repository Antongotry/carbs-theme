<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' ); ?>

<div class="catalog-header container" id="primary">
        <div class="catalog-header__left">
            <?php
            /**
             * Hook: woocommerce_show_page_title.
             *
             * Allow developers to remove the product taxonomy archive page title.
             *
             * @since 2.0.6.
             */
            if ( apply_filters( 'woocommerce_show_page_title', true ) ) :
                ?>
                <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
               
            <?php endif; ?>


            <?php echo do_shortcode('[fe_chips reset="yes"]') ?>

            
        </div>
        <div class="catalog-header__right">
            <div class="catalog-header__select">
                <div class="modal__header">
                    <span>Сортування</span>
                    <div id="closeBtnSort">
                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.9709 0.65625L6.31408 6.3131M6.31408 6.3131L0.657227 11.97M6.31408 6.3131L0.657227 0.65625M6.31408 6.3131L11.9709 11.97" stroke="#E93A53" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <span class="select-title">Сортувати за:</span>

                <?php
                // Получаем опции сортировки каталога
                $catalog_orderby_options = array(
                    'price'      => 'Спочатку найдешевші',
                    'popularity' => 'Популярні',
                    'rating'     => 'Рекомендовані',
                    'date'       => 'За новизною',
                    'price-desc' => 'Спочатку найдорожчі',
                );
                $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'menu_order';
                ?>
                <div class="select-body">
                    <div class="select">
                        <span class="select-current">
                            <?php echo $catalog_orderby_options[$orderby]; ?>
                        </span>
                        <svg width="7" height="5" viewBox="0 0 7 5" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0L7 8.34903e-08L3.5 5L0 0Z"></path>
                        </svg>
                    </div>
                    <div class="option-body">
                        <?php foreach ($catalog_orderby_options as $id => $name) : ?>
                            <div class="option" data-value="<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <form class="woocommerce-ordering" method="get" style="display: none;">
                    <select name="orderby" class="orderby" aria-label="<?php esc_attr_e('Shop order', 'woocommerce'); ?>">
                        <?php foreach ($catalog_orderby_options as $id => $name) : ?>
                            <option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>><?php echo esc_html($name); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="paged" value="1" />
                    <?php wc_query_string_form_fields(null, array('orderby', 'submit', 'paged', 'product-page')); ?>
                </form>

                
            </div>
            <div class="catalog-header__types">
                <a href="##" class="catalog-header__type active">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="9.56445" y="0.53125" width="11.9032" height="5.45161" rx="0.5" />
                        <rect x="9.56445" y="8.27539" width="11.9032" height="5.45161" rx="0.5" />
                        <rect x="9.56445" y="16.0156" width="11.9032" height="5.45161" rx="0.5" />
                        <rect x="0.532227" y="0.53125" width="6.74193" height="5.45161" rx="0.5" />
                        <rect x="0.532227" y="8.27539" width="6.74193" height="5.45161" rx="0.5" />
                        <rect x="0.532227" y="16.0156" width="6.74193" height="5.45161" rx="0.5" />
                    </svg>
                </a>
                <a href="##" class="catalog-header__type">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.532227" y="0.53125" width="9.32258" height="9.32258" rx="0.5" />
                        <rect x="12.1445" y="0.53125" width="9.32258" height="9.32258" rx="0.5" />
                        <rect x="0.532227" y="12.1465" width="9.32258" height="9.32258" rx="0.5" />
                        <rect x="12.1445" y="12.1465" width="9.32258" height="9.32258" rx="0.5" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="categories-horizontal-scroll-wrapper__categories-list">
    <?php
        $category = get_queried_object();
        $category_id = $category->term_id;
        if (function_exists('my_theme_render_categories')) {
            my_theme_render_categories('categories-list', $category_id);
        }
    ?>
    </div>
    <div class="catalog-header__right-mob">
        <a href="##" id="filter" class="catalog-header__icon">
            <svg width="26" height="16" viewBox="0 0 26 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line x1="12.499" y1="3" x2="25.249" y2="3" stroke="#242424" stroke-width="1.5" stroke-linecap="round" />
                <line x1="0.75" y1="12.25" x2="13.5" y2="12.25" stroke="#242424" stroke-width="1.5" stroke-linecap="round" />
                <line x1="0.75" y1="3" x2="6" y2="3" stroke="#242424" stroke-width="1.5" stroke-linecap="round" />
                <line x1="20" y1="12.25" x2="25.25" y2="12.25" stroke="#242424" stroke-width="1.5" stroke-linecap="round" />
                <circle cx="9.37402" cy="3.375" r="2.625" stroke="#E93A53" stroke-width="1.5" stroke-linecap="round" />
                <circle cx="16.626" cy="12.625" r="2.625" stroke="#E93A53" stroke-width="1.5" stroke-linecap="round" />
            </svg>
            <span>Фільтр</span>
        </a>
        <a href="##" class="catalog-header__icon" id="sort">
            <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 1V20.5M5 20.5L1 17M5 20.5L9 17" stroke="#E93A53" stroke-width="1.5" stroke-linecap="round" />
                <path d="M12.5 2H30.5M12.5 6.5H27.0946M12.5 11H24.1757M12.5 15.5H20.7703M12.5 20H17.3649" stroke="#3D3D3D" stroke-width="1.5" stroke-linecap="round" />
            </svg>
        </a>
    </div>

    <div class="catalog-main container">


	<aside data-spoilers id="sidebar">
		<div class="modal__header">
			<span>Фільтри</span>
			<div id="closeBtnFilter">
			<svg
				width="13"
				height="13"
				viewBox="0 0 13 13"
				fill="none"
				xmlns="http://www.w3.org/2000/svg"
			>
				<path
				d="M11.9709 0.65625L6.31408 6.3131M6.31408 6.3131L0.657227 11.97M6.31408 6.3131L0.657227 0.65625M6.31408 6.3131L11.9709 11.97"
				stroke="#E93A53"
				stroke-linecap="round"
				stroke-linejoin="round"
				/>
			</svg>
			</div>
		</div>
		
		<?php echo do_shortcode('[fe_widget]'); ?>

        <div class="modal-widget-close">Застосувати фільтри</div>
	</aside>
        
        
       


<?php
/**
 * Hook: woocommerce_shop_loop_header.
 *
 * @since 8.6.0
 *
 * @hooked woocommerce_product_taxonomy_archive_header - 10
 */
 
 
 
do_action( 'woocommerce_shop_loop_header' );

if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' ); ?>

</div>


<?php
// Вивід опису категорії перед футером у контейнері з Crabs
if ( is_product_category() ) {
    $term_description = term_description();
    if ( ! empty( $term_description ) ) {
        echo '<div class="catalog-main container">';
        echo '<div class="bottom-category-description">';
        echo wpautop( $term_description );
        echo '</div>';
        echo '</div>';
    }
}
?>




<?php 
/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );



get_footer( 'shop' );
