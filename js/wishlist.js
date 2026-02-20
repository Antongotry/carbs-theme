// jQuery(document).ready(function($) {
//     updateWishlistCount();

//     function getWishlistCountFromCookie() {
//         let wishlist = $.cookie('wishlist');
//         if (wishlist === undefined || wishlist === null || wishlist.length === 0) {
//             return 0;
//         } else {
//             wishlist = JSON.parse(wishlist);
//             return wishlist.length;
//         }
//     }

//     function updateWishlistCount() {
//         let count = getWishlistCountFromCookie();
//         $('#wishlist-count').text(count); // Обновляем в хедере
//         $('#wishlist-count-content').text(count); // Обновляем в контентной части
//     }

//     // Функция для удаления товара из избранного
//     function removeFromWishlist(productId) {
//         let wishlist = $.cookie('wishlist');
//         if (wishlist !== undefined) {
//             wishlist = JSON.parse(wishlist);
//             let index = wishlist.indexOf(productId);
//             if (index > -1) {
//                 wishlist.splice(index, 1); // Удаляем товар из массива
//                 $.cookie('wishlist', JSON.stringify(wishlist), { expires: 30, path: '/' });
//                 updateWishlistCount(); // Обновляем количество товаров на странице
//                 $(`#product-${productId}`).remove(); // Удаляем карточку товара со страницы
//                 iziToast.success({
//                     title: 'Success',
//                     message: 'Товар видалено з обраного',
//                 });
//             }
//         }
//     }

//     // Обработчик клика на кнопке "удалить из избранного"
//     $('.remove-wishlist').on('click', function () {
//         let productId = $(this).data('product-id');
//         removeFromWishlist(productId);
//     });

//     // Обработчик для добавления/удаления из избранного
//     $('.wishlist-icon').on('click', function () {
//         let $this = $(this);
//         let productId = $this.data('id');
//         let ajaxLoader = $this.closest('.product-card').find('.ajax-loader');
//         ajaxLoader.fadeIn();

//         let wishlist = $.cookie('wishlist');
//         $this.toggleClass('in-wishlist');
//         if (wishlist === undefined) {
//             wishlist = [productId];
//             $.cookie('wishlist', JSON.stringify(wishlist), { expires: 30, path: '/' });
//             iziToast.success({
//                 title: 'Success',
//                 message: wooeshop_wishlist_object.add,
//             });
//         } else {
//             wishlist = JSON.parse(wishlist);
//             if (wishlist.includes(productId)) {
//                 let index = wishlist.indexOf(productId);
//                 wishlist.splice(index, 1);

//                 iziToast.success({
//                     title: 'Success',
//                     message: wooeshop_wishlist_object.remove,
//                 });

//                 if (location.pathname === '/wishlist/') {
//                     iziToast.warning({
//                         message: wooeshop_wishlist_object.reload,
//                         timeout: 2000,
//                         onClosing: function(instance, toast, closedBy){
//                             location = location.href;
//                         }
//                     });
//                 }
//             } else {
//                 if (wishlist.length >= 8) {
//                     wishlist.shift();
//                 }
//                 wishlist.push(productId);
//                 iziToast.success({
//                     title: 'Success',
//                     message: wooeshop_wishlist_object.add,
//                 });
//             }
//             $.cookie('wishlist', JSON.stringify(wishlist), { expires: 30, path: '/' });
//         }

//         ajaxLoader.fadeOut();
//         updateWishlistCount(); // Обновляем количество в обоих местах
//     });
// });


jQuery(document).ready(function($) {
    // Инициализация и обновление количества товаров в избранном
    updateWishlistCount();
    updateCartCount();

    // Обработчик на случай возврата назад в браузере
    window.addEventListener("pageshow", function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            // Если страница загружена из кэша, обновляем оба счетчика
            updateWishlistCount();
            updateCartCount();
        }
    });

    // Функция для получения количества товаров в избранном из cookie
    function getWishlistCountFromCookie() {
        let wishlist = $.cookie('wishlist');
        if (wishlist === undefined || wishlist === null || wishlist.length === 0) {
            return 0;
        } else {
            wishlist = JSON.parse(wishlist);
            return wishlist.length;
        }
    }

    // Функция для обновления количества товаров в избранном
    function updateWishlistCount() {
        let count = getWishlistCountFromCookie();
        $('#wishlist-count').text(count); // Обновляем в хедере
        $('#wishlist-count-content').text(count); // Обновляем в контентной части
    }

    // Функция для обновления количества товаров в корзине
    function updateCartCount() {
        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params.ajax_url,
            data: {
                action: 'get_cart_count'
            },
            success: function(response) {
                if (response.data.cart_count !== undefined) {
                    $('.cart-count').text(response.data.cart_count);
                }
                $('#mini-cart-spinner').hide(); // Скрываем спиннер после обновления
            }
        });
    }

    // Функция для удаления товара из избранного
    function removeFromWishlist(productId) {
        let wishlist = $.cookie('wishlist');
        if (wishlist !== undefined) {
            wishlist = JSON.parse(wishlist);
            let index = wishlist.indexOf(productId);
            if (index > -1) {
                wishlist.splice(index, 1); // Удаляем товар из массива
                $.cookie('wishlist', JSON.stringify(wishlist), { expires: 30, path: '/' });
                updateWishlistCount(); // Обновляем количество товаров на странице
				// Проверяем, находимся ли мы на странице вишлиста
				if ($('body').hasClass('page-template-_page-wishlist')) {
					$(`#product-${productId}`).remove(); // Удаляем карточку товара со страницы
				}
                iziToast.success({
                    message: wooeshop_wishlist_object.remove,
                });
            }
        }
    }

    // Обработчик клика для добавления/удаления товаров из избранного
    $('.wishlist-icon').on('click', function () {
        let $this = $(this);
        let productId = $this.data('id');
        let ajaxLoader = $this.closest('.product-card').find('.ajax-loader');
        ajaxLoader.fadeIn();

        let wishlist = $.cookie('wishlist');
        $this.toggleClass('in-wishlist');
        if (wishlist === undefined) {
            wishlist = [productId];
            $.cookie('wishlist', JSON.stringify(wishlist), { expires: 30, path: '/' });
            iziToast.success({
                message: wooeshop_wishlist_object.add,
            });
        } else {
            wishlist = JSON.parse(wishlist);
            if (wishlist.includes(productId)) {
                removeFromWishlist(productId); // Удаление через функцию
            } else {
                if (wishlist.length >= 8) {
                    wishlist.shift();
                }
                wishlist.push(productId);
                $.cookie('wishlist', JSON.stringify(wishlist), { expires: 30, path: '/' });
                iziToast.success({
                    message: wooeshop_wishlist_object.add,
                });
            }
        }

        ajaxLoader.fadeOut();
        updateWishlistCount(); // Обновляем количество в избранном
    });

    // Обработчик клика для удаления товаров из избранного с использованием `.remove-wishlist`
    $('body').on('click', '.remove-wishlist', function(e) {
        e.preventDefault();
        let productId = $(this).data('product-id');
        removeFromWishlist(productId); // Удаление через функцию
    });
    
    // Функция для показа спиннера
    function showSpinner() {
        $('#mini-cart-spinner').show();
    }
});


