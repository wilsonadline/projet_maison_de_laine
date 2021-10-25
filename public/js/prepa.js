var btn = document.querySelectorAll('.btn_categorie');
var zoomCat = document.querySelectorAll('.box_vignette_cat');
// var zoomCat = document.querySelectorAll('.box_vignette_cat');

for( var i = 0 ; i< btn.length; i++){
    for( var i = 0 ; i< zoomCat.length; i++){
        btn[i].addEventListener("mouseover", function( event ) 
        {
            event.target.style="transform: scale(1.20); transition : all 0.5s;"; 
            
        })  

btn[i].addEventListener("mouseout", function( event ) 
{
    event.target.style="transform: scale(1.05); transition : all 0.5s"; 
});
    }
}
// var tricot = document.querySelector('#tricot');
// var zoomTricot = document.querySelector('#box_vignette_tricot');

// tricot.addEventListener("mouseover", function () 
//     {
//         zoomTricot.style="transform: scale(1.05); transition : all 0.5s;"; 
//     })
  

// tricot.addEventListener("mouseout", function () 
//     {
//         zoomTricot.style="transform: scale(0.95); transition : all 0.5s"; 
//     })


// var broderie = document.querySelector('#broderie');
// var zoomBroderie = document.querySelector('#box_vignette_broderie');

// broderie.addEventListener("mouseover", function () 
//     {
//         zoomBroderie.style="transform: scale(1.05); transition : all 0.5s;"; 
//     })
    

// broderie.addEventListener("mouseout", function () 
//     {
//         zoomBroderie.style="transform: scale(0.95); transition : all 0.5s"; 
//     })

// var mercerie = document.querySelector('#mercerie');
// var zoomMercerie = document.querySelector('#box_vignette_prod_mercerie');

// mercerie.addEventListener("mouseover", function () 
//     {
//         zoomMercerie.style="transform: scale(1.05); transition : all 0.5s;"; 
//     })
    

// mercerie.addEventListener("mouseout", function () 
//     {
//         zoomMercerie.style="transform: scale(0.95); transition : all 0.5s"; 
//     })


// var indienne = document.querySelector('#indienne');
// var zoomIndienne = document.querySelector('#box_option_indienne');

// indienne.addEventListener("mouseover", function () 
//     {
//         zoomIndienne.style="transform: scale(1.05); transition : all 0.5s;"; 
//     })
    

// indienne.addEventListener("mouseout", function () 
//     {
//         zoomIndienne.style="transform: scale(0.95); transition : all 0.5s"; 
//     })

// var wax = document.querySelector('#wax');
// var zoomWax = document.querySelector('#box_option_wax');

// wax.addEventListener("mouseover", function () 
//     {
//         zoomWax.style="transform: scale(1.05); transition : all 0.5s;"; 
//     })
    

// wax.addEventListener("mouseout", function () 
//     {
//         zoomWax.style="transform: scale(0.95); transition : all 0.5s"; 
//     })

// var kimono = document.querySelector('#kimono');
// var zoomKimono = document.querySelector('#box_option_kimono');

// kimono.addEventListener("mouseover", function () 
//     {
//         zoomKimono.style="transform: scale(1.05); transition : all 0.5s;"; 
//     })
    

// kimono.addEventListener("mouseout", function () 
//     {
//         zoomKimono.style="transform: scale(0.95); transition : all 0.5s"; 
//     })
    






// var c = a;
// console.log(c);
// var a = b;
// console.log("voici la valeur affecté à a " + a);
// var b = c;
// console.log("voici la valeur affecté à b " + b);

// for(var i = 0; i < ){

// }

