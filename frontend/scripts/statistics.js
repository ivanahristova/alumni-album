let statistics = document.getElementById("statistics");

function addTableCaption(headline) {
    let caption = document.createElement('caption');
    caption.classList.add('table-caption');
    caption.innerHTML = headline;
    return caption;
}

function addTableCategories(categories) {
    let tr = document.createElement('tr');

    categories.forEach(category => {
        let th = document.createElement('th');
        th.classList.add('category-style');
        th.innerHTML = category;
        tr.appendChild(th);
    });

    return tr;
}

function createTableRow(elements) {
    let tr = document.createElement('tr');

    elements.forEach(element => {
        let td = document.createElement('td');
        td.innerHTML = element;
        tr.appendChild(td);
    })

    return tr;
}

function showStatistics(path, caption, categories, elementNames) {
    fetch(path)
        .then(response => response.json())
        .then(json => {
            let data = json.data;

            if (data.length === 0) {
                let element = document.getElementById("no-data");
                element.style.display = 'block';
            } else {
                statistics.style.display = 'block';

                let table = document.createElement('table');
                table.classList.add('chart');
                table.id = 'users-stats-chart';

                statistics.appendChild(table);

                table.appendChild(addTableCaption(caption));
                table.appendChild(addTableCategories(categories));

                for (let i = 0; i < data.length; i++) {
                    table.appendChild(createTableRow([data[i][elementNames[0]], data[i][elementNames[1]]]));
                }
            }
        });
}

function setTab(tab) {
    let fields = document.getElementById(tab.id);
    fields.style.display = 'block';
    tab.classList.add('active');
    statistics.innerHTML = "";

    if (tab.id === "class") {
        let path = '../../../backend/controllers/statistics/get-class-statistics.php';
        let caption = 'Регистрирани потребители от випуск';
        let categories = ['Випуск', 'Потребители'];
        let elements = ["class", "count"];
        showStatistics(path, caption, categories, elements);
    } else if (tab.id === "programme") {
        let path = '../../../backend/controllers/statistics/get-programme-statistics.php';
        let caption = 'Регистрирани потребители от специалност';
        let categories = ['Специалност', 'Потребители'];
        let elements = ["name", "count"];
        showStatistics(path, caption, categories, elements);
    } else if (tab.id === "photos") {
        let path = '../../../backend/controllers/statistics/get-photos-statistics.php';
        let caption = 'Брой снимки за випуск';
        let categories = ['Випуск', 'Снимки'];
        let elements = ["class", "count"];
        showStatistics(path, caption, categories, elements);
    }
}

function setActiveTab(event) {
    let tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => {
        let fields = document.getElementById(tab.id);
        fields.style.display = 'block';
        tab.classList.remove('active')
    });

    setTab(event.target)
}

(() => {
    let tabs = document.querySelectorAll('.tab');

    tabs.forEach(tab => {
        tab.addEventListener('click', setActiveTab)
    });

})();
