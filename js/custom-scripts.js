// Логин
document.addEventListener('DOMContentLoaded', function() {
	const closeModal = document.querySelector('#closeLogin');
	const modal = document.querySelector('#login');
	const body = document.querySelector('body');

	if (closeModal && modal) {
		closeModal.addEventListener('click', function() {
			modal.classList.remove('open');
			body.classList.remove('lock');
		});
	}

	const openModalButtons = document.querySelectorAll('[data-open="login"]');
	openModalButtons.forEach(button => {
		button.addEventListener('click', function() {
			modal.classList.add('open');
			body.classList.add('lock');
		});
	});
});

// Smooth scroll anchor
document.querySelectorAll('#masthead a[href*="#"]').forEach(anchor => {
	anchor.addEventListener('click', function(e) {
		const currentPage = window.location.pathname;
		const anchorPage = this.pathname;

		if (currentPage === anchorPage) {
			e.preventDefault();

			const targetId = this.getAttribute('href').split('#')[1];
			const target = document.getElementById(targetId);

			if (target) {
				window.scrollTo({
					top: target.offsetTop,
					behavior: 'smooth'
				});
			}
		}
	});
});



// Фильтр
document.addEventListener('DOMContentLoaded', function() {
	const select = document.querySelector('.select');
	const selectCurrent = document.querySelector('.select-current');
	const options = document.querySelectorAll('.option');
	const orderbySelect = document.querySelector('.orderby');

	if (!select || !selectCurrent || !options.length || !orderbySelect) {
		return; // Если элементы не найдены, выходим
	}

	// Устанавливаем текущее значение при загрузке страницы
	const currentOrderby = orderbySelect.value;
	const currentOption = document.querySelector(`.option[data-value="${currentOrderby}"]`);
	if (currentOption) {
		selectCurrent.textContent = currentOption.textContent;
	}

	select.addEventListener('click', function() {
		const optionBody = document.querySelector('.option-body');
		if (optionBody) {
			optionBody.style.display = optionBody.style.display === 'block' ? 'none' : 'block';
		}
	});

	options.forEach(option => {
		option.addEventListener('click', function() {
			const value = this.getAttribute('data-value');
			if (value) {
				selectCurrent.textContent = this.textContent;
				orderbySelect.value = value;
				orderbySelect.form.submit();
				const optionBody = document.querySelector('.option-body');
				if (optionBody) {
					optionBody.style.display = 'none';
				}
			}
		});
	});

	document.addEventListener('click', function(event) {
		if (!select.contains(event.target)) {
			const optionBody = document.querySelector('.option-body');
			if (optionBody) {
				optionBody.style.display = 'none';
			}
		}
	});
});


jQuery(document).ready(function($) {
	var ajaxUrl = load_more_params.ajaxurl;
	var currentPage = load_more_params.current_page;
	var maxPages = load_more_params.max_page;
	var currentCategory = load_more_params.current_category;

	$('#load-more').on('click', function(e) {
		e.preventDefault();
		var button = $(this);
		var orderby = $('select[name="orderby"]').val();  // Получаем выбранный параметр сортировки
		button.addClass('loading');

		var filterParams = window.location.search.substring(1);

		var data = {
			action: 'load_more_products',
			page: currentPage,
			orderby: orderby,
			filters: filterParams,
		};

		if (currentCategory) {
			data.category = currentCategory;  // Передаем категорию в запрос, если она есть
		}

		if (currentPage < maxPages) {
			$.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: data,
				success: function(response) {
					//console.log('AJAX Response:', response);
					if (response.success) {
						$('#catalog-body').append(response.data);
						currentPage++;

						// Если достигнут предел страниц или больше товаров нет, скрываем кнопку
						if (currentPage >= maxPages) {
							button.hide();  // Скрыть кнопку "Завантажити більше"
						}
					} else {
						button.text('Товарів не знайдено');
						button.hide();  // Скрываем кнопку при отсутствии товаров
					}
					button.removeClass('loading');
				},
				error: function() {
					button.text('Товарів не знайдено');
					button.removeClass('loading');
					button.hide();  // Скрываем кнопку в случае ошибки
				}
			});
		} else {
			button.hide();  // Скрываем кнопку, если больше страниц нет
		}
	});
	initializeOrDestroyAllRelatedSwipers();

	var resizeTimerRelated;
	jQuery(window).on('resize', function() {
		clearTimeout(resizeTimerRelated);
		resizeTimerRelated = setTimeout(function() {
			initializeOrDestroyAllRelatedSwipers();
		}, 250);
	});
});




// Фильтрация в сайдбаре
document.addEventListener("DOMContentLoaded", function() {
	const sidebar = document.querySelector("#sidebar");

	if (sidebar) {
		function initializeFilterCollapsibles() {
			const filterCollapsibles = sidebar.querySelectorAll(".wpc-filter-collapsible");

			filterCollapsibles.forEach(function(collapsible) {
				const filterContent = collapsible.querySelector(".wpc-filter-content");

				// Устанавливаем начальную высоту в зависимости от класса
				if (collapsible.classList.contains("wpc-opened")) {
					filterContent.style.height = filterContent.scrollHeight + "px";
				} else {
					filterContent.style.height = "0";
				}

				// Функция для плавного открытия и закрытия
				function updateHeight() {
					if (collapsible.classList.contains("wpc-opened")) {
						filterContent.style.height = filterContent.scrollHeight + "px";
						filterContent.addEventListener("transitionend", function handleTransitionEnd() {
							if (collapsible.classList.contains("wpc-opened")) {
								filterContent.style.height = "auto";
							}
							filterContent.removeEventListener("transitionend", handleTransitionEnd);
						});
					} else {
						filterContent.style.height = filterContent.scrollHeight + "px"; // Устанавливаем текущую высоту
						requestAnimationFrame(() => {
							filterContent.style.height = "0"; // Переход к высоте 0
						});
					}
				}

				// Добавляем наблюдатель за изменениями класса
				const observer = new MutationObserver(updateHeight);
				observer.observe(collapsible, { attributes: true, attributeFilter: ['class'] });
			});
		}

		// Инициализируем фильтры при загрузке страницы
		initializeFilterCollapsibles();

		// Наблюдаем за изменениями в контейнере сайдбара
		const sidebarObserver = new MutationObserver(function(mutations) {
			mutations.forEach(function(mutation) {
				if (mutation.type === "childList") {
					initializeFilterCollapsibles();
				}
			});
		});

		sidebarObserver.observe(sidebar, { childList: true, subtree: true });
	}
});

// Инициализация галлереи на странице продукта
window.addEventListener("load", function () {
	const cardMain = document.querySelector(".card-main");

	if(cardMain){
		baguetteBox.run(".gallery");
	}
});

// Инициализация галлереи в отзывах
window.addEventListener("load", function () {
	baguetteBox.run(".gallery-reviews");
});


