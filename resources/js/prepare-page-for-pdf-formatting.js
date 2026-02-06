function prepareHtml() {
    let html = document.createElement('html');
    html.innerHTML =
        `<head>${document.head.innerHTML}</head><body>${document.getElementsByTagName('main')[0].innerHTML}</body>`;

    let navbar = html.getElementsByTagName('navbar');
    while (navbar[0]) {
        navbar[0].parentNode.removeChild(navbar[0]);
    }
    let nav = html.getElementsByTagName('nav');
    while (nav[0]) {
        nav[0].parentNode.removeChild(nav[0]);
    }
    let form = html.getElementsByTagName('form');
    while (form[0]) {
        form[0].parentNode.removeChild(form[0]);
    }
    let link = html.getElementsByTagName('a');
    while (link[0]) {
        link[0].parentNode.removeChild(link[0]);
    }
    let table = html.getElementsByTagName('table');
    while (table[0]) {
        table[0].parentNode.removeChild(table[0]);
    }
    html.style.fontFamily = 'Dejavu Serif';
    html.style.fontSize = '14px';
    return html;
}

function extractDataFromRows(rows) {
    let data = new Array();
    rows.forEach(element => {
        data.push(element.innerHTML);
        element.innerHTML = "";
    });
    return data;
}

$('#formatButton').on('click', async function (e) {
    e.preventDefault();
    let html = prepareHtml();
    let table = document.getElementsByTagName("table")[0];
    let tmpTable = table;
    let rows = $(table).children("tbody").children("tr").children("td").get();
    let data = extractDataFromRows(rows);
    let rowsHtml = new Array();

    while (table.children[1].children[0]) {
        table.children[1].children[0].parentNode.removeChild(table.children[1].children[0]);
    }

    let htmlData = {
        pageHtml: html.outerHTML,
        tableHtml: table.outerHTML,
        tableRowHtml: rows.map(el => el.outerHTML).join("\n"),
        data: data,
        insertAfterElement: "<" + table.parentElement.tagName.toLowerCase() + " id=\"" + table.parentElement.id + "\">"
    }

    const response = await fetch('/format/pdf/cache-pdf-data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify(htmlData)
    });

    table = tmpTable;

    if (response.ok) {
        const responseData = await response.json();

        console.log(responseData);

        $("#cacheKey").val(responseData.cacheKey);

        document.getElementById("formatForm").submit();
    }
    else {
        console.log("Cache error");
    }

});