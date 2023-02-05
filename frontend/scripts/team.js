let container = document.getElementById("image-container");

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

function loadTemplatePhotos() {
    fetch('../../../backend/controllers/get-template-photos.php')
        .then(response => response.json())
        .then(data => {
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
}

loadTemplatePhotos();