// Отзывы
jQuery(document).ready(function($) {
	if ($('#reviews-container').length) {
		var page = 1; // Стартовая страница
		var order = 'desc'; // Стартовый порядок сортировки

		function loadReviews(page, append = false) {
			$.ajax({
				url: load_more_params.ajaxurl,
				type: 'POST',
				data: {
					action: 'load_more_reviews',
					page: page,
					order: order
				},
				success: function(response) {
					if(response.success) {
						if (append) {
							$('#reviews-container').append(response.html); // Добавление отзывов
						} else {
							$('#reviews-container').html(response.html); // Замена отзывов
						}
						if (!response.has_more) {
							$('#more-reviews').hide(); // Скрытие кнопки, если больше отзывов нет
						} else {
							$('#more-reviews').show(); // Показывать кнопку, если есть еще отзывы
						}
						baguetteBox.run('.gallery-reviews'); // Переинициализация baguetteBox
					} 
				}
			});
		}

		function updateButtonText() {
			var buttonText = order === 'desc' ? 'Найновіші' : 'Найстаріші';
			$('#toggle-order span').contents().first().replaceWith(buttonText + ' ');
		}

		$('#toggle-order').on('click', function(e) {
			e.preventDefault();
			order = order === 'desc' ? 'asc' : 'desc';
			page = 1;
			updateButtonText();
			loadReviews(page);
		});

		$('#more-reviews a').on('click', function(e) {
			e.preventDefault();
			page++;
			loadReviews(page, true);
		});

		// Инициализация
		updateButtonText();
		loadReviews(page);
	}
});



// Main


jQuery(document).ready(function($) {
	// Main Slider v1 - Start
	var mainSliderv1 = new Swiper('.main-slider-v1', {
		slidesPerView: 1,
		spaceBetween: 0,
		// autoHeight: true,
		loop: true,
		centeredSlides: true,
		lazy: {
			loadPrevNext: true,
		},
		autoplay: {
			delay: 5000,
			disableOnInteraction: false,
		},
		navigation: {
			nextEl: ".button-next-1",
			prevEl: ".button-prev-1",
		},
		pagination: {
			el: ".swiper-pagination",
			clickable: true
		},
	});
// Зміна фонів для різних розмірів екрану
function updateSlideImages() {
    $('.main-slider-v1 .swiper-slide .slide-bg-img').each(function() {
      var $img = $(this);
      var desktop = $img.data('desktop');
      var note = $img.data('note');
      var mob = $img.data('mob');

      var width = $(window).width();
      if (width < 769 && mob) {
        $img.attr('src', mob);
      } else if (width >= 769 && width < 1600 && note) {
        $img.attr('src', note);
      } else {
        $img.attr('src', desktop);
      }
    });
  }

// Виклик при завантаженні
updateSlideImages();

// Виклик при ресайзі
$(window).on('resize', function () {
  updateSlideImages();
});

	// Main Slider v1 - End
	// Функция для инициализации слайдера
	function initSwiper() {
		swiper = new Swiper('.cart-slider__gallary', {
			// Ваши настройки Swiper
			loop: true,
			slidesPerView: 1,
			spaceBetween: 16,
			navigation: {
				nextEl: '.cart-btn-next',
				prevEl: '.cart-btn-prev',
			},
			breakpoints: {
				640: {
					slidesPerView: 2,
					spaceBetween: 16,
				},
				768: {
					slidesPerView: 3,
					spaceBetween: 16,
				},
				1024: {
					slidesPerView: 6,
					spaceBetween: 16,
				},
			},
		});
	}

	// Функция для обновления числа товаров в корзине
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
				initSwiper();
				$('#mini-cart-spinner').hide(); // Скрываем спиннер после обновления
			}
		});
	}

	// Обработчик на случай возврата назад в браузере
	window.addEventListener("pageshow", function(event) {
		if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
			// Если страница загружена из кэша, обновляем количество товаров в корзине
			updateCartCount();
		}
	});


	// Функция для обновления содержимого корзины
	function updateCartContent(fragments) {
		$('#constructor-cart-content').html(fragments['div.widget_shopping_cart_content']);
		$('#cart-content').html(fragments['div.widget_shopping_cart_content']);
		initSwiper();
		$('#mini-cart-spinner').hide(); // Скрываем спиннер после обновления
	}

	// Показать спиннер
	function showSpinner() {
		$('#mini-cart-spinner').show();
	}

	// Обработчик клика по кнопке добавления в корзину для конструктора
	$('body').on('click', '.add-to-cart-button', function(e) {
		e.preventDefault();

		var product_id = $(this).data('product_id');
		var quantity = 1; // или другой способ получения количества

		showSpinner(); // Показать спиннер

		$.ajax({
			type: 'POST',
			url: wc_add_to_cart_params.ajax_url,
			data: {
				action: 'add_to_cart',
				product_id: product_id,
				quantity: quantity
			},
			success: function(response) {
				if (!response.error) {
					// Обновляем содержимое корзины
					updateCartContent(response.fragments);

					// Обновляем число товаров в корзине
					updateCartCount();
					baguetteBox.run('#cart-wrapper');
					// $('#cart-wrapper').addClass('open');
					// $('body').addClass('lock');
				} else {
					alert('Ошибка при добавлении товара в корзину.');
					$('#mini-cart-spinner').hide(); // Скрываем спиннер в случае ошибки
				}
			}
		});
	});

	// Обработчик выбора атрибутов
	$('.shortstory__card').on('click', function(e) {
		e.preventDefault();
		var $this = $(this);
		var attributeName = $this.find('h4').text();
		var attributeType = $this.closest('.shortstory__colection').attr('id').includes('color') ? 'color' : 'collection';

		// Устанавливаем выбранный атрибут
		if(attributeType === 'color') {
			$('.attrs').attr('data-color', attributeName);
		} else {
			$('.attrs').attr('data-collection', attributeName);
		}

		// Обновляем стили активного состояния
		$this.closest('.shortstory__row').find('.shortstory__card').removeClass('active');
		$this.addClass('active');

		// Проверяем, выбраны ли оба атрибута, и активируем кнопку
		updateAddToCartButtonState();
	});

	// Функция для проверки наличия выбранных атрибутов и обновления состояния кнопки добавления в корзину
	function updateAddToCartButtonState() {
		const colorSelected = $('.attrs').attr('data-color') !== "";
		const collectionSelected = $('.attrs').attr('data-collection') !== "";

		const addToCartButton = $('.shortstory__btn-black.attrs');
		const addClasses = addToCartButton.data('add-classes');

		if (colorSelected && collectionSelected) {
			addToCartButton.removeClass('disabled');
			addToCartButton.attr('href', addToCartButton.data('url'));
			if (addClasses) {
				addClasses.split(' ').forEach(className => addToCartButton.addClass(className));
			}
		} else {
			addToCartButton.addClass('disabled');
			addToCartButton.attr('href', '#');
			if (addClasses) {
				addClasses.split(' ').forEach(className => addToCartButton.removeClass(className));
			}
		}
	}

	// Добавление в корзину товаров с атрибутами цвета и коллекции
	$('.shortstory__btn-black.attrs').on('click', function(e) {
		e.preventDefault();

		// Получаем данные из кастомных кнопок
		let colorSelected = $('.attrs').attr('data-color');
		let collectionSelected = $('.attrs').attr('data-collection');


		// Преобразуем значения в формат, который используется в селектах (нижний регистр, дефисы вместо пробелов)
		colorSelected = colorSelected.toLowerCase().replace(/\s+/g, '-');
		collectionSelected = collectionSelected.toLowerCase().replace(/\s+/g, '-');


		// Найдём стандартные селекты на странице
		const colorSelect = $('select[name="attribute_pa_kolory-v-naiavnosti"]');
		const collectionSelect = $('select[name="attribute_pa_kolektsiia"]');


		// Проверяем, что оба атрибута выбраны
		if (!colorSelected || !collectionSelected) {
			alert('Пожалуйста, выберите цвет и коллекцию.');
			return;
		}

		// Устанавливаем выбранное значение в селект цвета, если оно есть
		if (colorSelect.length > 0 && colorSelect.find('option[value="' + colorSelected + '"]').length > 0) {
			colorSelect.val(colorSelected).trigger('change');
		} 

		// Устанавливаем выбранное значение в селект коллекции, если оно есть
		if (collectionSelect.length > 0 && collectionSelect.find('option[value="' + collectionSelected + '"]').length > 0) {
			collectionSelect.val(collectionSelected).trigger('change');
		} 

		// Отправляем AJAX запрос для добавления товара в корзину
		var product_id = $('input[name="product_id"]').val();
		var variation_id = $('input[name="variation_id"]').val(); // Убедитесь, что variation_id передается правильно
		var quantity = 1; // Можно сделать динамическим, если требуется

		showSpinner(); // Показать спиннер

		$.ajax({
			type: 'POST',
			url: wc_add_to_cart_params.ajax_url,
			data: {
				action: 'woocommerce_add_to_cart_variable_rc',
				product_id: product_id,
				variation_id: variation_id,
				quantity: quantity,
				variation: {
					'attribute_pa_kolory-v-naiavnosti': colorSelected,
					'attribute_pa_kolektsiia': collectionSelected
				}
			},
			success: function(response) {
				if (!response.error) {
					// Обновляем содержимое корзины
					updateCartContent(response.fragments);

					// Обновляем число товаров в корзине
					updateCartCount();
					baguetteBox.run('#cart-wrapper');
					// $('#cart-wrapper').addClass('open');
					// $('body').addClass('lock');
				} else {
					alert('Ошибка при добавлении товара в корзину.');
					$('#mini-cart-spinner').hide(); // Скрываем спиннер в случае ошибки
				}
			}
		});
	});


	// Открытие попапа и добавление товара в корзину через AJAX для всех остальных кнопок
	$('body').on('click', '.add_to_cart_button:not(.shortstory-attrs .add_to_cart_button)', function(e) {
		e.preventDefault();

		var product_id = $(this).data('product_id');
		var quantity = 1; // или другой способ получения количества

		showSpinner(); // Показать спиннер

		$.ajax({
			type: 'POST',
			url: wc_add_to_cart_params.ajax_url,
			data: {
				action: 'add_to_cart',
				product_id: product_id,
				quantity: quantity
			},
			success: function(response) {
				if (!response.error) {
					// Обновляем содержимое корзины в попапе
					updateCartContent(response.fragments);

					// Обновляем число товаров в корзине
					updateCartCount();

					// Показываем попап
					baguetteBox.run('#cart-wrapper');
					// $('#cart-wrapper').addClass('open');
					// $('body').addClass('lock');

				} else {
					alert('Ошибка при добавлении товара в корзину.');
					$('#mini-cart-spinner').hide(); // Скрываем спиннер в случае ошибки
				}

			}
		});
	});

	// Обновление количества товаров в корзине через AJAX
	$('body').on('click', '.quantity-controls .plus, .quantity-controls .minus', function(e) {
		e.preventDefault();

		var $this = $(this);
		var $input = $this.siblings('input');
		var quantity = parseInt($input.val());
		var cart_item_key = $this.data('cart_item_key');
		var product_id = $this.data('product_id');

		if ($this.hasClass('plus')) {
			quantity++;
		} else {
			quantity = quantity > 1 ? quantity - 1 : 1;
		}

		showSpinner(); // Показать спиннер

		$.ajax({
			type: 'POST',
			url: wc_add_to_cart_params.ajax_url,
			data: {
				action: 'update_cart_item',
				cart_item_key: cart_item_key,
				quantity: quantity,
				product_id: product_id
			},
			success: function(response) {
				if (!response.error) {
					// Обновляем содержимое корзины
					updateCartContent(response.fragments);

					// Обновляем число товаров в корзине
					updateCartCount();
				} else {
					alert('Ошибка при обновлении корзины.');
					$('#mini-cart-spinner').hide(); // Скрываем спиннер в случае ошибки
				}
			}
		});
	});


