let container = document.getElementById("image-container");

const search_form = document.getElementById('search-form');

function createImgForm(src, alt) {
    let div = document.createElement('div');
    div.classList.add('img');

    let img = document.createElement('img');
    img.classList.add('image-view');
    img.src = src;
    img.alt = alt;

    div.appendChild(img);
    return div;
}

search_form.addEventListener("submit", async function (event) {
    event.preventDefault();

    const data = {};
    const fields = search_form.querySelectorAll('input, select');

    fields.forEach(field => {
        data[field.name] = field.value;
    });

    await fetch('../../../backend/controllers/search-photo.php', {
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
                messageElement.innerHTML = "Успешна заявка";
                messageElement.parentElement.classList.add('form-success');
                messageElement.parentElement.classList.remove('form-error');
            } else {
                messageElement.innerHTML = "Неуспешна заявка";
                messageElement.parentElement.classList.add('form-error');
                messageElement.parentElement.classList.remove('form-success');
                return;
            }

            let photos = data.data;

            if (photos.length === 0) {
                let element = document.getElementById("no-images");
                element.style.display = 'block';
            } else {
                for (let i = 0; i < photos.length; i++) {
                    container.appendChild(createImgForm(photos[i].src, photos[i].alt));
                }
            }

        });
})
