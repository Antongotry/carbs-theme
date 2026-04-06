<?php
/**
* Template Name: Сторінка Оформлення Замовлення
*
* @package WordPress
* @subpackage Crabs Theme
* @since Crabs Theme 1.0
*/

get_header(); ?>

<section class="orders">
    <div class="container">
        
        
        <?php echo do_shortcode( '[woocommerce_checkout]' ) ?>
        
    </div>
</section>

<?php
get_footer();