// 	$('.open-popup').on('click', function(e) {
// 		e.preventDefault();
// 		$('#showroom').addClass('open');
// 	});

// 	$('.close-popup').on('click', function(e) {
// 		e.preventDefault();
// 		$('#showroom').removeClass('open');
// 	});

jQuery(function($) {
  $('.open-popup').on('click', function(e) {
    e.preventDefault();
    const popup = $('#showroom');
    popup.addClass('open');

    const sku = $(this).data('sku'); // SKU можна передати через data-атрибут у кнопці
    const stockContainer = popup.find('.showroom_availability');

    if (!sku) {
      stockContainer.text('Немає SKU для перевірки.');
      return;
    }

    stockContainer.text('Перевіряємо наявність...');

    $.ajax({
      url: '/wp-admin/admin-ajax.php',
      data: {
        action: 'get_product_stock',
        sku: sku
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          stockContainer.html(response.data.status);
        } else {
          stockContainer.html('Помилка: ' + response.data.message);
        }
      },
      error: function() {
        stockContainer.html('❌ Помилка при запиті.');
      }
    });
  });

  $('.close-popup').on('click', function(e) {
    e.preventDefault();
    $('#showroom').removeClass('open');
  });
});



	// Закрытие попапа
	$('#cart-wrapper').on('click', '#closeCart, .back-to-shop', function() {
		$('#cart-wrapper').removeClass('open');
		$('body').removeClass('lock');

	});

	// Открытие попапа при клике на кнопку корзины в хедере
	$('#btnCartHeader').on('click', function(e) {
		e.preventDefault();
		$('#cart-wrapper').addClass('open');
		$('body').addClass('lock');

		// Логирование AJAX URL
		console.log('AJAX URL:', wc_add_to_cart_params.ajax_url);

		showSpinner(); // Показать спиннер

		// Выполняем AJAX-запрос для получения содержимого корзины
		$.ajax({
			type: 'POST',
			url: wc_add_to_cart_params.ajax_url,  // Используем этот URL для отправки запроса
			data: {
				action: 'get_refreshed_fragments'
			},
			success: function(response) {
				console.log('Ответ сервера:', response);
				if (!response.error && response.fragments) {
					updateCartContent(response.fragments);
				} else {
					console.error('Ошибка при загрузке содержимого корзины.', response);
				}
			},
			error: function(xhr, status, error) {
				console.error('Ошибка AJAX-запроса:', status, error);
			},
			complete: function() {
				$('#mini-cart-spinner').hide();
			}
		});
	});


	// Инициализация обновления числа товаров в корзине при загрузке страницы
	updateCartCount();

	// Обновление количества товаров в корзине при удалении товаров
	$('body').on('click', '.remove', function(e) {
		e.preventDefault();
		var $this = $(this);

		showSpinner(); // Показать спиннер

		$.ajax({
			type: 'POST',
			url: $this.attr('href'),
			success: function(response) {
				// Обновляем содержимое корзины в попапе
				updateCartContent($(response).find('#cart-content').html());

				// Обновляем число товаров в корзине
				updateCartCount();
			}
		});
	});

	// Изначально делаем кнопку неактивной
	$('.shortstory-attrs a').addClass('disabled');

	// Обновляем состояние кнопки при изменении атрибутов
	updateAddToCartButtonState();
});


jQuery(document).ready(function($) {
	// Обновление количества товаров в корзине
	function updateCartCount() {
		let cartCount = $('.widget_shopping_cart_content').find('.woocommerce-mini-cart-item').length;
		$('.cart-count').text(cartCount); // Обновляем количество в хедере
	}

	// Отслеживаем событие обновления фрагментов WooCommerce
	$(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function() {
		updateCartCount();
	});

	// Инициализация обновления числа товаров в корзине при загрузке страницы
	updateCartCount();
});



