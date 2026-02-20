<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Динамическая версия стилей и скриптов - Start
function get_dynamic_version()
{
	return date('YmdHis');
	// return '2100.01.01';
}
// Динамическая версия стилей и скриптов - End

// Принудительная инициализация WooCommerce на кастомных страницах
function ensure_woocommerce_loaded() {
    if (function_exists('WC')) {
        if (null === WC()->session) {
            WC()->session = new WC_Session_Handler(); // Инициализируем сессию
            WC()->session->init();
        }

        if (null === WC()->cart) {
            WC()->cart = new WC_Cart(); // Инициализируем корзину
        }

        WC()->cart->calculate_totals(); // Пересчитываем корзину
    }
}
add_action('template_redirect', 'ensure_woocommerce_loaded');

// Очистка корзины после успешного заказа
add_action('wp_footer', function() {
    if (is_page('thank-you')) { // Если это страница благодарности
        WC()->cart->empty_cart(); // Очищаем корзину
    }
});

 
// Gallery Product Page - Start
function yourtheme_add_woocommerce_support()
{
	add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'yourtheme_add_woocommerce_support');
add_filter('woocommerce_gallery_image_size', function ($size) {
	return 'full';
});
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
// Gallery Product Page - End

// Disable WooCommerce styles
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

// Dequeue parent theme styles
// Remove parent Storefront inline-styles
add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

add_action('after_setup_theme', 'remove_parent_theme_supports', 20);
function remove_parent_theme_supports() {
    //remove_theme_support('wc-product-gallery-zoom');
    //remove_theme_support('wc-product-gallery-lightbox');
    add_theme_support('woocommerce', array(
        'single_image_width' => 930,
        'thumbnail_image_width' => 930,
        'gallery_thumbnail_image_width' => 100,
    ));

    // Ensure WooCommerce image sizes are set
    update_option('woocommerce_single_image_width', 930);
    update_option('woocommerce_thumbnail_image_width', 930);
    update_option('woocommerce_gallery_thumbnail_image_width', 100);
}

// Добавляем поддержку загрузки svg картинок

function sanitize_svg($file) {
    $filetype = wp_check_filetype($file['name']);
    if ($filetype['ext'] === 'svg') {
        $file['type'] = 'image/svg+xml';
    }
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'sanitize_svg');
  

/**
 * @snippet       Edit SELECT OPTIONS Button - WordPress Shop
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 5
 * @community     https://businessbloomer.com/club/
 */
 
add_filter( 'woocommerce_product_add_to_cart_text', 'bbloomer_change_select_options_button_text', 9999, 2 );
 
function bbloomer_change_select_options_button_text( $label, $product ) {
   if ( $product->is_type( 'variable' ) ) {
      return 'Додати в кошик';
   }
   return $label;
}



/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}

// Enqueue child theme styles and scripts
function crabs_project_enqueue_styles_scripts() {
    // External stylesheets
    wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), null );
    wp_enqueue_style( 'baguettebox-css', 'https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.css', array(), null );

    // Child theme stylesheet
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/css/style.min.css', array(), null );
    wp_enqueue_style( 'additional-styles', get_stylesheet_directory_uri() . '/css/add-style.css', array(), null );
    wp_enqueue_style( 'wooeshop-izitoast', get_stylesheet_directory_uri() . '/css/iziToast.min.css' );

    // External scripts
    wp_enqueue_script( 'jquery' ); // Подключение jQuery
    wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array('jquery'), null, true );
    wp_enqueue_script( 'baguettebox-js', 'https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.js', array('jquery'), null, true );

    if (is_checkout() || is_page('broniuvannia-do-polohiv')) {
        wp_enqueue_script('maskedinput', get_stylesheet_directory_uri() . '/js/jquery.maskedinput.min.js', array('jquery'));
        add_action( 'wp_footer', 'masked_script', 999);
    }

    // Custom script files
    wp_enqueue_script( 'app-js', get_stylesheet_directory_uri() . '/js/app.min.js', array('jquery'), null, true );
    wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/js/custom-scripts.js', array('jquery'), null, true );

    // Localize the script with new data
    global $wp_query;
    $current_category = is_product_category() ? get_queried_object()->slug : '';  // Получаем slug категории для WooCommerce

    wp_localize_script('custom-js', 'load_more_params', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'current_page' => max(1, get_query_var('paged')),
        'max_page' => $wp_query->max_num_pages,
        'review_nonce' => wp_create_nonce('review_nonce'),
        'current_category' => $current_category  // Передаем текущую категорию
    ));


    wp_enqueue_script( 'wooeshop-izitoast', get_stylesheet_directory_uri() . '/js/iziToast.min.js', array(), false, true );
    wp_enqueue_script( 'wooeshop-jquery-cookie', get_stylesheet_directory_uri() . '/js/jquery.cookie.js', array(), false, true );

    wp_enqueue_script( 'wooeshop-main', get_stylesheet_directory_uri() . '/js/wishlist.js', array('jquery', 'wooeshop-jquery-cookie'), false, true );

	wp_localize_script( 'wooeshop-main', 'wooeshop_wishlist_object', array(
		'url'   => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'wooeshop_wishlist_nonce' ),
		'remove' => __( 'Товар видалений зі списку обраного', 'wooeshop' ),
		'add' => __( 'Товар доданий до списку обраного', 'wooeshop' ),
		'reload' => __( 'Сторінка будет перезавантажена', 'wooeshop' ),
	) );


}
add_action( 'wp_enqueue_scripts', 'crabs_project_enqueue_styles_scripts' );

function masked_script() {
    if ( wp_script_is( 'jquery', 'done' ) ) {
        ?>
            <script type="text/javascript">
                jQuery( function( $ ) {
                    $("#billing_phone, .wpcf7-validates-as-tel").mask("+38(999) 999-99-99",{placeholder:"+38(___) ___-__-__"});
                });
            </script>
        <?php
    }
}

function enqueue_review_script() {
    wp_enqueue_script('review-ajax', get_stylesheet_directory_uri() . '/js/review-ajax.js', array('jquery'), null, true);
    wp_localize_script('review-ajax', 'review_ajax_obj', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('review_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_review_script');





function crabs_project_customize_register( $wp_customize ) {
    class WP_Customize_TinyMCE_Control extends WP_Customize_Control {
        public $type = 'tinymce_editor';

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <textarea class="tinymce-editor" id="<?php echo $this->id; ?>" <?php $this->link(); ?> rows="5" style="width:100%;"><?php echo esc_textarea( $this->value() ); ?></textarea>
            </label>
            <?php
        }
    }

    // Add setting for logo
    $wp_customize->add_setting( 'crabs_project_logo' );

    // Add control for logo in Site Identity section
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'crabs_project_logo', array(
        'label'    => __( 'Завантажити лого', 'crabs_project' ),
        'section'  => 'title_tagline',
        'settings' => 'crabs_project_logo',
    ) ) );

    // Add Section for Footer
    $wp_customize->add_section('footer_section', array(
        'title'    => __('Підвал', 'crabs'),
        'priority' => 30,
    ));

    // Footer Text Under Logo
    $wp_customize->add_setting('footer_text_under_logo', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('footer_text_under_logo', array(
        'label'    => __('Текст під лого', 'crabs'),
        'section'  => 'footer_section',
        'settings' => 'footer_text_under_logo',
        'type'     => 'textarea',
    ));

    // Add Section for Product Page Settings
    $wp_customize->add_panel('woocommerce_crabs_panel', array(
        'title'    => __('Woocommerce Crabs', 'crabs'),
        'priority' => 40,
    ));

    $wp_customize->add_section('product_page_section', array(
        'title'    => __('Сторінка товару', 'crabs'),
        'priority' => 35,
        'panel'    => 'woocommerce_crabs_panel',
    ));

    // Оплата Setting with TinyMCE
    $wp_customize->add_setting('product_page_payment', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_TinyMCE_Control($wp_customize, 'product_page_payment', array(
        'label'    => __('Оплата', 'crabs'),
        'section'  => 'product_page_section',
        'settings' => 'product_page_payment',
    )));

    // Доставка Setting with TinyMCE
    $wp_customize->add_setting('product_page_shipping', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_TinyMCE_Control($wp_customize, 'product_page_shipping', array(
        'label'    => __('Доставка', 'crabs'),
        'section'  => 'product_page_section',
        'settings' => 'product_page_shipping',
    )));

    // Бронювання до пологів Setting with TinyMCE
    $wp_customize->add_setting('product_page_booking', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_TinyMCE_Control($wp_customize, 'product_page_booking', array(
        'label'    => __('Бронювання до пологів', 'crabs'),
        'section'  => 'product_page_section',
        'settings' => 'product_page_booking',
    )));
    
    // Footer Address
    $wp_customize->add_setting('footer_address', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('footer_address', array(
        'label'    => __('Адреса', 'crabs'),
        'section'  => 'footer_section',
        'settings' => 'footer_address',
        'type'     => 'textarea',
    ));
    
    // Footer Email
    $wp_customize->add_setting('footer_email', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('footer_email', array(
        'label'    => __('Email', 'crabs'),
        'section'  => 'footer_section',
        'settings' => 'footer_email',
        'type'     => 'text',
    ));
    
    // Footer Phone 1
    $wp_customize->add_setting('footer_phone_1', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('footer_phone_1', array(
        'label'    => __('Телефон 1', 'crabs'),
        'section'  => 'footer_section',
        'settings' => 'footer_phone_1',
        'type'     => 'text',
    ));
    
    // Footer Phone 2
    $wp_customize->add_setting('footer_phone_2', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('footer_phone_2', array(
        'label'    => __('Телефон 2', 'crabs'),
        'section'  => 'footer_section',
        'settings' => 'footer_phone_2',
        'type'     => 'text',
    ));
    
    // Instagram
    $wp_customize->add_setting('footer_social_instagram', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('footer_social_instagram', array(
        'label'    => __('Instagram', 'crabs'),
        'section'  => 'footer_section',
        'settings' => 'footer_social_instagram',
        'type'     => 'text',
    ));
    
    // Telegram
    $wp_customize->add_setting('footer_social_telegram', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('footer_social_telegram', array(
        'label'    => __('Telegram', 'crabs'),
        'section'  => 'footer_section',
        'settings' => 'footer_social_telegram',
        'type'     => 'text',
    ));
    
    // Viber
    $wp_customize->add_setting('footer_social_viber', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('footer_social_viber', array(
        'label'    => __('Viber', 'crabs'),
        'section'  => 'footer_section',
        'settings' => 'footer_social_viber',
        'type'     => 'text',
    ));

}
add_action('customize_register', 'crabs_project_customize_register');

// Подключаем TinyMCE
function crabs_project_enqueue_tinymce() {
    wp_enqueue_script('tinymce');
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '.tinymce-editor',
                menubar: false,
                toolbar: 'bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent',
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                        $(editor.getElement()).trigger('change');
                    });
                }
            });
        }
    });
    </script>
    <?php
}
add_action('customize_controls_print_footer_scripts', 'crabs_project_enqueue_tinymce');




// Disable Gutenberg for all post types except 'post'
add_filter('use_block_editor_for_post', 'conditional_gutenberg_for_post', 10, 2);
function conditional_gutenberg_for_post($use_block_editor, $post) {
    if ($post->post_type === 'post') {
        return true; // Enable Gutenberg for posts
    }
    return false; // Disable Gutenberg for other post types
}

// Disable Gutenberg for custom post types
add_filter('use_block_editor_for_post_type', 'conditional_gutenberg_for_post_type', 10, 2);
function conditional_gutenberg_for_post_type($use_block_editor, $post_type) {
    if ($post_type === 'post') {
        return true; // Enable Gutenberg for posts
    }
    return false; // Disable Gutenberg for other post types
}

// Disable Gutenberg for widgets
add_action('after_setup_theme', 'remove_gutenberg_widget_support');
function remove_gutenberg_widget_support() {
    remove_theme_support('widgets-block-editor');
}


// Добавление поля при создании новой категории
function add_custom_field_to_product_cat() {
    ?>
    <div class="form-field">
        <label for="main_category"><?php _e( 'Головна категорія', 'crabs_project' ); ?></label>
        <input type="checkbox" name="main_category" id="main_category" value="1">
    </div>
    <?php
}
add_action( 'product_cat_add_form_fields', 'add_custom_field_to_product_cat', 10, 2 );

// Добавление поля при редактировании категории
function edit_custom_field_in_product_cat($term) {
    $main_category = get_term_meta($term->term_id, 'main_category', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="main_category"><?php _e( 'Головна категорія', 'crabs_project' ); ?></label></th>
        <td>
            <input type="checkbox" name="main_category" id="main_category" value="1" <?php checked($main_category, '1'); ?>>
        </td>
    </tr>
    <?php
}
add_action( 'product_cat_edit_form_fields', 'edit_custom_field_in_product_cat', 10, 2 );

// Сохранение значения поля
function save_custom_field_in_product_cat($term_id) {
    if (isset($_POST['main_category'])) {
        update_term_meta($term_id, 'main_category', '1');
    } else {
        update_term_meta($term_id, 'main_category', '');
    }
}
add_action( 'edited_product_cat', 'save_custom_field_in_product_cat', 10, 2 );
add_action( 'create_product_cat', 'save_custom_field_in_product_cat', 10, 2 );


function crabs_project_custom_woocommerce_image_dimensions() {
    // Set image sizes for product catalog
    update_option('woocommerce_thumbnail_image_width', 463);
    update_option('woocommerce_thumbnail_cropping', 'custom'); // Enable custom cropping
    update_option('woocommerce_thumbnail_cropping_width', 463);
    update_option('woocommerce_thumbnail_cropping_height', 487);

    // Set image sizes for single product page
    update_option('woocommerce_single_image_width', 930);
    // Default height for aspect ratio will be set automatically
}
add_action('after_switch_theme', 'crabs_project_custom_woocommerce_image_dimensions');

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );

