(function ($) {
  'use strict';

  // HTML попапу
  const popupHTML = `
    <div class="crabs-bonus_popup" aria-hidden="true">
      <div class="crabs-bonus_popup__inner">
        <div class="crabs-bonus_popup__icon">
          <img src="/wp-content/uploads/2025/10/Krab.webp" alt="Crabs bonus icon" width="56" height="41">
        </div>
        <p>Як ви можете використати бонуси?<br><strong>1 краб = 1 грн</strong></p>
      </div>
    </div>
  `;

  // Створюємо попап у body
  function ensurePopup() {
    if (!$('.crabs-bonus_popup').length) {
      $('body').append(popupHTML);
    }
  }

  // Відкрити
 function openPopup($btn) {
  ensurePopup();
  const $popup = $('.crabs-bonus_popup');
  $popup.css('display', 'flex').addClass('active');
  $('body').addClass('crabs-bonus-popup-open');
}

  // Закрити
 function closePopup() {
  $('body').removeClass('crabs-bonus-popup-open');
  const $popup = $('.crabs-bonus_popup');
  $popup.removeClass('active').hide(); // повністю ховаємо
}

  // Клік на кнопку "і"
  $(document).on('click', '.crabs-bonus__hint', function (e) {
    e.preventDefault();
    openPopup($(this));
  });

  // Клік поза попапом — закриває
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.crabs-bonus_popup, .crabs-bonus__hint').length) {
      closePopup();
    }
  });

  // ESC — закриває
  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') closePopup();
  });

  // Ініціалізація
  $(function () {
    ensurePopup();
  });
})(jQuery);
