<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

$email_reply = 'onesevenpro@gmail.com';
$email_order_group = 'onesevenpro@gmail.com';

$telegram_bot_token = '6592428348:AAFPyD4uZKEJUBHaX8eYNRgKMOhrxk6_U-o';
$telegram_chat_id = '527414476';

$crm_api_key = 'NTU1ZjI3MTY4YzI2NzNhNGQ3NjMwM2NlYTRjMjRkZTZkN2RmNzZiMA';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$form_type = $_POST['form_type'] ?? '';
	$name = sanitize_text_field($_POST['name'] ?? '');
	$phone = sanitize_text_field($_POST['phone'] ?? '');

	if (!$name || !$phone) {
		wp_die("Потрібно заповнити всі поля.");
	}

	// -----------------------------
	// ОБРОБКА ТИПІВ ФОРМ
	// -----------------------------

	if ($form_type === 'one_click') {

		$product_id   = sanitize_text_field($_POST['product_id'] ?? '');
		$product_name = sanitize_text_field($_POST['product_name'] ?? '');
		$product_sku  = sanitize_text_field($_POST['product_sku'] ?? '');
		$product_url  = esc_url_raw($_POST['product_url'] ?? '');
		$product_price = sanitize_text_field($_POST['product_price'] ?? '');

		$message  = "Заявка на покупку в один клік:\n\n";
		$message .= "Сайт: " . $_SERVER['HTTP_HOST'] . "\n";
		$message .= "Ім'я: $name\n";
		$message .= "Телефон: $phone\n";
		$message .= "ID товару: $product_id\n";
		$message .= "Назва товару: $product_name\n";
		$message .= "Артикул товару: $product_sku\n";
		$message .= "Посилання на товар: $product_url\n";

		$subject = "Заявка на покупку в один клік";
		$post_title = "Заявка: $name ($product_name)";

		$crm_order_data = [
            "source_id" => 9,
            "manager_id" => 3,
            "buyer_comment" => "Замовлення в один клік.\nТовар: $product_name\nSKU: $product_sku\nURL: $product_url",
            "buyer" => [
                "full_name" => $name,
                "phone"     => $phone
            ],
            "products" => [
                [
                    "sku" => $product_sku,
                    "quantity" => 1,
                    "price" => (float)$product_price
                ]
            ]
        ];
        
        $crm_title = null;
        $crm_comment = null;
        $crm_product_data = [];

	}

	elseif ($form_type === 'call_back') {
	    
		$product_title = sanitize_text_field($_POST['product_title'] ?? '');
		$product_sku  = sanitize_text_field($_POST['product_sku'] ?? '');
		$product_url  = esc_url_raw($_POST['product_url'] ?? '');

		$message  = "Нова заявка на консультацію:\n\n";
		$message .= "Сайт: " . $_SERVER['HTTP_HOST'] . "\n";
		$message .= "Ім'я: $name\n";
		$message .= "Телефон: $phone\n";
		$message .= "Коментар: $question\n";
		$message .= "ID товару: $product_id\n";
		$message .= "Назва товару: $product_name\n";
		$message .= "Артикул товару: $product_sku\n";
		$message .= "Посилання на товар: $product_url\n";

		$subject = "Нова заявка на консультацію";
		$post_title = "Зворотний зв'язок: $name";

		$crm_title = "Заявка на консультацію від $name";
		$crm_comment = "Заявка на консультацію.\nТовар: $product_title\nURL: $product_url\nАртикул товару: $product_sku";

	}

	elseif ($form_type === 'contact_page') {

		$surname = sanitize_text_field($_POST['surname'] ?? '');
		$email   = sanitize_email($_POST['email'] ?? '');

		$message  = "Нова заявка з контактної форми:\n\n";
		$message .= "Сайт: " . $_SERVER['HTTP_HOST'] . "\n";
		$message .= "Ім'я: $name\n";
		$message .= "Прізвище: $surname\n";
		$message .= "Телефон: $phone\n";
		$message .= "Email: $email\n";

		$subject = "Нова заявка: контактна форма";
		$post_title = "Контактна форма: $name $surname";

		$crm_title = "Контактна форма: $name $surname";
		$crm_comment = "Email: $email";

	}

	else {
		wp_die("Невідомий тип форми");
	}

	// -----------------------------
	// Створення поста form_orders
	// -----------------------------

	$post_id = wp_insert_post([
		'post_title'   => $post_title,
		'post_content' => $message,
		'post_status'  => 'private',
		'post_type'    => 'form_orders'
	]);


	// -----------------------------
	// Відправка email
	// -----------------------------

	$headers = [
		'From: ' . get_bloginfo('name') . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>',
		'Reply-To: ' . $email_reply,
		'Content-Type: text/html; charset=UTF-8'
	];

	$email_message = nl2br($message);

    // REMOVE ???
    // 	wp_mail($email_order_group, $subject, $email_message, $headers);


	// -----------------------------
	// Телеграм
	// -----------------------------

	$telegramMessage = urlencode($message);
	$url = "https://api.telegram.org/bot$telegram_bot_token/sendMessage?chat_id=$telegram_chat_id&text=$telegramMessage";

    // REMOVE ???
    // 	file_get_contents($url);


	// -----------------------------
	// Відправка В CRM (KEYCRM)
	// -----------------------------

	if ($form_type === 'one_click') {

        $response = wp_remote_post("https://openapi.keycrm.app/v1/order", [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $crm_api_key
            ],
            'timeout' => 20,
            'body' => wp_json_encode($crm_order_data)
        ]);
    
        // DEBUG
        // error_log(print_r($response, true));
    
    } else {
    
        $lead_data = [
            "title"        => $crm_title,
            "source_id"    => 9,
            "pipeline_id"  => 1,
            "manager_id"   => 3,
            "contact" => [
                "full_name" => $name,
                "email"     => $email ?? '',
                "phone"     => $phone,
            ],
            "manager_comment" => $crm_comment,
            "products" => [
                [
                    "sku" => $product_sku
                ]
            ]
        ];
    
        $response = wp_remote_post("https://openapi.keycrm.app/v1/pipelines/cards", [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $crm_api_key
            ],
            'timeout' => 20,
            'body' => wp_json_encode($lead_data)
        ]);
    
        // DEBUG
        // error_log(print_r($response, true));
    }


	$referer = $_SERVER['HTTP_REFERER'] ?? '/';

    $clean_referer = strtok($referer, '?');
    
    if ($form_type === 'one_click') {
        header("Location: /thank-you/");
        exit;
    }
    
    if ($form_type === 'call_back') {
        header("Location: " . $clean_referer . "?notify=success");
        exit;
    }
    
    if ($form_type === 'contact_page') {
        header("Location: " . $clean_referer . "?notify=success");
        exit;
    }
    
    // fallback
    exit;
}
