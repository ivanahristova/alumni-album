let container = document.getElementById("photos");

const search_form = document.getElementById('search-form');

function createPhoto(src, alt) {
    let div = document.createElement('div');
    div.classList.add('photo');

    let img = document.createElement('img');
    img.src = src;
    img.alt = alt;

    div.append(img);

    return div;
}

search_form.addEventListener("submit", async function (event) {
    event.preventDefault();

    const fields = search_form.querySelectorAll('input, select');
    let data = {};
    let description = "";
    let programmeName = "";

    data["programme_code"] = document.getElementById("programme").value;
    await fetch('../../../backend/controllers/get-programme-name.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(json => {
            programmeName = json.data;
        });

    data = {};
    fields.forEach(field => {
        if (field.name === "programme") {
            data[field.name] = field.value;
            description = description + " " + programmeName;
        } else {
            data[field.name] = field.value;
            description = description + " " + field.value;
        }
    });

    await fetch('../../../backend/controllers/search-photos.php', {
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
                messageElement.innerHTML = "Резултати от " + description;
                messageElement.parentElement.classList.add('form-success');
                messageElement.parentElement.classList.remove('form-error');
            } else {
                messageElement.innerHTML = "Неуспешна заявка";
                messageElement.parentElement.classList.add('form-error');
                messageElement.parentElement.classList.remove('form-success');
                return;
            }

            let element = document.getElementById("no-images");
            let photos = data.data;

            container.innerHTML = "";

            if (photos.length === 0) {
                element.removeAttribute("hidden");
            } else {
                element.setAttribute("hidden", "");

                for (let i = 0; i < photos.length; i++) {
                    container.append(createPhoto(photos[i].path, photos[i].description));
                }
            }
        });

    for (let i = 0; i < search_form.elements.length; i++) {
        search_form.elements[i].value = "";
    }
})
