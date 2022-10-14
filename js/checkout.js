const submitCouponForm = (event) => {
    event.preventDefault();

    let coupon_code = document.querySelector('input[id="abstract_coupon_code"]').value;

    let form = document.querySelector('form.checkout_coupon');

    form.querySelector('input[name="coupon_code"]').value = coupon_code;
    document.querySelector('form.checkout_coupon').submit();
}


document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('button[name="abstract_apply_coupon"]').addEventListener('click', submitCouponForm);
})