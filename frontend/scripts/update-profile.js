const update_form = document.getElementById('update-form');
update_form.addEventListener("submit", function (event) {
    event.preventDefault();

    let email = document.getElementById("email").value;
    let old_password = document.getElementById("new_password").value;
    let new_password = document.getElementById("old_password").value;

    let formData = new FormData();
    formData.append("email", email);
    formData.append("new_password", old_password);
    formData.append("old_password", new_password);

    fetch('../../../backend/controllers/update-profile.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(json => {
            let messageElement = document.getElementById("success");
            document.getElementById("success").innerHTML = json.message;
            if (json.status === "success") {
                messageElement.innerHTML = "Успешна промяна";
                messageElement.parentElement.classList.add('form-success');
                messageElement.parentElement.classList.remove('form-error');
            } else {
                messageElement.innerHTML = "Неуспешна промяна";
                messageElement.parentElement.classList.add('form-error');
                messageElement.parentElement.classList.remove('form-success');
            }
        });
})
