function createPhoto(src) {
    let div = document.createElement("div");
    div.classList.add('photo');

    let img = document.createElement("img");
    img.src = src;

    div.append(img);

    return div;
}

(() => {
    let loadMoreButton = document.getElementById("load-more-button");
    let photos = document.getElementById("photos");
    let imagesPerPage = 6;
    let currentIndex = 0;

    loadMoreButton.addEventListener("click", () => {
        fetch(`../../../backend/controllers/get-photos.php?offset=${currentIndex}&length=${imagesPerPage}`)
            .then(response => response.json())
            .then(images => {
                images.data.forEach(image => {
                    photos.append(createPhoto(image.path));
                });

                if (images.data.length < imagesPerPage) {
                    loadMoreButton.style.display = "none";
                }

                currentIndex += images.data.length;
            });
    });

    loadMoreButton.click();
})();
