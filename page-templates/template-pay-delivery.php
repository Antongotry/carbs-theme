<?php
/**
 * Template Name: Crabs — Оплата та доставка
 */
get_header();
?>

<main id="primary" class="site-main">
  <section class="crabs-paydel">
    <div class="crabs-container">

      <h1 class="crabs-paydel__title"><?php the_title(); ?></h1>

      <div class="crabs-paydel__grid">

        <!-- Доставка -->
        <article class="crabs-card">
          <h2 class="crabs-card__title">Доставка</h2>

          <p class="crabs-card__lead">
            Ми розуміємо, що з появою малечі хочеться мінімум зайвих турбот. Тому подбали, щоб доставка була простою,
            зрозумілою і надійною.
          </p>

          <div class="crabs-card__block">
            <h3 class="crabs-card__h">Нова Пошта</h3>
            <p class="crabs-card__p">
              Доставляємо замовлення по всій Україні службою Нова Пошта.<br>
              При замовленні від 5000 грн доставка безкоштовна.
            </p>
          </div>

          <div class="crabs-card__block">
            <h3 class="crabs-card__h">Курʼєрська доставка</h3>
            <ul class="crabs-card__list">
              <li>За потреби можемо доставити замовлення курʼєром Нової Пошти прямо до дверей.</li>
              <li>Курʼєрська доставка також безкоштовна при замовленні від 5000 грн.</li>
            </ul>
          </div>

          <div class="crabs-card__block">
            <h3 class="crabs-card__h">Самовивіз</h3>
            <p class="crabs-card__p">
              Якщо вам зручно забрати покупку особисто, ви можете скористатися самовивозом з нашого шоуруму:
              м. Івано-Франківськ, вул. Вовчинецька, 227.
            </p>
          </div>

          <!-- Лого/іконка (за бажанням) -->
          <div class="crabs-card__logos">
            <!-- Постав своє SVG/PNG -->
            <img src="/wp-content/uploads/2025/12/nova-poshta.webp" alt="Нова Пошта"> 
          </div>
        </article>

        <!-- Оплата -->
        <article class="crabs-card">
          <h2 class="crabs-card__title">Оплата</h2>

          <p class="crabs-card__lead">
            Ми зробили оплату максимально простою і зрозумілою — без зайвих кроків і складних умов.
          </p>

          <div class="crabs-card__block">
            <h3 class="crabs-card__h">Оплата при отриманні</h3>
            <p class="crabs-card__p">
              Ви можете оплатити замовлення у відділенні Нової Пошти після огляду товару.
            </p>
          </div>

          <div class="crabs-card__block">
            <h3 class="crabs-card__h">Онлайн-оплата</h3>
            <p class="crabs-card__p">Оплатити замовлення можна онлайн через:</p>
            <ul class="crabs-card__list">
              <li>MonoCheckout</li>
              <li>MonoPay</li>
              <li>Оплата проходить швидко та безпечно, без додаткових комісій.</li>
            </ul>
          </div>

          <div class="crabs-card__block">
            <h3 class="crabs-card__h">Оплата на рахунок ФОП</h3>
            <p class="crabs-card__p">
              За потреби ви можете оплатити замовлення за реквізитами. Усі дані ми надаємо після оформлення замовлення.
            </p>
          </div>

          <div class="crabs-card__block">
            <h3 class="crabs-card__h">Готівкою</h3>
            <p class="crabs-card__p">Готівковий розрахунок доступний при самовивезенні в Івано-Франківську.</p>
          </div>

          <div class="crabs-card__paylogos">
            <!-- Підстав свої SVG/PNG -->
            <img src="/wp-content/uploads/2025/12/image-11.webp" alt="MonoPay"> 
            <img src="/wp-content/uploads/2025/12/image-16.webp" alt="Apple Pay"> 
            <img src="/wp-content/uploads/2025/12/image-13.webp" alt="WayForPay"> 
            <img src="/wp-content/uploads/2025/12/image-15.webp" alt="Google Pay"> 
          </div>
        </article>

      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
