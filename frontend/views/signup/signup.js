const registrationForm = document.getElementById('register-btn');

const email = document.getElementById('email');
const password = document.getElementById('password');
const repeatedPassword = document.getElementById('repeated-password');
const role = document.getElementById('role');
const firstName = document.getElementById('first-name');
const lastName = document.getElementById('last-name');

registrationForm.addEventListener('click', (event) => {
    event.preventDefault();
    register();
});

async function register() {
    let isValid = validateFields();

    if (!isValid) {
        document.getElementById("success").innerHTML = "Неуспешна регистрация";
        return;
    }

    // let exists = await checkDB();
    // if (!exists) {
    //     document.getElementById("success").innerHTML = "Успешна регистрация";
    // } else {
    //     document.getElementById("success").innerHTML = "Съществуващ потребител";
    // }
}

function validateEmail() {
    if (checkNull(email)) {
        document.getElementById("email-error").innerHTML = "Имейлът е задължително поле";
        return false;
    } else if (!checkEmailFormat(email)) {
        document.getElementById("email-error").innerHTML = "Невалиден имейл формат";
        return false;
    } else {
        document.getElementById("email-error").innerHTML = "";
        return true;
    }
}

function validatePassword() {
    if (checkNull(password)) {
        document.getElementById("password-error").innerHTML = "Паролата е задължително поле";
        return false;
    } else if (validateStringLength(password, 6, 10)) {
        document.getElementById("password-error").innerHTML = "Паролата: от 6 до 10 символа.";
        return false;
    } else if (!checkPasswordStrength(password)) {
        document.getElementById("password-error").innerHTML = "Съдържание: малка буква, главна буква и цифра";
        return false;
    } else {
        document.getElementById("password-error").innerHTML = "";
        return true;
    }
}

function validatePasswordsMatch() {
    if (password !== repeatedPassword) {
        document.getElementById("repeated-password-error").innerHTML = "Паролата не съвпада";
        return false;
    }
    return true;
}

function validateFirstName() {
    if (checkNull(firstName)) {
        document.getElementById("first-name-error").innerHTML = "Името е задължително поле";
        return false;
    } else if (validateStringLength(firstName, 0, 255)) {
        document.getElementById("first-name-error").innerHTML = "Името трябва да бъде до 255 символа";
        return false;
    }
    return true;
}

function validateLastName() {
    if (checkNull(lastName)) {
        document.getElementById("last-name-error").innerHTML = "Фамилията е задължително поле";
        return false;
    } else if (validateStringLength(lastName, 0, 255)) {
        document.getElementById("last-name-error").innerHTML = "Фамилията трябва да бъде до 255 символа";
        return false;
    }
    return true;
}

function validateFields() {
    return validateEmail() && validatePassword() && validatePasswordsMatch() && validateFirstName() && validateLastName();
}

function checkNull(field) {
    return field.value.length === 0;
}

function validateStringLength(str, min, max) {
    const length = str.value.length;
    return length < min || length > max;
}

function checkEmailFormat(email) {
    const validEmailRegex = /^[a-zA-Z0-9]+[\w-]+@[a-zA-Z0-9]+([\w-]+\.)+[\w-]{2,4}$/;
    return validEmailRegex.test(email.value);
}

function checkPasswordStrength(password) {
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,10}$/;
    return passwordRegex.test(password.value);
}

function on_change(el) {
    if (el.id === 'student-action-input') {
        document.getElementById('student-fields').style.display = 'block';
        document.getElementById('teacher-fields').style.display = 'none';
    } else if (el.id === 'teacher-action-input') {
        document.getElementById('teacher-fields').style.display = 'block';
        document.getElementById('student-fields').style.display = 'none';
    }
}
