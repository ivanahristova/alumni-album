function createPhoto(src, alt) {
    let div = document.createElement('div');
    div.classList.add('photo');

    let img = document.createElement('img');
    img.src = src;
    img.alt = alt;

    div.append(img);

    return div;
}

function loadTemplatePhotos(photosContainer) {
    fetch('../../../backend/controllers/get-template-photos.php')
        .then(response => response.json())
        .then(json => {
            let element = document.getElementById("no-images");
            let photos = json.data;

            if (photos.length === 0) {
                element.removeAttribute("hidden");
            } else {
                element.setAttribute("hidden", "");

                for (let i = 0; i < photos.length; i++) {
                    photosContainer.append(createPhoto(photos[i].src, photos[i].alt));
                }
            }
        });
}

(() => {
    let photosContainer = document.getElementById("photos");
    loadTemplatePhotos(photosContainer);
})();