jQuery(document).ready(function($) {

	function initSwiper() {
		swiper = new Swiper('.gallery-view', {
			loop: true,
			thumbs: {
				swiper: {
					el: ".gallery-view-mini",
					slidesPerView: 5,
					spaceBetween: 15,
					direction: "vertical",
				},
			},
			navigation: {
				nextEl: ".gallery-view-btn-next",
				prevEl: ".gallery-view-btn-prev",
			},
		});
	}

	// Функция для обработки кнопки "Читати більше..."
	function attachReadMoreHandler() {
		$('body').on('click', '.read-more-button', function(e) {
			e.preventDefault();
			var shortDescriptionBody = $(this).closest('.short-description').find('.short-description__body');

			if (shortDescriptionBody.css('max-height') === '120px') {
				//shortDescriptionBody.css('max-height', shortDescriptionBody[0].scrollHeight + 'px');
				shortDescriptionBody.addClass('open'); // Добавляем класс open
				$(this).text('Сховати');
			} else {
				shortDescriptionBody.css('max-height', '120px');
				shortDescriptionBody.removeClass('open'); // Убираем класс open
				$(this).text('Читати більше...');
			}
		});
	}

	// Открытие попапа быстрого просмотра
	$('body').on('click', '.view-product-button', function(e) {
		e.preventDefault();

		var productId = $(this).data('product_id');

		$.ajax({
			type: 'POST',
			url: wc_add_to_cart_params.ajax_url,
			data: {
				action: 'get_quick_view_content',
				product_id: productId
			},
			success: function(response) {
				// Подгружаем контент в попап
				$('#popup-qv-content').html(response);
				$('#quickView').addClass('open');

				// Инициализация слайдера
				initSwiper();

				// Добавляем обработчик для кнопки "Читати більше..."
				attachReadMoreHandler();
			},
		});
	});

	// Закрытие попапа
	$('body').on('click', '#closeQuickView', function(e) {
		e.preventDefault();
		$('#quickView').removeClass('open');
	});

});


// Добавляем класс при смене состояния чекбокса и радиокнопки
document.addEventListener('DOMContentLoaded', function() {
	const updateRadioStyles = function() {
		const radioButtons = document.querySelectorAll('input[type="radio"][name="model"]');

		radioButtons.forEach(radio => {
			const wrapper = radio.closest('.wpc-term-item-content-wrapper');
			if (!wrapper) return; // Проверяем наличие элемента обертки

			// Добавляем обработчик события изменения
			radio.addEventListener('change', function() {
				// Сначала убираем класс 'checked' со всех элементов
				radioButtons.forEach(r => {
					const closestWrapper = r.closest('.wpc-term-item-content-wrapper');
					if (closestWrapper) {
						closestWrapper.classList.remove('checked');
					}
				});

				// Добавляем класс 'checked' к выбранной радиокнопке
				if (radio.checked) {
					wrapper.classList.add('checked');
				}
			});

			// Проверка состояния радиокнопки при загрузке страницы
			if (radio.checked) {
				wrapper.classList.add('checked');
			}
		});
	};

	updateRadioStyles();

	// Используем MutationObserver для отслеживания изменений в DOM
	const observer = new MutationObserver(function(mutations) {
		mutations.forEach(function(mutation) {
			if (mutation.type === 'childList') {
				updateRadioStyles(); // Обновляем стили при изменениях в DOM
			}
		});
	});

	// Наблюдаем за документом на предмет добавления/удаления дочерних элементов
	observer.observe(document.body, {
		childList: true,
		subtree: true
	});


});






// Переинициализация слайдера на странице конструктора выбор цвета в карточке товара

function initializeSwiper() {
	jQuery('.cons__gallary').each(function(index, element) {
		var swiper = new Swiper(element, {
			slidesPerView: 4,
			spaceBetween: 11,
			loop: true,
			navigation: {
				nextEl: jQuery(element).closest('.product-item').find('.builder-btn-next')[0],
				prevEl: jQuery(element).closest('.product-item').find('.builder-btn-prev')[0],
			},
		});
	});
}

// Initialize Swiper on document ready
jQuery(document).ready(function() {
	initializeSwiper();
});

// Re-initialize Swiper after AJAX content load
jQuery(document).on('ready', function() {
	initializeSwiper();
});

// Calculate and display 10% of the original amount
jQuery(document).ready(function($) {
	var originalAmountText = $('.orders__bottom-option .o_price .woocommerce-Price-amount bdi').text();
	var originalAmount = parseFloat(originalAmountText.replace(/\s/g, '').replace('₴', '').replace(',', '.'));

	$('#payment-percentage input[type="radio"]').on('change', function() {
		if ($(this).is(':checked')) {
			var tenPercentAmount = originalAmount * 0.10; // Calculate 10% of the original amount
			$('.orders__bottom-option .o_price .woocommerce-Price-amount bdi').text(tenPercentAmount.toString().replace('.', ',') + ' ₴');
		} else {
			$('.orders__bottom-option .o_price .woocommerce-Price-amount bdi').text(originalAmountText);
		}
	});
});


// Добавляем кнопку гугл авторизации на странице чекаут
document.addEventListener('DOMContentLoaded', function() {
	const ordersButtons = document.querySelector('.orders__buttons');

	if (ordersButtons) {
		const newCustomerButton = ordersButtons.querySelector('.newCustomer');
		const existingCustomerButton = ordersButtons.querySelector('.existingCustomer');
		const nslContainer = document.querySelector('.registration-form');

		if (newCustomerButton && existingCustomerButton && nslContainer) {
			existingCustomerButton.addEventListener('click', function() {
				nslContainer.classList.add('activeLoginForm');
			});

			newCustomerButton.addEventListener('click', function() {
				nslContainer.classList.remove('activeLoginForm');
			});
		}
	}
});

// Форма входа на странице оформления заказа

document.addEventListener('DOMContentLoaded', function() {
	const customLoginSubmitButton = document.getElementById('custom-login-submit');
	const loginErrors = document.getElementById('login-errors');


	if (customLoginSubmitButton) {
		customLoginSubmitButton.addEventListener('click', function(event) {
			event.preventDefault(); // Останавливаем стандартное поведение кнопки

			const email = document.getElementById('custom_email').value;
			const password = document.getElementById('custom_password').value;

			if (!email || !password) {
				loginErrors.textContent = 'Please fill in both fields.';
				return;
			}

			// Заполняем скрытую форму WooCommerce
			document.getElementById('username').value = email;
			document.getElementById('password').value = password;

			// Нажимаем на сабмит скрытой формы
			document.querySelector('.woocommerce-form-login .btn-red').click();
		});
	}
});

//Дублируем данные формы
jQuery(document).ready(function($) {
	var firstNameMain = $('#billing_first_name'); // Поле "Имя" в основной форме
	var lastNameMain = $('#billing_last_name'); // Поле "Фамилия" в основной форме
	var phoneMain = $('#billing_phone'); // Поле "Телефон" в основной форме
	var emailMain = $('#billing_email'); // Поле "E-mail" в основной форме

	var firstNameLu = $('input[name="billing_first_name_lu"]'); // Поле "Имя" в форме логина
	var lastNameLu = $('input[name="billing_last_name_lu"]'); // Поле "Фамилия" в форме логина
	var phoneLu = $('input[name="billing_phone_lu"]'); // Поле "Телефон" в форме логина
	var emailLu = $('input[name="billing_email_lu"]'); // Поле "E-mail" в форме логина

	// Функция для синхронизации значений полей на лету
	function syncFields(fromField, toField) {
		fromField.on('input', function() {
			toField.val($(this).val());
		});
	}

	// Связываем поля друг с другом
	syncFields(firstNameMain, firstNameLu);
	syncFields(lastNameMain, lastNameLu);
	syncFields(phoneMain, phoneLu);
	syncFields(emailMain, emailLu);

	// Также связываем в обратную сторону
	syncFields(firstNameLu, firstNameMain);
	syncFields(lastNameLu, lastNameMain);
	syncFields(phoneLu, phoneMain);
	syncFields(emailLu, emailMain);

	// Синхронизация перед сабмитом формы чекаута
	$('#place_order').on('click', function() {
		// Обновляем основную форму перед сабмитом
		firstNameMain.val(firstNameLu.val());
		lastNameMain.val(lastNameLu.val());
		phoneMain.val(phoneLu.val());
		emailMain.val(emailLu.val());
	});

});