add_filter('woocommerce_breadcrumb_home_url', function($url){
    if (!empty($_SERVER['HTTP_REFERER'])) {
        return esc_url($_SERVER['HTTP_REFERER']);
    }
    return home_url(); // fallback
});

// Breadcrumbs
function custom_woocommerce_breadcrumb($args = array()) {
    $args = wp_parse_args( $args, apply_filters( 'woocommerce_breadcrumb_defaults', array(
        'delimiter'   => ' / ',
        'wrap_before' => '<div class="breadcrambs"><div class="container">',
        'wrap_after'  => '</div></div>',
        'before'      => '<span>',
        'after'       => '</span>',
        'home'        => _x( 'Повернутися назад', 'breadcrumb', 'woocommerce' ),
    ) ) );

    $breadcrumbs = new WC_Breadcrumb();

    if ( ! empty( $args['home'] ) ) {
        $breadcrumbs->add_crumb( $args['home'], apply_filters( 'woocommerce_breadcrumb_home_url', home_url() ) );
    }

    $args['breadcrumb'] = $breadcrumbs->generate();

    wc_get_template( 'global/breadcrumb.php', $args );
}

function custom_breadcrumb_output($args) {
    ob_start();
    custom_woocommerce_breadcrumb($args);
    $breadcrumb_html = ob_get_clean();

    // Добавляем класс к последнему элементу
    $breadcrumb_html = preg_replace('/<span>([^<]+)<\/span>\s*<\/div>\s*<\/div>$/', '<span class="active">$1</span></div></div>', $breadcrumb_html);

    echo $breadcrumb_html;
}

add_filter('woocommerce_get_breadcrumb', function($crumbs, $breadcrumb){
    if (is_single() && get_post_type() === 'post' || is_category()) {

        // array_shift($crumbs);

        array_splice($crumbs, 1, 0, [
            [
                'Блог',
                get_permalink(get_option('page_for_posts'))
            ]
        ]);
    }

    return $crumbs;
}, 20, 2);




// Удаление элемента woocommerce-result-count
function remove_woocommerce_result_count_actions() {
    remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_before_main_content', 'storefront_before_content', 10 );
    remove_action( 'woocommerce_after_main_content', 'storefront_after_content', 10 );
    
    remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9 );
    remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 31 );

    remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper', 9 );
    remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close', 31 );

    remove_action( 'woocommerce_before_shop_loop', 'storefront_woocommerce_pagination', 30 );
    remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 30 );

    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10); 

}
add_action( 'wp', 'remove_woocommerce_result_count_actions', 10 );

// Меняем название текста Sale
add_filter('gettext', 'change_sale_label', 20, 3);
function change_sale_label($translated_text, $text, $domain) {
    if ($text === 'On Sale') {
        $translated_text = 'Товари зі знижкою';
    }
    return $translated_text;
}

// Добавляем новую вкладку "Додаткові параметри" и "Характеристики"
add_filter('woocommerce_product_data_tabs', 'add_custom_product_data_tab');
function add_custom_product_data_tab($tabs) {
    $tabs['additional_params'] = array(
        'label'    => __('Додаткові параметри', 'woocommerce'),
        'target'   => 'additional_params_product_data',
        'class'    => array('show_if_simple', 'show_if_variable'),
    );
    $tabs['characteristics'] = array(
        'label'    => __('Характеристики', 'woocommerce'),
        'target'   => 'characteristics_product_data',
        'class'    => array('show_if_simple', 'show_if_variable'),
    );
    return $tabs;
}

// Контент новых вкладок "Додаткові параметри" и "Характеристики"
add_action('woocommerce_product_data_panels', 'add_custom_product_data_fields');
function add_custom_product_data_fields() {
    global $woocommerce, $post;
    ?>
    <div id="additional_params_product_data" class="panel woocommerce_options_panel">
        <div class="options_group">
            <?php
            woocommerce_wp_text_input(
                array(
                    'id' => '_chassis_weight',
                    'label' => __('Вага шасі з люлькою, кг', 'woocommerce'),
                    'desc_tip' => 'true',
                    'description' => __('Вага шасі з люлькою у кг.', 'woocommerce'),
                    'type' => 'number',
                    'custom_attributes' => array(
                        'step' => 'any',
                        'min' => '0'
                    )
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => '_chassis_width',
                    'label' => __('Ширина шасі, см', 'woocommerce'),
                    'desc_tip' => 'true',
                    'description' => __('Ширина шасі у см.', 'woocommerce'),
                    'type' => 'number',
                    'custom_attributes' => array(
                        'step' => 'any',
                        'min' => '0'
                    )
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => '_group_carseats',
                    'label' => __('Група автокрісла', 'woocommerce'),
                    'desc_tip' => 'true',
                    'description' => __('Введіть групу', 'woocommerce'),
                    'type' => 'text'
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => '_group_age',
                    'label' => __('Вікова група', 'woocommerce'),
                    'desc_tip' => 'true',
                    'description' => __('Введіть групу', 'woocommerce'),
                    'type' => 'text'
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => '_baby_pack',
                    'label' => __('Пакунок малюка', 'woocommerce'),
                    'desc_tip' => 'true',
                    'description' => __('Введіть назву', 'woocommerce'),
                    'type' => 'text'
                )
            );
            woocommerce_wp_checkbox(
                array(
                    'id' => '_instok_showroom',
                    'label' => __('В наявності у шоурумі', 'woocommerce'),
                    'description' => __('Якщо так, натисніть на чекбокс', 'woocommerce'),
                    'desc_tip' => 'true'
                )
            );
            ?>

            <style>
                ._instok_showroom_field input[type="checkbox"]{
                    width: auto;
                }
            </style>
        </div>
    </div>
    <div id="characteristics_product_data" class="panel woocommerce_options_panel">
        <div class="options_group">
            <?php
            woocommerce_wp_text_input(
                array(
                    'id' => '_type',
                    'label' => __('Тип', 'woocommerce'),
                    'desc_tip' => 'true',
                    'description' => __('Введіть тип', 'woocommerce'),
                    'type' => 'text'
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => '_color_stroller',
                    'label' => __('Колір коляски', 'woocommerce'),
                    'desc_tip' => 'true',
                    'description' => __('Введіть колір коляски', 'woocommerce'),
                    'type' => 'text'
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => '_color_frame',
                    'label' => __('Колір рами', 'woocommerce'),
                    'desc_tip' => 'true',
                    'description' => __('Введіть колір рами', 'woocommerce'),
                    'type' => 'text'
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => '_wheels',
                    'label' => __('Колеса', 'woocommerce'),
                    'desc_tip' => 'true',
                    'description' => __('Введіть тип колес', 'woocommerce'),
                    'type' => 'text'
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => '_handle_adjustment',
                    'label' => __('Регулювання батьківської ручки', 'woocommerce'),
                    'desc_tip' => 'true',
                    'description' => __('Введіть спосіб регулювання', 'woocommerce'),
                    'type' => 'text'
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => '_brand_country',
                    'label' => __('Країна бренду', 'woocommerce'),
                    'desc_tip' => 'true',
                    'description' => __('Введіть країну бренду', 'woocommerce'),
                    'type' => 'text'
                )
            );
            woocommerce_wp_textarea_input(
                array(
                    'id' => '_package',
                    'label' => __('Комплектація', 'woocommerce'),
                    'desc_tip' => 'true',
                    'description' => __('Введіть комплектацію', 'woocommerce'),
                )
            );
            ?>

        </div>
    </div>
    <?php
}

// Сохраняем значения полей
add_action('woocommerce_process_product_meta', 'save_custom_product_data_fields');
function save_custom_product_data_fields($post_id) {
    $fields = [
        '_chassis_weight',
        '_chassis_width',
        '_group_carseats',
        '_group_age',
        '_baby_pack',
        '_type',
        '_color_stroller',
        '_color_frame',
        '_wheels',
        '_handle_adjustment',
        '_brand_country',
        '_package',
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Обработка чекбокса
    $wc_checkbox = isset( $_POST['_instok_showroom'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_instok_showroom', $wc_checkbox );
    
}


// Количество продуктов на архивной странице
function custom_loop_shop_per_page($cols) : int {
        return 12;
}
add_filter('loop_shop_per_page', 'custom_loop_shop_per_page', 20);


// Сортировка продуктов
function custom_default_product_sorting( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'product' ) ) {
        if ( ! isset( $_GET['orderby'] ) ) {
            $query->set( 'meta_key', 'total_sales' );
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'order', 'DESC' );
        }
    }
}
add_action( 'pre_get_posts', 'custom_default_product_sorting' );


// Кнопка Завантажити больше
function load_more_products() {
    parse_str($_POST['filters'], $filter_args);
    $paged = isset($_POST['page']) ? intval($_POST['page']) + 1 : 2;
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'popularity';
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';

    $base_meta_query = array(
        'stock_clause' => array(
            'key'     => '_stock_status',
            'compare' => 'EXISTS'
        )
    );

    switch ($orderby) {
        case 'price':
            $orderby_query = array(
                'meta_query' => array_merge($base_meta_query, array(
                    'price_clause' => array(
                        'key'     => '_price',
                        'compare' => 'EXISTS',
                        'type'    => 'NUMERIC'
                    )
                )),
                'orderby' => array(
                    'stock_clause' => 'ASC',
                    'price_clause' => 'ASC'
                )
            );
            break;

        case 'price-desc':
            $orderby_query = array(
                'meta_query' => array_merge($base_meta_query, array(
                    'price_clause' => array(
                        'key'     => '_price',
                        'compare' => 'EXISTS',
                        'type'    => 'NUMERIC'
                    )
                )),
                'orderby' => array(
                    'stock_clause' => 'ASC',
                    'price_clause' => 'DESC'
                )
            );
            break;

        case 'popularity':
            $orderby_query = array(
                'meta_query' => array_merge($base_meta_query, array(
                    'sales_clause' => array(
                        'key'     => 'total_sales',
                        'compare' => 'EXISTS',
                        'type'    => 'NUMERIC'
                    )
                )),
                'orderby' => array(
                    'stock_clause' => 'ASC',
                    'sales_clause' => 'DESC'
                )
            );
            break;

        case 'rating':
            $orderby_query = array(
                'meta_query' => array_merge($base_meta_query, array(
                    'rating_clause' => array(
                        'key'     => '_wc_average_rating',
                        'compare' => 'EXISTS',
                        'type'    => 'NUMERIC'
                    )
                )),
                'orderby' => array(
                    'stock_clause' => 'ASC',
                    'rating_clause' => 'DESC'
                )
            );
            break;

        case 'date':
        default:
            $orderby_query = array(
                'meta_query' => $base_meta_query,
                'orderby' => array(
                    'stock_clause' => 'ASC',
                    'date' => 'DESC'
                )
            );
            break;
    }

    $mockValue = 1;
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'paged' => $paged,
        'posts_per_page' => custom_loop_shop_per_page($mockValue),
    );

    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );
    }

    if (!empty($filter_args)) {
        $tax_query = [];
        $additional_meta_query = [];

        foreach ($filter_args as $key => $value) {
            if ($key === 'color' || $key === 'size') {
                $tax_query[] = [
                    'taxonomy' => $key,
                    'field'    => 'slug',
                    'terms'    => $value,
                ];
            }
            elseif ($key === 'length') {
                $additional_meta_query[] = [
                    'key'     => $key,
                    'value'   => $value,
                    'compare' => '=',
                ];
            }
            elseif ($key === 'min_price') {
                $additional_meta_query[] = [
                    'key'     => '_price',
                    'value'   => floatval($value),
                    'compare' => '>=',
                    'type'    => 'NUMERIC',
                ];
            }
            elseif ($key === 'max_price') {
                $additional_meta_query[] = [
                    'key'     => '_price',
                    'value'   => floatval($value),
                    'compare' => '<=',
                    'type'    => 'NUMERIC',
                ];
            }
            elseif ($key === 'min_chassis-weight') {
                $additional_meta_query[] = [
                    'key'     => 'chassis-weight',
                    'value'   => floatval($value),
                    'compare' => '>=',
                    'type'    => 'NUMERIC',
                ];
            }
            elseif ($key === 'max_chassis-weight') {
                $additional_meta_query[] = [
                    'key'     => 'chassis-weight',
                    'value'   => floatval($value),
                    'compare' => '<=',
                    'type'    => 'NUMERIC',
                ];
            }
            elseif ($key === 'min_chassis-width') {
                $additional_meta_query[] = [
                    'key'     => 'chassis-width',
                    'value'   => floatval($value),
                    'compare' => '>=',
                    'type'    => 'NUMERIC',
                ];
            }
            elseif ($key === 'max_chassis-width') {
                $additional_meta_query[] = [
                    'key'     => 'chassis-width',
                    'value'   => floatval($value),
                    'compare' => '<=',
                    'type'    => 'NUMERIC',
                ];
            }
        }

        if (!empty($tax_query)) {
            if (isset($args['tax_query'])) {
                $args['tax_query']['relation'] = 'AND';
                $args['tax_query'] = array_merge($args['tax_query'], $tax_query);
            } else {
                $args['tax_query'] = $tax_query;
            }
        }

        if (!empty($additional_meta_query)) {
            if (!empty($orderby_query['meta_query'])) {
                $combined_meta_query = array_merge($orderby_query['meta_query'], $additional_meta_query);
                $orderby_query['meta_query'] = array_merge(['relation' => 'AND'], $combined_meta_query);
            } else {
                $orderby_query['meta_query'] = array_merge(['relation' => 'AND'], $additional_meta_query);
            }
        }
    }

    if (!empty($orderby_query['meta_query']) && !isset($orderby_query['meta_query']['relation'])) {
        $orderby_query['meta_query'] = array_merge(['relation' => 'AND'], $orderby_query['meta_query']);
    }

    $args = array_merge($args, $orderby_query);

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        ob_start();
        while ($query->have_posts()): $query->the_post();
            wc_get_template_part('content', 'product');
        endwhile;
        wp_send_json_success(ob_get_clean());
    else :
        error_log('No products found with args: ' . print_r($args, true));
        wp_send_json_error('No more products found.');
    endif;
    wp_die();
}
add_filter('posts_orderby', 'custom_orderby_stock_status', 10, 2);
function custom_orderby_stock_status($orderby, $query) {
    if (!is_admin() && $query->is_main_query()) {
        global $wpdb;
        $orderby = "
            (SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = '_stock_status') ASC,
            (SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = '_price')+0 ASC
        ";
    }
    return $orderby;
}


