$(document).ready(function () {
    $(".update_in_cart").click(function () {
        let id = $(this).attr("data-id");
        let count = $("#count_" + id).val();
        $.ajax({
            url: 'cart/update',
            type: 'POST',
            data: {
                id: id,
                count: count
            },
            success: function (res) {
                let responseData = JSON.parse(res);

                $('.product_in_cart_count').html(responseData.count);
                $('#product_total_price_' + id).html(responseData.product.total_price);
                $('#total_price').html(responseData.total_price);
            },
            error: function (e) {
            }
        });
    });


    $(".delete_from_cart").click(function () {
        let id = $(this).attr("data-id");
        $.ajax({
            url: 'cart/delete',
            type: 'POST',
            data: {
                id: id,
            },
            success: function (res) {
                let product = $('#product_' + id);
                product.empty();

                let responseData = JSON.parse(res);

                $('.product_in_cart_count').html(responseData.count);
                $('#total_price').html(responseData.total_price);
            },
            error: function (e) {
            }
        });
    });


    $("#coupon_active").click(function (e) {
        e.preventDefault();
        let coupon = $("input[name='coupon']").val();
        $.ajax({
            url: 'cart/coupon/add',
            type: 'POST',
            data: {
                coupon: coupon,
            },
            success: function (res) {

            },
            error: function (e) {
            }
        });
    });

    const deliveryPickUp = 1;
    const deliveryCourier = 2;
    const deliveryPickEms = 3;

    $(".delivery").click(function () {
        let deliveryType = $('input[name="delivery"]:checked').val();
        if (deliveryType == deliveryCourier){
            $('.address-form').show();
            $('#calculate-delivery').hide();
        } else if(deliveryType == deliveryPickEms) {
            $('.address-form').show();
            $('#calculate-delivery').show();
        } else {
            $('.address-form').hide();
            $('#calculate-delivery').hide();
        }
    });

    $("#calculate-delivery").click(function (e) {
        e.preventDefault();
        let deliveryType = $('input[name="delivery"]:checked').val();
        $.ajax({
            url: 'cart/calculate/delivery',
            type: 'POST',
            data: {
                id: deliveryType,
            },
            success: function (res) {

            },
            error: function (e) {
            }
        });
    });
});

function setCursorPosition(pos, e) {
    e.focus();
    if (e.setSelectionRange) e.setSelectionRange(pos, pos);
    else if (e.createTextRange) {
        var range = e.createTextRange();
        range.collapse(true);
        range.moveEnd("character", pos);
        range.moveStart("character", pos);
        range.select()
    }
}

function mask(e) {
    //console.log('mask',e);
    var matrix = this.placeholder,// .defaultValue
        i = 0,
        def = matrix.replace(/\D/g, ""),
        val = this.value.replace(/\D/g, "");
    def.length >= val.length && (val = def);
    matrix = matrix.replace(/[_\d]/g, function (a) {
        return val.charAt(i++) || "_"
    });
    this.value = matrix;
    i = matrix.lastIndexOf(val.substr(-1));
    i < matrix.length && matrix != this.placeholder ? i++ : i = matrix.indexOf("_");
    setCursorPosition(i, this)
}

window.addEventListener("DOMContentLoaded", function () {
    var input = document.querySelector("#input-tel");
    input.addEventListener("input", mask, false);
    input.focus();
    setCursorPosition(3, input);
});