// Конструктор допрацювання - Start
jQuery(document).ready(function($) {
	function updateCartCount() {
		var cartCount = $('#btnCartHeader #cart-count').text();
		$('.package-items span.now-nums').html(cartCount);

		if (cartCount == '0') {
			$('.package-box').hide();
		} else {
			$('.package-box').show();
		}
	}

	$(document).ready(function() {
		setTimeout(function () {
			updateCartCount();
		}, 500);
		var observer = new MutationObserver(function(mutations) {
			mutations.forEach(function(mutation) {
				if (mutation.type === "characterData" || mutation.type === "childList") {
					updateCartCount();
				}
			});
		});

		var target = document.querySelector('#btnCartHeader #cart-count');
		if (target) {
			observer.observe(target, { 
				characterData: true, 
				childList: true, 
				subtree: true 
			});
		}
	});

	if ($(window).width() < 1025) {
		var packageBox = $('.package-box');
		var scrollBox = $('.scroll-box');
		packageBox.hide();

		$(window).scroll(function() {
			if ($(this).scrollTop() > 500) {
				packageBox.fadeIn();
			} else {
				packageBox.fadeOut();
			}
		});
		$(window).scroll(function() {
			if ($(this).scrollTop() > 1000) {
				scrollBox.fadeIn();
			} else {
				scrollBox.fadeOut();
			}
		});
	}

	$(document).on('click', '.btn-black.open-cart', function () {
		$('#btnCartHeader').click();
	});

	$(document).on('click', '.wpc-filters-section-445 li', function(e) {
		if (!$(e.target).is('a') && !$(e.target).is('input')) {
			e.preventDefault();
			var link = $(this).find('a');
			if (link.length) {
				window.location.href = link.attr('href');
			}
		}
	});

	// Обработчик для кнопки "Вибір моделі"
	$('.scroll-model').on('click', function(e) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: $('.wpc-filters-section-445').offset().top - 80
		}, 1000);
	});

	// Обработчик для кнопки "Вибір категорії"
	$('.scroll-category').on('click', function(e) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: $('.wpc-filters-section-446').offset().top - 60
		}, 1000);
	});


});
// Конструктор допрацювання - End

// Side Меню - Start
jQuery(document).ready(function($) {
	$('.open-login-pop-up').on('click', function () {
		$('#login').toggleClass('open');
	});
	$('.header__catalogue').on('click', function () {
		$('.menu-header-wrapper').toggleClass('active');
		document.body.classList.toggle('menu-open');
		$(this).toggleClass('active');
	});
	$('.menu-header-background, .side-menu-close').on('click', function () {
		$('.menu-header-wrapper').removeClass('active');
		document.body.classList.toggle('menu-open');
		$('.side-menu-call').removeClass('active');
	});
	$('.menu-item').on('click', function () {
	    $('.menu-header-wrapper').removeClass('active');
	    document.body.classList.remove('menu-open');
	})

	$('.category-header').click(function(e) {
		e.stopPropagation(); // Предотвращаем всплытие события

		var $categoryItem = $(this).closest('.category-item');
		var $subcategoryList = $categoryItem.children('.subcategory-list');

		// Закрываем все открытые подкатегории того же уровня
		$categoryItem.siblings('.category-item.active').removeClass('active')
			.children('.subcategory-list').slideUp(300)
			.closest('.category-item').find('.category-arrow').css('transform', 'rotate(90deg)');

		$categoryItem.toggleClass('active');

		if ($categoryItem.hasClass('active')) {
			$subcategoryList.slideDown(300);
			$(this).find('.category-arrow').css('transform', 'rotate(0deg)');
		} else {
			$subcategoryList.slideUp(300);
			$(this).find('.category-arrow').css('transform', 'rotate(90deg)');

			// Закрываем все вложенные активные категории
			$categoryItem.find('.category-item.active').removeClass('active')
				.children('.subcategory-list').slideUp(300)
				.closest('.category-item').find('.category-arrow').css('transform', 'rotate(90deg)');
		}

	});
});
// Side Меню - End


// Обработка событий на страницах - Start
jQuery(document).ready(function ($) {
	//Активация формы лайтбокса
	setTimeout(function () {
		$('.form-lightbox-wrapper').addClass('ready');
	}, 500);
	$('.call-back-call').on('click', function () {
		$('.form-lightbox-wrapper.call-back-call').addClass('active');
	});
	$('.lightbox-background').on('click', function () {
		$(this).closest('.form-lightbox-wrapper').removeClass('active');
	});
	$('.close-lightbox').on('click', function () {
		$(this).closest('.form-lightbox-wrapper').removeClass('active');
	});
	
});
// Обработка событий на страницах - End

// Обработка событий на страницах - Start
jQuery(document).ready(function ($) {
	//Активация формы лайтбокса
	setTimeout(function () {
		$('.form-lightbox-wrapper').addClass('ready');
	}, 500);
	$('.one-click_btn').on('click', function () {
		$('.form-lightbox-wrapper.one-click').addClass('active');
	});
	$('.one-click .lightbox-background').on('click', function () {
		$(this).closest('.one-click .form-lightbox-wrapper').removeClass('active');
	});
	$('.one-click .close-lightbox').on('click', function () {
		$(this).closest('.one-click .form-lightbox-wrapper').removeClass('active');
	});
});
// Обработка событий на страницах - End


// Обработка событий на страницах - Start
jQuery(document).ready(function ($) {
	//Активация формы лайтбокса
	setTimeout(function () {
		$('.form-lightbox-wrapper').addClass('ready');
	}, 500);
	$('.call-back-call').on('click', function () {
		$('.form-lightbox-wrapper.call-back').addClass('active');
	});
	$('.lightbox-background').on('click', function () {
		$(this).closest('.form-lightbox-wrapper').removeClass('active');
	});
	$('.close-lightbox').on('click', function () {
		$(this).closest('.form-lightbox-wrapper').removeClass('active');
	});
});
// Обработка событий на страницах - End

