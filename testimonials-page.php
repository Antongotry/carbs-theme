<?php
/**
* Template Name: Сторінка відгуків
*
* @package WordPress
* @subpackage Crabs Theme
* @since Crabs Theme 1.0
*/

get_header(); ?>

<div class="feedback-header container">
    <div class="feedback-header__left">
        <h1>Відгуки</h1>
        <a href="##" id="toggle-order">
            <span>Найновіші <svg
                  width="7"
                  height="5"
                  viewBox="0 0 7 5"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M7 3.79191e-08L3.5 5L0 0L7 3.79191e-08Z"
                    fill="#E93A53"
                  />
                </svg></span>
            
            <svg
                width="32"
                height="22"
                viewBox="0 0 32 22"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="new-icon"
              >
                <path
                  d="M5 1V20.5M5 20.5L1 17M5 20.5L9 17"
                  stroke="#E93A53"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  d="M12.5 2H30.5M12.5 6.5H27.0946M12.5 11H24.1757M12.5 15.5H20.7703M12.5 20H17.3649"
                  stroke="#3D3D3D"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
              </svg>
        </a>
    </div>
    <!-- <a href="##" class="btn-black">Залишити відгук</a> -->
</div>

<article class="feedback-body container" id="reviews-container">
    <?php
    $args = array(
        'post_type' => 'product_reviews',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => $order,
        'posts_per_page' => 5, // Количество отзывов на страницу
        'paged' => $page
    );

    $reviews = get_posts($args);

    if ($reviews) {
        foreach ($reviews as $review) {
            $first_name = get_the_title($review->ID);
            $rating = get_post_meta($review->ID, 'rating', true);
            $review_text = $review->post_content;
            $photo_urls = get_post_meta($review->ID, 'photos', true);
            $review_date = get_the_date('d.m.Y', $review->ID);
    
            // Вычисляем количество звезд
            $stars_html = '';
            for ($i = 1; $i <= 5; $i++) {
                $star_color = $i <= $rating ? '#F4A804' : 'grey';
                $stars_html .= '<svg class="star" width="36" height="33" viewBox="0 0 36 33" fill="'.$star_color.'" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 0L24.1365 9.55386L35.119 12.4377L27.929 21.2261L28.5801 32.5623L18 28.44L7.41987 32.5623L8.07097 21.2261L0.880983 12.4377L11.8635 9.55386L18 0Z"/>
                                </svg>';
            }
    
            // Выводим отзыв
            ?>
            <section class="feedback-card">
                <div class="feedback-card__main">
                    <div class="feedback-card__left">
                        <div class="feedback-card__autor">
                            <div class="feedback-card__avatar">
                                <img src="<?php echo $photo_urls ? $photo_urls[0] : get_stylesheet_directory_uri() . '/img/Лого-4.png'; ?>" alt="avatar" />
                            </div>
                            <h3><?php echo $first_name; ?></h3>
                        </div>
                        <div class="feedback-card__rating">
                            <div class="feedback-card__stars">
                                <?php echo $stars_html; ?>
                            </div>
                            <span><?php echo number_format($rating, 1); ?></span>
                        </div>
                    </div>
                    <div class="feedback-card__text">
                        <p><?php echo wp_trim_words($review_text, 20); ?></p>
                        <a href="<?php echo get_permalink($review->ID); ?>">Читати повністю</a>
                    </div>
                    <div class="feedback-card__right"><?php echo $review_date; ?></div>
                </div>
                <div class="feedback-card__product gallery-reviews">
                    <?php if (!empty($photo_urls)) : ?>
                        <?php foreach ($photo_urls as $photo_url) : ?>
                            <div class="feedback-card__image">
                                <a href="<?php echo $photo_url; ?>">
                                    <img src="<?php echo $photo_url; ?>" alt="product" />
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
            <?php
        }
    } else {
        echo '<p>Нет отзывов</p>';
    }
    ?>

</article>

<div class="more container" id="more-reviews">
    <a href="##">
    Більше відгуків
    <svg
        width="20"
        height="11"
        viewBox="0 0 20 11"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
    >
        <path
        d="M0.999321 1L9.69497 9.69565L18.3906 1"
        stroke="#E93A53"
        stroke-width="1.5"
        stroke-linecap="round"
        stroke-linejoin="round"
        />
    </svg>
    </a>
</div>

<?php
get_footer();