add_action('wp_ajax_nopriv_load_more_products', 'load_more_products');
add_action('wp_ajax_load_more_products', 'load_more_products');


// Хук для отслеживания просмотренных продуктов
function track_recently_viewed_products() {
    if ( ! is_singular( 'product' ) ) {
        return;
    }

    global $post;

    if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) { // если куки не существует
        $viewed_products = array();
    } else {
        $viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );
    }

    // Удаляем текущий продукт, если он уже есть в списке
    $viewed_products = array_diff( $viewed_products, array( $post->ID ) );

    // Добавляем текущий продукт в начало списка
    array_unshift( $viewed_products, $post->ID );

    // Ограничиваем список до 15 продуктов
    if ( sizeof( $viewed_products ) > 15 ) {
        array_pop( $viewed_products );
    }

    // Сохраняем список в куки
    wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}
add_action( 'template_redirect', 'track_recently_viewed_products', 20 );





// Reviews Shortcode
function review_form_shortcode() {
    global $post;
    if ($post->post_type !== 'product') {
        return ''; // Шорткод работает только на страницах продуктов
    }

    $unique_id = uniqid(); // Генерация уникального идентификатора для каждого инстанса формы

    ob_start();
    ?>
    <div id="review-form-container-<?php echo $unique_id; ?>" class="review-form-container">
        <form id="review-form-<?php echo $unique_id; ?>" enctype="multipart/form-data">
            <input type="hidden" id="product_id-<?php echo $unique_id; ?>" name="product_id" value="<?php echo $post->ID; ?>">
            <div class="reviews__form">
                <h3>Залишити відгук</h3>
                <div class="reviews__inputs">
                    <input type="text" id="first_name-<?php echo $unique_id; ?>" name="first_name" required placeholder="Ім'я">
                    <input type="text" id="last_name-<?php echo $unique_id; ?>" name="last_name" required placeholder="Прізвище">
                </div>
                <textarea id="review-<?php echo $unique_id; ?>" name="review" required placeholder="Ваш відгук"></textarea>
            </div>
            <div class="reviews__judgement">
                <h3>Ваша оцінка</h3>
                <div class="reviews-slider__value">
                    <div class="wrap">
                        <input type="radio" name="rating" value="5" id="5-stars-<?php echo $unique_id; ?>">
                        <label for="5-stars-<?php echo $unique_id; ?>">
                            <svg class="star" width="36" height="33" viewBox="0 0 36 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z" fill="#F4A804"/>
                            </svg>

                        </label>

                        <input type="radio" name="rating" value="4" id="4-stars-<?php echo $unique_id; ?>">
                        <label for="4-stars-<?php echo $unique_id; ?>">
                            <svg class="star" width="36" height="33" viewBox="0 0 36 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z" fill="#F4A804"/>
                            </svg>

                        </label>

                        <input type="radio" name="rating" value="3" id="3-stars-<?php echo $unique_id; ?>">
                        <label for="3-stars-<?php echo $unique_id; ?>">
                            <svg class="star" width="36" height="33" viewBox="0 0 36 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z" fill="#F4A804"/>
                            </svg>
                        </label>

                        <input type="radio" name="rating" value="2" id="2-stars-<?php echo $unique_id; ?>">
                        <label for="2-stars-<?php echo $unique_id; ?>">
                            <svg class="star" width="36" height="33" viewBox="0 0 36 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z" fill="#F4A804"/>
                            </svg>
                        </label>

                        <input type="radio" name="rating" value="1" id="1-star-<?php echo $unique_id; ?>">
                        <label for="1-star-<?php echo $unique_id; ?>">
                            <svg class="star" width="36" height="33" viewBox="0 0 36 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z" fill="#F4A804"/>
                            </svg>
                        </label>
                    </div>
                </div>
            </div>
                
            <div class="photo">
                <label for="photos-<?php echo $unique_id; ?>">Завантажити фото (макс. 5)</label>
                <input type="file" id="photos-<?php echo $unique_id; ?>" name="photos[]" multiple accept="image/*" max="5">
            </div>
            <div class="message-container"></div> <!-- Контейнер для сообщений -->
            <button class="btn-black" type="submit">Відправити відгук</button>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('review_form', 'review_form_shortcode');


// Get Raiting Count
function get_ratings_count($product_id) {
    $ratings_count = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
    $reviews = get_posts(array(
        'post_type' => 'product_reviews',
        'meta_key' => 'product_id',
        'meta_value' => $product_id,
        'post_status' => 'publish', // Только опубликованные отзывы
        'numberposts' => -1
    ));

    foreach ($reviews as $review) {
        $rating = get_post_meta($review->ID, 'rating', true);
        if ($rating) {
            $ratings_count[$rating]++;
        }
    }

    return $ratings_count;
}

function custom_review_image_size() {
    add_image_size('review-thumbnail', 43, 43, true); // true для жесткого обрезания
}
add_action('after_setup_theme', 'custom_review_image_size');

function handle_review_submission() {
    // Проверка nonce (если есть)

    // Получение данных из формы
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $product_id = intval($_POST['product_id']);
    $review = sanitize_textarea_field($_POST['review']);
    $rating = intval($_POST['rating']);
    $photos = $_FILES['photos'];

    // Получение названия продукта
    $product_title = get_the_title($product_id);

    // Проверка и загрузка фото
    $photo_urls = [];
    if ($photos && !empty($photos['name'][0])) {
        for ($i = 0; $i < count($photos['name']); $i++) {
            if ($i >= 5) break; // Ограничение на 5 фото
            $_FILES['photo'] = [
                'name' => $photos['name'][$i],
                'type' => $photos['type'][$i],
                'tmp_name' => $photos['tmp_name'][$i],
                'error' => $photos['error'][$i],
                'size' => $photos['size'][$i]
            ];
            $uploaded = media_handle_upload('photo', 0);
            if (is_wp_error($uploaded)) {
                wp_send_json_error(array('message' => 'Ошибка загрузки фото: ' . $uploaded->get_error_message()));
            }
            $photo_urls[] = wp_get_attachment_image_url($uploaded, 'full');
        }
    }

    // Создание отзыва как кастомного поста
    $review_post = array(
        'post_title' => $first_name . ' ' . $last_name,
        'post_content' => $review,
        'post_status' => 'pending', // Статус "на модерации"
        'post_type' => 'product_reviews',
        'meta_input' => array(
            'rating' => $rating,
            'photos' => $photo_urls,
            'product_id' => $product_id,
            'product_title' => $product_title
        )
    );

    $post_id = wp_insert_post($review_post);

    if (is_wp_error($post_id)) {
        wp_send_json_error(array('message' => 'Ошибка сохранения отзыва: ' . $post_id->get_error_message()));
    }

    wp_send_json_success(array('message' => 'Отзыв успешно отправлен на модерацию'));
}
add_action('wp_ajax_submit_review', 'handle_review_submission');
add_action('wp_ajax_nopriv_submit_review', 'handle_review_submission');


function add_photos_meta_box() {
    add_meta_box(
        'photos_meta_box', // ID метабокса
        'Фото отзыва', // Заголовок метабокса
        'display_photos_meta_box', // Функция для отображения метабокса
        'product_reviews', // Тип записи
        'normal', // Положение метабокса
        'high' // Приоритет
    );
}
add_action('add_meta_boxes', 'add_photos_meta_box');

function display_photos_meta_box($post) {
    $photo_urls = get_post_meta($post->ID, 'photos', true);

    if (!empty($photo_urls)) {
        foreach ($photo_urls as $index => $photo_url) {
            echo '<div class="photo-item">';
            echo '<img src="' . $photo_url . '" alt="Фото отзыва" style="max-width:100%; height:auto;"><br>';
            echo '<button class="button delete-photo" data-photo-index="' . $index . '">Удалить</button>';
            echo '</div>';
        }
    } else {
        echo '<p>Нет загруженных фотографий</p>';
    }
    ?>

    <style>
        .photo-item {
            margin-bottom: 15px;
            max-width: 150px;
            display: flex;
        }
        .photo-item .delete-photo {
            display: block;
            margin-top: 5px;
        }
    </style>
    <script>
        jQuery(document).ready(function($) {
            $('.delete-photo').on('click', function(e) {
                e.preventDefault();
                var photoIndex = $(this).data('photo-index');
                var postID = <?php echo $post->ID; ?>;
                var nonce = '<?php echo wp_create_nonce('delete_photo_nonce'); ?>';

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'delete_review_photo',
                        photo_index: photoIndex,
                        post_id: postID,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Ошибка удаления фото');
                        }
                    }
                });
            });
        });
    </script>
    <?php
}

function delete_review_photo() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'delete_photo_nonce')) {
        wp_send_json_error(array('message' => 'Ошибка nonce'));
        return;
    }

    $post_id = intval($_POST['post_id']);
    $photo_index = intval($_POST['photo_index']);

    $photo_urls = get_post_meta($post_id, 'photos', true);

    if (isset($photo_urls[$photo_index])) {
        unset($photo_urls[$photo_index]);
        update_post_meta($post_id, 'photos', array_values($photo_urls)); // Обновляем метаданные, убирая удалённое фото
        wp_send_json_success();
    } else {
        wp_send_json_error(array('message' => 'Фото не найдено'));
    }
}
add_action('wp_ajax_delete_review_photo', 'delete_review_photo');


function display_product_title_meta_box($post) {
    $product_title = get_post_meta($post->ID, 'product_title', true);
    echo '<input type="text" readonly value="' . esc_attr($product_title) . '" class="widefat" />';
}


function create_product_reviews_post_type() {
    register_post_type('product_reviews',
        array(
            'labels' => array(
                'name' => __('Отзывы о продуктах'),
                'singular_name' => __('Отзыв о продукте')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'custom-fields', 'thumbnail'),
            'show_in_rest' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => null,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-star-filled',
        )
    );
}
add_action('init', 'create_product_reviews_post_type');



function get_custom_product_average_rating($product_id) {
    // Получение всех отзывов для текущего продукта
    $reviews = get_posts(array(
        'post_type' => 'product_reviews',
        'meta_key' => 'product_id',
        'meta_value' => $product_id,
        'post_status' => 'publish', // Только опубликованные отзывы
        'numberposts' => -1
    ));

    if (!$reviews) {
        return 0; // Нет отзывов
    }

    $rating_sum = 0;
    $rating_count = 0;

    foreach ($reviews as $review) {
        $rating = get_post_meta($review->ID, 'rating', true);
        if ($rating) {
            $rating_sum += $rating;
            $rating_count++;
        }
    }

    if ($rating_count === 0) {
        return 0; // Нет оценок
    }

    return round($rating_sum / $rating_count, 2); // Средний рейтинг, округленный до двух знаков после запятой
}

// Шорткод вывода отзывов в карусели на странице продукта
function display_product_reviews($atts) {
    global $post;
    
    if ($post->post_type !== 'product') {
        return ''; // Шорткод работает только на страницах продуктов
    }
    
    $args = array(
        'post_type' => 'product_reviews',
        'meta_key' => 'product_id',
        'meta_value' => $post->ID,
        'post_status' => 'publish',
        'numberposts' => -1
    );
    
    $reviews = get_posts($args);
    
    if (!$reviews) {
        return '<p>Немає відгуків</p>'; // Сообщение, если нет отзывов
    }

    ob_start();
    
    foreach ($reviews as $review) {
        $first_name = get_the_title($review->ID);
        $rating = get_post_meta($review->ID, 'rating', true);
        $review_text = $review->post_content;
        $photo_urls = get_post_meta($review->ID, 'photos', true);
        $product_title = get_post_meta($review->ID, 'product_title', true);
        
        $avatar_url = !empty($photo_urls[0]) ? $photo_urls[0] : '';

        // Вычисляем количество звезд
        $stars_html = '';
        for ($i = 1; $i <= 5; $i++) {
            $star_color = $i <= $rating ? '#F4A804' : 'grey';
            $stars_html .= '<svg class="star" width="36" height="33" viewBox="0 0 36 33" fill="'.$star_color.'" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z"/>
                            </svg>';
        }
        
        // Вывод отзыва
        ?>
        <section class="swiper-slide">
            <div class="reviews-slider__top">
                <div class="reviews-slider__autor">
                    <div class="reviews-slider__avatar">
                        <img src="<?php echo esc_url($avatar_url ? $avatar_url : get_stylesheet_directory_uri() . '/img/Лого-4.png'); ?>" alt="avatar" />
                    </div>
                    <h3><?php echo $first_name; ?></h3>
                </div>
                <div class="reviews-slider__rating">
                    <div class="reviews-slider__stars">
                        <?php echo $stars_html; ?>
                    </div>
                    <span><?php echo number_format($rating, 1); ?></span>
                </div>
            </div>
            <div class="reviews-slider__text">
                <?php echo wpautop($review_text); ?>
            </div>
            <div class="reviews-slider__product gallery-reviews">
                
                <?php if (!empty($photo_urls)) : ?>
                    <?php foreach ($photo_urls as $photo_url) : ?>
                        <div class="reviews-slider__image">
                        <a href="<?php echo $photo_url; ?>">
                            <img src="<?php echo $photo_url; ?>" alt="product" />
                        </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
            </div>
        </section>
        <?php
    }
    
    return ob_get_clean();
}

