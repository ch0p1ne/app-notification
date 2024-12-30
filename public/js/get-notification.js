var orders = []
$(document).ready(() => {
    var updateFrequency = 10500;
    console.log(order_queue);
    console.log(provider_name);
    console.log(user_position);
    

    getLocalStorage();
    var row = ''
    const urlJax = "http://127.0.0.1:8090/get-notification/command"

    //Requete initial
    updateRequest(urlJax)
    // Requette répété à interval regulier apres 7.5 seconde
    setInterval(updateRequest, updateFrequency, urlJax)

})




$.ajaxSetup({
    beforeSend : function(xhr) {
        xhr.setRequestHeader('Order-queue', order_queue) ;
        xhr.setRequestHeader('Provider-name', provider_name);
    }
});

// Requette ajax appeler a intervalle regulier pour recuperer les messages
// RabbitMQ
function updateRequest(url) {
    $.ajax({
        url: url,
        method: "GET",
        data: {},
        success: function (data) {
            update(data)
        }
    });
    console.log("fin de la quete ajax, mise a jour du contenue ...");

    var row = ""
    

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

    localStorage.setItem(provider_name, JSON.stringify(orders));
}


function getLocalStorage() {
    if (localStorage.getItem(provider_name)) {
        orders = JSON.parse(localStorage.getItem(provider_name));
        console.log("Recuperation dans le localstorage de : " + provider_name)
    }
    else {
        localStorage.setItem(provider_name, JSON.stringify(orders))
        console.log("Creation dans le localstorage de : " +  provider_name)
    }
}