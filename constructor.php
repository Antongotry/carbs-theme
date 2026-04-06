<?php
/**
* Template Name: Збери свій Cybex
*
* @package WordPress
* @subpackage Crabs Theme
* @since Crabs Theme 1.0
*/

get_header(); 

global $is_constructor_page;
$is_constructor_page = true;
?>


<div id="constructor" class="builder container">
    <h1>Збери свій Cybex</h1>

    <div class="builder__content">
        <p class="intro mob">Підберіть складові для ідеального комплекту від Cybex під ваші потреби в одному місці, не шукаючи по сайту.</p>
        <aside>
            <?php echo do_shortcode('[fe_widget id="442"]'); ?>
            <?php //echo do_shortcode('[fe_open_button id="442"]'); ?>
            
            <div class="package-box" style="display:none;">
				<div class="package-items"> <span class="pc">Всього товарів в кошику:</span> <span class="now-nums">1</span></div>
				<a class="btn-black open-cart"><span class="pc">Переглянути кошик</span></a>
<!-- <div id="constructor-cart-content" class="constructor-cart"><div class="widget_shopping_cart_content constructor-content"></div></div> -->
            </div>
        </aside>
		<div class="block-mobile">
		    
		    
		    
		
		
		<div class="widget-title wpc-filter-title mob">Оберіть товар</div>
        <div class="builder__body" id="constructor-products">
            <article>
                <?php
                // Получаем параметры фильтрации из URL
                $query_vars = array_merge($_GET, array(
                    'post_type'      => 'product',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1, // <<< показати всі
                ));
                // додаємо умову: лише товари "instock"
                $stock_meta = array(
                'key'   => '_stock_status',
                'value' => 'instock',
                );
                // якщо в URL уже є meta_query — підмішаємо, інакше створимо
                if ( isset($query_vars['meta_query']) && is_array($query_vars['meta_query']) ) {
                  $query_vars['meta_query'][] = $stock_meta;
                     } else {
                 $query_vars['meta_query'] = array( $stock_meta );
                }

                // $loop = new WP_Query($query_vars);

                // Создаем новый WP_Query с учетом параметров фильтрации
                $loop = new WP_Query($query_vars);
                
                if ($loop->have_posts()) :
                    // echo '<div class="products-grid">';
                    while ($loop->have_posts()) : $loop->the_post();
                    global $product;
                        if ( ! $product || ! $product->is_in_stock() ) {
                 continue; // пропустити товар і перейти до наступного
                }

                        
                        $product_id = $product->get_id();
                        $product_id = $product->get_id();

                        $colors = wc_get_product_terms( $product_id, 'pa_kolory-v-naiavnosti', array( 'fields' => 'all' ) );

                        // Уникальные идентификаторы
                        $slider_id = 'swiper-' . $product_id;
                        $prev_button_id = 'prev-button-' . $product_id;
                        $next_button_id = 'next-button-' . $product_id;
                        ?>
                        
                        <section class="product-item">
                            <a class="builder__image" href="<?php the_permalink(); ?>">
                                <?php if (has_post_thumbnail()) {
                                    the_post_thumbnail('medium');
                                } ?>
                            </a>
                            
                            <div class="builder__colors">
                                <?php if(!empty($colors)) { ?>
                                    <div class="cons__gallary" id="<?php echo $slider_id; ?>">
                                        <div class="builder__wrapper swiper-wrapper">
                                            <?php foreach ( $colors as $color ) : ?>
                                                <div class="swiper-slide" data-attribute-slug="<?php echo esc_attr( $color->slug ); ?>">
                                                    
                                                        <?php 
                                                        $thumbnail_id = get_term_meta( $color->term_id, 'attribute_color_image_id', true );
                                                        if ($thumbnail_id) {
                                                            echo wp_get_attachment_image( $thumbnail_id, 'full' );
                                                        // } else {
                                                        //     echo '<img src="' . get_stylesheet_directory_uri() . '/img/default-attribute.jpg" alt="default image" />';
                                                         }
                                                        ?>
                                                        
                                                    
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="builder__slider-buttons">
                                        <div class="builder-btn-prev btn-swiper" id="<?php echo $prev_button_id; ?>">
                                            <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.4375 14.9531L1.40792 7.92355L8.4375 0.893973" stroke="#242424" stroke-width="1.08147" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <div class="builder-btn-next btn-swiper" id="<?php echo $next_button_id; ?>">
                                            <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.65625 0.894531L7.68583 7.92411L0.65625 14.9537" stroke="#242424" stroke-width="1.08147" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </div>
                                      <?php } ?> 
                            </div>
                            
                            
                           
                            
                              <?php
