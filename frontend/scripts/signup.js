function unsetTab(tab) {
    let fields = document.getElementById(tab.id + '-fields');
    fields.style.display = 'none';
    fields.querySelectorAll('*').forEach(field => field.removeAttribute('required'));
    tab.classList.remove('active');
}

function setTab(tab) {
    let fields = document.getElementById(tab.id + '-fields');
    fields.style.display = 'block';

    if (tab.id !== 'teacher') {
        fields.querySelectorAll('input, select').forEach(field => field.setAttribute('required', ''));
    }

    tab.classList.add('active');
}

function setActiveTab(event) {
    let tabs = document.querySelectorAll('.tab');
    tabs.forEach(unsetTab);
    setTab(event.target)
    document.querySelector('.tab-bar').style.borderBottom = '1px solid black';
}

function removeUnusedFields(tab, formValues) {
    let fields = document.getElementById(tab.id + '-fields');
    fields.querySelectorAll('input, select').forEach(field => delete formValues[field.name]);
}

async function signup(formValues, id) {
    let json = await fetch(`../../../backend/controllers/${id}-signup.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formValues)
    }).then(response => response.json());

    return json.status !== 'success' ? json.data : null;
}

async function signupHandler(event) {
    event.preventDefault();
    let inputs = document.querySelectorAll('.form-body input, select');
    let messageElement = document.getElementById('success');
    let inactiveTabs = document.querySelectorAll('.tab:not(.active)');
    let formValues = {};

    inputs.forEach(input => formValues[input.name] = input.value.trim());
    inactiveTabs.forEach(tab => removeUnusedFields(tab, formValues));

    let message = await signup(formValues, document.querySelector('.tab.active').id);

    if (message == null) {
        messageElement.innerHTML = "Успешна регистрация";
        messageElement.parentElement.classList.add('form-success');
        messageElement.parentElement.classList.remove('form-error');
    } else {
        messageElement.innerHTML = message;
        messageElement.parentElement.classList.add('form-error');
        messageElement.parentElement.classList.remove('form-success');
    }

    event.preventDefault();
}

(() => {
    let tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => tab.addEventListener('click', setActiveTab));

    let signupButton = document.getElementById('signup-button');
    signupButton.addEventListener('click', signupHandler);

    document.getElementById('class').max = new Date().getFullYear();
    document.getElementById('student').click();
})();
