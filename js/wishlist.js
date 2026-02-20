jQuery(document).ready(function($) {
    updateWishlistCount();
    updateCartCount();

    window.addEventListener("pageshow", function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            updateWishlistCount();
            updateCartCount();
        }
    });

    function getWishlistCountFromCookie() {
        try {
            let wishlist = $.cookie('wishlist');
            if (wishlist === undefined || wishlist === null || wishlist.length === 0) {
                return 0;
            }
            wishlist = JSON.parse(wishlist);
            return Array.isArray(wishlist) ? wishlist.length : 0;
        } catch(e) {
            return 0;
        }
    }

    function getWishlistArray() {
        try {
            let raw = $.cookie('wishlist');
            if (raw === undefined || raw === null || raw.length === 0) {
                return [];
            }
            let parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
        } catch(e) {
            return [];
        }
    }

    function saveWishlist(wishlist) {
        $.cookie('wishlist', JSON.stringify(wishlist), { expires: 30, path: '/' });
    }

    function updateWishlistCount() {
        let count = getWishlistCountFromCookie();
        $('#wishlist-count').text(count);
        $('#wishlist-count-content').text(count);
    }

    function updateCartCount() {
        var ajaxUrl = null;
        if (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.ajax_url) {
            ajaxUrl = wc_add_to_cart_params.ajax_url;
        } else if (typeof wooeshop_wishlist_object !== 'undefined' && wooeshop_wishlist_object.url) {
            ajaxUrl = wooeshop_wishlist_object.url;
        }
        if (!ajaxUrl) {
            $('#mini-cart-spinner').hide();
            return;
        }
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: { action: 'get_cart_count' },
            success: function(response) {
                if (response && response.data && response.data.cart_count !== undefined) {
                    $('.cart-count').text(response.data.cart_count);
                }
                $('#mini-cart-spinner').hide();
            },
            error: function() {
                $('#mini-cart-spinner').hide();
            }
        });
    }

    function removeFromWishlist(productId) {
        productId = parseInt(productId, 10);
        let wishlist = getWishlistArray();
        let index = wishlist.indexOf(productId);
        if (index > -1) {
            wishlist.splice(index, 1);
            saveWishlist(wishlist);
            updateWishlistCount();
            if ($('body').hasClass('page-template-_page-wishlist')) {
                $('#product-' + productId).remove();
                if (wishlist.length === 0) {
                    $('.favourites__body').html(
                        '<div class="empty-wishlist">' +
                        '<p>Ваш список вподобаного пустий, будь ласка додайте товари</p>' +
                        '<a href="/shop/" class="btn-black"><span>До каталогу</span></a></div>'
                    );
                }
            }
            if (typeof iziToast !== 'undefined' && typeof wooeshop_wishlist_object !== 'undefined') {
                iziToast.success({ message: wooeshop_wishlist_object.remove });
            }
        }
    }

    $(document).on('click', '.wishlist-icon', function (e) {
        e.preventDefault();
        e.stopPropagation();

        let $this = $(this);
        let productId = parseInt($this.data('id'), 10);
        if (!productId) return;

        let ajaxLoader = $this.closest('.product-card, .catalog-card').find('.ajax-loader');
        ajaxLoader.fadeIn();

        let wishlist = getWishlistArray();
        if (wishlist.includes(productId)) {
            $this.removeClass('in-wishlist');
            removeFromWishlist(productId);
        } else {
            if (wishlist.length >= 8) {
                wishlist.shift();
            }
            wishlist.push(productId);
            saveWishlist(wishlist);
            $this.addClass('in-wishlist');
            if (typeof iziToast !== 'undefined' && typeof wooeshop_wishlist_object !== 'undefined') {
                iziToast.success({ message: wooeshop_wishlist_object.add });
            }
        }

        ajaxLoader.fadeOut();
        updateWishlistCount();
    });

    $(document).on('click', '.remove-wishlist', function(e) {
        e.preventDefault();
        let productId = $(this).data('product-id');
        removeFromWishlist(productId);
    });

    function showSpinner() {
        $('#mini-cart-spinner').show();
    }
});