// перед <div class="builder-card__prices"> … або одразу під назвою
global $product; // у тебе він вже є нижче для цін, але на всяк випадок

$status_class = '';
$status_text  = '';

if ( $product->is_in_stock() ) {
    $status_class = 'tag-available';
    $status_text  = 'В наявності';
} elseif ( $product->is_on_backorder( 1 ) ) {
    $status_class = 'tag-preorder';
    $status_text  = 'Під замовлення';
} else {
    $status_class = 'tag-outofstock';
    $status_text  = 'Немає в наявності';
}

echo '<div class="catalog-card__tag catalog-card__tag--mobile tag-order ' . esc_attr( $status_class ) . '">'
        . esc_html( $status_text ) .
     '</div>';
?>
  

                            <a href="<?php the_permalink(); ?>" class="builder-card__title">
                                <h2 class="product-title"><?php the_title(); ?></h2>
                            </a>

                            <div class="builder-card__footer">
                                <div class="builder-card__prices">
                                    <?php if ( $product->is_type( 'variable' ) ) {
                                        // Get the available variations
                                        $available_variations = $product->get_available_variations();
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
                                            <div class="catalog-card__current-pirce"><?php echo wc_price( $min_price ); ?></div>
                                            <div class="catalog-card__old-pirce"><?php echo wc_price( $min_regular_price ); ?> </div>
                                            <?php
                                        } else {
                                            // Show regular price range
                                            ?>
                                            <div class="catalog-card__current-pirce"><?php echo wc_price( $min_price ); ?> - <?php echo wc_price( $max_price ); ?></div>
                                            <?php
                                        }
                                    } else {
                                        // For simple products
                                        if ( $product->get_sale_price() ) {
                                            ?>
                                            <div class="catalog-card__current-pirce"><?php echo wc_price( $product->get_sale_price() ); ?></div>
                                            <div class="catalog-card__old-pirce"><?php echo wc_price( $product->get_regular_price() ); ?></div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="catalog-card__current-pirce"><?php echo wc_price( $product->get_price() ); ?></div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>

                                <div class="builder-card__buttons">
                                    <a href="##" class="btn-black add-to-cart-button" data-product_id="<?php echo $product_id; ?>">
                                    В кошик
                                    <svg
                                        viewBox="0 0 19 19"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                        d="M5.14139 9V5C5.14139 2.79086 7.09281 1 9.5 1C11.9072 1 13.8586 2.79086 13.8586 5V9M3.1795 18H15.8205C17.1057 18 18.1118 16.9845 17.99 15.8104L17.1598 7.81038C17.0533 6.78391 16.1139 6 14.9903 6H4.00971C2.88615 6 1.94675 6.78391 1.84022 7.81038L1.01001 15.8104C0.888159 16.9846 1.89427 18 3.1795 18Z"
                                        stroke="white"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        />
                                    </svg>
                                    </a>
                                    <a href="<?php the_permalink(); ?>" class="btn-white view-product-button" data-product_id="<?php echo $product_id; ?>">
                                        Переглянути</a>
                                </div>
                            </div>
                            
                        </section>

                        <?php
                    endwhile;
                    
                else :
                    echo __('No products found');
                endif;

                wp_reset_postdata();
                ?>
            </article>

            <!-- <div class="builder__pagination">
                <a href="##" class="builder__pagination-text active">Перша</a>
                <a href="##" class="builder__pagination-number active">1</a>
                <a href="##" class="builder__pagination-number">2</a>
                <span>...</span>
                <a href="##" class="builder__pagination-number">10</a>
                <a href="##" class="builder__pagination-text">Остання</a>
            </div> -->

        </div>
        
       </div> 

    </div>
</div>

<div class="scroll-box" style="display:none;">
	<a class="btn-black scroll-model"><span>Вибір моделі</span></a>
	<a class="btn-black scroll-category"><span>Вибір категорії</span></a>
</div>




<?php
get_footer();
?>
