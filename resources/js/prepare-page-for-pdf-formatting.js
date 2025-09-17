function prepareHtml() {
    let html = document.createElement('html');
    html.innerHTML =
        `<head>${document.head.innerHTML}</head><body>${document.getElementsByTagName('main')[0].innerHTML}</body>`;

    let navbar = html.getElementsByTagName('navbar');
    while (navbar[0]) {
        navbar[0].parentNode.removeChild(navbar[0]);
    }
    let nav = html.getElementsByTagName('nav');
    while(nav[0]){
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
    while(table[0]){
        table[0].parentNode.removeChild(table[0]);
    }
    html.style.fontFamily = 'Dejavu Serif';
    html.style.fontSize = '14px';
    return html;
}

$('#formatButton').on('click', function (e) {
    e.preventDefault();
    let html = prepareHtml();
    $('#pageHtml').val(html.outerHTML);
    $('#submitForm').trigger('submit');
});