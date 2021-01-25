const commentMessageBox = $(".comment-message-box");
const commentMessageBoxText = $(".comment-message-box .text");


$('.button-subscribe').click(function(e) {
    e.preventDefault();
    $.ajax({
        url: '/subscribe',
        type: 'POST',
        data: {
            'email': $('#email').val(),
        },
        success: function (res) {
            let response = JSON.parse(res)
            if (response.error) {
                commentMessageBoxText.text(response.error);
            } else {
                commentMessageBoxText.text("Отзыв успешно отправлен и находится на модерации");
            }
            commentMessageBox.show('slow');
            stopLoadingAnimation();
        },
        error: function () {
            commentMessageBoxText.text("Произошла ошибка при попытке отправления отзыва. Пожалуйста, попробуйте повторить запрос.");
            commentMessageBox.show('slow');
            stopLoadingAnimation();
        },
    });
});
