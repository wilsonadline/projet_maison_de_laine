// $.ajax({
//     type: "GET",
//     url: "/articles/ajout/{$id}",
//     success: function(data){
//         console.log(data);
//         // document.location.href = "http://localhost:8000/";   

//  let stock = $(".stock-2").text();
//  console.log((stock));

// var stock = document.getElementsByClassName("stock");
//  let stock = 
// for ( var a = s ; s< stock.length ; s++){
//     stock[s].addEventListener("click" , function(){
//         console.log("ok");
//     })
// }


function sendStock(id)
{
    let stock = $(".stockValue-"+id).val();
    
    $.ajax({
        type: "POST",
        url: "/admin/articles/list",
        data :stock, 
        success: function(data){
            console.log(data);
            // document.location.href = "http://localhost:8000/";   
                           }
    });
 console.log(stock);   
}