add_shortcode('product_reviews', 'display_product_reviews');


function load_more_reviews() {
    header('Content-Type: application/json');

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $order = isset($_POST['order']) && $_POST['order'] === 'asc' ? 'ASC' : 'DESC';

    $args = array(
        'post_type' => 'product_reviews',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => $order,
        'posts_per_page' => 5, // Количество отзывов на страницу
        'paged' => $page
    );

    $reviews = new WP_Query($args);
    ob_start();

    if ($reviews->have_posts()) {
        while ($reviews->have_posts()) {
            $reviews->the_post();
            $first_name = get_the_title();
            $rating = get_post_meta(get_the_ID(), 'rating', true);
            $review_text = get_the_content();
            $review_date = get_the_date('d.m.Y');
            $photo_urls = get_post_meta(get_the_ID(), 'photos', true);

            // Вычисляем количество звезд
            $stars_html = '';
            for ($i = 1; $i <= 5; $i++) {
                $star_color = $i <= $rating ? '#F4A804' : 'grey';
                $stars_html .= '<svg class="star" width="36" height="33" viewBox="0 0 36 33" fill="'.$star_color.'" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z"/>
                                </svg>';
            }

            // Выводим отзыв
            ?>
            <section class="feedback-card">
                <div class="feedback-card__main">
                    <div class="feedback-card__left">
                        <div class="feedback-card__autor">
                            <div class="feedback-card__avatar">
                                <img src="<?php echo !empty($photo_urls) ? $photo_urls[0] : get_stylesheet_directory_uri() . '/img/photo.png'; ?>" alt="avatar" />
                            </div>
                            <h3><?php echo $first_name; ?></h3>
                        </div>
                        <div class="feedback-card__rating">
                            <div class="feedback-card__stars">
                                <?php echo $stars_html; ?>
                            </div>
                            <span><?php echo number_format($rating, 1); ?></span>
                        </div>
                    </div>
                    <div class="feedback-card__text">
                        <p><?php echo wp_trim_words($review_text, 20); ?></p>
                        <a href="<?php echo get_permalink(); ?>">Читати повністю</a>
                    </div>
                    <div class="feedback-card__right"><?php echo $review_date; ?></div>
                </div>
                <div class="feedback-card__product gallery-reviews">
                <?php if (!empty($photo_urls)) : ?>
                    <?php foreach ($photo_urls as $photo_url) : ?>
                        <div class="feedback-card__image">
                            <a href="<?php echo $photo_url; ?>">
                                <img src="<?php echo $photo_url; ?>" alt="product" />
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                </div>
            </section>
            <?php
        }
        wp_reset_postdata();
    } else {
        echo '<p>Нет отзывов</p>';
    }

    $output = ob_get_clean();
    $has_more = $reviews->max_num_pages > $page;
    echo json_encode(array('success' => true, 'html' => $output, 'order' => $order, 'has_more' => $has_more));
    wp_die();
}
add_action('wp_ajax_load_more_reviews', 'load_more_reviews');
add_action('wp_ajax_nopriv_load_more_reviews', 'load_more_reviews');



// Функция AJAX для добавления товара в корзину
function ajax_add_to_cart() {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    
    $added = WC()->cart->add_to_cart($product_id, $quantity);
    
    if ($added) {
        WC_AJAX::get_refreshed_fragments();
    } else {
        echo json_encode(array('error' => true));
    }
    wp_die();
}
add_action('wp_ajax_nopriv_add_to_cart', 'ajax_add_to_cart');
add_action('wp_ajax_add_to_cart', 'ajax_add_to_cart');

// Функция AJAX для обновления количества товара в корзине
function ajax_update_cart_item() {
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        WC()->cart->set_quantity($cart_item_key, $quantity);
        WC_AJAX::get_refreshed_fragments();
    } else {
        echo json_encode(array('error' => true));
    }
    wp_die();
}
add_action('wp_ajax_nopriv_update_cart_item', 'ajax_update_cart_item');
add_action('wp_ajax_update_cart_item', 'ajax_update_cart_item');


// Изменение текста "Разом" на "Всього" в мини-корзине
add_filter('gettext', 'change_cart_total_text', 20, 3);
function change_cart_total_text($translated_text, $text, $domain) {
    if ($domain === 'woocommerce' && $text === 'Subtotal:') {
        $translated_text = 'Всього:';
    }
    return $translated_text;
}

// Функция для получения числа товаров в корзине
function get_cart_count() {
    $cart_count = WC()->cart->get_cart_contents_count();
    wp_send_json_success(array('cart_count' => $cart_count));
}
add_action('wp_ajax_get_cart_count', 'get_cart_count');
add_action('wp_ajax_nopriv_get_cart_count', 'get_cart_count');



// Функция добавления товара в корзину
function custom_woocommerce_cart_contents() {
    ob_start();
    woocommerce_mini_cart();
    return ob_get_clean();
}

// Регистрация шорткода
add_shortcode('popup_cart', 'custom_woocommerce_cart_contents');


// Функция AJAX для получения обновленных фрагментов корзины
function get_refreshed_fragments() {
    error_log('Функция get_refreshed_fragments вызвана');
    if ( ! isset( WC()->cart ) ) {
        error_log('Корзина не инициализирована');
        return;
    }
    ob_start();
    if (WC()->cart->is_empty()) {
        // Используем новый шаблон для пустой корзины
        wc_get_template('empty-cart-template.php');
    } else {
        woocommerce_mini_cart();
    }
    $mini_cart = ob_get_clean();
    $data = array(
        'fragments' => apply_filters(
            'woocommerce_add_to_cart_fragments',
            array(
                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
            )
        ),
        'cart_hash' => WC()->cart->get_cart_hash(),
    );
    error_log('Фрагменты корзины: ' . print_r($data, true));
    wp_send_json($data);
}

add_action('wp_ajax_get_refreshed_fragments', 'get_refreshed_fragments');
add_action('wp_ajax_nopriv_get_refreshed_fragments', 'get_refreshed_fragments');

