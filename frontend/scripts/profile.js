import "./basic-auth";

var user_id = "";
var table_name = "";
window.onload = function () {
    fetch('../../backend/controllers/auth/student-auth.php', {
        method: 'POST'
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                //show student
                table_name = "student";
            } else {
                //show teacher
                table_name = "teacher";
            }
            user_id = data.data;

        });
};

const update_form = document.getElementById('update-form');
update_form.addEventListener("submit", function (event) {
    event.preventDefault();

    var student_class = document.getElementById("class").value;
    var student_programme = document.getElementById("programme").value;
    var student_subclass = document.getElementById("subclass").value;
    var student_group = document.getElementById("student-group").value;

    let formData = new FormData();
    formData.append("class", student_class);
    formData.append("programme", student_programme);
    formData.append("subclass", student_subclass);
    formData.append("student-group", student_group);
    formData.append("user_id", user_id);
    formData.append("table_name", table_name);
    fetch('../../../backend/controllers/update-profile.php', {
        method: 'POST',
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
})
