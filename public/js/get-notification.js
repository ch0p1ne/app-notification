$(document).ready( () => {

    $(".command-container").click( () => {
        row
    }

    );
    $.ajax({
        url: "http://localhost:8090/get-notification/command",
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
    var order = []
    var subOrder = []

    if(localStorage.order) {
        order = JSON.parse(localStorage.order) 
    }

    if(date.length != 0) {
        for (const i in data) {
            order.push(data[i])
        }
    }
    console.log(order)

    localStorage.order = JSON.stringify(order) 
    console.log(order)
}