function enqueue_woocommerce_scripts() {
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_script( 'wc-cart-fragments' );  
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_woocommerce_scripts' );







function track_first_added_product( $cart_item_key, $product_id, $quantity ) {
    if ( WC()->cart->get_cart_contents_count() == $quantity ) {
        // Сохраняем ID первого добавленного товара в сессию
        WC()->session->set( 'first_added_product_id', $product_id );
    }
}
add_action( 'woocommerce_add_to_cart', 'track_first_added_product', 10, 3 );


function first_added_upsell_products_shortcode() {
    // $first_product_id = WC()->session->get( 'first_added_product_id' );
    $cart = WC()->cart->get_cart();
    
    $first_cart_item = reset( $cart );
    $first_product_id = $first_cart_item['product_id'];

    if ( ! $first_product_id ) {
        return '';
    }

    $product = wc_get_product( $first_product_id );
    
    if ( ! $product ) {
        return '';
    }

    ob_start();

    $upsells = $product->get_upsell_ids();

    if ( ! empty( $upsells ) ) {
        $args = array(
            'post_type'           => 'product',
            'posts_per_page'      => 4, // количество выводимых продуктов
            'post__in'            => $upsells,
            'orderby'             => 'post__in',
        );

        $upsell_products = new WP_Query( $args );

        if ( $upsell_products->have_posts() ) : ?>
                <div class="cart-slider">
                    <h2>З цим товаром також купують</h2>
                    <article>
                        <div class="cart-slider__gallary">
                            <div class="relate-slider__wrapper swiper-wrapper">
                                
                                <?php while ( $upsell_products->have_posts() ) : $upsell_products->the_post(); 
                                
                                    $current_product = wc_get_product( get_the_ID() ); ?>
                                    <section class="swiper-slide">

                                            <div class="cart-slider__image">
                                                <?php the_post_thumbnail('full'); ?>
                                                <a class="heart-icon wishlist-icon <?php echo wooeshop_in_wishlist2( $current_product->get_id() ) ? 'in-wishlist' : '' ?>" data-id="<?php echo $current_product->get_id(); ?>">
                                                
                                                    <svg class="heart-icon"><use xlink:href="#heart-icon"></use></svg>
                                                    
                                                
                                                </a>
                                            </div>

                                            <div class="cart-slider__footer-card">
                                                <div class="cart-slider__rating">
                                                    <div class="cart-slider__stars">
                                                        <?php 
                                                            $average_rating = get_custom_product_average_rating($current_product->get_id()); 
                                                            $ratings_count = get_ratings_count($current_product->get_id()); 
                                                        ?>
                                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                            <svg class="star" width="36" height="33" viewBox="0 0 36 33" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill="<?php echo ($i <= $average_rating) ? '#F4A804' : 'grey'; ?>"  d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z"/>
                                                            </svg>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span><?php echo number_format($average_rating, 1); ?></span>
                                                </div>
                                                
                                                <div class="cart-slider__mid">
                                                    <a href="##" class="cart-slider__title"><?php the_title(); ?></a>

                                                    <a href="<?php echo esc_url( $current_product->add_to_cart_url() ); ?>" class="add_to_cart_button ajax_add_to_cart bag" data-product_id="<?php echo esc_attr( $current_product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $current_product->get_sku() ); ?>" aria-label="<?php echo esc_attr( $current_product->add_to_cart_text() ); ?>" rel="nofollow">
                                            
                                                    <svg class="bag"><use xlink:href="#bag"></use></svg>
                                                    </a>
                                                </div>

                                                <div class="cart-slider__prices">
                                                <?php if ( $current_product->is_type( 'variable' ) ) {
                                                    // Get the available variations
                                                    $available_variations = $current_product->get_available_variations();
                                                    $variation_prices = array();

                                                    foreach ( $available_variations as $variation ) {
                                                        $variation_obj = new WC_Product_Variation( $variation['variation_id'] );
                                                        $variation_prices[] = $variation_obj->get_price();
                                                    }

                                                    // Get the minimum and maximum prices from the variations
                                                    $min_price = min( $variation_prices );
                                                    $max_price = max( $variation_prices );

                                                    // Get the minimum and maximum regular prices from the variations
                                                    $variation_regular_prices = array_map( function( $variation ) {
                                                        $variation_obj = new WC_Product_Variation( $variation['variation_id'] );
                                                        return $variation_obj->get_regular_price();
                                                    }, $available_variations );

                                                    $min_regular_price = min( $variation_regular_prices );
                                                    $max_regular_price = max( $variation_regular_prices );

                                                    if ( $min_price !== $min_regular_price ) {
                                                        // Show sale price range
                                                        ?>
                                                        <div class="current-price"><?php echo wc_price( $min_price ); ?></div>
                                                        <div class="old-price active"><?php echo wc_price( $min_regular_price ); ?> </div>
                                                        <?php
                                                    } else {
                                                        // Show regular price range
                                                        ?>
                                                        <div class="current-price"><?php echo wc_price( $min_price ); ?> - <?php echo wc_price( $max_price ); ?></div>
                                                        <?php
                                                    }
                                                } else {
                                                    // For simple products
                                                    if ( $current_product->get_sale_price() ) {
                                                        ?>
                                                        <div class="current-price"><?php echo wc_price( $current_product->get_sale_price() ); ?></div>
                                                        <div class="old-price active"><?php echo wc_price( $current_product->get_regular_price() ); ?></div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class="current-price"><?php echo wc_price( $current_product->get_price() ); ?></div>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                                </div>
                                                
                                                <a href="##" class="btn-black">В кошик
                                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4.62877 7.72073V4.17384C4.62877 2.21495 6.14744 0.626953 8.02082 0.626953C9.8942 0.626953 11.4129 2.21495 11.4129 4.17384V7.72073M3.10194 15.7012H12.9397C13.9399 15.7012 14.7229 14.8008 14.6281 13.7596L13.982 6.66587C13.8991 5.75567 13.168 5.06056 12.2936 5.06056H3.74805C2.87365 5.06056 2.14256 5.75568 2.05966 6.66587L1.41355 13.7596C1.31872 14.8008 2.10172 15.7012 3.10194 15.7012Z" stroke="white" stroke-width="1.16736" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>
                                                

                                            </div>
                                    </section>
                                <?php endwhile; ?>
                               
                            </div>
                        </div>
                        <div class="cart-slider__buttons">
                            <div class="cart-btn-prev btn-swiper">
                            <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="0.5" y="0.5" width="34" height="34" rx="2.5" stroke="#242424"/>
                                <path d="M20.1927 11.668L14.3594 17.5013L20.1927 23.3346" stroke="#242424" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <svg width="55" height="55" viewBox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="0.5" y="0.5" width="54" height="54" rx="4.5" stroke="#242424"/>
                                <path d="M31 18L22 27.5L31 37" stroke="#242424" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>                    
                            </div>
                            <div class="cart-btn-next btn-swiper">
                            <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="0.5" y="0.5" width="34" height="34" rx="2.5" stroke="#242424"/>
                                <path d="M14.3581 23.332L20.1914 17.4987L14.3581 11.6654" stroke="#242424" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <svg width="55" height="55" viewBox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="0.5" y="0.5" width="54" height="54" rx="4.5" stroke="#242424"/>
                                <path d="M24 37L33 27.5L24 18" stroke="#242424" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>                    
                            </div>
                        </div>
                    </article>
                </div>
            
            <?php
            wp_reset_postdata();
        endif;
    }

    return ob_get_clean();
}
add_shortcode( 'first_added_upsell_products', 'first_added_upsell_products_shortcode' );

// Checkout Page

// Настройка полей контактной информации
function custom_override_checkout_fields( $fields ) {
    unset( $fields[ 'billing' ][ 'billing_company' ] ); // компания
    unset( $fields[ 'billing' ][ 'billing_country' ] ); // страна
    unset( $fields[ 'billing' ][ 'billing_address_1' ] ); // адрес 1
    unset( $fields[ 'billing' ][ 'billing_address_2' ] ); // адрес 2
    unset( $fields[ 'billing' ][ 'billing_city' ] ); // город
    unset( $fields[ 'billing' ][ 'billing_state' ] ); // регион, штат
    unset( $fields[ 'billing' ][ 'billing_postcode' ] ); // почтовый индекс
//     unset( $fields[ 'billing' ][ 'billing_phone' ] ); // телефон

    unset( $fields[ 'shipping' ][ 'shipping_first_name' ] ); // фамилия
    unset( $fields[ 'shipping' ][ 'shipping_last_name' ] ); // фамилия
    unset( $fields[ 'shipping' ][ 'shipping_company' ] ); // компания
    unset( $fields[ 'shipping' ][ 'shipping_country' ] ); // страна
    unset( $fields[ 'shipping' ][ 'shipping_address_1' ] ); // адрес 1
    unset( $fields[ 'shipping' ][ 'shipping_address_2' ] ); // адрес 2
    unset( $fields[ 'shipping' ][ 'shipping_phone' ] ); // телефон
    unset( $fields[ 'shipping' ][ 'shipping_city' ] ); // город
    unset( $fields[ 'shipping' ][ 'shipping_state' ] ); // регион, штат
    unset( $fields[ 'shipping' ][ 'shipping_postcode' ] ); // почтовый индекс

    unset( $fields['order']['order_comments'] ); // Примечание к заказу


    // Настраиваем порядок и отображение полей
    $fields['billing']['billing_first_name'] = array(
        'label'     => __('Ім\'я', 'woocommerce'),
        'placeholder'   => _x('Ім\'я', 'placeholder', 'woocommerce'),
        'required'  => true,
        'class'     => array('form-row-first'),
        'clear'     => true
    );

    $fields['billing']['billing_last_name'] = array(
        'label'     => __('Прізвище', 'woocommerce'),
        'placeholder'   => _x('Прізвище', 'placeholder', 'woocommerce'),
        'required'  => true,
        'class'     => array('form-row-last'),
        'clear'     => true
    );

    $fields['billing']['billing_phone'] = array(
        'label'     => __('Номер телефону', 'woocommerce'),
        'placeholder'   => _x('+38 (___) ___ ____*', 'placeholder', 'woocommerce'),
        'required'  => true,
        'class'     => array('form-row-first'),
        'clear'     => true
    );

    $fields['billing']['billing_email'] = array(
        'label'     => __('E-mail', 'woocommerce'),
        'placeholder'   => _x('E-mail', 'placeholder', 'woocommerce'),
        'required'  => true,
        'class'     => array('form-row-last'),
        'clear'     => true
    );

    return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );



/**
 * Moving the payments
 */
add_action( 'woocommerce_checkout_shipping', 'my_custom_display_payments', 20 );

/**
 * Displaying the Payment Gateways 
 */
function my_custom_display_payments() {
  if ( WC()->cart->needs_payment() ) {
    $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
    WC()->payment_gateways()->set_current_gateway( $available_gateways );
  } else {
    $available_gateways = array();
  }
  ?>
    <div class="registration-form__block">
        <h4 class="registration-form__heading">Оплата</h4>
        <div class="registration-form__details registration-form__details--payment">
            <div class="registration-form__group registration-form__group--payment">
                <?php if ( WC()->cart->needs_payment() ) : ?>
                <ul class="wc_payment_methods payment_methods methods">
                    <?php
                    if ( ! empty( $available_gateways ) ) {
                    foreach ( $available_gateways as $gateway ) {
                        wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                    }
                    } else {
                    echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
                    }
                    ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
}

/**
 * Adding the payment fragment to the WC order review AJAX response
 */
add_filter( 'woocommerce_update_order_review_fragments', 'my_custom_payment_fragment' );

/**
 * Adding our payment gateways to the fragment #checkout_payments so that this HTML is replaced with the updated one.
 */
function my_custom_payment_fragment( $fragments ) {
	ob_start();

	my_custom_display_payments();

	$html = ob_get_clean();

	$fragments['#checkout_payments'] = $html;

	return $fragments;
}


add_filter('woocommerce_enable_order_notes_field', '__return_false');

// Убираем заголовок в блоке доставки
add_filter( 'woocommerce_shipping_package_name', 'custom_woocommerce_shipping_package_name', 10, 3 );

function custom_woocommerce_shipping_package_name( $package_name, $i, $package ) {
    return ''; // Возвращаем пустое значение для удаления заголовка
}

// Quick View
function quick_view_shortcode($atts) {
    ob_start();

    // Получаем ID продукта
    $atts = shortcode_atts(array('product_id' => ''), $atts, 'quick-view');
    $product_id = intval($atts['product_id']);

    $product = wc_get_product($product_id);

    //echo '<div class="quick-view__content area">';
    if ($product) {
        // Галерея изображений
        $attachment_ids = $product->get_gallery_image_ids();
        $thumbnail_url = wp_get_attachment_url(get_post_thumbnail_id($product_id));


        if ($attachment_ids) {
        
            echo '<div class="quick-view__gallery">';

                echo '<div class="gallery-view-mini">';
                    echo '<div class="swiper-wrapper">';

                    foreach ($attachment_ids as $attachment_id) {
                        echo '<div class="gallery-view-mini__image swiper-slide">'. wp_get_attachment_image($attachment_id, 'full', true, array('class' => 'thumbnail-image', 'data-full' => wp_get_attachment_url($attachment_id))).'</div>';
                    }

                    echo '</div>';
                echo '</div>';

                echo '<div thumbsSlider="" class="gallery-view">';
                    echo '<div class="swiper-wrapper">';
                        foreach ($attachment_ids as $attachment_id) {
                            echo '<div class="gallery-view__image swiper-slide">' .wp_get_attachment_image($attachment_id, 'full', true, array('class' => 'thumbnail-image', 'data-full' => wp_get_attachment_url($attachment_id))). '</div>';
                        }
                    echo '</div>';
                    echo '<div class="gallery-view__slider-buttons">
                        <div class="gallery-view-btn-prev btn-swiper">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0.5" y="0.5" width="39" height="39" rx="4.5" stroke="#242424"/>
                            <path d="M21.2422 24L16.9995 19.7573L21.2422 15.5146" stroke="#242424" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        </div>
                        <div class="gallery-view-btn-next btn-swiper">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0.5" y="0.5" width="39" height="39" rx="4.5" stroke="#242424"/>
                            <path d="M19 16L23.2427 20.2427L19 24.4854" stroke="#242424" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        </div>
                    </div>';
                echo '</div>';

            echo '</div>';

        } else {

            echo '<div class="quick-view__picture"><img src="'. $thumbnail_url.'" alt="" /></div>';
        }


        // SKU, заголовок, цвета, описание, цена и кнопки
        echo '<div class="quick-view__body">';

            echo '<div class="quick-view__header">';
                echo '<span class="sku">' . __('Код: ', 'woocommerce') . $product->get_sku() . '</span>';
                echo '<h2>' . $product->get_name() . '</h2>';
            echo '</div>';
        

        // Вывод атрибута "цвет"
        $colors = wc_get_product_terms( $product_id, 'pa_kolory-v-naiavnosti', array( 'fields' => 'all' ) );
        if(!empty($colors)) { ?>
            <div id="color-attributes" class="shortstory__colection quick-view__colors quick-view__colors">
                <h3>Колір</h3>
                <div class="quick-view__colors-block">
                    <?php foreach ( $colors as $color ) : ?>
                        <a href="##" class="shortstory__card" data-attribute-slug="<?php echo esc_attr( $color->slug ); ?>">
                            <div class="shortstory__image">
                                <?php 
                                $thumbnail_id = get_term_meta( $color->term_id, 'attribute_color_image_id', true );
                                if ($thumbnail_id) {
                                    echo wp_get_attachment_image( $thumbnail_id, 'full' );
                                } else {
                                    echo '<img src="' . get_stylesheet_directory_uri() . '/img/default-attribute.jpg" alt="default image" />';
                                }
                                ?>
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11" cy="11" r="11" fill="#E93A53" />
                                    <path d="M17 7L9.43756 15L6 11.3637" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php }

        // Полное описание
        $full_description = apply_filters('the_content', get_post_field('post_content', $product_id));

        echo '<div class="short-description">';

            echo '<div class="short-description__body">';

                echo  wp_kses_post($full_description);

            echo '</div>';

            echo '<a href="##" class="read-more-button">Читати більше...</a>';
            
        
        echo '</div>';


        echo '<div class="quick-view__prices">' . $product->get_price_html() . '</div>';

            echo '<div class="quick-view__footer">';
                echo '<a href="#" class="btn-black button add-to-cart-button" data-product_id="' . $product_id . '">
                    <span>Додати в кошик</span>
                    <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>';
                echo '</a>';

                echo '<div class="order-tag">
                    <svg width="31" height="20" viewBox="0 0 31 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.71429 1H19C19.5523 1 20 1.44772 20 2V2.69643M27.4643 8.46429H21C20.4477 8.46429 20 8.01657 20 7.46428V2.69643M27.4643 8.46429L28.9289 10.2219C29.0787 10.4016 29.1607 10.6281 29.1607 10.862V15.6071C29.1607 16.1594 28.713 16.6071 28.1607 16.6071H26.1071M27.4643 8.46429L24.6924 3.22854C24.5191 2.90118 24.179 2.69643 23.8086 2.69643H20M19.6607 16.6071H13.8929M7.44643 16.6071H3.71429M8.125 12.1964H2.69643M10.5 8.46429H1M11.8571 4.73214H2.69643" stroke="#E93A53" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="10.5003" cy="16.6077" r="2.39286" stroke="#E93A53" stroke-width="2"/>
                    <circle cx="22.7132" cy="16.6077" r="2.39286" stroke="#E93A53" stroke-width="2"/>
                    </svg>                  
                    <span>Під замовлення</span>';
                echo '</div>';

            echo '</div>';

        echo '</div>';
    }
    


    echo '<div id="closeQuickView" class="close-popup">
        <svg viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M32 1L1 32M1 1L32 32" stroke="#E93A53" stroke-width="2"></path>
        </svg>
    </div>';
    //echo '</div>';

    return ob_get_clean();
}
add_shortcode('quick-view', 'quick_view_shortcode');



function get_quick_view_content() {
    $product_id = intval($_POST['product_id']);

    $output = do_shortcode('[quick-view product_id="' . $product_id . '"]');
    
    echo $output;
    wp_die();
}
add_action('wp_ajax_get_quick_view_content', 'get_quick_view_content');
add_action('wp_ajax_nopriv_get_quick_view_content', 'get_quick_view_content');

add_filter('wpcf7_autop_or_not', '__return_false');



// Wishlist
add_action( 'wp_ajax_wooeshop_wishlist_action', 'wooeshop_wishlist_action_cb' );
add_action( 'wp_ajax_nopriv_wooeshop_wishlist_action', 'wooeshop_wishlist_action_cb' );

function wooeshop_wishlist_action_cb() {

	if ( ! isset( $_POST['nonce'] ) ) {
		echo json_encode( [ 'status' => 'error', 'answer' => __( 'Security error 1', 'wooeshop' ) ] );
		wp_die();
	}

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wooeshop_wishlist_nonce' ) ) {
		echo json_encode( [ 'status' => 'error', 'answer' => __( 'Security error 2', 'wooeshop' ) ] );
		wp_die();
	}

	$product_id = (int) $_POST['product_id'];
	$product = wc_get_product( $product_id );

	if ( ! $product || $product->get_status() != 'publish' ) {
		echo json_encode( [ 'status' => 'error', 'answer' => __( 'Error product', 'wooeshop' ) ] );
		wp_die();
	}

	$wishlist = wooeshop_get_wishlist();

	if ( false !== ( $key = array_search( $product_id, $wishlist ) ) ) {
		unset( $wishlist[$key] );
		$answer = json_encode( [ 'status' => 'success', 'answer' => __( 'The product hase been removed from wishlist', 'wooeshop' ) ] );
	} else {
		if ( count( $wishlist ) >= 8 ) {
			array_shift( $wishlist );
		}
		$wishlist[] = $product_id;
		$answer = json_encode( [ 'status' => 'success', 'answer' => __( 'The product hase been added to wishlist', 'wooeshop' ) ] );
	}
	$wishlist = implode( ',', $wishlist );
	setcookie( 'wooeshop_wishlist', $wishlist, time() + 3600 * 24 * 30, '/' );

	wp_die( $answer );
}

function wooeshop_in_wishlist( $product_id ) {
	$wishlist = wooeshop_get_wishlist();
	return false !== array_search( $product_id, $wishlist );
}


function wooeshop_in_wishlist2( $product_id ) {
	$wishlist = wooeshop_get_wishlist2();
	return false !== array_search( $product_id, $wishlist );
}

function wooeshop_get_wishlist2() {
	$wishlist = isset( $_COOKIE['wishlist'] ) ? $_COOKIE['wishlist'] : [];
	//$wishlist = $_COOKIE['wishlist'] ?? [];
	if ( $wishlist ) {
		$wishlist = json_decode($wishlist);
	}
	return $wishlist;
}

// Регистрация AJAX-действий для получения количества товаров в избранном
add_action( 'wp_ajax_get_wishlist_count', 'get_wishlist_count' );
add_action( 'wp_ajax_nopriv_get_wishlist_count', 'get_wishlist_count' );

function get_wishlist_count() {
    // Проверка nonce для безопасности
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wooeshop_wishlist_nonce' ) ) {
        wp_send_json_error( [ 'message' => 'Security error' ] );
        wp_die();
    }

    // Получаем список товаров в избранном из cookie
    $wishlist = wooeshop_get_wishlist();
    $count = is_array( $wishlist ) ? count( $wishlist ) : 0;

    // Возвращаем количество товаров
    wp_send_json_success( [ 'count' => $count ] );
}

// Функция для получения избранного из cookie
function wooeshop_get_wishlist() {
    $wishlist = isset( $_COOKIE['wishlist'] ) ? $_COOKIE['wishlist'] : [];
    if ( $wishlist ) {
        $wishlist = json_decode( stripslashes( $wishlist ), true );
    }
    return $wishlist;
}



add_action( 'wp_ajax_load_wishlist_products', 'load_wishlist_products_cb' );
add_action( 'wp_ajax_nopriv_load_wishlist_products', 'load_wishlist_products_cb' );

function load_wishlist_products_cb() {
    $product_ids = isset( $_POST['product_ids'] ) ? array_map( 'intval', (array) $_POST['product_ids'] ) : array();
    $product_ids = array_filter( $product_ids );

    if ( empty( $product_ids ) ) {
        wp_send_json_success( array( 'html' => '', 'count' => 0 ) );
    }

    $args = array(
        'post_type'      => 'product',
        'post__in'       => $product_ids,
        'posts_per_page' => -1,
        'orderby'        => 'post__in',
    );

    $query = new WP_Query( $args );

    ob_start();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            global $product;
            if ( ! $product || ! $product->is_visible() ) continue;

            $product_id = $product->get_id();
            $classAv = $product->is_in_stock() ? 'tag-available' : 'tag-order';
            $textAv  = $product->is_in_stock() ? 'В наявності' : 'Під замовлення';
            ?>
            <section id="product-<?php echo $product_id; ?>" <?php wc_product_class( 'catalog-card', $product ); ?>>
                <a href="<?php the_permalink(); ?>" class="catalog-card__image">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail(); ?>
                    <?php else : ?>
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/catalog-card.png" alt="<?php the_title(); ?>" />
                    <?php endif; ?>
                </a>
                <div class="catalog-card__tag tag-order <?php echo esc_attr($classAv); ?>">
                    <span><?php echo esc_html($textAv); ?></span>
                </div>
                <div class="catalog-card__content">
                    <div class="catalog-card__icons">
                        <div class="catalog-card__tag tag-order <?php echo esc_attr($classAv); ?>">
                            <span><?php echo esc_html($textAv); ?></span>
                        </div>
                        <div class="catalog-card__icon-right">
                            <div class="catalog-card__icon-group">
                                <img src="/wp-content/uploads/2025/07/Lapka-ta-vidsotok-e1751405070826.webp" alt="percentage">
                            </div>
                            <?php if ( has_term( 'Забронювати до пологів', 'product_tag', $product_id ) ) : ?>
                                <div class="catalog-card__icon-single">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/pregnant-woman.png" alt="reserve before childbirth">
                                </div>
                            <?php endif; ?>
                        </div>
                        <button class="remove-wishlist" data-product-id="<?php echo $product_id; ?>">
                            <svg class="delete-icon"><use xlink:href="#delete"></use></svg>
                            <span>Видалити</span>
                        </button>
                    </div>
                    <div class="catalog-card__footer">
                        <?php if ( $product->is_in_stock() ) : ?>
                            <div class="catalog-card__tag">
                                <svg width="15" height="11" viewBox="0 0 15 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 1L5.06257 10L1 5.90911" stroke="#5EB04C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>В наявності</span>
                            </div>
                        <?php endif; ?>
                        <div class="catalog-card__mid">
                            <a href="<?php the_permalink(); ?>">
                                <h3 class="catalog-card__title"><?php the_title(); ?></h3>
                            </a>
                            <a href="#" class="catalog-card__bag add-to-cart-button" data-product_id="<?php echo $product_id; ?>">
                                <svg class="bag"><use xlink:href="#bag"></use></svg>
                            </a>
                        </div>
                        <div class="catalog-card__prices">
                        <?php
                        if ( $product->is_type( 'variable' ) ) {
                            $available_variations = $product->get_available_variations();
                            $variation_prices = array();
                            foreach ( $available_variations as $variation ) {
                                $vo = new WC_Product_Variation( $variation['variation_id'] );
                                $p = $vo->get_price();
                                if ( $p !== '' && $p !== null ) $variation_prices[] = $p;
                            }
                            if ( ! empty( $variation_prices ) ) {
                                $min_price = min( $variation_prices );
                                $max_price = max( $variation_prices );
                                $variation_regular_prices = array_filter( array_map( function( $v ) {
                                    $vo = new WC_Product_Variation( $v['variation_id'] );
                                    $rp = $vo->get_regular_price();
                                    return ( $rp !== '' && $rp !== null ) ? $rp : null;
                                }, $available_variations ) );
                                if ( ! empty( $variation_regular_prices ) ) {
                                    $min_regular_price = min( $variation_regular_prices );
                                    if ( $min_price != $min_regular_price ) {
                                        echo '<div class="catalog-card__current-pirce">' . wc_price( $min_price ) . '</div>';
                                        echo '<div class="catalog-card__old-pirce">' . wc_price( $min_regular_price ) . '</div>';
                                    } else {
                                        echo '<div class="catalog-card__current-pirce">' . wc_price( $min_price ) . ( $min_price != $max_price ? ' - ' . wc_price( $max_price ) : '' ) . '</div>';
                                    }
                                } else {
                                    echo '<div class="catalog-card__current-pirce">' . wc_price( $min_price ) . '</div>';
                                }
                            }
                        } else {
                            if ( $product->get_sale_price() !== '' && $product->get_sale_price() !== null ) {
                                echo '<div class="catalog-card__current-pirce">' . wc_price( $product->get_sale_price() ) . '</div>';
                                echo '<div class="catalog-card__old-pirce">' . wc_price( $product->get_regular_price() ) . '</div>';
                            } elseif ( $product->get_price() !== '' && $product->get_price() !== null ) {
                                echo '<div class="catalog-card__current-pirce">' . wc_price( $product->get_price() ) . '</div>';
                            }
                        }
                        ?>
                        </div>
                        <div class="catalog-card__buttons">
                            <?php if ( $product->is_in_stock() ) : ?>
                                <?php if ( $product->is_type( 'variable' ) ) : ?>
                                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="btn-black"><?php echo esc_html( $product->add_to_cart_text() ); ?>
                                        <svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="btn-black add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="<?php echo esc_attr( $product->add_to_cart_text() ); ?>" rel="nofollow">
                                        <span>Додати в кошик</span>
                                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <a href="<?php the_permalink(); ?>" class="btn-white">Детальніше</a>
                        </div>
                    </div>
                </div>
            </section>
            <?php
        }
        wp_reset_postdata();
    }
    $html = ob_get_clean();

    wp_send_json_success( array( 'html' => $html, 'count' => $query->found_posts ) );
}

