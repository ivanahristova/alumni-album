window.addEventListener('load', () => {
    fetch('../../../backend/controllers/auth/basic-auth.php')
        .then(response => {
            if (!response.ok) {
                window.location.assign("../login");
            }
        })
        .catch(error => {
            console.error("could not run authorization check:", error);
        });
});
