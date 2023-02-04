const upload_form = document.getElementById("upload-form");
upload_form.addEventListener("submit", function (event) {
    event.preventDefault();

    var student_class = document.getElementById("class").value;
    var student_programme = document.getElementById("programme").value;
    var student_subclass = document.getElementById("subclass").value;
    var student_group = document.getElementById("student-group").value;

    let formData = new FormData();
    formData.append("image", event.target.elements.image.files[0]);
    formData.append("class", student_class);
    formData.append("programme", student_programme);
    formData.append("subclass", student_subclass);
    formData.append("student-group", student_group);


    fetch("../../../backend/controllers/upload-image.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {

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

    for (var i = 0; i < upload_form.elements.length; i++) {
        upload_form.elements[i].value = "";
    }
});
