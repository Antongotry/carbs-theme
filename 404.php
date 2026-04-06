<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package storefront
 */

get_header(); ?>

<section class="outcome outcome--error">
                    <div class="container">
                        <img
                            src="<?php echo get_stylesheet_directory_uri(); ?>/img/icons/error.svg"
                            alt="error"
                            class="outcome__img"
                        />
                        <h2 class="title outcome__title">
                            Упс, щось пішло не так. На жаль, такої сторінки не існує.
                        </h2>
                        <p class="outcome__text">
                            Вибачте, але даної сторінки не існує або вона була
                            видалена. Будь ласка, скористайтесь навігацією в меню або
                            перейдіть на головну сторінку.
                        </p>
                        <a href="/" class="btn outcome__btn">Повернутися на головну</a>
                    </div>
                </section>

<?php
get_footer();
