document.addEventListener('DOMContentLoaded', () => {
  const cart    = document.getElementById('cart-wrapper');   // існуючий контейнер кошика
  const overlay = document.getElementById('cartOverlay');    // додаємо через wp_footer
  const openBtn = document.getElementById('btnCartHeader');  // кнопка у хедері
  const closeBtn= document.getElementById('closeCart');      // хрестик всередині кошика

  if (!cart || !overlay) return;

  const openCart = () => {
    cart.classList.add('open');
    overlay.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  };
 const closeCart = () => {
    if (!cart.classList.contains('open')) return;

    // Додаємо клас closing для анімації
    cart.classList.add('closing');
    overlay.classList.remove('is-open');
    document.body.classList.remove('lock');

    // Коли завершиться transition — прибираємо класи
    const onDone = (e) => {
        if (e && e.target !== cart) return; // щоб не спрацьовувало від дітей
        cart.classList.remove('open', 'closing');
        cart.removeEventListener('transitionend', onDone);
        document.body.style.overflow = '';
    };

    cart.addEventListener('transitionend', onDone);
    setTimeout(onDone, 400); // запасний варіант, якщо transitionend не спрацює
};


  openBtn?.addEventListener('click', e => { e.preventDefault(); openCart(); });
  closeBtn?.addEventListener('click', (e) => {
  e.preventDefault();
  e.stopPropagation();
  e.stopImmediatePropagation(); // якщо ще є слухачі
  closeCart();
}, true); // capture-фаза, щоб спрацювати раніше за делегати

  overlay?.addEventListener('click', closeCart);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCart(); });

  // Синхронізація, якщо інші скрипти додають/знімають .open
  const mo = new MutationObserver(() => {
    if (cart.classList.contains('open')) {
      overlay.classList.add('is-open'); document.body.style.overflow = 'hidden';
    } else {
      overlay.classList.remove('is-open'); document.body.style.overflow = '';
    }
  });
  mo.observe(cart, { attributes: true, attributeFilter: ['class'] });
});


