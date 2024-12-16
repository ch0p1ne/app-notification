var order = []
$(document).ready( () => {
    
    getLocalStorage();

    $(".command-container").click( () => {
        row
    }

    );
    $.ajax({
        url: "http://192.168.0.100:8090/get-notification/command",
        method: "GET",
        data: {},
        success: function (data) {
            data.forEach(e => {
                
            });
            update(data)
        }
    });
    console.log("fin de la quete ajax");

})


async function update(data) {
    var subOrder = data  

    // si il y a de nouvelles commandes alors ...
    if(data.length != 0) {
        order.push(subOrder)
        console.log(order)
    }

    localStorage.order = JSON.stringify(order) 
}


function getLocalStorage() {
    if(localStorage.order) {
        order = JSON.parse(localStorage.order) ;
        console.log("Recuperation dans le localstorage de 'order'")
    }
    else {
        localStorage.setItem('order', JSON.stringify(order))
        console.log("Creation dans le localstorage de 'order'")
    }
}