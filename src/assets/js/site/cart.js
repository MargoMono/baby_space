$(document).ready(function () {
    $('.update_in_cart').click(function () {
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

    let inputPaymentPickUp = $('input[id=input-payment-pick-up]');
    let inputPaymentYandex = $('input[id=input-payment-yandex]');

    let addressFormDeliveryCourier = $('.address-form-delivery-courier');
    let addressFormDeliveryEms = $('.address-form-delivery-ems');

    $(".delivery").click(function () {
        let deliveryType = Number($('input[name="delivery"]:checked').val());
        if (deliveryType === deliveryCourier) {
            addressFormDeliveryCourier.show();
            addressFormDeliveryEms.hide();
            blockPaymentMethodForPickUp();
        } else if (deliveryType === deliveryPickEms) {
            addressFormDeliveryCourier.hide();
            addressFormDeliveryEms.show();
            blockPaymentMethodForPickUp();
        } else if (deliveryType === deliveryPickUp) {
            addressFormDeliveryCourier.hide();
            addressFormDeliveryEms.hide();
            unblockPaymentMethodForPickUp();
        }
    });

    function blockPaymentMethodForPickUp() {
        inputPaymentPickUp.attr("disabled", true);
        inputPaymentYandex.prop("checked", true);
    }

    function unblockPaymentMethodForPickUp() {
        inputPaymentPickUp.attr("disabled", false);
        inputPaymentPickUp.prop("checked", true);
    }

    let deliveryEmsWarningMessage = $('.delivery-ems-warning');
    let deliveryEmsDangerMessage = $('.delivery-ems-danger');
    let deliveryEmsPrice = $('.delivery-ems-price');
    let deliveryEmsPriceText = $('#delivery-ems-price-text');
    let deliveryEmsPriceTextConvert = $('#delivery-ems-price-text-convert');

    function showCalculateDeliveryWarningMessage() {
        deliveryEmsWarningMessage.show();
        deliveryEmsDangerMessage.hide();
    }

    function showCalculateDeliveryErrorMessage() {
        deliveryEmsWarningMessage.hide();
        deliveryEmsDangerMessage.show();
    }

    function hideWarningAndErrorMessage() {
        deliveryEmsWarningMessage.hide();
        deliveryEmsDangerMessage.hide();
    }

    $("#calculate-delivery").click(function (e) {
        e.preventDefault();
        let country = $('select[name="country-ems"]').val();
        let address = $('input[name="address-ems"]').val();
        let postcode = $('input[name="postcode-ems"]').val();
        let weight = $('input[name="weight-ems"]').val();

        if (country === "") {
            showCalculateDeliveryWarningMessage();
            return;
        }

        if (address === "") {
            showCalculateDeliveryWarningMessage();
            return;
        }

        if (postcode === "") {
            showCalculateDeliveryWarningMessage();
            return;
        }

        hideWarningAndErrorMessage();


        $.ajax({
            url: 'cart/calculate/delivery',
            type: 'POST',
            data: {
                country: country,
                address: address,
                postcode: postcode,
                weight: weight,
            },
            success: function (res) {
                deliveryEmsPrice.show();
                let responseData = JSON.parse(res);
                deliveryEmsPriceText.html(responseData.tariff);
                deliveryEmsPriceTextConvert.html(responseData.tariff_convert);
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