// Валідація Форм - Start
// jQuery Mask Plugin - Start
jQuery(document).ready(function ($) {
	var $jscomp=$jscomp||{};$jscomp.scope={};$jscomp.findInternal=function(a,n,f){a instanceof String&&(a=String(a));for(var p=a.length,k=0;k<p;k++){var b=a[k];if(n.call(f,b,k,a))return{i:k,v:b}}return{i:-1,v:void 0}};$jscomp.ASSUME_ES5=!1;$jscomp.ASSUME_NO_NATIVE_MAP=!1;$jscomp.ASSUME_NO_NATIVE_SET=!1;$jscomp.SIMPLE_FROUND_POLYFILL=!1;
	$jscomp.defineProperty=$jscomp.ASSUME_ES5||"function"==typeof Object.defineProperties?Object.defineProperty:function(a,n,f){a!=Array.prototype&&a!=Object.prototype&&(a[n]=f.value)};$jscomp.getGlobal=function(a){return"undefined"!=typeof window&&window===a?a:"undefined"!=typeof global&&null!=global?global:a};$jscomp.global=$jscomp.getGlobal(this);
	$jscomp.polyfill=function(a,n,f,p){if(n){f=$jscomp.global;a=a.split(".");for(p=0;p<a.length-1;p++){var k=a[p];k in f||(f[k]={});f=f[k]}a=a[a.length-1];p=f[a];n=n(p);n!=p&&null!=n&&$jscomp.defineProperty(f,a,{configurable:!0,writable:!0,value:n})}};$jscomp.polyfill("Array.prototype.find",function(a){return a?a:function(a,f){return $jscomp.findInternal(this,a,f).v}},"es6","es3");
	(function(a,n,f){"function"===typeof define&&define.amd?define(["jquery"],a):"object"===typeof exports&&"undefined"===typeof Meteor?module.exports=a(require("jquery")):a(n||f)})(function(a){var n=function(b,d,e){var c={invalid:[],getCaret:function(){try{var a=0,r=b.get(0),h=document.selection,d=r.selectionStart;if(h&&-1===navigator.appVersion.indexOf("MSIE 10")){var e=h.createRange();e.moveStart("character",-c.val().length);a=e.text.length}else if(d||"0"===d)a=d;return a}catch(C){}},setCaret:function(a){try{if(b.is(":focus")){var c=
		b.get(0);if(c.setSelectionRange)c.setSelectionRange(a,a);else{var g=c.createTextRange();g.collapse(!0);g.moveEnd("character",a);g.moveStart("character",a);g.select()}}}catch(B){}},events:function(){b.on("keydown.mask",function(a){b.data("mask-keycode",a.keyCode||a.which);b.data("mask-previus-value",b.val());b.data("mask-previus-caret-pos",c.getCaret());c.maskDigitPosMapOld=c.maskDigitPosMap}).on(a.jMaskGlobals.useInput?"input.mask":"keyup.mask",c.behaviour).on("paste.mask drop.mask",function(){setTimeout(function(){b.keydown().keyup()},
    100)}).on("change.mask",function(){b.data("changed",!0)}).on("blur.mask",function(){f===c.val()||b.data("changed")||b.trigger("change");b.data("changed",!1)}).on("blur.mask",function(){f=c.val()}).on("focus.mask",function(b){!0===e.selectOnFocus&&a(b.target).select()}).on("focusout.mask",function(){e.clearIfNotMatch&&!k.test(c.val())&&c.val("")})},getRegexMask:function(){for(var a=[],b,c,e,t,f=0;f<d.length;f++)(b=l.translation[d.charAt(f)])?(c=b.pattern.toString().replace(/.{1}$|^.{1}/g,""),e=b.optional,
    (b=b.recursive)?(a.push(d.charAt(f)),t={digit:d.charAt(f),pattern:c}):a.push(e||b?c+"?":c)):a.push(d.charAt(f).replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&"));a=a.join("");t&&(a=a.replace(new RegExp("("+t.digit+"(.*"+t.digit+")?)"),"($1)?").replace(new RegExp(t.digit,"g"),t.pattern));return new RegExp(a)},destroyEvents:function(){b.off("input keydown keyup paste drop blur focusout ".split(" ").join(".mask "))},val:function(a){var c=b.is("input")?"val":"text";if(0<arguments.length){if(b[c]()!==a)b[c](a);
    c=b}else c=b[c]();return c},calculateCaretPosition:function(a){var d=c.getMasked(),h=c.getCaret();if(a!==d){var e=b.data("mask-previus-caret-pos")||0;d=d.length;var g=a.length,f=a=0,l=0,k=0,m;for(m=h;m<d&&c.maskDigitPosMap[m];m++)f++;for(m=h-1;0<=m&&c.maskDigitPosMap[m];m--)a++;for(m=h-1;0<=m;m--)c.maskDigitPosMap[m]&&l++;for(m=e-1;0<=m;m--)c.maskDigitPosMapOld[m]&&k++;h>g?h=10*d:e>=h&&e!==g?c.maskDigitPosMapOld[h]||(e=h,h=h-(k-l)-a,c.maskDigitPosMap[h]&&(h=e)):h>e&&(h=h+(l-k)+f)}return h},behaviour:function(d){d=
		d||window.event;c.invalid=[];var e=b.data("mask-keycode");if(-1===a.inArray(e,l.byPassKeys)){e=c.getMasked();var h=c.getCaret(),g=b.data("mask-previus-value")||"";setTimeout(function(){c.setCaret(c.calculateCaretPosition(g))},a.jMaskGlobals.keyStrokeCompensation);c.val(e);c.setCaret(h);return c.callbacks(d)}},getMasked:function(a,b){var h=[],f=void 0===b?c.val():b+"",g=0,k=d.length,n=0,p=f.length,m=1,r="push",u=-1,w=0;b=[];if(e.reverse){r="unshift";m=-1;var x=0;g=k-1;n=p-1;var A=function(){return-1<
			g&&-1<n}}else x=k-1,A=function(){return g<k&&n<p};for(var z;A();){var y=d.charAt(g),v=f.charAt(n),q=l.translation[y];if(q)v.match(q.pattern)?(h[r](v),q.recursive&&(-1===u?u=g:g===x&&g!==u&&(g=u-m),x===u&&(g-=m)),g+=m):v===z?(w--,z=void 0):q.optional?(g+=m,n-=m):q.fallback?(h[r](q.fallback),g+=m,n-=m):c.invalid.push({p:n,v:v,e:q.pattern}),n+=m;else{if(!a)h[r](y);v===y?(b.push(n),n+=m):(z=y,b.push(n+w),w++);g+=m}}a=d.charAt(x);k!==p+1||l.translation[a]||h.push(a);h=h.join("");c.mapMaskdigitPositions(h,
    b,p);return h},mapMaskdigitPositions:function(a,b,d){a=e.reverse?a.length-d:0;c.maskDigitPosMap={};for(d=0;d<b.length;d++)c.maskDigitPosMap[b[d]+a]=1},callbacks:function(a){var g=c.val(),h=g!==f,k=[g,a,b,e],l=function(a,b,c){"function"===typeof e[a]&&b&&e[a].apply(this,c)};l("onChange",!0===h,k);l("onKeyPress",!0===h,k);l("onComplete",g.length===d.length,k);l("onInvalid",0<c.invalid.length,[g,a,b,c.invalid,e])}};b=a(b);var l=this,f=c.val(),k;d="function"===typeof d?d(c.val(),void 0,b,e):d;l.mask=
				d;l.options=e;l.remove=function(){var a=c.getCaret();l.options.placeholder&&b.removeAttr("placeholder");b.data("mask-maxlength")&&b.removeAttr("maxlength");c.destroyEvents();c.val(l.getCleanVal());c.setCaret(a);return b};l.getCleanVal=function(){return c.getMasked(!0)};l.getMaskedVal=function(a){return c.getMasked(!1,a)};l.init=function(g){g=g||!1;e=e||{};l.clearIfNotMatch=a.jMaskGlobals.clearIfNotMatch;l.byPassKeys=a.jMaskGlobals.byPassKeys;l.translation=a.extend({},a.jMaskGlobals.translation,e.translation);
    l=a.extend(!0,{},l,e);k=c.getRegexMask();if(g)c.events(),c.val(c.getMasked());else{e.placeholder&&b.attr("placeholder",e.placeholder);b.data("mask")&&b.attr("autocomplete","off");g=0;for(var f=!0;g<d.length;g++){var h=l.translation[d.charAt(g)];if(h&&h.recursive){f=!1;break}}f&&b.attr("maxlength",d.length).data("mask-maxlength",!0);c.destroyEvents();c.events();g=c.getCaret();c.val(c.getMasked());c.setCaret(g)}};l.init(!b.is("input"))};a.maskWatchers={};var f=function(){var b=a(this),d={},e=b.attr("data-mask");
    b.attr("data-mask-reverse")&&(d.reverse=!0);b.attr("data-mask-clearifnotmatch")&&(d.clearIfNotMatch=!0);"true"===b.attr("data-mask-selectonfocus")&&(d.selectOnFocus=!0);if(p(b,e,d))return b.data("mask",new n(this,e,d))},p=function(b,d,e){e=e||{};var c=a(b).data("mask"),f=JSON.stringify;b=a(b).val()||a(b).text();try{return"function"===typeof d&&(d=d(b)),"object"!==typeof c||f(c.options)!==f(e)||c.mask!==d}catch(w){}},k=function(a){var b=document.createElement("div");a="on"+a;var e=a in b;e||(b.setAttribute(a,
    "return;"),e="function"===typeof b[a]);return e};a.fn.mask=function(b,d){d=d||{};var e=this.selector,c=a.jMaskGlobals,f=c.watchInterval;c=d.watchInputs||c.watchInputs;var k=function(){if(p(this,b,d))return a(this).data("mask",new n(this,b,d))};a(this).each(k);e&&""!==e&&c&&(clearInterval(a.maskWatchers[e]),a.maskWatchers[e]=setInterval(function(){a(document).find(e).each(k)},f));return this};a.fn.masked=function(a){return this.data("mask").getMaskedVal(a)};a.fn.unmask=function(){clearInterval(a.maskWatchers[this.selector]);
    delete a.maskWatchers[this.selector];return this.each(function(){var b=a(this).data("mask");b&&b.remove().removeData("mask")})};a.fn.cleanVal=function(){return this.data("mask").getCleanVal()};a.applyDataMask=function(b){b=b||a.jMaskGlobals.maskElements;(b instanceof a?b:a(b)).filter(a.jMaskGlobals.dataMaskAttr).each(f)};k={maskElements:"input,td,span,div",dataMaskAttr:"*[data-mask]",dataMask:!0,watchInterval:300,watchInputs:!0,keyStrokeCompensation:10,useInput:!/Chrome\/[2-4][0-9]|SamsungBrowser/.test(window.navigator.userAgent)&&
    k("input"),watchDataMask:!1,byPassKeys:[9,16,17,18,36,37,38,39,40,91],translation:{0:{pattern:/\d/},9:{pattern:/\d/,optional:!0},"#":{pattern:/\d/,recursive:!0},A:{pattern:/[a-zA-Z0-9]/},S:{pattern:/[a-zA-Z]/}}};a.jMaskGlobals=a.jMaskGlobals||{};k=a.jMaskGlobals=a.extend(!0,{},k,a.jMaskGlobals);k.dataMask&&a.applyDataMask();setInterval(function(){a.jMaskGlobals.watchDataMask&&a.applyDataMask()},k.watchInterval)},window.jQuery,window.Zepto);
});
// jQuery Mask Plugin - End
jQuery(document).ready(function ($) {
	function validatePhone(form) {
		var phoneInput = form.find('input[name="phone"]');
		var phone = phoneInput.val().replace(/\D/g, ''); // Убираем все нецифровые символы
		var regex = /^380\d{9}$/;
		if (!regex.test(phone)) {
			phoneInput.addClass("error");
			return { isValid: false, phone: phoneInput.val() }; // Возвращаем отформатированный номер для отображения
		} else {
			phoneInput.removeClass("error");
			return { isValid: true, phone: phoneInput.val() };
		}
	}

	function validatePrivacyPolicy(form) {
		var privacyCheckbox = form.find('#privacy-policy');
		if (privacyCheckbox.length === 0) {
			return { isValid: true };
		}
		if (!privacyCheckbox.is(':checked')) {
			return { isValid: false };
		}
		return { isValid: true };
	}

	$('.form-lightbox-wrapper form.form-fields').on('submit', function (e) {
		var phoneValidation = validatePhone($(this));
		var isPhoneValid = phoneValidation.isValid;
		var phone = phoneValidation.phone;

		var privacyPolicyValidation = validatePrivacyPolicy($(this));
		var isPrivacyPolicyAccepted = privacyPolicyValidation.isValid;

		if (!isPhoneValid || !isPrivacyPolicyAccepted) {
			e.preventDefault();
			if (!isPhoneValid && !isPrivacyPolicyAccepted) {
				alert('Неправильно введено номер телефону: ' + phone + '\nПеревірялось:\n' +
					  '1. Номер має починатись з +38.\n' +
					  '2. Має містити 10 цифр після коду країни.\n\n' +
					  'Будь ласка, погодьтеся з політикою конфіденційності.');
			} else if (!isPhoneValid) {
				alert('Неправильно введено номер телефону: ' + phone + '\nПеревірялось:\n' +
					  '1. Номер має починатись з +38.\n' +
					  '2. Має містити 10 цифр після коду країни.');
			} else if (!isPrivacyPolicyAccepted) {
				alert('Будь ласка, погодьтеся з політикою конфіденційності.');
			}
		}
	});

	// Код маски телефона
	$('.form-lightbox-wrapper input[type="tel"]').mask('+38(000)000-00-00', {
		placeholder: "+38(0__)___-__-__",
		onKeyPress: function (cep, event, currentField, options) {
			var currentValue = $(currentField).val();
			if (currentValue.length >= 5 && currentValue[4] !== '0') {
				var newValue = currentValue.slice(0, 4) + '0' + currentValue.slice(4);
				$(currentField).val(newValue);
			}
		}
	});
});
// Валідація Форм - End
var relatedSwiperInstances = [];
var mainRelatedSwiperBreakpoint = 0;
var cartRelatedSwiperBreakpoint = 767.98;

