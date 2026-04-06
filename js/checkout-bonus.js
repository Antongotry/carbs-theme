(function($){
  'use strict';

  function updateBonus() {
    // шукаємо загальну вартість
    const totalText = $('.orders__bottom-block p').text() || $('.orders__bottom-option').text();
    if (!totalText) return;

    // шукаємо число перед "₴"
    const match = totalText.match(/([\d\s.,]+)\s*₴/);
    if (!match) return;

    // очищаємо і конвертуємо у число
    const total = parseFloat(match[1].replace(/\s/g, '').replace(',', '.')) || 0;
    const bonus = Math.round(total * 0.01); // 1% бонусів

    // шукаємо або створюємо елемент для виводу
    let $bonus = $('.order-details__bonus');
    if (!$bonus.length) {
      $('.orders__bottom-block').append('<span class="order-details__bonus"></span>');
      $bonus = $('.order-details__bonus');
    }

    $bonus.text(`+${bonus.toLocaleString('uk-UA')} крабів`);
  }

  // запускаємо при оновленні checkout
  $(document.body).on('updated_checkout', updateBonus);

  // перший запуск після завантаження
  $(document).ready(function(){
    setTimeout(updateBonus, 800);
  });

})(jQuery);
