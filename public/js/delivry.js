// Jquery

// le total du panier + prix de livraison
function calculateTotalTTC(){
    // recup la valeur de l'input selectionnée
    let selectedId = $("input[name=drone]:checked").val();

    // recup la valeur total du panier en le transformant en float 
    let ttcToShow = parseFloat($('#totalTtc')[0].attributes.value.value);

    // recup le prix de la livraison
    let price = document.getElementById(selectedId + "-price").value;

    // j'ajoute le prix de la livraison à la valeur total du panier en le transformant en float auparavant donnant le totalTTC
    ttcToShow += parseFloat(price);

    // si un input a bien été selectioné et si le total TTC est > 0
    if(selectedId && ttcToShow>0){
        // recup premier element HTML dont l'id est totalTtc en lui inserrant le texte ttcToShow
        $('#totalTtc')[0].innerText = ttcToShow + '€';
    }
}

// lorsque l'input avec l'attribut name = drone change alors la fonction recalcule le total TTC 
function onChangeDeliveryOption(){
    $("input[name=drone]").on('change', function(){
        calculateTotalTTC();
    });
}

// si la page est chargée, alors j'appelle la fonction onChangeDeliveryOption
$(document).ready(function(){
    onChangeDeliveryOption();
});