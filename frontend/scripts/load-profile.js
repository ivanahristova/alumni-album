let user_id = "";
let role = "";

window.onload = function () {
    fetch('../../../backend/controllers/auth/student-auth.php', {
        method: 'POST'
    })
        .then(response => response.json())
        .then(json => {
            if (json.status === "success") {
                role = "student";
                user_id = json.data;
                showStudentFields();
            }
        });

    fetch('../../../backend/controllers/auth/teacher-auth.php', {
        method: 'POST'
    })
        .then(response => response.json())
        .then(json => {
            if (json.status === "success") {
                role = "teacher";
                user_id = json.data;
                showTeacherFields();
            }
        });
};

function showStudentFields() {
    fetch('../../../backend/controllers/get-student.php', {
        method: 'POST'
    })
        .then(response => response.json())
        .then(json => {
            if (json.status === "success") {
                let studentInfo = json.data;
                let container = document.getElementById("info-fields");

                if (studentInfo["class"] != null) {
                    let div = document.createElement('div');
                    div.innerHTML = studentInfo["class"];
                    container.appendChild(div);
                }
                if (studentInfo["subclass"] != null) {
                    let div = document.createElement('div');
                    div.innerHTML = studentInfo["subclass"];
                    container.appendChild(div);
                }
                if (studentInfo["programme_id"] != null) {
                    let div = document.createElement('div');
                    div.innerHTML = studentInfo["programme_id"];
                    container.appendChild(div);
                }
                if (studentInfo["student_group"] != null) {
                    let div = document.createElement('div');
                    div.innerHTML = studentInfo["student_group"];
                    container.appendChild(div);
                }

                container.style.display = 'block';
            }
        });
}

function showTeacherFields() {
    fetch('../../../backend/controllers/get-teacher.php', {
        method: 'POST'
    })
        .then(response => response.json())
        .then(json => {
            if (json.status === "success") {
                let teacherInfo = json.data;
                let container = document.getElementById("info-fields");

                if (teacherInfo["title"] != null) {
                    let div = document.createElement('div');
                    div.innerHTML = teacherInfo["class"];
                    container.appendChild(div);
                }

                container.style.display = 'block';
            }
        });
}