// Делаем редирект со страницы корзины
add_action( 'template_redirect', 'redirect_cart_to_home' );

function redirect_cart_to_home() {
    if ( is_cart() ) {
        wp_redirect( home_url() );
        exit;
    }
}


// Логика для зарегистрированных пользователей на странице букинга
add_action('wp_ajax_save_cart_to_bookings', 'save_cart_to_bookings');
add_action('wp_ajax_nopriv_save_cart_to_bookings', 'save_cart_to_bookings');

function save_cart_to_bookings() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'review_nonce')) {
        wp_send_json_error('Invalid nonce');
        return;
    }

    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
        return;
    }

    global $wpdb;
    $user_id = get_current_user_id();
    $cart_items = isset($_POST['cart_items']) ? $_POST['cart_items'] : [];

    // Логируем данные корзины для отладки
    error_log('Переданные данные корзины: ' . print_r($cart_items, true));

    if (!empty($cart_items)) {
        foreach ($cart_items as $item) {
            // Пропускаем, если нет названия или цены
            if (empty($item['title']) || empty($item['price'])) {
                continue;
            }

            $product = wc_get_product(intval($item['id']));
            $image_url = wp_get_attachment_image_url($product->get_image_id(), 'full');
            
            // Очищаем цену от всех символов, кроме цифр
            $raw_price = sanitize_text_field($item['price']);
            $price = preg_replace('/[^\d]/', '', $raw_price); // Убираем все, кроме цифр

            // Логируем данные о товаре и цене перед вставкой
            error_log('ID продукта: ' . intval($item['id']));
            error_log('Название продукта: ' . sanitize_text_field($item['title']));
            error_log('Переданная цена: ' . $raw_price);
            error_log('Цена для сохранения: ' . $price);

            // Вставляем данные в таблицу
            $wpdb->insert(
                'wp_user_bookings',
                [
                    'user_id' => $user_id,
                    'product_id' => intval($item['id']),
                    'product_name' => sanitize_text_field($item['title']),
                    'quantity' => 1,
                    'price' => $price, // Сохраняем очищенную цену без форматирования
                    'image_url' => $image_url ? $image_url : '',
                    'booking_date' => current_time('mysql'),
                    'expiration_date' => date('Y-m-d H:i:s', strtotime('+2 months')),
                    'reservation_price' => $price, // Сохраняем цену бронирования в числовом формате
                ]
            );

            // Логируем, если есть ошибка при вставке в базу
            if ($wpdb->last_error) {
                error_log('Ошибка базы данных: ' . $wpdb->last_error);
            }
        }
    }

    wp_send_json_success('Booking saved');
}





//Вывод товаров на странице /thank-you/
function display_user_bookings() {
    if (!is_user_logged_in()) {
        echo 'Вы не авторизованы.';
        return;
    }

    global $wpdb;
    $user_id = get_current_user_id();
    $user_info = get_userdata($user_id);
    $user_name = $user_info->user_login;
    $booking_number = $user_name . '-BOOKING-' . rand(100000, 999999); // Формируем номер бронирования

    $bookings = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM wp_user_bookings WHERE user_id = %d AND expiration_date > NOW()",
            $user_id
        )
    );

    if ($bookings) {
        $total_price = 0; // Переменная для хранения общей суммы

        // Выводим блок с деталями заказа
        ?>
        <section class="order-details">
            <div class="container">
                <div class="order-details__body">
                    <h4 class="order-details__title">Деталі замовлення</h4>
                    <div class="order-details__top">
                        <div class="order-details__option">
                            <span class="order-details__label">Номер бронювання:</span>
                            <span class="order-details__value" id="booking-number"><?php echo $booking_number; ?></span>
                        </div>
                        <div class="order-details__option">
                            <span class="order-details__label">Дата:</span>
                            <span class="order-details__value"><?php echo date('d.m.Y'); ?></span>
                        </div>
                    </div>
                    <div class="order-details__bottom">
                        <div class="order-details__option">
                            <span class="order-details__label">Сума:</span>
                            <span class="order-details__value" id="order-total">
                                <?php
                                foreach ($bookings as $booking) {
                                    // Суммируем цены всех товаров
                                    $total_price += floatval($booking->price);
                                }
                                // Форматируем цену с разделением тысяч пробелами
                                echo $total_price;
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="product">
            <div class="container">
                <div class="product__list" id="product-list">
                    <h4 class="order-details__title product__title">Товар</h4>

                        <?php
                        // Далее выводим сами товары
                        foreach ($bookings as $booking) {
                            if (!empty($booking->product_name)) {
                                ?>
                                <div class="product__item">
                                    <div class="product__image">
                                        <img src="<?php echo esc_url($booking->image_url); ?>" alt="<?php echo esc_attr($booking->product_name); ?>">
                                    </div>
                                    <div class="product__info">
                                        <div class="product__details">
                                            <p class="product__name">
                                                <?php echo esc_html($booking->product_name); ?>
                                            </p>
                                            <p class="product__price">
    <?php
        // Преобразуем цену в нужный формат
        $formatted_price = number_format((float)$booking->price, 0, '', ' ');
        echo esc_html($formatted_price . ' ₴');
    ?>
</p>
                                        </div>
                                        <div class="product__quantity">
                                            <span class="product__label">К-сть штук:</span>
                                            <span class="product__value">
                                                <?php echo esc_html($booking->quantity); ?> шт
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } ?>

                </div>
            </div>
        </section>
    <?php } 
}

add_shortcode('user_bookings', 'display_user_bookings');



// Добавление вариативного товара в корзину через аякс

add_action('wp_ajax_woocommerce_add_to_cart_variable_rc', 'woocommerce_add_to_cart_variable_rc');
add_action('wp_ajax_nopriv_woocommerce_add_to_cart_variable_rc', 'woocommerce_add_to_cart_variable_rc');

