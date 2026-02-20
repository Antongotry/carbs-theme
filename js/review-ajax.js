jQuery(document).ready(function($) {
    $('form[id^="review-form-"]').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('action', 'submit_review');

        var messageContainer = $(this).find('.message-container');

        $.ajax({
            url: review_ajax_obj.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json', // Ожидаем JSON ответ
            success: function(response) {
                console.log(response); // Логирование ответа
                if (response.success) {
                    messageContainer.html('<p class="success">Ваш відгук успішно відправлено.</p>');
                } else {
                    messageContainer.html('<p class="error">' + response.message + '</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ошибка:', jqXHR.responseText); // Логирование ошибки
                messageContainer.html('<p class="error">Помилка відправки форми: ' + textStatus + ' ' + errorThrown + '</p>');
            }
        });
    });
});