function initializeOrDestroyAllRelatedSwipers() {
	try {
		var screenWidth = jQuery(window).width();

		relatedSwiperInstances.forEach(function(instanceData) {
			if (instanceData.swiper && typeof instanceData.swiper.destroy === 'function') {
				instanceData.swiper.destroy(true, true);
			}
		});
		relatedSwiperInstances = [];

		jQuery('.related').each(function() {
			var $thisRelated = jQuery(this);
			var isInCart = $thisRelated.closest('#cart-wrapper').length > 0;
			var swiperOptions = null;
			var slidesInCurrentRelated = $thisRelated.find('.swiper-slide').length;

			$thisRelated.removeClass('horizontal-scroll-mobile horizontal-scroll-mobile-cart');

			if (isInCart) {
				if (screenWidth > cartRelatedSwiperBreakpoint) {
					if (slidesInCurrentRelated > 0) {
						var slidesPerViewInCart = 2;
						swiperOptions = {
							slidesPerView: slidesPerViewInCart,
							spaceBetween: 10,
							slidesPerGroup: 1,
							loop: slidesInCurrentRelated > slidesPerViewInCart,
							navigation: {
								nextEl: $thisRelated.closest('.card-swiper-slider').find(".related-cart-btn-next")[0],
								prevEl: $thisRelated.closest('.card-swiper-slider').find(".related-cart-btn-prev")[0],
							},
							observer: true,
							observeParents: true,
						};
					}
				} else {
					if (slidesInCurrentRelated > 0) {
						$thisRelated.addClass('vertical-scroll-cart-mobile');
					}
				}
			} else {
				if (screenWidth > mainRelatedSwiperBreakpoint) {
					if (slidesInCurrentRelated > 0) {
						var slidesPerViewProductPageBase = 2;
						swiperOptions = {
							slidesPerView: slidesPerViewProductPageBase,
							spaceBetween: 10,
							slidesPerView: 2,
							slidesPerGroup: 1,
							loop: slidesInCurrentRelated > slidesPerViewProductPageBase,
							breakpoints: {
								1024: { slidesPerView: 4, spaceBetween: 20, loop: slidesInCurrentRelated > 4, slidesPerGroup: 1 },
								1440: { slidesPerView: 5, spaceBetween: 20, loop: slidesInCurrentRelated > 5, slidesPerGroup: 1 },
								
							},
							navigation: {
								nextEl: $thisRelated.closest('.card-swiper-slider').find(".related-btn-next")[0],
								prevEl: $thisRelated.closest('.card-swiper-slider').find(".related-btn-prev")[0],
							},
							observer: true,
							observeParents: true,
						};
					}
				} else {
					if (slidesInCurrentRelated > 0) {
						$thisRelated.addClass('vertical-scroll-cart-mobile');
					}
				}
			}
			if ($thisRelated.length > 0 && swiperOptions) {
				try {
					var currentSwiperInstance = new Swiper($thisRelated[0], swiperOptions);
					relatedSwiperInstances.push({element: $thisRelated[0], swiper: currentSwiperInstance});
				} catch (swiperError) {
					console.error("Error initializing Swiper for element:", $thisRelated[0], swiperError);
				}
			}
		});

	} catch (e) {
		console.error("Error in initializeOrDestroyAllRelatedSwipers:", e);
	}
}