function woocommerce_add_to_cart_variable_rc() {
    // Проверяем, переданы ли необходимые данные
    if ( ! isset( $_POST['product_id'], $_POST['variation_id'], $_POST['quantity'], $_POST['variation'] ) ) {
        wp_send_json_error( array( 'error' => 'Недостаточно данных для добавления товара в корзину.' ) );
        return;
    }

    $product_id    = absint( $_POST['product_id'] );
    $variation_id  = absint( $_POST['variation_id'] );
    $quantity      = absint( $_POST['quantity'] );
    $variation     = wc_clean( wp_unslash( $_POST['variation'] ) );

    // Получаем объект продукта по ID
    $product = wc_get_product( $product_id );

    // Проверяем, является ли товар вариативным
    if ( ! $product || 'variable' !== $product->get_type() ) {
        wp_send_json_error( array( 'error' => 'Неправильный тип товара.' ) );
        return;
    }

    // Добавляем товар в корзину
    $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );

    // Если не удалось добавить товар в корзину, возвращаем ошибку
    if ( ! $cart_item_key ) {
        wp_send_json_error( array( 'error' => 'Не удалось добавить товар в корзину.' ) );
        return;
    }

    // Возвращаем обновленные фрагменты корзины и информацию о количестве товаров
    WC_AJAX::get_refreshed_fragments();
}



add_action('template_redirect', 'disable_review_page_redirect');

function disable_review_page_redirect() {
    if (is_singular('product_reviews')) {  // Проверка, если текущая страница - это отзыв
        wp_redirect(home_url());  // Редирект на главную страницу
        exit;
    }
}
function add_quick_buy_form_after_footer() {
    if (is_product()) {
        global $product;
        
        // Проверяем, является ли $product действительным объектом WC_Product
        if (!$product || !is_a($product, 'WC_Product')) {
            $product = wc_get_product(get_the_ID());
        }
        
        // Если все еще нет действительного объекта продукта, выходим
        if (!$product) {
            return;
        }
		
		// Отключил!
		return;

        ?>
        <div class="form-lightbox-wrapper buy-one-click">
            <div class="lightbox-background"></div>
            <div class="lightbox-section">
                <div class="lightbox-content">
                    <div class="close-lightbox"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/close.svg" loading="lazy" width="14" height="14" alt="Закрити"></div>
                    <div class="form-head">
                        <div class="h2">Залиште заявку</div>
                    </div>
                    <div class="form-description">Залиште свої контактні дані і ми зв'яжемося <br>з вами найближчим часом</div>
                    <form class="form-fields call-back-form" action="<?php echo get_stylesheet_directory_uri(); ?>/php/call-back-form.php" method="post">
                        <div class="form-data-group">
                            <input type="hidden" name="form_type" value="quick_buy">
                            <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
                            <input type="hidden" name="product_name" value="<?php echo esc_attr($product->get_name()); ?>">
                            <input type="hidden" name="product_sku" value="<?php echo esc_attr($product->get_sku()); ?>">
                            <input type="hidden" name="product_url" value="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                            <input class="form-input" type="text" name="name" placeholder="Ім'я*" required>
                            <input class="form-input" type="tel" name="phone" placeholder="+38(0__)___-__-__" required>
                        </div>
                        <button type="submit" name="submit_one_click_order" class="btn btn-primary"><span>Залишити заявку</span></button>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('wp_footer', 'add_quick_buy_form_after_footer', 100);




add_action('admin_post_notify_about_availability', 'handle_notify_form');
add_action('admin_post_nopriv_notify_about_availability', 'handle_notify_form');

function handle_notify_form() {

    $name   = sanitize_text_field($_POST['name'] ?? '');
    $email  = sanitize_email($_POST['email'] ?? '');
    $phone  = sanitize_text_field($_POST['phone'] ?? '');
    
    $product_title = sanitize_text_field($_POST['product_title'] ?? '');
    $product_url   = esc_url_raw($_POST['product_url'] ?? '');
    $product_sku    = sanitize_text_field($_POST['product_sku'] ?? '');

    if (!$name || !$email || !$phone) {
        wp_die("Потрібно заповнити всі поля.");
    }

    $api_key = 'NTU1ZjI3MTY4YzI2NzNhNGQ3NjMwM2NlYTRjMjRkZTZkN2RmNzZiMA';

    $lead_data = [
        "title"         => "Повідомити про наявність: $product_title",
        "source_id"    => 9,
        "pipeline_id"  => 1,
        "manager_id"   => 3,
        "contact"     => [
            "full_name" => $name,
            "email"    => $email,
            "phone"    => $phone,
        ],
        "manager_comment"      => "Запит на повідомлення про наявність.\nТовар: $product_title\nURL: $product_url\nАртикул товару: $product_sku",
        "products" => [
            [
                "sku" => $product_sku
            ]
        ]
    ];

    $response = wp_remote_post("https://openapi.keycrm.app/v1/pipelines/cards", [
        'headers' => [
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $api_key
        ],
        'body' => wp_json_encode($lead_data),
        'timeout' => 20
    ]);

    // error_log(print_r($response, true));

    // admin duplicate mail
    // wp_mail(
    //     get_option('admin_email'),
    //     'Запит на повідомлення про наявність',
    //     "Ім’я: $name\nEmail: $email\nТелефон: $phone\nТовар: $product_title\nURL: $product_url"
    // );

    wp_redirect(add_query_arg('notify', 'success', $product_url));
    exit;
}


if (!function_exists('my_theme_render_categories')) {
    function my_theme_render_categories($location, $parent_id = 0, $level = 0) {
        $args = array(
            'taxonomy'     => 'product_cat',
            'parent'       => $parent_id,
            'hide_empty'   => false,
        );
        
        $categories = get_categories($args);

        $exclude_slugs = array('bez-katehorii', 'na-vydalennia', 'novi-pozytsii');

        usort($categories, function($a, $b) {
            $order_a = get_field('custom_category_order', 'product_cat_' . $a->term_id);
            $order_b = get_field('custom_category_order', 'product_cat_' . $b->term_id);

            $order_a = ($order_a !== '' && $order_a !== null && is_numeric($order_a)) ? intval($order_a) : 9999;
            $order_b = ($order_b !== '' && $order_b !== null && is_numeric($order_b)) ? intval($order_b) : 9999;

            if ($order_a === $order_b) {
                return strcasecmp($a->name, $b->name);
            }
            return $order_a - $order_b;
        });

        foreach ($categories as $cat) {

            if (in_array($cat->slug, $exclude_slugs, true)) {
                continue;
            }

            if ($cat->count === 0) {
                continue;
            }

            $category_id = $cat->term_id;
            $thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);
            $image_data = wp_get_attachment_image_src($thumbnail_id, 'medium');
            ?>
            <div class="<?php echo $location?> category-item level-<?php echo $level; ?>" id="category-item-<?php echo esc_attr($category_id); ?>">
                <div class="category-item__card">

                    <a href="<?php echo esc_url(get_term_link($cat->slug, 'product_cat')); ?>" class="category-item__link-main">
                        <div class="photo-item">
                            <?php if ($image_data): ?>
                                <img src="<?php echo esc_url($image_data[0]); ?>" alt="<?php echo esc_attr($cat->name); ?>" class="category-item__icon">
                            <?php else: ?>
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/default-category-photo.svg');?>" alt="<?php echo esc_attr($cat->name); ?>" class="category-item__icon category-item__icon--default">
                            <?php endif; ?>
                        </div>
                        <span class="category-item__name"><?php echo esc_html($cat->name); ?></span>
                    </a>
                </div>
            </div>
            <?php
        }
    }
}


add_action('wp_ajax_get_cart_upsells', 'get_cart_upsells_ajax_handler');
add_action('wp_ajax_nopriv_get_cart_upsells', 'get_cart_upsells_ajax_handler');

function get_cart_upsells_ajax_handler() {
    WC()->cart->get_cart_from_session();
    if ( ! class_exists('WooCommerce') || ! WC()->cart ) {
        wp_send_json_error();
    }

    $cart_products_ids    = array();
    $cart_upsell_products = array();

    foreach ( array_reverse(WC()->cart->get_cart(), true) as $cart_item ) {
        $product_id = $cart_item['product_id'];
        $cart_products_ids[] = $product_id;
        $product = wc_get_product( $product_id );
        if ( $product ) {
            $upsells = $product->get_upsell_ids();
            foreach ( $upsells as $upsell_id ) {
                $cart_upsell_products[] = (int) $upsell_id;
            }
            if(!empty($cart_upsell_products)) {
                break;
            }
        }
    }

    $cart_upsell_products = array_unique( $cart_upsell_products );
    $cart_upsell_products = array_diff( $cart_upsell_products, $cart_products_ids );
    $cart_upsell_products = array_values( $cart_upsell_products );

    if ( empty( $cart_upsell_products ) ) {
        wp_send_json_success( array('html' => '') );
    }

    ob_start();
    ?>
    <div class="card-swiper">
        <h2>Разом з цим товаром купують</h2>
        <?php
        $args = array(
            'post_type'           => 'product',
            'post__in'            => $cart_upsell_products,
            'orderby'             => 'post__in',
            'posts_per_page'      => -1,
            'ignore_sticky_posts' => 1,
            'meta_query'          => array(
                array( 'key' => '_stock_status', 'value' => 'instock' ),
            ),
        );

        $upsell_query = new WP_Query( $args );

        if ( $upsell_query->have_posts() ) : ?>
            <article class="card-list">
                <div class="product-grid">
                    <?php
                    while ( $upsell_query->have_posts() ) :
                        $upsell_query->the_post();
                        $current_product = wc_get_product( get_the_ID() );
                        ?>
                        <div class="product-grid-item">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a class="card-swiper-slider__image" href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('full', ['alt' => get_the_title() ]); ?>
                                </a>
                            <?php else : ?>
                                <a class="card-swiper-slider__image" href="<?php the_permalink(); ?>">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/card.jpg" alt="slide-photo" />
                                </a>
                            <?php endif; ?>

                            <div class="product-grid-item__content">
                                <h3 class="card-swiper-slider__title card-swiper-title-cart"><?php the_title(); ?></h3>
                                <div class="card-swiper-slider__bottom-card">
                                    <div class="card-swiper-slider__prices">
                                        <?php
                                        if ( $current_product->is_type('variable') ) {
                                            $vars = $current_product->get_available_variations();
                                            $prices = $regulars = [];
                                            foreach ( $vars as $v ) {
                                                $pv = new WC_Product_Variation( $v['variation_id'] );
                                                if ( $pv->get_price()   ) $prices[]   = $pv->get_price();
                                                if ( $pv->get_regular_price() ) $regulars[] = $pv->get_regular_price();
                                            }
                                            if ( $prices ) {
                                                $min = min($prices);
                                                $max = max($prices);
                                                $reg_min = $regulars ? min($regulars) : $min;
                                                if ( $min < $reg_min ) {
                                                    echo '<div class="card-swiper-slider__current-pirce card-swiper-price-cart">'
                                                        . wc_price($min) .'</div>';
                                                    echo '<div class="card-swiper-slider__old-pirce card-swiper-price-cart">'
                                                        . wc_price($reg_min) .'</div>';
                                                } else {
                                                    echo '<div class="card-swiper-slider__current-pirce card-swiper-price-cart">'
                                                        . wc_price($min) .' - '. wc_price($max) .'</div>';
                                                }
                                            }
                                        } else {
                                            if ( $current_product->get_sale_price() ) {
                                                echo '<div class="card-swiper-slider__current-pirce card-swiper-price-cart">'
                                                    . wc_price( $current_product->get_sale_price() ) .'</div>';
                                                echo '<div class="card-swiper-slider__old-pirce card-swiper-price-cart">'
                                                    . wc_price( $current_product->get_regular_price() ) .'</div>';
                                            } elseif ( $current_product->get_price() ) {
                                                echo '<div class="card-swiper-slider__current-pirce card-swiper-price-cart">'
                                                    . wc_price( $current_product->get_price() ) .'</div>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <a href="<?php echo esc_url( $current_product->add_to_cart_url() ); ?>"
                               class="add_to_cart_button ajax_add_to_cart card-swiper-slider__bag"
                               data-product_id="<?php echo esc_attr( $current_product->get_id() ); ?>"
                               data-product_sku="<?php echo esc_attr( $current_product->get_sku() ); ?>"
                               aria-label="<?php echo esc_attr( $current_product->add_to_cart_text() ); ?>"
                               rel="nofollow">
                                <svg class="bag">
                                    <use xlink:href="#red-bag"></use>
                                </svg>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </article>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>
    <?php

    $html = ob_get_clean();
    wp_send_json_success( array('html' => $html) );
}

function crabs_theme_sticky_scripts() {
    if ( is_product() ) {
        wp_enqueue_script( 'sticky-kit', get_stylesheet_directory_uri() . '/js/sticky-kit.min.js', array( 'jquery' ), '1.1.2', true );
        wp_enqueue_script( 'sticky-init', get_stylesheet_directory_uri() . '/js/sticky-init.js', array( 'sticky-kit' ), null, true );
    }
}
add_action( 'wp_enqueue_scripts', 'crabs_theme_sticky_scripts' );




add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'cart-slide',
        get_stylesheet_directory_uri() . '/js/cart-slide.js',
        [], '1.0', true
    );
});

add_action('wp_footer', function () {
    echo '<div id="cartOverlay" class="cart-overlay" aria-hidden="true"></div>';
});


add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'cart-feedback',
        get_stylesheet_directory_uri() . '/js/cart-feedback.js',
        ['jquery','wc-add-to-cart'], // важливо!
        '1.0',
        true
    );
});





