let container = document.getElementById("info-fields");
let user_id = "";
let role = "";

async function getUserEssentials() {
    await fetch('../../../backend/controllers/auth/student-auth.php')
        .then(response => response.json())
        .then(json => {
            if (json.status === "success") {
                role = "student";
            } else if (json.status === "failure") {
                role = "teacher";
            }
            user_id = json.data;
        });
}

(async () => {
    await getUserEssentials();

    if (role === "student") {
        await showStudentFields();
    } else {
        await showTeacherFields();
    }
})();

async function showStudentFields() {
    let json = await fetch('../../../backend/controllers/get-student.php').then(response => response.json());
    await extractStudentData(json);
}

function appendToContainer(container, title, value) {
    let div = document.createElement('div');
    div.classList.add('data-field');

    let h5 = document.createElement('h5');
    h5.classList.add('field-title');
    h5.innerHTML = title + ':';
    div.appendChild(h5);

    let p = document.createElement('p');
    p.innerHTML = value;
    div.appendChild(p);

    container.appendChild(div);
}

function formatName(name) {
    let formattedName = name.toLowerCase();
    return formattedName.charAt(0).toUpperCase() + formattedName.slice(1);
}

function appendFormattedNameToContainer(container, firstName, lastName) {
    firstName = formatName(firstName);
    lastName = formatName(lastName);
    appendToContainer(container, 'Име', firstName + ' ' + lastName);
}

async function extractStudentData(json) {
    if (json.status === "success") {
        for (const field of json.data) {
            appendFormattedNameToContainer(container, field["first_name"], field["last_name"]);
            appendToContainer(container, "Факултетен номер", field["faculty_number"]);

            appendToContainer(container, "Випуск", field["class"]);

            let programmeName = await getProgrammeNameByProgrammeId(field["programme_id"]);
            appendToContainer(container, 'Специалност', programmeName);

            appendToContainer(container, 'Поток', field["subclass"]);
            appendToContainer(container, 'Група', field["student_group"]);
        }
    }
}

async function getProgrammeNameByProgrammeId(id) {
    let data = {};
    data["programme_id"] = id;
    let programmeName = "";
    await fetch('../../../backend/controllers/get-programme-name-by-id.php', {
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

    return programmeName;
}

async function showTeacherFields() {
    let json = await fetch('../../../backend/controllers/get-teacher.php').then(response => response.json());
    extractTeacherData(json);
}

function extractTeacherData(json) {
    if (json.status === "success") {
        for (const field of json.data) {
            appendToContainer(container, 'Титла', field["title"]);
            appendFormattedNameToContainer(container, field["first_name"], field["last_name"]);
        }
    }
}
