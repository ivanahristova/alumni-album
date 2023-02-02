const search_form = document.getElementById('search-form');
search_form.addEventListener("submit", function (event) {
    event.preventDefault();

    const data = {};
    const fields = search_form.querySelectorAll('input, select');

    fields.forEach(field => {
        data[field.name] = field.value;
    });

    fetch('../../../backend/controllers/search-photo.php', {
        method: 'POST',
        headers: {
            'Content-Type' : 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data =>{

        let messageElement = document.getElementById("success");
        document.getElementById("success").innerHTML = data.message;
        if (data.status === "success") {
            messageElement.parentElement.classList.add('form-success');
            messageElement.parentElement.classList.remove('form-error');
        } else {
            messageElement.parentElement.classList.add('form-error');
            messageElement.parentElement.classList.remove('form-success');
        }

    });
})
