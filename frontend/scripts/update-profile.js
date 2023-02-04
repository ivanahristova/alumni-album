function updateProfile(form, file) {
//    event.preventDefault();

    const data = {};
    const fields = form.querySelectorAll('input');

    fields.forEach(field => {
        data[field.name] = field.value;
    });


    fetch('../../../backend/controllers/' + file, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {

            let messageElement = document.getElementById("success");
            document.getElementById("success").innerHTML = data.message;
            if (data.status === "success") {
                messageElement.innerHTML = "Успешна промяна";
                messageElement.parentElement.classList.add('form-success');
                messageElement.parentElement.classList.remove('form-error');
            } else {
                messageElement.innerHTML = "Неуспешна промяна";
                messageElement.parentElement.classList.add('form-error');
                messageElement.parentElement.classList.remove('form-success');
            }
        });
}

(() => {
    let updateEmailForm = document.getElementById('update-email-form');
    let updatePasswordForm = document.getElementById('update-password-form');

    updateEmailForm.addEventListener("submit", event => {
        event.preventDefault();
        updateProfile(updateEmailForm, "update-email.php");
    });
    updatePasswordForm.addEventListener("submit", event => {
        event.preventDefault();
        updateProfile(updatePasswordForm, "update-password.php");
    });
})();
