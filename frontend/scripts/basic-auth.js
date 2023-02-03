window.onload = function () {
    fetch('../../../backend/controllers/basic-auth.php')
        .then(response => response.json())
        .then(data => {
            let status = data.data;

            if (status === "unauthorized") {
                window.location.assign("../login");
            }
        })
        .catch(function (error) {
            console.error("Error checking authorization: ", error);
        });
};
