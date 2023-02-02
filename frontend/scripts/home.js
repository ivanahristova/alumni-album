let currentIndex = 0;
const imagesPerPage = 5;

let lastIndex = 0;
let hasMorePhotos = true;

const imageContainer = document.getElementById("image-container");
const loadMoreButton = document.getElementById("load-more-button");

function loadMore() {
    const endIndex = currentIndex + imagesPerPage;
    if (hasMorePhotos === false) {
        let element = document.getElementById("no-more-images");
        element.style.display = 'block';
    }

    const temp = currentIndex;
    fetch('../../../backend/controllers/photos/get-photos.php?start={currentIndex}&end={endIndex}')
        .then(response => response.json())
        .then(images => {
            images.forEach(function(image) {
                const img = document.createElement("img");
                img.src = image.src;
                imageContainer.appendChild(img);
            });

            currentIndex = endIndex;
            lastIndex = currentIndex;
        });

    if (currentIndex === temp) {
        hasMorePhotos = false;
    }
}

loadMoreButton.addEventListener("click", function() {
    loadMore();
});

// Show the first set of images
loadMore();
