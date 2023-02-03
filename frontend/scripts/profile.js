(() => {
    let role;
    // check if student
    // check if teacher

    // show fields for logged in role
    let fields = document.getElementById(role + '-fields'); // fields is null
    fields.style.display = 'block';
})

();

// something along the lines of
function getStudentRole() {
    fetch('../../../backend/controllers/student-auth.php')
        .then(response => response.json())
        .then(data => {
            let status = data.data;

            if (status === "authorized") {
                return "student";
            }
        })
        .catch(function (error) {
            console.error("Error checking authorization: ", error);
        });
    return false;
}
