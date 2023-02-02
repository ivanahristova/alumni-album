document.getElementById('logout-button').addEventListener('click', async () => {
    let json = await fetch("../../../backend/controllers/logout.php").then(response => response.json());
    window.location.assign("../login");
})
