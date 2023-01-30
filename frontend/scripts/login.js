(() => {
    let loginForm = document.getElementById('login-form');

    loginForm.addEventListener('submit', event => {
        const formFields = loginForm.querySelectorAll('input');
        const data = {};

        formFields.forEach(field => {
            data[field.name] = field.value;
        });

        fetch("../../../backend/controllers/login.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        }).then(response => console.log(response));

        event.preventDefault();
    });
})();
