function handleFileSelectMulti(evt) {
    const files = evt.target.files;
    document.getElementById('file-preview-table').innerHTML = "";
    let i = 0, f;
    for (; f = files[i]; i++) {
        if (!f.type.match('image.*')) {
            alert("Только изображения....");
        }

        let reader = new FileReader();
        reader.onload = (function (theFile) {
            return function (e) {
                const span = document.createElement('span');
                span.innerHTML = ['<img class="img-thumbnail" src="', e.target.result,
                    '" title="', escape(theFile.name), '"/>'].join('');
                document.getElementById('file-preview-table').insertBefore(span, null);
            };
        })(f);

        reader.readAsDataURL(f);
    }
}

document.getElementById('upload-files').addEventListener('change', handleFileSelectMulti, false);


// Валидация формы
const addressFormDeliveryEms = $('.comment-form__alert');

function validAlert() {
    addressFormDeliveryEms.show();
}

const userNameInput = $('#user_name');
const userEmailInput = $('#user_email');
const descriptionInput = $('#description');
const imgObj = $("#loadImg");
const commentMessageBox = $(".comment-message-box");
const commentMessageBoxText = $(".comment-message-box .text");
const commentMessageBoxCloseButton = $('.comment-message-box .close');

$('.comment-form__button').on('click', function (e) {
    e.preventDefault();
    if (!userNameInput.val()) {
        validAlert(e);
        return;
    }

    if (!userEmailInput.val()) {
        validAlert(e);
        return;
    }

    if (!descriptionInput.val()) {
        validAlert(e);
        return;
    }

    function startLoadingAnimation() {
        imgObj.show();
    }

    function stopLoadingAnimation() {
        imgObj.hide();
    }

    addressFormDeliveryEms.hide();

    const formData = new FormData();

    $.each($("#upload-files").prop('files'), function (index, value) {
        formData.append("files[]", value);
    });

    formData.append("user_name", userNameInput.val());
    formData.append("user_email", userEmailInput.val());
    formData.append("description", descriptionInput.val());

    $.ajax({
        url: '/comments/create-comment',
        processData: false,
        contentType: false,
        data: formData,
        type: 'POST',
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

    startLoadingAnimation();
});

$(document).ready(function () {
    commentMessageBoxCloseButton.click(function () {
        commentMessageBox.hide('slow');
    });

    $(document).mouseup(function (e) {
        if (!commentMessageBox.is(e.target)
            && commentMessageBox.has(e.target).length === 0) {
            commentMessageBox.hide('slow');
        }
    });
});

$(document).ready(function () {
    $('.comment_image img').click(function () {
        $('.big-image-image').attr('src', $(this).attr('src'));
        $('body').css({"overflow": "hidden"});
        $('.big-image').show('slow');


    });

    $('.big-image .close').click(function () {
        $('.big-image').hide('slow');
        $('body').css({"overflow": "auto"});
    });

    $(document).mouseup(function (e) {
        var div = $(".big-image .content");
        if (!div.is(e.target)
            && div.has(e.target).length === 0) {
            $('.big-image').hide('slow');
            $('body').css({"overflow": "auto"});
        }
    });
});