document.addEventListener('DOMContentLoaded', function() {

	const openBtn = document.getElementById('open-how-to-pay-later-popup');
	const closeBtn = document.getElementById('close-how-to-pay-later-popup');
	const popup = document.getElementById('how-to-pay-later');


	if (openBtn && popup) {
		openBtn.addEventListener('click', function(event) {
			event.preventDefault();
			popup.classList.add('open');
		});
	}

	if (closeBtn && popup) {
		closeBtn.addEventListener('click', function() {
			popup.classList.remove('open');
		});
	}

	if (popup) {
		popup.addEventListener('click', function(event) {
			if (event.target === popup) {
				popup.classList.remove('open');
			}
		});
	}

});

const lookSwiper = new Swiper('.card-swiper-slider__gallary.look', {
	slidesPerView: 'auto',
	spaceBetween: 20,
	scrollbar: {
		el: '.swiper-scrollbar',
		draggable: true,
	},
	allowTouchMove: true,
	simulateTouch: true,
});

jQuery(document).ready(function($) {
	function loadCartUpsells() {
		$('#upsells-in-cart-container').html('<p>Завантаження...</p>');

		$.post(
			load_more_params.ajaxurl,
			{
				action: 'get_cart_upsells'
			},
			function(response) {
				console.log('Отримано відповідь від сервера:', response);

				if (response.success && response.data.html) {
					$('#upsells-in-cart-container').html(response.data.html);

					if ($('#upsells-in-cart-container .card-swiper-slider__gallary.related').length > 0) {
						console.log('Знайдено слайдер, ініціалізую Swiper...');
						new Swiper('#upsells-in-cart-container .card-swiper-slider__gallary.related', {
							slidesPerView: 'auto',
							spaceBetween: 15,
							navigation: {
								nextEl: '.related-cart-btn-next',
								prevEl: '.related-cart-btn-prev',
							},
							mousewheel: true,
							freeMode: true,
						});
					}
				} else {
					$('#upsells-in-cart-container').html('');
				}
			}
		).fail(function(jqXHR, textStatus, errorThrown) {
			console.error('AJAX-запит провалився!', textStatus, errorThrown);
		});
	}

	$(document.body).on('click', '#btnCartHeader', function() {
		loadCartUpsells();
	});

	$(document.body).on('added_to_cart', function() {
		loadCartUpsells();
	});

});


jQuery(function($) {
	if ( $('#upsells-in-cart-container').length === 0 ) {
		return;
	}

	function updateCartUpsells() {

		$('#upsells-in-cart-container').html('<p>Оновлення рекомендованих товарів...</p>');

		$.post(
			wc_add_to_cart_params.ajax_url,
			{
				action: 'get_cart_upsells'
			},
			function(response) {
				if (response.success) {
					$('#upsells-in-cart-container').html(response.data.html);
				} else {
					$('#upsells-in-cart-container').html('');
				}
			}
		);
	}

	$(document.body).on('removed_from_cart', updateCartUpsells);
	updateCartUpsells();
});

document.addEventListener('DOMContentLoaded', function () {
	const triggers = document.querySelectorAll('.open-before-birth-popup');
	const popupOverlay = document.querySelector('.before-birth-popup-overlay');
	const closeBtn = document.querySelector('.before-birth-popup-close');

	if (triggers.length > 0 && popupOverlay && closeBtn) {

		function openPopup(e) {
			e.preventDefault();
			popupOverlay.style.display = 'flex';
		}

		function closePopup() {
			popupOverlay.style.display = 'none';
		}

		triggers.forEach(function(trigger) {
			trigger.addEventListener('click', openPopup);
		});

		closeBtn.addEventListener('click', closePopup);

		popupOverlay.addEventListener('click', function (e) {
			if (e.target === popupOverlay) {
				closePopup();
			}
		});
	}
});

document.addEventListener('DOMContentLoaded', function () {
    let searchIcon = document.querySelector(".header__search");
    searchIcon.addEventListener("click", () => {
        setTimeout(() => {
            document.querySelector('.search-form__input').focus();
        }, 100); 
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const closeButton = document.querySelector('.modal-widget-close');
    if (!closeButton) {
        return;
    }
    
    closeButton.addEventListener("click", () => {
        document.querySelector('aside#sidebar').classList.remove('open');  
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const baseFilters = document.querySelectorAll(
        '.wpc-checkbox-item input, .wpc-btn, .wpc-submit'
    );

    baseFilters.forEach(el => {
        el.addEventListener('click', () => {
            setTimeout(() => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 30);
        });
    });


    const priceInputs = document.querySelectorAll('.wpc-filters-range-min, .wpc-filters-range-max');

    priceInputs.forEach(input => {
        ['change', 'input'].forEach(evt => {
            input.addEventListener(evt, () => {
                setTimeout(() => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 30);
            });
        });
    });


    document.querySelectorAll('.wpc-filter-range-form').forEach(form => {
        form.addEventListener('submit', () => {
            setTimeout(() => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 30);
        });
    });


    const sliderHandles = document.querySelectorAll('.ui-slider-handle');

    sliderHandles.forEach(handle => {
        handle.addEventListener('mouseup', () => {
            setTimeout(() => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 30);
        });
    });

});

window.addEventListener('fe:ajaxFiltered', () => console.log('WINDOW: fe:ajaxFiltered triggered'));
document.addEventListener('fe:ajaxFiltered', () => console.log('DOCUMENT: fe:ajaxFiltered triggered'));
document.body.addEventListener('fe:ajaxFiltered', () => console.log('BODY: fe:ajaxFiltered triggered'));

window.addEventListener('fe:ajaxStart', () => console.log('WINDOW: fe:ajaxStart triggered'));
document.addEventListener('fe:ajaxStart', () => console.log('DOCUMENT: fe:ajaxStart triggered'));
document.body.addEventListener('fe:ajaxStart', () => console.log('BODY: fe:ajaxStart triggered'));




