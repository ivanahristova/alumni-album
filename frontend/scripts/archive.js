export * from '../lib/jszip/dist/jszip.min.js';

function createPhoto(src, alt) {
    let div = document.createElement('div');
    div.classList.add('photo');

    let a = document.createElement('a');
    a.setAttribute('download', 'photo');
    a.href = src;

    let img = document.createElement('img');
    img.src = src;
    img.alt = alt;

    a.append(img);
    div.append(a);

    return div;
}

function loadButton(base64) {
    let a = document.getElementById('download-button');
    a.href = "data:application/zip;base64," + base64;
    a.innerHTML = "Изтегли всичко";
    a.removeAttribute('hidden');
}

async function prepareZip(photos) {
    let zip = new JSZip().folder("images");

    for (let photo of photos) {
        let filename = photo.path.substring(photo.path.lastIndexOf('/') + 1);
        let blob = await fetch(photo.path).then(res => res.blob());
        zip.file(filename, blob);
    }

    zip.generateAsync({type: "base64"}).then(loadButton);
}

function loadPhotos(photos, container) {
    photos.forEach(photo => {
        container.append(createPhoto(photo.path, photo.description));
    });
}

function loadPage(json) {
    let container = document.getElementById('photos');
    let downloadButton = document.getElementById('download-button');
    let message = document.getElementById('no-images');
    let photos = json.data;

    container.innerHTML = '';

    if (photos.length === 0) {
        message.removeAttribute('hidden');
        downloadButton.setAttribute('hidden', '');
    } else {
        prepareZip(photos);
        loadPhotos(photos, container);
        message.setAttribute('hidden', '');
        downloadButton.removeAttribute('hidden');
    }
}

(() => {
    let searchForm = document.getElementById('search-form');

    searchForm.addEventListener('submit', event => {
        let fields = searchForm.querySelectorAll('input, select');
        let data = {};

        fields.forEach(field => {
            data[field.name] = field.value;
        });

        fetch('../../../backend/controllers/search-photos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(loadPage);

        event.preventDefault();
    });
})();
