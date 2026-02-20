document.addEventListener('DOMContentLoaded', function () {
  const BP = 992; // мінімальна ширина для ввімкнення слайдера (desktop)
  const mm = window.matchMedia(`(min-width:${BP}px)`);

  // список категорій (поточний горизонтальний скрол)
  const list = document.querySelector('.categories-horizontal-scroll-wrapper__categories-list');
  if (!list) return;

  let swiper = null;
  let wrapper = null;

  // допоміжне: прибрати інлайн-стилі
  function clearInlineStyles(el) {
    if (!el) return;
    el.removeAttribute('style');
    Array.from(el.children || []).forEach(ch => ch.removeAttribute('style'));
  }

  // створюємо/повертаємо панель навігації, вставляємо ЇЇ В СЕРЕДИНУ .cat-swiper
  function ensureNav() {
    const currentSwiper = document.querySelector('.cat-swiper');
    if (!currentSwiper) return null;

    let nav = currentSwiper.querySelector('.cat-swiper-nav');
    if (nav) return nav; // вже є

    nav = document.createElement('div');
    nav.className = 'cat-swiper-nav';
    nav.innerHTML = `
      <button type="button" class="cat-swiper-btn cat-prev" aria-label="Попередня">‹</button>
      <button type="button" class="cat-swiper-btn cat-next" aria-label="Наступна">›</button>
    `;
    currentSwiper.appendChild(nav);
    return nav;
  }

  function mountSlider() {
    if (swiper) return; // вже ініціалізовано

    // обгортка .swiper
    wrapper = document.createElement('div');
    wrapper.className = 'swiper cat-swiper';
    list.parentNode.insertBefore(wrapper, list);
    wrapper.appendChild(list);

    // класи для Swiper
    list.classList.add('swiper-wrapper');
    Array.from(list.children).forEach(el => el.classList.add('swiper-slide'));

    // конфіги: loop лише якщо достатньо слайдів
    const slidesCount = list.children.length;
    const loopEnabled = slidesCount > 3;

    // стрілки всередині .cat-swiper
    const nav = ensureNav();

    // ініціалізація
    swiper = new Swiper(wrapper, {
      loop: loopEnabled,
      speed: 450,
      spaceBetween: 10,
      slidesPerView: 'auto',
      watchSlidesProgress: true,
      navigation: nav ? {
        nextEl: nav.querySelector('.cat-next'),
        prevEl: nav.querySelector('.cat-prev')
      } : undefined,
      observeParents: true,
      observer: true
    });
  }

  function unmountSlider() {
    if (!swiper) return;

    // знищуємо Swiper
    swiper.destroy(true, true);
    swiper = null;

    // повертаємо DOM у вихідний стан
    if (wrapper && wrapper.parentNode) {
      wrapper.parentNode.insertBefore(list, wrapper);
      wrapper.remove();
      wrapper = null;
    }
    list.classList.remove('swiper-wrapper');
    Array.from(list.children).forEach(el => el.classList.remove('swiper-slide'));

    // забираємо інлайн-стилі, які міг навісити Swiper
    clearInlineStyles(list);
    Array.from(list.children).forEach(clearInlineStyles);
  }

  function apply() {
    if (mm.matches) {
      mountSlider();   // desktop — слайдер
    } else {
      unmountSlider(); // mobile — звичайний горизонтальний скрол
    }
  }

  // перший запуск
  apply();

  // реагуємо на зміну брейкпоінту
  if (typeof mm.addEventListener === 'function') {
    mm.addEventListener('change', apply);
  } else {
    // fallback для старіших браузерів
    mm.addListener(apply);
  }
});
