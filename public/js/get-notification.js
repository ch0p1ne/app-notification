var orders = []
$(document).ready(() => {
    console.log(order_queue);
    console.log(provider_name);
    console.log(user_position);
    

    getLocalStorage();
    var row = ''

    //Requete initial
    updateRequest("http://192.168.0.100:8090/get-notification/command")
    // Requette répété à interval regulier apres 7.5 seconde
    setInterval(updateRequest, 7500, "http://192.168.0.100:8090/get-notification/command")

})

function updateRequest(url) {
    $.ajax({
        url: url,
        method: "GET",
        data: {},
        success: function (data) {
            data.forEach(e => {

            });
            update(data)
        }
    });
    console.log("fin de la quete ajax, mise a jour du contenue ...");

    var row = ""

    orders.forEach(order => {
        row += "<table>"
        row += "<caption> Commande numéro :" + orders.indexOf(order) + "</caption>"
        row += "<thead> " +
                "<tr>" +
                    "<td> Nom des produit </td>" +
                    "<td> Detail de commande </td>" +
                    "<td> Quantité </td>" +
                    "<td> ETAT </td>" +
                "</tr>" +
            "</thead>"
        order.forEach(product => {
            product.forEach(item => {
                row += '<tr>' +
                    '<td>' + item.product_name + '</td>'+
                    '<td>' + "item." + '</td>'+
                    '<td>' + item.product_qte + '</td>'+
                    '<td>' + "En cours de livraison" + '</td>'
                    + '</tr>'
            });
        });
        row += "</table>"
    });
    

    var tableContainer = document.querySelector('.table-container')
    tableContainer.innerHTML = ''
    tableContainer.innerHTML = row
}


function update(data) {
    var suborders = data

    // si il y a de nouvelles commandes alors ...
    if (data.length != 0) {
        orders.push(suborders)
        console.log(orders)
    }

    localStorage.orders = JSON.stringify(orders)
}


function getLocalStorage() {
    if (localStorage.orders) {
        orders = JSON.parse(localStorage.orders);
        console.log("Recuperation dans le localstorage de 'orders'")
    }
    else {
        localStorage.setItem('orders', JSON.stringify(orders))
        console.log("Creation dans le localstorage de 'orders'")
    }
}