window.addEventListener('DOMContentLoaded', () => {

    signUpValidation();

});


if (document.getElementById('user_login')) {
    document.getElementById('user_login').placeholder = 'Email';
}
if (document.getElementById('user_pass')) {
    document.getElementById('user_pass').placeholder = 'Password';

}
if (document.getElementById('wp-submit')) {
    document.getElementById('wp-submit').value = 'Sign in';

}


// SIGNUP VALIDATION STARTS

function signUpValidation() {

    const signUpbtn = document.querySelector('button.woocommerce-form-register__submit');
    const emailInput = document.getElementById('reg_email');
    const firstnameInput = document.getElementById('reg_firstname');
    const lastnameInput = document.getElementById('reg_lastname');
    const dateInput = document.getElementById('reg_date');
    const passwordInput = document.getElementById('reg_password');
    const countryInput = document.getElementById('reg_country');
    const cityInput = document.getElementById('reg_city');
    const maleInput = document.getElementById('male');
    const femaleInput = document.getElementById('female');
    const gender = document.getElementById('gender');
    const regFormwc = document.getElementById('audioteria_reg_custom');


//check for valid email

    const isEmailValid = (email) => {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    };

//check password

    const isPasswordSecure = (password) => {
        const re = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
        return re.test(password);
    };

// check character limit

    const isBetween = (length, min, max) => length < min || length > max ? false : true;

    let showHtml = document.querySelector('.woocommerce-notices-wrapper');
    const showError = (input, message) => {
        // get the form-field element
        const formField = input;
        // add the error class
        // formField.nextElementSibling.classList.remove('d-none');
        formField.classList.add('error-border');

        // show the error message


        if (showHtml) {
            showHtml.innerHTML = '';
        }

        const errorEl = '<ul class="woocommerce-error" role="alert"><li>' + message + '</li></ul>';
        if (showHtml) {
            (showHtml).innerHTML = errorEl;
        }

        return '';

    };

    const showSuccess = (input, message) => {
        // get the form-field element
        const formField = input;
        // add the error class
        formField.classList.add('error-success');


        // show the error message
        if (showHtml) {
            (showHtml).innerHTML = '';
        }

    };


//check email

    const checkEmail = () => {
        //  event.preventDefault();
        let valid = false;
        const email = emailInput.value.trim();
        if (email === '') {
            showError(emailInput, 'Email cannot be blank.');
        } else if (!isEmailValid(email)) {
            showError(emailInput, 'Email is not valid.')
        } else {
            showSuccess(emailInput);
            valid = true;
        }
        return valid;
    }

//checkfirstname

    const checkFirstname = () => {

        let valid = false;
        const min = 3,
            max = 25;
        const firstname = firstnameInput.value.trim();


        if (firstname === '') {
            showError(firstnameInput, 'FirstName Fields are Required');

        } else if (!isBetween(firstname.length, min, max)) {
            showError(firstnameInput, `FirstName must be between ${min} and ${max} characters.`)
        } else {
            showSuccess(firstnameInput);

            valid = true;
        }
        return valid;
    }


// //checklastname


    const checkLastname = () => {

        let valid = false;
        const min = 3,
            max = 25;
        const lastname = lastnameInput.value.trim();

        if (lastname === '') {
            showError(lastnameInput, 'LastName Fields are Required');

        } else if (!isBetween(lastname.length, min, max)) {
            showError(lastnameInput, `LastName must be between ${min} and ${max} characters.`)
        } else {
            showSuccess(lastnameInput);

            valid = true;
        }
        return valid;
    }


// //check date


    const checkDate = () => {

        let valid = false;

        const date = dateInput.value;

        if (date === '') {
            showError(dateInput, 'Date Field are Required');

        } else {
            showSuccess(dateInput);

            valid = true;
        }
        return valid;
    }


// //check country


    const checkCountry = () => {

        let valid = false;
        const min = 3,
            max = 25;
        const country = countryInput.value.trim();

        if (country === '') {
            showError(countryInput, 'Country Fields are Required');

        } else if (!isBetween(country.length, min, max)) {
            showError(countryInput, `Country must be between ${min} and ${max} characters.`)
        } else {
            showSuccess(countryInput);

            valid = true;
        }
        return valid;
    }


//check city


    const checkCity = () => {

        let valid = false;
        const min = 3,
            max = 25;
        const city = cityInput.value.trim();

        if (city === '') {
            showError(cityInput, 'City Fields are Required');

        } else if (!isBetween(city.length, min, max)) {
            showError(cityInput, `City must be between ${min} and ${max} characters.`)
        } else {
            showSuccess(cityInput);

            valid = true;
        }
        return valid;
    }

//check gender


    const checkGender = () => {

        let valid = false;

        const male = maleInput.value;
        const female = femaleInput.value;

        if (male === false || female === false) {

            showError(gender, 'Gender Field is Required');

        } else {
            showSuccess(gender);

            valid = true;
        }
        return valid;
    }

//check password

    const checkPassword = () => {

        let valid = false;

        const password = passwordInput.value.trim();

        if (password === '') {
            showError(passwordInput, 'Password cannot be blank.');
        } else if (!isPasswordSecure(password)) {
            showError(passwordInput, 'Password must has at least 8 characters that include at least 1 lowercase character, 1 uppercase characters, 1 number, and 1 special character in (!@#$%^&*)');
        } else {
            showSuccess(passwordInput);
            valid = true;
        }

        return valid;
    };

    if (signUpbtn) {
        signUpbtn.addEventListener('click', function (event) {


            let isCorrectEmail = checkEmail();
            let isCorrectFirstName = checkFirstname();
            let isCorrectLastName = checkLastname();
            let isCorrectDate = checkDate();
            let isCorrectCountry = checkCountry();
            let isCorrectCity = checkCity();
            let isCorrectGender = checkGender();
            let isCorrectPassword = checkPassword();


            // isAllValid = isCorrectEmail;
            isAllValid = isCorrectEmail && isCorrectFirstName && isCorrectLastName && isCorrectDate && isCorrectCountry && isCorrectCity && isCorrectGender && isCorrectPassword;

            if (!isAllValid) {
                event.preventDefault();

            }

        });
    }


}

//Add spacing below password reset input to show password strength
const passwordStrength = document.querySelector('.woocommerce-password-strength');
if (passwordStrength) {
    passwordStrength.style.paddingBottom = '37px';

}





