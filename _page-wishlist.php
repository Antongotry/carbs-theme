<?php 

/* Template Name: Страница избранного */ 

?>
<?php get_header() ?>

<div class="favourites__header container">
    <h1>Список вподобаного</h1>
    <span>Кількість товарів: <span id="wishlist-count-content">0</span> шт</span>
</div>

<div class="catalog-main container">
    <article class="favourites__body active wishlist" id="wishlist-products-container"
             data-ajax-url="<?php echo esc_attr( admin_url('admin-ajax.php') ); ?>">
        <div class="wishlist-loading" id="wishlist-loading" style="text-align:center;padding:40px 0;">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/spinner_def.gif" alt="loading" width="40" height="40">
        </div>
    </article>
</div>

<?php get_footer() ?>
