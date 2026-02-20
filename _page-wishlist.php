<?php 

/* Template Name: Страница избранного */ 

?>
<?php get_header() ?>

<div class="favourites__header container">
    <h1>Список вподобаного</h1>
    <span>Кількість товарів: <span id="wishlist-count-content">0</span> шт</span>
</div>

<div class="catalog-main container">
    <article class="favourites__body active wishlist" id="wishlist-products-container">
        <div class="wishlist-loading" id="wishlist-loading" style="text-align:center;padding:40px 0;">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/spinner_def.gif" alt="loading" width="40" height="40">
        </div>
    </article>
</div>

<script>
jQuery(document).ready(function($) {
    var wishlistRaw = $.cookie('wishlist');
    var wishlist = [];
    try {
        if (wishlistRaw) {
            wishlist = JSON.parse(wishlistRaw);
            if (!Array.isArray(wishlist)) wishlist = [];
        }
    } catch(e) {
        wishlist = [];
    }

    $('#wishlist-count-content').text(wishlist.length);

    if (wishlist.length === 0) {
        $('#wishlist-products-container').html(
            '<div class="empty-wishlist">' +
            '<p>Ваш список вподобаного пустий, будь ласка додайте товари</p>' +
            '<a href="/shop/" class="btn-black"><span>До каталогу</span></a>' +
            '</div>'
        );
        return;
    }

    var ajaxUrl = (typeof wooeshop_wishlist_object !== 'undefined' && wooeshop_wishlist_object.url)
        ? wooeshop_wishlist_object.url
        : '<?php echo admin_url("admin-ajax.php"); ?>';

    $.ajax({
        type: 'POST',
        url: ajaxUrl,
        data: {
            action: 'load_wishlist_products',
            product_ids: wishlist
        },
        success: function(response) {
            if (response.success && response.data.html) {
                $('#wishlist-products-container').html(response.data.html);
                $('#wishlist-count-content').text(response.data.count);
            } else {
                $('#wishlist-products-container').html(
                    '<div class="empty-wishlist">' +
                    '<p>Ваш список вподобаного пустий, будь ласка додайте товари</p>' +
                    '<a href="/shop/" class="btn-black"><span>До каталогу</span></a>' +
                    '</div>'
                );
            }
        },
        error: function() {
            $('#wishlist-products-container').html(
                '<div class="empty-wishlist">' +
                '<p>Помилка завантаження. Спробуйте оновити сторінку.</p>' +
                '</div>'
            );
        }
    });
});
</script>

<?php get_footer() ?>