add_action('wp_footer', function () {
  if ( !is_shop() && !is_product_taxonomy() ) return; // тільки сторінки каталогу/категорій
  ?>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const body = document.querySelector('#catalog-body');
    const typeWrap = document.querySelector('.catalog-header__types');
    if (!body || !typeWrap) return;

    // У вашій розмітці 2 кнопки: [0] — список, [1] — плитки
    const btns = typeWrap.querySelectorAll('.catalog-header__type');
    const btnList = btns[0];
    const btnGrid = btns[1];

    function markActive(which){ // which: 'grid' | 'list'
      [btnList, btnGrid].forEach(b => b && b.classList.remove('active'));
      (which === 'grid' ? btnGrid : btnList).classList.add('active');
    }

    function setMode(which, persist=true){
      body.classList.remove('list','active');
      if (which === 'grid') body.classList.add('active'); // grid у вас = class "active"
      else body.classList.add('list');                    // list = class "list"
      markActive(which);
      if (persist) localStorage.setItem('catalog_view_mode', which);
    }

    // 1) застосувати збережений режим або дефолт (grid)
    const saved = localStorage.getItem('catalog_view_mode');
    setMode(saved || 'grid', false);

    // 2) слухачі на кліки по іконках
    if (btnGrid) btnGrid.addEventListener('click', function(e){
      e.preventDefault();
      setMode('grid');
    });
    if (btnList) btnList.addEventListener('click', function(e){
      e.preventDefault();
      setMode('list');
    });
  });
  </script>
  <?php
});






add_action('wp_enqueue_scripts', function () {
  wp_enqueue_script(
    'catalog-slider',
    get_stylesheet_directory_uri() . '/js/catalog-slider.js',
    ['swiper-js'], // залежність від Swiper
    null,
    true
  );
});



add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'catalog-slider',
    get_stylesheet_directory_uri() . '/css/catalog-slider.css',
    [],
    null
  );
});



function crabs_render_stock_badge( $product = null ) {
    if ( ! $product ) $product = wc_get_product( get_the_ID() );
    if ( ! $product ) return;

    // Поріг низьких залишків з WooCommerce (як у твоєму скріні)
    $threshold = (int) wc_get_low_stock_amount( $product );
    if ( $threshold <= 0 ) $threshold = 2;

    $status_class = '';
    $status_text  = '';
    $tooltip_text = '';
    $qty          = 0; // рахуємо для відображення

    if ( ! $product->is_in_stock() ) {
        $status_class = 'tag-outofstock';
        $status_text  = 'Під замовлення';
    } elseif ( $product->is_on_backorder( 1 ) ) {
        $status_class = 'tag-preorder';
        $status_text  = 'Під замовлення';
    } else {
        // підрахунок залишку
        if ( $product->is_type('simple') ) {
            if ( $product->managing_stock() ) {
                $qty = max(0, (int) $product->get_stock_quantity());
            } else {
                $qty = $threshold + 1; // без керування складом вважаємо “достатньо”
            }
        } elseif ( $product->is_type('variable') ) {
            // сума по варіаціях (можна замінити на мінімум — за потреби скажи)
            $sum = 0; $has_managed = false;
            foreach ( $product->get_children() as $vid ) {
                $v = wc_get_product( $vid );
                if ( ! $v || ! $v->is_in_stock() ) continue;
                if ( $v->managing_stock() ) {
                    $has_managed = true;
                    $sum += max(0, (int) $v->get_stock_quantity());
                }
            }
            $qty = $has_managed ? $sum : ($threshold + 1);
        }

        if ( $qty > 0 && $qty <= $threshold ) {
            $status_class = 'tag-lowstock';
            // показуємо кількість прямо в бейджі
            $status_text  = 'Закінчується';
            $tooltip_text = 'Залишилося мало товару (поріг: ' . $threshold . ' шт).';
        } else {
            $status_class = 'tag-available';
            $status_text  = 'В наявності';
        }
    }

    echo '<div class="catalog-card__tag tag-order ' . esc_attr($status_class) . '">'
        . esc_html($status_text)
        . ( $status_class === 'tag-lowstock'
            ? ' <button type="button" class="stock-info-btn" aria-label="Що означає статус">i</button>'
              . '<div class="stock-tooltip" role="tooltip">' . esc_html($tooltip_text) . '</div>'
            : '' )
        . '</div>';
}

function crabs_should_show_price( $product ) {
    if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
        return true;
    }

    // Простий або варіація: ховаємо, якщо out of stock або backorder
    // if ( $product->is_type( 'simple' ) || $product->is_type( 'variation' ) ) {
    //     return ( $product->is_in_stock() && ! $product->is_on_backorder( 1 ) );
    // }

    // // Варіативний: показуємо ціну тільки якщо є ХОЧА Б ОДНА варіація справді "в наявності" (не backorder)
    // if ( $product->is_type( 'variable' ) ) {
    //     foreach ( $product->get_children() as $vid ) {
    //         $v = wc_get_product( $vid );
    //         if ( $v && $v->is_in_stock() && ! $v->is_on_backorder( 1 ) ) {
    //             return true;
    //         }
    //     }
    //     return false; // усі варіації backorder/out of stock → ховаємо
    // }

    return true;
}

// Попап бонусів
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_script(
    'crabs-bonus-popup',
    get_stylesheet_directory_uri() . '/js/crabs-bonus-popup.js',
    array('jquery', 'wc-cart-fragments'),
    '1.0.0',
    true
  );
});

add_action('wp_enqueue_scripts', function() {
  if (is_checkout()) {
    wp_enqueue_script(
      'checkout-bonus',
      get_stylesheet_directory_uri() . '/js/checkout-bonus.js',
      array('jquery', 'wc-checkout'),
      '1.0.0',
      true
    );
  }
});


add_action('wp_ajax_get_product_stock', 'get_product_stock_callback');
add_action('wp_ajax_nopriv_get_product_stock', 'get_product_stock_callback');

function get_product_stock_callback() {
    $xml_url = 'https://server-v2.servep2p.com:8085/PricePromOdegda.xml';
    $sku = sanitize_text_field($_GET['sku'] ?? '');

    if (!$sku) {
        wp_send_json_error(['message' => 'SKU не передано.']);
    }

    $transient_key = 'xml_priceprom_cache';
    $xml_data = get_transient($transient_key);

    if ($xml_data === false) {
        $response = wp_remote_get($xml_url, [
            'timeout' => 15,
            'sslverify' => false, // ⚠️ ігноруємо помилки SSL для цього запиту
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => 'Помилка завантаження XML: ' . $response->get_error_message()]);
        }

        $body = wp_remote_retrieve_body($response);
        if (empty($body)) {
            wp_send_json_error(['message' => 'Отримано порожню відповідь від сервера XML.']);
        }

        $xml = @simplexml_load_string($body);
        if (!$xml) {
            wp_send_json_error(['message' => 'Не вдалося розпарсити XML.']);
        }

        $xml_data = json_encode($xml);
        set_transient($transient_key, $xml_data, HOUR_IN_SECONDS);
    }

    $xml = json_decode($xml_data);
    $stock_status = 'Немає даних по товару.';
    
    if ($xml) {
        $offers = [];
    
        // XML може мати вкладені <shop><offers><offer>
        if (isset($xml->shop->offers->offer)) {
            $offers = $xml->shop->offers->offer;
        } elseif (isset($xml->offer)) {
            $offers = $xml->offer;
        }
    
        foreach ($offers as $offer) {
            $kodBAF     = isset($offer->KodBAF) ? (string) $offer->KodBAF : '';
            $vendorCode = isset($offer->vendorCode) ? (string) $offer->vendorCode : '';
        
            if ($kodBAF === $sku || $vendorCode === $sku) {
                $quantity = isset($offer->quantity_in_stock) ? (int) $offer->quantity_in_stock : 0;
                $stock_status = $quantity > 0
                    ? " ✅ В наявності на складі ($quantity шт.)"
                    : "❌ Немає в наявності";
                break;
            }
        }
    }


    wp_send_json_success(['status' => $stock_status]);
}





add_action( 'wp_enqueue_scripts', function () {

    // підв’язуємо до базового фронтового скрипта WooCommerce
    wp_add_inline_script( 'wc-add-to-cart', "
    (function($){
      function recalcBonuses(scope){
        var \$root = scope ? \$(scope) : \$(document);

        \$root.find('.cart_section, .mini_cart_item, article.woocommerce-mini-cart-item').each(function(){
          var \$item  = \$(this);
          var \$wrap  = \$item.find('.cart_bottom'); // контейнер, де показуємо бонуси
          if (!\$wrap.length) return;

          // ціна за 1 шт має бути покладена у data-атрибут у шаблоні
          var unit = parseFloat(String(\$wrap.data('unit-price')).replace(',', '.')) || 0;

          // читаємо кількість
          var \$qty = \$item.find('.quantity input.qty, .quantity-controls input[type=\"text\"], .quantity-controls input[type=\"number\"]').first();
          var qty  = parseInt(\$qty.val(), 10);
          if (isNaN(qty) || qty < 1) qty = 1;

          var bonus = Math.max(0, Math.round(unit * qty * 0.01));

          // підставляємо число
          var \$val = \$wrap.find('.cart-bonus__value');
          if (!\$val.length) {
            var \$text = \$wrap.find('.cart-bonus__text');
            if (\$text.length) {
              \$('<span class=\"cart-bonus__value\"></span>').insertBefore(\$text);
              \$val = \$wrap.find('.cart-bonus__value');
            } else {
              \$wrap.append('<span class=\"cart-bonus__value\"></span>');
              \$val = \$wrap.find('.cart-bonus__value');
            }
          }
          \$val.text('+' + bonus);
        });
      }

      // перерахунок при завантаженні/оновленні фрагментів
      \$(document).on('ready wc_fragments_loaded wc_fragments_refreshed updated_wc_div', function(){
        recalcBonuses(document);
      });

      // перерахунок при зміні кількості
      \$(document).on('click', '.quantity .plus, .quantity .minus, .quantity-controls .plus, .quantity-controls .minus', function(){
        var \$item = \$(this).closest('.cart_section, .mini_cart_item, article.woocommerce-mini-cart-item');
        setTimeout(function(){ recalcBonuses(\$item); }, 10);
      });

      \$(document).on('change keyup', '.quantity input.qty, .quantity-controls input[type=\"text\"], .quantity-controls input[type=\"number\"]', function(){
        recalcBonuses(\$(this).closest('.cart_section, .mini_cart_item, article.woocommerce-mini-cart-item'));
      });

    })(jQuery);
    ");

});

// картинки для категорій в конструкторі
add_action('wp_head', function() {
    if (!is_page('konstruktor')) return;

    $terms = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false
    ]);

    if (empty($terms) || is_wp_error($terms)) {
        return;
    }

    echo "<style>";

    foreach ($terms as $term) {
        $image_id = get_term_meta($term->term_id, 'thumbnail_id', true);

        if (!$image_id) continue;

        $image_url = wp_get_attachment_image_url($image_id, 'full');

        if (!$image_url) continue;

        echo "
        #constructor .wpc-filter-product_cat .wpc-term-id-{$term->term_id} label::before {
            background: url({$image_url}) !important;
            background-size: contain !important;
            background-repeat: no-repeat !important;
        }";
    }

    echo "</style>";
});

// заміна назви "mono checkout" на потрібну
add_filter('woocommerce_gateway_title', function($title, $gateway_id){
    if ($gateway_id === 'monocheckout') {
        return 'Оплата частинами (Mono)';
    }
    return $title;
}, 20, 2);

// додавання og:image для категорій
add_action('wp_head', function () {
    if (!is_tax('product_cat')) {
        return;
    }

    $term = get_queried_object();
    $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);

    if ($thumbnail_id) {
        $image = wp_get_attachment_image_src($thumbnail_id, 'full');

        if (!empty($image[0])) {
            echo "\n<!-- Custom OG Image for product_cat -->\n";
            echo '<meta property="og:image" content="' . esc_url($image[0]) . '">' . "\n";
            echo '<meta property="og:image:secure_url" content="' . esc_url($image[0]) . '">' . "\n";
            echo '<meta property="og:image:type" content="image/jpeg">' . "\n";
            echo '<meta property="og:image:width" content="1200">' . "\n";
            echo '<meta property="og:image:height" content="630">' . "\n";
        }
    }
});




add_action('wp_enqueue_scripts', function () {

  if (is_page_template('page-templates/template-pay-delivery.php')) {
    $rel = '/css/pay-delivery.css';
    $abs = get_stylesheet_directory() . $rel;

    wp_enqueue_style(
      'crabs-pay-delivery',
      get_stylesheet_directory_uri() . $rel,
      [],
      file_exists($abs) ? filemtime($abs) : '1.0'
    );
  }

});

add_action('wp_enqueue_scripts', function () {

  if (is_page_template('page-templates/template-returns.php')) {
    $rel = '/css/returns.css';
    $abs = get_stylesheet_directory() . $rel;

    wp_enqueue_style(
      'crabs-returns',
      get_stylesheet_directory_uri() . $rel,
      [],
      file_exists($abs) ? filemtime($abs) : '1.0'
    );
  }

});


add_action('wp_enqueue_scripts', function () {

  if (is_page_template('page-templates/template-warranty.php')) {
    $rel = '/css/warranty.css';
    $abs = get_stylesheet_directory() . $rel;

    wp_enqueue_style(
      'crabs-warranty',
      get_stylesheet_directory_uri() . $rel,
      [],
      file_exists($abs) ? filemtime($abs) : '1.0'
    );
  }

});



add_action('wp_enqueue_scripts', function () {

  if (is_page_template('page-templates/template-installment.php')) {
    $rel = '/css/installment.css';
    $abs = get_stylesheet_directory() . $rel;

    wp_enqueue_style(
      'crabs-installment',
      get_stylesheet_directory_uri() . $rel,
      [],
      file_exists($abs) ? filemtime($abs) : '1.0'
    );
  }

});

remove_action('wp_head', 'wp_site_icon', 99);



