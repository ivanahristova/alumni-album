(() => {
    let logoutButton = document.getElementById('logout-button');

    logoutButton.addEventListener('click', () => {
        fetch("../../../backend/controllers/logout.php")
            .then(response => {
                if (response.ok) {
                    window.location.assign("../login")
                }
            })
    });
})();
