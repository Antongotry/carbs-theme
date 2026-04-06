<?php
/**
 * Product Loop End
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-end.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

	
</article>

<?php if (is_shop() || is_product_category() ) : ?>
<?php
 global $wp_query;
?>
<?php if ($wp_query->max_num_pages > 1) : ?>
<div class="catalog-main__btn">
    <a href="##" class="btn-black" id="load-more" data-page="1">
        <svg class="my-custom-icon-class" width="23" height="19">
            <use xlink:href="#icon-refresh-cycle"></use>
        </svg>
        <span style="margin-left: 10px">Ще 12 товарів</span>
    </a>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if (is_product_category()) : ?>
    <div class="catalog-footer">
<!--        <h2>--><?php //woocommerce_page_title(); ?><!--</h2>-->
    </div>
<?php endif; ?>


			
</div> <!-- catalog-body -->
