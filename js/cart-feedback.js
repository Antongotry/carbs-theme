jQuery(function($){
  const $cartBtn = $('#btnCartHeader');

  // Створюємо toast один раз
  if (!$('#wcAddToast').length) {
    $('body').append('<div id="wcAddToast" class="wc-toast" role="status" aria-live="polite">Товар додано в кошик</div>');
  }
  const $toast = $('#wcAddToast');

  // Woo подія після AJAX-додавання
  $(document.body).on('added_to_cart', function(e, fragments, cart_hash, $button){
    // 1) Toast
    $toast.addClass('show');
    setTimeout(()=> $toast.removeClass('show'), 1800);

    // 2) Пульс іконки кошика
    $cartBtn.addClass('pulse');
    setTimeout(()=> $cartBtn.removeClass('pulse'), 800);
  });
});
