async function login(formValues) {
    let json = await fetch("../../../backend/controllers/login.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formValues)
    }).then(response => response.json());

    return json.status !== 'success' ? json.data : null;
}

(() => {
    let loginForm = document.getElementById('login-form');

    loginForm.addEventListener('submit', async event => {
        event.preventDefault();
        let messageElement = document.getElementById('success');
        const formValues = loginForm.querySelectorAll('input');
        const data = {};

        formValues.forEach(field => {
            data[field.name] = field.value;
        });

        let message = await login(data);

        if (message == null) {
            messageElement.innerHTML = "Успешен вход";
            messageElement.parentElement.classList.add('form-success');
            messageElement.parentElement.classList.remove('form-error');
            window.location.assign("../home");
        } else {
            messageElement.innerHTML = message;
            messageElement.parentElement.classList.add('form-error');
            messageElement.parentElement.classList.remove('form-success');
        }

        event.preventDefault();
    });
})();
