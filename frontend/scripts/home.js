let currentIndex = 0;
const imagesPerPage = 6;

let lastIndex = 0;

const imageContainer = document.getElementById("image-container");
const loadMoreButton = document.getElementById("load-more-button");

function loadMore() {
    const endIndex = currentIndex + imagesPerPage;
    const temp = currentIndex;

    fetch(`../../../backend/controllers/get-photos.php?start=${currentIndex}&end=${endIndex}`)
        .then(response => response.json())
        .then(images => {
            images.data.forEach(image => {
                const div = document.createElement("div");
                div.classList.add('my-container-item');

                const img = document.createElement("img");
                img.src = image.path;

                div.appendChild(img);
                imageContainer.appendChild(div);
                currentIndex++;
            });

            lastIndex = currentIndex;

            if (currentIndex === temp) {
                loadMoreButton.setAttribute("hidden", "");
            }
        });
}

loadMoreButton.addEventListener("click", loadMore);

// Show the first set of images
loadMore();
